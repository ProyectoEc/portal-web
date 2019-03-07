<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_template_no_articles_theme_setup' ) ) {
	add_action( 'green_action_before_init_theme', 'green_template_no_articles_theme_setup', 1 );
	function green_template_no_articles_theme_setup() {
		green_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'green'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'green_template_no_articles_output' ) ) {
	function green_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'green'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'green' ); ?></p>
				<p><?php echo sprintf( wp_kses(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'green'), $GREEN_GLOBALS['allowed_tags'] ), esc_url( home_url( '/' ) ), get_bloginfo()); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'green'); ?></p>
				<?php echo do_shortcode('[trx_search open="fixed"]'); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>