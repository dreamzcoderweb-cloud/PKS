<?php

namespace App\Services;

use App\Models\BranchPrice;
use App\Repositories\Interfaces\BranchPriceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchPriceService
{
    protected $branchPriceRepository;

    public function __construct(BranchPriceRepositoryInterface $branchPriceRepository)
    {
        $this->branchPriceRepository = $branchPriceRepository;
    }

    /**
     * Get all branch prices.
     *
     * @return Collection
     */
    public function getAllBranchPrices(): Collection
    {
        return $this->branchPriceRepository->all();
    }

    /**
     * Get details of a branch price.
     *
     * @param int $id
     * @return BranchPrice
     * @throws ModelNotFoundException
     */
    public function getBranchPriceDetails(int $id): BranchPrice
    {
        $branchPrice = $this->branchPriceRepository->findById($id);

        if (!$branchPrice) {
            throw new ModelNotFoundException("Branch price not found.");
        }

        return $branchPrice;
    }

    /**
     * Create a new branch price.
     *
     * @param array $data
     * @return BranchPrice
     */
    public function createBranchPrice(array $data): BranchPrice
    {
        return DB::transaction(function () use ($data) {
            return $this->branchPriceRepository->create($data);
        });
    }

    /**
     * Update an existing branch price.
     *
     * @param int $id
     * @param array $data
     * @return BranchPrice
     * @throws ModelNotFoundException
     */
    public function updateBranchPrice(int $id, array $data): BranchPrice
    {
        return DB::transaction(function () use ($id, $data) {
            $branchPrice = $this->branchPriceRepository->findById($id);

            if (!$branchPrice) {
                throw new ModelNotFoundException("Branch price not found.");
            }

            return $this->branchPriceRepository->update($branchPrice, $data);
        });
    }

    /**
     * Delete a branch price.
     *
     * @param int $id
     * @return void
     * @throws ModelNotFoundException
     */
    public function deleteBranchPrice(int $id): void
    {
        DB::transaction(function () use ($id) {
            $branchPrice = $this->branchPriceRepository->findById($id);

            if (!$branchPrice) {
                throw new ModelNotFoundException("Branch price not found.");
            }

            $this->branchPriceRepository->delete($branchPrice);
        });
    }
}
