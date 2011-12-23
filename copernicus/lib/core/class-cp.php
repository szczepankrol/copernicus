<?php

/**
 * Main framework class
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

/**
 * 
 */
class cp {

	private $twig;
	public $config;
	private $bloginfo;
	private $cpt; // custom post types
	private $scpt; // standard post types
	private $sidebars; // custom post types
	public $menus; // menus
	private $templates; // template files

	/**
	 * 
	 */
	function __construct() {
		// load config file
		$this->load_config();

		//load bloginfo
		$this->load_bloginfo();

		// load dBug
		$this->load_dBug();

		// theme support
		$this->theme_support();

		// load and configure additional libraries
		$this->twig_init();

		// extend standard post types
		$this->extend_spt();

		// create custom post types
		$this->create_cpt();
		
		// register sidevars
		$this->create_sidebars();
		
		// add css files to header
		$this->add_css();

		// add js files to header
		$this->add_js();
		//update_post_meta($post_ID, '_wp_page_template',  $page_template);
	}

	/**
	 * 
	 */
	private function load_config() {
		// get config
		require_once CP_THEME_PATH . '/cp-config.php';
		$this->config = $cp_config;
		$this->cpt = $cp_cpt;
		$this->spt = $cp_spt;
		$this->sidebars = $cp_sidebar;
		$this->menus = $cp_menu;
		$this->templates = $cp_template;
	}

	/**
	 * 
	 */
	private function load_bloginfo() {
		
		// define bloginfo keys
		$parameters = array(
			'name',
			'description',
			'admin_email',
			'url',
			'wpurl',
			'stylesheet_directory',
			'stylesheet_url',
			'template_directory',
			'template_url',
			'atom_url',
			'rss2_url',
			'rss_url',
			'pingback_url',
			'rdf_url',
			'comments_atom_url',
			'comments_rss2_url',
			'charset',
			'html_type',
			'language',
			'text_direction',
			'version'
		);
		
		// get all values from bloginfo based on keys
		foreach ($parameters as $value) {
			$this->bloginfo[$value] = get_bloginfo($value);
		}
	}

	/**
	 * 
	 */
	private function theme_support() {
		
		// add theme support for custom menus
		if ($this->config['theme_support']['menu'])
			add_theme_support('menus');

		// add theme support for post thumbnail
		if ($this->config['theme_support']['post_thumbnail'])
			add_theme_support('post-thumbnails');
		
		// add theme support for automatic feed links
		if ($this->config['theme_support']['automatic_feed_links'])
			add_theme_support('automatic-feed-links');
	}

	/**
	 * 
	 */
	private function twig_init() {
		
		// load Twig class
		require_once CP_LIB_PATH . '/Twig/Autoloader.php';
		Twig_Autoloader::register();

		// define Twig loader
		$twig_loader = new Twig_Loader_Filesystem(array(CP_THEME_PATH . CP_TEMPLATE_DIR, CP_COPERNICUS_PATH . CP_TEMPLATE_DIR));
		$this->twig = new Twig_Environment($twig_loader, array(
			//'cache' => CP_CACHE_DIR,
		));
		
		// load twig extensions
		include_once 'twig.php';
		
		// add custom functions
		$this->twig->addFunction('menu', new Twig_Function_Function('menu'));

	}
	
	/**
	 * 
	 */
	private function load_dBug() {
		
		// load dBug library
		require_once CP_LIB_PATH . '/dBug/dBug.php';
	}
	
	/**
	 * 
	 */
	private function add_css() {
		global $wp_styles;

		foreach ($this->config['css'] as $css) {
			if ((is_admin() && $css['admin']) || (!is_admin() && $css['front'])) {
				wp_register_style($css['name'], CP_STATIC_DIR . $css['folder'] . '/' . $css['filename'], $css['dependencies'], $css['version'], $css['media']);
				if ($css['condition'])
					$GLOBALS['wp_styles']->add_data($css['name'], 'conditional', $css['condition']);
				wp_enqueue_style($css['name']);
			}
			
		}
	}
	
	/**
	 * 
	 */
	private function add_js() {
		foreach ($this->config['js'] as $js) {
			if ((is_admin() && $js['admin']) || (!is_admin() && $js['front'])) {
				$js['header'] ? $js['footer'] = 0 : $js['footer'] = 1;
				if ($js['googleapis']) {
					wp_deregister_script($js['name']);
					wp_register_script($js['name'], 'http://ajax.googleapis.com/ajax/libs' . $js['googleapis'], $js['dependencies'], $js['version'], $js['footer']);
					wp_enqueue_script($js['name']);
				}
				else
					wp_enqueue_script($js['name'], CP_STATIC_DIR . $js['folder'] . '/' . $js['filename'], $js['dependencies'], $js['version'], $js['footer']);
				$GLOBALS['wp_scripts']->add_data($js['name'], 'conditional', 'IE 6');
			}
		}
	}
	
	/**
	 * 
	 */
	private function cleanup() {
		if (!$this->config['cleanup']['meta']['generator'])
			remove_action('wp_head', 'wp_generator');
		if (!$this->config['cleanup']['meta']['rsd'])
			remove_action('wp_head', 'rsd_link');
		if (!$this->config['cleanup']['meta']['wlwmanifest'])
			remove_action('wp_head', 'wlwmanifest_link');
		if (!$this->config['cleanup']['meta']['index_rel'])
			remove_action('wp_head', 'index_rel_link');
		if (!$this->config['cleanup']['meta']['feed_links_extra'])
			remove_action('wp_head', 'feed_links_extra', 3);
		if (!$this->config['cleanup']['meta']['feed_links'])
			remove_action('wp_head', 'feed_links', 2);
		if (!$this->config['cleanup']['meta']['parent_post_rel'])
			remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		if (!$this->config['cleanup']['meta']['start_post_rel'])
			remove_action('wp_head', 'start_post_rel_link', 10, 0);
		if (!$this->config['cleanup']['meta']['adjacent_posts_rel'])
			remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

		if (!$this->config['cleanup']['js']['l10n'])
			wp_deregister_script('l10n');
	}

	/**
	 * 
	 */
	private function filter_url_fix($matches) {
		return str_replace(CP_BASE_URL, '', $matches[0]);
	}
	
	/**
	 * 
	 */
	private function filter_script_conditional($matches) {
		foreach ($this->config['js'] AS $js) {
			if ($js['condition']) {
				if (preg_match('/' . str_replace('/', '\/', $js['folder']) . '\/' . $js['filename'] . '/', $matches[1])) {
					return "<!--[if " . $js['condition'] . "]>\n\t" . $matches[0] . "\n\t<![endif]-->";
				}
			}
		}

		return $matches[0];
	}
	
	/**
	 * 
	 */
	private function create_cpt() {
		
		// if there are custom post types defined in config
		if (is_array($this->cpt)) {
			
			// load custom post type class
			include CP_LIB_PATH . '/core/class-cpt.php';

			// declare custom post type
			$cp_cpt = new cp_cpt($this->cpt);
		}
	}
	
	/**
	 * 
	 */
	private function extend_spt() {
		
		// if there are extensions to standard post types
		if (is_array($this->spt)) {
			
			// load standard post type class
			include CP_LIB_PATH . '/core/class-spt.php';
				
			// extend standard post type
			$cp_spt = new cp_spt($this->spt);
		}
	}
	
	/**
	 * 
	 */
	private function create_sidebars() {
		
		// if there are sidebars defined in config
		if (is_array($this->sidebars)) {

			// for each sidebar
			foreach ($this->sidebars AS $key => $sidebar) {
				
				// register sidebar
				register_sidebar( $sidebar );
			}
		}
	}

	/**
	 * 
	 */
	public function run() {

		// clean unwanted markup
		$this->cleanup();

		// get header
		ob_start();
		wp_head();
		$header = ob_get_clean();
		$header = str_replace("\n", "\n\t", $header);

		$header = preg_replace_callback('/(<link rel=\'stylesheet\' [0-9a-z =\'-:\/?]+>)/', array($this, 'filter_url_fix'), $header);
		$header = preg_replace_callback('/(<script [0-9a-z =\'-:\/?]+>)/', array($this, 'filter_url_fix'), $header);

		$header = preg_replace_callback('/(<script [0-9a-z =\'-:\/?]+><\/script>)/', array($this, 'filter_script_conditional'), $header);

		// get footer
		ob_start();
		wp_footer();
		$footer = ob_get_clean();
		$footer = str_replace("\n", "\n\t", $footer);

		$footer = preg_replace_callback('/(<script [0-9a-z =\'-:\/?]+>)/', array($this, 'filter_url_fix'), $footer);

		$footer = preg_replace_callback('/(<script [0-9a-z =\'-:\/?]+><\/script>)/', array($this, 'filter_script_conditional'), $footer);
		
		if (is_home()) {
			$id = get_option('page_for_posts');
		}
		else if (is_page()) {
			$id = get_the_ID();
		}
		
		$meta_data = get_post_custom($id);
		
		if ($meta_data['_wp_page_template'][0]) {
			$template = $meta_data['_wp_page_template'][0];
		}
		else if (is_array($this->templates)) {
			foreach ($this->templates AS $temp) {
				if ($temp['default'])
					$template = $temp['id'];
			}
		}
		else {
			$template = 'default';
		}
		
		
//		echo $id;
//		echo '<br />';
//		echo '<br />';
//		
//		global $query_string;
//		//query_posts('page=44');
//		echo '<p>'.$query_string.'</p>';
//		
//		echo 'home'.is_home();
//		echo 'front'.is_front_page();
//		echo 'page'.is_page();
//		echo '<br />';
//		the_ID();
//		echo '<br />';
//		$custom = get_post_custom($post->ID);
//		echo $custom['_wp_page_template'][0];
//		echo '<br />';
//		$frontpage_id = get_option('page_on_front');
//		echo $frontpage_id;
//		echo '<br />';
//		$frontpage_id = get_option('page_for_posts');
//		echo $frontpage_id;
//		echo '<br />';
	
//		while ( have_posts() ) : the_post();
//			echo the_time('F jS, Y');
//			echo the_title();
//			//the_ID();
//			echo get_post_type();
//		endwhile;
		
		$vars['config'] = $this->config;
		$vars['bloginfo'] = $this->bloginfo;
		$vars['header'] = $header;
		$vars['footer'] = $footer;
		
		// render page
		echo $this->twig->render($template.'.html', $vars);
	}
	
	public function _get_templates() {
		return $this->templates;
	}
	
}

?>