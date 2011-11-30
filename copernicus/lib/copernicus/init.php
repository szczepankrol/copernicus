<?php

/**
 * Start point for the theme
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

// run the pre hook
do_action('cp_pre');

// theme support
include_once CP_LIB_DIR . '/copernicus/theme-support.php';

// load and configure additional libraries
include_once CP_LIB_DIR . '/copernicus/libraries.php';

// load copernicus
include_once CP_LIB_DIR . '/copernicus/library.php';

// clean up
include_once CP_LIB_DIR . '/copernicus/clean-up.php';

// initialize framework
do_action('cp_init');

// setup framework
do_action('cp_setup');


?>