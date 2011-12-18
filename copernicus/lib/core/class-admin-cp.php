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

		// add js files in admin panel
		add_action('admin_init', array($this, 'load_js'));
		
		// add css files in admin panel
		add_action('admin_init', array($this, 'load_css'));
		
		// initialize all plugins
		$this->init_plugins();
		
	}
	
	function init_plugins() {
		global $cp;
		
		// auto populate menu_order for new pages
		if ($cp->config['plugins']['admin_auto_menu_order'])
			add_action('dbx_post_advanced', array($this, 'page_save_dialog'));
		
		// page order dnd
		if ($cp->config['plugins']['admin_page_order_dnd'])
			add_action('admin_init', array($this, 'page_order_dnd'));
	}

	public function load_js() {
		
		// load main admin js file
		wp_register_script('cp_admin', CP_STATIC_DIR . '/js/cp-admin.js', array('jquery','jquery-ui-core', 'jquery-ui-sortable'), CP_VERSION, 1);
		wp_enqueue_script('cp_admin');
	}
	
	public function load_css() {
		wp_register_style('cp_admin', CP_STATIC_DIR . '/css/cp-admin.css', '', CP_VERSION, 'all');
		wp_enqueue_style('cp_admin');
	}
	
	public function page_order_dnd() {
		
		wp_register_style('cp_admin_page_order', CP_STATIC_DIR . '/css/cp-admin-page-order.css', '', CP_VERSION, 'all');
		wp_enqueue_style('cp_admin_page_order');
		
		// load js for nested sortable
		wp_register_script('jquery-ui-nested', CP_STATIC_DIR . '/js/jquery.ui.nestedSortable.js', array('jquery','jquery-ui-core', 'jquery-ui-sortable'), CP_VERSION, 1);
		wp_enqueue_script('jquery-ui-nested');
		
		// load main admin js file
		wp_register_script('cp_admin_page_order', CP_STATIC_DIR . '/js/cp-admin-page-order.js', array('jquery','jquery-ui-core', 'jquery-ui-sortable'), CP_VERSION, 1);
		wp_enqueue_script('cp_admin_page_order');
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