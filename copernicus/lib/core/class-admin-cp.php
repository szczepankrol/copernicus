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
	
	private $templates;

	function __construct() {

		// add js files in admin panel
		add_action('admin_init', array($this, 'load_js'));
		
		// add css files in admin panel
		add_action('admin_init', array($this, 'load_css'));
		
		$this->_set_templates();
		
		// initialize all plugins
		$this->init_plugins();
		
		$this->custom_media_upload();
		
		add_filter('media_upload_tabs', array($this, 'remove_gallery'),99);
	}
	
	function remove_gallery($array) {
		unset($array['gallery']);
	//	print_r($array);
		return $array;
	}
	
	function custom_media_upload() {
		if (isset ($_GET['cmu'])) {
			//add_filter( 'media_upload_tabs', array($this,'no_media_library_tab') );
			add_action('admin_print_footer_scripts', array($this,'header_f'));
		}
	}
	
	function header_f() {
		$cmu = $_GET['cmu'];
		echo '<script type="text/javascript">
							jQuery(document).ready(function() {
								//alert("asda");
								jQuery("tr.align").hide();
								jQuery("tr.url").hide();
								jQuery("tr.image-size").hide();
								jQuery("p.ml-submit").hide();
								jQuery("#url").parents("tr").hide();
								jQuery("a.del-link").hide();
								jQuery(".savesend input.button").val("add");
								jQuery("#go_button").val("add");
								
								window.old = window.updateMediaForm;
									
								window.updateMediaForm = function(html) {
									window.old(html);
									alert("asdasdas");
									jQuery("tr.align").hide();
									jQuery("tr.url").hide();
									jQuery("tr.image-size").hide();
									jQuery("p.ml-submit").hide();
									jQuery("#url").parents("tr").hide();
									jQuery("a.del-link").hide();
									jQuery(".savesend input.button").val("add");
									jQuery("#go_button").val("add");
								}

								jQuery(".savesend input.button").click(function($this){
									vvv = jQuery(this).attr(\'id\');
									vvv = vvv.replace("send[", "");
									vvv = vvv.replace("]", "");
									
									zzz = jQuery(this).parents("table").find("img.thumbnail.").attr(\'src\');
									alert(vvv + zzz);
									jQuery("#media_file", top.document).append("<img src=\""+zzz+"\" /><input type=\"hidden\" name=\"'.$cmu.'[]\" value=\""+vvv+"\" /> "+vvv+"");
									top.tb_remove();
									return false;
									exit;
								}
								);
							});
						</script>';
	}


	function no_media_library_tab( $tabs ) {
		unset($tabs['library']);
		return $tabs;
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
		wp_register_script('cp_admin', CP_COPERNICUS_STATIC_DIR . '/js/cp-admin.js', array('jquery','jquery-ui-core', 'jquery-ui-sortable'), CP_VERSION, 1);
		wp_enqueue_script('cp_admin');
	}
	
	public function load_css() {
		wp_register_style('cp_admin', CP_COPERNICUS_STATIC_DIR . '/css/cp-admin.css', '', CP_VERSION, 'all');
		wp_enqueue_style('cp_admin');
	}
	
	public function page_order_dnd() {
		
		wp_register_style('cp_admin_page_order', CP_COPERNICUS_STATIC_DIR . '/css/cp-admin-page-order.css', '', CP_VERSION, 'all');
		wp_enqueue_style('cp_admin_page_order');
		
		// load js for nested sortable
		wp_register_script('jquery-ui-nested', CP_COPERNICUS_STATIC_DIR . '/js/jquery.ui.nestedSortable.js', array('jquery','jquery-ui-core', 'jquery-ui-sortable'), CP_VERSION, 1);
		wp_enqueue_script('jquery-ui-nested');
		
		// load main admin js file
		wp_register_script('cp_admin_page_order', CP_COPERNICUS_STATIC_DIR . '/js/cp-admin-page-order.js', array('jquery','jquery-ui-core', 'jquery-ui-sortable'), CP_VERSION, 1);
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

	private function _set_templates() {
		global $cp;
		$this->templates = $cp->_get_templates();
	}
	
}

?>