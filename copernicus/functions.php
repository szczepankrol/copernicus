<?php

/**
 * Theme functions
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

// include framework constants
require_once dirname(__FILE__) . '/lib/copernicus/constants.php';

if (CP_VERSION) {

	// load initialize framework
	require_once CP_LIB_PATH . '/copernicus/class-cp.php';
	
	$cp = new cp;
	
} else {
	echo 'error loading framework constants';
}
?>