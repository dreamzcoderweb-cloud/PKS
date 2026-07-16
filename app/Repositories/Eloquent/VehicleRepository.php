<?php

namespace App\Repositories\Eloquent;

use App\Models\Vehicle;
use App\Repositories\Interfaces\VehicleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class VehicleRepository implements VehicleRepositoryInterface
{
    /**
     * Get all vehicles.
     *
     * @return Collection
     */
    public function all(bool $activeOnly = false): Collection
    {
        $query = Vehicle::latest();
        if ($activeOnly) {
            $query->active();
        }
        return $query->get();
    }

    /**
     * Find vehicle by ID.
     *
     * @param int $id
     * @return Vehicle|null
     */
    public function findById(int $id): ?Vehicle
    {
        return Vehicle::find($id);
    }

    /**
     * Create a new vehicle.
     *
     * @param array $data
     * @return Vehicle
     */
    public function create(array $data): Vehicle
    {
        return Vehicle::create($data);
    }

    /**
     * Update an existing vehicle.
     *
     * @param Vehicle $vehicle
     * @param array $data
     * @return Vehicle
     */
    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        $vehicle->update($data);
        return $vehicle;
    }

    /**
     * Delete a vehicle.
     *
     * @param Vehicle $vehicle
     * @return bool
     */
    public function delete(Vehicle $vehicle): bool
    {
        return $vehicle->delete();
    }
}
