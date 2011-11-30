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
$cp_config['theme_support']['menu'] = false;
$cp_config['theme_support']['post_thumbnail'] = true;
$cp_config['theme_support']['automatic_feed_links'] = true;

/**
 * custom post types
 */

/**
 * JS files
 */

$cp_config['js'][] = array('name'=>'script', 'folder'=>'/js', 'filename'=>'script.js');
$cp_config['js'][] = array('name'=>'html5', 'folder'=>'/js', 'filename'=>'html5.js', 'header'=>true, 'condition'=>'lt IE 9');


/**
 * CSS file
 */

$cp_config['css'][] = array('name'=>'style', 'folder'=>'/css', 'filename'=>'style.css', 'media'=>'all', 'version'=>1);
$cp_config['css'][] = array('name'=>'print', 'folder'=>'/css', 'filename'=>'print.css', 'media'=>'print', 'version'=>1);
$cp_config['css'][] = array('name'=>'ie', 'folder'=>'/css', 'filename'=>'ie.css', 'media'=>'all', 'condition'=>'lt IE 8', 'version'=>1);

/**
 * cleanup
 */

$cp_config['cleanup']['meta']['generator'] = false; // Display the XHTML generator that is generated on the wp_head hook, WP version
$cp_config['cleanup']['meta']['rsd'] = false; // Display the link to the Really Simple Discovery service endpoint, EditURI link
$cp_config['cleanup']['meta']['wlwmanifest'] = false; // // Display the link to the Windows Live Writer manifest file.
$cp_config['cleanup']['meta']['index_rel'] = true; // index link
$cp_config['cleanup']['meta']['feed_links_extra'] = false; // Display the links to the extra feeds such as category feeds
$cp_config['cleanup']['meta']['feed_links'] = false; // Display the links to the general feeds: Post and Comment Feed
$cp_config['cleanup']['meta']['parent_post_rel'] = false; // prev link
$cp_config['cleanup']['meta']['start_post_rel'] = false; // start link
$cp_config['cleanup']['meta']['adjacent_posts_rel'] = false; // Display relational links for the posts adjacent to the current post.

$cp_config['cleanup']['ks']['l10n'] = false;

?>