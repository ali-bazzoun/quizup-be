<?php

require_once __DIR__ . '/../services/AuthService.php';

class AuthController
{
    private AuthService     $auth_service;

    public function __construct()
    {
        $this->auth_service = new AuthService();
    }

    public function login(array $request): void
    {
        $user = $this->auth_service->attempt_login($request['email'], $request['password']);
        if (!$user)
        {
            JSONResponse::error('Invalid credentials', 401);
            return;
        }
        JSONResponse::success(['user' => $user], 'Login successful');
    }

    public function register(array $request): void
    {
        $user = $this->auth_service->attempt_register($request['email'], $request['password']);
        if (!$user)
        {
            JSONResponse::error('Invalid credentials', 401);
            return;
        }
        JSONResponse::success(['user' => $user], 'Register successful');
    }
}
