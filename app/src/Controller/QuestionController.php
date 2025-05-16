<?php

require_once __DIR__ . '/../Service/QuestionService.php';
require_once __DIR__ . '/../Util/JsonResponse.php';
require_once __DIR__ . '/../Util/QuestionValidator.php';
require_once __DIR__ . '/../Exception/IdNotExistException.php';

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
        catch (IdNotExistException $e)
        {
            JsonResponse::error($e->getMessage(), 409);
            return ;
        }
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
            else
                JsonResponse::error("create questions failed.", 400);
            return ;
        }
        catch (IdNotExistException $e)
        {
            JsonResponse::error($e->getMessage(), 409);
            return ;
        }
    }

    public function edit_question(?int $id, array $data): void
    {
        if (!$id)
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
            else
                JsonResponse::error('Update failed', 400);
            return ;
        }
        catch (IdNotExistException $e)
        {
            JsonResponse::error($e->getMessage(), 409);
            return ;
        }
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
            else
                JsonResponse::error('Delete failed', 400);
            return ;
        }
        catch (IdNotExistException $e)
        {
            JsonResponse::error($e->getMessage(), 409);
            return ;
        }
    }
}
