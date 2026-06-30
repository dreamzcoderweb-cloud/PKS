<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Services\BranchService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserBranchController extends Controller
{
    use ApiResponse;

    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of all branches.
     */
    public function index(): JsonResponse
    {
        $branches = $this->branchService->getAllBranches();
        return $this->successResponse('Branches retrieved successfully.', BranchResource::collection($branches));
    }

    /**
     * Store a newly created branch.
     */
    public function store(StoreBranchRequest $request): JsonResponse
    {
        $branch = $this->branchService->createBranch($request->validated());
        return $this->successResponse('Branch created successfully.', new BranchResource($branch), 201);
    }

    /**
     * Display the specified branch.
     */
    public function show(int $id): JsonResponse
    {
        $branch = $this->branchService->getBranchDetails($id);
        return $this->successResponse('Branch details retrieved successfully.', new BranchResource($branch));
    }

    /**
     * Update the specified branch.
     */
    public function update(UpdateBranchRequest $request, int $id): JsonResponse
    {
        $branch = $this->branchService->updateBranch($id, $request->validated());
        return $this->successResponse('Branch updated successfully.', new BranchResource($branch));
    }

    /**
     * Remove the specified branch.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->branchService->deleteBranch($id);
        return $this->successResponse('Branch deleted successfully.');
    }
}
