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
$cp_config['theme_support']['menu'] = true;
$cp_config['theme_support']['post_thumbnail'] = true;
$cp_config['theme_support']['automatic_feed_links'] = true;

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