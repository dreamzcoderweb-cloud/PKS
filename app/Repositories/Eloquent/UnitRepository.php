<?php

namespace App\Repositories\Eloquent;

use App\Models\Unit;
use App\Repositories\Interfaces\UnitRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UnitRepository implements UnitRepositoryInterface
{
    /**
     * Get all units.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Unit::latest()->get();
    }

    /**
     * Find unit by ID.
     *
     * @param int $id
     * @return Unit|null
     */
    public function findById(int $id): ?Unit
    {
        return Unit::find($id);
    }

    /**
     * Create a new unit.
     *
     * @param array $data
     * @return Unit
     */
    public function create(array $data): Unit
    {
        return Unit::create($data);
    }

    /**
     * Update an existing unit.
     *
     * @param Unit $unit
     * @param array $data
     * @return Unit
     */
    public function update(Unit $unit, array $data): Unit
    {
        $unit->update($data);
        return $unit;
    }

    /**
     * Delete a unit.
     *
     * @param Unit $unit
     * @return bool
     */
    public function delete(Unit $unit): bool
    {
        return $unit->delete();
    }
}
