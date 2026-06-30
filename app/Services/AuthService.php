<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user (admin or user).
     *
     * @param array $data
     * @param string $role
     * @return User
     */
    public function register(array $data, string $role): User
    {
        return DB::transaction(function () use ($data, $role) {
            $data['password'] = Hash::make($data['password']);
            $data['role'] = $role;
            return $this->userRepository->create($data);
        });
    }

    /**
     * Authenticate user/admin and generate Sanctum token.
     *
     * @param string $email
     * @param string $password
     * @param string $role
     * @return array
     * @throws ValidationException
     */
    public function login(string $email, string $password, string $role): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // Verify that the user has the matching role for this login flow
        if ($user->role !== $role) {
            throw ValidationException::withMessages([
                'email' => ["Access denied. This account does not have {$role} privileges."],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Log out current user (revoke tokens).
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
