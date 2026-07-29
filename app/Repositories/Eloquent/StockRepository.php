<?php

namespace App\Repositories\Eloquent;

use App\Models\Stock;
use App\Repositories\Interfaces\StockRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StockRepository implements StockRepositoryInterface
{
    /**
     * Get all stocks.
     *
     * @return Collection
     */
     public function all(?string $brandName = null): Collection
    {
        $query = Stock::with(['user', 'branch', 'unit', 'alternateUnit']);

        if ($brandName !== null) {
            $query->where('brand_name', $brandName);
        }

        return $query->latest('id')->get();
    }

    /**
     * Get stocks owned by a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function findForUser(int $userId, ?string $brandName = null): Collection
    {
        $query = Stock::with(['user', 'branch', 'unit', 'alternateUnit'])->where('created_by', $userId);
        if ($brandName !== null) {
            $query->where('brand_name', $brandName);
        }
        return $query->latest('id')->get();
    }

    /**
     * Get purchase stock records by joining purchases, purchase details, and stocks.
     *
     * @param string|null $brandName
     * @return Collection
     */
    public function getPurchaseStocks(?string $brandName = null): Collection
    {
        $query = Stock::with(['user', 'branch', 'unit', 'alternateUnit'])
            ->join('purchase_details', 'stocks.brand_name', '=', 'purchase_details.brand_name')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->select(
                'stocks.*',
                'purchases.purchase_id as purchase_uuid',
                'purchases.lot_number as purchase_lot_number',
                'purchase_details.unit_value as purchase_unit_value',
                'purchase_details.unit_type as purchase_unit_type',
                'purchase_details.alter_unit_value as purchase_alter_unit_value',
                'purchase_details.alter_unit_type as purchase_alter_unit_type',
                'purchase_details.rate as purchase_rate'
            );

        if ($brandName !== null) {
            $query->where('stocks.brand_name', $brandName);
        }

        return $query->latest('purchase_details.id')->get();
    }

    /**
     * Get purchase stock records for a specific user.
     *
     * @param int $userId
     * @param string|null $brandName
     * @return Collection
     */
    public function getPurchaseStocksForUser(int $userId, ?string $brandName = null): Collection
    {
        $query = Stock::with(['user', 'branch', 'unit', 'alternateUnit'])
            ->join('purchase_details', 'stocks.brand_name', '=', 'purchase_details.brand_name')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->select(
                'stocks.*',
                'purchases.purchase_id as purchase_uuid',
                'purchases.lot_number as purchase_lot_number',
                'purchase_details.unit_value as purchase_unit_value',
                'purchase_details.unit_type as purchase_unit_type',
                'purchase_details.alter_unit_value as purchase_alter_unit_value',
                'purchase_details.alter_unit_type as purchase_alter_unit_type',
                'purchase_details.rate as purchase_rate'
            )
            ->where('stocks.created_by', $userId)
            ->where('purchases.created_by', $userId);

        if ($brandName !== null) {
            $query->where('stocks.brand_name', $brandName);
        }

        return $query->latest('purchase_details.id')->get();
    }

    /**
     * Find stock by ID.
     *
     * @param int $id
     * @return Stock|null
     */
    public function findById(int $id): ?Stock
    {
        return Stock::find($id);
    }

    /**
     * Create a new stock.
     *
     * @param array $data
     * @return Stock
     */
    public function create(array $data): Stock
    {
        return Stock::create($data);
    }

    /**
     * Update an existing stock.
     *
     * @param Stock $stock
     * @param array $data
     * @return Stock
     */
    public function update(Stock $stock, array $data): Stock
    {
        $stock->update($data);
        return $stock;
    }

    /**
     * Delete a stock.
     *
     * @param Stock $stock
     * @return bool
     */
    public function delete(Stock $stock): bool
    {
        return $stock->delete();
    }
}
