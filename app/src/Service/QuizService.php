<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repository/QuizRepository.php';
require_once __DIR__ . '/../Repository/QuestionRepository.php';
require_once __DIR__ . '/../Repository/OptionRepository.php';
require_once __DIR__ . '/../Util/logging.php';

class QuizService
{
	private \PDO $db;
	private QuizRepository $quiz_repo;
	private QuestionRepository $question_repo;
	private OptionRepository $option_repo;

	public function __construct()
	{
        $this->db = Database::get_connection();
		$this->quiz_repo = new QuizRepository();
		$this->question_repo = new QuestionRepository();
		$this->option_repo = new OptionRepository();
	}

	public function create_quiz(array $data): bool
	{
		$this->db->beginTransaction();

		try
		{
			$quiz = $this->quiz_repo->create($data);
			$quiz_id = $quiz->id;

			foreach ($data['questions'] ?? [] as $q_data)
			{
				$q_data['quiz_id'] = $quiz_id;
				$question = $this->question_repo->create($q_data);
				$question_id = $question->id;

				foreach ($q_data['options'] ?? [] as $opt_data)
				{
					$opt_data['question_id'] = $question_id;
					$option = $this->option_repo->create($opt_data);
				}
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
		$all_quizzes = $this->quiz_repo->all_with_questions();
		$valid_quizzes = [];

		foreach ($all_quizzes as $quiz)
		{
			if (count($quiz->questions) < 3)
			{
				continue;
			}
			$valid = true;
			foreach ($quiz->questions as $question)
			{
				if (count($question->options) < 2)
				{
					$valid = false;
					break;
				}
				$has_correct = false;
				foreach ($question->options as $option)
				{
					if ($option->is_correct)
					{
						$has_correct = true;
						break;
					}
				}
				if (!$has_correct)
				{
					$valid = false;
					break;
				}
			}
			if ($valid)
			{
				$valid_quizzes[] = $quiz;
			}
		}
		return $valid_quizzes;
	}

	public function update_quiz(array $data): bool
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
