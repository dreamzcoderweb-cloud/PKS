<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\AlternateUnit;
use App\Models\Dealer;
use App\Repositories\Interfaces\SaleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleService
{
    protected $saleRepository;

    public function __construct(SaleRepositoryInterface $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    /**
     * Retrieve all sales depending on user role.
     */
    public function getSalesForUser($user, ?string $from = null, ?string $to = null, ?string $branchId = null, ?string $saletype = null): Collection
    {
        $effectiveBranchId = $user->role === 'admin' ? $branchId : ($user->branch_id ?? $branchId);

        $query = $user->role === 'admin'
            ? $this->saleRepository->all()
            : $this->saleRepository->findForUser($user->getOwnerId());

        return $this->applyFilters($query, $from, $to, $effectiveBranchId, $saletype);
    }

    /**
     * Retrieve a specific sale if authorized.
     */
    public function getSaleDetails($user, int $id): Sale
    {
        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            throw new ModelNotFoundException('Sale not found.');
        }

        if ($user->role !== 'admin') {
            if ((int)$sale->created_by !== (int)$user->getOwnerId()) {
                throw new AuthorizationException('You are not authorized to view this sale.');
            }
            if ($user->branch_id !== null && (string)$sale->branch_id !== (string)$user->branch_id) {
                throw new AuthorizationException('You are not authorized to view this sale.');
            }
        }

        return $sale;
    }

    /**
     * Create a sale with its details, images, and deduct stock.
     */
    public function createSale($user, array $data): Sale
    {
        if ($user->role !== 'admin' && !empty($user->branch_id)) {
            $data['branch_id'] = $user->branch_id;
        } else {
            $data['branch_id'] = $data['branch_id'] ?? $user->branch_id;
        }

        $dealerId = $this->resolveDealerId($user, $data['branch_id'], $data);

        $uploadedImages = [];
        try {
            return DB::transaction(function () use ($user, $data, $dealerId, &$uploadedImages) {
                // 1. Process and upload images
                if (isset($data['sale_images'])) {
                    $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('sales') : public_path('sales');
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    foreach ($data['sale_images'] as $image) {
                        $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move($targetDir, $filename);
                        $uploadedImages[] = 'sales/' . $filename;
                    }
                }

                $saleTypeInput = $data['saletype'] ?? 0;
                if (is_string($saleTypeInput) && !is_numeric($saleTypeInput)) {
                    $saleTypeInput = strtolower(trim($saleTypeInput)) === 'decorticate' ? 1 : 0;
                } else {
                    $saleTypeInput = (int) $saleTypeInput;
                }

                // 2. Create Sale Master record
                $saleData = [
                    'sale_id' => (string) Str::uuid(),
                    'branch_id' => $data['branch_id'],
                    'dealer_id' => $dealerId,
                    'vehicle_id' => $data['vehicle_id'],
                    'saletype' => $saleTypeInput,
                    'invoice_number' => $data['invoice_number'],
                    'driver_name' => $data['driver_name'],
                    'driver_number' => $data['driver_number'],
                    'sale_date' => $data['sale_date'],
                    'sale_images' => $uploadedImages,
                    'created_by' => $user->getOwnerId(),
                ];

                $sale = $this->saleRepository->create($saleData);

                // 3. Process Sale Details & Validate / Deduct Stock
                if (isset($data['details'])) {
                    foreach ($data['details'] as $detail) {
                        // Lock the stock row to prevent race conditions during read-modify-write
                        $stock = Stock::where('id', $detail['stock_id'])->lockForUpdate()->firstOrFail();

                        // Validate primary units stock
                        if ($stock->units < $detail['unit_value']) {
                            throw ValidationException::withMessages([
                                'details' => ["Sufficient stock is not available for stock '{$stock->stock_name}'. Available: {$stock->units}, Requested: {$detail['unit_value']}."]
                            ]);
                        }

                        // Validate alternate units stock if specified
                        // if (isset($detail['alternate_unit_value']) && $detail['alternate_unit_value'] !== null) {
                        //     if ($stock->mt < $detail['alternate_unit_value']) {
                        //         throw ValidationException::withMessages([
                        //             'details' => ["Sufficient alternate stock is not available for stock '{$stock->stock_name}'. Available: {$stock->mt}, Requested: {$detail['alternate_unit_value']}."]
                        //         ]);
                        //     }
                        // }

                        // Deduct stock levels atomically
                        $stock->decrement('units', $detail['unit_value']);
                        if (isset($detail['alternate_unit_value']) && $detail['alternate_unit_value'] !== null) {
                            $stock->decrement('mt', $detail['alternate_unit_value']);
                        }

                        // Create Sale Detail record
                        $saleDetail = $sale->details()->create([
                            'stock_id' => $detail['stock_id'],
                            'lot_number' => $detail['lot_number'],
                            'unit_value' => $detail['unit_value'],
                            'unit_id' => $detail['unit_id'],
                            'alternate_unit_value' => $detail['alternate_unit_value'] ?? null,
                            'alternate_unit_id' => $detail['alternate_unit_id'] ?? null,
                            'rate' => $detail['rate'] ?? null,
                        ]);

                        // Fetch Unit Names for movements log
                        $unit = Unit::find($detail['unit_id']);
                        $unitName = $unit ? $unit->unit : 'Units';

                        // Write Stock Movement Log for primary unit deduction (negative = outgoing)
                        StockMovement::create([
                            'stock_id' => $stock->id,
                            'sale_id' => $sale->id,
                            'quantity' =>  $detail['unit_value'],
                            'unit' => $unitName,
                            'movement_type' => 'sale',
                            'transaction_date' => $data['sale_date'],
                            'user_id' => $user->getOwnerId(),
                        ]);

                        // Write Stock Movement Log for alternate unit deduction (negative = outgoing)
                        if (isset($detail['alternate_unit_value']) && $detail['alternate_unit_value'] !== null && isset($detail['alternate_unit_id'])) {
                            $alterUnit = AlternateUnit::find($detail['alternate_unit_id']);
                            $alterUnitName = $alterUnit ? $alterUnit->alter_unit : 'Alt Units';

                            StockMovement::create([
                                'stock_id' => $stock->id,
                                'sale_id' => $sale->id,
                                'quantity' =>  $detail['alternate_unit_value'],
                                'unit' => $alterUnitName,
                                'movement_type' => 'sale',
                                'transaction_date' => $data['sale_date'],
                                'user_id' => $user->getOwnerId(),
                            ]);
                        }
                    }
                }

                return $sale->load(['branch', 'dealer', 'vehicle', 'user', 'details.stock', 'details.unit', 'details.alternateUnit']);
            });
        } catch (\Exception $e) {
            // Delete newly uploaded images on transaction rollback to avoid orphan files
            foreach ($uploadedImages as $image) {
                if (app()->runningUnitTests()) {
                    Storage::disk('public')->delete($image);
                } else {
                    $filePath = public_path($image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
            throw $e;
        }
    }

    /**
     * Update a sale (Admin only).
     */
    public function updateSale($user, int $id, array $data): Sale
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException('Only admins are authorized to update sales.');
        }

        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            throw new ModelNotFoundException('Sale not found.');
        }

        $newUploadedImages = [];
        $oldImages = $sale->sale_images ?? [];

        try {
            return DB::transaction(function () use ($sale, $data, $user, &$newUploadedImages, $oldImages) {
                // 1. Revert previous stock deductions for old details
                foreach ($sale->details as $oldDetail) {
                    $stock = Stock::where('id', $oldDetail->stock_id)->lockForUpdate()->first();
                    if ($stock) {
                        $stock->increment('units', $oldDetail->unit_value);
                        if ($oldDetail->alternate_unit_value !== null) {
                            $stock->increment('mt', $oldDetail->alternate_unit_value);
                        }
                    }
                }

                // Delete old stock movements and details
                StockMovement::where('sale_id', $sale->id)->delete();
                $sale->details()->delete();

                // 2. Handle image uploads if provided
                $saleImages = $oldImages;
                if (isset($data['sale_images'])) {
                    $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('sales') : public_path('sales');
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    $newImagesList = [];
                    foreach ($data['sale_images'] as $image) {
                        $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move($targetDir, $filename);
                        $newImagesList[] = 'sales/' . $filename;
                        $newUploadedImages[] = 'sales/' . $filename;
                    }
                    $saleImages = $newImagesList;
                }

                $saleTypeInput = $data['saletype'] ?? $sale->saletype ?? 0;
                if (is_string($saleTypeInput) && !is_numeric($saleTypeInput)) {
                    $saleTypeInput = strtolower(trim($saleTypeInput)) === 'decorticate' ? 1 : 0;
                } else {
                    $saleTypeInput = (int) $saleTypeInput;
                }

                // 3. Update Sale Master
                $dealerId = $this->resolveDealerId($user, $data['branch_id'], $data);

                $saleData = [
                    'branch_id' => $data['branch_id'],
                    'dealer_id' => $dealerId,
                    'vehicle_id' => $data['vehicle_id'],
                    'saletype' => $saleTypeInput,
                    'invoice_number' => $data['invoice_number'],
                    'driver_name' => $data['driver_name'],
                    'driver_number' => $data['driver_number'],
                    'sale_date' => $data['sale_date'],
                    'sale_images' => $saleImages,
                ];

                $sale = $this->saleRepository->update($sale, $saleData);

                // 4. Process new details & validate / deduct stock
                if (isset($data['details'])) {
                    foreach ($data['details'] as $detail) {
                        // Lock the stock row to prevent race conditions during read-modify-write
                        $stock = Stock::where('id', $detail['stock_id'])->lockForUpdate()->firstOrFail();

                        // Validate primary units stock
                        if ($stock->units < $detail['unit_value']) {
                            throw ValidationException::withMessages([
                                'details' => ["Sufficient stock is not available for stock '{$stock->stock_name}'. Available: {$stock->units}, Requested: {$detail['unit_value']}."]
                            ]);
                        }

                        // Validate alternate units stock
                        // if (isset($detail['alternate_unit_value']) && $detail['alternate_unit_value'] !== null) {
                        //     if ($stock->mt < $detail['alternate_unit_value']) {
                        //         throw ValidationException::withMessages([
                        //             'details' => ["Sufficient alternate stock is not available for stock '{$stock->stock_name}'. Available: {$stock->mt}, Requested: {$detail['alternate_unit_value']}."]
                        //         ]);
                        //     }
                        // }

                        // Deduct stock atomically
                        $stock->decrement('units', $detail['unit_value']);
                        if (isset($detail['alternate_unit_value']) && $detail['alternate_unit_value'] !== null) {
                            $stock->decrement('mt', $detail['alternate_unit_value']);
                        }

                        // Save detail
                        $sale->details()->create([
                            'stock_id' => $detail['stock_id'],
                            'lot_number' => $detail['lot_number'],
                            'unit_value' => $detail['unit_value'],
                            'unit_id' => $detail['unit_id'],
                            'alternate_unit_value' => $detail['alternate_unit_value'] ?? null,
                            'alternate_unit_id' => $detail['alternate_unit_id'] ?? null,
                            'rate' => $detail['rate'] ?? null,
                        ]);

                        // Fetch Unit Names
                        $unit = Unit::find($detail['unit_id']);
                        $unitName = $unit ? $unit->unit : 'Units';

                        // Log primary unit movement (negative = outgoing)
                        StockMovement::create([
                            'stock_id' => $stock->id,
                            'sale_id' => $sale->id,
                            'quantity' =>  $detail['unit_value'],
                            'unit' => $unitName,
                            'movement_type' => 'sale',
                            'transaction_date' => $data['sale_date'],
                            'user_id' => $user->getOwnerId(),
                        ]);

                        // Log alternate unit movement (negative = outgoing)
                        if (isset($detail['alternate_unit_value']) && $detail['alternate_unit_value'] !== null && isset($detail['alternate_unit_id'])) {
                            $alterUnit = AlternateUnit::find($detail['alternate_unit_id']);
                            $alterUnitName = $alterUnit ? $alterUnit->alter_unit : 'Alt Units';

                            StockMovement::create([
                                'stock_id' => $stock->id,
                                'sale_id' => $sale->id,
                                'quantity' =>  $detail['alternate_unit_value'],
                                'unit' => $alterUnitName,
                                'movement_type' => 'sale',
                                'transaction_date' => $data['sale_date'],
                                'user_id' => $user->getOwnerId(),
                            ]);
                        }
                    }
                }

                // If images were updated, delete the old images since transaction succeeded
                if (isset($data['sale_images'])) {
                    foreach ($oldImages as $oldImage) {
                        if (app()->runningUnitTests()) {
                            Storage::disk('public')->delete($oldImage);
                        } else {
                            $oldPath = public_path($oldImage);
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }
                    }
                }

                return $sale->load(['branch', 'dealer', 'vehicle', 'user', 'details.stock', 'details.unit', 'details.alternateUnit']);
            });
        } catch (\Exception $e) {
            // Delete newly uploaded images on transaction failure
            foreach ($newUploadedImages as $image) {
                if (app()->runningUnitTests()) {
                    Storage::disk('public')->delete($image);
                } else {
                    $filePath = public_path($image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
            throw $e;
        }
    }

    /**
     * Delete a sale.
     */
    public function deleteSale($user, int $id, bool $force = false): void
    {
        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            throw new ModelNotFoundException('Sale not found.');
        }

        if ($user->role !== 'admin') {
            if ((int)$sale->created_by !== (int)$user->getOwnerId()) {
                throw new AuthorizationException('You are not authorized to delete this sale.');
            }
            if ($user->branch_id !== null && (string)$sale->branch_id !== (string)$user->branch_id) {
                throw new AuthorizationException('You are not authorized to delete this sale.');
            }
        }

        DB::transaction(function () use ($sale, $force) {
            // 1. Revert stock levels
            foreach ($sale->details as $detail) {
                $stock = Stock::where('id', $detail->stock_id)->lockForUpdate()->first();
                if ($stock) {
                    $stock->increment('units', $detail->unit_value);
                    if ($detail->alternate_unit_value !== null) {
                        $stock->increment('mt', $detail->alternate_unit_value);
                    }
                }
            }

            // 2. Revert/Delete stock movements associated with this sale
            StockMovement::where('sale_id', $sale->id)->delete();

            if ($force) {
                // Delete images from disk for permanent deletion
                foreach ($sale->sale_images ?? [] as $image) {
                    if (app()->runningUnitTests()) {
                        Storage::disk('public')->delete($image);
                    } else {
                        $filePath = public_path($image);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }

                // Delete details and master record permanently
                $sale->details()->delete();
                $sale->forceDelete();
            } else {
                // Soft delete
                $sale->delete();
            }
        });
    }

    protected function applyFilters(Collection $query, ?string $from = null, ?string $to = null, ?string $branchId = null, ?string $saletype = null): Collection
    {
        $filtered = $query;

        if ($from !== null && $from !== '') {
            $fromDate = $this->parseDate($from);
            if ($fromDate) {
                $fromDate = $fromDate->startOfDay();
                $filtered = $filtered->filter(function ($item) use ($fromDate) {
                    $itemDate = $item->created_at ?? $item->sale_date;
                    return $itemDate && \Carbon\Carbon::parse($itemDate)->gte($fromDate);
                });
            }
        }

        if ($to !== null && $to !== '') {
            $toDate = $this->parseDate($to);
            if ($toDate) {
                $toDate = $toDate->endOfDay();
                $filtered = $filtered->filter(function ($item) use ($toDate) {
                    $itemDate = $item->created_at ?? $item->sale_date;
                    return $itemDate && \Carbon\Carbon::parse($itemDate)->lte($toDate);
                });
            }
        }

        if ($branchId !== null && $branchId !== '') {
            $filtered = $filtered->filter(function ($item) use ($branchId) {
                return (string) ($item->branch_id ?? '') === (string) $branchId;
            });
        }

        if ($saletype !== null && $saletype !== '') {
            $targetType = null;
            if (is_numeric($saletype)) {
                $targetType = (int) $saletype;
            } else {
                $normalized = strtolower(trim($saletype));
                if ($normalized === 'sale') {
                    $targetType = 0;
                } elseif ($normalized === 'decorticate') {
                    $targetType = 1;
                }
            }

            if ($targetType !== null) {
                $filtered = $filtered->filter(function ($item) use ($targetType) {
                    return (int) ($item->saletype ?? 0) === $targetType;
                });
            }
        }

        return $filtered->values();
    }

    protected function parseDate(string $date): ?\Carbon\Carbon
    {
        try {
            if (str_contains($date, '/')) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $date);
            }
            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
                return \Carbon\Carbon::createFromFormat('d-m-Y', $date);
            }
            return \Carbon\Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Generate PDF for a specific sale (mimicking Gatepass layout).
     */
    public function generateGatepassPdf($user, int $saleId)
    {
        $sale = $this->getSaleDetails($user, $saleId);

        // Map Sale details to match the exact structure expected by pdf.gatepass view
        $gatepass = new \stdClass();
        $gatepass->gatepass_number = $sale->invoice_number;
        //$gatepass->gatepass_type = 'outward';
        //$gatepass->movement_type = 'sale';
        $gatepass->gatepass_date = $sale->sale_date;
        $gatepass->branch = $sale->branch;
        $gatepass->status = 'completed';
        $gatepass->dealer = $sale->dealer;
        $gatepass->customer = null;
        $gatepass->sale = $sale;
        $gatepass->purchase = null;
        $gatepass->transporter = null;
        $gatepass->vehicle = $sale->vehicle;
        $gatepass->driver_name = $sale->driver_name;
        $gatepass->driver_number = $sale->driver_number;
        $gatepass->remarks = null;
        $gatepass->user = $sale->user;
        $gatepass->details = $sale->details;

        return Pdf::loadView('pdf.gatepass', ['gatepass' => $gatepass]);
    }

    protected function resolveDealerId($user, $branchId, array $data): int
    {
        $dealerId = $data['dealer_id'] ?? null;
        $dealerName = $data['dealer_name'] ?? null;

        if (!empty($dealerId)) {
            $dealer = Dealer::where('id', $dealerId)->first();
            if (!$dealer || (string)$dealer->branch_id !== (string)$branchId) {
                throw ValidationException::withMessages([
                    'dealer_id' => ['The selected dealer does not belong to the specified branch.']
                ]);
            }
            return (int) $dealer->id;
        }

        if (!empty($dealerName)) {
            $dealerName = trim($dealerName);

            $existing = Dealer::where('branch_id', $branchId)
                ->where(function ($q) use ($dealerName) {
                    $q->where('name', $dealerName)
                      ->orWhere(DB::raw('LOWER(name)'), strtolower($dealerName));
                })->first();

            if ($existing) {
                return (int) $existing->id;
            }

            $lastCode = (int) Dealer::selectRaw('MAX(CAST(dealer_code AS UNSIGNED)) as max_code')->value('max_code');
            $newDealer = Dealer::create([
                'dealer_id' => (string) Str::uuid(),
                'dealer_code' => (string) ($lastCode + 1),
                'branch_id' => $branchId,
                'name' => $dealerName,
                'business_name' => $data['business_name'] ?? $dealerName,
                'contact_number' => $data['contact_number'] ?? '',
                'address' => $data['address'] ?? '',
                'status' => 1,
                'created_by' => $user->getOwnerId(),
            ]);

            return (int) $newDealer->id;
        }

        throw ValidationException::withMessages([
            'dealer_id' => ['Either dealer_id or dealer_name is required.']
        ]);
    }
}
