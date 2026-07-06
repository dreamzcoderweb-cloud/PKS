<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new Admin.
     */
    public function register(AdminRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $role = $validated['role'] ?? 'admin';
        $user = $this->authService->register($validated, $role);
        $message = $role === 'user' ? 'User registered successfully.' : 'Admin registered successfully.';
        return $this->successResponse($message, new UserResource($user), 201);
    }

    /**
     * Authenticate Admin and issue token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials['mobile_number'], $credentials['password'], 'admin');

        return $this->successResponse('Login successful.', [
            'user' => new UserResource($result['user']),
            'token' => $result['token']
        ]);
    }

    /**
     * Log out the Admin.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return $this->successResponse('Logged out successfully.');
    }

    /**
     * Get Admin profile.
     */
    public function profile(Request $request): JsonResponse
    {
        return $this->successResponse('Profile details retrieved.', new UserResource($request->user()));
    }
}
