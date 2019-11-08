<?php
// local instance - DDEV
if (strpos($_SERVER['HTTP_HOST'], 'ddev.local') !== false) {
    define('DB_NAME', 'db');
    define('DB_USER', 'db');
    define('DB_PASSWORD', 'db');
    define('DB_HOST', 'db');
    define('WP_DEBUG', true);
    define('WP_HOME', 'https://wordpress-muster.ddev.local');
}
// local instance - without DDEV
elseif (strpos($_SERVER['HTTP_HOST'], 'diploma-thesis.test') !== false) {

    define('DB_NAME', 'ukf_diplomoka');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');
    define('WP_DEBUG', true);
    define('WP_HOME', 'http://diploma-thesis.test');
}
//// dwtest preview
//elseif (strpos($_SERVER['HTTP_HOST'], 'dwtest.at') !== false) {
//    define('DB_NAME', 'fonira');
//    define('DB_USER', 'fonira');
//    define('DB_PASSWORD', 'Uu7lr_85');
//    define('DB_HOST', 'localhost');
//    define('WP_DEBUG', true);
//    define('WP_HOME', 'https://fonira.dwtest.at');
//}
//// LIVE server
//else {
//    define('DB_NAME', '');
//    define('DB_USER', '');
//    define('DB_PASSWORD', '');
//    define('DB_HOST', 'localhost');
//    define('WP_DEBUG', false);
//    define('WP_HOME', 'https://wordpress-muster.dwtest.at');
//}

//define('ABSPATH', dirname(__FILE__) . '/wp');
//define('WP_SITEURL', WP_HOME . '/wp');
//define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content');
//define('WP_CONTENT_URL', WP_HOME . '/wp-content');

$table_prefix  = 'wp_';

define('AUTH_KEY', 'htXGbflmTsBQctOcdvWFxMlzAQgTBWzHYJtQPGvrdlxgSxZaYqXDPScJEbjqMcMD');
define('SECURE_AUTH_KEY', 'PsicnWtPxllHNhukoRqvnkyXVDhhxgXvvlmZxpLrFTHoncoAPFcbUAxAvLIcrZri');
define('LOGGED_IN_KEY', 'qSZGwfEpcudbepoTmUFNHnkWfbDGwcgaaEWizYTOVuraoHOFMPsRNVfuScHdyFSW');
define('NONCE_KEY', 'QYZXRMCaVvexBcHNrzSpXFbSZoduIhhfbIegssqwkqKjYibwjkzYCyZxqzlVzDUR');
define('AUTH_SALT', 'AVMiXCnRntWxLfbKUQllgYyvvzybeOFJpSsiCuEQqDDdDfwSQWVvICVjJgIYOHna');
define('SECURE_AUTH_SALT', 'dWgfbnXGzJapKDgmUJsUQHUKyqhbsowdDHZUmcfvuGkqbrhqRTEnexPeLxaJcXlZ');
define('LOGGED_IN_SALT', 'ysJaPzwXGOveqrgjHcemVMomaIgeZGiqLuZehPFBRfiBcYNbcrNvJwhgwydoLtdH');
define('NONCE_SALT', 'sFhlKPRfLmQcxFrkIthlZNSgXkKWaFFtZBxHmMxUzZgafqJVkgxNYcBPxEqMaqSa');

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
