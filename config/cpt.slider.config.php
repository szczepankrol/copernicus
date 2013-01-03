<?php

// test field
$cp_config['cpt'][] = array(
	'settings' => array(
		'active' => true,
		'name' => 'slider',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'page',
		'hierarchial' => false,
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
		'post_type' => 'slider',
		'context' => 'normal', // normal | advanced | side
		'priority' => 'high' // high | core | default | low
	),
	'fields' => array(
		1 => array(
			'id' => 'text',
			'name' => 'Text',
			'type' => 'text',
			'translation' => 1,
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
		)
	)
);
