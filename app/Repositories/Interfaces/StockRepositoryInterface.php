<?php

namespace App\Repositories\Interfaces;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;

interface StockRepositoryInterface
{
    /**
     * Get all stocks.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get stocks owned by a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function findForUser(int $userId): Collection;

    /**
     * Find stock by ID.
     *
     * @param int $id
     * @return Stock|null
     */
    public function findById(int $id): ?Stock;

    /**
     * Create a new stock.
     *
     * @param array $data
     * @return Stock
     */
    public function create(array $data): Stock;

    /**
     * Update an existing stock.
     *
     * @param Stock $stock
     * @param array $data
     * @return Stock
     */
    public function update(Stock $stock, array $data): Stock;

    /**
     * Delete a stock.
     *
     * @param Stock $stock
     * @return bool
     */
    public function delete(Stock $stock): bool;
}
