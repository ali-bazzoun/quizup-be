<?php

class RegisterValidator
{
	public function isValid(string $email, string $password) : bool
	{
		$length = strlen($password);
		if ($length < 8 || $length > 64) {
            return false;
        }
		if (!preg_match('/^[\x20-\x7E]*$/', $password)) {
			return false;
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
	}
}
