<?php
/**
 * Theme Config file
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

/**
 * dirs & urls
 */

define( 'CP_TEMPLATE_DIR', '/templates');


/**
 * wp theme support
 */
define( 'CP_WP_SUPPORT_MENU', true);
define( 'CP_WP_SUPPORT_POST_THUMBNAIL', true);
define( 'CP_WP_SUPPORT_AUTOMATIC_FEED_LINKS', true);

/**
 * custom post types
 */

/**
 * JS files
 */

$cp_config['js'][] = array('folder'=>'/js', 'name'=>'script.js');


/**
 * CSS file
 */

$cp_config['css'][] = array('folder'=>'/css', 'name'=>'style.css', 'media'=>'all');
$cp_config['css'][] = array('folder'=>'/css', 'name'=>'print.css', 'media'=>'print');

?>