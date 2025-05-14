<?php

class DatabaseQueryException extends Exception
{
	public function __construct($message = "A database query error occurred.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
