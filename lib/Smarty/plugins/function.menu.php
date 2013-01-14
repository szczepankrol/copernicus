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
	global $CP_Cpt, $wpdb, $post;
	
	$active_pages = array('active' => '', 'ancestors' => array(), 'parent_cpt' => ''); 
	
	if ($post) {
		$active_pages['active'] = $post->ID;
		$active_pages['ancestors'] = $post->ancestors;
		$active_pages['parent_cpt'] = $CP_Cpt->get_parent_page($post->post_type);
	}
	
	if (!isset ($params['id'])) {
		return null;
	}

	$all_pages = $wpdb->get_results("
		SELECT
			p.ID, 
				p.post_parent, 
					p.post_name, 
						(SELECT pm.meta_value FROM " . $wpdb->postmeta . " AS pm WHERE p.ID = pm.post_id AND pm.meta_key = 'title_de') AS title_de,
						(SELECT pm.meta_value FROM " . $wpdb->postmeta . " AS pm WHERE p.ID = pm.post_id AND pm.meta_key = 'title') AS title
		FROM
			" . $wpdb->posts . " AS p
				
		WHERE 
			p.post_type = 'page'
				AND p.post_status = 'publish'
		ORDER BY menu_order
	", ARRAY_A);
	
	//new dBug($all_pages);
	
	$pages_ordered = order_pages($all_pages);
	
	//new dBug($pages_ordered);
	
	$navigation = render_navigation($pages_ordered, $active_pages);
	
	return $navigation;
}

function render_navigation($pages, $active_pages, $post_name = '') {
	$navigation = '';
	
	foreach ($pages AS $page) {
		$current_post_name = $post_name . $page['post_name'].'/';
		$navigation.= '<li';
		if ($page['ID'] == $active_pages['active'] || in_array($page['ID'], $active_pages['ancestors']) || $page['ID'] == $active_pages['parent_cpt']) {
			$navigation.= ' class="active"';
		}
		$navigation.= '>';
		
		if ($page['title'.LANGUAGE_SUFFIX]) {
			$title = $page['title'.LANGUAGE_SUFFIX];
		}
		else {
			$title = $page['title'];
		}
		
		$navigation.= '<a href="'.get_bloginfo('url').'/'.$current_post_name.'"';
		$navigation.= '>'.$title.'</a>';
		
		if (isset($page['children'])) {
			$navigation.= '<ul>';
			$navigation.= render_navigation($page['children'], $active_pages, $current_post_name);
			$navigation.= '</ul>';
		}
		
		$navigation.= '</li>';
	}
	
	return $navigation;
}

function order_pages($all_pages, $parent_id = 0) {
	$pages_ordered = array();
	
	foreach ($all_pages AS $page) {
		if ($page['post_parent'] == $parent_id) {
			$pages_ordered[$page['ID']] = $page;
			$pages_ordered[$page['ID']]['children'] = order_pages($all_pages, $page['ID']);
		}
	}
	
	return $pages_ordered;
}

function smarty_function_menu_old($params, $template) {
    
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
			$navigation = wp_list_pages( $menu['args'] );
		//	new dBug($menu['args']);
			
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