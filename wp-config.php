<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Wordpressdev' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
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
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
