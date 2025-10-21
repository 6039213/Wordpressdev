<?php
/**
 * Complete WordPress Setup Script
 * Fixes EVERYTHING for Schoolbord Restaurant
 */

// Load WordPress
require_once('wp-load.php');

echo "<h1>🚀 Complete Schoolbord Setup</h1>";

// 1. CREATE PRIMARY MENU
echo "<h2>1. Creating Primary Menu...</h2>";

// Delete existing menu if it exists
$existing_menu = wp_get_nav_menu_object('Primary Menu');
if ($existing_menu) {
    wp_delete_nav_menu($existing_menu->term_id);
}

// Create new menu
$menu_id = wp_create_nav_menu('Primary Menu');
echo "✅ Menu created with ID: $menu_id<br>";

// Add menu items
$menu_items = [
    ['title' => 'Home', 'url' => home_url('/'), 'order' => 1],
    ['title' => 'Menu', 'url' => home_url('/menu'), 'order' => 2],
    ['title' => 'About', 'url' => home_url('/about'), 'order' => 3],
    ['title' => 'Contact', 'url' => home_url('/contact'), 'order' => 4],
    ['title' => 'Reservations', 'url' => home_url('/reservations'), 'order' => 5],
];

foreach ($menu_items as $item) {
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title' => $item['title'],
        'menu-item-url' => $item['url'],
        'menu-item-status' => 'publish',
        'menu-item-type' => 'custom',
        'menu-item-position' => $item['order']
    ]);
    echo "✅ Added: {$item['title']}<br>";
}

// 2. SET MENU LOCATION
echo "<h2>2. Setting Menu Location...</h2>";
$locations = get_theme_mod('nav_menu_locations');
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
echo "✅ Menu assigned to 'primary' location<br>";

// 3. FIX PERMALINKS
echo "<h2>3. Fixing Permalinks...</h2>";
global $wp_rewrite;
$wp_rewrite->set_permalink_structure('/%postname%/');
$wp_rewrite->flush_rules();
echo "✅ Permalinks updated to /%postname%/<br>";

// 4. CREATE REQUIRED PAGES
echo "<h2>4. Creating Required Pages...</h2>";

$pages = [
    'Menu' => 'Our delicious menu',
    'About' => 'About Restaurant Schoolbord',
    'Contact' => 'Get in touch with us',
    'Reservations' => '[schoolbord_reservation_form]'
];

foreach ($pages as $title => $content) {
    // Check if page exists
    $page = get_page_by_title($title);
    
    if (!$page) {
        $page_id = wp_insert_post([
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1
        ]);
        echo "✅ Created page: $title (ID: $page_id)<br>";
    } else {
        echo "✓ Page already exists: $title<br>";
    }
}

// 5. ACTIVATE RESTAURANT THEME
echo "<h2>5. Checking Theme...</h2>";
$current_theme = wp_get_theme();
if ($current_theme->get_stylesheet() !== 'restauranttheme') {
    switch_theme('restauranttheme');
    echo "✅ Activated Restaurant Theme<br>";
} else {
    echo "✓ Restaurant Theme already active<br>";
}

// 6. ACTIVATE PLUGINS
echo "<h2>6. Checking Plugins...</h2>";
$required_plugins = ['schoolbord-reservations/schoolbord-reservations.php'];

foreach ($required_plugins as $plugin) {
    if (!is_plugin_active($plugin)) {
        activate_plugin($plugin);
        echo "✅ Activated: $plugin<br>";
    } else {
        echo "✓ Already active: $plugin<br>";
    }
}

// 7. UPDATE RESTAURANT INFO
echo "<h2>7. Setting Restaurant Info...</h2>";

update_option('restaurant_name', 'Restaurant Schoolbord');
update_option('restaurant_phone', '+31 24 648 794');
update_option('restaurant_email', 'info@schoolbord.nl');
update_option('restaurant_address', '123 Restaurant Street, Amsterdam');

echo "✅ Restaurant information updated<br>";

// 8. CHECK URLs
echo "<h2>8. Verifying URLs...</h2>";
echo "Home URL: " . get_option('home') . "<br>";
echo "Site URL: " . get_option('siteurl') . "<br>";
echo "Current URL: " . home_url() . "<br>";

// 9. CLEAR ALL CACHES
echo "<h2>9. Clearing Caches...</h2>";
wp_cache_flush();
echo "✅ Cache cleared<br>";

echo "<h1>✅ SETUP COMPLETE!</h1>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Go to <a href='" . admin_url() . "'>Dashboard</a></li>";
echo "<li>Go to <a href='" . home_url() . "'>Homepage</a></li>";
echo "<li>Check <a href='" . admin_url('nav-menus.php') . "'>Menus</a></li>";
echo "</ol>";
?>

