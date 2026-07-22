<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * Get all customers.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Customer::with(['branch', 'user'])->get();
    }

    /**
     * Get customers owned by a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function findForUser(int $userId): Collection
    {
        return Customer::with(['branch', 'user'])->where('added_by', $userId)->get();
    }

    /**
     * Find customer by ID.
     *
     * @param int $id
     * @return Customer|null
     */
    public function findById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Update an existing customer.
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);
        return $customer;
    }

    /**
     * Delete a customer.
     *
     * @param Customer $customer
     * @return bool
     */
    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }

    /**
     * Find customer by email or mobile number.
     *
     * @param string $identifier
     * @return Customer|null
     */
    public function findByIdentifier(string $identifier): ?Customer
    {
        return Customer::where('email', $identifier)
            ->orWhere('mobile_number', $identifier)
            ->first();
    }
}
