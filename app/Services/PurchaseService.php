<?php

namespace App\Services;

use App\Models\AlternateUnit;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\Unit;
use App\Models\User;
use App\Repositories\Interfaces\PurchaseRepositoryInterface;
use App\Repositories\Interfaces\StockRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function getPurchasesForUser($user, ?string $from = null, ?string $to = null, ?string $branchId = null): Collection
    {
        $query = $user->role === 'admin'
            ? $this->purchaseRepository->all()
            : $this->purchaseRepository->findForUser($user->getOwnerId());

        return $this->applyFilters($query, $from, $to, $branchId);
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

        if ($user->role !== 'admin' && (int)$purchase->created_by !== (int)$user->getOwnerId()) {
            throw new AuthorizationException('You are not authorized to view this purchase.');
        }

        return $purchase;
    }

    /**
     * Create a purchase with its details and images.
     */
    public function createPurchase($user, array $data): Purchase
    {
        $data['branch_id'] = $data['branch_id'] ?? $user->branch_id;
        return DB::transaction(function () use ($user, $data) {
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

            $purchaseData = [
                'purchase_id' => (string) Str::uuid(),
                'branch_id' => $data['branch_id'],
                'dealer_id' => $data['dealer_id'],
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $data['vehicle_id'],
                'driver_number' => $data['driver_number'],
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

        return DB::transaction(function () use ($purchase, $data) {
            $purchaseData = [
                'branch_id' => $data['branch_id'],
                'dealer_id' => $data['dealer_id'],
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $data['vehicle_id'],
                'driver_number' => $data['driver_number'],
            ];

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

    protected function applyFilters(Collection $query, ?string $from = null, ?string $to = null, ?string $branchId = null): Collection
    {
        $filtered = $query;

        if ($from !== null && $from !== '') {
            $filtered = $filtered->filter(function ($item) use ($from) {
                return $item->created_at && $item->created_at->gte($this->normalizeDate($from));
            });
        }

        if ($to !== null && $to !== '') {
            $filtered = $filtered->filter(function ($item) use ($to) {
                return $item->created_at && $item->created_at->lte($this->normalizeDate($to));
            });
        }

        if ($branchId !== null && $branchId !== '') {
            $filtered = $filtered->filter(function ($item) use ($branchId) {
                return (string) ($item->branch_id ?? '') === (string) $branchId;
            });
        }

        return $filtered->values();
    }

    protected function normalizeDate(string $date): string
    {
        // Try parsing as d/m/Y format first (e.g., 25/07/2026)
        $parsed = \Carbon\Carbon::createFromFormat('d/m/Y', $date);
        if ($parsed) {
            return $parsed->format('Y-m-d');
        }

        // Try parsing as Y-m-d format (e.g., 2026-07-25)
        $parsed = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        if ($parsed) {
            return $parsed->format('Y-m-d');
        }

        return $date;
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

        if ($user->role !== 'admin' && (int)$purchase->created_by !== (int)$user->getOwnerId()) {
            throw new AuthorizationException('You are not authorized to delete this purchase.');
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
}
