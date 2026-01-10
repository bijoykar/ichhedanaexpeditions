<?php
/**
 * Ichhedana Expeditions - Configuration File
 * 
 * @package IchhedanaExpeditions
 * @version 1.0
 */

// Start Session first - before any output
if (session_status() === PHP_SESSION_NONE) {
    define('SESSION_NAME', 'ichhedana_session');
    session_name(SESSION_NAME);
    session_start();
}

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Configuration
// Use '127.0.0.1' instead of 'localhost' if your host blocks localhost
// Or use the specific hostname provided by your hosting provider
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'ichhedana_expeditions');
define('DB_USER', 'root'); // Change to 'ichhedana_expeditions' after creating user
define('DB_PASS', ''); // Change to 'ichhedana_expeditions_pass' after creating user
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_URL', 'http://143.244.141.5/ichhedanaexpeditions');
define('SITE_NAME', 'Ichhedana Expeditions');
define('SITE_TAGLINE', 'Wildlife Photography Tours & Expeditions');
define('SITE_EMAIL', 'ichhedanaexpeditions@gmail.com');
define('SITE_PHONE', '9007820752');

// Directory Paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('ADMIN_PATH', ROOT_PATH . '/admin');

// URL Paths
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOAD_URL', SITE_URL . '/uploads');
define('ADMIN_URL', SITE_URL . '/admin');

// Upload Directories
define('TOUR_UPLOAD_DIR', UPLOAD_PATH . '/tours');
define('DESTINATION_UPLOAD_DIR', UPLOAD_PATH . '/destinations');
define('BLOG_UPLOAD_DIR', UPLOAD_PATH . '/blog');
define('GALLERY_UPLOAD_DIR', UPLOAD_PATH . '/gallery');
define('REVIEW_UPLOAD_DIR', UPLOAD_PATH . '/reviews');

// Session Configuration
define('SESSION_LIFETIME', 7200); // 2 hours

// Pagination
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Image Settings
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']);
define('THUMBNAIL_WIDTH', 400);
define('THUMBNAIL_HEIGHT', 300);
define('LARGE_IMAGE_WIDTH', 1200);
define('LARGE_IMAGE_HEIGHT', 900);

// Date/Time Settings
date_default_timezone_set('Asia/Kolkata');
define('DATE_FORMAT', 'd M, Y');
define('DATETIME_FORMAT', 'd M, Y h:i A');

// Security Settings
define('ADMIN_SESSION_KEY', 'ichhedana_admin_user');
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_COST', 10);

// Email Configuration (Configure for production)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'ichhedanaexpeditions@gmail.com');
define('SMTP_PASSWORD', ''); // Add SMTP password
define('SMTP_ENCRYPTION', 'tls');
define('EMAIL_FROM_NAME', 'Ichhedana Expeditions');
define('EMAIL_FROM_ADDRESS', 'ichhedanaexpeditions@gmail.com');

// Social Media URLs
define('FACEBOOK_URL', 'https://www.facebook.com/profile.php?id=100063782000455');
define('FACEBOOK_GROUP_URL', 'https://www.facebook.com/groups/2010223942443396/');
define('INSTAGRAM_URL', 'https://www.instagram.com/ichhedanaexpeditions/');

// SEO Settings
define('DEFAULT_META_DESCRIPTION', 'Ichhedana Expeditions offers professional wildlife photography tours to national parks across India and Bhutan. Led by experienced wildlife photographers.');
define('DEFAULT_META_KEYWORDS', 'wildlife photography, photography tours, wildlife expeditions, nature photography, India tours, Bhutan tours');

// Maintenance Mode
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'We are currently performing scheduled maintenance. Please check back soon.');

// Cache Settings
define('CACHE_ENABLED', false);
define('CACHE_LIFETIME', 3600); // 1 hour

// Autoload classes
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../includes/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
