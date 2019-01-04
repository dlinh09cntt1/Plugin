<?php
if(!function_exists('func_sendmail_piala')){
	function func_sendmail_piala($atts){
		$atts = shortcode_atts(array(
			'post_type' => 'post'
		),$atts);
		extract($atts);
		ob_start();
		if(get_post_type() == 'post'){
			$title = get_the_title(get_the_ID());
			$link = get_permalink(get_the_ID());
			$to = get_option('email_personnel');
			$current_user = wp_get_current_user();
			$user_email = $current_user->user_email;
			$user_name = $current_user->user_login;
			$subject = 'Piala Send Mail Post';
			$content = 'Username: '.$user_name.' Usermail: '.$user_email.' Title: '.$title.' Link: '.$link;
			wp_mail( $to, $subject, $content );
		}
		return ob_get_clean();
	}
}
add_shortcode('sendmail_piala','func_sendmail_piala');