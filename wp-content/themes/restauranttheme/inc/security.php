<?php
/**
 * Security Enhancements for Restaurant Pro Theme
 * 
 * @package RestaurantPro
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security Headers
 */
function restaurant_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }
}
add_action('send_headers', 'restaurant_security_headers');

/**
 * Remove WordPress version from head
 */
function restaurant_remove_version() {
    return '';
}
add_filter('the_generator', 'restaurant_remove_version');

/**
 * Remove version from scripts and styles
 */
function restaurant_remove_script_version($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'restaurant_remove_script_version', 15, 1);
add_filter('style_loader_src', 'restaurant_remove_script_version', 15, 1);

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove unnecessary WordPress features
 */
function restaurant_remove_unnecessary_features() {
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');
    
    // Remove Windows Live Writer manifest
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove Really Simple Discovery link
    remove_action('wp_head', 'rsd_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove adjacent posts links
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'restaurant_remove_unnecessary_features');

/**
 * Disable file editing in admin
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Limit login attempts
 */
function restaurant_limit_login_attempts() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $transient_name = 'login_attempts_' . md5($ip);
    $attempts = get_transient($transient_name);
    
    if ($attempts === false) {
        set_transient($transient_name, 1, 15 * MINUTE_IN_SECONDS);
    } elseif ($attempts < 5) {
        set_transient($transient_name, $attempts + 1, 15 * MINUTE_IN_SECONDS);
    } else {
        wp_die('Too many login attempts. Please try again in 15 minutes.');
    }
}
add_action('wp_login_failed', 'restaurant_limit_login_attempts');

/**
 * Hide login errors
 */
function restaurant_hide_login_errors() {
    return 'Invalid username or password.';
}
add_filter('login_errors', 'restaurant_hide_login_errors');

/**
 * Change login URL
 */
function restaurant_change_login_url() {
    if (PHP_SAPI === 'cli') {
        return;
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($request_uri, 'wp-login.php') === false) {
        return;
    }

    // Respect WordPress' own redirects (e.g., /wp-admin/ -> wp-login.php?redirect_to=...)
    if (!empty($_GET['redirect_to']) || !empty($_GET['reauth']) || !empty($_GET['action']) || !empty($_POST)) {
        return;
    }

    // Only redirect if a custom login page actually exists.
    $custom_login_slug = 'admin';
    if (function_exists('get_page_by_path') && get_page_by_path($custom_login_slug)) {
        wp_safe_redirect(home_url('/' . $custom_login_slug . '/'));
        exit();
    }
}
add_action('init', 'restaurant_change_login_url');

/**
 * Block bad user agents
 */
function restaurant_block_bad_user_agents() {
    $bad_user_agents = array(
        'bot',
        'crawler',
        'spider',
        'scraper',
        'curl',
        'wget',
        'python',
        'java',
        'perl',
        'ruby'
    );

    if (PHP_SAPI === 'cli' || empty($_SERVER['HTTP_USER_AGENT'])) {
        return;
    }

    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    
    foreach ($bad_user_agents as $bad_agent) {
        if (strpos($user_agent, $bad_agent) !== false) {
            status_header(403);
            exit('Access denied');
        }
    }
}
add_action('init', 'restaurant_block_bad_user_agents');

/**
 * Disable directory browsing
 */
function restaurant_disable_directory_browsing() {
    if (PHP_SAPI === 'cli') {
        return;
    }

    $request_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

    if ($request_path === false || $request_path === '' || $request_path === '/') {
        return;
    }

    $document_root = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    if (!$document_root) {
        return;
    }

    $target_path = realpath($document_root . DIRECTORY_SEPARATOR . ltrim(urldecode($request_path), '/\\'));

    if (!$target_path) {
        return;
    }

    $doc_normalized    = strtolower(str_replace('\\', '/', $document_root));
    $target_normalized = strtolower(str_replace('\\', '/', $target_path));

    $site_root_real = realpath(ABSPATH);
    if ($site_root_real) {
        $site_root = strtolower(str_replace('\\', '/', $site_root_real));
    } else {
        $site_root = '';
    }

    if ($site_root !== '' && $target_normalized === $site_root) {
        return;
    }

    if (file_exists($target_path . DIRECTORY_SEPARATOR . 'index.php') || file_exists($target_path . DIRECTORY_SEPARATOR . 'index.html')) {
        return;
    }

    if (strpos($target_normalized, $doc_normalized) === 0 && is_dir($target_path)) {
        status_header(403);
        exit('Directory browsing is not allowed');
    }
}
add_action('init', 'restaurant_disable_directory_browsing');

/**
 * Sanitize file uploads
 */
function restaurant_sanitize_file_uploads($file) {
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx');
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_types)) {
        $file['error'] = 'File type not allowed';
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'restaurant_sanitize_file_uploads');

/**
 * Add security headers to admin
 */
function restaurant_admin_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
}
add_action('admin_init', 'restaurant_admin_security_headers');

/**
 * Disable user enumeration
 */
function restaurant_disable_user_enumeration() {
    if (is_admin()) {
        return;
    }
    
    $query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    if ($query_string && preg_match('/author=([0-9]*)/i', $query_string)) {
        status_header(404);
        exit();
    }
}
add_action('init', 'restaurant_disable_user_enumeration');

/**
 * Remove admin bar for non-admins
 */
function restaurant_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'restaurant_remove_admin_bar');

/**
 * Disable theme and plugin editor
 * Note: DISALLOW_FILE_EDIT is already defined in wp-config.php
 */
// Removed duplicate define to prevent warning

/**
 * Add security nonce to forms (only when needed)
 */
function restaurant_add_security_nonce() {
    // Only add nonce to specific pages that need it
    if (is_page('reservations') || is_page('contact')) {
        wp_nonce_field('restaurant_security_nonce', 'restaurant_nonce_field');
    }
}
add_action('wp_head', 'restaurant_add_security_nonce');

/**
 * Verify security nonce (only for forms that have the nonce)
 */
function restaurant_verify_security_nonce() {
    // Only verify if we're processing a form with our nonce
    if (isset($_POST['restaurant_nonce_field']) && !wp_verify_nonce($_POST['restaurant_nonce_field'], 'restaurant_security_nonce')) {
        wp_die('Security check failed');
    }
}
add_action('init', 'restaurant_verify_security_nonce');

/**
 * Block suspicious requests (only for non-admin requests)
 */
function restaurant_block_suspicious_requests() {
    // Skip for admin and AJAX requests
    if (is_admin() || defined('DOING_AJAX') || defined('DOING_CRON')) {
        return;
    }
    
    $suspicious_patterns = array(
        '/\.\.\//',
        '/\.\.\\\/',
        '/\.\.%2f/',
        '/\.\.%5c/',
        '/\.\.%252f/',
        '/\.\.%255c/',
        '/eval\s*\(/',
        '/base64_decode/',
        '/gzinflate/',
        '/str_rot13/',
        '/system\s*\(/',
        '/exec\s*\(/',
        '/shell_exec/',
        '/passthru/'
    );
    
    $request_uri = $_SERVER['REQUEST_URI'];
    $query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $request_string = $request_uri . ' ' . $query_string;
    
    foreach ($suspicious_patterns as $pattern) {
        if (preg_match($pattern, $request_string)) {
            status_header(403);
            exit('Suspicious request blocked');
        }
    }
}
add_action('init', 'restaurant_block_suspicious_requests');

/**
 * Rate limiting for AJAX requests (disabled for development)
 */
function restaurant_rate_limit_ajax() {
    // Skip rate limiting in development
    if (WP_DEBUG) {
        return;
    }
    
    if (defined('DOING_AJAX') && DOING_AJAX) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $transient_name = 'ajax_requests_' . md5($ip);
        $requests = get_transient($transient_name);
        
        if ($requests === false) {
            set_transient($transient_name, 1, 60);
        } elseif ($requests < 100) {
            set_transient($transient_name, $requests + 1, 60);
        } else {
            wp_die('Rate limit exceeded');
        }
    }
}
add_action('init', 'restaurant_rate_limit_ajax');
