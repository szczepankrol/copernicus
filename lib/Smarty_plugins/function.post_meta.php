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
function smarty_function_post_meta($params, $template) {

	if (isset($params['id'])) {
		$the_id = $params['id'];
	} else {
		$the_id = @get_the_ID();
	}

	if (!$the_id) {
		return null;
	}

	$post_meta = '';

	if (LANGUAGE_SUFFIX != '') {
		$post_meta = get_post_meta($the_id, $params['key'] . LANGUAGE_SUFFIX);
	}

	if (!$post_meta) {
		$post_meta = get_post_meta($the_id, $params['key']);
	}

	$post_meta = maybe_unserialize($post_meta);
	$post_meta = strip_array($post_meta);

	if (isset($params['html']) && $params['html']) {
		$post_meta = apply_filters('the_content', $post_meta);
	}

	if (isset($params['out'])) {
		$template->assign($params['out'], $post_meta);
		return;
	}
	
	if (isset($params['shortcode']) && $params['shortcode']) {
		$post_meta = do_shortcode($post_meta);
	}
	
	if (isset($params['more']) && $params['more']) {
		global $more;
		$more = 1;
		$post_parts = preg_split('/<!--more(.*?)?-->/', $post_meta) ;
		$post_meta = $post_parts[0];
		$post_meta.= ' <a href="'.get_permalink($the_id).'" class=more-link>'.$params['more'].'</a>';
	}
	
	return $post_meta;
}

function strip_array($array) {

	if (is_array($array)) {
		if (count($array) == 0) {
			return null;
		} else if (count($array) == 1) {
			$array = $array[0];
			return strip_array($array);
		}
	}

	return $array;
}