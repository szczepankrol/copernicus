<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * WP bloginfo function
 *
 * Type:     function<br>
 * Name:     bloginfo<br>
 * Purpose:  print out a bloginfo information
 *
 */
function smarty_function_menu($params, $template) {
    
	if (!isset ($params['id']))
		return null;
	
	global $CP_Menu;
	$menu = $CP_Menu->get_menu($params['id']);
	
	if ($menu) {
		
		if ($menu['type'] == 'standard') {

			if ($menu['args']['child_of'] === "current_id") {
				global $post;
				if ($post->post_parent) {
					$menu['args']['child_of'] = $post->post_parent;
				}
				else {
					$menu['args']['child_of'] = $post->ID;
				}
			}
		//	echo $menu['args']['child_of'];
		//	new dBug($menu['args']);
			$navigation = wp_list_pages( $menu['args'] );
			$navigation = preg_replace('/current_page_item/', 'active', $navigation);
			$navigation = preg_replace('/page_item[ ]?/', '', $navigation);
			$navigation = preg_replace('/page-item-2[0-9 ]+?/', '', $navigation);
		}
		return $navigation;
	}
	
//    $retval = bloginfo($params['show'], $params['filter']);
	
    return null;
    
}

?>