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
function smarty_function_the_breadcrumb($parms) {
	//new dBug($parms);
	
	global $post;
	$aa=$parms['id'];
	if($aa!=2){
	$breadcrumb = '<ul id="crumbs">';
	if (!is_home()) {
		$breadcrumb.= '<li><a href="';
		$breadcrumb.= get_option('home');
		$breadcrumb.= '">';
		$breadcrumb.= 'Home';
		$breadcrumb.= "</a></li>";
		if (is_category() || is_single()) {
			$breadcrumb.= '<li>';
			the_category(' </li><li> ');
			if (is_single()) {
				$breadcrumb.= "</li><li>";
				the_title();
				$breadcrumb.= '</li>';
			}
		} elseif (is_page()) {
			if ($post->post_parent) {
				$anc = get_post_ancestors($post->ID);

				foreach ($anc as $ancestor) {
					$breadcrumb.= '<li><a href="'.get_the_guid($ancestor) .'">' . get_the_title($ancestor) . '</a></li>';
				}
				$breadcrumb.= '<li>';
				$breadcrumb.= get_the_title();
				$breadcrumb.= '</li>';
			} else {
				$breadcrumb.= '<li>';
				$breadcrumb.= get_the_title();
				$breadcrumb.= '</li>';
			}
		}
	}
	$breadcrumb.= '</ul>';


	return $breadcrumb;
	}
}