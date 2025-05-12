<?php

require_once __DIR__ . '/../repositories/QuestionRepository.php';

class QuestionService
{
	private QuestionRepository $repo;

	public function __construct()
	{
		$this->repo = new QuestionRepository();
	}

	public get_valid_questions(int $quiz_id)
	{
		$questions =  $this->repo->find_by_quiz_id_with_options($quiz_id);
		$valid_questions = [];
		foreach($questions as $question)
		{
			if (count($question->options) > 1)
			{
				$valid_questions[] = $question;
			}
		}
		return $valid_questions;
	}

	public edit_question(int $question_id, $data)
	{
		$this->repo->update($question_id, $data);
	}

	public delete_question(int $question_id)
	{
		$this->repo->delete($question_id);
	}
}