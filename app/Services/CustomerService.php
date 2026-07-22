<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Hash;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get customers filtered by user role.
     *
     * @param User $user
     * @return Collection
     */
    public function getCustomersForUser($user): Collection
    {
        if ($user->role === 'admin') {
            return $this->customerRepository->all();
        }

        return $this->customerRepository->findForUser($user->getOwnerId());
    }

    /**
     * Get detailed customer info if authorized.
     *
     * @param User $user
     * @param int $id
     * @return Customer
     * @throws ModelNotFoundException
     * @throws AuthorizationException
     */
    public function getCustomerDetails($user, int $id): Customer
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            throw new ModelNotFoundException("Customer not found.");
        }

        if ($user->role !== 'admin' && (int)$customer->added_by !== (int)$user->getOwnerId()) {
            throw new AuthorizationException("You are not authorized to view this customer.");
        }

        return $customer;
    }

    /**
     * Create a new customer with random unique customer_code and customer_id UUID.
     *
     * @param User $user
     * @param array $data
     * @return Customer
     */
    public function createCustomer($user, array $data): Customer
    {
        return Cache::lock('create_customer_lock', 10)->block(5, function () use ($user, $data) {
            return DB::transaction(function () use ($user, $data) {
                $data['added_by'] = $user->getOwnerId();
                $data['customer_id'] = (string) Str::uuid();
                $data['customer_code'] = $this->generateUniqueCustomerCode($user);
                if (isset($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                }

                return $this->customerRepository->create($data);
            });
        });
    }

    /**
     * Update an existing customer (Admin only).
     *
     * @param User $user
     * @param int $id
     * @param array $data
     * @return Customer
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    public function updateCustomer($user, int $id, array $data): Customer
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException("Only admins are authorized to edit customers.");
        }

        return DB::transaction(function () use ($id, $data) {
            $customer = $this->customerRepository->findById($id);

            if (!$customer) {
                throw new ModelNotFoundException("Customer not found.");
            }

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            return $this->customerRepository->update($customer, $data);
        });
    }

    /**
     * Delete a customer (Admin only).
     *
     * @param User $user
     * @param int $id
     * @return void
     * @throws AuthorizationException
     * @throws ModelNotFoundException
     */
    public function deleteCustomer($user, int $id): void
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException("Only admins are authorized to delete customers.");
        }

        DB::transaction(function () use ($id) {
            $customer = $this->customerRepository->findById($id);

            if (!$customer) {
                throw new ModelNotFoundException("Customer not found.");
            }

            $this->customerRepository->delete($customer);
        });
    }

    /**
     * Generate a unique sequential customer code based on user role.
     *
     * @param User $user
     * @return string
     */

     protected function generateUniqueCustomerCode($user): string
    {
         $lastCode = (int) Customer::selectRaw('MAX(CAST(customer_code AS UNSIGNED)) as max_code')->value('max_code');

        return (string) ($lastCode + 1);
    }
}
