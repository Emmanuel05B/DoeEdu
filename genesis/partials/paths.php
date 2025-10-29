<?php
/*
 * DoE Genesis - Paths / Constants
 * Purpose: Centralize base paths and URLs for includes, uploads, images, and pages
 */

// Load .env variables (optional, fallback to defaults if not set)
$dotenv = parse_ini_file(__DIR__ . '/../../.env'); // adjust if your .env path changes

// ---------------------------
// Base paths
// ---------------------------
define('BASE_PATH', $dotenv['BASE_PATH'] ?? __DIR__ . '/..'); // points to genesis folder
define('BASE_URL', $dotenv['APP_URL'] ?? 'http://localhost/DoE_Genesis/DoeEdu/genesis'); // include genesis folder in URL

// ---------------------------
// Subfolder paths (filesystem)
// ---------------------------
define('ADMIN_PATH', BASE_PATH . '/admin/pages');
define('TUTOR_PATH', BASE_PATH . '/tutor/pages');
define('LEARNER_PATH', BASE_PATH . '/learner/pages');
define('COMMON_PATH', BASE_PATH . '/common/pages');

define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('ATTACHMENTS_PATH', UPLOADS_PATH . '/attachments');
define('IMAGES_PATH', UPLOADS_PATH . '/images');
define('RESOURCES_PATH', UPLOADS_PATH . '/resources');
define('PQ_MEMOS_PATH', UPLOADS_PATH . '/practicequestionsmemos');
define('PQ_IMAGES_PATH', UPLOADS_PATH . '/practice_question_images');
define('PROFILE_PICS_PATH', UPLOADS_PATH . '/ProfilePictures');
define('QUIZ_MEMOS_PATH', UPLOADS_PATH . '/quizmemos');
define('QUIZ_IMAGES_PATH', UPLOADS_PATH . '/quizimages');


// ---------------------------
// Subfolder URLs (for HTML links)
// ---------------------------
define('ADMIN_URL', BASE_URL . '/admin/pages');
define('TUTOR_URL', BASE_URL . '/tutor/pages');
define('LEARNER_URL', BASE_URL . '/learner/pages');
define('COMMON_URL', BASE_URL . '/common/pages');

define('UPLOADS_URL', BASE_URL . '/uploads');
define('ATTACHMENTS_URL', UPLOADS_URL . '/attachments');
define('IMAGES_URL', UPLOADS_URL . '/images');
define('RESOURCES_URL', UPLOADS_URL . '/resources');
define('PQ_MEMOS_URL', UPLOADS_URL . '/practicequestionsmemos');
define('PQ_IMAGES_URL', UPLOADS_URL . '/practice_question_images');
define('PROFILE_PICS_URL', UPLOADS_URL . '/ProfilePictures');
define('QUIZ_MEMOS_URL', UPLOADS_URL . '/quizmemos');
define('QUIZ_IMAGES_URL', UPLOADS_URL . '/quizimages');
