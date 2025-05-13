<?php

require_once __DIR__ . '/../src/services/AuthService.php';
require_once __DIR__ . '/../src/utils/response.php';
require_once __DIR__ . '/../src/utils/LoginValidator.php';

$requestBody = file_get_contents('php://input');
$request = json_decode($requestBody, true);

if (!$request || !isset($request['email']) || !isset($request['password']))
{
    JsonResponse::error('Invalid request format', 400);
    exit;
}

$errors = LoginValidator::validate($request);
if ($errors)
{
    log_error("Validation failed: " . print_r($errors, true), 'ERROR');
    JsonResponse::error('Invalid input', 422);
    exit;
}

$auth_service = new AuthService();

try
{
    $user = $auth_service->attempt_login($request['email'], $request['password']);
    if (!$user)
    {
        JsonResponse::error('Invalid credentials', 401);
        return;
    }
    JsonResponse::success(['user' => $user], 'Login successful');
}
catch (\Exception $e)
{
    log_error("Exception caught during logging in", 'ERROR', $e);
    JsonResponse::error('Unexpected error', 500);
}
