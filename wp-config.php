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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '!_9]0sK?cZ Ai/GPO{f4H[|x%HlS;7}=R{xFU,p}^sG^h)7n20J&8YPa!U)<s}<*');
define('SECURE_AUTH_KEY',  's/_fAu$w8vcDQglhT8PNgVr=. r:U}[m^R*}>el&>~N!l40P/j~p#Y&n<)XM$B+1');
define('LOGGED_IN_KEY',    'G!8i^h*:k_XrUX%#nx~l;O$NHd$4(L1`RhgK3f~eNAI_Da1h0V/m2vQu]TxR#m~[');
define('NONCE_KEY',        '<RUMc4`a$d9olz<~gon-PV0Z>c~xzN6J-cL_Bkw$FGSziHkooxY<lHMT9{HRfrKB');
define('AUTH_SALT',        'UzHQ9Zd.D]Xxd@0%6r_H[<Ocbne$meBW?*Q1uiE&:E{;(LdN6!6NTZZWXFo{-%@6');
define('SECURE_AUTH_SALT', 'xbb_|F6D5 %_+H*^~s9+P>7PB*S$igEQ3a#O~Q4I`G0F`Tl?@Z;<)<rjCq|`it_6');
define('LOGGED_IN_SALT',   '0r:4+7x>*AW3QmXl};VR?xi;Zw`TU]UcTxW6| r[j:EC8cxuX~jPpfqb`k|blvsN');
define('NONCE_SALT',       ',fNiO`Qa|r;h&l7^ok/GI/@&+mH_}<L<4YOFx(!lF)zq!I:e26)K9EkJlm9]kP;@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
