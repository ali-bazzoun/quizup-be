<?php

function error_handler(string $level, string $message, string $file, string $line)
{
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] level: $level", 0);
    error_log("[$timestamp] message: $message", 0);
    error_log("[$timestamp] file: $file", 0);
    error_log("[$timestamp] line: $line", 0);
    error_log("----------------------------------");
    // error_log($error, 0);
    // error_log($error, 3, 'php://stderr');
}
