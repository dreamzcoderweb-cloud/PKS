<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\Interfaces\VehicleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VehicleService
{
    protected $vehicleRepository;

    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * Get all vehicles.
     *
     * @return Collection
     */
    public function getAllVehicles(bool $activeOnly = false): Collection
    {
        return $this->vehicleRepository->all($activeOnly);
    }

    /**
     * Get details of a vehicle.
     *
     * @param int $id
     * @return Vehicle
     * @throws ModelNotFoundException
     */
    public function getVehicleDetails(int $id): Vehicle
    {
        $vehicle = $this->vehicleRepository->findById($id);

        if (!$vehicle) {
            throw new ModelNotFoundException("Vehicle not found.");
        }

        return $vehicle;
    }

    /**
     * Create a new vehicle.
     *
     * @param array $data
     * @return Vehicle
     */
    public function createVehicle(array $data): Vehicle
    {
        return DB::transaction(function () use ($data) {

            $data['status'] = $data['status'] ?? 1;

            if (!empty($data['vehicle_number'])) {
                $data['name'] = $data['vehicle_number'];
            } elseif (!empty($data['driver_number'])) {
                $data['name'] = $data['driver_number'];
            }

            unset($data['vehicle_number'], $data['driver_number']);

            return $this->vehicleRepository->create($data);
        });
    }

    /**
     * Update an existing vehicle.
     *
     * @param int $id
     * @param array $data
     * @return Vehicle
     * @throws ModelNotFoundException
     */
    public function updateVehicle(int $id, array $data): Vehicle
    {
        return DB::transaction(function () use ($id, $data) {

            $vehicle = $this->vehicleRepository->findById($id);

            if (!$vehicle) {
                throw new ModelNotFoundException("Vehicle not found.");
            }

            if (!empty($data['vehicle_number'])) {
                $data['name'] = $data['vehicle_number'];
            } elseif (!empty($data['driver_number'])) {
                $data['name'] = $data['driver_number'];
            }

            unset($data['vehicle_number'], $data['driver_number']);

            return $this->vehicleRepository->update($vehicle, $data);
        });
    }

    /**
     * Delete a vehicle.
     *
     * @param int $id
     * @return void
     * @throws ModelNotFoundException
     */
    public function deleteVehicle(int $id): void
    {
        DB::transaction(function () use ($id) {
            $vehicle = $this->vehicleRepository->findById($id);

            if (!$vehicle) {
                throw new ModelNotFoundException("Vehicle not found.");
            }

            $this->vehicleRepository->delete($vehicle);
        });
    }
}
