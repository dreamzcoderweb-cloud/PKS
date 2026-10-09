<?php

namespace App\Services;

use App\Models\AlternateUnit;
use App\Models\Dealer;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vehicle;
use App\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Repositories\Interfaces\StockRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseService
{
    protected $purchaseRepository;
    protected $stockRepository;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository, ?StockRepositoryInterface $stockRepository = null)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->stockRepository = $stockRepository ?? app(StockRepositoryInterface::class);
    }

    /**
     * Retrieve all purchases depending on user role.
     */
    public function getPurchasesForUser($user, ?string $from = null, ?string $to = null, ?string $branchId = null, ?string $dealer = null): Collection
    {
        $effectiveBranchId = $user->role === 'admin' ? $branchId : ($user->branch_id ?? $branchId);

        $query = $user->role === 'admin'
            ? $this->purchaseRepository->all()
            : $this->purchaseRepository->findForUser($user->getOwnerId());

        return $this->applyFilters($query, $from, $to, $effectiveBranchId, $dealer);
    }

    /**
     * Retrieve a specific purchase if authorized.
     */
    public function getPurchaseDetails($user, int $id): Purchase
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            throw new ModelNotFoundException('Purchase not found.');
        }

        if ($user->role !== 'admin') {
            if ((int)$purchase->created_by !== (int)$user->getOwnerId()) {
                throw new AuthorizationException('You are not authorized to view this purchase.');
            }
            if ($user->branch_id !== null && (string)$purchase->branch_id !== (string)$user->branch_id) {
                throw new AuthorizationException('You are not authorized to view this purchase.');
            }
        }

        return $purchase;
    }

    /**
     * Create a purchase with its details and images.
     */
    public function createPurchase($user, array $data): Purchase
    {
        if ($user->role !== 'admin' && !empty($user->branch_id)) {
            $data['branch_id'] = $user->branch_id;
        } else {
            $data['branch_id'] = $data['branch_id'] ?? $user->branch_id;
        }

        $dealerId = $this->resolveDealerId($user, $data['branch_id'], $data);
        $vehicleId = $this->resolveVehicleId($data['vehicle_id'] ?? null, $data['vehicle_number'] ?? null);

        return DB::transaction(function () use ($user, $data, $dealerId, $vehicleId) {
            $storedImages = [];
            if (isset($data['purchase_images'])) {
                $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('purchases') : public_path('purchases');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                foreach ($data['purchase_images'] as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($targetDir, $filename);
                    $storedImages[] = 'purchases/' . $filename;
                }
            }

            $purchaseDate = isset($data['purchase_date']) || isset($data['date'])
                ? $this->parseDate($data['purchase_date'] ?? $data['date'])
                : now();

            $purchaseData = [
                'purchase_id' => (string) Str::uuid(),
                'branch_id' => $data['branch_id'],
                'dealer_id' => $dealerId,
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $vehicleId,
                'driver_number' => $data['driver_number'],
                'purchase_date' => $purchaseDate ? $purchaseDate->format('Y-m-d H:i:s') : now(),
                'purchase_images' => $storedImages,
                'created_by' => $user->getOwnerId(),
            ];

            $purchase = $this->purchaseRepository->create($purchaseData);

            if (isset($data['details'])) {
                foreach ($data['details'] as $detail) {
                    $brandName = $detail['brand_name'] ?? null;
                    $stockName = $detail['stock_name'] ?? null;

                    if (!$brandName || !$stockName) {
                        continue;
                    }

                    $existingStock = Stock::where('brand_name', $brandName)
                        ->where('stock_name', $stockName)
                        ->first();

                    if ($existingStock) {
                        continue;
                    }

                    $unitId = Unit::where('unit', $detail['unit_type'] ?? null)->value('unit_id');
                    $alternateUnitId = AlternateUnit::where('alter_unit', $detail['alter_unit_type'] ?? null)->value('alter_unit_id');

                    $this->stockRepository->create([
                        'stock_id' => (string) Str::uuid(),
                        'brand_name' => $brandName,
                        'stock_name' => $stockName,
                        'lott_number' => $detail['lot_number'] ?? $data['lot_number'] ?? null,
                        'units' => isset($detail['unit_value']) ? (float) $detail['unit_value'] : 0,
                        'mt' => isset($detail['alter_unit_value']) ? (float) $detail['alter_unit_value'] : 0,
                        'stock_code' => $this->generateUniqueStockCode(),
                        'branch_id' => $data['branch_id'],
                        'unit_id' => $unitId,
                        'alter_unit_id' => $alternateUnitId,
                        'unit_value' => isset($detail['unit_value']) ? (float) $detail['unit_value'] : null,
                        'alter_unit_value' => isset($detail['alter_unit_value']) ? (float) $detail['alter_unit_value'] : null,
                        'rate' => isset($detail['rate']) ? (float) $detail['rate'] : null,
                        'rate_stock' => isset($detail['rate']) ? (float) $detail['rate'] : null,
                        'created_by' => $user->getOwnerId(),
                    ]);
                }

                $purchase->details()->createMany($data['details']);
            }

            return $purchase->load(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details']);
        });
    }

    /**
     * Update a purchase (Admin only).
     */
    public function updatePurchase($user, int $id, array $data): Purchase
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException('Only admins are authorized to update purchases.');
        }

        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            throw new ModelNotFoundException('Purchase not found.');
        }

        $dealerId = $this->resolveDealerId($user, $data['branch_id'], $data);
        $vehicleId = $this->resolveVehicleId($data['vehicle_id'] ?? null, $data['vehicle_number'] ?? null);

            $existingPurchaseDate = $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date) : ($purchase->created_at ? \Carbon\Carbon::parse($purchase->created_at) : null);
            $purchaseDate = isset($data['purchase_date']) || isset($data['date'])
                ? $this->parseDate($data['purchase_date'] ?? $data['date'], $existingPurchaseDate)
                : null;

            $purchaseData = [
                'branch_id' => $data['branch_id'],
                'dealer_id' => $dealerId,
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $vehicleId,
                'driver_number' => $data['driver_number'],
            ];

            if ($purchaseDate) {
                $purchaseData['purchase_date'] = $purchaseDate->format('Y-m-d H:i:s');
            }

            // Handle images update if provided
            if (isset($data['purchase_images'])) {
                // Delete old images
                foreach ($purchase->purchase_images ?? [] as $oldImage) {
                    if (app()->runningUnitTests()) {
                        Storage::disk('public')->delete($oldImage);
                    } else {
                        $oldPath = public_path($oldImage);
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                }

                $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('purchases') : public_path('purchases');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $storedImages = [];
                foreach ($data['purchase_images'] as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($targetDir, $filename);
                    $storedImages[] = 'purchases/' . $filename;
                }
                $purchaseData['purchase_images'] = $storedImages;
            }

            $purchase = $this->purchaseRepository->update($purchase, $purchaseData);

            // Re-sync details: delete old, create new
            if (isset($data['details'])) {
                $purchase->details()->delete();
                $purchase->details()->createMany($data['details']);
            }

            return $purchase->load(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details']);
        });
    }

    protected function generateUniqueStockCode(): string
    {
        $lastCode = (int) Stock::selectRaw('MAX(CAST(stock_code AS UNSIGNED)) as max_code')->value('max_code');

        return (string) ($lastCode + 1);
    }

    protected function applyFilters(Collection $query, ?string $from = null, ?string $to = null, ?string $branchId = null, ?string $dealer = null): Collection
    {
        $filtered = $query;

        if ($from !== null && $from !== '') {
            $fromDate = $this->parseDate($from);
            if ($fromDate) {
                $fromDate = $fromDate->startOfDay();
                $filtered = $filtered->filter(function ($item) use ($fromDate) {
                    $itemDate = $item->purchase_date ?? $item->created_at;
                    return $itemDate && \Carbon\Carbon::parse($itemDate)->gte($fromDate);
                });
            }
        }

        if ($to !== null && $to !== '') {
            $toDate = $this->parseDate($to);
            if ($toDate) {
                $toDate = $toDate->endOfDay();
                $filtered = $filtered->filter(function ($item) use ($toDate) {
                    $itemDate = $item->purchase_date ?? $item->created_at;
                    return $itemDate && \Carbon\Carbon::parse($itemDate)->lte($toDate);
                });
            }
        }

        if ($branchId !== null && $branchId !== '') {
            $filtered = $filtered->filter(function ($item) use ($branchId) {
                return (string) ($item->branch_id ?? '') === (string) $branchId;
            });
        }

        if ($dealer !== null && trim($dealer) !== '') {
            $dealerTerm = strtolower(trim($dealer));
            $filtered = $filtered->filter(function ($item) use ($dealerTerm) {
                $dealerName = strtolower($item->dealer?->name ?? '');
                return str_contains($dealerName, $dealerTerm);
            });
        }

        return $filtered->values();
    }

    /**
     * Prepare structured purchase report data.
     */
    public function getPurchaseReportData($user, ?string $from = null, ?string $to = null, ?string $branchId = null, ?string $dealer = null): array
    {
        $purchases = $this->getPurchasesForUser($user, $from, $to, $branchId, $dealer);

        $items = [];
        $totalPurchasesCount = $purchases->count();
        $totalUnitQuantity = 0.0;
        $totalAlterQuantity = 0.0;
        $totalAmount = 0.0;

        foreach ($purchases as $purchase) {
            $date = $purchase->purchase_date
                ? $purchase->purchase_date->format('d-m-Y')
                : ($purchase->created_at ? $purchase->created_at->format('d-m-Y') : '-');
            $dealerName = $purchase->dealer?->name ?? '-';
            $vehicleNumber = $purchase->vehicle?->name ?? $purchase->vehicle?->vehicle_number ?? '-';
            $transporterName = $purchase->transporter?->name ?? '-';

            if ($purchase->details && $purchase->details->isNotEmpty()) {
                foreach ($purchase->details as $detail) {
                    $unitVal = $detail->unit_value !== null ? (float) $detail->unit_value : null;
                    $alterVal = $detail->alter_unit_value !== null ? (float) $detail->alter_unit_value : null;
                    $rate = $detail->rate !== null ? (float) $detail->rate : null;
                    $itemTotal = ($unitVal !== null && $rate !== null) ? round($unitVal * $rate, 2) : 0.0;

                    $totalUnitQuantity += ($unitVal ?? 0);
                    $totalAlterQuantity += ($alterVal ?? 0);
                    $totalAmount += $itemTotal;

                    $items[] = [
                        'purchase_id' => $purchase->id,
                        'date' => $date,
                        'dealer_name' => $dealerName,
                        'vehicle' => $vehicleNumber,
                        'transporter' => $transporterName,
                        'brand' => $detail->brand_name ?? '-',
                        'stock' => $detail->stock_name ?? '-',
                        'lot_no' => $detail->lot_number ?? $purchase->lot_number ?? '-',
                        'unit_qty' => $unitVal,
                        'unit_name' => $detail->unit_type ?? '',
                        'alter_qty' => $alterVal,
                        'alter_unit_name' => $detail->alter_unit_type ?? '',
                        'rate' => $rate,
                        'total_amount' => $itemTotal,
                    ];
                }
            } else {
                $items[] = [
                    'purchase_id' => $purchase->id,
                    'date' => $purchase->purchase_date
                        ? $purchase->purchase_date->format('d-m-Y')
                        : ($purchase->created_at ? $purchase->created_at->format('d-m-Y') : '-'),
                    'dealer_name' => $dealerName,
                    'vehicle' => $vehicleNumber,
                    'transporter' => $transporterName,
                    'brand' => '-',
                    'stock' => '-',
                    'lot_no' => $purchase->lot_number ?? '-',
                    'unit_qty' => null,
                    'unit_name' => '',
                    'alter_qty' => null,
                    'alter_unit_name' => '',
                    'rate' => null,
                    'total_amount' => 0.0,
                ];
            }
        }

        $branchName = null;
        if ($user && $user->branch) {
            $branchName = $user->branch->name;
        } elseif ($purchases->isNotEmpty() && $purchases->first()->branch) {
            $branchName = $purchases->first()->branch->name;
        }

        return [
            'items' => $items,
            'total_purchases' => $totalPurchasesCount,
            'total_items' => count($items),
            'total_unit_qty' => $totalUnitQuantity,
            'total_alter_qty' => $totalAlterQuantity,
            'total_amount' => round($totalAmount, 2),
            'filters' => [
                'from' => $from,
                'to' => $to,
                'branch_id' => $branchId,
                'branch_name' => $branchName,
                'dealer_name' => $dealer,
            ],
            'meta' => [
                'generated_at' => now()->format('d-m-Y h:i A'),
                'generated_by' => $user->name ?? $user->username ?? 'User',
            ],
        ];
    }

    /**
     * Generate Purchase Report PDF.
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePurchaseReportPdf($user, ?string $from = null, ?string $to = null, ?string $branchId = null, ?string $dealer = null)
    {
        $reportData = $this->getPurchaseReportData($user, $from, $to, $branchId, $dealer);

        return Pdf::loadView('pdf.purchase_report', ['data' => (object) $reportData])
            ->setPaper('a4', 'landscape');
    }

    protected function parseDate(string $date, ?\Carbon\Carbon $preserveTimeFrom = null): ?\Carbon\Carbon
    {
        $date = trim($date);
        $tz = config('app.timezone', 'Asia/Kolkata');

        $hasTime = (bool) preg_match('/\d{1,2}:\d{2}/', $date);

        $formatsWithTime = [
            'd-m-Y H:i:s',
            'd-m-Y H:i',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'Y-m-d H:i:s',
            'Y-m-d H:i',
        ];

        $formatsDateOnly = [
            'd-m-Y',
            'd/m/Y',
            'Y-m-d',
        ];

        if ($hasTime) {
            foreach ($formatsWithTime as $fmt) {
                try {
                    $dt = \Carbon\Carbon::createFromFormat($fmt, $date, $tz);
                    if ($dt !== false) {
                        return $dt;
                    }
                } catch (\Throwable $e) {
                }
            }
            try {
                return \Carbon\Carbon::parse($date, $tz);
            } catch (\Throwable $e) {
                return null;
            }
        } else {
            foreach ($formatsDateOnly as $fmt) {
                try {
                    $dt = \Carbon\Carbon::createFromFormat($fmt, $date, $tz);
                    if ($dt !== false) {
                        if ($preserveTimeFrom) {
                            $dt->setTime($preserveTimeFrom->hour, $preserveTimeFrom->minute, $preserveTimeFrom->second);
                        } else {
                            $now = \Carbon\Carbon::now($tz);
                            $dt->setTime($now->hour, $now->minute, $now->second);
                        }
                        return $dt;
                    }
                } catch (\Throwable $e) {
                }
            }
            try {
                $dt = \Carbon\Carbon::parse($date, $tz);
                if ($preserveTimeFrom) {
                    $dt->setTime($preserveTimeFrom->hour, $preserveTimeFrom->minute, $preserveTimeFrom->second);
                } else {
                    $now = \Carbon\Carbon::now($tz);
                    $dt->setTime($now->hour, $now->minute, $now->second);
                }
                return $dt;
            } catch (\Throwable $e) {
                return null;
            }
        }
    }

    /**
     * Delete a purchase.
     */
    public function deletePurchase($user, int $id): void
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            throw new ModelNotFoundException('Purchase not found.');
        }

        if ($user->role !== 'admin') {
            if ((int)$purchase->created_by !== (int)$user->getOwnerId()) {
                throw new AuthorizationException('You are not authorized to delete this purchase.');
            }
            if ($user->branch_id !== null && (string)$purchase->branch_id !== (string)$user->branch_id) {
                throw new AuthorizationException('You are not authorized to delete this purchase.');
            }
        }

        DB::transaction(function () use ($purchase) {
            // Delete images from disk
            foreach ($purchase->purchase_images ?? [] as $image) {
                if (app()->runningUnitTests()) {
                    Storage::disk('public')->delete($image);
                } else {
                    $filePath = public_path($image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            $purchase->details()->delete();
            $this->purchaseRepository->delete($purchase);
        });
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

    protected function resolveVehicleId($vehicleId = null, ?string $vehicleNumber = null): int
    {
        if (!empty($vehicleId)) {
            $vehicle = Vehicle::where('vehicle_id', $vehicleId)->first();
            if (!$vehicle) {
                throw ValidationException::withMessages([
                    'vehicle_id' => ['The selected vehicle is invalid.']
                ]);
            }
            return (int) $vehicle->vehicle_id;
        }

        if (!empty($vehicleNumber)) {
            $vehicleNumber = trim($vehicleNumber);

            $existing = Vehicle::where('name', $vehicleNumber)
                ->orWhere(DB::raw('LOWER(name)'), strtolower($vehicleNumber))
                ->first();

            if ($existing) {
                return (int) $existing->vehicle_id;
            }

            $isLorry = preg_match('/\d/', $vehicleNumber);
            $vehicleType = $isLorry ? 'lorry' : 'local';

            $newVehicle = Vehicle::create([
                'vehicle_type' => $vehicleType,
                'name' => $vehicleNumber,
                'status' => 1,
            ]);

            return (int) $newVehicle->vehicle_id;
        }

        throw ValidationException::withMessages([
            'vehicle_id' => ['Either vehicle_id or vehicle_number is required.']
        ]);
    }
}
