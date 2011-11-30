<?php

/**
 * Load and setup additional libraries
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

function cp_twig_init() {
	require_once CP_LIB_DIR . '/twig/Autoloader.php';
	Twig_Autoloader::register();
	$twig_loader = new Twig_Loader_Filesystem(CP_TEMPLATE_DIR);
	$cp->twig = new Twig_Environment($twig_loader, array(
				//'cache' => CP_CACHE_DIR,
			));
echo 'asdasd';
	//echo $twig->render('index.html', array('name' => 'Fabien'));
}

cp_twig_init();

//add_action('cp_init', 'cp_twig_init');
?>