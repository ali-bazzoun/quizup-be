<?php

require_once __DIR__ . '/../Service/QuestionService.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/QuestionValidator.php';
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
        try
        {
            $questions = $this->question_service->get_valid_questions($quiz_id);
            JsonResponse::success(['questions' => $questions], 'Valid Questions');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error("Get questions failed.", 400);
    }

    public function create_question(array $data): void
    {
        $errors = QuestionValidator::validate_create_data($data);
        if ($errors)
        {
            error_handler('Exception', "Validation failed: " . $errors[0], __FILE__, __LINE__);
            JsonResponse::error('Invalid input', 422);
            return ;
        }
        try
        {
            if ($this->question_service->create_question($data))
                JsonResponse::success(null, 'Question created successfully');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error("create questions failed.", 400);
    }

    public function edit_question(?int $id, array $data): void
    {
        if (!id)
        {
            JsonResponse::error("Missing ID.", 400);
            return ;
        }
        $errors = QuestionValidator::validate_update_data($data);
        if ($errors)
        {
            error_handler('Exception', "Validation failed: " . $errors[0], __FILE__, __LINE__);
            JsonResponse::error('Invalid input', 422);
            return ;
        }
        try
        {
            if ($this->question_service->edit_question($id, $data))
                JsonResponse::success(null, 'Question updated successfully');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error('Update failed', 400);
    }

    public function delete_question(?int $id): void
    {
        if (!$id)
        {
            JsonResponse::error("Missing ID", 400);
            return ;
        }
        try
        {
            if ($this->question_service->delete_question($id))
                JsonResponse::success(null, 'Question deleted successfully');
            return ;
        }
        catch (\Exception $e)
        {
            error_handler('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
        }
        JsonResponse::error('Delete failed', 400);
    }
}
