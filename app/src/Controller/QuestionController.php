<?php

require_once __DIR__ . '/../Service/QuestionService.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/Normalizer.php';

class QuestionController
{
    private QuestionService $question_service;

    public function __construct()
    {
        $this->question_service = new QuestionService();
    }

    public function get_questions(int ?$quiz_id): void
    {
        if (!isset($quiz_id) || !is_numeric($quiz_id))
        {
	        log_error("Missing or invalid quiz ID.");
	        return false;
        }
        $questions = $this->question_service->get_valid_questions((int)$quiz_id);
        JsonResponse::success(['questions' => $questions], 'Valid Questions');
    }

    public function create_question($data): void
    {
        if (!isset($data['quiz_id']) || !is_numeric($data['quiz_id']))
        {
	        log_error("Missing or invalid quiz ID.");
	        return false;
        }
        $data = normalize_create_question_data($data);
        if ($data && $this->question_service->create_question($data))
            JsonResponse::success(null, 'Question created successfully');
		else
            JsonResponse::error('Create failed', 400);
    }

    public function edit_question($data): void
    {
        if (!isset($data['id']) || !is_numeric($data['id']))
        {
	        log_error("Missing or invalid question ID.");
	        return false;
        }
        if ($data && $this->question_service->edit_question($data))
            JsonResponse::success(null, 'Question updated successfully');
		else
            JsonResponse::error('Update failed', 400);
    }

    public function delete_question(?int $id): void
    {
        if ($id && $this->question_service->delete_question($id))
            JsonResponse::success(null, 'Question deleted successfully');
		else
            JsonResponse::error('Delete failed', 400);
    }
}
