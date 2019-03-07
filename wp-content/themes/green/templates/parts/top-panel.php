			<?php 
				// WP custom header
				$header_image = $header_image2 = $header_color = '';
				if ($top_panel_style=='dark') {
					if (($header_image = get_header_image()) == '') {
						$header_image = green_get_custom_option('top_panel_bg_image');
					}
					if (file_exists(green_get_file_dir('skins/'.($theme_skin).'/images/bg_over.png'))) {
						$header_image2 = green_get_file_url('skins/'.($theme_skin).'/images/bg_over.png');
					}
					$header_color = green_get_link_color(green_get_custom_option('top_panel_bg_color'));
				}

				$header_style = $top_panel_opacity!='transparent' && ($header_image!='' || $header_image2!='' || $header_color!='') 
					? ' style="background: ' 
						. ($header_image2!='' ? 'rgba(0,0,0,.2) url('.esc_url($header_image2).') repeat-x center bottom' : '')
						. ($header_image!=''  ? ($header_image2!='' ? ',' : '') . 'url('.esc_url($header_image).') repeat center top' : '') 
						. ($header_color!=''  ? ' '.esc_attr($header_color).';' : '')
						.'"' 
					: '';

				if ($top_panel_style=='light') {
					if (($header_image = get_header_image()) == '') {
						$header_image = green_get_custom_option('top_panel_bg_image');
					}
					if (file_exists(green_get_file_dir('skins/'.($theme_skin).'/images/bg_over.png'))) {
						$header_image2 = green_get_file_url('skins/'.($theme_skin).'/images/bg_over.png');
					}
					$header_color = green_get_link_color(green_get_custom_option('top_panel_bg_color'));
				}
				if ($header_image2!='' || $header_color != '' || $header_image!='') { 
					$header_style = ' style="' . ($header_image2!='' ? 'background-image: url('.esc_url($header_image2).');  
										background-repeat: repeat-x; background-position: center top;' : '')
									.($header_image!='' ? 'background-image: url('.esc_url($header_image).');' : '') 
									.($header_color ? ' background-color:'.esc_attr($header_color).';' : '') . '"';
				}
			?>

			<div class="top_panel_fixed_wrap"></div>

			<header class="top_panel_wrap bg_tint_<?php echo esc_attr($top_panel_style); ?>" <?php echo ($header_style); ?>>
				
				<?php if (green_get_custom_option('show_menu_user')=='yes') { ?>
					<div class="menu_user_wrap">
						<div class="content_wrap clearfix">
							<div class="menu_user_area menu_user_right menu_user_nav_area">
								<?php require_once( green_get_file_dir('templates/parts/user-panel.php') ); ?>
							</div>
							<?php if (green_get_custom_option('show_contact_info')=='yes') { ?>
							<div class="menu_user_area menu_user_left menu_user_contact_area"><?php echo force_balance_tags(trim(green_get_custom_option('contact_info'))); ?></div>
							<?php } ?>
							<?php if (green_get_custom_option('show_contact_info')=='yes') { ?>
							<div class="menu_user_area menu_user_left menu_user_contact_area contact_mob_phone"><?php echo force_balance_tags(trim(green_get_custom_option('contact_mob_phone'))); ?></div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<div class="menu_main_wrap logo_<?php echo esc_attr(green_get_custom_option('logo_align')); ?><?php echo ($GREEN_GLOBALS['logo_text'] ? ' with_text' : ''); ?>">
					<div class="content_wrap clearfix">
						<div class="logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php
                                $alt = basename($GREEN_GLOBALS['logo_fixed']);
                                $alt = substr($alt,0,strlen($alt) - 4);
                                echo !empty($GREEN_GLOBALS['logo_'.($logo_style)]) ? '<img src="'.esc_url($GREEN_GLOBALS['logo_'.($logo_style)]).'" class="logo_main" alt="'.esc_html($alt).'"><img src="'.esc_url($GREEN_GLOBALS['logo_fixed']).'" class="logo_fixed" alt="'.esc_html($alt).'">' : ''; ?><?php echo ($GREEN_GLOBALS['logo_text'] ? '<span class="logo_text">'.($GREEN_GLOBALS['logo_text']).'</span>' : ''); ?></a>
						</div>
						
						<?php 
							$button_after_menu = green_get_theme_option('button_after_menu');
							if ($button_after_menu != '') {
						?>
						<span class="button-after-menu sc_button sc_button_square sc_button_style_filled sc_button_bg_custom sc_button_size_mini"><?php echo ($button_after_menu); ?></span>
						<?php } ?>

                        <?php
                        if (function_exists('trx_utils_require_shortcode')) {
                            if (green_get_custom_option('show_search')=='yes') echo do_shortcode('[trx_search open="no" title=" "]'); }
                        ?>
						
						
						<a href="#" class="menu_main_responsive_button icon-menu-1"></a>
	
						<nav role="navigation" class="menu_main_nav_area">
							<?php
							if (empty($GREEN_GLOBALS['menu_main'])) $GREEN_GLOBALS['menu_main'] = green_get_nav_menu('menu_main');
							if (empty($GREEN_GLOBALS['menu_main'])) $GREEN_GLOBALS['menu_main'] = green_get_nav_menu();
							echo ($GREEN_GLOBALS['menu_main']);
							?>
						</nav>


					</div>
				</div>

			</header>
