<?php

/**
 * Create custom post types
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
class cpt {

	private $cpts; // custom post types from config

	public function __construct($cpt) {
		$this->cpts = $cpt;

		if ($this->cpts['settings']['active']) {

			add_action('init', array($this, 'create_cpt'));

			if (is_admin()) {
				
				add_action('admin_init', array($this, 'add_meta_boxes'));

				add_action('save_post', array($this, 'save_cpt'));

				add_filter('pre_get_posts', array($this, 'set_admin_order'));
			
			
				if ($_GET['post_type'] == $this->cpts['settings']['name']) {

					add_action('manage_posts_custom_column', array($this, 'custom_columns'));
					add_filter('manage_edit-' . $this->cpts['settings']['name'] . '_columns', array($this, 'define_columns'));

				}
			}
		}
	}

	public function create_cpt() {
		
		// create an array of supported elements
		$supports = array();
		if (is_array($this->cpts['support'])) {
			foreach ($this->cpts['support'] as $key => $value) {
				if ($value) $supports[] = $key;
			}
		}
		
		register_post_type(
				$this->cpts['settings']['name'], array(
			'labels' => $this->cpts['labels'],
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'capability_type' => 'page',
			'hierarchial' => true,
			'rewrite' => array('slug' => 'team'),
			'supports' => $supports
				)
		);
	}

	public function add_meta_boxes() {

		// if custom post type has fields
		if (is_array($this->cpts['custom_fields'])) {

			// for each field
			foreach ($this->cpts['custom_fields'] as $field) {

				// add meta box (group)
				add_meta_box($field['id'], $field['name'], array($this, 'add_meta_box'), $this->cpts['settings']['name'], $field['context'], $field['priority'], array('fields' => $field['fields']));
			}
		}
	}

	public function add_meta_box($post, $metabox) {

		// if meta box has fields
		if (isset($metabox['args']['fields'])) {
			global $post;

			// get data from the DB for current post id
			$custom = get_post_custom($post->ID);
			$fields = $metabox['args']['fields'];

			// for each field in a box
			foreach ($fields as $field) {

				// get value
				$value = $custom[$field['id']][0];

				// get default value if no value in db
				if ($value == '')
					$value = $field['default'];
				
				echo json_decode($value);
				// create a form field based on the type of field
				switch ($field['field_type']) {

					// text field
					case 'text':
						echo '<p class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
						echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '" />';
						echo '</p>';
						break;

					// textarea field
					case 'textarea':
						echo '<p class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
						echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '">' . $value . '</textarea>';
						echo '</p>';
						break;
					
					// selectboxes
					case 'selectbox':
						echo '<div class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
						echo '<ul>';
						if (is_array($field['value'])) {
							foreach ($field['value'] AS $field_key => $field_value) {
								echo '<li>';
								echo '<input type="checkbox" /> ' . $field_value . '';
								echo '</li>';
							}
						}
						echo '</ul>';
						echo '</div>';
						break;
						
					// checkboxes
					case 'checkbox':
						echo '<div class="cp_meta_box">';
						echo '<span class="title">' . $field['name'] . ':</span>';
						echo '<ul>';
						if (is_array($field['value'])) {
							foreach ($field['value'] AS $field_key => $field_value) {
								echo '<li>';
								echo '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '_' . $field_key . '" value="' . $field_key . '" /> ';
								echo '<label for="' . $field['id'] . '_' . $field_key . '">' . $field_value . '</label>';
								echo '</li>';
							}
						}
						echo '</ul>';
						echo '</div>';
						break;
					
					// radio 
					case 'radio':
						echo '<div class="cp_meta_box">';
						echo '<span class="title">' . $field['name'] . ':</span>';
						echo '<ul>';
						if (is_array($field['value'])) {
							foreach ($field['value'] AS $field_key => $field_value) {
								echo '<li>';
								echo '<input type="radio" name="' . $field['id'] . '" id="' . $field['id'] . '_' . $field_key . '" value="' . $field_key . '" ';
								if ($value == $field_key) echo 'checked="checked" ';
								echo '/> ';
								echo '<label for="' . $field['id'] . '_' . $field_key . '">' . $field_value . '</label>';
								echo '</li>';
							}
						}
						echo '</ul>';
						echo '</div>';
						break;
				}
			}
		}
	}

	public function save_cpt() {

		// if custom post type has fields
		if (is_array($this->cpts['custom_fields'])) {

			// for each field
			foreach ($this->cpts['custom_fields'] as $field) {

				// Save all fields in meta box (group)
				$this->save_meta_box($field['fields']);
			}
		}
	}

	public function save_meta_box($fields) {
		global $post;

		// can't save during autosave, otherwise it saves blank values (there's a problem that meta box values are not send with POST during autosave. Probably fixable
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		// for each field in a box
		foreach ($fields as $field) {
			update_post_meta($post->ID, $field['id'], $_POST[$field['id']]);
		}
	}

	public function define_columns($columns) {
		
		// if there are columns in config
		if (isset ($this->cpts['columns'])) {
			
			// reset default columns
			$columns = array();
			
			// for each column in config
			foreach ($this->cpts['columns'] as $column) {
				
				// switch based on column type
				switch ($column['type']) {
					
					case 'standard':
						$columns[$column['id']] = $column['name'];
						break;
					
					case 'custom':
						$columns[$column['id']] = $column['name'];
						break;
					
					case 'checkbox':
						$columns['cb'] = '<input type="checkbox" />';
						break;
				}
			}
		}
		return $columns;
	}

	public function custom_columns($column) {
		global $post;
		
		// if there are columns in config
		if (isset ($this->cpts['columns'])) {
			
			// reset default columns
			$columns = array();
			
			// for each column in config
			foreach ($this->cpts['columns'] as $cp_column) {
				// switch based on column type
				switch ($cp_column['type']) {
					
					case 'custom':
						if ($cp_column['id'] == $column) {
							$custom = get_post_custom();
							echo $custom[$cp_column['id']][0];
						}
						break;
				}
			}
		}

		
	}

	public function set_admin_order($wp_query) {
		$orderby = $this->cpts['settings']['orderby'];
		$order = $this->cpts['settings']['order'];

		if (isset($_GET['orderby']))
			$orderby = $_GET['orderby'];
		if (isset($_GET['order']))
			$order = $_GET['order'];

		// Get the post type from the query  
		$post_type = $wp_query->query['post_type'];

		if ($post_type == $this->cpts['settings']['name']) {

			if (isset ($this->cpts['settings']['meta_order']) && $this->cpts['settings']['meta_order']!='') {
				$wp_query->set('orderby', $this->cpts['settings']['meta_order']);
				$wp_query->set('meta_key', $orderby);
			}
			else {
				// 'orderby' value can be any column name  
				$wp_query->set('orderby', $orderby);
			}
			
			// 'order' value can be ASC or DESC  
			$wp_query->set('order', $order);
		}
	}

}

?>
