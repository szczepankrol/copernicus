<?php
/**
 * Copernicus Theme Framework Config file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/* ----------- copernicus info -------------- */

$cp_config['theme']['short_name'] = 'Copernicus';
$cp_config['theme']['full_name'] = 'Copernicus Framework Theme';
$cp_config['theme']['version'] = '0.2';
$cp_config['theme']['wp_support'] = '3.4';


/* ----------- wp theme support ----------- */

$cp_config['theme_support']['menu'] = true;
$cp_config['theme_support']['post_thumbnail'] = true;
$cp_config['theme_support']['automatic_feed_links'] = false;


/* ----------- cleanup -------------- */

$cp_config['cleanup']['meta']['generator'] = false; // Display the XHTML generator that is generated on the wp_head hook, WP version
$cp_config['cleanup']['meta']['rsd'] = false; // Display the link to the Really Simple Discovery service endpoint, EditURI link
$cp_config['cleanup']['meta']['wlwmanifest'] = false; // // Display the link to the Windows Live Writer manifest file.
$cp_config['cleanup']['meta']['index_rel'] = false; // index link
$cp_config['cleanup']['meta']['feed_links_extra'] = false; // Display the links to the extra feeds such as category feeds
$cp_config['cleanup']['meta']['feed_links'] = false; // Display the links to the general feeds: Post and Comment Feed
$cp_config['cleanup']['meta']['parent_post_rel'] = false; // prev link
$cp_config['cleanup']['meta']['start_post_rel'] = false; // start link
$cp_config['cleanup']['meta']['adjacent_posts_rel'] = false; // Display relational links for the posts adjacent to the current post.

$cp_config['cleanup']['js']['l10n'] = false; 

$cp_config['cleanup']['admin']['bar'] = false; 


/* ----------- plugins -------------- */

$cp_config['plugins']['admin_auto_menu_order'] = true; // auto populate order field
$cp_config['plugins']['admin_page_order_dnd'] = false; // page ordering with d'n'd


/* ----------- css files -------------- */

$cp_config['css'][] = array(
	'name' => 'style',
	'url' => '',
	'filename' => 'static/css/style.css',
	'media' => 'all',
	'front' => true,
	'admin' => false,
	'dependencies' => array(),
	'condition' => false, // lt IE 9
	'version' => false
);


/* ----------- js files -------------- */

$cp_config['js'][] = array(
	'name' => 'script',
	'url' => '',
	'filename' => 'static/js/script.js',
	'footer' => true,
	'front' => true,
	'admin' => false,
	'dependencies' => array('jquery'),
	'version' => false
);

$cp_config['js'][] = array(
	'name' => 'jquery',
	'url' => 'http://ajax.googleapis.com/ajax/libs',
	'filename' => 'jquery/1.7.2/jquery.min.js',
	'footer' => true,
	'front' => true,
	'admin' => false,
	'dependencies' => array(),
	'version' => false
);


/* ----------- Custom Post Type -------------- */


/* ----------- Post Meta Boxes -------------- */


/* ----------- Taxonomy -------------- */


/* ----------- Users -------------- */


/* ----------- User Meta Boxes -------------- */


/* ----------- Custom Menus -------------- */

?>