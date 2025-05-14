<?php

class RegisterExistedEmailException extends Exception
{
    public function __construct($message = "Email already exists.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
