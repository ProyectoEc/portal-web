<?php
/**
 * ThemeREX Framework: Theme options custom fields
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_options_custom_theme_setup' ) ) {
	add_action( 'green_action_before_init_theme', 'green_options_custom_theme_setup' );
	function green_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'green_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'green_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'green_options_custom_load_scripts');
	function green_options_custom_load_scripts() {
		green_enqueue_script( 'green-options-custom-script',	green_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
function green_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(green_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'green') : esc_html__( 'Choose Image', 'green')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'green') : esc_html__( 'Choose Image', 'green')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="green_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'green') : esc_html__( 'Choose Image', 'green')) . '</a>';
			break;
	}
	return apply_filters('green_filter_show_custom_field', $output, $id, $field, $value);
}
?>