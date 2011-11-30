<?php
/**
 * Theme support declaration
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

function cp_theme_support() {
	if (CP_WP_SUPPORT_MENU) add_theme_support( 'menus' );
	if (CP_WP_SUPPORT_POST_THUMBNAIL) add_theme_support( 'post-thumbnails' );
	if (CP_WP_SUPPORT_AUTOMATIC_FEED_LINKS) add_theme_support( 'automatic-feed-links' );
}

add_action( 'cp_init', 'cp_theme_support' );

?>