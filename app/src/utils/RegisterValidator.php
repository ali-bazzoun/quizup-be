<?php

require_once __DIR__ . '/logging.php';

class RegisterValidator
{
	public function isValid(string $email, string $password) : bool
	{
		$length = strlen($password);
		if ($length < 8 || $length > 64) {
			log_error("password length is not correct.");
            return false;
        }
		if (!preg_match('/^[\x20-\x7E]*$/', $password)) {
			log_error("password character's are not allowed.");
			return false;
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			log_error("email is not valid.");
			return false;
		}
		return true;
	}
}
