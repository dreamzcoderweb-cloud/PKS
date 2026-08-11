<?php

namespace App\Http\Controllers\Mob\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Resources\Mob\User\UserMobSaleResource;
use App\Services\SaleService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserMobSaleController extends Controller
{
    use ApiResponse;

    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request): JsonResponse
    {
        $sales = $this->saleService->getSalesForUser(
            $request->user(),
            $request->query('from'),
            $request->query('to'),
            $request->query('branch_id'),
            $request->query('saletype') ?? $request->query('sale_type')
        );
        return $this->successResponse('Sales retrieved successfully.', UserMobSaleResource::collection($sales));
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $sale = $this->saleService->createSale($request->user(), $request->validated());
        return $this->successResponse('Sale created successfully.', new UserMobSaleResource($sale), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $sale = $this->saleService->getSaleDetails($request->user(), $id);
        return $this->successResponse('Sale details retrieved successfully.', new UserMobSaleResource($sale));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $force = $request->boolean('force');
        $this->saleService->deleteSale($request->user(), $id, $force);
        return $this->successResponse('Sale deleted successfully.');
    }

    public function generatePdf(Request $request, int $id): JsonResponse
    {
        $sale = $this->saleService->getSaleDetails($request->user(), $id);
        return $this->successResponse('Gatepass details retrieved successfully.', new UserMobSaleResource($sale));
    }
}
