<?php
/**
 * The template for displaying the footer.
 */

global $GREEN_GLOBALS;

				green_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (green_get_custom_option('body_style')!='fullscreen') green_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// User custom footer
			if (green_get_custom_option('show_user_footer') == 'yes') {
				$user_footer = green_strclear(green_get_custom_option('user_footer_content'), 'p');
				if (!empty($user_footer)) {
					$user_footer = green_substitute_all($user_footer);
					?>
					<footer class="user_footer_wrap"><div class="content_wrap"><?php echo ($user_footer); ?></div></footer>
					<?php
				}
			}


			// Footer sidebar
			$footer_show  = green_get_custom_option('show_sidebar_footer');
			$footer_parts = explode(' ', $footer_show);
			$footer_tint  = !empty($footer_parts[0]) ? $footer_parts[0] : 'dark';
			$footer_style = !empty($footer_parts[1]) ? $footer_parts[1] : $footer_tint;
			if (!green_sc_param_is_off($footer_show)) { 
				$GREEN_GLOBALS['current_sidebar'] = 'footer';
				?>
				<footer class="footer_wrap footer_wrap_none_p bg_tint_<?php echo esc_attr($footer_tint); ?> footer_style_<?php echo esc_attr($footer_style); ?> widget_area">
					<div class="content_wrap">
						<div class="columns_wrap">
						<?php
						do_action( 'before_sidebar' );
						if ( is_active_sidebar( green_get_custom_option('sidebar_footer') ) ) { //remove it so SB can work
							if ( ! dynamic_sidebar( green_get_custom_option('sidebar_footer') ) ) {
								// Put here html if user no set widgets in sidebar
							}
						}
						do_action( 'after_sidebar' );
						?>
						</div>	<!-- /.columns_wrap -->
					</div>	<!-- /.content_wrap -->
				</footer>	<!-- /.footer_wrap -->
			<?php
			}

			// Footer Testimonials stream
			$show = green_get_custom_option('show_testimonials_in_footer');
			if (!green_sc_param_is_off($show)) { 
				$count = max(1, green_get_custom_option('testimonials_count'));
				$data = do_shortcode('[trx_testimonials count="'.esc_attr($count).'"][/trx_testimonials]');
				if ($data) {
					$bg_image = green_get_custom_option('testimonials_bg_image');
					$bg_color = green_get_menu_color(green_get_custom_option('testimonials_bg_color'));
					$bg_overlay = max(0, min(1, green_get_custom_option('testimonials_bg_overlay')));
					if ($bg_overlay > 0) {
						$rgb = green_hex2rgb($bg_color);
						$over_color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['b'].','.(int)$rgb['b'].','.(float)$bg_overlay.')';
					}
					?>
					<footer class="testimonials_wrap sc_section bg_tint_<?php echo esc_attr($show); ?>" style="<?php echo ($bg_image ? 'background-image: url('.esc_url($bg_image).');' : '') . ($bg_color ? 'background-color:'.esc_attr($bg_color).';' : ''); ?>">
						<div class="sc_section_overlay" data-bg_color="<?php echo esc_attr($bg_color); ?>" data-overlay="<?php echo esc_attr($bg_overlay); ?>"<?php echo ($bg_overlay > 0 ? ' style="background-color:'. esc_attr($over_color).';"' : ''); ?>>
							<div class="content_wrap"><?php echo ($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Footer Twitter stream
			$show = green_get_custom_option('show_twitter_in_footer');
			if (!green_sc_param_is_off($show)) { 
				$count = max(1, green_get_custom_option('twitter_count'));
				$data = do_shortcode('[trx_twitter count="'.esc_attr($count).'"]');
				if ($data) {
					$bg_image = green_get_custom_option('twitter_bg_image');
					$bg_color = green_get_link_color(green_get_custom_option('twitter_bg_color'));
					$bg_overlay = max(0, min(1, green_get_custom_option('twitter_bg_overlay')));
					if ($bg_overlay > 0) {
						$rgb = green_hex2rgb($bg_color);
						$over_color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['b'].','.(int)$rgb['b'].','.(float)$bg_overlay.')';
					}
					?>
					<footer class="twitter_wrap sc_section bg_tint_<?php echo esc_attr($show); ?>" style="<?php echo ($bg_image ? 'background-image: url('.esc_url($bg_image).');' : '') . ($bg_color ? 'background-color:'.esc_url($bg_color).';' : ''); ?>">
						<div class="sc_section_overlay" data-bg_color="<?php echo esc_attr($bg_color); ?>" data-overlay="<?php echo esc_attr($bg_overlay); ?>"<?php echo ($bg_overlay > 0 ? ' style="background-color:'. esc_attr($over_color).';"' : ''); ?>>
							<div class="content_wrap"><?php echo ($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}

			// Footer contacts
			if (($contacts_style = green_get_custom_option('show_contacts_in_footer')) != 'hide'  ) { 
				$firsttext_contacts_in_footer = green_get_custom_option('firsttext_contacts_in_footer');
				$text_contacts_in_footer = green_get_custom_option('text_contacts_in_footer');
				
					?>
					<footer class="contacts_wrap bg_tint_<?php echo esc_attr($contacts_style); ?> contacts_style_<?php echo esc_attr($contacts_style); ?>">
						<div class="content_wrap">
							
							<div class="image_contacts">
								<p class="firsttext_contacts"><?php echo ($firsttext_contacts_in_footer); ?></p>
								<p class="text_contacts"><?php echo ($text_contacts_in_footer); ?></p>
							</div>
							
							<?php echo do_shortcode('[trx_contact_form custom="yes" align="none" animation="none"][trx_form_item type="button" label="Sign up" label_position="over" animation="none" css="float:right; overflow: hidden;"][trx_form_item type="text" name="email" label_position="over" animation="none" css="float:left" value="Enter Your Email"][/trx_contact_form]'); ?>
						</div>	<!-- /.content_wrap -->
					</footer>	<!-- /.contacts_wrap -->
					<?php
				
			}
			
			// Google map
			if ( green_get_custom_option('show_googlemap')=='yes' ) { 
				$map_address = green_get_custom_option('googlemap_address');
				$map_latlng  = green_get_custom_option('googlemap_latlng');
				$map_zoom    = green_get_custom_option('googlemap_zoom');
				$map_style   = green_get_custom_option('googlemap_style');
				$map_height  = green_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					echo do_shortcode('[trx_googlemap'
							. (!empty($map_address) ? ' address="'.esc_attr($map_address).'"' : '')
							. (!empty($map_latlng)  ? ' latlng="'.esc_attr($map_latlng).'"' : '')
							. (!empty($map_style)   ? ' style="'.esc_attr($map_style).'"' : '')
							. (!empty($map_zoom)    ? ' zoom="'.esc_attr($map_zoom).'"' : '')
							. (!empty($map_height)  ? ' height="'.esc_attr($map_height).'"' : '')
							. ']');
				}
			}

			// Copyright area
			if (green_get_custom_option('show_copyright_in_footer')=='yes') {
			?> 
				<div class="copyright_wrap">
					<div class="content_wrap">
						<div class="logo">
							<a href="<?php
                            $alt = basename($GREEN_GLOBALS['logo_footer']);
                            $alt = substr($alt,0,strlen($alt) - 4);
                            echo esc_url( home_url( '/' ) ); ?>"><?php echo ($GREEN_GLOBALS['logo_footer'] ? '<img src="'.esc_url($GREEN_GLOBALS['logo_footer']).'" alt="'.esc_html($alt).'">' : ''); ?><?php echo ($GREEN_GLOBALS['logo_text'] ? '<span class="logo_text">'.($GREEN_GLOBALS['logo_text']).'</span>' : ''); ?></a>
						</div>
						<?php
                        $green_copyright = green_get_custom_option('footer_copyright');
                        $green_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $green_copyright);
                        echo force_balance_tags(do_shortcode($green_copyright));
                        ?>
                        <?php
                        if (function_exists('trx_utils_require_shortcode')) {
                            echo do_shortcode('[trx_socials style="icons" size="small"][/trx_socials]');
                        }?>
					</div>
				</div>
			<?php } ?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->

<?php
if (green_get_custom_option('show_theme_customizer')=='yes') {
	require_once( green_get_file_dir('core/core.customizer/front.customizer.php') );
}
?>

<a href="#" class="scroll_to_top icon-angle-up-1" title="<?php esc_html__('Scroll to top', 'green'); ?>"></a>

<div class="custom_html_section">
<?php echo force_balance_tags(green_get_custom_option('custom_code')); ?>
</div>

<?php echo force_balance_tags(green_get_custom_option('gtm_code2')); ?>

<?php wp_footer(); ?>



</body>
</html>