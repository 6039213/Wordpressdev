<?php
echo "<h1>WordPress Test</h1>";
echo "<p>Current URL: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Server Name: " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Check if wp-load.php exists
if (file_exists('wp-load.php')) {
    echo "<p style='color:green'>✅ wp-load.php found</p>";
    
    require_once('wp-load.php');
    
    echo "<h2>WordPress Info:</h2>";
    echo "<p>Home URL: " . get_option('home') . "</p>";
    echo "<p>Site URL: " . get_option('siteurl') . "</p>";
    echo "<p>Admin URL: " . admin_url() . "</p>";
    echo "<p>WP Admin: <a href='" . admin_url() . "'>" . admin_url() . "</a></p>";
    
    // Check if we can access admin
    echo "<h2>Quick Links:</h2>";
    echo "<ul>";
    echo "<li><a href='" . home_url() . "'>Homepage</a></li>";
    echo "<li><a href='" . admin_url() . "'>Admin Dashboard</a></li>";
    echo "<li><a href='" . wp_login_url() . "'>Login Page</a></li>";
    echo "</ul>";
    
} else {
    echo "<p style='color:red'>❌ wp-load.php NOT found</p>";
}
?>

