<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repository/QuizRepository.php';
require_once __DIR__ . '/QuestionService.php';
require_once __DIR__ . '/../Util/Logging.php';

class QuizService
{
	private \PDO $db;
	private QuizRepository $quiz_repo;
	private QuestionService $question_service;

	public function __construct()
	{
        $this->db = Database::get_connection();
		$this->question_service = new QuestionService();
		$this->quiz_repo = new QuizRepository();
	}

	public function create_quiz(array $data): bool
	{
		if (empty($data['title']))
		{
			log_error("Quiz title is required.");
			return false;
		}
		$this->db->beginTransaction();
		try
		{
			$quiz = $this->quiz_repo->create($data);
			foreach ($data['questions'] ?? [] as $q_data)
			{
				$q_data['quiz_id'] = $quiz->id;
				$question = $this->question_service->create_question($q_data);
			}
			$this->db->commit();
			return true;
		}
		catch (\Throwable $e)
		{
			$this->db->rollBack();
			log_error("Failed to create quiz (rolling back).", 'CRITICAL', $e);
			return false;
		}
	}

	public function get_valid_quizzes(): array
	{
		$all_quizzes = $this->quiz_repo->all();
		$valid_quizzes = [];
		foreach ($all_quizzes as $quiz)
		{
			$questions = $this->question_service->get_valid_questions($quiz->id);
			if (count($questions) > 2)
			{
				$quiz->questions = $questions;
				$valid_quizzes[] = $quiz;
			}
		}
		return $valid_quizzes;
	}

	public function edit_quiz(array $data): bool
    {
		$id = $data['id'];
		if (!$this->quiz_repo->exists($id))
		{
			log_error("Quiz with ID $id does not exist.");
			return false;
		}
        return $this->quiz_repo->update($id, $data);
    }

    public function delete_quiz(int $id): bool
    {
		if (!$this->quiz_repo->exists($id))
		{
			log_error("Quiz with ID $id does not exist.");
			return false;
		}
        return $this->quiz_repo->delete($id);
    }
}
