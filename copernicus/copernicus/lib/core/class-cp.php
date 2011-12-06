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
	private $cpt; // custom post types

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

		// create custom post types
		$this->create_cpt();
	}

	private function load_config() {
		// get config
		require_once CP_THEME_PATH . '/cp-config.php';
		$this->config = $cp_config;
		$this->cpt = $cp_cpt;
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

		$twig_loader = new Twig_Loader_Filesystem(array(CP_THEME_PATH . CP_TEMPLATE_DIR, CP_CORE_TEMPLATE_PATH));
		$this->twig = new Twig_Environment($twig_loader, array(
						//'cache' => CP_CACHE_DIR,
				));
	}

	private function load_dBug() {
		require_once CP_LIB_PATH . '/dBug/dBug.php';
	}

	private function add_css() {
		global $wp_styles;

		foreach ($this->config['css'] as $css) {
			wp_register_style($css['name'], CP_STATIC_DIR . $css['folder'] . '/' . $css['filename'], $css['dependencies'], $css['version'], $css['media']);
			if ($css['condition'])
				$GLOBALS['wp_styles']->add_data($css['name'], 'conditional', $css['condition']);
			wp_enqueue_style($css['name']);
		}
	}

	private function add_js() {
		foreach ($this->config['js'] as $js) {
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

	private function filter_url_fix($matches) {
		return str_replace(CP_BASE_URL, '', $matches[0]);
	}

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
	
	private function create_cpt() {
		if (is_array($this->cpt)) {
			include CP_LIB_PATH . '/core/class-cp-cpt.php';

			foreach ($this->cpt AS $key => $cpt) {
				$cpt = new cpt($cpt);
			}
		}
	}

	public function run() {

		// add css files to header
		$this->add_css();

		// add js files to header
		$this->add_js();

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

		$vars['config'] = $this->config;
		$vars['bloginfo'] = $this->bloginfo;
		$vars['header'] = $header;
		$vars['footer'] = $footer;
		echo $this->twig->render('index.html', $vars);
	}

}

?>