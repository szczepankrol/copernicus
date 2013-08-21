<?php

/**
 * Copernicus language class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * language class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Language {

	// all meta boxes
	private $language = array();
	private $current_language = array();

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

		if (isset($_GET['lang'])) {
			$this->change_language($_GET['lang']);
		}
		
		if (isset (CP::$config['language'])) {
			
			// get meta box configuration
			$this->language = CP::$config['language'];
		}
		
		$this->set_current_language();
	}
	
	public function set_current_language() {
		if (isset($_SESSION['language'])) {
			$current_language = $this->get_language($_SESSION['language']);
		}
		else if (isset ($_COOKIE['language'])) {
			$current_language = $this->get_language($_COOKIE['language']);
		}
		else {
			$current_language = $this->get_language();
		}
		$_SESSION['language'] = $current_language['code'];
		$expire = 60 * 60 * 24 * 31; // a month
		setcookie('language', $current_language['code'], time() + $expire);
		define('LANGUAGE', $current_language['code']);
		define('LANGUAGE_SUFFIX', $current_language['postmeta_suffix']);
	}
	
	public function get_language($code = '') {
		foreach ($this->language as $language) {
			if ($code) {
				if ($code == $language['code']) {
					return $language;
				}
			}
			else {
				if ($language['default']) {
					return $language;
				}
			}
		}
	}

	public function get_languages($status = 1) {
		$languages = $this->language;
		
		if ($status != 'all') {
			
			foreach ($languages as $key => $language) {
				if ($language['status'] != $status) {
					unset($languages[$key]);
				}
			}
		}
		return $languages;
	}
	
	private function change_language($lang) {
		$_SESSION['language'] = $lang;
		$home = get_home_url();
		
		wp_redirect($_SERVER['HTTP_REFERER']);
		
//		wp_redirect($home);
		exit;
	}
}