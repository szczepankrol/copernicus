<?php

/**
 * Copernicus taxonomy class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * taxonomy class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Taxonomy {
	
	var $taxonomy = array();
	
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
		
		if (isset ($config['taxonomy'])) {
			$this->taxonomy = $config['taxonomy'];
			
			// create taxonomies
			add_action('after_setup_theme', array($this, 'create_taxonomies'));
		}
	}
	
	/**
	 * Start adding taxonomies
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	function create_taxonomies() {
		
		// if there are taxonomies
		if (is_array($this->taxonomy)) {

			// for each taxonomy
			foreach($this->taxonomy AS $taxonomy) {
				
				// if taxonomy is active
				if ($taxonomy['settings']['active'])

					// create meta box groups
					$this->add_taxonomy($taxonomy);
			}
		}
	}
	
	/**
	 * Add taxonomy
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	function add_taxonomy($taxonomy) {

		$args['labels'] = $taxonomy['labels'];
		
		$args = array_merge($args, $taxonomy['args']);
		
		// do the registration
		register_taxonomy(
			$taxonomy['settings']['id'],
			$taxonomy['settings']['post_type'],
			$args
		);
	}
}