<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchService
{
    protected $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    /**
     * Get all branches.
     *
     * @return Collection
     */
    public function getAllBranches(): Collection
    {
        return $this->branchRepository->all();
    }

    /**
     * Get details of a branch.
     *
     * @param int $id
     * @return Branch
     * @throws ModelNotFoundException
     */
    public function getBranchDetails(int $id): Branch
    {
        $branch = $this->branchRepository->findById($id);

        if (!$branch) {
            throw new ModelNotFoundException("Branch not found.");
        }

        return $branch;
    }

    /**
     * Create a new branch.
     *
     * @param array $data
     * @return Branch
     */
    public function createBranch(array $data): Branch
    {
        return DB::transaction(function () use ($data) {
            // Default value for status: 1 (Active)
            $data['status'] = $data['status'] ?? 1;
            return $this->branchRepository->create($data);
        });
    }

    /**
     * Update an existing branch.
     *
     * @param int $id
     * @param array $data
     * @return Branch
     * @throws ModelNotFoundException
     */
    public function updateBranch(int $id, array $data): Branch
    {
        return DB::transaction(function () use ($id, $data) {
            $branch = $this->branchRepository->findById($id);

            if (!$branch) {
                throw new ModelNotFoundException("Branch not found.");
            }

            return $this->branchRepository->update($branch, $data);
        });
    }

    /**
     * Delete a branch.
     *
     * @param int $id
     * @return void
     * @throws ModelNotFoundException
     */
    public function deleteBranch(int $id): void
    {
        DB::transaction(function () use ($id) {
            $branch = $this->branchRepository->findById($id);

            if (!$branch) {
                throw new ModelNotFoundException("Branch not found.");
            }

            $this->branchRepository->delete($branch);
        });
    }
}
