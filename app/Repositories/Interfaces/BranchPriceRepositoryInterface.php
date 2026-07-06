<?php

namespace App\Repositories\Interfaces;

use App\Models\BranchPrice;
use Illuminate\Database\Eloquent\Collection;

interface BranchPriceRepositoryInterface
{
    /**
     * Get all branch prices.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find branch price by ID.
     *
     * @param int $id
     * @return BranchPrice|null
     */
    public function findById(int $id): ?BranchPrice;

    /**
     * Create a new branch price.
     *
     * @param array $data
     * @return BranchPrice
     */
    public function create(array $data): BranchPrice;

    /**
     * Update an existing branch price.
     *
     * @param BranchPrice $branchPrice
     * @param array $data
     * @return BranchPrice
     */
    public function update(BranchPrice $branchPrice, array $data): BranchPrice;

    /**
     * Delete a branch price.
     *
     * @param BranchPrice $branchPrice
     * @return bool
     */
    public function delete(BranchPrice $branchPrice): bool;
}
