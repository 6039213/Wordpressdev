<?php
echo "<h1>PHP WORKS!</h1>";
echo "<p>Server: " . $_SERVER["SERVER_NAME"] . "</p>";
echo "<p>Docker: " . (file_exists("/.dockerenv") ? "YES" : "NO") . "</p>";
echo "<p>Database Host from wp-config: ";

// Load wp-config to check DB settings
require_once('wp-config.php');
echo DB_HOST . "</p>";
echo "<p>Database Name: " . DB_NAME . "</p>";
echo "<p>Database User: " . DB_USER . "</p>";

// Test database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        echo "<p style='color:red'>Database Connection: FAILED - " . $conn->connect_error . "</p>";
    } else {
        echo "<p style='color:green'>Database Connection: SUCCESS</p>";
        
        // Get WordPress URLs from database
        $result = $conn->query("SELECT option_name, option_value FROM wp_options WHERE option_name IN ('home', 'siteurl')");
        echo "<h2>WordPress URLs in Database:</h2>";
        while($row = $result->fetch_assoc()) {
            echo "<p>" . $row['option_name'] . ": " . $row['option_value'] . "</p>";
        }
    }
    $conn->close();
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>

