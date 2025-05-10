<?php

require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../utils/JsonResponse.php';

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
            JsonResponse::error('Invalid credentials', 401);
            return;
        }
        JsonResponse::success('Login successful', ['user' => $user]);
    }

    public function register(array $request): void
    {
        // You'd validate and call AuthService::register() here
    }
}
