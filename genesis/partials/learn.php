How to use paths.php

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
1️⃣ First issue — Couldn’t read .env

You were using parse_ini_file() to read .env.

Problem: your .env had special characters like &, #, spaces, etc.

parse_ini_file() cannot handle these, so PHP threw syntax errors.

Solution:

Install and use phpdotenv (vlucas/phpdotenv)

It safely reads .env values with any characters, quotes, or spaces.

2️⃣ Second issue — Access denied

After .env was read correctly, your code tried to connect to MySQL with the credentials.

MySQL rejected emmanuel_doe because the user had USAGE privileges only, meaning no access to your database.

Solution:

Grant the user proper privileges on your database (SELECT, INSERT, UPDATE, DELETE).

Make sure .env password matches exactly what MySQL expects.