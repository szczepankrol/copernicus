<?php

class CP_Smarty {

	public $smarty;
	
	function __construct() {
		$this->_init();
	}

	private function _init() {
		// load smarty
		CP::load_library(CP_PATH.'/lib/Smarty/Smarty.class.php');
		
		if (is_child_theme()) {
			$template_dirs[] = get_stylesheet_directory() . '/templates/';
			$plugins_dirs[] = get_stylesheet_directory() . '/lib/SmartyPlugins/';
		}
		$template_dirs[] = CP_PATH . '/templates/';
		$plugins_dirs[] = CP_PATH.'/lib/SmartyPlugins/';
		
		$this->smarty = new Smarty();
		$this->smarty->addPluginsDir($plugins_dirs);
		$this->smarty->setTemplateDir($template_dirs);
		$this->smarty->setCompileDir(WP_CONTENT_DIR . '/smarty/templates_c/');
		$this->smarty->setCacheDir(WP_CONTENT_DIR . '/smarty/cache/');
	}
}