<?php

require_once __DIR__ . '/../Service/QuizService.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/QuizValidator.php';
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
        try
        {
            $quizzes = $this->quiz_service->get_valid_quizzes();
            JsonResponse::success(['quizzes' => $quizzes], 'Valid Quizzes');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error('Get quizzes failed.');
    }

    public function create_quiz(array $data): void
    {
        $errors = QuizValidator::validate_create_data($data);
        if ($errors)
        {
            error_handler('Exception', "Validation failed: " . print_r($errors, true), __FILE__, __LINE__);
            JsonResponse::error('Invalid input', 422);
            return ;
        }
        try
        {
            $data = normalize_create_quiz_data($data);
            if ($this->quiz_service->create_quiz($data))
                JsonResponse::success(null, 'Quiz created successfully');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error('Create failed', 400);
    }

    public function edit_quiz(?int $id, array $data): void
    {
        if (!$id)
        {
            JsonResponse::error("ID is missing", 400);
            return ;
        }
        $errors = QuizValidator::validate_update_data($data);
        if ($errors)
        {
            error_handler('Exception', "Validation failed: " . print_r($errors, true), __FILE__, __LINE__);
            JsonResponse::error('Invalid input', 422);
            return ;
        }
        try
        {
            if ($this->quiz_service->edit_quiz($data))
                JsonResponse::success(null, 'Quiz updated successfully');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error('Update failed', 400);
    }

    public function delete_quiz(?int $id): void
    {
        if (!$id)
        {
            JsonResponse::error("Missing ID", 400);
            return ;
        }
        try
        {
            if ($this->quiz_service->delete_quiz($id))
                JsonResponse::success(null, 'Quiz deleted successfully');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error('Delete failed', 400);
    }
}
