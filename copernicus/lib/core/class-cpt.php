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
				if ($cpt['settings']['active'])
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

		$settings = array(
			'active' => false,
			'labels' => $cpt['labels'],
			'name' => 'customposttype',
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => false,
			'query_var' => false,
			'capability_type' => 'post',
			'hierarchial' => false,
			'rewrite' => array('slug' => 'cpt'),
			'orderby' => 'title',
			'order' => 'ASC',
			'meta_order' => 'meta_value'
		);
		
		$settings = array_merge($settings, $cpt['settings']);
	//	new dBug($settings);
		register_post_type(
				$cpt['settings']['name'], $settings
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
