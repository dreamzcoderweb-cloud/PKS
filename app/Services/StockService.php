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
    public function getStocksForUser($user, ?string $brandName = null, ?string $from = null, ?string $to = null, ?string $branchId = null): Collection
    {
        $query = $user->role === 'admin'
            ? $this->stockRepository->all($brandName)
            : $this->stockRepository->findForUser($user->getOwnerId(), $brandName);

        return $this->applyFilters($query, $from, $to, $branchId);
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
        $query = $user->role === 'admin'
            ? $this->stockRepository->getPurchaseStocks($brandName)
            : $this->stockRepository->getPurchaseStocksForUser($user->getOwnerId(), $brandName);

        return $this->applyFilters($query, $from, $to, $branchId);
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
        $data['branch_id'] = $data['branch_id'] ?? $user->branch_id;
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
}
