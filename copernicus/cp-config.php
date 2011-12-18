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

$cp_config['js'][] = array('name'=>'jquery', 'googleapis'=>'/jquery/1.6.2/jquery.min.js');
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

/**
 * Plugins
 */

$cp_config['plugins']['admin_auto_menu_order'] = true; // auto populate order field
$cp_config['plugins']['admin_page_order_dnd'] = false; // auto populate order field

/**
 * Custom Post Types
 */

// Team members

$cp_cpt[] = array(
	'settings' => array(
		'active' => true,
		'name' => 'team',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'page',
		'hierarchial' => true,
		'rewrite' => array('slug' => 'team'),
		'orderby' => 'email',
		'order' => 'ASC',
		'meta_order' => 'meta_value'
	),
	'labels' => array(
		'name' => __('Team members'),
		'singular_name' => __('Team member'),
		'add_new' => __('Add New'),
		'add_new_item' => __('Add New Team Member'),
		'edit_item' => __('Edit Team Member'),
		'new_item' => __('New Team Member'),
		'view_item' => __('View Team Member'),
		'search_items' => __('Search Team Members'),
		'not_found' => __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	),
	'support' => array(
		'title' => true,
		'editor' => false,
		'author' => false,
		'thumbnail' => true,
		'excerpt' => false,
		'trackbacks' => false,
		'custom-fields' => false,
		'comments' => false,
		'revisions' => false,
		'page-attributes' => true,
		'post-formats' => false
	),
	'custom_fields' => array(
		0 => array(
			'id' => 'dialog',
			'name' => 'Dialog',
			'context' => 'normal', // normal | advanced | side
			'priority' => 'high', // high | core | default | low
			'fields' => array(
				1 => array(
					'id' => 'slogan',
					'name' => 'Slogan',
					'field_type' => 'text',
					'default' => 'asd'
				),
				2 => array(
					'id' => 'slogan_b',
					'name' => 'Slogan 2',
					'field_type' => 'text',
					'default' => ''
				),
				3 => array(
					'id' => 'descr',
					'name' => 'Description',
					'field_type' => 'textarea',
					'default' => ''
				),
				4 => array(
					'id' => 'h1',
					'name' => 'H1',
					'field_type' => 'text',
					'default' => ''
				),
				5 => array(
					'id' => 'email',
					'name' => 'E-mail',
					'field_type' => 'text',
					'default' => ''
				)
			)
		)
	),
	'columns' => array(
		1 => array (
			'type' => 'checkbox',
			'id' => 'cb',
		),
		2 => array (
			'type' => 'standard',
			'id' => 'title',
			'name' => 'Title'
		),
		3 => array (
			'type' => 'custom',
			'id' => 'email',
			'name' => 'E-mail'
		),
		4 => array (
			'type' => 'custom',
			'id' => 'slogan',
			'name' => 'Slogan'
		)
	)
);


// Slider

$cp_cpt[] = array(
	'settings' => array(
		'active' => true,
		'name' => 'slider',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'page',
		'hierarchial' => true,
		'rewrite' => array('slug' => 'slider'),
		'orderby' => 'menu_order',
		'order' => 'ASC'
	),
	'labels' => array(
		'name' => __('Slider'),
		'singular_name' => __('Slider'),
		'add_new' => __('Add New'),
		'add_new_item' => __('Add New Slider'),
		'edit_item' => __('Edit Slider'),
		'new_item' => __('New Slider'),
		'view_item' => __('View Slider'),
		'search_items' => __('Search Slider'),
		'not_found' => __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	),
	'support' => array(
		'title' => true,
		'editor' => false,
		'author' => false,
		'thumbnail' => true,
		'excerpt' => false,
		'trackbacks' => false,
		'custom-fields' => false,
		'comments' => false,
		'revisions' => false,
		'page-attributes' => true,
		'post-formats' => false
	),
	'custom_fields' => array(
		0 => array(
			'id' => 'dialog',
			'name' => 'Dialog',
			'context' => 'normal', // normal | advanced | side
			'priority' => 'high', // high | core | default | low
			'fields' => array(
				1 => array(
					'id' => 'slogan',
					'name' => 'Slogan',
					'field_type' => 'text',
					'default' => 'asd'
				),
				2 => array(
					'id' => 'slogan_b',
					'name' => 'Slogan 2',
					'field_type' => 'text',
					'default' => ''
				),
				3 => array(
					'id' => 'descr',
					'name' => 'Description',
					'field_type' => 'textarea',
					'default' => ''
				),
				4 => array(
					'id' => 'h1',
					'name' => 'H1',
					'field_type' => 'text',
					'default' => ''
				),
				5 => array(
					'id' => 'email',
					'name' => 'E-mail',
					'field_type' => 'text',
					'default' => ''
				),
				6 => array(
					'id' => 'month',
					'name' => 'Month',
					'field_type' => 'checkbox',
					'value' => array(
						1 => 'January',
						2 => 'February',
						3 => 'March'
					),
				),
				7 => array(
					'id' => 'ctype',
					'name' => 'Type',
					'field_type' => 'radio',
					'value' => array(
						1 => 'January',
						2 => 'February',
						3 => 'March'
					),
				)
			)
		)
	),
	'manage_columns' => array(
		'title',
		'editor',
		'name'
	)
);

?>