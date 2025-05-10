<?php

require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../utils/JSONResponse.php';

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(array $request): void
    {
        $user = $this->authService->attemptLogin($request['email'], $request['password']);
        if (!$user) {
            JSONResponse::error('Invalid credentials', 401);
            return;
        }
        JSONResponse::success(['user' => $user], 'Login successful');
    }

    public function register(array $request): void
    {
        $user = $this->authService->attemptRegister($request['email'], $request['password']);
        if (!$user) {
            JSONResponse::error('Invalid credentials', 401);
            return;
        }
        JSONResponse::success(['user' => $user], 'Register successful');
    }
}
