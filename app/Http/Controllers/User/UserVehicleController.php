<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Services\VehicleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserVehicleController extends Controller
{
    use ApiResponse;

    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     * Display a listing of all vehicles.
     */
    public function index(Request $request): JsonResponse
    {
        $activeOnly = $request->boolean('active') || $request->boolean('active_only');
        $vehicles = $this->vehicleService->getAllVehicles($activeOnly);
        return $this->successResponse('Vehicles retrieved successfully.', VehicleResource::collection($vehicles));
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $vehicle = $this->vehicleService->createVehicle($request->validated());
        return $this->successResponse('Vehicle created successfully.', new VehicleResource($vehicle), 201);
    }

    /**
     * Display the specified vehicle.
     */
    public function show(int $id): JsonResponse
    {
        $vehicle = $this->vehicleService->getVehicleDetails($id);
        return $this->successResponse('Vehicle details retrieved successfully.', new VehicleResource($vehicle));
    }

    /**
     * Update the specified vehicle.
     */
    public function update(UpdateVehicleRequest $request, int $id): JsonResponse
    {
        $vehicle = $this->vehicleService->updateVehicle($id, $request->validated());
        return $this->successResponse('Vehicle updated successfully.', new VehicleResource($vehicle));
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->vehicleService->deleteVehicle($id);
        return $this->successResponse('Vehicle deleted successfully.');
    }
}
