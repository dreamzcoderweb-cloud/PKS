<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
    /**
     * Get all customers.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get customers owned by a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function findForUser(int $userId): Collection;

    /**
     * Find customer by ID.
     *
     * @param int $id
     * @return Customer|null
     */
    public function findById(int $id): ?Customer;

    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer;

    /**
     * Update an existing customer.
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function update(Customer $customer, array $data): Customer;

    /**
     * Delete a customer.
     *
     * @param Customer $customer
     * @return bool
     */
    public function delete(Customer $customer): bool;
}
