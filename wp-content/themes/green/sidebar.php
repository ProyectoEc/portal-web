<?php
/**
 * The Sidebar containing the main widget areas.
 */

$sidebar_show  = green_get_custom_option('show_sidebar_main');
$sidebar_parts = explode(' ', $sidebar_show);
$sidebar_tint  = !empty($sidebar_parts[0]) ? $sidebar_parts[0] : 'light';
$sidebar_style = !empty($sidebar_parts[1]) ? $sidebar_parts[1] : $sidebar_tint;
$sidebar_class = green_get_sidebar_class(green_get_custom_option('show_sidebar_main'), green_get_custom_option('sidebar_main_position'));

if (!green_sc_param_is_off($sidebar_show)) {
	?>
	<div class="<?php echo esc_attr($sidebar_class); ?> sidebar widget_area bg_tint_<?php echo esc_attr($sidebar_tint); ?> sidebar_style_<?php echo esc_attr($sidebar_style); ?>" role="complementary">
		<?php
		do_action( 'before_sidebar' );
		global $GREEN_GLOBALS;
		if (!empty($GREEN_GLOBALS['reviews_markup'])) 
			echo '<aside class="column-1_1 widget widget_reviews">' . ($GREEN_GLOBALS['reviews_markup']) . '</aside>';
		$GREEN_GLOBALS['current_sidebar'] = 'main';
		if ( is_active_sidebar( green_get_custom_option('sidebar_main') ) ) { //remove it so SB can work
			if ( ! dynamic_sidebar( green_get_custom_option('sidebar_main') ) ) {
				// Put here html if user no set widgets in sidebar
			}
		}
		do_action( 'after_sidebar' );
		?>
	</div> <!-- /.sidebar -->
	<?php
}
?>