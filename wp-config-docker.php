<?php
/**
 * WordPress Configuration for Docker
 * Rename this file to wp-config.php to use with Docker
 */

// ** Database settings for Docker ** //
define( 'DB_NAME', 'schoolbord_db' );
define( 'DB_USER', 'wordpress' );
define( 'DB_PASSWORD', 'schoolbord2024' );
define( 'DB_HOST', 'db:3306' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', 'utf8mb4_unicode_ci' );

/**#@+
 * Authentication unique keys and salts.
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

/**#@-*/

$table_prefix = 'wp_';

// WordPress Debug Settings
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );

// Performance Settings
define( 'WP_MEMORY_LIMIT', '512M' );
define( 'WP_MAX_MEMORY_LIMIT', '1024M' );
define( 'WP_POST_REVISIONS', 3 );
define( 'AUTOSAVE_INTERVAL', 300 );

// Security Settings
define( 'DISALLOW_FILE_EDIT', false );
define( 'FORCE_SSL_ADMIN', false );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );

// WordPress URLs for Docker
define( 'WP_HOME', 'http://localhost:8080' );
define( 'WP_SITEURL', 'http://localhost:8080' );

// Cache Settings
define( 'WP_CACHE', true );
define( 'COMPRESS_CSS', true );
define( 'COMPRESS_SCRIPTS', true );

// File Permissions
define( 'FS_METHOD', 'direct' );

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
