<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGatepassRequest;
use App\Http\Requests\UpdateGatepassRequest;
use App\Http\Resources\GatepassResource;
use App\Services\GatepassService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminGatepassController extends Controller
{
    use ApiResponse;

    protected $gatepassService;

    public function __construct(GatepassService $gatepassService)
    {
        $this->gatepassService = $gatepassService;
    }

    public function index(Request $request): JsonResponse
    {
        $gatepasses = $this->gatepassService->getGatepassesForUser($request->user());
        return $this->successResponse('Gatepasses retrieved successfully.', GatepassResource::collection($gatepasses));
    }

    public function store(StoreGatepassRequest $request): JsonResponse
    {
        $gatepass = $this->gatepassService->createGatepass($request->user(), $request->validated());
        return $this->successResponse('Gatepass created successfully.', new GatepassResource($gatepass), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $gatepass = $this->gatepassService->getGatepassDetails($request->user(), $id);
        return $this->successResponse('Gatepass details retrieved successfully.', new GatepassResource($gatepass));
    }

    public function update(UpdateGatepassRequest $request, int $id): JsonResponse
    {
        $gatepass = $this->gatepassService->updateGatepass($request->user(), $id, $request->validated());
        return $this->successResponse('Gatepass updated successfully.', new GatepassResource($gatepass));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $force = $request->boolean('force');
        $this->gatepassService->deleteGatepass($request->user(), $id, $force);
        return $this->successResponse('Gatepass deleted successfully.');
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,approved,dispatched,completed,cancelled'
        ]);

        $gatepass = $this->gatepassService->updateStatus($request->user(), $id, $request->input('status'));
        return $this->successResponse('Gatepass status updated successfully.', new GatepassResource($gatepass));
    }

    public function generatePdf(Request $request, int $id)
    {
        $pdf = $this->gatepassService->generateGatepassPdf($request->user(), $id);
        return $pdf->download('gatepass-' . $id . '.pdf');
    }
}
