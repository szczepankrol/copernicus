<?php

/**
 * Copernicus translation class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * translation class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Translation {

	// all meta boxes
	private $translation = array();

	/**
	 * Class constructor
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function __construct() {
		
		// initialize the meta boxes
		//$this->_init();
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

		}
	}
}

?>