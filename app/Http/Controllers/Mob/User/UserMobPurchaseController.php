<?php

namespace App\Http\Controllers\Mob\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Resources\Mob\User\UserMobPurchaseResource;
use App\Services\PurchaseService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserMobPurchaseController extends Controller
{
    use ApiResponse;

    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request): JsonResponse
    {
        $purchases = $this->purchaseService->getPurchasesForUser(
            $request->user(),
            $request->query('from'),
            $request->query('to'),
            $request->query('branch_id')
        );
        return $this->successResponse('Purchases retrieved successfully.', UserMobPurchaseResource::collection($purchases));
    }

    public function store(StorePurchaseRequest $request): JsonResponse
    {
        $purchase = $this->purchaseService->createPurchase($request->user(), $request->validated());
        return $this->successResponse('Purchase created successfully.', new UserMobPurchaseResource($purchase), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $purchase = $this->purchaseService->getPurchaseDetails($request->user(), $id);
        return $this->successResponse('Purchase details retrieved successfully.', new UserMobPurchaseResource($purchase));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->purchaseService->deletePurchase($request->user(), $id);
        return $this->successResponse('Purchase deleted successfully.');
    }
}
