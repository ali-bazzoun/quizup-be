<?php

require_once __DIR__ . '/../Service/QuizService.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/normalizer.php';

class QuizController
{
    private QuizService $quiz_service;

    public function __construct()
    {
        $this->quiz_service = new QuizService();
    }

    private function get_quizzes(): void
    {
        $quizzes = $this->quiz_service->get_valid_quizzes();
        JsonResponse::success(['quizzes' => $quizzes], 'Valid Quizzes');
    }

    private function create_quiz(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $data = normalize_create_quiz_data($data);
        if ($data && $this->quiz_service->create_quiz($data))
        {
            JsonResponse::success(null, 'Quiz created successfully');
        }
        else
        {
            JsonResponse::error('Create failed', 400);
        }
    }

    private function update_quiz(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data && $this->quiz_service->update_quiz($data))
        {
            JsonResponse::success(null, 'Quiz updated successfully');
        }
        else
        {
            JsonResponse::error('Update failed', 400);
        }
    }

    private function delete_quiz(?int $id): void
    {
        if ($id && $this->quiz_service->delete_quiz($id))
        {
            JsonResponse::success(null, 'Quiz deleted successfully');
        }
        else
        {
            JsonResponse::error('Delete failed', 400);
        }
    }
}
