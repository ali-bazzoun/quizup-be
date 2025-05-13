<?php

require_once __DIR__ . '/../Service/AuthService.php';
require_once __DIR__ . '/../Util/RegisterValidator.php';
require_once __DIR__ . '/../Util/LoginValidator.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/logging.php';

class AuthController
{
    private AuthService $auth_service;

    public function __construct()
    {
        $this->auth_service = new AuthService();
    }

    public function register(array $request): void
    {
        if (!isset($request['email'], $request['password']))
        {
            JsonResponse::error('Invalid request format', 400);
            return;
        }

        $errors = RegisterValidator::validate($request);
        if ($errors)
        {
            log_error("Validation failed: " . print_r($errors, true), 'ERROR');
            JsonResponse::error('Invalid input', 422);
            return;
        }

        try
        {
            $user = $this->auth_service->attempt_register($request['email'], $request['password']);
            if (!$user)
            {
                JsonResponse::error('Invalid credentials', 401);
                return;
            }
            JsonResponse::success(['user' => $user], 'Register successful');
        }
        catch (\Exception $e)
        {
            log_error("Exception caught during registration", 'ERROR', $e);
            JsonResponse::error('Unexpected error', 500);
        }
    }

    public function login(array $request): void
    {
        if (!isset($request['email'], $request['password']))
        {
            JsonResponse::error('Invalid request format', 400);
            return;
        }

        $errors = LoginValidator::validate($request);
        if ($errors)
        {
            log_error("Validation failed: " . print_r($errors, true), 'ERROR');
            JsonResponse::error('Invalid input', 422);
            return;
        }

        try
        {
            $user = $this->auth_service->attempt_login($request['email'], $request['password']);
            if (!$user)
            {
                JsonResponse::error('Invalid credentials', 401);
                return;
            }
            JsonResponse::success(['user' => $user], 'Login successful');
        }
        catch (\Exception $e)
        {
            log_error("Exception caught during login", 'ERROR', $e);
            JsonResponse::error('Unexpected error', 500);
        }
    }
}
