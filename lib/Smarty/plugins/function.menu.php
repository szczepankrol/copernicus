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

	foreach ($params as $key => $param) {
		$menu['args'][$key] = $param;
	}
	
	if ($menu) {
		
		if ($menu['type'] == 'standard') {
			global $post;

			if ($menu['args']['child_of'] === "current_id") {
				if ($post->post_parent) {
					$menu['args']['child_of'] = $post->post_parent;
				}
				else {
					$menu['args']['child_of'] = $post->ID;
				}
			}
			else if ($menu['args']['child_of'] === "level_1") {
				$ancestors = array_reverse($post->ancestors);
				if (isset($ancestors[0]))
					$menu['args']['child_of'] = $ancestors[0];
				else
					$menu['args']['child_of'] = $post->ID;
			}
			else if ($menu['args']['child_of'] === "level_2") {
				$ancestors = array_reverse($post->ancestors);
				if (isset($ancestors[1]))
					$menu['args']['child_of'] = $ancestors[1];
				else
					$menu['args']['child_of'] = $post->ID;
			}
		//	echo $menu['args']['child_of'];
		//	new dBug($menu['args']);
			$navigation = wp_list_pages( $menu['args'] );
			
			if (isset($menu['args']['limit']) && $menu['args']['limit']) {
				$pages_arr = explode("\n", $navigation);
				
				$navigation = '';
				
				$from = 0;
				if (isset($menu['args']['from']))
					$from = $menu['args']['from'] - 1;
				
				for ($i=$from; $i<$menu['args']['limit'] + $from; $i++) {
					if (isset($pages_arr[$i]))
						$navigation.= $pages_arr[$i];
				}
			}
			
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