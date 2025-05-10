<?php

function get_logs_dir() {
    // Still useful for metric logs, or you can remove this if all logs should go to stderr
    $logDir = __DIR__ . '/../logs';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    return $logDir;
}

function log_metric($metric) {
    // If you want metrics to still be saved to file, leave this as-is
    $logDir = get_logs_dir();
    $logFile = $logDir . '/metrics.log';

    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $metric\n";

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

function log_error($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] ERROR: $message\n";

    fwrite(STDERR, $logMessage);
}

?>