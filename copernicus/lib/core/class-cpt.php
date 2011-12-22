<?php

/**
 * Create custom post types
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
class cp_cpt {

	private $cpts; // custom post types from config

	public function __construct($cpt) {
		
		$this->_set_cpts($cpt);
		
		$this->create_post_types();
		$this->extend_post_types();
	}
	
	public function create_post_types() {
		
		if (is_array($this->cpts)) {
			
			foreach($this->cpts AS $cpt) {
				$this->create_post_type($cpt);
			}
		}
	}
	
	public function create_post_type($cpt) {

		// create an array of supported elements
		$supports = array();
		if (is_array($cpt['support'])) {
			foreach ($cpt['support'] as $key => $value) {
				if ($value)
					$supports[] = $key;
			}
		}

		register_post_type(
				$cpt['settings']['name'], array(
					'labels' => $cpt['labels'],
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'capability_type' => 'page',
					'hierarchial' => true,
					'rewrite' => array('slug' => 'team'),
					'supports' => $supports
				)
		);
	}

	private function extend_post_types() {
		if (is_admin()) {
			
			if (is_array($this->cpts)) {

				include_once CP_LIB_PATH . '/core/class-meta-box.php';

				foreach ($this->cpts as $cpt) {
					$meta_box = new cp_meta_box($cpt);
				}
			}
		}
	}
	
	private function _set_cpts($cpts) {
		$this->cpts = $cpts;
	}
	
	private function _get_cpts() {
		return $this->cpts;
	}

}

?>
