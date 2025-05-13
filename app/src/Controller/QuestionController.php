<?php

require_once __DIR__ . '/../Service/QuestionService.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/normalizer.php';

class QuestionController
{
    private QuestionService $question_service;

    public function __construct()
    {
        $this->question_service = new QuestionService();
    }

    private function get_questions(): void
    {
        $quiz_id = $_GET['quiz_id'] ?? null;
        if (!$quiz_id)
		{
            JsonResponse::error('Quiz ID is required', 400);
            return;
        }
        $questions = $this->question_service->get_valid_questions((int)$quiz_id);
        JsonResponse::success(['questions' => $questions], 'Valid Questions');
    }

    private function create_question(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $data = normalize_create_question_data($data);
        if ($data && $this->question_service->create_question($data))
		{
            JsonResponse::success(null, 'Question created successfully');
        }
		else
		{
            JsonResponse::error('Create failed', 400);
        }
    }

    private function update_question(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data && $this->question_service->update_question($data))
		{
            JsonResponse::success(null, 'Question updated successfully');
        }
		else
		{
            JsonResponse::error('Update failed', 400);
        }
    }

    private function delete_question(?int $id): void
    {
        if ($id && $this->question_service->delete_question($id))
		{
            JsonResponse::success(null, 'Question deleted successfully');
        }
		else
		{
            JsonResponse::error('Delete failed', 400);
        }
    }
}
