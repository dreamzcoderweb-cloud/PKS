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

    public function register(array $data, string $role): User
    {
        return DB::transaction(function () use ($data, $role) {
            $data['password'] = Hash::make($data['password']);
            $data['role'] = $role;
            if ($role === 'admin') {
                $data['status'] = $data['status'] ?? 1;
            }
            return $this->userRepository->create($data);
        });
    }

    /**
     * Authenticate user/admin and generate Sanctum token.
     *
     * @param string|array $identifierOrData
     * @param string|null $password
     * @param string $role
     * @param int|string|null $branch_id
     * @return array
     * @throws ValidationException
     */
    public function login(string|array $identifierOrData, ?string $password = null, string $role = 'user', int|string|null $branch_id = null): array
    {
        if (is_array($identifierOrData)) {
            $identifier = $identifierOrData['email'] ?? $identifierOrData['mobile_number'] ?? null;
            $password = $identifierOrData['password'] ?? $password;
            $branch_id = $identifierOrData['branch_id'] ?? $branch_id;
        } else {
            $identifier = $identifierOrData;
        }

        // 1. Validate missing Branch ID
        if (empty($branch_id)) {
            throw ValidationException::withMessages([
                'branch_id' => ['Branch ID is required.'],
            ]);
        }

        // 2. Validate Branch ID existence in branches table
        $branchExists = \App\Models\Branch::where('branch_id', $branch_id)->exists();
        if (!$branchExists) {
            throw ValidationException::withMessages([
                'branch_id' => ['The selected Branch ID is invalid.'],
            ]);
        }

        // 3. Find user by email or mobile number
        $user = $identifier ? $this->userRepository->findByIdentifier($identifier) : null;

        if (!$user || !Hash::check($password, $user->password)) {
            $key = (is_array($identifierOrData) && isset($identifierOrData['mobile_number']) && !isset($identifierOrData['email'])) ? 'mobile_number' : 'email';
            throw ValidationException::withMessages([
                $key => ['Invalid credentials.'],
            ]);
        }

        // 4. Verify that the user has the matching role for this login flow
        if ($user->role !== $role) {
            $key = (is_array($identifierOrData) && isset($identifierOrData['mobile_number']) && !isset($identifierOrData['email'])) ? 'mobile_number' : 'email';
            throw ValidationException::withMessages([
                $key => ["Access denied. This account does not have {$role} privileges."],
            ]);
        }

        // 5. Verify that the user's assigned branch_id matches the submitted branch_id
        if ((int)$user->branch_id !== (int)$branch_id) {
            throw ValidationException::withMessages([
                'branch_id' => ['The specified Branch ID does not match this user account.'],
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
