<?php

class IdNotExistException extends Exception
{
    public function __construct($message = "ID does not exist.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
