<?php
function log_metric($metric) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $metric\n";
    file_put_contents('logs/metrics.log', $logMessage, FILE_APPEND);
}

function log_error($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] ERROR: $message\n";
    file_put_contents('logs/db_errors.log', $logMessage, FILE_APPEND);
}
?>