<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_template_404_theme_setup' ) ) {
	add_action( 'green_action_before_init_theme', 'green_template_404_theme_setup', 1 );
	function green_template_404_theme_setup() {
		green_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'green_template_404_output' ) ) {
	function green_template_404_output() {
		global $GREEN_GLOBALS;
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<div class="box_nonepage left_nonepage">
					<h1 class="page_title"><?php wp_kses( esc_html_e( '&nbsp;', 'green' ), $GREEN_GLOBALS['allowed_tags'] ); ?></h1>
				</div>
				<div class="box_nonepage right_nonepage">
					<h2 class="page_subtitle"><?php esc_html_e("Couldn't find what you're looking for!", 'green'); ?></h2>
					<p class="page_description"><?php echo sprintf( wp_kses_data( __('Go back, or return to <a href="/">Home page</a> to choose a new page. 
						Please report any broken links to our team.', 'green'), $GREEN_GLOBALS['allowed_tags'] ), esc_url( home_url( '/' ) ) ); ?></p>
					<div class="page_search"><?php echo do_shortcode('[trx_search open="fixed"]'); ?></div>
				</div>
			</div>
		</article>
		<?php
	}
}
?>