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
		
		// for each argument
		foreach ($params['args'] as $key => $arg) {
			
			// for meta query arguments
			if ($key == 'meta_query') {
				
				// if the loop in config has NO meta_query
				if (!isset($loop['args'][$key])) {
					$loop['args'][$key] = $arg;
				}
				
				// if the loop in config has meta_query
				else {

					foreach ($arg AS $arg_key => $arg_value) {
						
						foreach ($loop['args'][$key] as $loop_key => $loop_value) {
							if ($arg_value['key'] == $loop_value['key']) {
								//new dBug($arg_value);
								//new dBug($loop['args'][$key][$loop_key]);
								$new_arg = array_merge($loop['args'][$key][$loop_key], $arg_value);
								//new dBug($new_arg);
								$loop['args'][$key][$loop_key] = $new_arg;
							}
						}
					}
					

				}
			}

			else {
				$loop['args'][$key] = $arg;
			}
		}
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