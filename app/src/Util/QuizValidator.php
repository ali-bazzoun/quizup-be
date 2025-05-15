<?php

class QuizValidator
{
	private static function validate_string_format(string $str): bool
	{
		if ($str[0] === ' ' || substr($str, -1) === ' ')
			return false;
		return true;
	}

	private static function validate_quiz(array $data): array
	{
		$errors = [];
		if (empty($data['title']))
			$errors[] = 'Title cannot be empty';
		elseif (!QuizValidator::validate_string_format($data['title']))
			$errors[] = 'Title format is not valid';
		return $errors;
	}

	private static function validate_question(array $data): array
	{
		$errors = [];
		if (empty($data['text']))
			$errors[] =  'Question text cannot be empty';
		elseif (!QuizValidator::validate_string_format($data['text']))
			$errors[] = 'Question text format is not valid';
		return $errors;
	}

	private static function validate_option(array $data): array
	{
		$errors = [];
		if (empty($data['text']))
			$errors[] = 'Option text cannot be empty';
		elseif (!QuizValidator::validate_string_format($data['text']))
			$errors[] = 'Option text format is not valid';
		if (!array_key_exists('is_correct', $data))
			$errors[] = 'Option must have is_correct set';
		elseif (!is_bool($data['is_correct']))
			$errors[] = 'is_correct must be a boolean';
		return $errors;
	}

	private static function validate_quiz_update(array $data): array
	{
		$errors = [];
		if (isset($data['title']) && empty($data['title']))
			$errors[] = 'Title cannot be empty';
		return $errors;
	}

	private static function validate_question_update(array $data): array
	{
		$errors = [];
		if (isset($data['text']) && empty($data['text']))
			$errors[] = 'Question cannot be empty';
		elseif (!QuizValidator::validate_string_format($data['text']))
			$errors[] = 'Option text format is not valid';
		return $errors;
	}

	private static function validate_option_update(array $data): array
	{
		$errors = [];
		if (isset($data['text']) && empty($data['text']))
			$errors[] = 'Option text cannot be empty';
		if (array_key_exists('is_correct', $data) && !is_bool($data['is_correct']))
			$errors[] = 'Option must have boolean is_correct';
		return $errors;
	}

	public static function validate_create_data(array $data): array
	{
		$errors = [];
		if (empty($data))
			$errors[] = 'Request Body is empty';
		else
		{
			$errors = array_merge($errors, QuizValidator::validate_quiz($data));
			foreach ($data['questions'] ?? [] as $question)
			{
				$errors = array_merge($errors, QuizValidator::validate_question($question));
				foreach ($question['options'] ?? [] as $option)
					$errors = array_merge($errors, QuizValidator::validate_option($option));
			}
		}
		return $errors;
	}

	public static function validate_update_data(array $data): array
	{
		$errors = [];
		if (empty($data))
			$errors[] = 'Request Body is empty';
		else
		{
			$errors = array_merge($errors, QuizValidator::validate_quiz_update($data));
			foreach ($data['questions'] ?? [] as $question)
			{
				$errors = array_merge($errors, QuizValidator::validate_question_update($question));
				foreach ($question['options'] ?? [] as $option)
					$errors = array_merge($errors, QuizValidator::validate_option_update($option));
			}
		}
		return $errors;
	}
}
