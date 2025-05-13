<?php

class LoginValidator
{
	public static function validate(array $data): array
	{
		$errors = [];

		if (empty($data['email']))
		{
			$errors['email'] = 'Email is required.';
		}

		if (empty($data['password']))
		{
			$errors['password'] = 'Password is required.';
		}

		return $errors;
	}
}
