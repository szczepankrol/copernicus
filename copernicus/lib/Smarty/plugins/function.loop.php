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
function smarty_function_loop($params, $template) {
	global $CP_Loop;
	global $post;
	global $pages;
	$main_post = $post;
	$main_pages = $pages;
	
	//new dBug($params);
	
	if (!$params['name'])
		return null;
	
	$loop = $CP_Loop->get_loop($params['name']);
	
	// if there are valid arguments parameters
	if (isset($params['args']) && is_array($params['args'])) {
		
		global $CP_Loop;
		$loop['args'] = $CP_Loop->merge_attributes($params['args'], $loop['args']);
		
	}
	//new dBug( $loop['args'] );
	
	if (isset($params['template']))
		$loop['template'] = $params['template'];
	
	if ($loop) {
		$return = '';
		
		$WP_loop = new WP_Query( $loop['args'] );
		
		$key = 0;
		
		while ( $WP_loop->have_posts() ) : $WP_loop->the_post();
			CP::$smarty->assign('key', $key);
			$return.= CP::$smarty->fetch($loop['template']);;
			$key++;
		endwhile;
		
		$post = $main_post;
		$pages = $main_pages;
		
		$return = apply_filters("cp_loop", $return);
		return $return;
	}
	
	$post = $main_post;
	return null;
	
}

?>