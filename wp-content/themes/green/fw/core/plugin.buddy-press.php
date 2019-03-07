<?php
/* BuddyPress support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('green_buddypress_theme_setup')) {
	add_action( 'green_action_before_init_theme', 'green_buddypress_theme_setup' );
	function green_buddypress_theme_setup() {
		if (green_is_buddypress_page()) {
			add_action( 'green_action_add_styles', 'green_buddypress_frontend_scripts' );
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('green_filter_detect_inheritance_key',	'green_buddypress_detect_inheritance_key', 9, 1);
		}
	}
}
if ( !function_exists( 'green_buddypress_settings_theme_setup2' ) ) {
	add_action( 'green_action_before_init_theme', 'green_buddypress_settings_theme_setup2', 3 );
	function green_buddypress_settings_theme_setup2() {
		if (green_exists_buddypress()) {
			green_add_theme_inheritance( array('buddypress' => array(
				'stream_template' => 'buddypress',
				'single_template' => '',
				'taxonomy' => array(),
				'taxonomy_tags' => array(),
				'post_type' => array(),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if BuddyPress installed and activated
if ( !function_exists( 'green_exists_buddypress' ) ) {
	function green_exists_buddypress() {
		return class_exists( 'BuddyPress' );
	}
}

// Check if current page is BuddyPress page
if ( !function_exists( 'green_is_buddypress_page' ) ) {
	function green_is_buddypress_page() {
		return  green_is_bbpress_page() || (function_exists('is_buddypress') && is_buddypress());
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'green_buddypress_detect_inheritance_key' ) ) {
	//add_filter('green_filter_detect_inheritance_key',	'green_buddypress_detect_inheritance_key', 9, 1);
	function green_buddypress_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return green_is_buddypress_page() ? 'buddypress' : '';
	}
}

// Enqueue BuddyPress custom styles
if ( !function_exists( 'green_buddypress_frontend_scripts' ) ) {
	//add_action( 'green_action_add_styles', 'green_buddypress_frontend_scripts' );
	function green_buddypress_frontend_scripts() {
		green_enqueue_style( 'buddypress-style',  green_get_file_url('css/buddypress-style.css'), array(), null );
	}
}

?>