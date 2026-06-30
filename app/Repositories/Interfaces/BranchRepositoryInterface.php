<?php

namespace App\Repositories\Interfaces;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;

interface BranchRepositoryInterface
{
    /**
     * Get all branches.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find branch by ID.
     *
     * @param int $id
     * @return Branch|null
     */
    public function findById(int $id): ?Branch;

    /**
     * Create a new branch.
     *
     * @param array $data
     * @return Branch
     */
    public function create(array $data): Branch;

    /**
     * Update an existing branch.
     *
     * @param Branch $branch
     * @param array $data
     * @return Branch
     */
    public function update(Branch $branch, array $data): Branch;

    /**
     * Delete a branch.
     *
     * @param Branch $branch
     * @return bool
     */
    public function delete(Branch $branch): bool;
}
