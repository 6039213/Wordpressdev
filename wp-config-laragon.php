<?php
/**
 * WordPress Configuration for Laragon
 * 
 * Use this file if you prefer to use Laragon instead of Docker
 * Rename this file to wp-config.php to use with Laragon
 */

// ** Database settings for Laragon ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpressdev' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', 'utf8mb4_unicode_ci' );

/**#@+
 * Authentication unique keys and salts.
 */
define( 'AUTH_KEY',         'du[L=Yylf(6`bt}}&i+6H1+GWWbAQIl3#!Fn,gb~$[ ~Q?FyMka$xJ=rmy*+:ysB' );
define( 'SECURE_AUTH_KEY',  'J|[RYFD[7j)-n?>V5dsO,Q/~Z}]J[>7U|4O2!`SvO@U#G1rMv=J|85DXNqDyNh0T' );
define( 'LOGGED_IN_KEY',    'bZ:WI!&C4A4RuKWyu(R])P>9}:l):e%cji(*gpp4;Sm/0R;*WtK>0ZN@kS;LzejK' );
define( 'NONCE_KEY',        'V(5<Il.<%OH`&>p$5*,on}b)+$XF^jn@iSS$JGcHU/q,=wlYJdH/T =;K?kRWUmq' );
define( 'AUTH_SALT',        'ris[Y8>oS?+16p]<aoir,}|kPMU~#,6f5iMQ7+oy0o*ZTE9g96]I2Gc%zr,ok|yk' );
define( 'SECURE_AUTH_SALT', '0aU@QaJ*6P5(i6*5F]VlS B6`**8*hjzJ.Y$aKMa2;}VAGBqOF;f77s0n)+j1Cym' );
define( 'LOGGED_IN_SALT',   '$BOjNBeUv%tnBJ,Q0*;1K}yIm7HCX%0L0,`eenLcGhlj}Z:=.z}Zsd}Gff/Ttoj|' );
define( 'NONCE_SALT',       'xDN,r@P0r0o%GTeA &8Gxeu&BmaomRHqqaZs}t`tip#eAIm[:HaJ#[R:P?Vt41ub' );

/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wp_';

/**
 * WordPress Debug Settings
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );

/**
 * Performance Settings
 */
define( 'WP_MEMORY_LIMIT', '512M' );
define( 'WP_MAX_MEMORY_LIMIT', '1024M' );
define( 'WP_POST_REVISIONS', 3 );
define( 'AUTOSAVE_INTERVAL', 300 );
define( 'WP_CRON_LOCK_TIMEOUT', 60 );

/**
 * Security Settings
 */
define( 'DISALLOW_FILE_EDIT', true );
define( 'FORCE_SSL_ADMIN', false );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'DISALLOW_FILE_MODS', false );

/**
 * WordPress URLs for Laragon
 */
define( 'WP_HOME', 'http://wordpressdev.test' );
define( 'WP_SITEURL', 'http://wordpressdev.test' );

/**
 * Cache Settings
 */
define( 'WP_CACHE', true );
define( 'COMPRESS_CSS', true );
define( 'COMPRESS_SCRIPTS', true );
define( 'ENFORCE_GZIP', true );

/**
 * File Permissions
 */
define( 'FS_METHOD', 'direct' );
define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );
define( 'FS_CHMOD_FILE', ( 0644 & ~ umask() ) );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
