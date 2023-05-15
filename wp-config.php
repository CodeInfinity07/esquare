<?php
define( 'WP_CACHE', true );

// Added by WP Rocket
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'esqugcfh_esquare' );

/** Database username */
define( 'DB_USER', 'esqugcfh_esquare' );

/** Database password */
define( 'DB_PASSWORD', '[[esquare1978]]' );

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
define( 'AUTH_KEY',         '5hkjnabwg3qinfaw1xmyxheenjpcukmfmk7zxddp86su7pvvvdjbmledgpps805c' );
define( 'SECURE_AUTH_KEY',  '1ldc8tjbbewl3i4mnx834embv2kdj9lkg6rfhpcot3i7f6xtovgzwreppgzf6p9c' );
define( 'LOGGED_IN_KEY',    'wzhabdl6i8tx6qwbh7ozdlypab3wfozq5fjkabvj6zjrjlb5mq39gkp7tr4pqfct' );
define( 'NONCE_KEY',        'tl2tohswiic6qr3y4cjirusnpkm6vggzfw0arztro2zgmd94u9jputded5qjm9ws' );
define( 'AUTH_SALT',        '1khigqd72mu4iwzusbldos9cmfjy1uh67dy40smb9957mxyybfwo3fppfix5wnw9' );
define( 'SECURE_AUTH_SALT', 'do8v1ydxswdcylbjwslh8hj6pdec5byi5bjaxcwbvm1v6dsj3onrv7qifwlv8hmo' );
define( 'LOGGED_IN_SALT',   'qcbr9v7jjnn3bn8dumeim6snlpjrsvmttbanok15g4tcpnf000ukghfcwf01dzoc' );
define( 'NONCE_SALT',       '2gfklratelhsoqyvpwuf2pukajpux5dwpm6kpjq5dzcxzdnippxgx4rcwftenyh8' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpbt_';

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
