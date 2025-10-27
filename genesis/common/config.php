<?php
/*
Purpose:
- Disable default PHP error display
- Log errors to a file
- Redirect users to error page on any error
- Catch fatal errors, normal errors, and uncaught exceptions
*/

// Load path constants
include_once(__DIR__ . '/../partials/paths.php'); // adjust relative path if needed

// Turn off default error display
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs/php-error.log'); // centralized logs
error_reporting(E_ALL);

// Exception handler
set_exception_handler(function($e) {
    error_log("Uncaught Exception: " . $e->getMessage() . 
              " in " . $e->getFile() . 
              " on line " . $e->getLine());
    header("Location: " . COMMON_URL . "/error.php");
    exit();
});

// Error handler
set_error_handler(function($severity, $message, $file, $line) {
    error_log("Error [$severity]: $message in $file on line $line");
    header("Location: " . COMMON_URL . "/error.php");
    exit();
});

// Shutdown function for fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log("Fatal Error: " . print_r($error, true));
        header("Location: " . COMMON_URL . "/error.php");
        exit();
    }
});



















/*
Purpose:

Disable default PHP error display

Log errors to a file

Redirect users to /common/pages/error.php on any error

Catch fatal errors, normal errors, and uncaught exceptions

*/


// ==========================
// Global Configuration / Error Handling
// ==========================

// Turn off default error display
/*
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-error.log'); // logs folder outside user folders
error_reporting(E_ALL);

// --------------------------
// Exception handler
// --------------------------
set_exception_handler(function($e) {
    error_log("Uncaught Exception: " . $e->getMessage() . 
              " in " . $e->getFile() . 
              " on line " . $e->getLine());
    header("Location: /DoE_Genesis/DoeEdu/genesis/common/pages/error.php");
    exit();
});

// --------------------------
// Error handler
// --------------------------
set_error_handler(function($severity, $message, $file, $line) {
    error_log("Error [$severity]: $message in $file on line $line");
    header("Location: /DoE_Genesis/DoeEdu/genesis/common/pages/error.php");
    exit();
});

// --------------------------
// Shutdown function for fatal errors
// --------------------------
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log("Fatal Error: " . print_r($error, true));
        header("Location: /DoE_Genesis/DoeEdu/genesis/common/pages/error.php");
        exit();
    }
});

*/

