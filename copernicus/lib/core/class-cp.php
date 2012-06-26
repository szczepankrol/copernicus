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

	private static $config;
	
	public static $smarty;
	
	public static $phpThumb;

/* -------------- methods -------------- */	
	
	public static function init() {
		
		// load config file
		self::load_config();

		// load dBug
		self::load_library(CP_PATH.'/lib/dBug/dBug.php');
		
		// initialize smarty
		self::smarty_init();
		
		// load phpThumb
		self::phpthumb_init();
		
		// load SwiftMailer
		self::load_library(CP_PATH.'/lib/SwiftMailer/swift_required.php');
		
		// load & init cleanup class
		if (self::load_class('cleanup'))
			$CP_Cleanup = new CP_Cleanup;
		
		// load & init custom post types class
		if (self::load_class('cpt'))
			$CP_Cpt = new CP_Cpt;
		
		// load & init custom post types class
		if (self::load_class('mb'))
			$CP_Mb = new CP_Mb;
		
		// load & init taxonomy class
		if (self::load_class('taxonomy'))
			$CP_Taxonomy = new CP_Taxonomy;
		
		// load & init user role class
		if (self::load_class('ur'))
			$CP_Ur = new CP_Ur;
		
		// load & init user meta boxes class
		if (self::load_class('umb'))
			$CP_Umb = new CP_Umb;
		
		// load & init user meta boxes class
		if (self::load_class('menu')) {
			global $CP_Menu;
			$CP_Menu = new CP_Menu;
		}
		
		// load & init loop class
		if (self::load_class('loop')) {
			global $CP_Loop;
			$CP_Loop = new CP_Loop;
		}
		
		// load & init loop class
		if (self::load_class('image')) {
			global $CP_Image;
			$CP_Image = new CP_Image;
		}
		
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
		
		$page['title'] = self::get_page_title();
		
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
		
		while ( have_posts() ) : the_post();
		
		$view = self::$smarty->fetch($template);
		
		endwhile;
		
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
		
		// get config
		require_once get_stylesheet_directory() . '/cp-config.php';
		self::$config = $cp_config;
	}
	
	private static function load_class($class_name) {
		
		// define class file path
		$class_file = CP_PATH . '/lib/core/class-'.$class_name.'.php';

		// check if class file exists and return true if it does
		if (file_exists($class_file)) {
			include_once $class_file;
			return true;
		}
		
		echo $class_file . " doesn't exist";
		// if class doesn't exists
		return false;
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
		
		if (is_child_theme()) 
			$template_dirs[] = get_stylesheet_directory() . '/templates/';
		$template_dirs[] = CP_PATH . '/templates/';
		
		self::$smarty = new Smarty();
		self::$smarty->setTemplateDir($template_dirs);
		self::$smarty->setCompileDir(WP_CONTENT_DIR . '/smarty/templates_c/');
		self::$smarty->setCacheDir(WP_CONTENT_DIR . '/smarty/cache/');
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
}

?>