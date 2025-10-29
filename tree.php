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





.


<?php







require_once __DIR__ . '/../../common/config.php';  
include_once(__DIR__ . "/../../partials/paths.php");
include_once(BASE_PATH . "/partials/session_init.php");

if (!isLoggedIn()) {
    header("Location: " . COMMON_URL . "/login.php");
    exit();
}

include_once(BASE_PATH . "/partials/connect.php");
include_once(COMMON_PATH . "/../partials/head.php");  


<?php include_once(TUTOR_PATH . "/../partials/header.php"); ?> 
<?php include_once(TUTOR_PATH . "/../partials/mainsidebar.php"); ?>

<?php include_once(COMMON_PATH . "/../partials/queries.php"); ?>


require_once BASE_PATH . '/../vendor/autoload.php';

?>



















//learning. How to use paths.php

1️⃣ Include it at the top of any page:

require_once('../../partials/paths.php');  // adjust path depending on the page location


2️⃣ Example usages

Includes:

include(COMMON_PATH . '/connect.php');  // instead of ../../partials/connect.php


Redirects:

header('Location: ' . ADMIN_URL . '/adminindex.php');


HTML images:

<img src="<?php echo IMAGES_URL; ?>/westtt.png" alt="Example Image">


Uploads (filesystem):

$filePath = ATTACHMENTS_PATH . '/submission.pdf';


Uploads (URL):

$fileURL = ATTACHMENTS_URL . '/submission.pdf';

/////////////////////////
//  First issue — Couldn’t read .env

You were using parse_ini_file() to read .env.

Problem: your .env had special characters like &, #, spaces, etc.

parse_ini_file() cannot handle these, so PHP threw syntax errors.

Solution:

Install and use phpdotenv (vlucas/phpdotenv)

It safely reads .env values with any characters, quotes, or spaces.

//Second issue — Access denied

After .env was read correctly, your code tried to connect to MySQL with the credentials.

MySQL rejected emmanuel_doe because the user had USAGE privileges only, meaning no access to your database.

Solution:

Grant the user proper privileges on your database (SELECT, INSERT, UPDATE, DELETE).

Make sure .env password matches exactly what MySQL expects.

