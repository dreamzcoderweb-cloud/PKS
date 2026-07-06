<?php

namespace App\Services;

use App\Models\AlternateUnit;
use App\Repositories\Interfaces\AlternateUnitRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AlternateUnitService
{
    protected $alternateUnitRepository;

    public function __construct(AlternateUnitRepositoryInterface $alternateUnitRepository)
    {
        $this->alternateUnitRepository = $alternateUnitRepository;
    }

    /**
     * Get all alternate units.
     *
     * @return Collection
     */
    public function getAllAlternateUnits(): Collection
    {
        return $this->alternateUnitRepository->all();
    }

    /**
     * Get alternate unit details by ID.
     *
     * @param int $id
     * @return AlternateUnit
     * @throws ModelNotFoundException
     */
    public function getAlternateUnitDetails(int $id): AlternateUnit
    {
        $alternateUnit = $this->alternateUnitRepository->findById($id);

        if (!$alternateUnit) {
            throw new ModelNotFoundException("Alternate Unit not found.");
        }

        return $alternateUnit;
    }

    /**
     * Create a new alternate unit.
     *
     * @param array $data
     * @return AlternateUnit
     */
    public function createAlternateUnit(array $data): AlternateUnit
    {
        return DB::transaction(function () use ($data) {
            return $this->alternateUnitRepository->create($data);
        });
    }

    /**
     * Update an existing alternate unit.
     *
     * @param int $id
     * @param array $data
     * @return AlternateUnit
     * @throws ModelNotFoundException
     */
    public function updateAlternateUnit(int $id, array $data): AlternateUnit
    {
        return DB::transaction(function () use ($id, $data) {
            $alternateUnit = $this->alternateUnitRepository->findById($id);

            if (!$alternateUnit) {
                throw new ModelNotFoundException("Alternate Unit not found.");
            }

            return $this->alternateUnitRepository->update($alternateUnit, $data);
        });
    }

    /**
     * Delete an alternate unit.
     *
     * @param int $id
     * @return void
     * @throws ModelNotFoundException
     */
    public function deleteAlternateUnit(int $id): void
    {
        DB::transaction(function () use ($id) {
            $alternateUnit = $this->alternateUnitRepository->findById($id);

            if (!$alternateUnit) {
                throw new ModelNotFoundException("Alternate Unit not found.");
            }

            $this->alternateUnitRepository->delete($alternateUnit);
        });
    }
}
