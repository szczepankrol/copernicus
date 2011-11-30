<?php

/**
 * Main framework class
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

class cp {

	private $twig;
	private $config;
	private $bloginfo;

	function __construct() {
		// load config
		$this->load_config();

		//load bloginfo
		$this->load_bloginfo();

		// load dBug
		$this->dBug_load();

		// theme support
		$this->theme_support();

		// load and configure additional libraries
		$this->twig_init();
	}

	private function load_config() {
		// get config
		require_once CP_THEME_PATH . '/cp-config.php';
		$this->config = $cp_config;
	}

	private function load_bloginfo() {
		// get config
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
		foreach ($parameters as $value) {
			$this->bloginfo[$value] = get_bloginfo($value);
		}
	}

	private function theme_support() {
		if ($this->config['theme_support']['menu'])
			add_theme_support('menus');
		if ($this->config['theme_support']['post_thumbnail'])
			add_theme_support('post-thumbnails');
		if ($this->config['theme_support']['automatic_feed_links'])
			add_theme_support('automatic-feed-links');
	}

	private function twig_init() {
		require_once CP_LIB_PATH . '/Twig/Autoloader.php';
		Twig_Autoloader::register();

		$twig_loader = new Twig_Loader_Filesystem(CP_THEME_PATH . CP_TEMPLATE_DIR);
		$this->twig = new Twig_Environment($twig_loader, array(
						//'cache' => CP_CACHE_DIR,
				));
	}

	private function dBug_load() {
		require_once CP_LIB_PATH . '/dBug/dBug.php';
	}

	private function add_css() {
		global $wp_styles;
		echo $wp_styles->base_url;
		foreach ($this->config['css'] as $css) {
			wp_register_style($css['name'], CP_STATIC_DIR . $css['folder'] . '/' . $css['filename'], $css['dependencies'], $css['version'], $css['media']);
			if ($css['if'])
				$GLOBALS['wp_styles']->add_data($css['name'], 'conditional', $css['if']);
			wp_enqueue_style($css['name']);
		}
	}
	
	private function add_js() {
		foreach ($this->config['js'] as $js) {
			$js['header']?$js['footer']=0:$js['footer']=1;
			wp_enqueue_script($js['name'], CP_STATIC_DIR . $js['folder'] . '/' . $js['filename'], $js['dependencies'], $js['version'], $js['footer']);
			$GLOBALS['wp_scripts']->add_data( $js['name'], 'conditional', 'IE 6' );
		}
	}
	
	private function cleanup() {
		if (!$this->config['cleanup']['meta']['generator'])
			remove_action('wp_head', 'wp_generator');
		if (!$this->config['cleanup']['meta']['rsd'])
			remove_action('wp_head', 'rsd_link');
		if (!$this->config['cleanup']['meta']['wlwmanifest'])
			remove_action('wp_head', 'wlwmanifest_link');
		if (!$this->config['cleanup']['meta']['index_rel'])
			remove_action( 'wp_head', 'index_rel_link');
		if (!$this->config['cleanup']['meta']['feed_links_extra'])
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		if (!$this->config['cleanup']['meta']['feed_links'])
			remove_action( 'wp_head', 'feed_links', 2 );
		if (!$this->config['cleanup']['meta']['parent_post_rel'])
			remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		if (!$this->config['cleanup']['meta']['start_post_rel'])
			remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		if (!$this->config['cleanup']['meta']['adjacent_posts_rel'])
			remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
		
		if (!$this->config['cleanup']['js']['l10n'])
			wp_deregister_script( 'l10n' );
	}

	public function run() {

		// add css files to header
		$this->add_css();
		
		// add js files to header
		$this->add_js();
		
		$this->cleanup();

		// get header
		ob_start();
		wp_head();
		$header = ob_get_clean();
		$header = str_replace("\n", "\n\t", $header);
		
		$header = preg_replace('/<link rel=\'stylesheet\' [a-z- \']+ http/', '', $header);
		echo CP_BASE_URL;

		// get footer
		ob_start();
		wp_footer();
		$footer = ob_get_clean();
		$footer = str_replace("\n", "\n\t", $footer);


		$vars['config'] = $this->config;
		$vars['bloginfo'] = $this->bloginfo;
		$vars['header'] = $header;
		$vars['footer'] = $footer;
		echo $this->twig->render('index.html', $vars);
	}

}
?>