<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\User;
use App\Repositories\Interfaces\StockRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function getStocksForUser($user, ?string $brandName = null): Collection
    {
        if ($user->role === 'admin') {
            return $this->stockRepository->all($brandName);
        }

        return $this->stockRepository->findForUser($user->getOwnerId(), $brandName);
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

        if ($user->role !== 'admin' && (int)$stock->created_by !== (int)$user->getOwnerId()) {
            throw new AuthorizationException("You are not authorized to view this stock.");
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
        if ($user->role !== 'admin') {
            throw new AuthorizationException("Only admins are authorized to edit stocks.");
        }

        return DB::transaction(function () use ($id, $data) {
            $stock = $this->stockRepository->findById($id);

            if (!$stock) {
                throw new ModelNotFoundException("Stock not found.");
            }

            return $this->stockRepository->update($stock, $data);
        });
    }

    /**
     * Delete a stock item (Admin only).
     *
     * @param User $user
     * @param int $id
     * @return void
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    public function deleteStock($user, int $id): void
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException("Only admins are authorized to delete stocks.");
        }

        DB::transaction(function () use ($id) {
            $stock = $this->stockRepository->findById($id);

            if (!$stock) {
                throw new ModelNotFoundException("Stock not found.");
            }

            $this->stockRepository->delete($stock);
        });
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
}
