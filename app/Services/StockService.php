<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\User;
use App\Models\PurchaseDetail;
use App\Repositories\Interfaces\StockRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Barryvdh\DomPDF\Facade\Pdf;

class StockService
{
    protected $stockRepository;

    public function __construct(StockRepositoryInterface $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    /**
     * Get stocks filtered by user role.
     *
     * @param User $user
     * @return Collection
     */
    public function getStocksForUser($user, ?string $brandName = null, ?string $from = null, ?string $to = null, ?string $branchId = null): Collection
    {
        $effectiveBranchId = $user->role === 'admin' ? $branchId : ($user->branch_id ?? $branchId);

        $query = $user->role === 'admin'
            ? $this->stockRepository->all($brandName)
            : $this->stockRepository->findForUser($user->getOwnerId(), $brandName);

        return $this->applyFilters($query, $from, $to, $effectiveBranchId);
    }

    /**
     * Get purchase stock records filtered by user role.
     *
     * @param User $user
     * @param string|null $brandName
     * @param string|null $from
     * @param string|null $to
     * @param string|null $branchId
     * @return Collection
     */
    public function getPurchaseStocksForUser($user, ?string $brandName = null, ?string $from = null, ?string $to = null, ?string $branchId = null): Collection
    {
        $effectiveBranchId = $user->role === 'admin' ? $branchId : ($user->branch_id ?? $branchId);

        $query = $user->role === 'admin'
            ? $this->stockRepository->getPurchaseStocks($brandName)
            : $this->stockRepository->getPurchaseStocksForUser($user->getOwnerId(), $brandName);

        return $this->applyFilters($query, $from, $to, $effectiveBranchId);
    }

    /**
     * Get detailed stock info if authorized.
     *
     * @param User $user
     * @param int $id
     * @return Stock
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    public function getStockDetails($user, int $id): Stock
    {
        $stock = $this->stockRepository->findById($id);

        if (!$stock) {
            throw new ModelNotFoundException("Stock not found.");
        }

        if ($user->role !== 'admin') {
            if ((int)$stock->created_by !== (int)$user->getOwnerId()) {
                throw new AuthorizationException("You are not authorized to view this stock.");
            }
            if ($user->branch_id !== null && (string)$stock->branch_id !== (string)$user->branch_id) {
                throw new AuthorizationException("You are not authorized to view this stock.");
            }
        }

        return $stock;
    }

    /**
     * Create a new stock item with random unique stock_code and stock_id UUID.
     *
     * @param User $user
     * @param array $data
     * @return Stock
     */
    public function createStock($user, array $data): Stock
    {
        if ($user->role !== 'admin' && !empty($user->branch_id)) {
            $data['branch_id'] = $user->branch_id;
        } else {
            $data['branch_id'] = $data['branch_id'] ?? $user->branch_id;
        }
        return Cache::lock('create_stock_lock', 10)->block(5, function () use ($user, $data) {
            return DB::transaction(function () use ($user, $data) {
                $data['created_by'] = $user->getOwnerId();
                $data['stock_id'] = (string) Str::uuid();
                $data['stock_code'] = $this->generateUniqueStockCode($user);

                return $this->stockRepository->create($data);
            });
        });
    }

    /**
     * Update an existing stock item (Admin only).
     *
     * @param User $user
     * @param int $id
     * @param array $data
     * @return Stock
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    public function updateStock($user, int $id, array $data): Stock
    {
        return DB::transaction(function () use ($user, $id, $data) {
            $stock = $this->stockRepository->findById($id);

            if (!$stock) {
                throw new ModelNotFoundException("Stock not found.");
            }

            if ($user->role !== 'admin') {
                if ((int)$stock->created_by !== (int)$user->getOwnerId()) {
                    throw new AuthorizationException("You are not authorized to edit this stock.");
                }
                if ($user->branch_id !== null && (string)$stock->branch_id !== (string)$user->branch_id) {
                    throw new AuthorizationException("You are not authorized to edit this stock.");
                }
            }

            return $this->stockRepository->update($stock, $data);
        });
    }

    /**
     * Delete a stock item.
     *
     * @param User $user
     * @param int $id
     * @return void
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    public function deleteStock($user, int $id): void
    {
        DB::transaction(function () use ($user, $id) {
            $stock = $this->stockRepository->findById($id);

            if (!$stock) {
                throw new ModelNotFoundException("Stock not found.");
            }

            if ($user->role !== 'admin') {
                if ((int)$stock->created_by !== (int)$user->getOwnerId()) {
                    throw new AuthorizationException("You are not authorized to delete this stock.");
                }
                if ($user->branch_id !== null && (string)$stock->branch_id !== (string)$user->branch_id) {
                    throw new AuthorizationException("You are not authorized to delete this stock.");
                }
            }

            $this->stockRepository->delete($stock);
        });
    }

    protected function applyFilters(Collection $query, ?string $from = null, ?string $to = null, ?string $branchId = null): Collection
    {
        $filtered = $query;

        if ($from !== null && $from !== '') {
            $fromDate = $this->parseDate($from);
            if ($fromDate) {
                $fromDate = $fromDate->startOfDay();
                $filtered = $filtered->filter(function ($item) use ($fromDate) {
                    return $item->created_at && \Carbon\Carbon::parse($item->created_at)->gte($fromDate);
                });
            }
        }

        if ($to !== null && $to !== '') {
            $toDate = $this->parseDate($to);
            if ($toDate) {
                $toDate = $toDate->endOfDay();
                $filtered = $filtered->filter(function ($item) use ($toDate) {
                    return $item->created_at && \Carbon\Carbon::parse($item->created_at)->lte($toDate);
                });
            }
        }

        if ($branchId !== null && $branchId !== '') {
            $filtered = $filtered->filter(function ($item) use ($branchId) {
                return (string) ($item->branch_id ?? '') === (string) $branchId;
            });
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
     * Generate a unique sequential stock code based on user role.
     *
     * @param User $user
     * @return string
     */

    protected function generateUniqueStockCode($user): string
    {
        $lastCode = (int) Stock::selectRaw('MAX(CAST(stock_code AS UNSIGNED)) as max_code')->value('max_code');

        return (string) ($lastCode + 1);
    }

    /**
     * Retrieve stock buy/purchase details.
    /**
     * Helper to find stock by numeric ID, UUID stock_id, or stock_code with authorization checks.
     *
     * @param User $user
     * @param string|int $identifier
     * @return Stock
     */
    public function findStockByIdentifier($user, string|int $identifier): Stock
    {
        $query = Stock::with(['user', 'branch', 'unit', 'alternateUnit']);

        if (is_numeric($identifier)) {
            $stock = (clone $query)->where('id', $identifier)->first()
                ?? (clone $query)->where('stock_id', (string) $identifier)->first()
                ?? (clone $query)->where('stock_code', (string) $identifier)->first();
        } else {
            $stock = (clone $query)->where('stock_id', $identifier)->first()
                ?? (clone $query)->where('stock_code', $identifier)->first();
        }

        if (!$stock) {
            throw new ModelNotFoundException("Stock not found.");
        }

        if ($user->role !== 'admin') {
            if ((int)$stock->created_by !== (int)$user->getOwnerId()) {
                throw new AuthorizationException("You are not authorized to view this stock.");
            }
            if ($user->branch_id !== null && (string)$stock->branch_id !== (string)$user->branch_id) {
                throw new AuthorizationException("You are not authorized to view this stock.");
            }
        }

        return $stock;
    }

    /**
     * Helper to aggregate all sales associated with a specific stock.
     *
     * @param Stock $stock
     * @return array
     */
    public function getStockSalesSummary(Stock $stock): array
    {
        $saleDetails = \App\Models\SaleDetail::with(['sale.dealer', 'sale.branch', 'sale.vehicle', 'unit', 'alternateUnit'])
            ->where('stock_id', $stock->id)
            ->latest('id')
            ->get();

        $salesList = [];
        $totalSoldBags = 0.0;
        $totalSoldAltValue = 0.0;
        $totalSaleAmount = 0.0;
        $dealersSummary = [];

        foreach ($saleDetails as $sd) {
            $sale = $sd->sale;
            $saleDealer = $sale?->dealer;
            $bagQty = (float) $sd->unit_value;
            $altQty = $sd->alternate_unit_value !== null ? (float) $sd->alternate_unit_value : null;
            $rate = $sd->rate !== null ? (float) $sd->rate : 0.0;
            $itemTotal = $bagQty * $rate;

            $totalSoldBags += $bagQty;
            if ($altQty !== null) {
                $totalSoldAltValue += $altQty;
            }
            $totalSaleAmount += $itemTotal;

            $dealerName = $saleDealer?->name ?? 'N/A';
            $dealerId = $saleDealer?->id;

            if ($dealerId) {
                if (!isset($dealersSummary[$dealerId])) {
                    $dealersSummary[$dealerId] = [
                        'dealer_id' => $dealerId,
                        'dealer_code' => $saleDealer?->dealer_code,
                        'name' => $dealerName,
                        'dealer_name' => $dealerName,
                        'business_name' => $saleDealer?->business_name,
                        'contact_number' => $saleDealer?->contact_number,
                        'address' => $saleDealer?->address,
                        'bags' => 0.0,
                        'unit_type' => $sd->unit?->unit ?? $stock->unit?->unit ?? 'Bags',
                        'alternate_unit_value' => 0.0,
                        'alternate_unit_type' => $sd->alternateUnit?->alter_unit ?? $stock->alternateUnit?->alter_unit ?? 'KGs',
                        'total_amount' => 0.0,
                    ];
                }
                $dealersSummary[$dealerId]['bags'] += $bagQty;
                if ($altQty !== null) {
                    $dealersSummary[$dealerId]['alternate_unit_value'] += $altQty;
                }
                $dealersSummary[$dealerId]['total_amount'] += $itemTotal;
            }

            $salesList[] = [
                'id' => $sd->id,
                'sale_id' => $sale?->id,
                'sale_uuid' => $sale?->sale_id,
                'invoice_number' => $sale?->invoice_number,
                'sale_date' => $sale?->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') : null,
                'sale_date_raw' => $sale?->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->toIso8601String() : null,
                'sale_date_time' => $sale?->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y H:i:s') : null,
                'dealer_id' => $dealerId,
                'dealer_name' => $dealerName,
                'business_name' => $saleDealer?->business_name,
                'contact_number' => $saleDealer?->contact_number,
                'address' => $saleDealer?->address,
                'lot_number' => $sd->lot_number,
                'bag' => $bagQty,
                'unit_type' => $sd->unit?->unit ?? $stock->unit?->unit ?? 'Bags',
                'alternate_unit_value' => $altQty,
                'alternate_unit_type' => $sd->alternateUnit?->alter_unit ?? $stock->alternateUnit?->alter_unit ?? 'KGs',
                'rate' => $rate,
                'total_amount' => round($itemTotal, 2),
                'vehicle_number' => $sale?->vehicle?->name,
                'driver_name' => $sale?->driver_name,
                'driver_number' => $sale?->driver_number,
            ];
        }

        return [
            'sales' => $salesList,
            'dealers' => array_values($dealersSummary),
            'total_sold_bags' => $totalSoldBags,
            'total_sold_alternate_unit_value' => $totalSoldAltValue,
            'total_sale_amount' => round($totalSaleAmount, 2),
        ];
    }

    /**
     * Retrieve stock buy/purchase details (with associated sales summary).
     *
     * @param User $user
     * @param string|int $identifier
     * @return array
     */
    public function getStockBuyDetails($user, string|int $identifier): array
    {
        $stock = $this->findStockByIdentifier($user, $identifier);

        // Attempt to find purchase detail matching brand_name and stock_name
        $purchaseDetailQuery = PurchaseDetail::with(['purchase.dealer', 'purchase.branch', 'purchase.vehicle'])
            ->where('brand_name', $stock->brand_name)
            ->where('stock_name', $stock->stock_name);

        if (!empty($stock->lott_number)) {
            $purchaseDetail = (clone $purchaseDetailQuery)->where('lot_number', $stock->lott_number)->latest('id')->first();
        } else {
            $purchaseDetail = null;
        }

        if (!$purchaseDetail) {
            $purchaseDetail = $purchaseDetailQuery->latest('id')->first();
        }

        $dealer = $purchaseDetail?->purchase?->dealer;
        $customerName = $dealer?->name ?? $dealer?->business_name ?? null;
        $dealerName = $dealer?->name ?? null;

        // Sales summary for this stock
        $salesSummary = $this->getStockSalesSummary($stock);

        // If purchase dealer is not available, check sales dealers so name is not empty
        if (empty($dealerName) && !empty($salesSummary['dealers'])) {
            $dealerNames = array_map(function ($d) {
                return $d['dealer_name'];
            }, $salesSummary['dealers']);
            $dealerName = implode(', ', $dealerNames);
            $customerName = $dealerName;
            $dealer = count($salesSummary['dealers']) === 1 ? [
                'id' => $salesSummary['dealers'][0]['dealer_id'],
                'name' => $salesSummary['dealers'][0]['dealer_name'],
                'business_name' => $salesSummary['dealers'][0]['business_name'],
                'contact_number' => $salesSummary['dealers'][0]['contact_number'],
                'address' => $salesSummary['dealers'][0]['address'],
            ] : [
                'id' => null,
                'name' => $dealerName,
                'dealers_list' => $salesSummary['dealers'],
            ];
        }

        // Bag: from purchase detail if present, otherwise stock units
        $bag = $purchaseDetail ? (float) $purchaseDetail->unit_value : (float) $stock->units;
        $unitType = $purchaseDetail?->unit_type ?? $stock->unit?->unit ?? 'Bags';

        // Rate: from purchase detail or stock rate
        $rate = $purchaseDetail && $purchaseDetail->rate !== null
            ? (float) $purchaseDetail->rate
            : ($stock->rate !== null ? (float) $stock->rate : ($stock->rate_stock !== null ? (float) $stock->rate_stock : 0.00));

        // Buy Date: from purchase created_at, or stock created_at
        $buyDateRaw = $purchaseDetail?->purchase?->created_at ?? $purchaseDetail?->created_at ?? $stock->created_at;
        $buyDateFormatted = $buyDateRaw ? \Carbon\Carbon::parse($buyDateRaw)->format('d-m-Y') : 'N/A';
        $buyDateTime = $buyDateRaw ? \Carbon\Carbon::parse($buyDateRaw)->format('d-m-Y H:i:s') : 'N/A';

        $totalAmount = $bag * $rate;

        return [
            'id' => $stock->id,
            'stock_id' => $stock->stock_id,
            'stock_code' => $stock->stock_code,
            'customer_name' => $customerName ?? 'N/A',
            'dealer_name' => $dealerName ?? 'N/A',
            'dealer' => $dealer ? (is_array($dealer) ? $dealer : [
                'id' => $dealer->id,
                'name' => $dealer->name,
                'business_name' => $dealer->business_name,
                'contact_number' => $dealer->contact_number,
                'address' => $dealer->address,
            ]) : null,
            'stock' => $stock->stock_name,
            'stock_name' => $stock->stock_name,
            'brand_name' => $stock->brand_name,
            'lot_number' => $stock->lott_number ?? $purchaseDetail?->lot_number ?? 'N/A',
            'bag' => $bag,
            'unit_type' => $unitType,
            'alternate_unit_value' => $purchaseDetail ? (float) $purchaseDetail->alter_unit_value : ($stock->mt ? (float) $stock->mt : null),
            'alternate_unit_type' => $purchaseDetail?->alter_unit_type ?? $stock->alternateUnit?->alter_unit ?? 'KGs',
            'rate' => $rate,
            'total_amount' => round($totalAmount, 2),
            'buy_date' => $buyDateFormatted,
            'buy_date_raw' => $buyDateRaw ? \Carbon\Carbon::parse($buyDateRaw)->toIso8601String() : null,
            'buy_date_time' => $buyDateTime,
            'branch' => [
                'branch_id' => $stock->branch_id,
                'name' => $stock->branch?->name ?? 'N/A',
            ],
            'vehicle_number' => $purchaseDetail?->purchase?->vehicle?->name ?? null,
            'driver_number' => $purchaseDetail?->purchase?->driver_number ?? null,
            'total_sold_bags' => $salesSummary['total_sold_bags'],
            'total_sold_alternate_unit_value' => $salesSummary['total_sold_alternate_unit_value'],
            'remaining_bags' => (float) $stock->units,
            'remaining_alternate_unit_value' => (float) $stock->mt,
            'dealers' => $salesSummary['dealers'],
            'sales' => $salesSummary['sales'],
        ];
    }

    /**
     * Retrieve stock sale details / sale report.
     *
     * @param User $user
     * @param string|int $identifier
     * @return array
     */
    public function getStockSaleDetails($user, string|int $identifier): array
    {
        $stock = $this->findStockByIdentifier($user, $identifier);
        $salesSummary = $this->getStockSalesSummary($stock);

        return [
            'id' => $stock->id,
            'stock_id' => $stock->stock_id,
            'stock_code' => $stock->stock_code,
            'stock_name' => $stock->stock_name,
            'brand_name' => $stock->brand_name,
            'lot_number' => $stock->lott_number ?? 'N/A',
            'unit_type' => $stock->unit?->unit ?? 'Bags',
            'alternate_unit_type' => $stock->alternateUnit?->alter_unit ?? 'KGs',
            'total_sold_bags' => $salesSummary['total_sold_bags'],
            'total_sold_alternate_unit_value' => $salesSummary['total_sold_alternate_unit_value'],
            'total_sale_amount' => $salesSummary['total_sale_amount'],
            'remaining_bags' => (float) $stock->units,
            'remaining_alternate_unit_value' => (float) $stock->mt,
            'initial_bags' => $salesSummary['total_sold_bags'] + (float) $stock->units,
            'dealers' => $salesSummary['dealers'],
            'sales' => $salesSummary['sales'],
            'branch' => [
                'branch_id' => $stock->branch_id,
                'name' => $stock->branch?->name ?? 'N/A',
            ],
            'created_at' => $stock->created_at ? \Carbon\Carbon::parse($stock->created_at)->format('d-m-Y H:i:s') : null,
        ];
    }

    /**
     * Generate PDF for stock buy details.
     *
     * @param User $user
     * @param string|int $identifier
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateStockBuyDetailsPdf($user, string|int $identifier)
    {
        $details = $this->getStockBuyDetails($user, $identifier);

        return Pdf::loadView('pdf.stock_details', ['data' => (object) $details]);
    }

    /**
     * Generate PDF for stock sale details / sale report.
     *
     * @param User $user
     * @param string|int $identifier
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateStockSaleDetailsPdf($user, string|int $identifier)
    {
        $details = $this->getStockSaleDetails($user, $identifier);

        return Pdf::loadView('pdf.stock_sale_details', ['data' => (object) $details]);
    }
}

