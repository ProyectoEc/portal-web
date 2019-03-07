<?php
/**
 * ThemeREX Framework: messages subsystem
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('green_messages_theme_setup')) {
	add_action( 'green_action_before_init_theme', 'green_messages_theme_setup' );
	function green_messages_theme_setup() {
		// Core messages strings
		add_action('green_action_add_scripts_inline', 'green_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('green_get_error_msg')) {
	function green_get_error_msg() {
		global $GREEN_GLOBALS;
		return !empty($GREEN_GLOBALS['error_msg']) ? $GREEN_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('green_set_error_msg')) {
	function green_set_error_msg($msg) {
		global $GREEN_GLOBALS;
		$msg2 = green_get_error_msg();
		$GREEN_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('green_get_success_msg')) {
	function green_get_success_msg() {
		global $GREEN_GLOBALS;
		return !empty($GREEN_GLOBALS['success_msg']) ? $GREEN_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('green_set_success_msg')) {
	function green_set_success_msg($msg) {
		global $GREEN_GLOBALS;
		$msg2 = green_get_success_msg();
		$GREEN_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('green_get_notice_msg')) {
	function green_get_notice_msg() {
		global $GREEN_GLOBALS;
		return !empty($GREEN_GLOBALS['notice_msg']) ? $GREEN_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('green_set_notice_msg')) {
	function green_set_notice_msg($msg) {
		global $GREEN_GLOBALS;
		$msg2 = green_get_notice_msg();
		$GREEN_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('green_set_system_message')) {
	function green_set_system_message($msg, $status='info', $hdr='') {
		update_option('green_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('green_get_system_message')) {
	function green_get_system_message($del=false) {
		$msg = get_option('green_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			green_del_system_message();
		return $msg;
	}
}

if (!function_exists('green_del_system_message')) {
	function green_del_system_message() {
		delete_option('green_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('green_messages_add_scripts_inline')) {
	function green_messages_add_scripts_inline() {
		global $GREEN_GLOBALS;
		echo '<script type="text/javascript">'
			. 'jQuery(document).ready(function() {'
			// Strings for translation
			. 'GREEN_GLOBALS["strings"] = {'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'green')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'green')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'green')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'green')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'green')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'green')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address <b>%s</b> we sent a confirmation email.<br>Please, open it and click on the link.', 'green')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'green')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'green')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'green')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'green')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'green')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'green')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'green')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'green')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'green')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'green')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'green')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'green')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'green')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'green')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'green')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'green')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'green')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'green')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'green')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'green')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'green')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'green')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'green')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'green')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'green')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'green')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'green')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'green')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'green')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'green')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'green')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'green')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'green')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'green')) . '"'
				. '};'
			. '});'
			. '</script>';
	}
}
?>