<?php

namespace App\Services;

use App\Models\Unit;
use App\Repositories\Interfaces\UnitRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnitService
{
    protected $unitRepository;

    public function __construct(UnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    /**
     * Get all units.
     *
     * @return Collection
     */
    public function getAllUnits(): Collection
    {
        return $this->unitRepository->all();
    }

    /**
     * Get unit details by ID.
     *
     * @param int $id
     * @return Unit
     * @throws ModelNotFoundException
     */
    public function getUnitDetails(int $id): Unit
    {
        $unit = $this->unitRepository->findById($id);

        if (!$unit) {
            throw new ModelNotFoundException("Unit not found.");
        }

        return $unit;
    }

    /**
     * Create a new unit.
     *
     * @param array $data
     * @return Unit
     */
    public function createUnit(array $data): Unit
    {
        return DB::transaction(function () use ($data) {
            return $this->unitRepository->create($data);
        });
    }

    /**
     * Update an existing unit.
     *
     * @param int $id
     * @param array $data
     * @return Unit
     * @throws ModelNotFoundException
     */
    public function updateUnit(int $id, array $data): Unit
    {
        return DB::transaction(function () use ($id, $data) {
            $unit = $this->unitRepository->findById($id);

            if (!$unit) {
                throw new ModelNotFoundException("Unit not found.");
            }

            return $this->unitRepository->update($unit, $data);
        });
    }

    /**
     * Delete a unit.
     *
     * @param int $id
     * @return void
     * @throws ModelNotFoundException
     */
    public function deleteUnit(int $id): void
    {
        DB::transaction(function () use ($id) {
            $unit = $this->unitRepository->findById($id);

            if (!$unit) {
                throw new ModelNotFoundException("Unit not found.");
            }

            $this->unitRepository->delete($unit);
        });
    }
}
