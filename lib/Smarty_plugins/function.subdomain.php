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
function smarty_function_subdomain($params, $template) {
	
   $url = $_SERVER['HTTP_HOST'];

$parsedUrl = parse_url($url);
$host = explode('.', $parsedUrl['path']);

$subdomain = $host[0];
//new dBug( $subdomain );
if($subdomain) {$id="id=\"".$subdomain."\"";}

    return $id;
}

?>