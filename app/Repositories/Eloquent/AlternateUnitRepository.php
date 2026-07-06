<?php

namespace App\Repositories\Eloquent;

use App\Models\AlternateUnit;
use App\Repositories\Interfaces\AlternateUnitRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AlternateUnitRepository implements AlternateUnitRepositoryInterface
{
    /**
     * Get all alternate units.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return AlternateUnit::latest()->get();
    }

    /**
     * Find alternate unit by ID.
     *
     * @param int $id
     * @return AlternateUnit|null
     */
    public function findById(int $id): ?AlternateUnit
    {
        return AlternateUnit::find($id);
    }

    /**
     * Create a new alternate unit.
     *
     * @param array $data
     * @return AlternateUnit
     */
    public function create(array $data): AlternateUnit
    {
        return AlternateUnit::create($data);
    }

    /**
     * Update an existing alternate unit.
     *
     * @param AlternateUnit $alternateUnit
     * @param array $data
     * @return AlternateUnit
     */
    public function update(AlternateUnit $alternateUnit, array $data): AlternateUnit
    {
        $alternateUnit->update($data);
        return $alternateUnit;
    }

    /**
     * Delete an alternate unit.
     *
     * @param AlternateUnit $alternateUnit
     * @return bool
     */
    public function delete(AlternateUnit $alternateUnit): bool
    {
        return $alternateUnit->delete();
    }
}
