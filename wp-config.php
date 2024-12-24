<?php


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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'masterqu_fen2' );

/** Database username */
define( 'DB_USER', 'masterqu_fen2' );

/** Database password */
define( 'DB_PASSWORD', 'p6[3SZ8m(N' );

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
define( 'AUTH_KEY',         'm9rrqlmbrj7wpw4n4i6vmyx0x9ldimnyiqpxegtldrn0zajnfkf4r47wwhq7wdje' );
define( 'SECURE_AUTH_KEY',  'b5jomyupwffutfrnobptfmm5g6roivz1mdtteeawfbuac8peyxzcaz71sy8ztlld' );
define( 'LOGGED_IN_KEY',    'jvmfkhatrknm7l2drne3ay5qw7qarwh8b14w67oso7r3wd9hyquu72vht6v4pel2' );
define( 'NONCE_KEY',        'lbifgeqt5uqnb3x47ahbrq5dyh06oaqu2dldjkbv9hkdwwdhiaetxi5ksacowvsv' );
define( 'AUTH_SALT',        'kfazpgsadd5fonalkaikokh4azltglzohpj6v10kteincc3xlkcg0w8c9etpmwbm' );
define( 'SECURE_AUTH_SALT', 'vdk2uedrcpyio1yurslkcp8rl0hmazdcc9bqopbucqvskjtt9yg6xg7qnfvcu9ls' );
define( 'LOGGED_IN_SALT',   'njhbc1x9vfelolnpa46tk9w9iwle3mohvgs2yppgwruu1ztpexo2llv1y5vsxypj' );
define( 'NONCE_SALT',       'kkrn7wnx29lbcgwjylxoljsc3hsnrlprkyrlo2pbf5zux7ztj2pio38qef8s8whn' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpgb_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

define( 'WP_AUTO_UPDATE_CORE', 'minor' );


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
