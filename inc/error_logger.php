<?php
declare(strict_types=1);
/**
 * Centralized Error Logger
 * 
 * Provides a unified interface for logging errors and issues throughout the application.
 */

if (!defined('GRPG_INC')) {
    exit;
}

/**
 * Log an error message to the error log file.
 *
 * @param string $message The error message to log
 * @param string $level The severity level (ERROR, WARNING, NOTICE, INFO)
 * @param array $context Additional context information (optional)
 * @return bool Returns true if the message was logged successfully
 */
function log_error(string $message, string $level = 'ERROR', array $context = []): bool
{
    // Get log file path from configuration or use default
    $logFile = defined('ERROR_LOG_PATH') ? ERROR_LOG_PATH : dirname(__DIR__) . '/logs/error.log';
    
    // Ensure logs directory exists
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        if (!@mkdir($logDir, 0755, true)) {
            // Fallback to PHP's error_log if we can't create the directory
            error_log("Failed to create log directory: $logDir");
            error_log("[$level] $message");
            return false;
        }
    }
    
    // Prepare the log entry
    $timestamp = date('Y-m-d H:i:s');
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $userId = $_SESSION['id'] ?? 0;
    $requestUri = $_SERVER['REQUEST_URI'] ?? 'CLI';
    
    // Build the log message
    $logMessage = sprintf(
        "[%s] [%s] [IP: %s] [User: %s] [URI: %s] %s",
        $timestamp,
        $level,
        $ipAddress,
        $userId,
        $requestUri,
        $message
    );
    
    // Add context if provided
    if (!empty($context)) {
        $logMessage .= ' | Context: ' . json_encode($context);
    }
    
    $logMessage .= ' | User-Agent: ' . $userAgent . PHP_EOL;
    
    // Write to log file with file locking
    $result = @file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    // If writing fails, fallback to PHP's error_log
    if ($result === false) {
        error_log($logMessage);
        return false;
    }
    
    // Check if log rotation is needed
    if (defined('ERROR_LOG_MAX_SIZE') && file_exists($logFile)) {
        $maxSize = ERROR_LOG_MAX_SIZE;
        if (filesize($logFile) > $maxSize) {
            rotate_log($logFile);
        }
    }
    
    return true;
}

/**
 * Rotate the log file when it exceeds maximum size.
 *
 * @param string $logFile Path to the log file
 * @return bool Returns true if rotation was successful
 */
function rotate_log(string $logFile): bool
{
    $maxRotations = defined('ERROR_LOG_MAX_ROTATIONS') ? ERROR_LOG_MAX_ROTATIONS : 5;
    
    // Rotate existing backup files
    for ($i = $maxRotations - 1; $i > 0; $i--) {
        $oldFile = $logFile . '.' . $i;
        $newFile = $logFile . '.' . ($i + 1);
        
        if (file_exists($oldFile)) {
            if (file_exists($newFile)) {
                @unlink($newFile);
            }
            @rename($oldFile, $newFile);
        }
    }
    
    // Rotate current log file
    $backupFile = $logFile . '.1';
    if (@rename($logFile, $backupFile)) {
        // Create new empty log file
        @touch($logFile);
        @chmod($logFile, 0644);
        return true;
    }
    
    return false;
}

/**
 * Log a database error.
 *
 * @param string $message The error message
 * @param array $context Additional context (query, parameters, etc.)
 * @return bool
 */
function log_database_error(string $message, array $context = []): bool
{
    return log_error($message, 'DATABASE_ERROR', $context);
}

/**
 * Log a security issue.
 *
 * @param string $message The error message
 * @param array $context Additional context
 * @return bool
 */
function log_security_issue(string $message, array $context = []): bool
{
    return log_error($message, 'SECURITY', $context);
}

/**
 * Log an application warning.
 *
 * @param string $message The warning message
 * @param array $context Additional context
 * @return bool
 */
function log_warning(string $message, array $context = []): bool
{
    return log_error($message, 'WARNING', $context);
}

/**
 * Log an informational message.
 *
 * @param string $message The info message
 * @param array $context Additional context
 * @return bool
 */
function log_info(string $message, array $context = []): bool
{
    return log_error($message, 'INFO', $context);
}

/**
 * Get the last N lines from the error log.
 *
 * @param int $lines Number of lines to retrieve (default: 100)
 * @return array Array of log lines
 */
function get_error_log(int $lines = 100): array
{
    $logFile = defined('ERROR_LOG_PATH') ? ERROR_LOG_PATH : dirname(__DIR__) . '/logs/error.log';
    
    if (!file_exists($logFile)) {
        return [];
    }
    
    $file = new SplFileObject($logFile);
    $file->seek(PHP_INT_MAX);
    $totalLines = $file->key();
    
    if ($totalLines < $lines) {
        $lines = $totalLines;
    }
    
    $startLine = $totalLines - $lines;
    $result = [];
    
    $file->seek($startLine);
    while (!$file->eof()) {
        $line = $file->current();
        if (trim($line) !== '') {
            $result[] = $line;
        }
        $file->next();
    }
    
    return $result;
}
