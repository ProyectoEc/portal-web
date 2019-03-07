<?php
/**
 * default skin file for theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('green_skin_theme_setup')) {
	add_action( 'green_action_init_theme', 'green_skin_theme_setup', 1 );
	function green_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('green_filter_used_fonts',			'green_filter_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('green_filter_list_fonts',			'green_filter_list_fonts');

		// Add skin stylesheets
		add_action('green_action_add_styles',			'green_action_add_styles');
		// Add skin inline styles
		add_filter('green_filter_add_styles_inline',		'green_filter_add_styles_inline');
		// Add skin responsive styles
		add_action('green_action_add_responsive',		'green_action_add_responsive');
		// Add skin responsive inline styles
		add_filter('green_filter_add_responsive_inline',	'green_filter_add_responsive_inline');

		// Add skin scripts
		add_action('green_action_add_scripts',			'green_action_add_scripts');
		// Add skin scripts inline
		add_action('green_action_add_scripts_inline',	'green_action_add_scripts_inline');

		// Return links color (if not set in the theme options)
		add_filter('green_filter_get_link_color',		'green_filter_get_link_color', 10, 1);
		// Return links dark color
		add_filter('green_filter_get_link_dark',			'green_filter_get_link_dark',  10, 1);

		// Return main menu items color (if not set in the theme options)
		add_filter('green_filter_get_menu_color',		'green_filter_get_menu_color', 10, 1);
		// Return main menu items dark color
		add_filter('green_filter_get_menu_dark',			'green_filter_get_menu_dark',  10, 1);

		// Return user menu items color (if not set in the theme options)
		add_filter('green_filter_get_user_color',		'green_filter_get_user_color', 10, 1);
		// Return user menu items dark color
		add_filter('green_filter_get_user_dark',			'green_filter_get_user_dark',  10, 1);

		// Add color schemes
		green_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'green'),
		    /*
			// Main colors
			'accent1'				=> '#1eaace',		// rgb(30,170,206)
			'accent1_hover'			=> '#007c9c',		// rgb(0,124,156)
			'accent2'				=> '#1dbb90',		// rgb(29,187,144)
			'accent2_hover'			=> '#018763',		// rgb(1,135,99)
			'accent3'				=> '#ffb20e',		// rgb(255,178,14)
			'accent3_hover'			=> '#cc8b00',		// rgb(204,139,0) 
			'header'				=> '#222222',
			'header_link'			=> '#222222',
			'header_hover'			=> '#007c9c',
			'subheader'				=> '#222222',
			'subheader_link'		=> '#222222',
			'subheader_hover'		=> '#007c9c',
			'text'					=> '#666666',
			'text_link'				=> '#1eaace',
			'text_hover'			=> '#007c9c',
			'info'					=> '#222222',
			'info_link'				=> '#1eaace',
			'info_hover'			=> '#007c9c',
			'inverse'				=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			'border'				=> '#e4e7e8',
			'border_hover'			=> '#e4e7e8',
			'bg'					=> '#ffffff',
			'bg_hover'				=> '#ffffff',
			'shadow'				=> 'rgba(0, 0, 0, 0.2)',
			'shadow_hover'			=> 'rgba(0, 0, 0, 0.2)',
		
			// Highlight colors (menu items, form's fields, etc.)
			'highlight_text'		=> '#909090',
			'highlight_link'		=> '#1eaace',
			'highlight_hover'		=> '#007c9c',
			'highlight_bg'			=> '#f4f7f9',
			'highlight_bg_hover'	=> '#f4f7f9',
			'highlight_border'		=> '#f4f7f9',
			'highlight_border_hover'=> '#f4f7f9',
			'highlight_shadow'		=> 'rgba(0, 0, 0, 0.2)',
			'highlight_shadow_hover'=> 'rgba(0, 0, 0, 0.2)',
			*/
			// Old settings
			'menu_color' => '#9cc900',
			'menu_dark'  => '',
			'link_color' => '',
			'link_dark'  => '',
			'user_color' => '#313a42',
			'user_dark'  => ''
			)
		);
		green_add_color_scheme('contrast', array(
			'title'		 =>	__('Contrast', 'green'),
			// Old settings
			'menu_color' => '#adc244',
			'menu_dark'  => '',
			'link_color' => '',
			'link_dark'  => '',
			'user_color' => '',
			'user_dark'  => ''
			)
		);
		green_add_color_scheme('modern', array(
			'title'		 =>	__('Modern', 'green'),
			// Old settings
			'menu_color' => '#df8e4f',
			'menu_dark'  => '',
			'link_color' => '',
			'link_dark'  => '',
			'user_color' => '',
			'user_dark'  => ''
			)
		);
		green_add_color_scheme('pastel', array(
			'title'		 =>	__('Pastel', 'green'),
			// Old settings
			'menu_color' => '#10608e',
			'menu_dark'  => '',
			'link_color' => '',
			'link_dark'  => '',
			'user_color' => '',
			'user_dark'  => ''
			)
		);
	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('green_filter_used_fonts')) {
	//add_filter('green_filter_used_fonts', 'green_filter_used_fonts');
	function green_filter_used_fonts($theme_fonts) {
		$theme_fonts['Hind'] = 1;
		$theme_fonts['Grand Hotel'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('green_filter_list_fonts')) {
	//add_filter('green_filter_list_fonts', 'green_filter_list_fonts');
	function green_filter_list_fonts($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => green_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }

		if (!isset($list['Hind'])) {
				$list['Hind'] = array(
					'family' => 'sans-serif',																						// (required) font family
					'link'   => 'Hind:400,500,700,600,300',	// (optional) if you use Google font repository
					
					);
		}
		if (!isset($list['Grand Hotel'])) {
				$list['Grand Hotel'] = array(
					'family' => 'cursive',																						// (required) font family
					'link'   => 'Grand+Hotel',	// (optional) if you use Google font repository
					);
		}

		return $list;
	}
}


//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('green_action_add_styles')) {
	//add_action('green_action_add_styles', 'green_action_add_styles');
	function green_action_add_styles() {
		// Add stylesheet files
		green_enqueue_style( 'green-skin-style', green_get_file_url('skins/default/skin.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('green_filter_add_styles_inline')) {
	//add_filter('green_filter_add_styles_inline', 'green_filter_add_styles_inline');
	function green_filter_add_styles_inline($custom_style) {

		
		$scheme = ' ';

		global $GREEN_GLOBALS;

		// Links color
		$clr = green_get_custom_option('link_color');
		if (empty($clr) && $scheme!= 'original')	$clr = green_get_link_color();
		if (!empty($clr)) {
			$GREEN_GLOBALS['color_schemes'][$scheme]['link_color'] = $clr;
			$rgb = green_hex2rgb($clr);

			// .comments_wrap .comment-respond {
			// 	border-bottom-color: '.esc_attr($clr).'; 
			// }
			// .sc_team.sc_team_style_2 .sc_team_item_avatar .sc_team_item_hover 
			// {
			// 	background-color: rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.8);
			// }

			$custom_style .= '
				
				
			';
		}
		// Links dark color
		$clr_dark = green_get_custom_option('link_dark');
		if (empty($clr_dark) && $scheme!= 'original')	$clr_dark = green_get_link_dark();
		if (!empty($clr) || !empty($clr_dark)) {
			if (empty($clr_dark)) {
				$hsb = green_hex2hsb($clr);
				$hsb['s'] = min(100, $hsb['s'] + 15);
				$hsb['b'] = max(0, $hsb['b'] - 20);
				$clr = green_hsb2hex($hsb);
			} else
				$clr = $clr_dark;
			$GREEN_GLOBALS['color_schemes'][$scheme]['link_dark'] = $clr;
			//$rgb = green_hex2rgb($clr);
			$custom_style .= '
				
				
			';
		}


		// Menu color
		$clr = green_get_custom_option('menu_color');
		if (empty($clr) && $scheme!= 'original')	$clr = green_get_menu_color();
		if (!empty($clr)) {
			$GREEN_GLOBALS['color_schemes'][$scheme]['menu_color'] = $clr;
			$rgb = green_hex2rgb($clr);
			$custom_style .= '
				.menu_main_wrap .menu_main_nav > li:hover,
				.menu_main_wrap .menu_main_nav > li.sfHover,
				.menu_main_wrap .menu_main_nav > li#blob,
				.menu_main_wrap .menu_main_nav > li.current-menu-item,
				.menu_main_wrap .menu_main_nav > li.current-menu-parent,
				.menu_main_wrap .menu_main_nav > li.current-menu-ancestor,
				.menu_main_wrap .menu_main_nav > li ul,
				.container_footer .sc_button,
				.menu_main_wrap .menu_main_nav_area .menu_main_responsive .current-menu-item a 
				{
					background-color: rgba('.((int)$rgb['r'] + 8).','.((int)$rgb['g'] + 8 ).','.((int)$rgb['b'] + 8 ).', 1); 
				}
				.sidebar .widget_categories ul li::before,
				.widget_area ul li::before,
				.widget_area .widget_calendar .month_prev a,
				.widget_area .widget_calendar .month_next a,
				.sidebar.widget_area .widget_calendar .today .day_wrap,
				.widget_search,
				.widget .sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
				.widget .sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a,
				.scroll_to_top,
				.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcaps_item,
				blockquote.sc_quote_2,
				.sc_highlight_style_1,
				.sc_list_style_iconed.list-defined .sc_list_icon::before,
				.sc_accordion.sc_accordion_style_1 .sc_accordion_title.ui-state-active .sc_accordion_icon_closed,
				.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-tabs-active a,
				.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a:hover,
				.sc_table table tr:first-child th:first-child, 
				.sc_table table tr:first-child td:first-child,
				.sc_audio_container .mejs-container .mejs-playpause-button,
				.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,
				.sc_audio_container .mejs-container .mejs-controls .mejs-time-current,
				.sc_audio_container .mejs-container .mejs-controls .mejs-horizontal-volume-current,
				.sc_slider_controls_wrap a::before,
				.sc_button,
				.user_footer_wrap,
				.woocommerce a.button,
				.woocommerce a.button:hover,
				.sidebar .widget_price_filter,
				.woocommerce div.product form.cart .button,
				.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
				.post_author,
				.pagination_wrap.pagination_pages .active,
				.woocommerce table.cart thead th,
				.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
				.sc_scroll_controls_wrap a,
				#menu_user .menu_user_register a,
				#menu_user .menu_user_login a,
				#menu_user .menu_user_controls a,
				#menu_user .menu_user_logout a,
				.sc_scroll_bar .swiper-scrollbar-drag:before,
				.contacts_wrap .sc_contact_form_item_button button,
				.sc_countdown.sc_countdown_style_1 .sc_countdown_label,
				.sidebar.sidebar_left .widget_pages li.current_page_item,
				.isotope_filters a.active,
				.isotope_filters a:hover,
				div.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-handle,
				div.sc_donations_form_field_button input,
				.sc_emailer .sc_emailer_button,
				ul:not([class*="sc_"]) li::before,
				.list-defined li::before
				{
					background: '.esc_attr($clr).';
				}
				.custom_options #co_toggle
				{
					background: '.esc_attr($clr).' !important;
				}
				.sidebar .widget_categories ul li,
				.widget_area .post_content .post_info,
				.post_content .post_info .post_info_item a,
				.widget_area .widget_calendar .month_cur_today,
				.widget_area button:before,
				.widget_area .post_item .post_title:hover a,
				.sidebar .widget_rss .rss-date,
				.copyright_wrap p a,
				.color-title,
				.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item,
				.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcaps_item,
				blockquote.sc_quote::before,
				.sc_quote .sc_quote_title a,
				ol>li::before,
				.sc_socials .sc_socials_item a:hover,
				.sc_accordion.sc_accordion_style_1 .sc_accordion_title.ui-state-active,
				.sc_accordion.sc_accordion_style_1 .sc_accordion_title:hover,
				.sc_table table tr td:nth-child(2n+2),
				.sc_team_item .sc_team_item_position,
				.products li .post_content h3 a:hover,
				.woocommerce ul.products li.product .price,
				.sidebar .product-categories li .count,
				.woocommerce-page ul.product_list_widget li .amount,
				.woocommerce .product .amount,
				.woocommerce div.product .product_meta span a,
				.woocommerce div.product .product_meta .product_id span,
				.post_info_item a,
				.sc_chat .sc_chat_inner .sc_chat_title a,
				.post_info .post_info_counters .post_counters_item,
				.post_content .post_info_bottom .post_info_item.post_info_tags a,
				.comments_list_wrap .comment_info .comment_author,
				.comments_list_wrap .comment_info .comment_date_value,
				.comments_list_wrap .comment_reply::before,
				.comments_list_wrap .comment_reply a,
				.post_content .post_title:hover a,
				.title_gallery b,
				.woocommerce a.added_to_cart:hover,
				.woocommerce table.shop_table td.product-name a,
				.title_with_b b,
				.sc_testimonials .box-info-tes .sc_testimonial_author a,
				#menu_user li a,
				.menu_user_wrap .menu_user_left b,
				.bg_tint_light .menu_main_responsive_button,
				.sidebar.widget_area .widget_calendar thead .month_cur a,
				.sc_button.sc_button_style_filled.sc_button_style_2::after,
				.sc_team_item .sc_team_item_info .sc_team_item_title:hover a,
				.sc_infobox.sc_infobox_style_regular::before,
				.sc_infobox.sc_infobox_style_regular.warning::before,
				.sc_button.sc_button_size_mini.blogger-more::after
				{
					color: '.esc_attr($clr).';
				}
				.sc_highlight_style_2,
				.sc_socials_share .sc_socials_item a span,
				.sc_button.sc_button_style_filled.sc_button_style_2
				{
					border-color: '.esc_attr($clr).';
					color: '.esc_attr($clr).';
				}
				.sc_socials .sc_socials_item a:hover span,
				.comments_wrap .comments_field input:focus, 
				.comments_wrap .comments_field textarea:focus,
				.title_gallery::after,
				.sc_line_style_double,
				.bg_tint_dark .search_wrap.search_style_regular.search_opened
				{
					border-color: '.esc_attr($clr).';
				}
				.search_wrap.search_style_regular .search_form_wrap .search_submit,
				.search_wrap.search_style_regular .search_icon,
				.firsttext_contacts
				{
					color: '.esc_attr($clr).';
				}
				.sidebar.sidebar_left .widget_pages 
				{
					background-color: rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.9);
				}
				.post_content.ih-item.circle.effect1.colored .info, 
				.post_content.ih-item.circle.effect2.colored .info, 
				.post_content.ih-item.circle.effect5.colored .info .info-back, 
				.post_content.ih-item.circle.effect19.colored .info, 
				.post_content.ih-item.square.effect4.colored .mask1, 
				.post_content.ih-item.square.effect4.colored .mask2, 
				.post_content.ih-item.square.effect6.colored .info, 
				.post_content.ih-item.square.effect7.colored .info, 
				.post_content.ih-item.square.effect12.colored .info, 
				.post_content.ih-item.square.effect13.colored .info, 
				.post_content.ih-item.square.effect_dir.colored .info, 
				.post_content.ih-item.square.effect_shift.colored .info
				{
					background-color: rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.7);
				}
				.popup_wrap .popup_form_field.agree_field a,
				.remember_field a,
				.login_socials_problem a
				{
					color: '.esc_attr($clr).' !important;
				}
			';
		}
		
		// Menu dark color
		$clr_dark = green_get_custom_option('menu_dark');
		if (empty($clr_dark) && $scheme!= 'original')	$clr_dark = green_get_menu_dark();
		if (!empty($clr) || !empty($clr_dark)) {
			if (empty($clr_dark)) {
				$hsb = green_hex2hsb($clr);
				$hsb['s'] = min(100, $hsb['s'] + 15);
				$hsb['b'] = max(0, $hsb['b'] - 20);
				$clr = green_hsb2hex($hsb);
			} else
				$clr = $clr_dark;
			$GREEN_GLOBALS['color_schemes'][$scheme]['menu_dark'] = $clr;
			//$rgb = green_hex2rgb($clr);
			$custom_style .= '
				
				

			';
		}




		// User color
		$clr = green_get_custom_option('user_color');
		if (empty($clr) && $scheme!= 'original')	$clr = green_get_user_color();
		if (!empty($clr)) {
			$GREEN_GLOBALS['color_schemes'][$scheme]['user_color'] = $clr;
			$rgb = green_hex2rgb($clr);
			$custom_style .= '
				.page_top_wrap,
				.contacts_wrap.bg_tint_dark {
					background: '.esc_attr($clr).';
				}
				.top_panel_wrap.bg_tint_dark {
					background-color: rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].', 0.9);
				}
			';
		}
		// User dark color
		$clr_dark = green_get_custom_option('user_dark');
		if (empty($clr_dark) && $scheme!= 'original')	$clr_dark = green_get_user_dark();
		if (!empty($clr) || !empty($clr_dark)) {
			if (empty($clr_dark)) {
				$hsb = green_hex2hsb($clr);
				$hsb['s'] = min(100, $hsb['s'] + 15);
				$hsb['b'] = max(0, $hsb['b'] - 20);
				$clr = green_hsb2hex($hsb);
			} else
				$clr = $clr_dark;
			$GREEN_GLOBALS['color_schemes'][$scheme]['user_dark'] = $clr;
			//$rgb = green_hex2rgb($clr);
			$custom_style .= '
				
				
			';
		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('green_action_add_responsive')) {
	//add_action('green_action_add_responsive', 'green_action_add_responsive');
	function green_action_add_responsive() {
		if (file_exists(green_get_file_dir('skins/default/skin-responsive.css'))) 
			green_enqueue_style( 'theme-skin-responsive-style', green_get_file_url('skins/default/skin-responsive.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('green_filter_add_responsive_inline')) {
	//add_filter('green_filter_add_responsive_inline', 'green_filter_add_responsive_inline');
	function green_filter_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('green_action_add_scripts')) {
	//add_action('green_action_add_scripts', 'green_action_add_scripts');
	function green_action_add_scripts() {
		if (file_exists(green_get_file_dir('skins/default/skin.js')))
			green_enqueue_script( 'theme-skin-script', green_get_file_url('skins/default/skin.js'), array(), null );
		if (green_get_theme_option('show_theme_customizer') == 'yes' && file_exists(green_get_file_dir('skins/default/skin.customizer.js')))
			green_enqueue_script( 'theme-skin-customizer-script', green_get_file_url('skins/default/skin.customizer.js'), array(), null );
		if (file_exists(green_get_file_dir('fw/js/main_menu_hover.js')))
			green_enqueue_script( 'theme-skin-script', green_get_file_url('fw/js/main_menu_hover.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('green_action_add_scripts_inline')) {
	//add_action('green_action_add_scripts_inline', 'green_action_add_scripts_inline');
	function green_action_add_scripts_inline() {
		echo '<script type="text/javascript">'
			. 'jQuery(document).ready(function() {'
			. "if (GREEN_GLOBALS['theme_font']=='') GREEN_GLOBALS['theme_font'] = 'Roboto';"
			. "GREEN_GLOBALS['link_color'] = '" . green_get_link_color(green_get_custom_option('link_color')) . "';"
			. "GREEN_GLOBALS['menu_color'] = '" . green_get_menu_color(green_get_custom_option('menu_color')) . "';"
			. "GREEN_GLOBALS['user_color'] = '" . green_get_user_color(green_get_custom_option('user_color')) . "';"
			. "});"
			. "</script>";
	}
}


//------------------------------------------------------------------------------
// Get skin's colors
//------------------------------------------------------------------------------


// Return main theme bg color
if (!function_exists('green_filter_get_theme_bgcolor')) {
	//add_filter('green_filter_get_theme_bgcolor', 'green_filter_get_theme_bgcolor', 10, 1);
	function green_filter_get_theme_bgcolor($clr) {
		return '#ffffff';
	}
}



// Return link color (if not set in the theme options)
if (!function_exists('green_filter_get_link_color')) {
	//add_filter('green_filter_get_link_color', 'green_filter_get_link_color', 10, 1);
	function green_filter_get_link_color($clr) {
		return empty($clr) ? green_get_scheme_color('link_color') : $clr;
	}
}

// Return links dark color (if not set in the theme options)
if (!function_exists('green_filter_get_link_dark')) {
	//add_filter('green_filter_get_link_dark', 'green_filter_get_link_dark', 10, 1);
	function green_filter_get_link_dark($clr) {
		return empty($clr) ? green_get_scheme_color('link_dark') : $clr;
	}
}



// Return main menu color (if not set in the theme options)
if (!function_exists('green_filter_get_menu_color')) {
	//add_filter('green_filter_get_menu_color', 'green_filter_get_menu_color', 10, 1);
	function green_filter_get_menu_color($clr) {
		return empty($clr) ? green_get_scheme_color('menu_color') : $clr;
	}
}

// Return main menu dark color (if not set in the theme options)
if (!function_exists('green_filter_get_menu_dark')) {
	//add_filter('green_filter_get_menu_dark', 'green_filter_get_menu_dark', 10, 1);
	function green_filter_get_menu_dark($clr) {
		return empty($clr) ? green_get_scheme_color('menu_dark') : $clr;
	}
}



// Return user menu color (if not set in the theme options)
if (!function_exists('green_filter_get_user_color')) {
	//add_filter('green_filter_get_user_color', 'green_filter_get_user_color', 10, 1);
	function green_filter_get_user_color($clr) {
		return empty($clr) ? green_get_scheme_color('user_color') : $clr;
	}
}

// Return user menu dark color (if not set in the theme options)
if (!function_exists('green_filter_get_user_dark')) {
	//add_filter('green_filter_get_user_dark', 'green_filter_get_user_dark', 10, 1);
	function green_filter_get_user_dark($clr) {
		return empty($clr) ? green_get_scheme_color('user_dark') : $clr;
	}
}
?>