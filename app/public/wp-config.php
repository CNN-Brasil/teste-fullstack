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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'local');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', 'root');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY', '@Arb3$B#{/PloG(hk>0075WKZt?XOXB8?xILI0zpj_S%!hOIp|Iq2TCuIS(?yQ!u');
define('SECURE_AUTH_KEY', 'fmX5(/UYT8X:e#gg*kwD^86HYvy*?aItw-sMvH(J!D|j2a;/;r-Ko%eqDl=K#D74');
define('LOGGED_IN_KEY', 'w3]:}W,zKi>:nEpYzD/1a0Lo8WYCp& ksD<s<;i~&Gr7;RBfmrZ<f3WC)xTv=27!');
define('NONCE_KEY', '7BAUUOh_6Lb<5/o-~cD (W]%T0:Tt|/ &wTD_2K1($[;qL6l=cY,W$So_8RePx%g');
define('AUTH_SALT', 'd4I)k{F*:64iZ(!<4.)_i 9M^AESe31~8s;ot|sp|q.p!xzQq|Z_cNB3Uz17mM|I');
define('SECURE_AUTH_SALT', '{1VyCj//71<h+PH*3G/NE!QN&6Z.e(uX9letea#(#4|Q72oX^Dg8!Av72T|pdlqW');
define('LOGGED_IN_SALT', ',$#n0VdtEe=6JOKFXh#6y*O7s]~wK)9^*oG](S4#3NfL%|! z=3M8Ot}fn9m>k,W');
define('NONCE_SALT', 'J#:45AMu_Xr@;L?cEb]XG}o4@tn+`Rx0YL`nEVvzl/?YNko-p+4SY+Y&y%pq,QR}');
define('WP_CACHE_KEY_SALT', 'B*0W)Xcip&o9GFO/k)o1^R4fYXFB9mp@pyx7o*4[,Hm?c#o>4x$W/%zVN&Or:%x;');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if (! defined('WP_DEBUG')) {
    define('WP_DEBUG', false);
}

define('WP_ENVIRONMENT_TYPE', 'local');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
