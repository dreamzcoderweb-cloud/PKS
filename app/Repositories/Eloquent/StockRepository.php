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
    public function all(): Collection
    {
        return Stock::with(['user', 'branch', 'unit', 'alternateUnit'])->get();
    }

    /**
     * Get stocks owned by a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function findForUser(int $userId): Collection
    {
        return Stock::with(['user', 'branch', 'unit', 'alternateUnit'])->where('created_by', $userId)->get();
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
