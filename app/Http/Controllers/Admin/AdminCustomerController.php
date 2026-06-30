<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    use ApiResponse;

    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of all customers.
     */
    public function index(Request $request): JsonResponse
    {
        $customers = $this->customerService->getCustomersForUser($request->user());
        return $this->successResponse('Customers retrieved successfully.', CustomerResource::collection($customers));
    }

    /**
     * Store a newly created customer.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = $this->customerService->createCustomer($request->user(), $request->validated());
        return $this->successResponse('Customer created successfully.', new CustomerResource($customer), 201);
    }

    /**
     * Display the specified customer.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $customer = $this->customerService->getCustomerDetails($request->user(), $id);
        return $this->successResponse('Customer details retrieved successfully.', new CustomerResource($customer));
    }

    /**
     * Update the specified customer.
     */
    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        $customer = $this->customerService->updateCustomer($request->user(), $id, $request->validated());
        return $this->successResponse('Customer updated successfully.', new CustomerResource($customer));
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->customerService->deleteCustomer($request->user(), $id);
        return $this->successResponse('Customer deleted successfully.');
    }
}
