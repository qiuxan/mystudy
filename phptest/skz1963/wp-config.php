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
define('DB_NAME', 'skzdb');

/** MySQL database username */
define('DB_USER', 'skz1963admin');

/** MySQL database password */
define('DB_PASSWORD', '2302509');

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
define('AUTH_KEY',         'hq{DCeb`<kX*-7|A]U|N Z;=);AG[*`Vdd&ld46`yhO7_Hhgt!Zh6iaiOL*>}}w>');
define('SECURE_AUTH_KEY',  'gP23}o6Loqlv>Fjh,)e84ank)cFyz0WV)lzKt*gGE+bm{_*bO`41[]s]~~j3*GRW');
define('LOGGED_IN_KEY',    'FUp`}0?:I1kN+DUO}#LME,JfBidDz/7~A,/J>2%FW%4N<_z{3G(2V5|Y#o3+3|%l');
define('NONCE_KEY',        '9AYrgxa)-JmN;~UW1&.%c6=OQcWb/!.<y)FzRFy62<l{cf9Xa}HN/!G6|f;_B09t');
define('AUTH_SALT',        'l-MJkotyU[p*Ax&#Pyz/f11O=4|,v42eS56dpD)/+/Hw{v00S`:^GV=~(qmxP3kK');
define('SECURE_AUTH_SALT', 'G`71s5+(4P76-FNW[-N{cG9.MX|#1c8MA GZL0vSR@8[gt5M39Qx=8jcO94XFnu+');
define('LOGGED_IN_SALT',   '|&v,-w.&,G!fo/p 9fiIB$u<W{ab)/S2x-a/$E9y,d7`m=NGoHmx<n|~sU7>C{m7');
define('NONCE_SALT',       'Oq O2*5|ZMb<JnH7@;rlsjTEnZwRwrp~go}r$?6DG1orX;kA4s83Xrq:3;^tCrBA');

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
