<?php

/**
 * Copernicus Admin List View class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * Admin List View class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Alv {

	// part of config with all alvs
	private $alv = array();
	private $mb = array();
	
	private $alv_fields = array();
	private $mb_fields = array();

	/**
	 * Class constructor
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function __construct() {

		if (is_admin()) 
			
			// initialize the custom post types
			$this->_init();
	}

	/**
	 * Initiate the theme
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function _init() {

		// get config
		$config = CP::get_config();

		if (isset($config['alv'])) {
			$this->alv = $config['alv'];
			
			if (isset ($config['mb'])) {
			
				// get meta box configuration
				$this->mb = $config['mb'];
			}
			
			// Modify the title bars
			$this->modify_list_views();
			
			add_filter('pre_get_posts', array($this, 'set_list_views_order'));
			
			add_action('manage_posts_custom_column', array($this, 'custom_columns'), 10, 2);
		}
	}

	/**
	 * Take the alvs from config and create alv
	 *
	 * @access type public
	 * @return type null doesn't return a value
	 * @author Piotr Soluch
	 */
	public function modify_list_views() {

		// if there are alvs
		if (is_array($this->alv)) {

			// for each alv
			foreach ($this->alv AS $alv) {

				// if alv is active
				if ($alv['settings']['active']) {
					
					if ($_GET['post_type'] == $alv['settings']['post_type']) {
						
						$this->alv_fields = $alv['fields'];
						$this->get_mb_fields($alv['settings']['post_type']);

						// create alv
						add_filter('manage_edit-' . $alv['settings']['post_type'] . '_columns', array($this, 'modify_list_view'));
					}
				}
			}
		}
	}
	
	/**
	 * Create a custom post type
	 *
	 * @access type public
	 * @return type null doesn't return a value
	 * @author Piotr Soluch
	 */
	public function modify_list_view($columns) {
		$new_columns = array();
		
		new dBug($columns);
		
		if (isset($columns['cb']))
			$new_columns['cb'] = $columns['cb'];
		
		foreach ($this->alv_fields as $key => $field) {
			switch($field) {
				case 'title':
				case 'date':
				case 'author':
					$field_name = ucfirst($field);
					break;
				case 'ID':
					$field_name = $field;
					break;
				default:
					$field_name = $this->mb_fields[$field]['name'];
					break;
			}
			$new_columns[$field] = $field_name;
			
		}
		
		return $new_columns;
	}
	
	private function get_mb_fields($post_type) {
		
		$fields = array();
		
		foreach ($this->mb as $mb) {
			
			foreach ($mb['fields'] as $field) {
				$fields[$field['id']] = $field;
			}
			
		}
		$this->mb_fields = $fields;
	}
	
	public function set_list_views_order($wp_query) {

		// Get the post type from the query  
		$post_type = $wp_query->query['post_type'];

		// if there are alvs
		if (is_array($this->alv)) {

			// for each alv
			foreach ($this->alv AS $alv) {

				// if alv is active
				if ($alv['settings']['post_type'] == $post_type) {
					
					if (isset($alv['settings']['orderby'])) {
						
						$orderby = $alv['settings']['orderby'];

						switch($orderby) {
							case 'none':
							case 'ID':
							case 'author':
							case 'title':
							case 'name':
							case 'date':
							case 'modified':
							case 'parent':
							case 'rand':
							case 'comment_count':
							case 'menu_order':
								$wp_query->set('orderby', $orderby);
								break;
							default:
								$wp_query->set('orderby', 'meta_value');
								$wp_query->set('meta_key', $orderby);
								break;
						}
					}

					if (isset($alv['settings']['order']))
						$wp_query->set('order', $alv['settings']['order']);
				}
			}
		}
	}

	public function custom_columns($column, $post_id) {
		global $CP_Mb;
		
		switch($column) {
			case 'ID':
				echo $post_id;
				break;
			default:
				$value = get_post_meta($post_id, $column, 1);
				$field = $this->mb_fields[$column];
				echo $CP_Mb->get_value($field, $value);
				break;
		}
		
	}

}

?>