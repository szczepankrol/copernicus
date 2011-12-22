<?php

/**
 * Framework constants
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

/**
 * Framework
 */
define('CP_SHORT_NAME', 'Copernicus');
define('CP_FULL_NAME', 'Copernicus - Wordpress Theme Framework');
define('CP_VERSION', '0.1');
define('CP_WP_SUPPORT', '3.2');

/**
 * Requirements
 */
define('CP_PHP_REQUIRED', '5.2.4');

/**
 * Additional frameworks
 */
define('CP_TWIG', '1.3');
define('CP_PHPTHUMB', '');

/**
 * Path & urls
 */
define('CP_BASE_PATH', ABSPATH); // wordpress base path
define('CP_THEME_PATH', get_stylesheet_directory());
define('CP_LIB_PATH', CP_COPERNICUS_PATH . '/lib');
define('CP_CACHE_PATH', CP_BASE_PATH . 'wp-content/cache');

define('CP_BASE_URL', get_bloginfo('wpurl'));
define('CP_COPERNICUS_URL', get_template_directory_uri());

define('CP_COPERNICUS_DIR', str_replace(CP_BASE_URL, '', CP_COPERNICUS_URL));
define('CP_COPERNICUS_STATIC_DIR', CP_COPERNICUS_DIR . '/static');

?>