<?php

/**
 * Copernicus shortcodes class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * shortcodes class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Sc {

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
	 * Initiate the shortcodes
	 *
	 * @access type public
	 * @return type mixed returns possible errors
	 * @author Piotr Soluch
	 */
	public function _init() {
		
		add_shortcode( 'loop', array($this,'sh_loop') );
		
	}
	
	public function sh_loop($atts) {
		global $CP_Loop;
		
		$loop = $CP_Loop->get_loop($atts['name']);
		
		foreach ($atts as $key => $att) {
			if (preg_match('/args_[a-z_]+/', $key, $matches)) {
				$key = str_replace('args_', '', $matches[0]);
				$loop['args'][$key] = $att;
			}
			else
				$loop[$key] = $att;
		}
		
		return $CP_Loop->show_loop($loop);
	}
	
	
}

?>