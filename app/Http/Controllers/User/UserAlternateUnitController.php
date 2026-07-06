<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlternateUnitRequest;
use App\Http\Requests\UpdateAlternateUnitRequest;
use App\Http\Resources\AlternateUnitResource;
use App\Services\AlternateUnitService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserAlternateUnitController extends Controller
{
    use ApiResponse;

    protected $alternateUnitService;

    public function __construct(AlternateUnitService $alternateUnitService)
    {
        $this->alternateUnitService = $alternateUnitService;
    }

    /**
     * Display a listing of all alternate units.
     */
    public function index(): JsonResponse
    {
        $alternateUnits = $this->alternateUnitService->getAllAlternateUnits();
        return $this->successResponse('Alternate units retrieved successfully.', AlternateUnitResource::collection($alternateUnits));
    }

    /**
     * Store a newly created alternate unit.
     */
    public function store(StoreAlternateUnitRequest $request): JsonResponse
    {
        $alternateUnit = $this->alternateUnitService->createAlternateUnit($request->validated());
        return $this->successResponse('Alternate unit created successfully.', new AlternateUnitResource($alternateUnit), 201);
    }

    /**
     * Display the specified alternate unit.
     */
    public function show(int $id): JsonResponse
    {
        $alternateUnit = $this->alternateUnitService->getAlternateUnitDetails($id);
        return $this->successResponse('Alternate unit details retrieved successfully.', new AlternateUnitResource($alternateUnit));
    }

    /**
     * Update the specified alternate unit.
     */
    public function update(UpdateAlternateUnitRequest $request, int $id): JsonResponse
    {
        $alternateUnit = $this->alternateUnitService->updateAlternateUnit($id, $request->validated());
        return $this->successResponse('Alternate unit updated successfully.', new AlternateUnitResource($alternateUnit));
    }

    /**
     * Remove the specified alternate unit.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->alternateUnitService->deleteAlternateUnit($id);
        return $this->successResponse('Alternate unit deleted successfully.');
    }
}
