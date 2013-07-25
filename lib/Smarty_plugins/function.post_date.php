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
function smarty_function_post_date($params, $template) {
    
	

	 
	$date = get_the_date();
	$date = str_replace('January', 'Styczeń',$date);
	$date = str_replace('February', 'Luty',$date);
	$date = str_replace('March', 'Marzec',$date);
	$date = str_replace('April', 'Kwiecień',$date);
	$date = str_replace('May', 'Maj',$date);
	$date = str_replace('June', 'Czerwiec',$date);
	$date = str_replace('July', 'Lipiec',$date);
	$date = str_replace('August', 'Sierpień',$date);
	$date = str_replace('September', 'Wrzesień',$date);
	$date = str_replace('October', 'Październik',$date);
	$date = str_replace('November', 'Listopad',$date);
	$date = str_replace('December', 'Grudzień',$date);
	//new dBug( $tag );
    return $date;
    
}

?>