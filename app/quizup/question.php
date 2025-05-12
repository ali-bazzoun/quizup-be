<?php

require_once __DIR__ . '/../src/services/QuestionService.php';
require_once __DIR__ . '/../src/utils/response.php';
require_once __DIR__ . '/../src/utils/normalizer.php';

$questions_service = new QuizService();
$method = $_SERVER['REQUEST_METHOD'];

try
{
	switch ($method)
	{
		case 'GET':
			$questions = $questions_service->get_valid_questions();
			JsonResponse::success(['questions' => $questions], 'Valid Questions');
			break;
		
		case 'POST':
			$data = json_decode(file_get_contents('php://input'), true);
            $data = normalize_create_question_data($data);
			if ($quiz_id && $questions_service->create_question())
			{
				JsonResponse::success(null, 'Question created successfully');
			}
			else
			{
				JsonResponse::error('Create failed', 400);
			}

		case 'PUT':
			$data = json_decode(file_get_contents('php://input'), true);
			if ($data && $question_service->update_question($data))
			{
                JsonResponse::success(null, 'Question updated successfully');
            }
			else
			{
                JsonResponse::error('Update failed', 400);
            }
            break;
		
		case 'DELETE':
		    $id = $_GET['id'] ?? null;            
            if ($id && $question_service->deleteQuiz((int)$id))
			{
                JsonResponse::success(null, 'Question deleted successfully');
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
