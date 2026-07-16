<?php

namespace App\Repositories\Interfaces;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

interface VehicleRepositoryInterface
{
    /**
     * Get all vehicles.
     *
     * @return Collection
     */
    public function all(bool $activeOnly = false): Collection;

    /**
     * Find vehicle by ID.
     *
     * @param int $id
     * @return Vehicle|null
     */
    public function findById(int $id): ?Vehicle;

    /**
     * Create a new vehicle.
     *
     * @param array $data
     * @return Vehicle
     */
    public function create(array $data): Vehicle;

    /**
     * Update an existing vehicle.
     *
     * @param Vehicle $vehicle
     * @param array $data
     * @return Vehicle
     */
    public function update(Vehicle $vehicle, array $data): Vehicle;

    /**
     * Delete a vehicle.
     *
     * @param Vehicle $vehicle
     * @return bool
     */
    public function delete(Vehicle $vehicle): bool;
}
