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
class CP_Loop {

	// all meta boxes
	private $loop = array();

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
		
		if (isset ($config['loop'])) {
			
			// get meta box configuration
			$this->loop = $config['loop'];
		}
	}

	public function get_loop($name) {
		foreach ($this->loop as $loop) {
			if ($loop['name'] == $name)
				return $loop;
		}
		
		return null;
	}

}

?>