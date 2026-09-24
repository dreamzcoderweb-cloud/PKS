<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\StockResource;
use App\Http\Resources\PurchaseStockResource;
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
        $brandName = $request->query('brand_name') ?? $request->query('brand');
        $stocks = $this->stockService->getStocksForUser(
            $request->user(),
            $brandName,
            $request->query('from'),
            $request->query('to'),
            $request->query('branch_id')
        );
        $purchaseStocks = $this->stockService->getPurchaseStocksForUser(
            $request->user(),
            $brandName,
            $request->query('from'),
            $request->query('to'),
            $request->query('branch_id')
        );
        // Merge both collections and remove duplicates
        $mergedStocks = $stocks->concat($purchaseStocks)->unique('id')->values();
        return $this->successResponse('Stocks retrieved successfully.', [
             'stock_list' => StockResource::collection($mergedStocks),
        ]);
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

    /**
     * Get stock buy details (Customer name, stock, bag, rate, buy date).
     */
    public function getBuyDetails(Request $request, string $id): JsonResponse
    {
        $details = $this->stockService->getStockBuyDetails($request->user(), $id);
        return $this->successResponse('Stock details retrieved successfully.', $details);
    }

    /**
     * Get stock sale details / sale report (Dealers, bags, sales list, remaining stock).
     */
    public function getSaleDetails(Request $request, string $id): JsonResponse
    {
        $details = $this->stockService->getStockSaleDetails($request->user(), $id);
        return $this->successResponse('Stock sale details retrieved successfully.', $details);
    }

    /**
     * Generate PDF for stock buy details.
     */
    public function generateBuyDetailsPdf(Request $request, string $id)
    {
        $pdf = $this->stockService->generateStockBuyDetailsPdf($request->user(), $id);
        return $pdf->download('stock-details-' . $id . '.pdf');
    }

    /**
     * Generate PDF for stock sale details / sale report.
     */
    public function generateSaleDetailsPdf(Request $request, string $id)
    {
        $pdf = $this->stockService->generateStockSaleDetailsPdf($request->user(), $id);
        return $pdf->download('stock-sale-report-' . $id . '.pdf');
    }
}

