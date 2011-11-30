<?php

/**
 * Theme functions
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

//include framework constants
require_once dirname(__FILE__) . '/lib/copernicus/constants.php';

if (CP_VERSION) {
	//include config file 
	require_once CP_THEME_DIR . '/cp-config.php';

	//initialize framework
	require_once CP_LIB_DIR . '/copernicus/init.php';
} else {
	echo 'error loading framework constants';
}
?>