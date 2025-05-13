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

    public function get_questions(?int $quiz_id): void
    {
        if (!$quiz_id)
        {
            JsonResponse::error("Missing quiz ID.", 400);
	        return ;
        }
        $questions = $this->question_service->get_valid_questions($quiz_id);
        JsonResponse::success(['questions' => $questions], 'Valid Questions');
    }

    public function create_question(array $data): void
    {
        if (empty($data))
        {
            JsonResponse::error("Request body is empty", 400);
            return ;
        }
        if (!isset($data['quiz_id']) || !is_numeric($data['quiz_id']))
        {
            JsonResponse::error("Missing or invalid quiz ID.", 400);
	        return ;
        }
        if (empty($data['text']))
        {
            JsonResponse::error("Question is empty.", 400);
	        return ;
        }
        $data = normalize_create_question_data($data);
        if ($this->question_service->create_question($data))
            JsonResponse::success(null, 'Question created successfully');
		else
            JsonResponse::error('Create failed', 400);
    }

    public function edit_question(?int $id, array $data): void
    {
        if (empty($data) || !$id)
        {
            JsonResponse::error("Request body is empty or ID is missing", 400);
            return ;
        }
        if ($this->question_service->edit_question($id, $data))
            JsonResponse::success(null, 'Question updated successfully');
		else
            JsonResponse::error('Update failed', 400);
    }

    public function delete_question(?int $id): void
    {
        if (!$id)
        {
            JsonResponse::error("Missing ID", 400);
            return ;
        }
        if ($this->question_service->delete_question($id))
            JsonResponse::success(null, 'Question deleted successfully');
		else
            JsonResponse::error('Delete failed', 400);
    }
}
