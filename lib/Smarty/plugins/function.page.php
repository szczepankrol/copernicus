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
function smarty_function_page($params, $template) {

	if (isset($params['id']) && isset($params['field'])) {
		
		$page = get_page( $params['id'] ) ;
	
	//	new dBug($page);
	
		if ($page) {
			switch($params['field']) {
				case 'title':
					return $page->post_title;
					break;
				case 'slug':
					return $page->post_name;
					break;
				case 'content':
					return $page->post_content;
					break;
				case 'guid':
					return $page->guid;
					break;
				default:
					return null;
					break;
			}
		}
	}
    
	return null;
	    
}

?>