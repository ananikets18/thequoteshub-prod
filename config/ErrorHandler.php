<?php
/**
 * Centralized Error and Exception Handler
 * 
 * Provides consistent error logging with correlation IDs and user-friendly error messages
 * Respects APP_DEBUG flag for detailed error information
 * 
 * @package QuotesHub
 * @version 1.0
 */

class ErrorHandler
{
    private static $logFile = null;
    private static $correlationId = null;

    /**
     * Initialize the error handler
     */
    public static function init()
    {
        // Set log file path
        self::$logFile = __DIR__ . '/../storage/logs/error.log';
        
        // Ensure logs directory exists
        $logsDir = dirname(self::$logFile);
        if (!file_exists($logsDir)) {
            mkdir($logsDir, 0755, true);
        }

        // Generate correlation ID for this request
        self::$correlationId = self::generateCorrelationId();

        // Set custom error and exception handlers
        set_error_handler([__CLASS__, 'handleError']);
        set_exception_handler([__CLASS__, 'handleException']);
        register_shutdown_function([__CLASS__, 'handleFatalError']);
    }

    /**
     * Generate a unique correlation ID for request tracking
     */
    private static function generateCorrelationId()
    {
        return sprintf(
            '%s-%s',
            date('YmdHis'),
            substr(bin2hex(random_bytes(8)), 0, 8)
        );
    }

    /**
     * Get the current correlation ID
     */
    public static function getCorrelationId()
    {
        return self::$correlationId;
    }

    /**
     * Handle PHP errors
     */
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        // Don't handle errors suppressed with @
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $errorType = self::getErrorType($errno);
        
        // Log the error
        self::logError([
            'type' => $errorType,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ]);

        // Display error if in debug mode
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<div style='padding:10px;background:#fee;border:1px solid #c33;margin:10px;'>";
            echo "<strong>{$errorType}:</strong> {$errstr}<br>";
            echo "<small>File: {$errfile} on line {$errline}</small><br>";
            echo "<small>Correlation ID: " . self::$correlationId . "</small>";
            echo "</div>";
        }

        // Don't execute PHP internal error handler
        return true;
    }

    /**
     * Handle uncaught exceptions
     */
    public static function handleException($exception)
    {
        // Log the exception
        self::logError([
            'type' => 'EXCEPTION',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'class' => get_class($exception)
        ]);

        // Clear any existing output
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Send appropriate response
        if (self::isAjaxRequest()) {
            self::sendJsonError($exception);
        } else {
            self::sendHtmlError($exception);
        }

        exit(1);
    }

    /**
     * Handle fatal errors
     */
    public static function handleFatalError()
    {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::logError([
                'type' => 'FATAL ERROR',
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line']
            ]);

            // Clear any existing output
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            if (self::isAjaxRequest()) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => 'A critical error occurred. Please try again later.',
                    'correlation_id' => self::$correlationId
                ]);
            } else {
                http_response_code(500);
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    echo "<h1>Fatal Error</h1>";
                    echo "<p><strong>Message:</strong> {$error['message']}</p>";
                    echo "<p><strong>File:</strong> {$error['file']}</p>";
                    echo "<p><strong>Line:</strong> {$error['line']}</p>";
                    echo "<p><strong>Correlation ID:</strong> " . self::$correlationId . "</p>";
                } else {
                    echo "<h1>500 - Internal Server Error</h1>";
                    echo "<p>An error occurred. Please contact support with reference ID: " . self::$correlationId . "</p>";
                }
            }
        }
    }

    /**
     * Log error to file
     */
    private static function logError($data)
    {
        $timestamp = date('Y-m-d H:i:s');
        $userId = $_SESSION['user_id'] ?? 'guest';
        $requestUri = $_SERVER['REQUEST_URI'] ?? 'N/A';
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'N/A';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? 'N/A';

        $logEntry = [
            'timestamp' => $timestamp,
            'correlation_id' => self::$correlationId,
            'type' => $data['type'],
            'message' => $data['message'],
            'file' => $data['file'],
            'line' => $data['line'],
            'user_id' => $userId,
            'request_method' => $requestMethod,
            'request_uri' => $requestUri,
            'remote_addr' => $remoteAddr,
            'user_agent' => $userAgent
        ];

        // Add class name for exceptions
        if (isset($data['class'])) {
            $logEntry['exception_class'] = $data['class'];
        }

        // Add stack trace in debug mode
        if (defined('APP_DEBUG') && APP_DEBUG && isset($data['trace'])) {
            $logEntry['trace'] = array_slice($data['trace'], 0, 5); // Limit to 5 frames
        }

        // Format log entry as JSON for easy parsing
        $logLine = json_encode($logEntry, JSON_UNESCAPED_SLASHES) . PHP_EOL;

        // Write to log file
        error_log($logLine, 3, self::$logFile);
    }

    /**
     * Check if request is AJAX
     */
    private static function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Send JSON error response
     */
    private static function sendJsonError($exception)
    {
        header('Content-Type: application/json');
        http_response_code(500);

        $response = [
            'success' => false,
            'error' => 'An error occurred. Please try again later.',
            'correlation_id' => self::$correlationId
        ];

        if (defined('APP_DEBUG') && APP_DEBUG) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => array_slice($exception->getTrace(), 0, 5)
            ];
        }

        echo json_encode($response);
    }

    /**
     * Send HTML error response
     */
    private static function sendHtmlError($exception)
    {
        http_response_code(500);

        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<!DOCTYPE html><html><head><title>Error</title>";
            echo "<style>body{font-family:Arial,sans-serif;padding:20px;background:#f5f5f5;}";
            echo ".error{background:#fff;border-left:4px solid #c33;padding:20px;margin:20px 0;box-shadow:0 2px 4px rgba(0,0,0,0.1);}";
            echo ".error h1{margin:0 0 10px;color:#c33;font-size:24px;}";
            echo ".error p{margin:5px 0;color:#333;}";
            echo ".error code{background:#f0f0f0;padding:2px 6px;border-radius:3px;font-family:monospace;}";
            echo ".trace{background:#f9f9f9;border:1px solid #ddd;padding:10px;margin:10px 0;overflow-x:auto;font-size:12px;}";
            echo "</style></head><body>";
            echo "<div class='error'>";
            echo "<h1>Uncaught Exception</h1>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<p><strong>Exception:</strong> <code>" . htmlspecialchars(get_class($exception)) . "</code></p>";
            echo "<p><strong>File:</strong> <code>" . htmlspecialchars($exception->getFile()) . "</code></p>";
            echo "<p><strong>Line:</strong> <code>" . $exception->getLine() . "</code></p>";
            echo "<p><strong>Correlation ID:</strong> <code>" . self::$correlationId . "</code></p>";
            echo "<div class='trace'><strong>Stack Trace:</strong><pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre></div>";
            echo "</div></body></html>";
        } else {
            echo "<!DOCTYPE html><html><head><title>Error</title>";
            echo "<style>body{font-family:Arial,sans-serif;text-align:center;padding:50px;background:#f5f5f5;}";
            echo ".error{background:#fff;display:inline-block;padding:40px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
            echo "h1{color:#c33;margin:0 0 20px;font-size:36px;}";
            echo "p{color:#666;margin:10px 0;}";
            echo ".ref{background:#f0f0f0;padding:10px;border-radius:4px;margin-top:20px;font-size:14px;color:#999;}";
            echo "</style></head><body>";
            echo "<div class='error'>";
            echo "<h1>500 - Internal Server Error</h1>";
            echo "<p>We apologize for the inconvenience. An error occurred while processing your request.</p>";
            echo "<p>Please try again later or contact support if the problem persists.</p>";
            echo "<div class='ref'>Reference ID: " . self::$correlationId . "</div>";
            echo "</div></body></html>";
        }
    }

    /**
     * Get human-readable error type
     */
    private static function getErrorType($errno)
    {
        $errors = [
            E_ERROR => 'ERROR',
            E_WARNING => 'WARNING',
            E_PARSE => 'PARSE ERROR',
            E_NOTICE => 'NOTICE',
            E_CORE_ERROR => 'CORE ERROR',
            E_CORE_WARNING => 'CORE WARNING',
            E_COMPILE_ERROR => 'COMPILE ERROR',
            E_COMPILE_WARNING => 'COMPILE WARNING',
            E_USER_ERROR => 'USER ERROR',
            E_USER_WARNING => 'USER WARNING',
            E_USER_NOTICE => 'USER NOTICE',
            E_STRICT => 'STRICT',
            E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
            E_DEPRECATED => 'DEPRECATED',
            E_USER_DEPRECATED => 'USER DEPRECATED'
        ];

        return $errors[$errno] ?? 'UNKNOWN ERROR';
    }

    /**
     * Manual error logging method for application use
     */
    public static function log($message, $context = [])
    {
        self::logError([
            'type' => 'APPLICATION LOG',
            'message' => $message,
            'file' => $context['file'] ?? 'N/A',
            'line' => $context['line'] ?? 0,
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ]);
    }
}
