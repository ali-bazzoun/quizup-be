<?php

require_once __DIR__ . '/../src/Database/Setup.php';
require_once __DIR__ . '/../src/Controller/QuizController.php';
require_once __DIR__ . '/../src/Controller/QuestionController.php';
require_once __DIR__ . '/../src/Controller/AuthController.php';
require_once __DIR__ . '/../src/Util/JsonResponse.php';

setup_database();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

function json_request_body(): array
{
    return json_decode(file_get_contents('php://input'), true) ?? [];
}

// Home
if ($uri === '/')
{
    echo "<h1>Welcome to QuizUp!</h1>";
    echo "<p>If you're looking for the API, head over to <strong>/quizup/api</strong> routes.</p>";
    exit;
}

// Auth Routes
else if ($uri === '/quizup/api/auth/login' && $method === 'POST')
    (new AuthController())->login(json_request_body());

else if ($uri === '/quizup/api/auth/register' && $method === 'POST')
    (new AuthController())->register(json_request_body());

// Quiz Routes
else if ($uri === '/quizup/api/quizzes' && $method === 'GET')
    (new QuizController())->get_quizzes();

else if ($uri === '/quizup/api/quizzes' && $method === 'POST')
    (new QuizController())->create_quiz(json_request_body());

else if (preg_match('#^/quizup/api/quiz/(\d+)$#', $uri, $match) && $method === 'PUT')
    (new QuestionController())->edit_question((int) $match[1], json_request_body());

else if (preg_match('#^/quizup/api/quizzes/(\d+)$#', $uri, $match) && $method === 'DELETE')
    (new QuizController())->delete_quiz((int) $match[1]);

// Question Routes
else if ($uri === '/quizup/api/questions' && $method === 'GET')
{
    $quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : null;
    (new QuestionController())->get_questions($quiz_id);
}

else if ($uri === '/quizup/api/questions' && $method === 'POST')
    (new QuestionController())->create_question();

else if (preg_match('#^/quizup/api/questions/(\d+)$#', $uri, $match) && $method === 'PUT')
    (new QuestionController())->edit_question((int) $match[1], json_request_body());

else if (preg_match('#^/quizup/api/questions/(\d+)$#', $uri, $match) && $method === 'DELETE')
    (new QuestionController())->delete_question((int) $match[1]);

// Not Found
else
    JsonResponse::error('Route not found', 404);
