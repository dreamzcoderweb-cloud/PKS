<?php

namespace App\Http\Controllers\Mob\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Resources\Mob\Admin\AdminMobPurchaseResource;
use App\Services\PurchaseService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminMobPurchaseController extends Controller
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
        return $this->successResponse('Purchases retrieved successfully.', AdminMobPurchaseResource::collection($purchases));
    }

    public function store(StorePurchaseRequest $request): JsonResponse
    {
        $purchase = $this->purchaseService->createPurchase($request->user(), $request->validated());
        return $this->successResponse('Purchase created successfully.', new AdminMobPurchaseResource($purchase), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $purchase = $this->purchaseService->getPurchaseDetails($request->user(), $id);
        return $this->successResponse('Purchase details retrieved successfully.', new AdminMobPurchaseResource($purchase));
    }

    public function update(UpdatePurchaseRequest $request, int $id): JsonResponse
    {
        $purchase = $this->purchaseService->updatePurchase($request->user(), $id, $request->validated());
        return $this->successResponse('Purchase updated successfully.', new AdminMobPurchaseResource($purchase));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->purchaseService->deletePurchase($request->user(), $id);
        return $this->successResponse('Purchase deleted successfully.');
    }
}
