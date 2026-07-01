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
    public function getAllVehicles(): Collection
    {
        return $this->vehicleRepository->all();
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
            // Default value for status: 1 (Active)
            $data['status'] = $data['status'] ?? 1;

            if ($data['vehicle_type'] === 'lorry') {
                $data['driver_number'] = null;
            } elseif ($data['vehicle_type'] === 'local') {
                $data['vehicle_number'] = null;
            }

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

            $finalType = $data['vehicle_type'] ?? $vehicle->vehicle_type;

            if ($finalType === 'lorry') {
                $data['driver_number'] = null;
            } elseif ($finalType === 'local') {
                $data['vehicle_number'] = null;
            }

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
