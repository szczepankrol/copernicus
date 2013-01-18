<?php

/**
 * Copernicus Custom Post Type class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * Custom Post Type class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Cpt {

	// part of config with all cpts
	private $cpt = array();

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
	 * Initiate the theme
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function _init() {

		// get config
		$config = CP::get_config();

		if (isset($config['cpt'])) {
			$this->cpt = $config['cpt'];

			// create custom post type
			add_action('init', array($this, 'create_post_types'));
		}
	}

	/**
	 * Take the cpts from config and create cpt
	 *
	 * @access type public
	 * @return type null doesn't return a value
	 * @author Piotr Soluch
	 */
	public function create_post_types() {

		// if there are cpts
		if (is_array($this->cpt)) {

			// for each cpt
			foreach ($this->cpt AS $cpt) {

				// if cpt is active
				if ($cpt['settings']['active'])

				// create cpt
					$this->create_post_type($cpt);
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
	private function create_post_type($cpt) {

		// create an array for supported elements
		$supports = array();

		// force to always include title
		$cpt['support']['title'] = true;
		
		// create a list of supported fields
		foreach ($cpt['support'] as $key => $value) {
			if ($value)
				$supports[] = $key;
		}

		// merge default and custom settings
		$settings = $cpt['settings'];
		$settings['supports'] = $supports;
		$settings['labels'] = $cpt['labels'];

		// register cpt
		register_post_type(
			$cpt['settings']['name'], $settings
		);
	}
	
	/**
	 * 
	 * @param type $post_type
	 * @return int
	 */
	public function get_parent_page($post_type) {
		
		foreach ($this->cpt as $cpt) {
			if ($cpt['settings']['name'] == $post_type) {
				if (isset($cpt['settings']['parent_page'])) {
					return $cpt['settings']['parent_page'];
				}
			}
		}
		
		return 0;
	}
	
	public function get_post_types() {
		$post_types = array();
		
		foreach ($this->cpt AS $cpt) {
			if ($cpt['settings']['active']) {
				$post_types[] = $cpt['settings']['name'];
			}
		}
		
		return $post_types;
	}
	
}

?>