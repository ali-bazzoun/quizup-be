<?php

function error_handler(string $level, string $message, string $file, string $line)
{
    $timestamp = date('Y-m-d H:i:s');
    $error =    "[$timestamp] level: $level"        .PHP_EOL;
    $error .=   "[$timestamp] message: $message"    .PHP_EOL;
    $error .=   "[$timestamp] file: $file"          .PHP_EOL;
    $error .=   "[$timestamp] line: $line"          .PHP_EOL;
    $error .=   "----------------------------------".PHP_EOL;
    error_log($error, 0);
    // error_log($error, 3, 'php://stderr');
}
