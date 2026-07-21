<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a user by mobile number.
     *
     * @param string $mobile_number
     * @return User|null
     */
    public function findByMobileNumber(string $mobile_number): ?User
    {
        return User::where('mobile_number', $mobile_number)->first();
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find a user by email or mobile number.
     *
     * @param string $identifier
     * @return User|null
     */
    public function findByIdentifier(string $identifier): ?User
    {
        return User::where('email', $identifier)
            ->orWhere('mobile_number', $identifier)
            ->first();
    }
}
