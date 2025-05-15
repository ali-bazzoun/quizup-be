<?php

class QuestionValidator
{
	private static function validate_question(array $data): array
	{
		$errors = [];
		if (empty($data['text']))
			$errors[] =  'Question cannot be empty';
		return $errors;
	}

	private static function validate_option(array $data): array
	{
		$errors = [];
		if (empty($data['text']))
			$errors[] = 'Option text cannot be empty';
		if (!array_key_exists('is_correct', $data))
			$errors[] = 'Option must have is_correct';
		elseif (!is_bool($data['is_correct']))
			$errors[] = 'is_correct must be a boolean';
		return $errors;
	}

	private static function validate_question_update(array $data): array
	{
		$errors = [];
		if (isset($data['text']) && empty($data['text']))
			$errors[] = 'Question cannot be empty';
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
			$errors = array_merge($errors, QuestionValidator::validate_question($data));
			foreach ($data['options'] ?? [] as $option)
				$errors = array_merge($errors, QuestionValidator::validate_option($option));
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
			$errors = array_merge($errors, QuestionValidator::validate_question_update($data));
			foreach ($data['options'] ?? [] as $option)
				$errors = array_merge($errors, QuestionValidator::validate_option_update($option));
		}
		return $errors;
	}
}
