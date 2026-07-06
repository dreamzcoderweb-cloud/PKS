<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchPriceRequest;
use App\Http\Requests\UpdateBranchPriceRequest;
use App\Http\Resources\BranchPriceResource;
use App\Services\BranchPriceService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AdminBranchPriceController extends Controller
{
    use ApiResponse;

    protected $branchPriceService;

    public function __construct(BranchPriceService $branchPriceService)
    {
        $this->branchPriceService = $branchPriceService;
    }

    /**
     * Display a listing of all branch prices.
     */
    public function index(): JsonResponse
    {
        $branchPrices = $this->branchPriceService->getAllBranchPrices();
        return $this->successResponse('Branch prices retrieved successfully.', BranchPriceResource::collection($branchPrices));
    }

    /**
     * Store a newly created branch price.
     */
    public function store(StoreBranchPriceRequest $request): JsonResponse
    {
        $branchPrice = $this->branchPriceService->createBranchPrice($request->validated());
        return $this->successResponse('Branch price created successfully.', new BranchPriceResource($branchPrice), 201);
    }

    /**
     * Display the specified branch price.
     */
    public function show(int $id): JsonResponse
    {
        $branchPrice = $this->branchPriceService->getBranchPriceDetails($id);
        return $this->successResponse('Branch price details retrieved successfully.', new BranchPriceResource($branchPrice));
    }

    /**
     * Update the specified branch price.
     */
    public function update(UpdateBranchPriceRequest $request, int $id): JsonResponse
    {
        $branchPrice = $this->branchPriceService->updateBranchPrice($id, $request->validated());
        return $this->successResponse('Branch price updated successfully.', new BranchPriceResource($branchPrice));
    }

    /**
     * Remove the specified branch price.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->branchPriceService->deleteBranchPrice($id);
        return $this->successResponse('Branch price deleted successfully.');
    }
}
