<?php
/**
 * The template for displaying donation's archive
 *
 * @package ThemeREX Donations
 * @since ThemeREX Donations 1.0
 */

$plugin = THEMEREX_Donations::get_instance();

if ( !function_exists('themerex_plugins_excerpt_more') ) {
	function themerex_plugins_excerpt_more( $more ) {
		return '&hellip;';
	}
	add_filter( 'excerpt_more',	'themerex_plugins_excerpt_more' );
}

get_header();

do_action('trx_donations_before_archive');

if ( have_posts() ) {
	?>
	<header class="page-header">
		<?php
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
	?>
	</header><!-- .page-header -->
	<?php

	do_action('trx_donations_before_archive_list');
	
	if ( ($columns = $plugin->get_option('blog_columns')) > 1)
		echo '<div class="' . ($plugin->get_option('columns_wrap_class')!='' ? esc_attr($plugin->get_option('columns_wrap_class')) : 'sc_donations_columns_wrap').'">';

	while ( have_posts() ) { the_post();
		require themerex_plugins_get_file_dir('templates/content-'.$plugin->get_option('blog_style').'.php');
	}

	if ( $columns > 1)
		echo '</div>';

	do_action('trx_donations_after_archive_list');

	// Previous/next page navigation.
	the_posts_pagination( array(
		'prev_text'          => __( 'Previous page', 'themerex' ),
		'next_text'          => __( 'Next page', 'themerex' ),
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'themerex' ) . ' </span>',
	) );

} else {

	// If no content, include the "No posts found" template.
	require themerex_plugins_get_file_dir('templates/content-none.php');

}

do_action('trx_donations_after_archive');

get_footer();

if ( function_exists('themerex_plugins_excerpt_more') ) {
	remove_filter( 'excerpt_more', 'themerex_plugins_excerpt_more' );
}
?>