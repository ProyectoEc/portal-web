<?php
/**
 * The template for displaying all single donations
 *
 * @package ThemeREX Donations
 * @since ThemeREX Donations 1.0
 */

get_header();

do_action('trx_donations_before_post');

while ( have_posts() ) { the_post();

	do_action('trx_donations_before_post_content');

	require themerex_plugins_get_file_dir('templates/content-single.php');

	do_action('trx_donations_after_post_content');

	// Previous/next post navigation.
	the_post_navigation( array(
		'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'themerex' ) . '</span> ' .
			'<span class="screen-reader-text">' . __( 'Next post:', 'themerex' ) . '</span> ' .
			'<h4 class="post-title">%title</h4>',
		'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'themerex' ) . '</span> ' .
			'<span class="screen-reader-text">' . __( 'Previous post:', 'themerex' ) . '</span> ' .
			'<h4 class="post-title">%title</h4>',
	) );

	do_action('trx_donations_after_post_navigation');

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		do_action('trx_donations_before_post_comments');
		comments_template();
		do_action('trx_donations_after_post_comments');
	}
}

do_action('trx_donations_after_post');

get_footer();
?>