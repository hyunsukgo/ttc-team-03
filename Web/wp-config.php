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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'ttc' );

/** MySQL database password */
define( 'DB_PASSWORD', 'TTC@2020sk' );

/** MySQL hostname */
define( 'DB_HOST', 'db.team03.sk-ttc.com' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
define( 'WP_HOME', 'http://team03.sk-ttc.com');
define( 'WP_SITEURL', 'http://team03.sk-ttc.com');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         't6n Awa:DU $/A#Q~w|]]EqIK78i[cl`|}]z=JKGcLB3$JMSb2tm;AXtG:;9d!Hc' );
define( 'SECURE_AUTH_KEY',  'm2~F~aq7xC}mja!qdS67n$O9vt+Pf9UtPD!531%8WXLL>tEMLM@%KC9^3yg8e9~5' );
define( 'LOGGED_IN_KEY',    'm:N$V2TVKe1JHhK_/%w[I(J*_s$DgKpV[#5V#aGP {-giK:esEO&NhE(Jn@:F*|U' );
define( 'NONCE_KEY',        'eU-5UF9+BF{c+ZuUWNxO`W%|Ve,Es1TpmxfO+tCf$8z=??6;6,y:M?ln+R$_*fLL' );
define( 'AUTH_SALT',        'Q<d!%)OHT~v4qWzn#0~_*#HN)jn0VA@62l(sBup~|<)}47h8s7@)FFN%VX=V-]#y' );
define( 'SECURE_AUTH_SALT', '~!A4C3*^uf?*mk^{H4#:qITYU1W1L2UPZkM#}2Mcq+0AlTxTFLiC1YcgFQJJMtPg' );
define( 'LOGGED_IN_SALT',   '%MKGOC<jb9}=;}nVS8pAL}l&zu%]R|C0x[61@;=BE=&V$l/YU&Z[iJJ3C<5bKu^+' );
define( 'NONCE_SALT',       '>,15K~i>?0nHgOjgYp]!H*t1|=g!h(voCpe[![[a0vrJcwdd|*MspiS&}]g}tOse' );

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
