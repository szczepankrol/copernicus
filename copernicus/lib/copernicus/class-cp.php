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
		$this->load_config();
		
		// theme support
		include_once CP_LIB_PATH . '/copernicus/theme-support.php';

		// load and configure additional libraries
		$this->twig_init();

		// clean up
		include_once CP_LIB_PATH . '/copernicus/clean-up.php';
	}
	
	private function load_config() {
		require_once CP_THEME_PATH . '/cp-config.php';
		$this->config = $cp_config;
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

