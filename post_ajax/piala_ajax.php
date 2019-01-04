<?php
/**
 * Plugin Name: Ajax Post Slider
 * Description: Ajax Post Slider
 * Author:      Linh D. Tran
 * Version:     1.0.0
 * License: GPLv2 or later
 */
define('PSA_NAME','AJP');
define('PSA_DIR',plugin_dir_path(__FILE__));
define('PSA_URL',plugin_dir_url(__FILE__));
/*require_once shortcode*/
require_once PSA_DIR . '/shortcode/shortcode_init.php';
/*require_once admin_setting*/
require_once PSA_DIR . '/inc/wp_admin_setting.php';
/*create class Post_Slider_Pagination*/
if(!class_exists('Post_Slider_Pagination')){
	class Post_Slider_Pagination{
		public $fonts = false;
		function __construct(){
			add_action('init',array($this,'func_ajax_post_slider'));
		}
		function func_ajax_post_slider(){
			//hook script
			add_action('wp_enqueue_scripts',array($this,'psa_enqueue_script'));
			require_once PSA_DIR . 'inc/function.php';
		}
		function psa_enqueue_script(){
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'ajp-admin-script', plugins_url('js/main.js', __FILE__), array('jquery'), '1.0', true );
			$php_array = array(
				'admin_ajax' => admin_url( 'admin-ajax.php' )
			);
			wp_localize_script( 'ajp-admin-script', 'svl_array_ajaxp', $php_array );
			wp_enqueue_style( 'ajaxp', plugins_url('css/style.css', __FILE__), array());
		}
	}
}
function func_ajax_post_slider_obj(){
	global $ajp;
	$ajp = new Post_Slider_Pagination();
}
add_action('plugins_loaded','func_ajax_post_slider_obj');