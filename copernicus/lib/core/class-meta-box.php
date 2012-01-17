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
		if (is_array($this->pt['custom_fields'])) {

			foreach ($this->pt['custom_fields'] AS $group) {
				// add meta box (group)
				add_meta_box($group['id'], $group['name'], array($this, 'add_meta_box'), $this->pt['settings']['name'], $group['context'], $group['priority'], array('fields' => $group['fields']));

			}
			
			// if post type has templates
			if (is_array($this->pt['additional_attributes'])) {
				
				if (in_array(true, $this->pt['additional_attributes'])) {
					
					if ($this->pt['additional_attributes']['templates'] && is_array($this->pt['templates'])) {
						
						$templates = array();

						foreach ($this->pt['templates'] as $template) {
							if ($template['active'])
								$templates[$template['id']] = $template['name'];
						}
						$fields[] = array(
							'id' => '_wp_page_template',
							'name' => 'Template',
							'field_type' => 'selectbox',
							'values' => $templates,
							'multiple' => false
						);
					}
					
					if ($this->pt['additional_attributes']['in_navigation']) {
						
						$fields[] = array(
							'id' => 'in_navigation',
							'name' => 'In navigation',
							'field_type' => 'selectbox',
							'values' => array(
								1 => 'yes',
								2 => 'no'
							)
						);
					}

					// add meta box with templates
					add_meta_box('templates', 'Additional Attributes', array($this, 'add_meta_box'), $this->pt['settings']['name'], 'side', 'low', array('fields' => $fields));
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
						if (!isset ($field['size'])) $field['size'] = 30;
						echo '<p class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
						echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" size="' . $field['size'] . '" value="' . $value . '" />';
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
						if (is_array($field['values'])) {
							foreach ($field['values'] AS $field_key => $field_value) {
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
						if ($field['multiple']) {
							if ($value)
								$values = maybe_unserialize($value);
							else
								$values = array();
						}
						echo '<div class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
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

					// radio 
					case 'radio':
						echo '<div class="cp_meta_box">';
						echo '<span class="title">' . $field['name'] . ':</span>';
						echo '<ul>';
						if (is_array($field['values'])) {
							foreach ($field['values'] AS $field_key => $field_value) {
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
						
					// date field
					case 'date':
						wp_register_style('jquery-ui', CP_COPERNICUS_STATIC_DIR . '/jquery-ui/jquery-ui-1.8.17.custom.css', '', '', 'all');
						wp_enqueue_style('jquery-ui');
						wp_enqueue_script('jquery');
						wp_enqueue_script('jquery-ui-core');
						wp_enqueue_script('jquery-ui-datepicker');

						echo '<p class="cp_meta_box">';
						echo '<label for="' . $field['id'] . '" class="title">' . $field['name'] . ':</label>';
						echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '" /> ';
						echo '</p>';
						echo '<script type="text/javascript">
							jQuery(function($){
								$(\'#' . $field['id'] .'\').datepicker({';
						if (isset ($field['options']) && is_array($field['options'])) {
							foreach ($field['options'] as $key => $option) {
								echo "'".$key."' : '".$option."',";
							}
						}
						echo '	});
							});
						</script>';
						break;
						
					case 'media':
						wp_enqueue_script('media-upload');
						wp_enqueue_script('thickbox');
						wp_enqueue_style('thickbox');
						echo '<div class="cp_meta_box">';
						echo '<span class="title">' . $field['name'] . ':</span>';
						echo '<div class="other_column">';
						echo '<div id="media_'.$field['id'].'">';
						
						$values = maybe_unserialize($value);
						if (is_array($values)) {
							foreach ($values as $key => $value) {
								echo '<div id="'.$key.'">';
								echo $value.'<input type="hidden" name="'.$field['id'].'[]" value="'.$value.'" />';
								echo ' <a href="javascript:remove_element('.$key.')">remove</a>';
								echo '</div>';
							}
						}
						
						echo '</div>';
						echo '<a href="http://local.laughinglemon.ch/wp-admin/media-upload.php?type=image&amp;TB_iframe=1&amp;width=640&amp;height=589" class="thickbox add_media" id="media-' . $field['id'] . '" title="Add Media" onclick="return false;">Upload/Insert <img src="http://local.laughinglemon.ch/wp-admin/images/media-button.png?ver=20111005" width="15" height="15"></a>';
						echo '</div>';
						echo '</div>';
						echo '<script type="text/javascript">
							function remove_element(id) {
								jQuery("#"+id).remove();
							}
							jQuery(document).ready(function() {
							
								new_editor = 0;
								window.send_to_editor_original = window.send_to_editor;
								
								jQuery(\'#media-' . $field['id'] . '\').click(function() {
									new_editor = 1;
									tb_show(\'\', \'media-upload.php?&cmu='.$field['id'].'&type=image&TB_iframe=true\');
									return false;
								});

								
								
								window.send_to_editor = function(html) {
									if (new_editor == 1) {
										alert(\'wwww\');
										tb_remove();
										new_editor = 0;
									}
									else {
										window.send_to_editor_original(html);
									}
								}

								
							});
						</script>';
						break;

				}
			}
		}
	}
	
	function test(){
		echo 'testasdasda';
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
		
		// if post type has templates
		if (is_array($this->pt['additional_attributes'])) {

			if ($this->pt['additional_attributes']['templates'])
				$fields[1] = array('id' => '_wp_page_template');
			if ($this->pt['additional_attributes']['in_navigation'])
				$fields[2] = array('id' => 'in_navigation');

			if (is_array($fields))
				$this->save_meta_box($fields);
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
						$column_text = '';
						if ($cp_column['id'] == $column) {
							$custom = get_post_custom();
							$results = maybe_unserialize($custom[$cp_column['id']][0]);
							if (is_array($results)) {
								foreach ($results as $key => $result) {
									foreach ($this->pt['custom_fields'] AS $fields) {
										foreach ($fields['fields'] as $field) {
											if ($field['id'] == $cp_column['id']) {
												echo $field['values'][$result];
											}
										}
									}
									//echo $result;
									if ($key < count($results) - 1) {
										echo ', ';
									}
								}
							}
							else {
								foreach ($this->pt['custom_fields'] AS $fields) {
									foreach ($fields['fields'] as $field) {
										if ($field['id'] == $cp_column['id']) {
											if ($field['values']) {
												if ($results!=0)
													$column_text = $field['values'][$results];
											}
											else {
												$column_text = $results;
											}
										}
									}
								}
								if ($column_text==0)
									$column_text = '';
								else if (!$column_text)
									$column_text = $results;
							}
						}
						echo $column_text;
						break;
				}
			}
		}
	}

	public function set_admin_order($wp_query) {
		$orderby = 'menu_order';
		$order = 'ASC';
		
		if (isset ($this->pt['settings']['orderby']))
			$orderby = $this->pt['settings']['orderby'];
		if (isset ($this->pt['settings']['order']))
			$order = $this->pt['settings']['order'];

		if (isset ($this->pt['settings']['custom_orderby']))
			$custom_orderby = $this->pt['settings']['custom_orderby'];

		if (isset($_GET['orderby']))
			$orderby = $_GET['orderby'];
		if (isset($_GET['order']))
			$order = $_GET['order'];

		// Get the post type from the query  
		$post_type = $wp_query->query['post_type'];

		if ($post_type == $this->pt['settings']['name']) {

			$standard_fields = array('title');
			
			if ($order) {
				$orderby = str_replace('custom', 'meta_value', $orderby);
				$wp_query->set('orderby', $orderby);
			} 
			if (isset ($custom_orderby)) {
				$wp_query->set('meta_key', $custom_orderby);
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