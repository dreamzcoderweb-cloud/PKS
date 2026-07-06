<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Services\UnitService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AdminUnitController extends Controller
{
    use ApiResponse;

    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    /**
     * Display a listing of all units.
     */
    public function index(): JsonResponse
    {
        $units = $this->unitService->getAllUnits();
        return $this->successResponse('Units retrieved successfully.', UnitResource::collection($units));
    }

    /**
     * Store a newly created unit.
     */
    public function store(StoreUnitRequest $request): JsonResponse
    {
        $unit = $this->unitService->createUnit($request->validated());
        return $this->successResponse('Unit created successfully.', new UnitResource($unit), 201);
    }

    /**
     * Display the specified unit.
     */
    public function show(int $id): JsonResponse
    {
        $unit = $this->unitService->getUnitDetails($id);
        return $this->successResponse('Unit details retrieved successfully.', new UnitResource($unit));
    }

    /**
     * Update the specified unit.
     */
    public function update(UpdateUnitRequest $request, int $id): JsonResponse
    {
        $unit = $this->unitService->updateUnit($id, $request->validated());
        return $this->successResponse('Unit updated successfully.', new UnitResource($unit));
    }

    /**
     * Remove the specified unit.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->unitService->deleteUnit($id);
        return $this->successResponse('Unit deleted successfully.');
    }
}
