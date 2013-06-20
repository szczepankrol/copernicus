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
	global $CP_Cpt, $CP_Menu, $wpdb, $post;
	
	$args = array(
		'depth' => '-1'
	);
	
	if (!isset($params['args'])) {
		$params['args'] = array();
	}
	
	$menu = $CP_Menu->get_menu($params['id']);
	
	if (isset($params['args'])) {
		$args = array_merge($args, $menu['args'], $params['args']);
	}
	
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
						(SELECT pm.meta_value FROM " . $wpdb->postmeta . " AS pm WHERE p.ID = pm.post_id AND pm.meta_key = 'title".LANGUAGE_SUFFIX."') AS title".LANGUAGE_SUFFIX.",
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
	
	$navigation = render_navigation($pages_ordered, $active_pages, $args);
	
	return $navigation;
}

function render_navigation($pages, $active_pages, $args, $post_args = array()) {
	$navigation = '';
	
	if (!isset($post_args['post_name'])) {
		$post_args['post_name'] = '';
	}
	
	if (!isset($post_args['level'])) {
		$post_args['level'] = 1;
	}
	else {
		$post_args['level']++;
	}
	
	foreach ($pages AS $page) {
		$post_args['current_post_name'] = $post_args['post_name'] . $page['post_name'].'/';
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
		
		$navigation.= '<a href="'.get_bloginfo('url').'/'.$post_args['current_post_name'].'"';
		$navigation.= '>'.$title.'</a>';
		
		if (isset($page['children'])) {
			$navigation.= '<ul>';
			//echo $post_args['level'];
			if ($args['depth'] == '-1' || $args['depth'] > $post_args['level']) {
				$navigation.= render_navigation($page['children'], $active_pages, $args, $post_args);
			}
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
