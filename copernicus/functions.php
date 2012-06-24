<?php

/**
 * Main functions file for Copernicus Theme Framework
 *
 * @package Copernicus
 * @subpackage Theme
 * @author Piotr Soluch
 */

// define path to the theme
define('CP_PATH', dirname(__FILE__));

// main class file path
$core_class_filename = CP_PATH . '/lib/core/class-cp.php';

if (file_exists($core_class_filename)) {

	// load & initialize framework
	require_once $core_class_filename;

	CP::init();

} else {
	echo 'error loading framework';
}

?>