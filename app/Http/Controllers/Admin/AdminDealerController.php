<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealerRequest;
use App\Http\Requests\UpdateDealerRequest;
use App\Http\Resources\DealerResource;
use App\Services\DealerService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDealerController extends Controller
{
    use ApiResponse;

    protected $dealerService;

    public function __construct(DealerService $dealerService)
    {
        $this->dealerService = $dealerService;
    }

    public function index(Request $request): JsonResponse
    {
        $dealers = $this->dealerService->getDealersForUser($request->user());
        return $this->successResponse('Dealers retrieved successfully.', DealerResource::collection($dealers));
    }

    public function store(StoreDealerRequest $request): JsonResponse
    {
        $dealer = $this->dealerService->createDealer($request->user(), $request->validated());
        return $this->successResponse('Dealer created successfully.', new DealerResource($dealer), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $dealer = $this->dealerService->getDealerDetails($request->user(), $id);
        return $this->successResponse('Dealer details retrieved successfully.', new DealerResource($dealer));
    }

    public function update(UpdateDealerRequest $request, int $id): JsonResponse
    {
        $dealer = $this->dealerService->updateDealer($request->user(), $id, $request->validated());
        return $this->successResponse('Dealer updated successfully.', new DealerResource($dealer));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->dealerService->deleteDealer($request->user(), $id);
        return $this->successResponse('Dealer deleted successfully.');
    }
}
