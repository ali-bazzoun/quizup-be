<?php

require_once __DIR__ . '/../Service/QuizService.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/Normalizer.php';

class QuizController
{
    private QuizService $quiz_service;

    public function __construct()
    {
        $this->quiz_service = new QuizService();
    }

    public function get_quizzes(): void
    {
        $quizzes = $this->quiz_service->get_valid_quizzes();
        JsonResponse::success(['quizzes' => $quizzes], 'Valid Quizzes');
    }

    public function create_quiz(array $data): void
    {
        if (empty($data))
        {
            JsonResponse::error("Request body is empty", 400);
            return ;
        }
        if (empty($data['title']))
        {
            JsonResponse::error("title is empty", 400);
            return ;
        }
        $data = normalize_create_quiz_data($data);
        if ($data && $this->quiz_service->create_quiz($data))
            JsonResponse::success(null, 'Quiz created successfully');
        else
            JsonResponse::error('Create failed', 400);
    }

    public function edit_quiz(?int $id, array $data): void
    {
        if (empty($data) || !$id)
        {
            JsonResponse::error("Request body is empty or ID is missing", 400);
            return ;
        }
        if ($data && $this->quiz_service->edit_quiz($data))
            JsonResponse::success(null, 'Quiz updated successfully');
        else
            JsonResponse::error('Update failed', 400);
    }

    public function delete_quiz(?int $id): void
    {
        if (!$id)
        {
            JsonResponse::error("Missing ID", 400);
            return ;
        }
        if ($this->quiz_service->delete_quiz($id))
            JsonResponse::success(null, 'Quiz deleted successfully');
        else
            JsonResponse::error('Delete failed', 400);
    }
}
