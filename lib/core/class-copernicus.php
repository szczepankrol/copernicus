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

	private static $config = array();
	
	public static $smarty;
	
	public static $phpThumb;

/* -------------- methods -------------- */	
	
	public static function init() {
		session_start();
		
		// load config file
		self::load_config();

		// load dBug
		self::load_library(CP_PATH.'/lib/dBug/dBug.php');
		
		// initialize smarty
		self::smarty_init();
		
		// load phpThumb
		self::phpthumb_init();

		// autoload copernicus classes
		self::autoload_classes(get_template_directory().'/lib/core');

		// autoload child theme classes
		self::autoload_classes(get_theme_root().'/'.get_stylesheet().'/lib');

		// theme support
		self::theme_support();
		
		// add js files
		add_filter('wp_enqueue_scripts', array('CP','add_js'));
		
		// add css files
		add_filter('wp_enqueue_scripts', array('CP','add_css'));
	}

	public static function header() {
		ob_start();
		wp_head();
		
		$header = ob_get_clean();
		$header = str_replace("\n", "\n\t", $header);
		
		global $post;

		$page['image'] = null;
		$page['language'] = str_replace('-', '_', get_bloginfo('language'));
		$page['title'] = self::get_page_title();
		
		if ($post) {
			$page['content'] = str_replace(array("\n","&nbsp;"), '', $post->post_content);
			
			if (has_post_thumbnail( $post->ID ) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
				$page['image'] = $image[0];
			}
		}
		else {
			$page['content'] = '';
		}
		
		self::$smarty->assign('header', $header);
		self::$smarty->assign('page', $page);
		$header = self::$smarty->fetch('_header.html');
		
		echo $header."\n";
	}
	
	public static function footer() {
		ob_start();
		wp_footer();
		
		$footer = ob_get_clean();
		$footer = str_replace("\n", "\n\t", $footer);
		
		self::$smarty->assign('footer', $footer);
		$footer = self::$smarty->display('_footer.html');
		
		echo $footer."\n";
	}

	public static function view($template) {
		
		$view = '';
		
		if (have_posts()) {
			the_post();
		}
		
		$view.= self::$smarty->fetch($template);
		
		echo $view."\n";
	}
	
	public static function add_js() {
		
		if (self::$config['js']) {
			
			foreach (self::$config['js'] as $js) {
				
				if ( (is_admin() && $js['admin']) || (!is_admin() && $js['front']) ) {
					
					if (!$js['url'])
						$js['url'] = get_bloginfo ('stylesheet_directory');
						
					wp_deregister_script($js['name']);
					wp_register_script($js['name'], $js['url'] . '/' . $js['filename'], $js['dependencies'], $js['version'], $js['footer']);
					wp_enqueue_script($js['name']);
				}
			}
		}
	}
	
	public static function add_css() {
		global $wp_styles;

		if (self::$config['css']) {
			
			foreach (self::$config['css'] as $css) {
				
				if ( (is_admin() && $css['admin']) || (!is_admin() && $css['front']) ) {

					if (!$css['url'])
						$css['url'] = get_bloginfo ('stylesheet_directory');

					wp_register_style($css['name'], $css['url'] . '/' . $css['filename'], $css['dependencies'], $css['version'], $css['media']);
					if ($css['condition'])
						$GLOBALS['wp_styles']->add_data($css['name'], 'conditional', $css['condition']);
					wp_enqueue_style($css['name']);
				}

			}
		}
	}
	
	public static function get_config() {
		return self::$config;
	}
	
/* -------------- private -------------- */
	
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
	
	private static function load_library($library_file) {
		
		// check if class file exists and return true if it does
		if (file_exists($library_file)) {
			include_once $library_file;
			return true;
		}
		
		// if class doesn't exists
		echo $library_file . " doesn't exist";
		return false;
	}

	private static function theme_support() {
		
		// add theme support for custom menus
		if (self::$config['theme_support']['menu'])
			add_theme_support('menus');

		// add theme support for post thumbnail
		if (self::$config['theme_support']['post_thumbnail'])
			add_theme_support('post-thumbnails');
		
		// add theme support for automatic feed links
		if (self::$config['theme_support']['automatic_feed_links'])
			add_theme_support('automatic-feed-links');
	}
	
	private static function smarty_init(){
		
		// load smarty
		self::load_library(CP_PATH.'/lib/Smarty/Smarty.class.php');
		
		if (is_child_theme()) {
			$template_dirs[] = get_stylesheet_directory() . '/templates/';
			$plugins_dirs[] = get_stylesheet_directory() . '/lib/SmartyPlugins/';
		}
		$template_dirs[] = CP_PATH . '/templates/';
		$plugins_dirs[] = CP_PATH.'/lib/SmartyPlugins/';
		
		self::$smarty = new Smarty();
		self::$smarty->addPluginsDir($plugins_dirs);
		self::$smarty->setTemplateDir($template_dirs);
		self::$smarty->setCompileDir(WP_CONTENT_DIR . '/smarty/templates_c/');
		self::$smarty->setCacheDir(WP_CONTENT_DIR . '/smarty/cache/');
		
		//var_dump(self::$smarty->getPluginsDir());
	}
	
	private static function phpthumb_init(){
		
		// load phpThumb
		self::load_library(CP_PATH.'/lib/phpThumb/phpthumb.class.php');
		
		self::$phpThumb = new phpThumb();
	}
	
	private static function get_page_title() {
		global $page, $paged;

		$title = '';
		
		$title.= wp_title( '|', false, 'right' );

		// Add the blog name.
		$title.= get_bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title.= " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title.= ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );
		
		return $title;
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
}
