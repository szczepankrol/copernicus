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
	'filename' => 'jquery/1.8.0/jquery.min.js',
	'footer' => true,
	'front' => true,
	'admin' => false,
	'dependencies' => array(),
	'version' => false
);


/* ----------- Custom Post Type -------------- */

// test field
$cp_config['cpt'][] = array(
	'settings' => array(
		'active' => true,
		'name' => 'test',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'page',
		'hierarchial' => false,
		'rewrite' => array('slug' => 'test'),
		'orderby' => 'menu_order',
		'order' => 'ASC'
	),
	'labels' => array(
		'name' => __('Test'),
		'singular_name' => __('Test'),
		'add_new' => __('Add New'),
		'add_new_item' => __('Add New Test'),
		'edit_item' => __('Edit Test'),
		'new_item' => __('New Test'),
		'view_item' => __('View Test'),
		'search_items' => __('Search Test'),
		'not_found' => __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	),
	'support' => array(
		'title' => true,
		'editor' => true,
		'author' => true,
		'thumbnail' => true,
		'excerpt' => true,
		'trackbacks' => true,
		'custom-fields' => false,
		'comments' => true,
		'revisions' => true,
		'page-attributes' => true,
		'post-formats' => false
	)
);


/* ----------- Post Meta Boxes -------------- */

$cp_config['mb'][] = array(
	'settings' => array(
		'active' => true,
		'id' => 'test_box',
		'name' => 'All Fields',
		'post_type' => 'test',
		'context' => 'normal', // normal | advanced | side
		'priority' => 'high' // high | core | default | low
	),
	'fields' => array(
		1 => array(
			'id' => 'text',
			'name' => 'Text',
			'type' => 'text',
			'prefix' => 'prefix',
			'suffix' => 'suffix',
			'description' => 'match pattern [A-Za-z]{3}',
			'default_value' => 'default value',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => true,
				'placeholder' => 'text placeholder',
				'pattern' => '[A-Za-z]{3}',
				'autocomplete' => 'on',
				'autofocus' => false,
			)
		),
		11 => array(
			'id' => 'email',
			'name' => 'E-mail',
			'type' => 'email',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => true,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => true,
			)
		),
		12 => array(
			'id' => 'number',
			'name' => 'Number',
			'type' => 'number',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'min' => 10,
				'max' => 20,
				'step' => 2,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		121 => array(
			'id' => 'range',
			'name' => 'Range',
			'type' => 'range',
			'prefix' => '10',
			'suffix' => '20',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'min' => 10,
				'max' => 20,
				'step' => 2,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		13 => array(
			'id' => 'password',
			'name' => 'Password',
			'type' => 'password',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		14 => array(
			'id' => 'color',
			'name' => 'Color',
			'type' => 'color',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		15 => array(
			'id' => 'date',
			'name' => 'Date',
			'type' => 'date',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		16 => array(
			'id' => 'url',
			'name' => 'Url',
			'type' => 'url',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '120px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		2 => array(
			'id' => 'textarea',
			'name' => 'Text Area',
			'type' => 'textarea',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '400px',
				'height' => '60px',
				'rows' => 6,
				'cols' => 60,
				'readonly' => false,
				'disabled' => false,
				'required' => true
			)
		),
		3 => array(
			'id' => 'editor',
			'name' => 'Editor',
			'type' => 'editor'
		),
		4 => array(
			'id' => 'checkbox',
			'name' => 'Checkboxes',
			'type' => 'checkbox',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'values' => array(
				1 => 'checkbox 1',
				2 => 'checkbox 2',
				3 => 'checkbox 3',
			),
			'attributes' => array(
				//'width' => '120px',
				//'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		5 => array(
			'id' => 'radio',
			'name' => 'Radio',
			'type' => 'radio',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'values' => array(
				1 => 'radio 1',
				2 => 'radio 2',
				3 => 'radio 3',
			),
			'attributes' => array(
				//'width' => '120px',
				//'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'autocomplete' => 'off',
				'autofocus' => false,
			)
		),
		6 => array(
			'id' => 'select',
			'name' => 'Select',
			'type' => 'select',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'values' => array(
				0 => '-- select --',
				1 => 'select 1',
				2 => 'select 2',
				3 => 'select 3',
			),
			'attributes' => array(
				'autofocus' => false,
				'disabled' => false,
				'required' => true,
				'size' => '1',
				'width' => '120px',
			)
		),
		7 => array(
			'id' => 'multiselect',
			'name' => 'Multi Select',
			'type' => 'multiselect',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'values' => array(
				'' => '-- select --',
				1 => 'select 1',
				2 => 'select 2',
				3 => 'select 3',
				4 => 'select 4',
				5 => 'select 5',
			),
			'attributes' => array(
				'autofocus' => false,
				'disabled' => false,
				'required' => true,
				'size' => '3',
				'width' => '120px',
			)
		),
		8 => array(
			'id' => 'post_link',
			'name' => 'Post Link',
			'type' => 'post_link',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'attributes' => array(
				'autofocus' => false,
				'disabled' => false,
				'required' => true,
				'size' => '1',
				'width' => '120px',
			),
			'arguments' => array(
				'post_type' => 'test'
			)
		),
		9 => array(
			'id' => 'file',
			'name' => 'File',
			'type' => 'file',
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'attributes' => array(
				'autofocus' => false,
				'disabled' => false,
				'required' => true,
				'size' => '1',
				'width' => '120px',
			)
		),
	)
);

/* ----------- Taxonomy -------------- */


/* ----------- Users -------------- */


/* ----------- User Meta Boxes -------------- */


/* ----------- Custom Menus -------------- */

?>