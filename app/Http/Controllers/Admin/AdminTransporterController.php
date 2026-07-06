<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransporterRequest;
use App\Http\Requests\UpdateTransporterRequest;
use App\Http\Resources\TransporterResource;
use App\Services\TransporterService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AdminTransporterController extends Controller
{
    use ApiResponse;

    protected $transporterService;

    public function __construct(TransporterService $transporterService)
    {
        $this->transporterService = $transporterService;
    }

    /**
     * Display a listing of all transporters.
     */
    public function index(): JsonResponse
    {
        $transporters = $this->transporterService->getAllTransporters();
        return $this->successResponse('Transporters retrieved successfully.', TransporterResource::collection($transporters));
    }

    /**
     * Store a newly created transporter.
     */
    public function store(StoreTransporterRequest $request): JsonResponse
    {
        $transporter = $this->transporterService->createTransporter($request->validated());
        return $this->successResponse('Transporter created successfully.', new TransporterResource($transporter), 201);
    }

    /**
     * Display the specified transporter.
     */
    public function show(int $id): JsonResponse
    {
        $transporter = $this->transporterService->getTransporterDetails($id);
        return $this->successResponse('Transporter details retrieved successfully.', new TransporterResource($transporter));
    }

    /**
     * Update the specified transporter.
     */
    public function update(UpdateTransporterRequest $request, int $id): JsonResponse
    {
        $transporter = $this->transporterService->updateTransporter($id, $request->validated());
        return $this->successResponse('Transporter updated successfully.', new TransporterResource($transporter));
    }

    /**
     * Remove the specified transporter.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->transporterService->deleteTransporter($id);
        return $this->successResponse('Transporter deleted successfully.');
    }
}
