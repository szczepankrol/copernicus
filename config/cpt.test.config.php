<?php

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
		'title' => false,
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
			'id' => 'title',
			'name' => 'Title',
			'type' => 'text',
			'title' => 1,
			'translation' => 1,
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'default_value' => '',
			'attributes' => array(
				'width' => '190px',
				'size' => '12',
				'readonly' => false,
				'disabled' => false,
				'maxlength' => false,
				'required' => false,
				'placeholder' => '',
				'pattern' => '',
				'autocomplete' => '',
				'autofocus' => false,
			)
		),
		1111 => array(
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
				'required' => false,
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
			'translation' => 1,
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
				'autofocus' => true,
			)
		),
		12 => array(
			'id' => 'number',
			'name' => 'Number',
			'type' => 'number',
			'translation' => 1,
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
			'translation' => 1,
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
			'translation' => 1,
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
			'translation' => 1,
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
			'translation' => 1,
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
			),
			'options' => array(
				'dateFormat' => 'yy-mm-dd'
			)
		),
		16 => array(
			'id' => 'url',
			'name' => 'Url',
			'type' => 'url',
			'translation' => 1,
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
			'translation' => 1,
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
				'required' => false
			)
		),
		3 => array(
			'id' => 'editor',
			'name' => 'Editor',
			'type' => 'editor',
			'translation' => 1
		),
		4 => array(
			'id' => 'checkbox',
			'name' => 'Checkboxes',
			'type' => 'checkbox',
			'translation' => 1,
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
			'translation' => 1,
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
			'translation' => 1,
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
				'required' => false,
				'size' => '1',
				'width' => '120px',
			)
		),
		7 => array(
			'id' => 'multiselect',
			'name' => 'Multi Select',
			'type' => 'multiselect',
			'translation' => 1,
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
				'required' => false,
				'size' => '3',
				'width' => '120px',
			)
		),
		8 => array(
			'id' => 'post_link',
			'name' => 'Post Link',
			'type' => 'post_link',
			'translation' => 1,
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'attributes' => array(
				'autofocus' => false,
				'disabled' => false,
				'required' => false,
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
			'translation' => 1,
			'prefix' => '',
			'suffix' => '',
			'description' => '',
			'limit' => '1',
			'filetypes' => array(
				'jpg',
				'gif',
				'png'
			),
			'fields' => array(
			),
			'attributes' => array(
				'autofocus' => false,
				'disabled' => false,
				'required' => false,
				'size' => '1',
				'width' => '120px',
			)
		),
	)
);
