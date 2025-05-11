<?php

class QuizService
{
	private QuizRepository $repo;

	public function __construct()
	{
		$this->repo = new QuizRepository();
	}

	public function get_valid_quizzes(): array
	{
		$all_quizzes = $this->repo->get_all_with_questions();
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

	public function update_quiz(int $id, array $data): bool
    {
        return $this->repo->update($id, $data);
    }

    public function delete_quiz(int $id): bool
    {
        return $this->repo->delete($id);
    }
}