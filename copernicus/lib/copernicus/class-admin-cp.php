<?php

/**
 * Admin class
 *
 * @package Copernicus
 * @author Piotr Soluch
 */
?>
<?php

class admin_cp {

	function __construct() {

		add_action('admin_init', array($this, 'load_js'));
		add_action('dbx_post_advanced', array($this, 'page_save_dialog'));
	}

	public function load_js() {
		wp_register_script('cp_admin', CP_STATIC_DIR . '/js/cp-admin.js', 'jquery', CP_VERSION, 1);
		wp_enqueue_script('cp_admin');
	}

	public function page_save_dialog() {
		global $post;
		global $wpdb;

		$sql = "SELECT max(menu_order) 
			FROM " . $wpdb->posts . " 
				WHERE post_type = 'page'
		";

		$max_order = $wpdb->get_var($wpdb->prepare($sql));
		if (!$post->menu_order)
			$post->menu_order = $max_order + 10;
	}

}

?>