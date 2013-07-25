<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * WP get_the_content function
 *
 * Type:     function
 * Name:     get_the_content
 * Purpose:  print out a bloginfo information
 *
 */
function smarty_function_dynamic_sidebar($params, $template) {
	
	if (isset($params['id'])) {
    	dynamic_sidebar( $params['id'] );
    }
}
