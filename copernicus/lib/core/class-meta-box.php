<?php

/**
 * Create meta boxes
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
class cp_meta_box {
	
	private $pt;
	
	public function __construct($pt) {
		
		$this->_set_pt($pt);
		
		add_action('admin_init', array($this, 'add_meta_groups'));
		
		add_action('save_post', array($this, 'save_cpt'));
		
		add_filter('pre_get_posts', array($this, 'set_admin_order'));
		
		if ($_GET['post_type'] == $this->pt['settings']['name']) {

			add_action('manage_posts_custom_column', array($this, 'custom_columns'));
			add_filter('manage_edit-' . $this->pt['settings']['name'] . '_columns', array($this, 'define_columns'));
		}
	}

	public function add_meta_groups() {

		// if custom post type has fields
		if (is_array($this->pt)) {

			foreach ($this->pt['custom_fields'] AS $group) {
				// add meta box (group)
				add_meta_box($group['id'], $group['name'], array($this, 'add_meta_box'), $this->pt['settings']['name'], $group['context'], $group['priority'], array('fields' => $group['fields']));

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

					// wysiwyg editor field
					case 'editor':
						echo '<div class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
						wp_editor($value, $field['id'], array());
						echo '</div>';
						break;

					// checkboxes
					case 'checkbox':
						if ($value)
							$values = maybe_unserialize($value);
						else
							$values = array();
						echo '<div class="cp_meta_box">';
						echo '<span class="title">' . $field['name'] . ':</span>';
						echo '<ul>';
						if (is_array($field['value'])) {
							foreach ($field['value'] AS $field_key => $field_value) {
								echo '<li>';
								echo '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '_' . $field_key . '" value="' . $field_key . '" ';
								if (in_array($field_key, $values))
									echo 'checked="checked" ';
								echo ' /> ';
								echo '<label for="' . $field['id'] . '_' . $field_key . '">' . $field_value . '</label>';
								echo '</li>';
							}
						}
						echo '</ul>';
						echo '</div>';
						break;

					// selectbox
					case 'selectbox':
						if ($value)
							$values = maybe_unserialize($value);
						else
							$values = array();
						echo '<div class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
						echo '<select id="' . $field['id'] . '" name="' . $field['id'] . '[]" size="' . $field['size'] . '" ';
						if ($field['multiple'])
							echo 'multiple="multiple" ';
						echo '>';
						if (is_array($field['value'])) {
							foreach ($field['value'] AS $field_key => $field_value) {
								echo '<option value="' . $field_key . '" ';
								if (in_array($field_key, $values))
									echo 'selected="selected" ';
								echo '> ';
								echo $field_value;
								echo '</option>';
							}
						}
						echo '</select>';
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
								if ($value == $field_key)
									echo 'checked="checked" ';
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
		if (is_array($this->pt['custom_fields'])) {

			// for each field
			foreach ($this->pt['custom_fields'] as $field) {

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
		if (isset($this->pt['columns'])) {

			// reset default columns
			$columns = array();

			// for each column in config
			foreach ($this->pt['columns'] as $column) {

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
		if (isset($this->pt['columns'])) {

			// reset default columns
			$columns = array();

			// for each column in config
			foreach ($this->pt['columns'] as $cp_column) {
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
		$orderby = $this->pt['settings']['orderby'];
		$order = $this->pt['settings']['order'];

		if (isset($_GET['orderby']))
			$orderby = $_GET['orderby'];
		if (isset($_GET['order']))
			$order = $_GET['order'];

		// Get the post type from the query  
		$post_type = $wp_query->query['post_type'];

		if ($post_type == $this->pt['settings']['name']) {

			if (isset($this->pt['settings']['meta_order']) && $this->pt['settings']['meta_order'] != '') {
				$wp_query->set('orderby', $this->pt['settings']['meta_order']);
				$wp_query->set('meta_key', $orderby);
			} else {
				// 'orderby' value can be any column name  
				$wp_query->set('orderby', $orderby);
			}

			// 'order' value can be ASC or DESC  
			$wp_query->set('order', $order);
		}
	}
	
	private function _set_pt($pt){
		$this->pt = $pt;
	}
	
}

?>