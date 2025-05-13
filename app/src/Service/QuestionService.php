<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repository/QuizRepository.php';
require_once __DIR__ . '/../Repository/QuestionRepository.php';
require_once __DIR__ . '/../Repository/OptionRepository.php';
require_once __DIR__ . '/../Util/Logging.php';

class QuestionService
{
	private \PDO $db;
	private QuizRepository $quiz_repo;
	private OptionRepository $option_repo;
	private QuestionRepository $question_repo;

	public function __construct()
	{
		$this->db = Database::get_connection();
		$this->quiz_repo = new QuizRepository();
		$this->question_repo = new QuestionRepository();
		$this->option_repo = new OptionRepository();
	}

	public function create_question(array $data): bool
	{
		$quiz_id = $data['quiz_id'];
		if (!$this->quiz_repo->exists($quiz_id))
		{
			log_error("Quiz with ID $quiz_id does not exist.");
			return false;
		}
		$this->db->beginTransaction();
		try
		{
			$question = $this->question_repo->create($data);
			foreach ($data['options'] ?? [] as $opt_data)
			{
				$opt_data['question_id'] = $question->id;
				$this->option_repo->create($opt_data);
			}
			$this->db->commit();
			return true;
		}
		catch (\Throwable $e)
		{
			$this->db->rollBack();
			log_error("Failed to create question (rolling back).", 'CRITICAL', $e);
			return false;
		}
	}

	public function get_valid_questions(int $quiz_id)
	{
		if (!$this->quiz_repo->exists($quiz_id))
		{
			log_error("Quiz with ID $quiz_id does not exist.");
			return false;
		}
		$questions =  $this->question_repo->all_by_quiz_id_with_options($quiz_id);
		$valid_questions = [];
		foreach($questions as $question)
			if (count($question->options) > 1)
				$valid_questions[] = $question;
		return $valid_questions;
	}

	public function edit_question($data): bool
	{
		$id = $data['id'];
		if (!$this->question_repo->exists($id))
		{
			log_error("Question with ID $id does not exist.");
			return false;
		}
		return $this->question_repo->update($id, $data);
	}

	public function delete_question(int $id): bool
	{
		if (!$this->question_repo->exists($id))
		{
			log_error("Question with ID $id does not exist.");
			return false;
		}
		return $this->question_repo->delete($id);
	}
}
