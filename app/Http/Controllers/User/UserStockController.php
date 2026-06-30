<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Resources\StockResource;
use App\Services\StockService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserStockController extends Controller
{
    use ApiResponse;

    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of the user's stocks.
     */
    public function index(Request $request): JsonResponse
    {
        $stocks = $this->stockService->getStocksForUser($request->user());
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
}
