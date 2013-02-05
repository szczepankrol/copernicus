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
function smarty_function_the_post_name($params, $template) {
    
	

$post_id_7 = get_post(); 
$post_name= $post_id_7->post_name;
	 
	//$date = get_the_date();
	//new dBug( $post_name );
    return $post_name;
    
}