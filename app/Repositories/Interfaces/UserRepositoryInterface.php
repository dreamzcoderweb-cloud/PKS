<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Find a user by mobile number.
     *
     * @param string $mobile_number
     * @return User|null
     */
    public function findByMobileNumber(string $mobile_number): ?User;

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user by email or mobile number.
     *
     * @param string $identifier
     * @return User|null
     */
    public function findByIdentifier(string $identifier): ?User;
}
