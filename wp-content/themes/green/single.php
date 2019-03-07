<?php
/**
Template Name: Single post
 */

get_header(); 

$single_style = green_get_custom_option('single_style');


while ( have_posts() ) { the_post();

	// Move green_set_post_views to the javascript - counter will work under cache system
		green_set_post_views(get_the_ID());

	//green_sc_clear_dedicated_content();
	green_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !green_sc_param_is_off(green_get_custom_option('show_sidebar_main')),
			'content' => green_get_template_property($single_style, 'need_content'),
			'terms_list' => green_get_template_property($single_style, 'need_terms')
		)
	);

}

get_footer();
?>