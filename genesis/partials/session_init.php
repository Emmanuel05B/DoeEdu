<?php
// Load .env (for optional session secrets)
$dotenv = parse_ini_file(__DIR__ . '/../../.env');

// Set a custom session name
session_name('DoeGenesisSession');

// Use secure cookie settings
$secure = isset($_SERVER['HTTPS']); // true if HTTPS
$httponly = true;                    // prevent JS access to cookies
$samesite = 'Strict';                // prevent CSRF

// Override secure flag for localhost
if (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) {
    $secure = false;
}

// Controls how long the session cookie lives and which paths/domains can use it
session_set_cookie_params([
    'lifetime' => 0,               // until browser closes
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => $secure,
    'httponly' => $httponly,
    'samesite' => $samesite
]);

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Regenerate session ID on first init to prevent fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['email']) && !empty($_SESSION['email']);
}
?>
