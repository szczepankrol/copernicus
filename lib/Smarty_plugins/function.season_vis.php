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
function smarty_function_season_vis($params, $template) {
    
	//new dBug ($params);
	$pcustom = get_post_custom($params['id']);

	
	$months = maybe_unserialize($pcustom['month'][0]);
	if (is_array($months) && in_array(date('m'), $months)) {

	}
	else {
		$season=" style='display:none'";

	}

	return $season;
    
}
?>