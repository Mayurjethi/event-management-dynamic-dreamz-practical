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
define( 'DB_NAME', 'dynamic_dreamz_prctl' );

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
define( 'AUTH_KEY',         'PI(D<fD9?=c7Rv<^af44YNFOB:#&j=%!euoQZc>k- 9iFoxq|i#.N+%.Mync2hkD' );
define( 'SECURE_AUTH_KEY',  'O;3bQ_M?s5>#7 Hw{ad<19]$L}b>ieCb/7BgG$>O>IA:j_!OL-0X$,q`IB.Y@1|L' );
define( 'LOGGED_IN_KEY',    '9ET)=rpx>pNsLu,In<vM|[xR8Z/s`t/oYv6 0:X9{e~QZX~}nMdXwwV3lmo$VFL$' );
define( 'NONCE_KEY',        '7={^O7]{@{8W#&F-SrI`2/0n%#&N?eIlA:0E6}S;=/:Kx!_.L3ZvNGf4voaZZy!y' );
define( 'AUTH_SALT',        'ePW$K6dxWRZr<r) $vfrFhw]8ilca+*dSP}}$^W#4slMQy~xR$7eV%7mEE&P)1Q5' );
define( 'SECURE_AUTH_SALT', '],Sfl$Yx/vHK;m]gM}|sz$O.&$ibAN>Rf[h)mb4vXt9|v]X`)x{&{b`:Wp,H1K]V' );
define( 'LOGGED_IN_SALT',   'qF%6|_P<zT/4iXwTh`Iz.>6ki!!Fq*[lQ0y/<$zErf9!WV4II#gHarQdw= e^9B]' );
define( 'NONCE_SALT',       'zIhAm61s:}&tyjKHipP@rNd,8eU1RzQn%F)f#LMWoI-_-LZx-+<yM(BP2$5}`*)x' );

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
define( 'WP_DEBUG',true );
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
