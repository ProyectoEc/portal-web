<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_template_related_theme_setup' ) ) {
	add_action( 'green_action_before_init_theme', 'green_template_related_theme_setup', 1 );
	function green_template_related_theme_setup() {
		green_add_template(array(
			'layout' => 'related',
			'mode'   => 'blogger',
			'need_columns' => true,
			'title'  => esc_html__('Related posts /no columns/', 'green'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'green'),
			'w'		 => 400,
			'h'		 => 225
		));
		green_add_template(array(
			'layout' => 'related_2',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'title'  => esc_html__('Related posts /2 columns/', 'green'),
			'thumb_title'  => esc_html__('Large image (crop)', 'green'),
			'w'		 => 750,
			'h'		 => 422
		));
		green_add_template(array(
			'layout' => 'related_3',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'title'  => esc_html__('Related posts /3 columns/', 'green'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'green'),
			'w'		 => 400,
			'h'		 => 225
		));
		green_add_template(array(
			'layout' => 'related_4',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'title'  => esc_html__('Related posts /4 columns/', 'green'),
			'thumb_title'  => esc_html__('Small image (crop)', 'green'),
			'w'		 => 250,
			'h'		 => 141
		));
	}
}

// Template output
if ( !function_exists( 'green_template_related_output' ) ) {
	function green_template_related_output($post_options, $post_data) {
		$show_title = true;	//!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(4, empty($parts[1]) ? $post_options['columns_count'] : (int) $parts[1]));
		$tag = green_sc_in_shortcode_blogger(true) ? 'div' : 'article';
		//require(green_get_file_dir('templates/parts/reviews-summary.php'));
		if ($columns > 1) {
			?>
			<div class="<?php echo 'column-1_'.esc_attr($columns); ?> column_padding_bottom">
			<?php
		}
		?>
		<<?php echo ($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['number']); ?>">

			<div class="post_content">
				<?php if ($post_data['post_video'] || $post_data['post_thumb'] || $post_data['post_gallery']) { ?>
				<div class="post_featured">
					<?php require(green_get_file_dir('templates/parts/post-featured.php')); ?>
				</div>
				<?php } ?>

				<?php if ($show_title) { ?>
					<div class="post_content_wrap">
					<?php if (!isset($post_options['links']) || $post_options['links']) { ?>
						<h4 class="post_title ffffff"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo ($post_data['post_title']); ?></a></h4>
					<?php } else { ?>
						<h4 class="post_title"><?php echo ($post_data['post_title']); ?></h4>
					<?php }
					//echo ($reviews_summary);
					?>
					</div>
				<?php } ?>
				
				
	    		<?php 
	    			if (!isset($post_options['info']) || $post_options['info']) { 
	    				require(green_get_file_dir('templates/parts/post-info.php')); 
	     			}
     			?>
	
				<div class="blogger-descr">
	     			<?php 
		    			if ($post_options['descr'] > 0) {
		     					if ($post_data['post_protected']) {
		     						echo ($post_data['post_excerpt']);
		     					} else {
		     						if ($post_data['post_excerpt']) {
		     							echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(green_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : green_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
		     						}
		     						if (empty($post_options['readmore'])) $post_options['readmore'] = esc_html__('READ MORE', 'green');
		     						if (!green_sc_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {
		     							echo do_shortcode('[trx_button style="default" size="mini" class="blogger-more" link="'.esc_url($post_data['post_link']).'"][/trx_button]');
		     						}
		     					}
		     				
		     			}
	     			?>
				</div>


			</div>	<!-- /.post_content -->
		</<?php echo ($tag); ?>>	<!-- /.post_item -->
		<?php
		if ($columns > 1) {
			?>
			</div>
			<?php
		}
	}
}
?>