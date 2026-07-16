<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\StockResource;
use App\Services\StockService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    use ApiResponse;

    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of all stocks.
     */
    public function index(Request $request): JsonResponse
    {
        $brandName = $request->query('brand_name') ?? $request->query('brand');
        $stocks = $this->stockService->getStocksForUser($request->user(), $brandName);
        return $this->successResponse('Stocks retrieved successfully.', StockResource::collection($stocks));
    }

    /**
     * Store a newly created stock.
     */
    public function store(StoreStockRequest $request): JsonResponse
    {
        $stock = $this->stockService->createStock($request->user(), $request->validated());
        return $this->successResponse('Stock created successfully.', new StockResource($stock), 201);
    }

    /**
     * Display the specified stock.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $stock = $this->stockService->getStockDetails($request->user(), $id);
        return $this->successResponse('Stock details retrieved successfully.', new StockResource($stock));
    }

    /**
     * Update the specified stock.
     */
    public function update(UpdateStockRequest $request, int $id): JsonResponse
    {
        $stock = $this->stockService->updateStock($request->user(), $id, $request->validated());
        return $this->successResponse('Stock updated successfully.', new StockResource($stock));
    }

    /**
     * Remove the specified stock.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->stockService->deleteStock($request->user(), $id);
        return $this->successResponse('Stock deleted successfully.');
    }
}
