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
        $sales = $this->saleService->getSalesForUser(
            $request->user(),
            $request->query('from'),
            $request->query('to'),
            $request->query('branch_id'),
            $request->query('saletype') ?? $request->query('sale_type'),
            $request->query('dealer_name') ?? $request->query('dealer') ?? $request->query('search')
        );
        return $this->successResponse('Sales retrieved successfully.', SaleResource::collection($sales));
    }

    public function generateSalesReportPdf(Request $request)
    {
        if ($request->query('format') === 'json') {
            $data = $this->saleService->getSalesReportData(
                $request->user(),
                $request->query('from_date'),
                $request->query('to_date'),
                $request->query('branch_id'),
                $request->query('saletype') ?? $request->query('sale_type'),
                $request->query('dealer_name') ?? $request->query('dealer') ?? $request->query('search')
            );
            return $this->successResponse('Sales report retrieved successfully.', $data);
        }

        $pdf = $this->saleService->generateSalesReportPdf(
            $request->user(),
            $request->query('from_date'),
            $request->query('to_date'),
            $request->query('branch_id'),
            $request->query('saletype') ?? $request->query('sale_type'),
            $request->query('dealer_name') ?? $request->query('dealer') ?? $request->query('search')
        );

        $filename = 'sales-report-' . now()->format('d-m-Y') . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
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

    public function generatePdf(Request $request, int $id)
    {
        $pdf = $this->saleService->generateGatepassPdf($request->user(), $id);
        return $pdf->download('gatepass-sale-' . $id . '.pdf');
    }
}
