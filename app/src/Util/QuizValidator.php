<?php

class QuizValidator
{
	private function validate_quiz(array $data): array
	{
		$errors = [];
		if (empty($data['title']))
			$errors[] =  'Title cannot be empty';
		return $errors;
	}

	private function validate_question(array $data): array
	{
		$errors = [];
		if (empty($data['text']))
			$errors[] =  'Question cannot be empty';
		return $errors;
	}

	private function validate_option(array $data): array
	{
		$errors = [];
		if (empty($data['text']))
			$errors[] = 'Option text cannot be empty';
		if (!array_key_exists('is_correct', $data))
			$errors[] = 'Option must have is_correct set (true or false)';
		elseif (!is_bool($data['is_correct']))
			$errors[] = 'is_correct must be a boolean (true or false)';
		return $errors;
	}

	private function validate_quiz_update(array $data): array
	{
		$errors = [];
		if (isset($data['title']) && empty($data['title']))
			$errors[] = 'Title cannot be empty';
		return $errors;
	}

	private function validate_question_update(array $data): array
	{
		$errors = [];
		if (isset($data['text']) && empty($data['text']))
			$errors[] = 'Question cannot be empty';
		return $errors;
	}

	private function validate_option_update(array $data): array
	{
		$errors = [];
		if (isset($data['text']) && empty($data['text']))
			$errors[] = 'Option text cannot be empty';
		if (array_key_exists('is_correct', $data) && !is_bool($data['is_correct']))
			$errors[] = 'Option must have is_correct set (true or false)';
		return $errors;
	}

	public function validate_create_data(array $data): array
	{
		$errors = [];
		if (empty($data))
			$errors[] = 'Request Body is empty';
		else
		{
			$errors = array_merge($errors, $this->validate_quiz($option));
			foreach ($data['questions'] ?? [] as $question)
			{
				$errors = array_merge($errors, $this->validate_question($option));
				foreach ($question['options'] ?? [] as $option)
					$errors = array_merge($errors, $this->validate_option($option));
			}
		}
		return $errors;
	}

	public function validate_update_data(array $data): array
	{
		$errors = [];
		if (empty($data))
			$errors[] = 'Request Body is empty';
		else
		{
			$errors = array_merge($errors, $this->validate_quiz_update($option));
			foreach ($data['questions'] ?? [] as $question)
			{
				$errors = array_merge($errors, $this->validate_question_update($option));
				foreach ($question['options'] ?? [] as $option)
					$errors = array_merge($errors, $this->validate_option_update($option));
			}
		}
		return $errors;
	}
}
