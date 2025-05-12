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
            JsonResponse::success(['quizzes' => $quizzes], 'Valid Quizzes');
            break;

        case 'PUT':
            $id = $_GET['id'] ?? null;
            $data = json_decode(file_get_contents('php://input'), true);

            if ($id && $data && $quiz_service->updateQuiz((int)$id, $data))
			{
                JsonResponse::success(null, 'Quiz updated successfully');
            }
			else
			{
                JsonResponse::error('Update failed', 400);
            }
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            if ($id && $quiz_service->deleteQuiz((int)$id))
			{
                JsonResponse::success(null, 'Quiz deleted successfully');
            }
			else
			{
                JsonResponse::error('Delete failed', 400);
            }
            break;

        default:
            JsonResponse::error('Method not allowed', 405);
    }
}
catch (Throwable $e)
{
    JsonResponse::error("Server error: " . $e->getMessage(), 500);
}
