<?php
// Konfigurasi Dasar
define('BASE_URL', 'http://localhost/informatika');
define('SITE_NAME', 'Teknik Informatika');
define('ADMIN_EMAIL', 'admin@informatika.ac.id');

// Directory Paths
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);
define('UPLOAD_PATH', ROOT_PATH . 'upload_img' . DIRECTORY_SEPARATOR);
define('COMPONENTS_PATH', ROOT_PATH . 'components' . DIRECTORY_SEPARATOR);

// Database Configuration (bisa dipisah ke file tersendiri jika diperlukan)
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_informatika');
define('DB_USER', 'root');
define('DB_PASS', '');

// Session Configuration
define('SESSION_PREFIX', 'informatika_');
define('SESSION_LIFETIME', 7200); // 2 jam 

// Upload Configuration
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');

// Security Configuration
define('HASH_COST', 10); // untuk password hashing
define('TOKEN_TIMEOUT', 3600); // 1 jam untuk reset password token

// Pagination Configuration
define('ITEMS_PER_PAGE', 10);
define('MAX_PAGINATION_LINKS', 5);

// Cache Configuration
define('CACHE_ENABLE', true);
define('CACHE_DURATION', 3600); // 1 jam

// Inisialisasi session dengan pengaturan keamanan
function init_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
}

// Function untuk menghasilkan CSRF token
function generate_csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Function untuk memverifikasi CSRF token
function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Autoload classes (jika menggunakan OOP)
spl_autoload_register(function ($class_name) {
    $class_file = CONFIG_PATH . $class_name . '.php';
    if (file_exists($class_file)) {
        require_once $class_file;
    }
});

// Inisialisasi session
init_session();

// Load database connection
require_once CONFIG_PATH . 'database.php';
require_once CONFIG_PATH . 'functions.php';

// Set headers keamanan
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Content-Security-Policy: default-src \'self\'');
