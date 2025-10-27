<?php
/**********************************************
 * DoE Genesis Bootstrap File
 * - Loads .env
 * - Initializes secure sessions
 * - Connects to DB
 * - Sets up custom error handling
 **********************************************/

// --- 1️⃣ Load .env ---
$dotenv = parse_ini_file(__DIR__ . '/../../.env'); // adjust path if needed

// --- 2️⃣ Secure session initialization ---
session_name('DoeGenesisSession');
$secure = isset($_SERVER['HTTPS']);
$httponly = true;
$samesite = 'Strict';

session_set_cookie_params([
    'lifetime' => 0,               // until browser closes
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => $secure,
    'httponly' => $httponly,
    'samesite' => $samesite
]);

session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// --- 3️⃣ Database connection ---
$Host = $dotenv['DB_HOST'] ?? 'localhost';
$User = $dotenv['DB_USER'] ?? 'root';
$Password = $dotenv['DB_PASS'] ?? '';
$dbname = $dotenv['DB_NAME'] ?? 'newgenesis';

$connect = mysqli_connect($Host, $User, $Password, $dbname);
if (!$connect) {
    error_log("DB Connection Failed: " . mysqli_connect_error());
    // optional: show generic message to user
    die("Oops! Something went wrong. Please try again later.");
}

// --- 4️⃣ Custom error handling (optional) ---
set_error_handler(function($errno, $errstr, $errfile, $errline){
    $logMessage = "[".date('Y-m-d H:i:s')."] Error: $errstr in $errfile on line $errline\n";
    file_put_contents(__DIR__ . '/../../logs/error.log', $logMessage, FILE_APPEND);
    // prevent display to users
    if (ini_get('display_errors')) {
        echo "Oops! Something went wrong. Our team has been notified.";
    }
    return true;
});

set_exception_handler(function($exception){
    $logMessage = "[".date('Y-m-d H:i:s')."] Uncaught Exception: ".$exception->getMessage()."\n";
    file_put_contents(__DIR__ . '/../../logs/error.log', $logMessage, FILE_APPEND);
    echo "Oops! Something went wrong. Our team has been notified.";
});
?>
