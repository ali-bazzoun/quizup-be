<?php

function log_error(string $message, string $level = 'ERROR', ?Throwable $exception = null): void 
{
    $timestamp = date('Y-m-d H:i:s');
    $log_line = "[$timestamp] [$level] $message";

    if ($exception) {
        $log_line .= ' | ' . $exception->getMessage() .
                    ' in ' . $exception->getFile() .
                    ':' . $exception->getLine();
    }

    $log_line .= "\n";

    if (defined('STDERR'))
    {
        fwrite(STDERR, $log_line);
    }
    else
    {
        error_log($log_line);
    }
}
