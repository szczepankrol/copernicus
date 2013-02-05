<?php

/**
 * My Story user meta box
 *
 * @package My Story
 * @subpackage My Story Theme
 * @author Piotr Soluch
 */

/**
 * user meta box class
 *
 * @package My Story
 * @subpackage My Story Theme
 * @author Piotr Soluch
 */
class CP_Umb {
	
	var $umb = array();
	
	/**
	 * Class constructor
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function __construct() {

		// initialize the custom post types
		$this->_init();
	}

	/**
	 * Initiate taxonomies
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function _init() {
		// get config
		$config = CP::get_config();
		
		if (isset ($config['umb'])) {
			$this->umb = $config['umb'];
			
			// create taxonomies
			add_action('edit_user_profile', array($this, 'add_user_meta_boxes'));
			//add_action('show_user_profile', array($this, 'add_user_meta_boxes'));
		}
	}
	
	/**
	 * Start adding user meta boxes
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function add_user_meta_boxes() {
		
		echo 'asdasd';
		// if there are meta boxes
		if (is_array($this->umb)) {

			// for each meta box
			foreach ($this->umb AS $umb) {

				// if meta box is active
				if ($umb['settings']['active'])

				// create meta box groups
					$this->add_user_meta_box_group($umb);
			}
		}
	}
	
	/**
	 * Add user meta box group
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @param $umb array all details of user meta box
	 * @author Piotr Soluch
	 */
	function add_user_meta_box_group($umb) {
	}
	
}