<?php

require_once '../controllers/AuthController.php';

$requestBody = file_get_contents('php://input');
$request = json_decode($requestBody, true);

if (!$request || !isset($request['email']) || !isset($request['password'])) {
    JsonResponse::error('Invalid request format', 400);
    exit;
}

$controller = new AuthController();
$controller->login($request);
