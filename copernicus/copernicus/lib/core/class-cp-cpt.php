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
			'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
				)
		);
	}

	public function add_meta_boxes() {

		// if custom post type has fields
		if (is_array($this->cpts['fields'])) {

			// for each field
			foreach ($this->cpts['fields'] as $field) {

				// there are 3 field types: standard, custom, group
				switch ($field['type']) {

					// add meta box (group)
					case 'group':
						add_meta_box($field['id'], $field['name'], array($this, 'add_meta_box'), $this->cpts['settings']['name'], $field['context'], $field['priority'], array('fields' => $field['fields']));
						break;
				}
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

				// create a form field based on the type of field
				switch ($field['field_type']) {

					// text field
					case 'text':
						echo '<p><label for="' . $field['id'] . '">' . $field['name'] . ':</label><input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '" /></p>';
						break;

					// textarea field
					case 'textarea':
						echo '<p><label for="' . $field['id'] . '">' . $field['name'] . ':</label><textarea name="' . $field['id'] . '" id="' . $field['id'] . '">' . $value . '</textarea></p>';
						break;
				}
			}
		}
	}

	public function save_cpt() {

		// if custom post type has fields
		if (is_array($this->cpts['fields'])) {

			// for each field
			foreach ($this->cpts['fields'] as $field) {

				// there are 3 field types: standard, custom, group
				switch ($field['type']) {

					// save meta box (group)
					case 'group':

						// Save all fields in meta box (group)
						$this->save_meta_box($field['fields']);
						break;
				}
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
				echo $orderby;
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
