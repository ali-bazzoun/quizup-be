<?php

function get_logs_dir() {
    $logDir = __DIR__ . '/../logs';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    return $logDir;
}

function log_metric($metric) {
    $logDir = get_logs_dir();
    $logFile = $logDir . '/metrics.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $metric\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

function log_error($message) {
    if (defined('STDERR')) {
        fwrite(STDERR, "[ERROR] $message\n");
    } else {
        error_log("[ERROR] $message");
    }
}
