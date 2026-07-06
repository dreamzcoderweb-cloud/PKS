<?php

namespace App\Repositories\Interfaces;

use App\Models\AlternateUnit;
use Illuminate\Database\Eloquent\Collection;

interface AlternateUnitRepositoryInterface
{
    /**
     * Get all alternate units.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find alternate unit by ID.
     *
     * @param int $id
     * @return AlternateUnit|null
     */
    public function findById(int $id): ?AlternateUnit;

    /**
     * Create a new alternate unit.
     *
     * @param array $data
     * @return AlternateUnit
     */
    public function create(array $data): AlternateUnit;

    /**
     * Update an existing alternate unit.
     *
     * @param AlternateUnit $alternateUnit
     * @param array $data
     * @return AlternateUnit
     */
    public function update(AlternateUnit $alternateUnit, array $data): AlternateUnit;

    /**
     * Delete an alternate unit.
     *
     * @param AlternateUnit $alternateUnit
     * @return bool
     */
    public function delete(AlternateUnit $alternateUnit): bool;
}
