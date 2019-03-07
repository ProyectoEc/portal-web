<?php
/**
 * The Sidebar containing the main widget areas.
 */

// Show left sidebar
$sidebar_show_left  = green_get_custom_option('show_sidebar_left');
$sidebar_parts = explode(' ', $sidebar_show_left);
$sidebar_tint  = !empty($sidebar_parts[0]) ? $sidebar_parts[0] : 'light';
$sidebar_style = !empty($sidebar_parts[1]) ? $sidebar_parts[1] : $sidebar_tint;

if (!green_sc_param_is_off($sidebar_show_left)) {
	?>
	<div class="sidebar widget_area bg_tint_<?php echo esc_attr($sidebar_tint); ?> sidebar_style_<?php echo esc_attr($sidebar_style); ?>" role="complementary">
		<?php
		do_action( 'before_sidebar' );
		global $GREEN_GLOBALS;
		if (!empty($GREEN_GLOBALS['reviews_markup'])) 
			echo '<aside class="column-1_1 widget widget_reviews">' . ($GREEN_GLOBALS['reviews_markup']) . '</aside>';
		$GREEN_GLOBALS['current_sidebar'] = 'left';
		if ( is_active_sidebar( green_get_custom_option('sidebar_main_left') ) ) { //remove it so SB can work
			if ( ! dynamic_sidebar( green_get_custom_option('sidebar_main_left') ) ) {
				// Put here html if user no set widgets in sidebar
			}
		}
		do_action( 'after_sidebar' );
		?>
	</div> <!-- /.sidebar -->
	<?php
}
?>