<?php

/**
 * Standard post type extension
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
class cp_spt {
	
	private $spts;
	
	public function __construct($spts) {
		
		// set standard post types
		$this->_set_spts($spts);
		
		$this->extend_post_types();
	}
	
	private function extend_post_types() {
		if (is_admin()) {
			
			if (is_array($this->spts)) {

				include_once CP_LIB_PATH . '/core/class-meta-box.php';

				foreach ($this->spts as $spt) {
					$meta_box = new cp_meta_box($spt);
				}
			}
		}
	}
	
	private function _set_spts($spts) {
		$this->spts = $spts;
	}
	
	private function _get_spts() {
		return $this->spts;
	}
	
}