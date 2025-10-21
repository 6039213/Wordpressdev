<?php
/**
 * Performance Optimizations for Restaurant Pro Theme
 * 
 * @package RestaurantPro
 * @version 2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enable GZIP compression
 */
function restaurant_enable_gzip() {
    if (!ob_get_level()) {
        ob_start('ob_gzhandler');
    }
}
add_action('init', 'restaurant_enable_gzip');

/**
 * Optimize database queries
 */
function restaurant_optimize_queries() {
    // Remove unnecessary queries
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // Optimize post queries
    add_action('pre_get_posts', 'restaurant_optimize_post_queries');
}
add_action('init', 'restaurant_optimize_queries');

function restaurant_optimize_post_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Limit posts per page
        if (is_home() || is_archive()) {
            $query->set('posts_per_page', 10);
        }
        
        // Optimize meta queries
        if (is_post_type_archive('dish')) {
            $query->set('meta_key', 'dish_price');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'ASC');
        }
    }
}

/**
 * Implement caching
 */
function restaurant_implement_caching() {
    // Browser caching
    if (!is_admin()) {
        header('Cache-Control: public, max-age=31536000');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
    }
}
add_action('init', 'restaurant_implement_caching');

/**
 * Optimize images
 */
function restaurant_optimize_images() {
    // Add WebP support
    add_filter('wp_generate_attachment_metadata', 'restaurant_generate_webp_images', 10, 2);
    
    // Lazy loading
    add_filter('wp_get_attachment_image_attributes', 'restaurant_add_lazy_loading', 10, 3);
}
add_action('init', 'restaurant_optimize_images');

function restaurant_generate_webp_images($metadata, $attachment_id) {
    if (get_theme_mod('restaurant_image_optimization', true)) {
        $upload_dir = wp_upload_dir();
        $file_path = get_attached_file($attachment_id);
        $file_info = pathinfo($file_path);
        
        if (in_array(strtolower($file_info['extension']), array('jpg', 'jpeg', 'png'))) {
            $webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
            
            // Convert to WebP (requires ImageMagick or GD)
            if (function_exists('imagewebp')) {
                $image = null;
                switch (strtolower($file_info['extension'])) {
                    case 'jpg':
                    case 'jpeg':
                        $image = imagecreatefromjpeg($file_path);
                        break;
                    case 'png':
                        $image = imagecreatefrompng($file_path);
                        break;
                }
                
                if ($image) {
                    imagewebp($image, $webp_path, 80);
                    imagedestroy($image);
                }
            }
        }
    }
    
    return $metadata;
}

function restaurant_add_lazy_loading($attributes, $attachment, $size) {
    if (get_theme_mod('restaurant_lazy_loading', true)) {
        $attributes['loading'] = 'lazy';
        $attributes['decoding'] = 'async';
    }
    
    return $attributes;
}

/**
 * Minify CSS and JS
 */
function restaurant_minify_assets() {
    if (!is_admin()) {
        // Minify inline CSS
        add_action('wp_head', 'restaurant_minify_inline_css', 999);
        
        // Minify inline JS
        add_action('wp_footer', 'restaurant_minify_inline_js', 999);
    }
}
add_action('init', 'restaurant_minify_assets');

function restaurant_minify_inline_css() {
    ob_start('restaurant_minify_css_output');
}

function restaurant_minify_css_output($buffer) {
    // Remove comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    
    // Remove unnecessary whitespace
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
    $buffer = preg_replace('/\s+/', ' ', $buffer);
    $buffer = str_replace(array(' {', '{ ', ' }', '} ', '; '), array('{', '{', '}', '}', ';'), $buffer);
    
    return $buffer;
}

function restaurant_minify_inline_js() {
    ob_start('restaurant_minify_js_output');
}

function restaurant_minify_js_output($buffer) {
    // Remove comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = preg_replace('!//.*$!m', '', $buffer);
    
    // Remove unnecessary whitespace
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
    $buffer = preg_replace('/\s+/', ' ', $buffer);
    
    return $buffer;
}

/**
 * Optimize database
 */
function restaurant_optimize_database() {
    global $wpdb;
    
    // Clean up post revisions
    $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'");
    
    // Clean up spam comments
    $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
    
    // Clean up trashed posts
    $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'trash'");
    
    // Optimize tables
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    foreach ($tables as $table) {
        $wpdb->query("OPTIMIZE TABLE {$table[0]}");
    }
}
add_action('wp_scheduled_delete', 'restaurant_optimize_database');

/**
 * Preload critical resources
 */
function restaurant_preload_resources() {
    if (is_front_page()) {
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/css/custom.css" as="style">' . "\n";
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/js/custom.js" as="script">' . "\n";
        echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" as="style">' . "\n";
    }
}
add_action('wp_head', 'restaurant_preload_resources', 1);

/**
 * Defer non-critical JavaScript
 */
function restaurant_defer_js($tag, $handle, $src) {
    $defer_scripts = array(
        'restaurant-main-script',
        'jquery-migrate',
        'wp-embed'
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace('<script ', '<script defer ', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'restaurant_defer_js', 10, 3);

/**
 * Remove unused CSS
 */
function restaurant_remove_unused_css() {
    // Remove block library CSS if not using blocks
    if (!has_blocks()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
    }
    
    // Remove WooCommerce CSS if not using WooCommerce
    if (!class_exists('WooCommerce')) {
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');
    }
}
add_action('wp_enqueue_scripts', 'restaurant_remove_unused_css', 100);

/**
 * Optimize WordPress queries
 */
function restaurant_optimize_wp_queries() {
    // Limit post revisions
    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', 3);
    }
    
    // Increase memory limit
    if (!defined('WP_MEMORY_LIMIT')) {
        define('WP_MEMORY_LIMIT', '512M');
    }
    
    // Optimize autosave interval
    if (!defined('AUTOSAVE_INTERVAL')) {
        define('AUTOSAVE_INTERVAL', 300);
    }
}
add_action('init', 'restaurant_optimize_wp_queries');

/**
 * Implement object caching
 */
function restaurant_implement_object_caching() {
    if (get_theme_mod('restaurant_enable_caching', true)) {
        // Cache expensive queries
        add_action('wp_ajax_nopriv_get_dishes', 'restaurant_cache_dishes_query');
        add_action('wp_ajax_get_dishes', 'restaurant_cache_dishes_query');
    }
}
add_action('init', 'restaurant_implement_object_caching');

function restaurant_cache_dishes_query() {
    $cache_key = 'restaurant_dishes_' . md5(serialize($_GET));
    $dishes = get_transient($cache_key);
    
    if (false === $dishes) {
        $dishes = new WP_Query(array(
            'post_type' => 'dish',
            'posts_per_page' => -1,
            'meta_key' => 'dish_price',
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
        ));
        
        set_transient($cache_key, $dishes, HOUR_IN_SECONDS);
    }
    
    wp_send_json_success($dishes);
}

/**
 * Optimize database queries with indexes
 */
function restaurant_add_database_indexes() {
    global $wpdb;
    
    $tables = array(
        $wpdb->postmeta  => array(
            'idx_meta_key_value' => "ALTER TABLE {$wpdb->postmeta} ADD INDEX idx_meta_key_value (meta_key(191), meta_value(191))",
        ),
        $wpdb->posts     => array(
            'idx_post_type_status' => "ALTER TABLE {$wpdb->posts} ADD INDEX idx_post_type_status (post_type, post_status)",
        ),
        $wpdb->comments  => array(
            'idx_comment_approved' => "ALTER TABLE {$wpdb->comments} ADD INDEX idx_comment_approved (comment_approved)",
        ),
    );
    
    foreach ($tables as $table => $indexes) {
        $existing = $wpdb->get_col("SHOW INDEX FROM {$table}", 2);
        foreach ($indexes as $key => $sql) {
            if (!in_array($key, $existing, true)) {
                $wpdb->query($sql);
            }
        }
    }
}
add_action('after_switch_theme', 'restaurant_add_database_indexes');

/**
 * Clean up expired transients
 */
function restaurant_cleanup_transients() {
    global $wpdb;
    
    $expired = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s AND option_value < %d",
            $wpdb->esc_like('_transient_timeout_') . '%',
            current_time('timestamp')
        )
    );
    
    if ($expired) {
        foreach ($expired as $option_name) {
            $transient_key = str_replace('_transient_timeout_', '', $option_name);
            delete_transient($transient_key);
        }
    }
}
add_action('wp_scheduled_delete', 'restaurant_cleanup_transients');

/**
 * Optimize REST API
 */
function restaurant_optimize_rest_api() {
    // Remove unnecessary REST API endpoints
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_action('rest_api_init', 'create_initial_rest_routes', 99);
    
    // Add caching to REST API
    add_filter('rest_pre_serve_request', 'restaurant_cache_rest_response', 10, 4);
}
add_action('init', 'restaurant_optimize_rest_api');

function restaurant_cache_rest_response($served, $result, $request, $server) {
    if (is_wp_error($result)) {
        return $served;
    }
    
    $cache_key = 'rest_api_' . md5($request->get_route() . serialize($request->get_params()));
    $cached_response = get_transient($cache_key);
    
    if (false !== $cached_response) {
        header('Content-Type: application/json');
        echo $cached_response;
        return true;
    }
    
    set_transient($cache_key, wp_json_encode($result), 5 * MINUTE_IN_SECONDS);
    
    return $served;
}
