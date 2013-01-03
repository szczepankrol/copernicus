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
		'author' => false,
		'thumbnail' => true,
		'excerpt' => false,
		'trackbacks' => false,
		'custom-fields' => false,
		'comments' => false,
		'revisions' => false,
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
			'title' => 1,
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '',
				'size' => '',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'pattern' => '',
				'autocomplete' => 'on',
				'autofocus' => false,
			)
		),
		2 => array(
			'id' => 'link',
			'name' => 'Link',
			'type' => 'text',
			'translation' => 1,
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '',
				'size' => '',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'pattern' => '',
				'autocomplete' => 'on',
				'autofocus' => false,
			)
		)
	)
);


$cp_config['loop'][] = array(
	'name' => 'slider',
	'template' => 'loop_slider.html',
	'args' => array(
		'post_type' => 'slider',
		'posts_per_page' => 5,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	)
);
