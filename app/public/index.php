<?php

require_once __DIR__ . '/../src/Database/Setup.php';

setup_database();

if ($_SERVER['REQUEST_URI'] === '/')
{
    echo "<h1>Welcome to the Quiz App!</h1>";
    echo "<p>It seems you are visiting the home page. If you're looking for the API, head over to <strong>/api</strong> routes.</p>";
    exit;
}

require_once __DIR__ . '/../src/Controller/QuizController.php';
require_once __DIR__ . '/../src/Controller/QuestionController.php';
require_once __DIR__ . '/../src/Controller/AuthController.php';
require_once __DIR__ . '/../src/Util/JsonResponse.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch (true)
{
    case preg_match('#^/api/auth/login$#', $uri):
        (new AuthController())->login(json_request_body());
        break;

    case preg_match('#^/api/auth/register$#', $uri):
        (new AuthController())->register(json_request_body());
        break;

    case $uri === '/api/quizzes' && $method === 'GET':
        (new QuizController())->get_quizzes();
        break;

    case $uri === '/api/quizzes' && $method === 'POST':
        (new QuizController())->create_quiz();
        break;

    case preg_match('#^/api/quizzes/(\d+)$#', $uri, $id_match) && $method === 'DELETE':
        (new QuizController())->delete_quiz((int)$id_match[1]);
        break;

    case $uri === '/api/questions' && $method === 'GET':
        (new QuestionController())->get_questions();
        break;

    case $uri === '/api/questions' && $method === 'POST':
        (new QuestionController())->create_question();
        break;

    case preg_match('#^/api/questions/(\d+)$#', $uri, $id_match) && $method === 'DELETE':
        (new QuestionController())->delete_question((int)$id_match[1]);
        break;

    default:
        JsonResponse::error('Route not found', 404);
        break;
}

function json_request_body(): array
{
    return json_decode(file_get_contents('php://input'), true) ?? [];
}
