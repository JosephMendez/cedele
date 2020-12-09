<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cedele_prod');

/** MySQL database username */
define( 'DB_USER', 'web');

/** MySQL database password */
define( 'DB_PASSWORD', 'cedele654321');

/** MySQL hostname */
define( 'DB_HOST', '172.31.26.55');

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'f34fb35a0e0e034ce91218a72f4f5505256d32e0');
define( 'SECURE_AUTH_KEY',  'eaf6b5df1073e83a4e14cb6c7580ae6c18818d7d');
define( 'LOGGED_IN_KEY',    'efc464bc6de6734c52184c2e07b671ad7b63d9b8');
define( 'NONCE_KEY',        '14d874412f3edee40a063d130459614318a1dfb1');
define( 'AUTH_SALT',        'e52f2d1b5802b6131b1db99d454b6b2464f815e8');
define( 'SECURE_AUTH_SALT', '944984e4bc8a1706616cf96c654c013407347347');
define( 'LOGGED_IN_SALT',   'dca086629075f0c495ffe14b4038d302788ec34b');
define( 'NONCE_SALT',       '53102bf3d1e5231f6299e109ebe9754990a8e823');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
