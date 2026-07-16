<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Resources\SaleResource;
use App\Services\SaleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSaleController extends Controller
{
    use ApiResponse;

    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request): JsonResponse
    {
        $sales = $this->saleService->getSalesForUser($request->user());
        return $this->successResponse('Sales retrieved successfully.', SaleResource::collection($sales));
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $sale = $this->saleService->createSale($request->user(), $request->validated());
        return $this->successResponse('Sale created successfully.', new SaleResource($sale), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $sale = $this->saleService->getSaleDetails($request->user(), $id);
        return $this->successResponse('Sale details retrieved successfully.', new SaleResource($sale));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $force = $request->boolean('force');
        $this->saleService->deleteSale($request->user(), $id, $force);
        return $this->successResponse('Sale deleted successfully.');
    }
}
