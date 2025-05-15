<?php

class DuplicateQuizTitleException extends Exception
{
    public function __construct($message = "Title already exists.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
