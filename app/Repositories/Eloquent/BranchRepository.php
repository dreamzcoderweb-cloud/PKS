<?php

namespace App\Repositories\Eloquent;

use App\Models\Branch;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository implements BranchRepositoryInterface
{
    /**
     * Get all branches.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Branch::all();
    }

    /**
     * Find branch by ID.
     *
     * @param int $id
     * @return Branch|null
     */
    public function findById(int $id): ?Branch
    {
        return Branch::find($id);
    }

    /**
     * Create a new branch.
     *
     * @param array $data
     * @return Branch
     */
    public function create(array $data): Branch
    {
        return Branch::create($data);
    }

    /**
     * Update an existing branch.
     *
     * @param Branch $branch
     * @param array $data
     * @return Branch
     */
    public function update(Branch $branch, array $data): Branch
    {
        $branch->update($data);
        return $branch;
    }

    /**
     * Delete a branch.
     *
     * @param Branch $branch
     * @return bool
     */
    public function delete(Branch $branch): bool
    {
        return $branch->delete();
    }
}
