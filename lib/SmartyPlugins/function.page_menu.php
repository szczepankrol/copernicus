<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * WP bloginfo function
 *
 * Type:     function
 * Name:     bloginfo
 * Purpose:  print out a bloginfo information
 *
 */
function smarty_function_page_menu($params, $template) {

	// default params
	$default_params = array(
        'depth'       => 0,
		'sort_column' => 'menu_order, post_title',
		'menu_class'  => 'menu',
		'include'     => '',
		'exclude'     => '',
		'echo'        => true,
		'show_home'   => false,
		'link_before' => '',
		'link_after'  => ''
	);
    
    // merge default params with the provided ones
	$params = array_merge($default_params, $params);
	
    return wp_page_menu($params['show']);
}
