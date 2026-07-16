<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Services\SaleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSaleController extends Controller
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

    public function update(UpdateSaleRequest $request, int $id): JsonResponse
    {
        $sale = $this->saleService->updateSale($request->user(), $id, $request->validated());
        return $this->successResponse('Sale updated successfully.', new SaleResource($sale));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->saleService->deleteSale($request->user(), $id);
        return $this->successResponse('Sale deleted successfully.');
    }
}
