<?php

/**
 * Copernicus meta box class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * meta box class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Mb {

	// all meta boxes
	private $mb = array();

	/**
	 * Class constructor
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function __construct() {
		
		// initialize the meta boxes
		$this->_init();
	}

	/**
	 * Initiate the meta boxes
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function _init() {

		// get config
		$config = CP::get_config();
		
		if (isset ($config['mb'])) {
			
			// get meta box configuration
			$this->mb = $config['mb'];

			// add meta boxes
			add_action('admin_init', array($this, 'add_meta_boxes'));

			// save meta boxes
			add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
		}
	}

	/**
	 * Start adding meta boxes
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function add_meta_boxes() {

		// if there are meta boxes
		if (is_array($this->mb)) {

			// for each meta box
			foreach ($this->mb AS $mb) {

				// if meta box is active
				if ($mb['settings']['active'])
					// create meta box groups
					$this->add_meta_box_group($mb);
			}
		}
	}

	/**
	 * Create meta box groups
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function add_meta_box_group($mb) {

		// add meta group
		add_meta_box(
			$mb['settings']['id'], 
			$mb['settings']['name'], 
			array($this, 'add_meta_box'), 
			$mb['settings']['post_type'], 
			$mb['settings']['context'],
			$mb['settings']['priority'], 
			array('fields' => $mb['fields'])
		);
	}

	/**
	 * Create meta boxes
	 *
	 * @access type public
	 * @return type null no return
	 * @author Piotr Soluch
	 */
	public function add_meta_box($post, $meta_box) {
		
		$styles = '';
		
		// get data from the DB for current post id
		$custom = get_post_custom($post->ID);
		$fields = $meta_box['args']['fields'];

		wp_nonce_field(basename(__FILE__), 'add_meta_box_nonce');

		// for each field in a box
		foreach ($fields as $field) {
			$value = '';

			// get if the value is saved
			if (isset($custom[$field['id']][0]))
				$value = $custom[$field['id']][0];

			if (empty($value)) {
				if (isset($field['default_value'])) {
					$value = $field['default_value'];
				}
			}

			// create a form field based on the type of field
			switch ($field['type']) {

				// text field
				case 'text':
				case 'password':
				case 'email':
				case 'number':
				case 'range':
				case 'color':
				case 'date':
				case 'url':
					if (!isset($field['attributes']['size']))
						$field['attributes']['size'] = 30;
					
					$field['text'] =  '<input type="' . $field['type'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '"';
					if (isset($field['attributes']))
						$field['text'].= $this->meta_box_attributes($field['attributes']);
					
					$field['text'].= '/>';
					
					echo $this->meta_box_field($field);
					
					break;

				// textarea field
				case 'textarea':
					if (!isset($field['attributes']['rows']))
						$field['attributes']['rows'] = 6;
					if (!isset($field['attributes']['cols']))
						$field['attributes']['cols'] = 60;
					
					$field['text'] = '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '"';
					if (isset($field['attributes']))
						$field['text'].= $this->meta_box_attributes($field['attributes']);
					$field['text'].= '>' . $value . '</textarea>';
					
					echo $this->meta_box_field($field);
					
					break;

				// wysiwyg editor field
				case 'editor':
					ob_start();
					wp_editor($value, $field['id'], array());
					$field['text'] = ob_get_clean();
					echo $this->meta_box_field($field);
					
					break;

				// checkboxes
				case 'checkbox':
					if ($value)
						$values = maybe_unserialize($value);
					else
						$values = array();
					
					$field['text'] = '<span>' . $field['name'] . ':</span>';
					$field['text'].= '<ul>';
					if (is_array($field['values'])) {
						foreach ($field['values'] AS $field_key => $field_value) {
							$field['text'].= '<li>';
							$field['text'].= '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '_' . $field_key . '" value="' . $field_key . '" ';
							if (in_array($field_key, $values))
								$field['text'].= 'checked="checked" ';
							$field['text'].= ' /> ';
							$field['text'].= '<label for="' . $field['id'] . '_' . $field_key . '">' . $field_value . '</label>';
							$field['text'].= '</li>';
						}
					}
					$field['text'].= '</ul>';
					
					echo $this->meta_box_field($field, 0);
					
					break;
				
				// radio 
				case 'radio':
					$field['text'] = '<span>' . $field['name'] . ':</span>';
					$field['text'].= '<ul>';
					if (is_array($field['values'])) {
						foreach ($field['values'] AS $field_key => $field_value) {
							$field['text'].= '<li>';
							$field['text'].= '<input type="radio" name="' . $field['id'] . '" id="' . $field['id'] . '_' . $field_key . '" value="' . $field_key . '" ';
							if ($value == $field_key)
								echo 'checked="checked" ';
							$field['text'].= '/> ';
							$field['text'].= '<label for="' . $field['id'] . '_' . $field_key . '">' . $field_value . '</label>';
							$field['text'].= '</li>';
						}
					}
					$field['text'].= '</ul>';
					
					echo $this->meta_box_field($field, 0);
					
					break;
				
				// selectbox
				case 'selectbox':
					if (!isset ($field['multiple']))
						$field['multiple'] = 0;
					if ($field['multiple']) {
						if ($value)
							$values = maybe_unserialize($value);
						else
							$values = array();
					}
					echo '<div class="cp_meta_box">';
					echo '<label for="' . $field['id'] . '">' . $field['name'] . ':</label>';
					echo '<select id="' . $field['id'] . '" name="' . $field['id'];
					if ($field['multiple'])
						echo '[]';
					echo '" size="' . $field['size'] . '" ';
					if ($field['multiple'])
						echo 'multiple="multiple" ';
					echo '>';
					if (is_array($field['values'])) {
						foreach ($field['values'] AS $field_key => $field_value) {
							echo '<option value="' . $field_key . '" ';

							if (($field['multiple'] && in_array($field_key, $values)) || (!$field['multiple'] && $value == $field_key))
								echo 'selected="selected" ';
							echo '> ';
							echo $field_value;
							echo '</option>';
						}
					}
					echo '</select>';
					echo '</div>';
					break;

				// custom post type list
				case 'cpt':
					echo '<div class="cp_meta_box">';
					echo '<label for="' . $field['id'] . '">' . $field['name'] . ':</label>';
					echo '<select id="' . $field['id'] . '" name="' . $field['id'] .'">';
					echo '<option value="0"> -- select -- </option>';
					$args = array( 'post_type' => $field['cpt']);
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post();
						echo '<option value="'. get_the_ID() .'"';
							if ($value == get_the_ID())
								echo 'selected="selected" ';
							echo '>'.  get_the_title() .'</option>';
					endwhile;
					echo '</select>';
					echo '</div>';
					break;
			}
		}
	}
	
	private function meta_box_field($field, $label = 1) {
		$return = '<div class="cp_meta_box field_' . $field['type'] . '">';
		if ($label) {
			$return.= '<label for="' . $field['id'] . '">' . $field['name'];

			if (isset($field['attributes']['required']) && $field['attributes']['required'])
				$return.= ' *';

			$return.= '</label>';
		}
		
		if (isset($field['prefix']) && $field['prefix'])
			$return.= '<div class="prefix">' . $field['prefix'] . '</div>';
		
		$return.= $field['text'];
		
		if (isset($field['suffix']) && $field['suffix'])
			$return.= '<div class="suffix">' . $field['suffix'] . '</div>';
		
		if (isset($field['description']) && $field['description'])
			$return.= '<div class="description">' . $field['description'] . '</div>';
		
		$return.= '</div>';
		
		return $return;
	}
	
	private function meta_box_attributes($attributes) {
		$styles = '';
		$return = '';
		
		if (is_array($attributes)) {
			foreach ($attributes AS $a_key => $attribute) {
				switch($a_key) {
					case 'width':
					case 'height':
						$styles.= $a_key . ': ' . $attribute . '; ';
						break;
					case 'disabled':
					case 'readonly':
					case 'required':
					case 'autofocus':
						if ($attribute)
							$return.= ' ' . $a_key . '="' . $a_key . '"';
						break;
					case 'size':
					case 'maxlength':
					case 'rows':
					case 'cols':
					case 'pattern':
					case 'placeholder':
					case 'min':
					case 'max':
					case 'step':
					case 'autocomplete':
						if ($attribute !== false)
						$return.= ' ' . $a_key . '="' . $attribute . '"';
						break;
				}
			}
		}
		
		if ($styles)
			$return.= ' style="'.$styles.'"';
		
		return $return;
	}

	/**
	 * Save meta boxes
	 *
	 * @access type public
	 * @return type none save the boxes
	 * @author Piotr Soluch
	 */
	public function save_meta_boxes($post_id, $post) {

		// if custom post type has fields
		if (is_array($this->mb)) {

			// for each field
			foreach ($this->mb as $meta_box) {

				// for the post type beeing saved
				if ($post->post_type == $meta_box['settings']['post_type']) {

					// Save all fields in meta box group
					$this->save_meta_box_fields($meta_box['fields']);
				}
			}
		}
	}

	/**
	 * Save meta box fields
	 *
	 * @access type public
	 * @return type none save the fields
	 * @author Piotr Soluch
	 */
	public function save_meta_box_fields($fields) {
		global $post;
		global $post_id;
		
		// for new posts
		if ($post === null)
			return;
		
		// get post type from post object
		$post_type = get_post_type_object($post->post_type);

		// Verify the nonce before proceeding.
		if (!isset($_POST['add_meta_box_nonce']) || !wp_verify_nonce($_POST['add_meta_box_nonce'], basename(__FILE__)))
			return;

		// Check if the current user has permission to edit the post.
		if (!current_user_can($post_type->cap->edit_post, $post_id))
			return;

		// for each field in a box
		foreach ($fields as $field) {

			// Get the meta key.
			$meta_key = $field['id'];

			// Get the posted data
			$new_meta_value = ( isset($_POST[$meta_key]) ? $_POST[$meta_key] : '' );

			// Get the meta value of the custom field key.
			$meta_value = get_post_meta($post_id, $meta_key, true);

			//can't save during autosave, otherwise it saves blank values (there's a problem that meta box values are not send with POST during autosave. Probably fixable
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return;

			// If a new meta value was added and there was no previous value, add it.
			if ($new_meta_value && '' == $meta_value)
				add_post_meta($post_id, $meta_key, $new_meta_value, true);

			// If the new meta value does not match the old value, update it.
			elseif ($new_meta_value && $new_meta_value != $meta_value)
				update_post_meta($post_id, $meta_key, $new_meta_value);

			// If there is no new meta value but an old value exists, delete it.
			elseif ('' == $new_meta_value && $meta_value)
				delete_post_meta($post_id, $meta_key, $meta_value);
		}
	}
}

?>