<?php

function get_logs_dir() {
    $logDir = __DIR__ . '/../logs';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    return $logDir;
}

function log_metric($metric) 
{
    $logDir = get_logs_dir();
    $logFile = $logDir . '/metrics.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $metric\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

function log_error(string $message, string $level = 'ERROR', ?Throwable $exception = null): void 
{
    $timestamp = date('Y-m-d H:i:s');
    $logLine = "[$timestamp] [$level] $message";

    if ($exception) {
        $logLine .= ' | ' . $exception->getMessage() .
                    ' in ' . $exception->getFile() .
                    ':' . $exception->getLine();
    }

    $logLine .= "\n";

    if (defined('STDERR')) {
        fwrite(STDERR, $logLine);
    } else {
        error_log($logLine);
    }
}
