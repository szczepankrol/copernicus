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
function smarty_function_taxonomy($params, $template) {
	global $post;
	$cat=$params['key'];
	$taxonomies= the_terms( $post->ID, ''.$cat.'' ,  ' ' );
	
    return $taxonomies;
}

?>