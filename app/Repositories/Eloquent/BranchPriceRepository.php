<?php

namespace App\Repositories\Eloquent;

use App\Models\BranchPrice;
use App\Repositories\Interfaces\BranchPriceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BranchPriceRepository implements BranchPriceRepositoryInterface
{
    /**
     * Get all branch prices.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return BranchPrice::all();
    }

    /**
     * Find branch price by ID.
     *
     * @param int $id
     * @return BranchPrice|null
     */
    public function findById(int $id): ?BranchPrice
    {
        return BranchPrice::find($id);
    }

    /**
     * Create a new branch price.
     *
     * @param array $data
     * @return BranchPrice
     */
    public function create(array $data): BranchPrice
    {
        return BranchPrice::create($data);
    }

    /**
     * Update an existing branch price.
     *
     * @param BranchPrice $branchPrice
     * @param array $data
     * @return BranchPrice
     */
    public function update(BranchPrice $branchPrice, array $data): BranchPrice
    {
        $branchPrice->update($data);
        return $branchPrice;
    }

    /**
     * Delete a branch price.
     *
     * @param BranchPrice $branchPrice
     * @return bool
     */
    public function delete(BranchPrice $branchPrice): bool
    {
        return $branchPrice->delete();
    }
}
