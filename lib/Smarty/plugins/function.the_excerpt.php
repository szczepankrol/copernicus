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
function smarty_function_the_excerpt($params, $template) {
	$more_link_text = '';
	$stripteaser = false;
	
	if (isset($params['more_link_text']))
		$more_link_text = $params['more_link_text'];

	if (isset($params['stripteaser']))
		$stripteaser = $params['stripteaser'];
	
	$content = get_the_excerpt($more_link_text, $stripteaser);
	$content = apply_filters('the_content', $content);
	
    return $content;
}

?>