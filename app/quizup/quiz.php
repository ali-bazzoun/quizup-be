<?php

require_once __DIR__ . '/../src/services/QuizService.php';
require_once __DIR__ . '/../src/utils/response.php';
require_once __DIR__ . '/../src/utils/normalizer.php';

$quiz_service = new QuizService();
$method = $_SERVER['REQUEST_METHOD'];

try 
{
    switch ($method)
	{
        case 'GET':
            $quizzes = $quiz_service->get_valid_quizzes();
            JsonResponse::success(['quizzes' => $quizzes], 'Valid Quizzes');
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $data = normalize_create_quiz_data($data);
            if ($data && $quiz_service->create_quiz($data))
            {
                JsonResponse::success(null, 'Quiz created successfully');
            }
            else
            {
                JsonResponse::error('Create failed', 400);
            }

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data && $quiz_service->update_quiz(($data))
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
