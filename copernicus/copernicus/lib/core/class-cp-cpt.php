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

			add_action('admin_init', array($this, 'add_meta_boxes'));

			add_action('save_post', array($this, 'save_cpt'));

			add_filter('pre_get_posts', array($this, 'set_order'));

			if ($_GET['post_type'] == $this->cpts['settings']['name']) {
				
				add_action('manage_posts_custom_column', array($this, 'custom_columns'));
				add_filter('manage_edit-' . $this->cpts['settings']['name'] . '_columns', array($this, 'define_columns'));
				
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
		if (isset ($this->cpts['columns'])) {
			$columns = $this->cpts['columns'];
		}
		return $columns;
	}

	public function custom_columns($column) {
		global $post;

		add_image_size('mini', 100, 150, true);

		switch ($column) {
			case 'description':
				the_excerpt();
				break;
			case 'email':
				$custom = get_post_custom();
				echo $custom['email'][0];
				break;
			case 'photo':
				the_post_thumbnail('mini');
				break;
		}
	}

	public function set_order() {
		
	}

}

?>
