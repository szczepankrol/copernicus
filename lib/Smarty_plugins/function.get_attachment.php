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
function smarty_function_get_attachment($params, $template) {
    
	
	$file=$params['file'];
	 
	//new dBug( $tag );
    return wp_get_attachment_url( $file );
    
}

?>