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

	function __construct() {
		// load config
		$this->load_config();

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

	private function theme_support() {
		if ($this->cp_config['theme_support']['menu'])
			add_theme_support('menus');
		if ($this->cp_config['theme_support']['post_thumbnail'])
			add_theme_support('post-thumbnails');
		if ($this->cp_config['theme_support']['automatic_feed_links'])
			add_theme_support('automatic-feed-links');
	}

	private function twig_init() {
		require_once CP_LIB_PATH . '/twig/Autoloader.php';
		Twig_Autoloader::register();

		$twig_loader = new Twig_Loader_Filesystem(CP_THEME_PATH . CP_TEMPLATE_DIR);
		$this->twig = new Twig_Environment($twig_loader, array(
						//'cache' => CP_CACHE_DIR,
				));
	}

	public function run() {
		$vars['config'] = $this->config;
		echo $this->twig->render('index.html', $vars);
	}

}
?>

