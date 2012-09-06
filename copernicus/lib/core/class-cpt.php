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
}

?>