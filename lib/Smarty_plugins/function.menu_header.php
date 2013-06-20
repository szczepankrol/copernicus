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
function smarty_menu_header($params, $template) {
    
global $CP_Menu;
global $post;
	global $pages;
			
			$args = array(
				'depth'        => 2,
				'exclude'      => '2',
				'title_li'     => '',
				'echo'         => 0,
				'sort_column'  => 'menu_order, post_title',
			);

			$menu = wp_list_pages( $args );
			$menu= str_replace("\n", "", $menu);
			new dBug($menu);
			return $menu;
			
		
    
}

?>