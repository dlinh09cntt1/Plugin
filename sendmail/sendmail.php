<?php
/**
 * Plugin Name: SendMail
 * Description: SendMail Plugin
 * Author:      Linh D. Tran
 * Version:     1.0.0
 * License: GPLv2 or later
 */
define('CA_NAME','SENDMAIL');
define('CA_DIR',plugin_dir_path(__FILE__));
define('CA_URL',plugin_dir_url(__FILE__));
/*require_once admin_setting*/
require_once CA_DIR . '/inc/wp_admin_setting.php';
require_once CA_DIR . 'inc/function.php';
/*create class my_sendmail*/
if(!class_exists('My_SendMail')){
	class My_SendMail{
		public $fonts = false;
		function __construct(){
			add_action('init',array($this,'func_sendmail'));
		}
		function func_sendmail(){
			//hook script
			add_action('wp_enqueue_scripts',array($this,'ca_enqueue_script'));
		}
		function ca_enqueue_script(){
			wp_enqueue_script('jquery');
		}
	}
}
function func_sendmail_obj(){
	global $sendmail;
	$sendmail = new My_SendMail();
}
add_action('plugins_loaded','func_sendmail_obj');