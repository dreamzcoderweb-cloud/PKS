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
    public function getCustomersForUser(User $user): Collection
    {
        if ($user->role === 'admin') {
            return $this->customerRepository->all();
        }

        return $this->customerRepository->findForUser($user->id);
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
    public function getCustomerDetails(User $user, int $id): Customer
    {
        $customer = $this->customerRepository->findById($id);

        if (!$customer) {
            throw new ModelNotFoundException("Customer not found.");
        }

        if ($user->role !== 'admin' && $customer->added_by !== $user->id) {
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
    public function createCustomer(User $user, array $data): Customer
    {
        return Cache::lock('create_customer_lock', 10)->block(5, function () use ($user, $data) {
            return DB::transaction(function () use ($user, $data) {
                $data['added_by'] = $user->id;
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
    public function updateCustomer(User $user, int $id, array $data): Customer
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
    public function deleteCustomer(User $user, int $id): void
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

     protected function generateUniqueCustomerCode(User $user): string
    {
        if ($user->role === 'admin') {
            $prefix = 'CUSTOMER_A';
            $startPos = 11;
        } else {
            $prefix = 'CUSTOMER_';
            $startPos = 10;
        }

        $lastNumber = Customer::where('customer_code', 'like', $prefix . '%')
            ->when($user->role !== 'admin', function ($query) {
                return $query->where('customer_code', 'not like', 'CUSTOMER_A%');
            })
            ->selectRaw("MAX(CAST(SUBSTRING(customer_code, {$startPos}) AS UNSIGNED)) as max_num")
            ->value('max_num');

        $nextNumber = ($lastNumber ?? 0) + 1;

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
