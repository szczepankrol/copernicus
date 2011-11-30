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
 * Path & urls
 */
define('CP_THEME_URL', get_bloginfo('template_url'));
define('CP_THEME_DIR', dirname(dirname(dirname(__FILE__))));
define('CP_LIB_DIR', CP_THEME_DIR . '/lib');
?>