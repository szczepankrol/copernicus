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
		$values = get_post_custom($post->ID);
		$fields = $meta_box['args']['fields'];

		wp_nonce_field(basename(__FILE__), 'add_meta_box_nonce');

		// for each field in a box
		foreach ($fields as $field) {
			$field['label'] = 1;
			
			echo $this->meta_box_field($field, $values);
		}
	}
	
	/**
	 * 
	 *
	 * @access type public
	 * @return type 
	 * @author Piotr Soluch
	 */
	private function meta_box_field($field, $values) {
		global $CP_Language;
		
		$return = '<div class="cp_meta_box field_' . $field['type'] . '">';
		if ($field['label']) {
			$return.= '<label';
			
			if (isset($field['title']) && $field['title']) {
				$return.= ' for="main-post-title"';
			}
			else {
				$return.= ' for='.$field['id'] .'"';
			}
			$return.= '>' . $field['name'];
				
			if (isset($field['attributes']['required']) && $field['attributes']['required'])
				$return.= ' *';

			$return.= '</label>';
		}
		
		$languages = $CP_Language->get_languages();
		
		$return.= '<div class="langs" id="langs_'.$field['id'].'">';
		if (isset($field['translation']) && $field['translation']) {
			
			if (!isset($_COOKIE['langs_'.$field['id']])) {
				$active = $languages[0]['code'];
			}
			else {
				$active = $_COOKIE['langs_'.$field['id']];
			}
			
			foreach ($languages as $language) {
				$return.= '<span id="_'.$field['id'].'_'.$language['code'].'" class="option';
				if ($active == $language['code'])
					$return.= ' active';
				$return.= '">'.$language['name'].'</span>';
			}
			
			$return.= '<div class="langs_list">';
			
			foreach ($languages as $language) {
				
				$return.= '<div id="div_'.$field['id'].'_'.$language['code'].'"';
				if ($active == $language['code'])
					$return.= ' class="active"';
				$return.= '>';
				$text = $this->show_field($field, $values, $language);
				$return.= $this->meta_box_field_content($field, $text);
				$return.= '</div>';
			}
			$return.= '</div>';
		}
		else {
			$text = $this->show_field($field, $values);
			$return.= $this->meta_box_field_content($field, $text);
		}
		$return.= '</div>';
		
		$return.= '</div>';
		
		return $return;
	}
	
	/**
	 * 
	 *
	 * @access type public
	 * @return type 
	 * @author Piotr Soluch
	 */
	private function show_field($field, $values, $language = array()) {
		
		if (isset($language['postmeta_suffix'])) {
			$suffix = $language['postmeta_suffix'];
		}
		else {
			$suffix = '';
		}
		
		if (!isset($language['default'])) {
			$language['default'] = 1;
		}
		
		$value = '';

		// get if the value is saved
		if (isset($values[$field['id'] . $suffix][0]))
			$value = $values[$field['id'] . $suffix][0];

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
			case 'url':

				if (!isset($field['attributes']['size']))
					$field['attributes']['size'] = 30;

				$field['text'] =  '<input type="' . $field['type'] . '" name="' . $field['id'] . $suffix . '" value="' . $value . '"';
				
				if (isset($field['title']) && $field['title'] && ($language['default']))
					$field['text'].= 'id="main-post-title"';
				else 
					$field['text'].= 'id='.$field['id'] . $suffix.'"';

				if (isset($field['attributes']))
					$field['text'].= $this->meta_box_attributes($field['attributes']);

				$field['text'].= '/>';

				break;

			case 'date':
				wp_register_style('jquery-ui', get_bloginfo ('template_directory') . '/static/jquery-ui/jquery-ui-1.8.17.custom.css', '', '', 'all');

				wp_enqueue_style('jquery-ui');
				wp_enqueue_script('jquery');
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-datepicker');

				$field['text'] =  '<input type="text" name="' . $field['id'] . $suffix . '" id="' . $field['id'] . $suffix . '" value="' . $value . '"';
				if (isset($field['attributes']))
					$field['text'].= $this->meta_box_attributes($field['attributes']);

				$field['text'].= '/>';

				$field['text'].= '<script type="text/javascript">
						jQuery(function($){
							$(\'#' . $field['id'] . $suffix .'\').datepicker({';
					if (isset ($field['options']) && is_array($field['options'])) {

						foreach ($field['options'] as $key => $option) {

							$field['text'].= "'".$key."' : '".$option."',";
						}
					}
					$field['text'].= '	});
						});
					</script>';


				break;

			// textarea field
			case 'textarea':
				if (!isset($field['attributes']['rows']))
					$field['attributes']['rows'] = 6;
				if (!isset($field['attributes']['cols']))
					$field['attributes']['cols'] = 60;

				$field['text'] = '<textarea name="' . $field['id'] . $suffix . '" id="' . $field['id'] . $suffix . '"';
				if (isset($field['attributes']))
					$field['text'].= $this->meta_box_attributes($field['attributes']);
				$field['text'].= '>' . $value . '</textarea>';

				break;

			// wysiwyg editor field
			case 'editor':
				ob_start();
				wp_editor($value, $field['id'] . $suffix , array());
				$field['text'] = ob_get_clean();

				break;

			// checkboxes
			case 'checkbox':
				if ($value)
					$values = maybe_unserialize($value);
				else
					$values = array();

				$field['text'] = '<span class="clm">' . $field['name'] . '</span>';
				$field['text'].= '<ul>';
				if (is_array($field['values'])) {
					foreach ($field['values'] AS $field_key => $field_value) {
						$field['text'].= '<li>';
						$field['text'].= '<input type="checkbox" name="' . $field['id'] . $suffix . '[]" id="' . $field['id'] . $suffix . '_' . $field_key . '" value="' . $field_key . '" ';
						if (in_array($field_key, $values))
							$field['text'].= 'checked="checked" ';

						if (!isset($field['attributes']))
							$field['attributes'] = array();
						
						$field['text'].= $this->meta_box_attributes($field['attributes']);
						$field['text'].= ' /> ';
						$field['text'].= '<label for="' . $field['id'] . $suffix . '_' . $field_key . '">' . $field_value . '</label>';
						$field['text'].= '</li>';
					}
				}
				$field['text'].= '</ul>';

				$field['label'] = 0;

				break;

			// radio 
			case 'radio':
				$field['text'] = '<span class="clm">' . $field['name'] . '</span>';
				$field['text'].= '<ul>';
				if (is_array($field['values'])) {
					foreach ($field['values'] AS $field_key => $field_value) {
						$field['text'].= '<li>';
						$field['text'].= '<input type="radio" name="' . $field['id'] . $suffix . '" id="' . $field['id'] . $suffix . '_' . $field_key . '" value="' . $field_key . '" ';
						if ($value == $field_key)
							$field['text'].= 'checked="checked" ';
						$field['text'].= $this->meta_box_attributes($field['attributes']);
						$field['text'].= '/> ';
						$field['text'].= '<label for="' . $field['id'] . $suffix . '_' . $field_key . '">' . $field_value . '</label>';
						$field['text'].= '</li>';
					}
				}
				$field['text'].= '</ul>';

				$field['label'] = 0;

				break;

			// selectbox
			case 'select':
				$field['text'] = '<select id="' . $field['id'] . $suffix . '" name="' . $field['id'] . $suffix . '"';
				$field['text'].= $this->meta_box_attributes($field['attributes']);
				$field['text'].= '>';
				if (is_array($field['values'])) {
					foreach ($field['values'] AS $field_key => $field_value) {
						$field['text'].= '<option value="' . $field_key . '" ';

						if ($value == $field_key)
							$field['text'].= 'selected="selected" ';
						$field['text'].= '> ';
						$field['text'].= $field_value;
						$field['text'].= '</option>';
					}
				}
				$field['text'].= '</select>';

				break;

			// multi select
			case 'multiselect':
				if ($value)
					$values = maybe_unserialize($value);
				else
					$values = array();

				if (!isset($field['size']))
					$field['size'] = 1;
				
				$field['text'] = '<select id="' . $field['id'] . $suffix . '" name="' . $field['id'] . $suffix .'[]" size="' . $field['size'] . '" multiple="multiple"';
				$field['text'].= $this->meta_box_attributes($field['attributes']);
				$field['text'].= '>';
				if (is_array($field['values'])) {
					foreach ($field['values'] AS $field_key => $field_value) {
						$field['text'].= '<option value="' . $field_key . '" ';

						if (in_array($field_key, $values))
							$field['text'].= 'selected="selected" ';
						$field['text'].= '> ';
						$field['text'].= $field_value;
						$field['text'].= '</option>';
					}
				}
				$field['text'].= '</select>';

				break;

			// post link
			case 'post_link':
				$field['text'] = '<select id="' . $field['id'] . $suffix . '" name="' . $field['id'] . $suffix .'"';
				$field['text'].= $this->meta_box_attributes($field['attributes']);
				$field['text'].= '>';
				$field['text'].= '<option value="0"> -- select -- </option>';

				if (!isset($field['arguments']['posts_per_page']))
					$field['arguments']['posts_per_page'] = '-1';

				$loop_links = new WP_Query( $field['arguments'] );

				$all_links = array();

				$posts = $loop_links->posts;


				foreach ($posts as $post) {
					if ($value == $post->ID)
						$post->selected = 1;
					$all_links[$post->post_parent][$post->ID] = $post;
				}

				$field['text'].= $this->get_links($all_links);
				$field['text'].= '</select>';
				break;

			case 'file':
				
				// This function loads in the required media files for the media manager.
				//wp_enqueue_media();
				
				if ($field['multiple']) {
					$go_function = 'media_upload_multiple';
					switch($field['filetype']) {
						case 'image':
							$button = 'Add Images';
							$button_window = 'Add Images';
							$title_window = 'Upload or Choose Images';
						break;
						case 'file':
							$button = 'Add Files';
							$button_window = 'Add Files';
							$title_window = 'Upload or Choose Files';
						break;
						case 'video':
							$button = 'Add Videos';
							$button_window = 'Add Videos';
							$title_window = 'Upload or Choose Videos';
						break;
					}
				}
				else {
					$go_function = 'media_upload_single';
					switch($field['filetype']) {
						case 'image':
							$button = 'Add an Image';
							$button_window = 'Add an Image';
							$title_window = 'Upload or Choose an Image';
						break;
						case 'file':
							$button = 'Add a File';
							$button_window = 'Add a File';
							$title_window = 'Upload or Choose a File';
						break;
						case 'video':
							$button = 'Add a Video';
							$button_window = 'Add a Video';
							$title_window = 'Upload or Choose a Video';
						break;
					}
				}
				
				if ($value) {
					$values = maybe_unserialize($value);
				}
				else {
					$values = array();
				}
				
				$disabled = '';
				
				if (!$field['multiple'] && count($values) == 1) {
					$disabled = ' disabled="disabled"';
				}
				
				$field['text'] = '<div id="container_'.$field['id'].'" class="cp-files">';
				$field['text'].= '<a href="javascript:'.$go_function.'(\''.$field['filetype'].'\',\''.$field['id'].'\',\''.$title_window.'\', \''.$button_window.'\');" '.$disabled.' class="cp-open-media button button-primary" id="button_'.$field['id'].'" title="' . esc_attr__( 'Add Images', 'tgm-nmp' ) . '">' . __( $button, 'tgm-nmp' ) . '</a>';
				
				
				foreach ($values as $attachment) {
					
					$attachment_data = get_post($attachment);
					$im = wp_get_attachment_metadata($attachment, 1);
					
					$field['text'].= '<div id="file-'.$attachment.'">';
					
					if ($field['filetype'] == 'image') {
						$field['text'].= '<img src="'.$attachment_data->guid.'" width="100">';
					}
					else {
						$image = wp_get_attachment_image_src( $attachment, 100, true);
						if ($image[1] > 100) {
							$image[1] = 100;
						}
						$field['text'].= '<img src="'.$image[0].'" width="'.$image[1].'" >';
						$field['text'].= '<span>'.basename($attachment_data->guid).'</span>';
					}
					
					$field['text'].= '<input type="hidden" name="'.$field['id'].'[]" value="'.$attachment.'">';
					$field['text'].= '<a href="javascript:remove_image(\''.$attachment.'\');" class="cp-remove button">Remove</a>';
					$field['text'].= '</div>';
				}
				
				$field['text'].= '</div>';
				break;
		}
		
		return $field['text'];
	}
	
	/**
	 * 
	 *
	 * @access type public
	 * @return type 
	 * @author Piotr Soluch
	 */
	public function meta_box_field_content($field, $text) {
		$return = '';
		
		if (isset($field['prefix']) && $field['prefix'])
			$return.= '<div class="prefix">' . $field['prefix'] . '</div>';
		
		$return.= $text;
		
		if (isset($field['suffix']) && $field['suffix'])
			$return.= '<div class="suffix">' . $field['suffix'] . '</div>';
		
		if (isset($field['description']) && $field['description'])
			$return.= '<div class="description">' . $field['description'] . '</div>';
		
		return $return;
	}
	
	/**
	 * 
	 *
	 * @access type public
	 * @return type 
	 * @author Piotr Soluch
	 */
	private function get_links($links, $parent_id = 0, $indent = 0) {
		$return = '';

		if (isset($links[$parent_id])) {
			foreach ($links[$parent_id] as $link) {
				$return.= '<option value="' . $link->ID . '"';
				if (isset($link->selected))
					$return.= ' selected="selected" ';
				$return.= '>';
				for ($i=0; $i<$indent; $i++) {
					$return.= '---';
				}
				if ($indent)
					$return.= ' ';
				$return.= $link->post_title;
				$return.= '</option>';
				$return.= $this->get_links($links, $link->ID, $indent+1);
			}
		}
		return $return;
	}
	
	/**
	 * 
	 *
	 * @access type public
	 * @return type 
	 * @author Piotr Soluch
	 */
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
						if ($attribute !== false && $attribute !='')
						$return.= ' ' . $a_key . '="' . $attribute . '"';
						break;
				}
			}
		}
		
		if ($styles)
			$return.= ' style="'.$styles.'"';
		
		return $return;
	}

// -------------------- SAVING --------------------	
	
	/**
	 * 
	 *
	 * @access type public
	 * @return type 
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
		global $post, $post_id, $CP_Language;
		
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
		
		$languages = $CP_Language->get_languages();

		// for each field in a box
		foreach ($fields as $field) {

			// Get the meta key.
			$meta_key = $field['id'];
			
			//can't save during autosave, otherwise it saves blank values (there's a problem that meta box values are not send with POST during autosave. Probably fixable
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}
			else if (isset($field['translation']) && $field['translation']) {
				
				foreach ($languages as $language) {

					$this->save_meta_box_field($field['id'].$language['postmeta_suffix'], $post_id);
				}
			}
			else {
				$this->save_meta_box_field($meta_key, $post_id);
			}
		}
	}
	
	/**
	 * 
	 * @param type $meta_key
	 * @param type $post_id
	 */
	public function save_meta_box_field($meta_key, $post_id) {
		
		// Get the posted data
		$new_meta_value = ( isset($_POST[$meta_key]) ? $_POST[$meta_key] : '' );

		// Get the meta value of the custom field key.
		$meta_value = get_post_meta($post_id, $meta_key, true);
		
		// If a new meta value was added and there was no previous value, add it.
		if ($new_meta_value && $meta_value == '')
			add_post_meta($post_id, $meta_key, $new_meta_value, true);

		// If the new meta value does not match the old value, update it.
		else if ($new_meta_value && $new_meta_value != $meta_value)
			update_post_meta($post_id, $meta_key, $new_meta_value);

		// If there is no new meta value but an old value exists, delete it.
		elseif (!$new_meta_value && $meta_value)
			delete_post_meta($post_id, $meta_key, $meta_value);
	}
	
// -------------------- OTHER --------------------
	
	/**
	 * 
	 *
	 * @access type public
	 * @return type 
	 * @author Piotr Soluch
	 */
	public function get_value($field, $value) {
		
		switch($field['type']) {
			case 'select':
				return $field['values'][$value];
				break;
			case 'post_link':
				$post_link = get_post( $value, ARRAY_A );
				return '<a href="'.get_permalink($post_link['ID']).'" target="_blank">'.$post_link['post_title'].'</a>';
				break;
			default:
				return $value;
				break;
		}
		
	}
	
	public function get_meta_box_fields() {
		$fields = array();
		
		//new dBug($this->mb);
		
		foreach ($this->mb AS $mb) {
			if ($mb['settings']['active']) {
				if (!isset($fields[$mb['settings']['post_type']])) {
					$fields[$mb['settings']['post_type']] = array();
				}
				
				foreach ($mb['fields'] AS $field) {
					
					$fieldName = $field['id'];
					if (isset($field['translation']) && $field['translation']) {
						$fieldName = $field['id'].LANGUAGE_SUFFIX;
					}
					$fields[$mb['settings']['post_type']][] = $fieldName;
				}
			}
		}
		
		return $fields;
	}
}

?>