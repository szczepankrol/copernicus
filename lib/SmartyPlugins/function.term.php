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
function smarty_function_term($params, $template) {
	
    $term = get_terms($params['name']);
	//new dBug( $term );
    $terms="";

    foreach ( $term as $value ): //Look, whe are inside the first key. (currently is '0').
    	$terms.= '<li>';
		$terms.= '<a href="/'.$value->taxonomy.'/'.$value->slug.'/" class="button"';
		$terms.= '>';
		$terms.= $value->name;
		$terms.= '</a>';
		$terms.= '</li>';
 
	endforeach;
	
	return $terms;
}

?>