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
class CP_Menu {

	// all meta boxes
	private $menu = array();

	/**
	 * Class constructor
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function __construct() {
		
		// initialize the meta boxes
		register_nav_menu( 'primary', 'Primary Menu' );
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

		if (isset (CP::$config['menu'])) {
			
			// get meta box configuration
			$this->menu = CP::$config['menu'];
		}
	}

	public function get_menu($menu_id) {
		register_nav_menu( 'primary', 'Primary Menu' );
		foreach ($this->menu as $menu) {
			if ($menu['id'] == $menu_id)
				return $menu;
		}
		
		return null;
	}

}