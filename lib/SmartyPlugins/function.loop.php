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
	global $CP_Loop, $CP_Smarty;
	global $post;
	global $pages;
	$main_post = $post;
	$main_pages = $pages;
	
	//new dBug($params);
	$return = '';
	$key = 0;

	if (isset($params['name']) && $params['name']) {
		
		$loop = $CP_Loop->get_loop($params['name']);
		
		if (isset($params['template'])) {
			$loop['template'] = $params['template'];
		}
	
		// if there are valid arguments parameters
		if (isset($params['args']) && is_array($params['args'])) {

			global $CP_Loop;
			$loop['args'] = $CP_Loop->merge_attributes($params['args'], $loop['args']);

		}

		if ($loop) {

			if (isset($loop['pages']) && $loop['pages']) {
				$current_page = get_query_var( 'paged' );

				if ($current_page) {
					$loop['args']['paged'] = $current_page;
				}
			}

			$WP_loop = new WP_Query( $loop['args'] );

		//	new dBug($WP_loop);

			while ( $WP_loop->have_posts() ) : $WP_loop->the_post();
				$CP_Smarty->smarty->assign('key', $key);
				$return.= $CP_Smarty->smarty->fetch($loop['template']);;
				$key++;
			endwhile;

			$return = apply_filters("cp_loop", $return);

			if (isset($loop['pages']) && $loop['pages'] && $WP_loop->max_num_pages > 1) {
				$return.= show_pagination($WP_loop->max_num_pages);
			}
		}
	}
	else {
		rewind_posts();
		while ( have_posts() ) : the_post();
			$CP_Smarty->smarty->assign('key', $key);
			$CP_Smarty->smarty->assign('post', $post);
			$return.= $CP_Smarty->smarty->fetch($params['template']);
			$key++;
		endwhile;

		global $wp_query;

		if ($wp_query->max_num_pages > 1) {
			$return.= show_pagination($wp_query->max_num_pages);
		}
	}
	
	$post = $main_post;
	$pages = $main_pages;
	return $return;
}

function show_pagination($pages = 0) {
	$pagination = '';

	$page_url = $_SERVER['REQUEST_URI'];
	$page_url = preg_replace('/\/page\/[0-9]+\//', '/', $page_url);

	if ($pages) {
		$current_page = get_query_var( 'paged' );
		if ($current_page < 1) {
			$current_page = 1;
		}

		$pagination.= '<ul class="pagination">';

		if ($current_page > 1) {
			$pagination.= '<li><a href="'.$page_url.'"><<</a></li>';
		}

		for ($i=0; $i < $pages; $i++) {
			if ($i == 0) {
				$pagination.= '<li><a href="'.$page_url.'">1</a></li>';
			}
			else {
				$pagination.= '<li><a href="'.$page_url.'page/'.($i+1).'/">'.($i+1).'</a></li>';
			}
		}

		if ($current_page < $pages) {
			$pagination.= '<li><a href="'.$page_url.'page/'.($current_page+1).'/">>></a></li>';
		}

		$pagination.= '</ul>';
	}

	return $pagination;
}

?>