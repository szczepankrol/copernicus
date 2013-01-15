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
		$the_id = get_the_ID();
	}

	$post_meta = '';
	$limit = 0;
	
	if (isset($params['limit'])) {
		$limit = $params['limit'];
	}

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

	if (isset($params['out']))
		$template->assign($params['out'], $post_meta);
	else



	if ($limit) {
		$post_meta_l = strlen($post_meta);

		if ($limit && $post_meta_l > $limit) {
			return substr($post_meta, 0, strpos($post_meta, ' ', $limit));
		} else {
			return $post_meta;
		}
	} else {
		return $post_meta;
	}
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