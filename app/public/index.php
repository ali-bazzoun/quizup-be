<?php

require_once __DIR__ . '/../src/Database/Setup.php';

setup_database();

if ($_SERVER['REQUEST_URI'] === '/')
{
    echo "<h1>Welcome to QuizUp!</h1>";
    echo "<p>If you're looking for the API, head over to <strong>/quizup/api</strong> routes.</p>";
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
    case preg_match('#^/quizup/api/auth/login$#', $uri):
        (new AuthController())->login(json_request_body());
        break;

    case preg_match('#^/quizup/api/auth/register$#', $uri):
        (new AuthController())->register(json_request_body());
        break;

    case $uri === '/quizup/api/quizzes' && $method === 'GET':
        (new QuizController())->get_quizzes();
        break;

    case $uri === '/quizup/api/quizzes' && $method === 'POST':
        (new QuizController())->create_quiz();
        break;

    case $uri === '/quizup/api/quizzes' && $method === 'PUT':
        (new QuizController())->get_quizzes(json_request_body());
        break;

    case preg_match('#^/quizup/api/quizzes/(\d+)$#', $uri, $id_match) && $method === 'DELETE':
        (new QuizController())->delete_quiz((int)$id_match[1]);
        break;

    case $uri === '/quizup/api/questions' && $method === 'GET':
        (new QuestionController())->get_questions();
        break;

    case $uri === '/quizup/api/questions' && $method === 'POST':
        (new QuestionController())->create_question();
        break;
    
    case $uri === '/quizup/api/questions' && $method === 'PUT':
        (new QuestionController())->edit_question(json_request_body());
        break;

    case preg_match('#^/quizup/api/questions/(\d+)$#', $uri, $id_match) && $method === 'DELETE':
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
