<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new User.
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated(), 'user');
        return $this->successResponse('User registered successfully.', new UserResource($user), 201);
    }

    /**
     * Authenticate User and issue token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials['mobile_number'], $credentials['password'], 'user');

        return $this->successResponse('Login successful.', [
            'user' => new UserResource($result['user']),
            'token' => $result['token']
        ]);
    }

    /**
     * Log out the User.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return $this->successResponse('Logged out successfully.');
    }

    /**
     * Get User profile.
     */
    public function profile(Request $request): JsonResponse
    {
        return $this->successResponse('Profile details retrieved.', new UserResource($request->user()));
    }
}
