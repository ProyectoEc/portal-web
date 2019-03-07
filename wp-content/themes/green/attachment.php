<?php
/**
Template Name: Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move green_set_post_views to the javascript - counter will work under cache system
	if (green_get_custom_option('use_ajax_views_counter')=='no') {
		green_set_post_views(get_the_ID());
	}

	green_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !green_sc_param_is_off(green_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>