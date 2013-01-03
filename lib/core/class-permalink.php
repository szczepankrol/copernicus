<?php

/**
 * Copernicus permalinks class file
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */

/**
 * permalinks class
 *
 * @package Copernicus
 * @subpackage Copernicus Theme
 * @author Piotr Soluch
 */
class CP_Permalink {
	
	var $permalinks = array();
	
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

		add_action('init', array($this, 'add_rewrite_rules'));
	}
	
	function add_rewrite_rules() {
		//add_rewrite_tag('%bcat%', '([a-z0-9_]+)','');
		
		add_rewrite_rule(
			'teaching/open-student-projects/([^/]+)/?$',
			'index.php?student-project=$matches[1]',
			'top'
		);
		
		add_rewrite_rule(
			'research/completed-projects/([^/]+)/?$',
			'index.php?project=$matches[1]',
			'top'
		);
		
		add_rewrite_rule(
			'people/former-members/([^/]+)/?$',
			'index.php?member=$matches[1]',
			'top'
		);
	}
}

?>