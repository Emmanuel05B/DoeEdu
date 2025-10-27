
<?php
/*
$dotenv = parse_ini_file(__DIR__ . '/../../.env'); 

$Host = $dotenv['DB_HOST'];
$User = $dotenv['DB_USER'];
$Password = $dotenv['DB_PASS'];
$dbname = $dotenv['DB_NAME'];

$connect = mysqli_connect($Host, $User, $Password, $dbname);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
    */
?>

<?php
// Load Composer autoload
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // genesis parent folder
$dotenv->load();

// Read DB credentials from .env
$Host     = $_ENV['DB_HOST'] ?? 'localhost';
$User     = $_ENV['DB_USER'] ?? '';
$Password = $_ENV['DB_PASS'] ?? '';
$dbname   = $_ENV['DB_NAME'] ?? '';

// Connect to MySQL
$connect = mysqli_connect($Host, $User, $Password, $dbname);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: set charset
mysqli_set_charset($connect, "utf8mb4");

