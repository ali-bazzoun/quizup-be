<?php

require_once __DIR__ . '/../src/services/quiz_service.php';
require_once __DIR__ . '/../src/utils/response.php';

$quiz_service = new QuizService();
$method = $_SERVER['REQUEST_METHOD'];

try 
{
    switch ($method)
	{
        case 'GET':
            $quizzes = $quiz_service->getValidQuizzes();
            JSONResponse::success(['quizzes' => $quizzes], 'Valid Quizzes');
            break;

        case 'PUT':
            $id = $_GET['id'] ?? null;
            $data = json_decode(file_get_contents('php://input'), true);

            if ($id && $data && $quiz_service->updateQuiz((int)$id, $data))
			{
                JSONResponse::success(null, 'Quiz updated successfully');
            }
			else
			{
                JSONResponse::error('Update failed', 400);
            }
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            if ($id && $quiz_service->deleteQuiz((int)$id))
			{
                JSONResponse::success(null, 'Quiz deleted successfully');
            }
			else
			{
                JSONResponse::error('Delete failed', 400);
            }
            break;

        default:
            JSONResponse::error('Method not allowed', 405);
    }
}
catch (Throwable $e)
{
    JSONResponse::error("Server error: " . $e->getMessage(), 500);
}
