<?php

require_once __DIR__ . '/../Service/AuthService.php';
require_once __DIR__ . '/../Util/RegisterValidator.php';
require_once __DIR__ . '/../Util/LoginValidator.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Exception/RegisterExistedEmailException.php';
require_once __DIR__ . '/../Util/Logging.php';

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
            return ;
        }
        $errors = RegisterValidator::validate($request);
        if ($errors)
        {
            error_handler('Exception', "Validation failed: " . $errors[0], __FILE__, __LINE__);
            JsonResponse::error('Invalid input', 422);
            return;
        }
        try
        {
            $user = $this->auth_service->attempt_register($request['email'], $request['password']);
            if (!$user)
            {
                JsonResponse::error('Registration failed');
                return ;
            }
            JsonResponse::success(['user' => $user], 'Register successful');
            return ;
        }
        catch (RegisterExistedEmailException $e)
        {
            JsonResponse::error("Registered email.", 400);
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
            JsonResponse::error('Unexpected error', 500);
            return ;
        }
    }

    public function login(array $request): void
    {
        if (!isset($request['email'], $request['password']))
        {
            JsonResponse::error('Invalid request format', 400);
            return ;
        }
        $errors = LoginValidator::validate($request);
        if ($errors)
        {
            error_handler('Exception', "Validation failed: " . $errors[0], __FILE__, __LINE__);
            JsonResponse::error('Invalid input', 422);
            return ;
        }
        try
        {
            $user = $this->auth_service->attempt_login($request['email'], $request['password']);
            if (!$user)
            {
                JsonResponse::error('Invalid credentials', 401);
                return ;
            }
            JsonResponse::success(['user' => $user], 'Login successful');
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
            JsonResponse::error('Unexpected error', 500);
            return ;
        }
    }
}
