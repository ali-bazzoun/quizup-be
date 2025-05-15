<?php

require_once __DIR__ . '/../Model/Quiz.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repository/QuizRepository.php';
require_once __DIR__ . '/QuestionService.php';
require_once __DIR__ . '/../Exception/DuplicateQuizTitleException.php';
require_once __DIR__ . '/../Exception/IdNotExistException.php';
require_once __DIR__ . '/../Util/Logging.php';

class QuizService
{
	private QuizRepository $quiz_repo;
	private QuestionService $question_service;

	public function __construct()
	{
		$this->question_service	= new QuestionService();
		$this->quiz_repo 		= new QuizRepository();
	}

	public function create_quiz(array $data): bool
	{
		if ($this->quiz_repo->exists_by_title($data['title']))
		{
			error_handler('Exception', "Quiz title '{$data['title']}' already exists.", __FILE__, __LINE__);
			throw new DuplicateQuizTitleException("Quiz title '{$data['title']}' already exists.");
		}
		$this->quiz_repo->startTransactionIfNotActive();
		try
		{
			$quiz = $this->quiz_repo->create($data);
			foreach ($data['questions'] ?? [] as $q_data)
			{
				$q_data['quiz_id'] = $quiz->id;
				$question = $this->question_service->create_question($q_data, false);
			}
			$this->quiz_repo->commitTransaction();
			return true;
		}
		catch (\Throwable $e)
		{
			$this->quiz_repo->rollbackTransaction();
			error_handler('Error', "Failed to create quiz (rolling back).", __FILE__, __LINE__);
			throw $e;
		}
	}

	public function get_valid_quiz_by_id(int $id): ?Quiz
	{
		if (!$this->quiz_repo->exists($id))
		{
			error_handler('Exception', "Quiz with ID $id does not exist.", __FILE__, __LINE__);
			throw new IdNotExistException("Quiz with ID $id does not exist.");
		}
		try
		{
			$quiz = $this->quiz_repo->find_by_id_with_questions_and_options($id);
			$valid_questions = [];
			foreach ($quiz->questions ?? [] as $question)
			{
				$count_correct = 0;
				foreach ($question->options ?? [] as $option)
					if ($option->is_correct == 1)
						$count_correct = $count_correct + 1;
				if ($count_correct == 1)
					$valid_questions[] = $question;
			}
			$quiz->questions = $valid_questions;
						// error_log(print_r($quiz, true), 0);
			if (count($quiz->questions) < 3)
				return null;
			return $quiz;
		}
		catch (\Exception $e)
		{
			error_handler('Error', "Get valid quiz failed (service).", __FILE__, __LINE__);
			throw $e;
		}
	}

	public function get_valid_quizzes(): array
	{
		try
		{
			$valid_quizzes = [];
			$all_quizzes = $this->quiz_repo->all();
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
			// return $all_quizzes;		
		}
		catch (\Throwable $e)
		{
			error_handler('Error', "Get valid quizzes failed (service).", __FILE__, __LINE__);
			throw $e;
		}
	}

	public function edit_quiz(int $id, array $data): bool
    {
		if (!$this->quiz_repo->exists($id))
		{
			error_handler('Exception', "Quiz with ID $id does not exist.", __FILE__, __LINE__);
			throw new IdNotExistException("Quiz with ID $id does not exist.");
		}
		try
		{
			return $this->quiz_repo->update($id, $data);
		}
		catch (\Throwable $e)
		{
			error_handler('Error', "Quiz update failed (service).", __FILE__, __LINE__);
			throw $e;
		}
    }

    public function delete_quiz(int $id): bool
    {
		if (!$this->quiz_repo->exists($id))
		{
			error_handler('Exception', "Quiz with ID $id does not exist.", __FILE__, __LINE__);
			throw new IdNotExistException("Quiz with ID $id does not exist.");
		}
		try
		{
        	return $this->quiz_repo->delete($id);
		}
		catch (\Throwable $e)
		{
			error_handler('Error', "Quiz delete failed (service).", __FILE__, __LINE__);
			throw $e;
		}
    }
}
