C:\xampp\htdocs\DoE_Genesis\DoeEdu
│
├─ genesis/
│   ├─ admin/
│   │   ├─ pages/                # All admin pages (e.g., adminindex.php)
│   │   └─ partials/             # Shared admin components (header, mainsidebar)
│   │
│   ├─ tutor/
│   │   ├─ pages/                # All tutor pages
│   │   └─ partials/             # Shared tutor components
│   │
│   ├─ learner/
│   │   ├─ pages/                # All learner pages
│   │   └─ partials/             # Shared learner components
│   │
│   ├─ common/
│   │   ├─ pages/                # Login, reset password, request invite, error.php, etc.
│   │   ├─ partials/             # Common page partials (if any)
│   │   └─ .htaccess             # Protect common pages if needed
│   │
│   ├─ logs/                      # Application logs
│   │
│   ├─ partials/                  # Global shared partials for all users
│   │   ├─ connect.php
│   │   └─ session_init.php
│   │   └─ paths.php
│   │
│   ├─ uploads/
│   │   ├─ attachments/           # Learner submission files
│   │   ├─ images/
│   │   ├─ resources/
│   │   ├─ practicequestionsmemos/
│   │   ├─ ProfilePictures/
│   │   └─ .htaccess              # Protect uploads
│
├─ vendor/                        # Composer dependencies
├─ .env                           # Environment variables (DB creds, email creds, base paths)
├─ .htaccess                      # Security rules (disable directory listing, protect .env)
├─ .git                           # Git repo








<?php
// --------------------------
// Core Bootstrap / Header
// --------------------------

// Error logging & config
require_once __DIR__ . '/../../common/config.php';  

// Path constants
include_once(__DIR__ . "/../../partials/paths.php");

// Start secure session
include_once(BASE_PATH . "/partials/session_init.php");

// Optional login check
$requiresLogin = true; // set to false for public pages
if ($requiresLogin && !isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

// Common HTML head
include_once(BASE_PATH . "/../partials/head.php");

// Database connection
include_once(BASE_PATH . "/partials/connect.php");
?>





<?php include_once(ADMIN_PATH . "/../partials/header.php"); ?>
<?php include_once(ADMIN_PATH . "/../partials/mainsidebar.php"); ?>



<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>