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
require_once dirname(__FILE__) . '/copernicus/lib/core/constants.php';

if (CP_VERSION) {

	// load initialize framework
	require_once CP_LIB_PATH . '/core/class-cp.php';

	$cp = new cp;

	if (is_admin()) {
		require_once CP_LIB_PATH . '/core/class-admin-cp.php';
		$admin_cp = new admin_cp;
	}
} else {
	echo 'error loading framework constants';
}
?>