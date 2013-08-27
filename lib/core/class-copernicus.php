<?php

/**
 * Main framework class
 *
 * @package Copernicus
 * @author Piotr Soluch
 */

/**
 * 
 */
class CP {

	public static $config = array();
	
	public static $smarty;
	
/* -------------- methods -------------- */	
	
	public static function init() {
		session_start();
		
		// load config file
		self::load_config();
		
		// autoload copernicus classes
		self::autoload_classes(get_template_directory().'/lib/core');

		// autoload child theme classes
		self::autoload_classes(get_theme_root().'/'.get_stylesheet().'/lib');
	}

/* -------------- views -------------- */	

	public static function header() {
		global $CP_Header;
		$CP_Header->show_header();
	}
	
	public static function footer() {
		global $CP_Footer;
		$CP_Footer->show_footer();
	}

	public static function view($template) {
		global $CP_Smarty;

		$view = '';
		
		if (have_posts()) {
			the_post();
		}
		
		$view.= $CP_Smarty->smarty->fetch($template);
		
		echo $view."\n";
	}

/* -------------- loaders -------------- */	
		
	private static function load_config() {	
		// get all files from config folder
		if ($handle = opendir(get_stylesheet_directory() . '/config/')) {

			// for each file with .config.php extension
			while (false !== ($filename = readdir($handle))) {
				
				if (preg_match('/.config.php$/', $filename)) {
				
					// get config array from file
					require_once get_stylesheet_directory() . '/config/'.$filename;
					
					// merge with config
					self::$config = array_merge(self::$config, $cp_config);
				}
			}
			closedir($handle);
		}
	}
	
	/**
	 * autoload and init all classes in a specific folder
	 * @param  string $folder_name folder name
	 * @return none
	 */
	private function autoload_classes($folder_name) {
		if (file_exists($folder_name)) {
			$handle = opendir($folder_name);
			
			while (false !== ($entry = readdir($handle))) {
				if (preg_match('/^class-((?!copernicus).*).php/', $entry, $matches)) {
					$file = $folder_name.'/'.$matches[0];
					$class = 'CP_'.ucfirst($matches[1]);
					
					if (file_exists($file)) {
						include_once $file;
						
						global $$class;
						$$class = new $class;
					}
				}
			}
		}
	}

	public static function load_library($library_file) {
		
		// check if class file exists and return true if it does
		if (file_exists($library_file)) {
			include_once $library_file;
			return true;
		}
		
		// if class doesn't exists
		echo $library_file . " doesn't exist";
		return false;
	}
}
