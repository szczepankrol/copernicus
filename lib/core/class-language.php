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

		if ( isset( $_GET['lang'] ) ) {
			$this->change_language( $_GET['lang'] );
		}

		// get current language
		$current_language = $this->get_current_language();

		$this->define_current_language( $current_language );
	}

	private function define_current_language( $current_language ) {
		$this->current_language = $current_language;
		define( 'LANGUAGE', $current_language['code'] );
		define( 'LANGUAGE_SUFFIX', $current_language['postmeta_suffix'] );
	}

	public function get_languages( $status = 1 ) {
		$languages = CP::$config['language'];

		if ( $status != 'all' ) {

			foreach ( $languages as $key => $language ) {
				if ( $language['status'] != $status ) {
					unset( $languages[$key] );
				}
			}
		}

		return $languages;
	}

	public function get_language( $code = '' ) {
		foreach ( CP::$config['language'] as $language ) {
			if ( $code ) {
				if ( $code == $language['code'] ) {
					return $language;
				}
			}
		}

		return null;
	}

	public function get_current_language() {
		if ( isset( $_SESSION['language'] ) ) {
			return $this->get_language( $_SESSION['language'] );
		}

		if ( isset ( $_COOKIE['language'] ) ) {
			return $this->get_language( $_COOKIE['language'] );
		}

		return $this->get_default_language();
	}

	public function get_default_language() {
		if ( $this->current_language )
			return $this->current_language;

		foreach ( CP::$config['language'] as $language ) {
			if ( $language['default'] ) {
				return $language;
			}
		}

		return null;
	}

	/**
	 * change language and redirecto to a previous page
	 *
	 * @param string  $language language code
	 * @return none
	 */
	private function change_language( $language ) {
		$this->set_current_language( $language );

		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit;
	}

	private function set_current_language( $current_language, $remember = true ) {
		// set session for current language
		$_SESSION['language'] = $current_language['code'];

		if ( $remember ) {
			$expire = 60 * 60 * 24 * 31; // a month

			// set cookie for current language
			setcookie( 'language', $current_language['code'], time() + $expire );
		}
	}
}
