<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Services\PurchaseService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPurchaseController extends Controller
{
    use ApiResponse;

    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request): JsonResponse
    {
        $from = $request->input('from_date') ?? $request->input('from') ?? $request->input('start_date');
        $to = $request->input('to_date') ?? $request->input('to') ?? $request->input('end_date');
        $branchId = $request->input('branch_id');
        $dealer = $request->input('dealer_name') ?? $request->input('dealer') ?? $request->input('search');

        $purchases = $this->purchaseService->getPurchasesForUser(
            $request->user(),
            $from,
            $to,
            $branchId,
            $dealer
        );
        return $this->successResponse('Purchases retrieved successfully.', PurchaseResource::collection($purchases));
    }

    public function generatePurchaseReportPdf(Request $request)
    {
        $from = $request->input('from_date') ?? $request->input('from') ?? $request->input('start_date');
        $to = $request->input('to_date') ?? $request->input('to') ?? $request->input('end_date');
        $branchId = $request->input('branch_id');
        $dealer = $request->input('dealer_name') ?? $request->input('dealer') ?? $request->input('search');

        if ($request->query('format') === 'json') {
            $data = $this->purchaseService->getPurchaseReportData(
                $request->user(),
                $from,
                $to,
                $branchId,
                $dealer
            );
            return $this->successResponse('Purchase report retrieved successfully.', $data);
        }

        $pdf = $this->purchaseService->generatePurchaseReportPdf(
            $request->user(),
            $from,
            $to,
            $branchId,
            $dealer
        );

        $filename = 'purchase-report-' . now()->format('d-m-Y') . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    public function store(StorePurchaseRequest $request): JsonResponse
    {
        $purchase = $this->purchaseService->createPurchase($request->user(), $request->validated());
        return $this->successResponse('Purchase created successfully.', new PurchaseResource($purchase), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $purchase = $this->purchaseService->getPurchaseDetails($request->user(), $id);
        return $this->successResponse('Purchase details retrieved successfully.', new PurchaseResource($purchase));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->purchaseService->deletePurchase($request->user(), $id);
        return $this->successResponse('Purchase deleted successfully.');
    }
}
