<?php
echo "<h1>Docker Test!</h1>";
echo "<p>PHP is working!</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Load WordPress
if (file_exists('/var/www/html/wp-load.php')) {
    require_once('/var/www/html/wp-load.php');
    echo "<p style='color:green'>WordPress loaded!</p>";
    echo "<p>Home: " . get_option('home') . "</p>";
    echo "<p>Site URL: " . get_option('siteurl') . "</p>";
    echo "<p><a href='" . admin_url() . "'>Go to Admin</a></p>";
} else {
    echo "<p style='color:red'>WordPress NOT loaded</p>";
}
?>

