<?php

namespace App\Repositories\Interfaces;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

interface UnitRepositoryInterface
{
    /**
     * Get all units.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find unit by ID.
     *
     * @param int $id
     * @return Unit|null
     */
    public function findById(int $id): ?Unit;

    /**
     * Create a new unit.
     *
     * @param array $data
     * @return Unit
     */
    public function create(array $data): Unit;

    /**
     * Update an existing unit.
     *
     * @param Unit $unit
     * @param array $data
     * @return Unit
     */
    public function update(Unit $unit, array $data): Unit;

    /**
     * Delete a unit.
     *
     * @param Unit $unit
     * @return bool
     */
    public function delete(Unit $unit): bool;
}
