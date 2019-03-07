<?php

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_options_settings_theme_setup2' ) ) {
	add_action( 'green_action_after_init_theme', 'green_options_settings_theme_setup2', 1 );
	function green_options_settings_theme_setup2() {
		if (green_options_is_used()) {
			global $GREEN_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			foreach ($GREEN_GLOBALS['options'] as $k=>$v) {
				if (isset($v['options']) && is_array($v['options'])) {
					foreach ($v['options'] as $k1=>$v1) {
						if (green_substr($k1, 0, 7) == '$green_' || green_substr($v1, 0, 7) == '$green_') {
							$list_func = green_substr(green_substr($k1, 0, 7) == '$green_' ? $k1 : $v1, 1);
							unset($GREEN_GLOBALS['options'][$k]['options'][$k1]);
							if (isset($lists[$list_func]))
								$GREEN_GLOBALS['options'][$k]['options'] = green_array_merge($GREEN_GLOBALS['options'][$k]['options'], $lists[$list_func]);
							else {
								if (function_exists($list_func)) {
									$GREEN_GLOBALS['options'][$k]['options'] = $lists[$list_func] = green_array_merge($GREEN_GLOBALS['options'][$k]['options'], $list_func == 'green_get_list_menus' ? $list_func(true) : $list_func());
							   	} else
							   		echo sprintf(esc_html__('Wrong function name %s in the theme options array', 'green'), $list_func);
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'green_options_reset' ) ) {
	function green_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(green_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'green_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "green_options%"');
				// Add Templates Options
				if (file_exists(green_get_file_dir('demo/templates_options.txt'))) {
					$theme_options_txt = green_fgc(green_get_file_dir('demo/templates_options.txt'));
					$data = unserialize(  $theme_options_txt) ;
					// Replace upload url in options
					foreach ($data as $k=>$v) {
						foreach ($v as $k1=>$v1) {
							$v[$k1] = green_replace_uploads_url(green_replace_uploads_url($v1, 'uploads'), 'imports');
						}
						add_option( $k, $v, '', 'yes' );
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}

// Prepare default Theme Options
if ( !function_exists( 'green_options_settings_theme_setup' ) ) {
	add_action( 'green_action_before_init_theme', 'green_options_settings_theme_setup', 2 );	// Priority 1 for add green_filter handlers
	function green_options_settings_theme_setup() {
		global $GREEN_GLOBALS;
		
		// Remove 'false' to clear all saved Theme Options on next run.
		// Attention! Use this way only on new theme installation, not in updates!
		green_options_reset();
		
		// Prepare arrays 
		$GREEN_GLOBALS['options_params'] = array(
			'list_fonts'		=> array('$green_get_list_fonts' => ''),
			'list_fonts_styles'	=> array('$green_get_list_fonts_styles' => ''),
			'list_socials' 		=> array('$green_get_list_socials' => ''),
			'list_icons' 		=> array('$green_get_list_icons' => ''),
			'list_posts_types' 	=> array('$green_get_list_posts_types' => ''),
			'list_categories' 	=> array('$green_get_list_categories' => ''),
			'list_menus'		=> array('$green_get_list_menus' => ''),
			'list_sidebars'		=> array('$green_get_list_sidebars' => ''),
			'list_positions' 	=> array('$green_get_list_sidebars_positions' => ''),
			'list_tints'	 	=> array('$green_get_list_bg_tints' => ''),
			'list_sidebar_styles' => array('$green_get_list_sidebar_styles' => ''),
			'list_skins'		=> array('$green_get_list_skins' => ''),
			'list_color_schemes'=> array('$green_get_list_color_schemes' => ''),
			'list_body_styles'	=> array('$green_get_list_body_styles' => ''),
			'list_blog_styles'	=> array('$green_get_list_templates_blog' => ''),
			'list_single_styles'=> array('$green_get_list_templates_single' => ''),
			'list_article_styles'=> array('$green_get_list_article_styles' => ''),
			'list_animations_in' => array('$green_get_list_animations_in' => ''),
			'list_animations_out'=> array('$green_get_list_animations_out' => ''),
			'list_filters'		=> array('$green_get_list_portfolio_filters' => ''),
			'list_hovers'		=> array('$green_get_list_hovers' => ''),
			'list_hovers_dir'	=> array('$green_get_list_hovers_directions' => ''),
			'list_sliders' 		=> array('$green_get_list_sliders' => ''),
			'list_popups' 		=> array('$green_get_list_popup_engines' => ''),
			'list_gmap_styles' 	=> array('$green_get_list_googlemap_styles' => ''),
			'list_yes_no' 		=> array('$green_get_list_yesno' => ''),
			'list_on_off' 		=> array('$green_get_list_onoff' => ''),
			'list_show_hide' 	=> array('$green_get_list_showhide' => ''),
			'list_sorting' 		=> array('$green_get_list_sortings' => ''),
			'list_ordering' 	=> array('$green_get_list_orderings' => ''),
			'list_locations' 	=> array('$green_get_list_dedicated_locations' => '')
			);


		// Theme options array
		$GREEN_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'green'),
					"start" => "partitions",
					"override" => "category,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),


		// Customization -> General
		//-------------------------------------------------
		
		'customization_general' => array(
					"title" => esc_html__('General', 'green'),
					"override" => "category,page,post",
					"icon" => 'iconadmin-cog',
					"start" => "customization_tabs",
					"type" => "tab"
					),

		'info_custom_1' => array(
					"title" => esc_html__('Theme customization general parameters', 'green'),
					"desc" => esc_html__('Select main theme skin, customize colors and enable responsive layouts for the small screens', 'green'),
					"override" => "category,page,post",
					"type" => "info"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'green'),
					"desc" => esc_html__('Select skin for the theme decoration', 'green'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "default",
					"options" => $GREEN_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"icon" => array(
					"title" => esc_html__('Select icon', 'green'),
					"desc" => esc_html__('Select icon for output before post/category name in some layouts', 'green'),
					"override" => "category,post",
					"std" => "",
					"options" => $GREEN_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),

		"color_scheme" => array(
					"title" => esc_html__('Color scheme', 'green'),
					"desc" => esc_html__('Select predefined color scheme. Or set separate colors in fields below', 'green'),
					"override" => "category,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $GREEN_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"link_color" => array(
					"title" => esc_html__('Links color', 'green'),
					"desc" => esc_html__('Links color. Also used as background color for the page header area and some other elements', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"link_dark" => array(
					"title" => esc_html__('Links dark color', 'green'),
					"desc" => esc_html__('Used as background color for the buttons, hover states and some other elements', 'green'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"menu_color" => array(
					"title" => esc_html__('Main menu color', 'green'),
					"desc" => esc_html__('Used as background color for the active menu item, calendar item, tabs and some other elements', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"menu_dark" => array(
					"title" => esc_html__('Main menu dark color', 'green'),
					"desc" => esc_html__('Used as text color for the menu items (in the Light style), as background color for the selected menu item, etc.', 'green'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"user_color" => array(
					"title" => esc_html__('User menu color', 'green'),
					"desc" => esc_html__('Used as background color for the user menu items and some other elements', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"user_dark" => array(
					"title" => esc_html__('User menu dark color', 'green'),
					"desc" => esc_html__('Used as background color for the selected user menu item, etc.', 'green'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),


		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'green'),
					"desc" => esc_html__('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'green'),
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'green'),
					"desc" => esc_html__('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'green'),
					"divider" => false,
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'green'),
					"desc" => esc_html__('Do you want use extended animations effects on your site?', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__('Remember visitor\'s settings', 'green'),
					"desc" => esc_html__('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'green'),
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'green'),
					"desc" => esc_html__('Do you want use responsive layouts on small screen or still use main layout?', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'info_custom_2' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'green'),
					"desc" => esc_html__('Put here your custom CSS and JS code', 'green'),
					"override" => "category,page,post",
					"type" => "info"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your CSS code',  'green'),
					"desc" => esc_html__('Put here your css code to correct main theme styles',  'green'),
					"override" => "category,post,page",
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		'custom_code' => array(
					"title" => esc_html__('Your HTML/JS code',  'green'),
					"desc" => esc_html__('Put here your invisible html/js code: Google analitics, counters, etc',  'green'),
					"override" => "category,post,page",
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'green'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-picture',
					"type" => "tab"
					),
		
		'info_custom_3' => array(
					"title" => esc_html__('Body parameters', 'green'),
					"desc" => esc_html__('Background color, pattern and image used only for fixed body style.', 'green'),
					"override" => "category,post,page",
					"type" => "info"
					),
					
		'body_style' => array(
					"title" => esc_html__('Body style', 'green'),
					"desc" => wp_kses( __('Select body style:<br><b>boxed</b> - if you want use background color and/or image,<br><b>wide</b> - page fill whole window with centered content,<br><b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings),<br><b>fullscreen</b> - page content fill whole window without any paddings', 'green'), $GREEN_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "wide",
					"options" => $GREEN_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'green'),
					"desc" => esc_html__('Fill the body background with the solid color (white or grey) or leave it transparend to show background image (or video)', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'load_bg_image' => array(
					"title" => esc_html__('Load background image', 'green'),
					"desc" => esc_html__('Always load background images or only for boxed body style', 'green'),
					"override" => "category,post,page",
					"std" => "boxed",
					"size" => "medium",
					"options" => array(
						'boxed' => esc_html__('Boxed', 'green'),
						'always' => esc_html__('Always', 'green')
					),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'green'),
					"desc" => esc_html__('Body background color',  'green'),
					"override" => "category,post,page",
					"std" => "#bfbfbf",
					"type" => "color"
					),
		
		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'green'),
					"desc" => esc_html__('Select theme background pattern (first case - without pattern)',  'green'),
					"override" => "category,post,page",
					"std" => "",
					"options" => array(
						0 => green_get_file_url('/images/spacer.png'),
						1 => green_get_file_url('/images/bg/pattern_1.png'),
						2 => green_get_file_url('/images/bg/pattern_2.png'),
						3 => green_get_file_url('/images/bg/pattern_3.png'),
						4 => green_get_file_url('/images/bg/pattern_4.png'),
						5 => green_get_file_url('/images/bg/pattern_5.png'),
						6 => green_get_file_url('/images/bg/pattern_6.png'),
						7 => green_get_file_url('/images/bg/pattern_7.png'),
						8 => green_get_file_url('/images/bg/pattern_8.png'),
						9 => green_get_file_url('/images/bg/pattern_9.png')
					),
					"style" => "list",
					"type" => "images"
					),

		'bg_custom_pattern' => array(
					"title" => esc_html__('Background custom pattern',  'green'),
					"desc" => esc_html__('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"
					),

		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'green'),
					"desc" => esc_html__('Select theme background image (first case - without image)',  'green'),
					"override" => "category,post,page",
					"std" => "",
					"options" => array(
						0 => green_get_file_url('/images/spacer.png'),
						1 => green_get_file_url('/images/bg/image_1_thumb.jpg'),
						2 => green_get_file_url('/images/bg/image_2_thumb.jpg'),
						3 => green_get_file_url('/images/bg/image_3_thumb.jpg'),
						4 => green_get_file_url('/images/bg/image_4_thumb.jpg'),
						5 => green_get_file_url('/images/bg/image_5_thumb.jpg'),
						6 => green_get_file_url('/images/bg/image_6_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),

		'bg_custom_image' => array(
					"title" => esc_html__('Background custom image',  'green'),
					"desc" => esc_html__('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"
					),

		'bg_custom_image_position' => array( 
					"title" => esc_html__('Background custom image position',  'green'),
					"desc" => esc_html__('Select custom image position',  'green'),
					"override" => "category,post,page",
					"std" => "left_top",
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'green'),
					"desc" => esc_html__("Show video on the site background (only for Fullscreen body style)", 'green'),
					"override" => "category,post,page",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'green'),
					"desc" => esc_html__("Youtube code of video", 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'green'),
					"desc" => esc_html__("URL to video-file (uploaded on your site)", 'green'),
					"readonly" =>false,
					"override" => "category,post,page",
					"before" => array(	'title' => esc_html__('Choose video', 'green'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'green'),
															'update' => esc_html__( 'Select Video', 'green')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'green'),
					"desc" => esc_html__('Use overlay texture for the video background', 'green'),
					"override" => "category,post,page",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		// Customization -> Logo
		//-------------------------------------------------
		
		'customization_logo' => array(
					"title" => esc_html__('Logo', 'green'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-heart',
					"type" => "tab"
					),
		
		'info_custom_4' => array(
					"title" => esc_html__('Main logo', 'green'),
					"desc" => esc_html__('Select or upload logos for the site\'s header and select it position', 'green'),
					"override" => "category,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => esc_html__('Favicon', 'green'),
					"desc" => wp_kses( __('Upload a 16px x 16px image that will represent your website\'s favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (http://www.favicon.cc/)</em>', 'green'), $GREEN_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_dark' => array(
					"title" => esc_html__('Logo image (dark header)', 'green'),
					"desc" => esc_html__('Main logo image for the dark header', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_light' => array(
					"title" => esc_html__('Logo image (light header)', 'green'),
					"desc" => esc_html__('Main logo image for the light header', 'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'green'),
					"desc" => esc_html__('Logo image for the header (if menu is fixed after the page is scrolled)', 'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),
		
		'logo_from_skin' => array(
					"title" => esc_html__('Logo from skin',  'green'),
					"desc" => esc_html__("Use logo images from current skin folder if not filled out fields above", 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'green'),
					"desc" => esc_html__('Logo text - display it after logo image', 'green'),
					"override" => "category,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => esc_html__('Logo slogan', 'green'),
					"desc" => esc_html__('Logo slogan - display it under logo image (instead the site slogan)', 'green'),
					"override" => "category,post,page",
					"std" => '',
					"type" => "hidden"  //text
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'green'),
					"desc" => esc_html__('Height for the logo in the header area', 'green'),
					"override" => "category,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'green'),
					"desc" => esc_html__('Top offset for the logo in the header area', 'green'),
					"override" => "category,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),

		'logo_align' => array(
					"title" => esc_html__('Logo alignment', 'green'),
					"desc" => esc_html__('Logo alignment (only if logo above menu)', 'green'),
					"override" => "category,post,page",
					"std" => "left",
					"options" =>  array("left"=>esc_html__("Left", 'green'), "center"=>esc_html__("Center", 'green'), "right"=>esc_html__("Right", 'green')),
					"dir" => "horizontal",
					"type" => "hidden"  //checklist
					),

		'iinfo_custom_5' => array(
					"title" => esc_html__('Logo for footer', 'green'),
					"desc" => esc_html__('Select or upload logos for the site\'s footer and set it height', 'green'),
					"override" => "category,post,page",
					"type" => "hidden"  //info
					),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'green'),
					"desc" => esc_html__('Logo image for the footer', 'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"  
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'green'),
					"desc" => esc_html__('Height for the logo in the footer area (in contacts)', 'green'),
					"override" => "category,post,page",
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"  
					),
		
		
		
		// Customization -> Menus
		//-------------------------------------------------
		
		"customization_menus" => array(
					"title" => esc_html__('Menus', 'green'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-menu',
					"type" => "tab"),
		
		"info_custom_6" => array(
					"title" => esc_html__('Top panel', 'green'),
					"desc" => esc_html__('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'green'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'green'),
					"desc" => esc_html__('Select position for the top panel with logo and main menu', 'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'green'),
						'above' => esc_html__('Above slider', 'green'),
						'below' => esc_html__('Below slider', 'green'),
						'over'  => esc_html__('Over slider', 'green')
					),
					"type" => "checklist"),
		
		"top_panel_style" => array( 
					"title" => esc_html__('Top panel style', 'green'),
					"desc" => esc_html__('Select background style for the top panel with logo and main menu', 'green'),
					"override" => "category,post,page",
					"std" => "dark",
					"options" => array(
						'dark' => esc_html__('Dark', 'green'),
						'light' => esc_html__('Light', 'green')
					),
					"type" => "checklist"),
		
		"top_panel_opacity" => array( 
					"title" => esc_html__('Top panel opacity', 'green'),
					"desc" => esc_html__('Select background opacity for the top panel with logo and main menu', 'green'),
					"override" => "category,post,page",
					"std" => "solid",
					"options" => array(
						'solid' => esc_html__('Solid', 'green'),
						'transparent' => esc_html__('Transparent', 'green')
					),
					"type" => "checklist"),
		
		'top_panel_bg_color' => array(
					"title" => esc_html__('Top panel bg color',  'green'),
					"desc" => esc_html__('Background color for the top panel',  'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"
					),
		
		"top_panel_bg_image" => array( 
					"title" => esc_html__('Top panel bg image', 'green'),
					"desc" => esc_html__('Upload top panel background image', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "media"),
		
		
		"info_custom_7" => array( 
					"title" => esc_html__('Main menu style and position', 'green'),
					"desc" => esc_html__('Select the Main menu style and position', 'green'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'green'),
					"desc" => esc_html__('Select main menu for the current page',  'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "default",
					"options" => $GREEN_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_position" => array( 
					"title" => esc_html__('Main menu position', 'green'),
					"desc" => esc_html__('Attach main menu to top of window then page scroll down', 'green'),
					"override" => "category,post,page",
					"std" => "fixed",
					"options" => array("fixed"=>esc_html__("Fix menu position", 'green'), "none"=>esc_html__("Don't fix menu position", 'green')),
					"dir" => "vertical",
					"type" => "radio"),
		
		"menu_align" => array( 
					"title" => esc_html__('Main menu alignment', 'green'),
					"desc" => esc_html__('Main menu alignment', 'green'),
					"override" => "category,post,page",
					"std" => "center",
					"options" => array(
						"left"   => esc_html__("Left (under logo)", 'green'),
						"center" => esc_html__("Center (under logo)", 'green'),
						"right"	 => esc_html__("Right (at same line with logo)", 'green')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'green'),
					"desc" => esc_html__('Use slider background for main menu items', 'green'),
					"std" => "no",
					"type" => "switch",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'green'),
					"desc" => esc_html__('Select animation to show submenu ', 'green'),
					"std" => "fadeIn",
					"type" => "select",
					"options" => $GREEN_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'green'),
					"desc" => esc_html__('Select animation to hide submenu ', 'green'),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => $GREEN_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'green'),
					"desc" => esc_html__('Allow relayout main menu if window width less then this value', 'green'),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'green'),
					"desc" => esc_html__('Allow responsive version for the main menu if window width less then this value', 'green'),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'green'),
					"desc" => esc_html__('Width for dropdown menus in main menu', 'green'),
					"override" => "category,post,page",
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_custom_8" => array(
					"title" => esc_html__("User's menu area components", 'green'),
					"desc" => esc_html__("Select parts for the user's menu area", 'green'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"show_menu_user" => array(
					"title" => esc_html__('Show user menu area', 'green'),
					"desc" => esc_html__('Show user menu area on top of page', 'green'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'green'),
					"desc" => esc_html__('Select user menu for the current page',  'green'),
					"override" => "category,post,page",
					"std" => "default",
					"options" => $GREEN_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_contact_info" => array(
					"title" => esc_html__('Show contact info', 'green'),
					"desc" => esc_html__("Show the contact details for the owner of the site at the top left corner of the page", 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'green'),
					"desc" => esc_html__('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'green'),
					"desc" => esc_html__('Show Login and Logout buttons in the user menu area', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		

		
		"info_custom_9" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'green'),
					"desc" => esc_html__("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'green'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'green'),
					"desc" => esc_html__('Show TOC for the current page', 'green'),
					"override" => "category,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'green'),
						'fixed' => esc_html__('Fixed', 'green'),
						'float' => esc_html__('Float', 'green')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'green'),
					"desc" => esc_html__('Automatically add "Home" item into table of contents - return to home page of the site', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'green'),
					"desc" => esc_html__('Automatically add "To Top" item into table of contents - scroll to top of the page', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'green'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,post,page",
					"type" => "tab"),
		
		"info_custom_10" => array( 
					"title" => esc_html__('Custom sidebars', 'green'),
					"desc" => esc_html__('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'green'),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'green'),
					"desc" => esc_html__('Manage custom sidebars. You can use it with each category (page, post) independently',  'green'),
					"divider" => false,
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_custom_11" => array(
					"title" => esc_html__('Sidebars settings', 'green'),
					"desc" => esc_html__('Show / Hide and Select sidebar in each location', 'green'),
					"override" => "category,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'green'),
					"desc" => esc_html__('Select style for the main sidebar or hide it',  'green'),
					"override" => "category,post,page",
					"std" => "light",
					"options" => $GREEN_GLOBALS['options_params']['list_sidebar_styles'],
					"dir" => "horizontal",
					"type" => "checklist"),
		
		'sidebar_main_position' => array( 
					"title" => esc_html__('Main sidebar position',  'green'),
					"desc" => esc_html__('Select main sidebar position on blog page',  'green'),
					"override" => "category,post,page",
					"std" => "right",
					"options" => $GREEN_GLOBALS['options_params']['list_positions'],
					"size" => "medium",
					"type" => "switch"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'green'),
					"desc" => esc_html__('Select main sidebar for the blog page',  'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "sidebar_main",
					"options" => $GREEN_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'green'),
					"desc" => esc_html__('Select style for the footer sidebar or hide it', 'green'),
					"override" => "category,post,page",
					"std" => "light",
					"options" => $GREEN_GLOBALS['options_params']['list_sidebar_styles'],
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'green'),
					"desc" => esc_html__('Select footer sidebar for the blog page',  'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "sidebar_footer",
					"options" => $GREEN_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'green'),
					"desc" => esc_html__('Select columns number for the footer sidebar',  'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),





		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'green'),
					"icon" => "iconadmin-picture",
					"override" => "category,page",
					"type" => "tab"),
		
		"info_custom_13" => array(
					"title" => esc_html__('Main slider parameters', 'green'),
					"desc" => esc_html__('Select parameters for main slider (you can override it in each category and page)', 'green'),
					"override" => "category,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'green'),
					"desc" => esc_html__('Do you want to show slider on each page (post)', 'green'),
					"divider" => false,
					"override" => "category,page",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'green'),
					"desc" => esc_html__('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'green'),
					"override" => "category,page",
					"std" => "none",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'green'),
						"fullwide"=>esc_html__("Fullwide", 'green'),
						"fullscreen"=>esc_html__("Fullscreen", 'green')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'green'),
					"desc" => esc_html__("Slider height (in pixels) - only if slider display with fixed height.", 'green'),
					"override" => "category,page",
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'green'),
					"desc" => esc_html__('What engine use to show slider?', 'green'),
					"override" => "category,page",
					"std" => "flex",
					"options" => $GREEN_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_alias" => array(
					"title" => esc_html__('Layer Slider: Alias (for Revolution) or ID (for Royal)',  'green'),
					"desc" => esc_html__("Revolution Slider alias or Royal Slider ID (see in slider settings on plugin page)", 'green'),
					"override" => "category,page",
					"std" => "",
					"type" => "text"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'green'),
					"desc" => esc_html__('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'green'),
					"override" => "category,page",
					"std" => "",
					"options" => green_array_merge(array(0 => esc_html__('- Select category -', 'green')), $GREEN_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'green'),
					"desc" => esc_html__("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'green'),
					"override" => "category,page",
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'green'),
					"desc" => esc_html__("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'green'),
					"override" => "category,page",
					"std" => "date",
					"options" => $GREEN_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'green'),
					"desc" => esc_html__('Select the desired ordering method for posts', 'green'),
					"override" => "category,page",
					"std" => "desc",
					"options" => $GREEN_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'green'),
					"desc" => esc_html__("Interval (in ms) for slides change in slider", 'green'),
					"override" => "category,page",
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'green'),
					"desc" => esc_html__("Choose pagination style for the slider", 'green'),
					"override" => "category,page",
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'green'),
						'yes'  => esc_html__('Dots', 'green'), 
						'over' => esc_html__('Titles', 'green')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'green'),
					"desc" => esc_html__("Do you want to show post's title, reviews rating and description on slides in slider", 'green'),
					"override" => "category,page",
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'green'),
						'slide' => esc_html__('Slide', 'green'), 
						'fixed' => esc_html__('Fixed', 'green')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'green'),
					"desc" => esc_html__("Do you want to show post's category on slides in slider", 'green'),
					"override" => "category,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'green'),
					"desc" => esc_html__("Do you want to show post's reviews rating on slides in slider", 'green'),
					"override" => "category,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'green'),
					"desc" => esc_html__("How many characters show in the post's description in slider. 0 - no descriptions", 'green'),
					"override" => "category,page",
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		// Customization -> Header & Footer
		//-------------------------------------------------
		
		'customization_header_footer' => array(
					"title" => esc_html__("Header &amp; Footer", 'green'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Header settings", 'green'),
					"desc" => esc_html__("Select components of the page header, set style and put the content for the user's header area", 'green'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"show_user_header" => array(
					"title" => esc_html__("Show user's header", 'green'),
					"desc" => esc_html__("Show custom user's header", 'green'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"user_header_content" => array(
					"title" => esc_html__("User's header content", 'green'),
					"desc" => esc_html__('Put header html-code and/or shortcodes here. You can use any html-tags and shortcodes', 'green'),
					"override" => "categor,page,post",
					"std" => "",
					"rows" => "10",
					"type" => "editor"),
		
		"show_page_top" => array(
					"title" => esc_html__('Show Top of page section', 'green'),
					"desc" => esc_html__('Show top section with post/page/category title and breadcrumbs', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"button_after_menu" => array(
					"title" => esc_html__('Button after menu', 'green'),
					"desc" => esc_html__('Link and Text button after menu', 'green'),
					"std" => "",
					"type" => "text"),

		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'green'),
					"desc" => esc_html__('Show post/page/category title', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'green'),
					"desc" => esc_html__('Show path to current category (post, page)', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'green'),
					"desc" => esc_html__("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'green'),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),
		
		
		
		
		"info_footer_2" => array(
					"title" => esc_html__("Footer settings", 'green'),
					"desc" => esc_html__("Select components of the footer, set style and put the content for the user's footer area", 'green'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"show_user_footer" => array(
					"title" => esc_html__("Show user's footer", 'green'),
					"desc" => esc_html__("Show custom user's footer", 'green'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"user_footer_content" => array(
					"title" => esc_html__("User's footer content", 'green'),
					"desc" => esc_html__('Put footer html-code and/or shortcodes here. You can use any html-tags and shortcodes', 'green'),
					"override" => "category,page,post",
					"std" => "",
					"rows" => "10",
					"type" => "editor"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'green'),
					"desc" => esc_html__('Show contact information area in footer: site logo, contact info and large social icons', 'green'),
					"override" => "category,post,page",
					"std" => "hide", //light
					"options" => array(
						'hide' 	=> esc_html__('Hide', 'green'),
						'light'	=> esc_html__('Light', 'green'),
						'dark'	=> esc_html__('Dark', 'green')
					),
					"dir" => "horizontal",
					"type" => "checklist"),  //checklist

		"firsttext_contacts_in_footer" => array(
					"title" => esc_html__('First Text to show before form',  'green'),
					"desc" => esc_html__("Enter First text before form", 'green'),
					"override" => "category,page,post",
					"std" => "",
					"type" => "text"),

		"text_contacts_in_footer" => array(
					"title" => esc_html__('Text to show before form',  'green'),
					"desc" => esc_html__("Enter text before form", 'green'),
					"override" => "category,page,post",
					"std" => "",
					"type" => "text"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'green'),
					"desc" => esc_html__('Show area with copyright information and small social icons in footer', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'green'),
					"desc" => esc_html__("Copyright text to show in footer area (bottom of site)", 'green'),
					"override" => "category,page,post",
					"std" => "AncoraThemes &copy; {Y}. All rights reserved.",
					"rows" => "10",
					"type" => "editor"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Testimonials in Footer', 'green'),
					"desc" => esc_html__('Select parameters for Testimonials in the Footer (you can override it in each category and page)', 'green'),
					"override" => "category,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'green'),
					"desc" => esc_html__('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "none",
					"options" => $GREEN_GLOBALS['options_params']['list_tints'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'green'),
					"desc" => esc_html__('Number testimonials to show', 'green'),
					"override" => "category,post,page",
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),

		"testimonials_bg_image" => array( 
					"title" => esc_html__('Testimonials bg image', 'green'),
					"desc" => esc_html__('Select image or put image URL from other site to use it as testimonials block background', 'green'),
					"override" => "category,post,page",
					"readonly" => false,
					"std" => "",
					"type" => "media"),

		"testimonials_bg_color" => array( 
					"title" => esc_html__('Testimonials bg color', 'green'),
					"desc" => esc_html__('Select color to use it as testimonials block background', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "color"),

		"testimonials_bg_overlay" => array( 
					"title" => esc_html__('Testimonials bg overlay', 'green'),
					"desc" => esc_html__('Select background color opacity to create overlay effect on background', 'green'),
					"override" => "category,post,page",
					"std" => 0,
					"step" => 0.1,
					"min" => 0,
					"max" => 1,
					"type" => "spinner"),
		
		
		"info_footer_4" => array(
					"title" => esc_html__('Twitter in Footer', 'green'),
					"desc" => esc_html__('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'green'),
					"override" => "category,page,post",
					"type" => "hidden"),  // info

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'green'),
					"desc" => esc_html__('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'green'),
					"override" => "category,post,page",
					"divider" => false,
					"std" => "none",
					"options" => $GREEN_GLOBALS['options_params']['list_tints'],
					"type" => "hidden"),  //checklist

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'green'),
					"desc" => esc_html__('Number twitter to show', 'green'),
					"override" => "category,post,page",
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "hidden"),  //spinner

		"twitter_bg_image" => array( 
					"title" => esc_html__('Twitter bg image', 'green'),
					"desc" => esc_html__('Select image or put image URL from other site to use it as Twitter block background', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "hidden"),  //media

		"twitter_bg_color" => array( 
					"title" => esc_html__('Twitter bg color', 'green'),
					"desc" => esc_html__('Select color to use it as Twitter block background', 'green'),
					"override" => "category,post,page",
					"std" => "",
					"type" => "hidden"), //color

		"twitter_bg_overlay" => array( 
					"title" => esc_html__('Twitter bg overlay', 'green'),
					"desc" => esc_html__('Select background color opacity to create overlay effect on background', 'green'),
					"override" => "category,post,page",
					"std" => 0,
					"step" => 0.1,
					"min" => 0,
					"max" => 1,
					"type" => "hidden"),  //spinner


		"info_footer_5" => array(
					"title" => esc_html__('Google map parameters', 'green'),
					"desc" => esc_html__('Select parameters for Google map (you can override it in each category and page)', 'green'),
					"override" => "category,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'green'),
					"desc" => esc_html__('Do you want to show Google map on each page (post)', 'green'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'green'),
					"desc" => esc_html__("Map height (default - in pixels, allows any CSS units of measure)", 'green'),
					"override" => "category,page",
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'green'),
					"desc" => esc_html__("Enter address to show on map center", 'green'),
					"override" => "category,page,post",
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longtitude to show on map',  'green'),
					"desc" => esc_html__("Enter coordinates (separated by comma) to show on map center (instead of address)", 'green'),
					"override" => "category,page,post",
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'green'),
					"desc" => esc_html__("Enter desired initial zoom for Google map", 'green'),
					"override" => "category,page,post",
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'green'),
					"desc" => esc_html__("Select style to show Google map", 'green'),
					"override" => "category,page,post",
					"std" => 'style1',
					"options" => $GREEN_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'green'),
					"desc" => esc_html__("Select or upload png-image with Google map marker", 'green'),
					"std" => '',
					"type" => "media"),
		
		
		
		
		// Customization -> Media
		//-------------------------------------------------
		
		'customization_media' => array(
					"title" => esc_html__('Media', 'green'),
					"override" => "category,post,page",
					"icon" => 'iconadmin-picture',
					"type" => "tab"),
		
		"info_media_1" => array(
					"title" => esc_html__('Retina ready', 'green'),
					"desc" => esc_html__("Additional parameters for the Retina displays", 'green'),
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'green'),
					"desc" => esc_html__('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'green'),
					"divider" => false,
					"std" => "1",
					"size" => "medium",
					"options" => array("1"=>esc_html__("Original", 'green'), "2"=>esc_html__("Retina", 'green')),
					"type" => "switch"),
		
		"info_media_2" => array(
					"title" => esc_html__('Media Substitution parameters', 'green'),
					"desc" => esc_html__("Set up the media substitution parameters and slider's options", 'green'),
					"override" => "category,page,post",
					"type" => "info"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'green'),
					"desc" => esc_html__('Substitute standard Wordpress gallery with our slider on the single pages', 'green'),
					"divider" => false,
					"override" => "category,post,page",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"substitute_slider_engine" => array(
					"title" => esc_html__('Substitution Slider engine', 'green'),
					"desc" => esc_html__('What engine use to show slider instead standard gallery?', 'green'),
					"override" => "category,post,page",
					"std" => "swiper",
					"options" => array(
						//"chop" => esc_html__("Chop slider", 'green'),
						"swiper" => esc_html__("Swiper slider", 'green')
					),
					"type" => "radio"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'green'),
					"desc" => esc_html__('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'green'),
					"desc" => esc_html__('Maximum images number from gallery into slider', 'green'),
					"override" => "category,post,page",
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Gallery popup engine', 'green'),
					"desc" => esc_html__('Select engine to show popup windows with galleries', 'green'),
					"std" => "magnific",
					"options" => $GREEN_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"popup_gallery" => array(
					"title" => esc_html__('Enable Gallery mode in the popup', 'green'),
					"desc" => esc_html__('Enable Gallery mode in the popup or show only single image', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'green'),
					"desc" => esc_html__('Substitute audio tag with source from soundcloud to embed player', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'green'),
					"desc" => esc_html__('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'green'),
					"desc" => esc_html__('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		// Customization -> Typography
		//-------------------------------------------------
		
		'customization_typography' => array(
					"title" => esc_html__("Typography", 'green'),
					"icon" => 'iconadmin-font',
					"type" => "tab"),
		
		"info_typo_1" => array(
					"title" => esc_html__('Typography settings', 'green'),
					"desc" => wp_kses( __('Select fonts, sizes and styles for the headings and paragraphs. You can use Google fonts and custom fonts.<br><br>How to install custom @font-face fonts into the theme?<br>All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!<br>Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.<br>Create your @font-face kit by using www.fontsquirrel.com/fontface/generator and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install.', 'green'), $GREEN_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"typography_custom" => array(
					"title" => esc_html__('Use custom typography', 'green'),
					"desc" => esc_html__('Use custom font settings or leave theme-styled fonts', 'green'),
					"divider" => false,
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"typography_h1_font" => array(
					"title" => esc_html__('Heading 1', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $GREEN_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h1_size" => array(
					"title" => esc_html__('Size', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "48",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h1_lineheight" => array(
					"title" => esc_html__('Line height', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "60",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h1_weight" => array(
					"title" => esc_html__('Weight', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h1_style" => array(
					"title" => esc_html__('Style', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $GREEN_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h1_color" => array(
					"title" => esc_html__('Color', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h2_font" => array(
					"title" => esc_html__('Heading 2', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $GREEN_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h2_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "36",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h2_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "43",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h2_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h2_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $GREEN_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h2_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h3_font" => array(
					"title" => esc_html__('Heading 3', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $GREEN_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h3_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "24",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h3_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "28",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h3_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h3_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $GREEN_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h3_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h4_font" => array(
					"title" => esc_html__('Heading 4', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $GREEN_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h4_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "20",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h4_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "24",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h4_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h4_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $GREEN_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h4_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h5_font" => array(
					"title" => esc_html__('Heading 5', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $GREEN_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h5_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "18",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h5_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "20",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h5_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h5_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $GREEN_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h5_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_h6_font" => array(
					"title" => esc_html__('Heading 6', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Signika",
					"options" => $GREEN_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_h6_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "16",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_h6_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "18",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_h6_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "400",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_h6_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $GREEN_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_h6_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		"typography_p_font" => array(
					"title" => esc_html__('Paragraph text', 'green'),
					"desc" => '',
					"divider" => false,
					"columns" => "3_8 first",
					"std" => "Source Sans Pro",
					"options" => $GREEN_GLOBALS['options_params']['list_fonts'],
					"type" => "fonts"),
		
		"typography_p_size" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "14",
					"step" => 1,
					"from" => 12,
					"to" => 60,
					"type" => "select"),
		
		"typography_p_lineheight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "21",
					"step" => 1,
					"from" => 12,
					"to" => 100,
					"type" => "select"),
		
		"typography_p_weight" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "300",
					"step" => 100,
					"from" => 100,
					"to" => 900,
					"type" => "select"),
		
		"typography_p_style" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8",
					"std" => "",
					"multiple" => true,
					"options" => $GREEN_GLOBALS['options_params']['list_fonts_styles'],
					"type" => "checklist"),
		
		"typography_p_color" => array(
					"title" => '',
					"desc" => '',
					"divider" => false,
					"columns" => "1_8 last",
					"std" => "#222222",
					"style" => "custom",
					"type" => "color"),
		
		
		
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'green'),
					"icon" => "iconadmin-docs",
					"override" => "category,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'green'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'green'),
					"desc" => esc_html__('Select desired blog streampage parameters (you can override it in each category)', 'green'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'green'),
					"desc" => esc_html__('Select desired blog style', 'green'),
					"divider" => false,
					"override" => "category,page",
					"std" => "excerpt",
					"options" => $GREEN_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'green'),
					"desc" => esc_html__('Select article display method: boxed or stretch', 'green'),
					"override" => "category,page",
					"std" => "stretch",
					"options" => $GREEN_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'green'),
					"desc" => esc_html__('Select desired hover style (only for Blog style = Portfolio)', 'green'),
					"override" => "category,page",
					"std" => "square effect_shift",
					"options" => $GREEN_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'green'),
					"desc" => esc_html__('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'green'),
					"override" => "category,page",
					"std" => "left_to_right",
					"options" => $GREEN_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'green'),
					"desc" => esc_html__('Select location for the dedicated content or featured image in the "excerpt" blog style', 'green'),
					"override" => "category,page,post",
					"std" => "default",
					"options" => $GREEN_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'green'),
					"desc" => esc_html__('Show filter buttons (only for Blog style = Portfolio, Masonry, Classic)', 'green'),
					"override" => "category,page",
					"std" => "hide",
					"options" => $GREEN_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'green'),
					"desc" => esc_html__('Select the desired sorting method for posts', 'green'),
					"override" => "category,page",
					"std" => "date",
					"options" => $GREEN_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'green'),
					"desc" => esc_html__('Select the desired ordering method for posts', 'green'),
					"override" => "category,page",
					"std" => "desc",
					"options" => $GREEN_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'green'),
					"desc" => esc_html__('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'green'),
					"override" => "category,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'green'),
					"desc" => esc_html__('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'green'),
					"override" => "category,page",
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'green'),
					"desc" => esc_html__('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'green'),
					"override" => "category,page",
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'green'),
					"icon" => "iconadmin-doc",
					"override" => "category,post,page",
					"type" => "tab"),
		
		
		"info_blog_2" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'green'),
					"desc" => esc_html__('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'green'),
					"override" => "category,post,page",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'green'),
					"desc" => esc_html__('Select desired style for single page', 'green'),
					"divider" => false,
					"override" => "category,page,post",
					"std" => "single-standard",
					"options" => $GREEN_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'green'),
					"desc" => esc_html__("Allow authors to edit their posts in frontend area)", 'green'),
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'green'),
					"desc" => esc_html__("Show featured image (if selected) before post content on single pages", 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'green'),
					"desc" => esc_html__('Show area with post title on single pages', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'green'),
					"desc" => esc_html__('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'green'),
					"override" => "category,page",
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'green'),
					"desc" => esc_html__('Show area with post info on single pages', 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'green'),
					"desc" => esc_html__('Show text before "Read more" tag on single pages', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'green'),
					"desc" => esc_html__("Show post author information block on single post page", 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'green'),
					"desc" => esc_html__("Show tags block on single post page", 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_counters" => array(
					"title" => esc_html__('Show post counters',  'green'),
					"desc" => esc_html__("Show counters block on single post page", 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'green'),
					"desc" => esc_html__("Show related posts block on single post page", 'green'),
					"override" => "category,post,page",
					"std" => "no", //yes
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"), //switch

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'green'),
					"desc" => esc_html__("How many related posts showed on single post page", 'green'),
					"override" => "category,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "hidden"), //spinner

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'green'),
					"desc" => esc_html__("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'green'),
					"override" => "category,post,page",
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "hidden"),  //spinner
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'green'),
					"desc" => esc_html__('Select the desired sorting method for related posts', 'green'),
		//			"override" => "category,courses_group,page",
					"std" => "date",
					"options" => $GREEN_GLOBALS['options_params']['list_sorting'],
					"type" => "hidden"), //select
		 
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'green'),
					"desc" => esc_html__('Select the desired ordering method for related posts', 'green'),
		//			"override" => "category,courses_group,page",
					"std" => "desc",
					"options" => $GREEN_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "hidden"), //switch
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'green'),
					"desc" => esc_html__("Show comments block on single post page", 'green'),
					"override" => "category,post,page",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_general' => array(
					"title" => esc_html__('Other parameters', 'green'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,page",
					"type" => "tab"),
		
		"info_blog_3" => array(
					"title" => esc_html__('Other Blog parameters', 'green'),
					"desc" => esc_html__('Select excluded categories, substitute parameters, etc.', 'green'),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'green'),
					"desc" => esc_html__('Select categories, which posts are exclude from blog page', 'green'),
					"divider" => false,
					"std" => "",
					"options" => $GREEN_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'green'),
					"desc" => esc_html__('Select type of the pagination on blog streampages', 'green'),
					"std" => "pages",
					"override" => "category,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'green'),
						'viewmore' => esc_html__('"View more" button', 'green'),
						'infinite' => esc_html__('Infinite scroll', 'green')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_pagination_style" => array(
					"title" => esc_html__('Blog pagination style', 'green'),
					"desc" => esc_html__('Select pagination style for standard page numbers', 'green'),
					"std" => "pages",
					"override" => "category,page",
					"options" => array(
						'pages'  => esc_html__('Page numbers list', 'green'),
						'slider' => esc_html__('Slider with page numbers', 'green')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'green'),
					"desc" => esc_html__('Select counters, displayed near the post title', 'green'),
					"std" => "views",
					"override" => "category,page",
					"options" => array(
						'views' => esc_html__('Views', 'green'),
						'likes' => esc_html__('Likes', 'green'),
						'rating' => esc_html__('Rating', 'green'),
						'comments' => esc_html__('Comments', 'green')
					),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'green'),
					"desc" => esc_html__('What category display in announce block (over posts thumb) - original or nearest parental', 'green'),
					"std" => "parental",
					"override" => "category,page",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'green'),
						'original' => esc_html__("Original post's category", 'green')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'green'),
					"desc" => esc_html__('Show post date after N days (before - show post age)', 'green'),
					"override" => "category,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'green'),
					"icon" => "iconadmin-newspaper",
					"override" => "category",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'green'),
					"desc" => esc_html__('Set up list of reviews criterias. You can override it in any category.', 'green'),
					"override" => "category",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'green'),
					"desc" => esc_html__("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'green'),
					"divider" => false,
					"override" => "category",
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'green'),
					"desc" => esc_html__("Maximum level for reviews marks", 'green'),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'green'), 
						'10'=>esc_html__('10 stars', 'green'), 
						'100'=>esc_html__('100%', 'green')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'green'),
					"desc" => esc_html__("Show rating marks as text or as stars/progress bars.", 'green'),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'green'), 
						'stars' => esc_html__('As stars or bars', 'green')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'green'),
					"desc" => esc_html__('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'green'),
					"std" => esc_html__("bad,poor,normal,good,great", 'green'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'green'),
					"desc" => esc_html__("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'green'),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'green'),
						'users' => esc_html__('By visitors', 'green')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'green'),
					"desc" => esc_html__("Do you want hide second reviews tab in widgets and single posts?", 'green'),
					"std" => "show",
					"options" => $GREEN_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'green'),
					"desc" => esc_html__("What visitors can vote: all or only registered", 'green'),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'green'), 
						'registered'=>esc_html__('Only registered', 'green')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'green'),
					"desc" => esc_html__('Add default reviews criterias.',  'green'),
					"override" => "category",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),

		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'green'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'green'),
					"desc" => esc_html__('Company address, phones and e-mail', 'green'),
					"type" => "info"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'green'),
					"desc" => esc_html__('E-mail for send contact form and user registration data', 'green'),
					"divider" => false,
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'green'),
					"desc" => esc_html__('Company country, post code and city', 'green'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'green'),
					"desc" => esc_html__('Street and house number', 'green'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'green'),
					"desc" => esc_html__('Phone number', 'green'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		"contact_mob_phone" => array(
					"title" => esc_html__('Mob phone', 'green'),
					"desc" => esc_html__('Phone number', 'green'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'green'),
					"desc" => esc_html__('Fax number', 'green'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in header', 'green'),
					"desc" => esc_html__('String with contact info in the site header', 'green'),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'green'),
					"desc" => esc_html__('Maximum length of the messages in the contact form shortcode and in the comments form', 'green'),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'green'),
					"desc" => esc_html__("Message's maxlength in the contact form shortcode", 'green'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'green'),
					"desc" => esc_html__("Message's maxlength in the comments form", 'green'),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'green'),
					"desc" => esc_html__('What function you want to use for sending mail: the built-in Wordpress wp_mail or standard PHP mail function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'green'),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'green'),
					"desc" => esc_html__("What function you want to use for sending mail?", 'green'),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'green'),
						'mail' => esc_html__('PHP mail', 'green')
					),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'green'),
					"icon" => "iconadmin-users",
					"override" => "category,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'green'),
					"desc" => esc_html__("Social networks list for site footer and Social widget", 'green'),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'green'),
				     "desc" => esc_html__('Select icon and write URL to your profile in desired social networks.',  'green'),
				     "divider" => false,
				     "std" => array(array('url'=>'', 'icon'=>'')),
				     "cloneable" => true,
				     "size" => "small",
				     "style" => 'icons',
				     "options" => $GREEN_GLOBALS['options_params']['list_icons'],
				     "type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'green'),
					"override" => "category,page",
					"desc" => esc_html__("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'green'),
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'green'),
					"override" => "category,page",
					"desc" => esc_html__("Show social share buttons block", 'green'),
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'green'),
						'vertical'	=> esc_html__('Vertical', 'green'),
						'horizontal'=> esc_html__('Horizontal', 'green')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'green'),
					"override" => "category,page",
					"desc" => esc_html__("Show share counters after social buttons", 'green'),
					"std" => "no", //yes
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'green'),
					"override" => "category,page",
					"desc" => esc_html__('Caption for the block with social share buttons',  'green'),
					"std" => esc_html__('Share:', 'green'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'green'),
					"desc" => esc_html__('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'green'),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"options" => $GREEN_GLOBALS['options_params']['list_icons'],  //list_socials
					"cloneable" => true,
					"size" => "small",
					"style" => 'icons',  // 'images'
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'green'),
					"desc" => esc_html__("Put to this section Twitter API 1.1 keys.<br>
					You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'green'),
					"type" => "hidden"), //info
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'green'),
					"desc" => esc_html__('Your login (username) in Twitter',  'green'),
					"divider" => false,
					"std" => "",
					"type" => "hidden"),  //text
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'green'),
					"desc" => esc_html__('Twitter API Consumer key',  'green'),
					"divider" => false,
					"std" => "",
					"type" => "hidden"), //text
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'green'),
					"desc" => esc_html__('Twitter API Consumer secret',  'green'),
					"divider" => false,
					"std" => "",
					"type" => "hidden"),  //text
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'green'),
					"desc" => esc_html__('Twitter API Token key',  'green'),
					"divider" => false,
					"std" => "",
					"type" => "hidden"),  //text
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'green'),
					"desc" => esc_html__('Twitter API Token secret',  'green'),
					"divider" => false,
					"std" => "",
					"type" => "hidden"),  //text

		"info_socials_4" => array(
			"title" => esc_html__('Login via Social network', 'green'),
			"desc" => esc_html__("Settings for the Login via Social networks", 'green'),
			"type" => "info"),

		"social_login" => array(
			"title" => esc_html__('Social plugin shortcode',  'green'),
			"desc" => esc_html__('Social plugin shortcode like [plugin_shortcode]',  'green'),
			"divider" => false,
			"std" => "wordpress_social_login",
			"type" => "text"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'green'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'green'),
					"desc" => esc_html__('Enable/disable AJAX search and output settings for it', 'green'),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'green'),
					"desc" => esc_html__('Show search field in the top area and side menus', 'green'),
					"divider" => false,
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'green'),
					"desc" => esc_html__('Use incremental AJAX search for the search field in top of page', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'green'),
					"desc" => esc_html__('The minimum length of the search string',  'green'),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'green'),
					"desc" => esc_html__('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'green'),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'green'),
					"desc" => esc_html__('Select post types, what will be include in search results. If not selected - use all types.', 'green'),
					"std" => "",
					"options" => $GREEN_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'green'),
					"desc" => esc_html__('Number of the posts to show in search results',  'green'),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'green'),
					"desc" => esc_html__("Show post's thumbnail in the search results", 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'green'),
					"desc" => esc_html__("Show post's publish date in the search results", 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'green'),
					"desc" => esc_html__("Show post's author in the search results", 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'green'),
					"desc" => esc_html__("Show post's counters (views, comments, likes) in the search results", 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'green'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'green'),
					"desc" => esc_html__('Basic theme functionality settings', 'green'),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'green'),
					"desc" => esc_html__('Send E-mail with new registration data to the contact email or to site admin e-mail [if contact email is empty]', 'green'),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'green'),
						'both'  => esc_html__('Both', 'green'),
						'admin' => esc_html__('Admin', 'green'),
						'user'  => esc_html__('User', 'green')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'green'),
					"desc" => esc_html__('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'green'),
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "hidden"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'green'),
					"desc" => esc_html__('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'green'),
					"desc" => esc_html__('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'green'),
					"desc" => esc_html__('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'green'),
					"desc" => esc_html__('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'green'),
					"desc" => esc_html__('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'green'),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_update_notifier" => array(
					"title" => esc_html__('Enable Update Notifier', 'green'),
					"desc" => esc_html__('Show update notifier in admin panel. <b>Attention!</b> When this option is enabled, the theme periodically (every few hours) will communicate with our server, to check the current version. When the connection is slow, it may slow down Dashboard.', 'green'),
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'green'),
					"desc" => esc_html__('Allow to use ThemeREX Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
//
//		"admin_po_composer" => array(
//					"title" => esc_html__('Enable PO Composer in the admin panel', 'green'),
//					"desc" => esc_html__('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'green'),
//					"std" => "no",
//					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
//					"type" => "switch"),

		"clear_shortcodes" => array(
					"title" => esc_html__('Remove line breaks around shortcodes', 'green'),
					"desc" => esc_html__('Do you want remove spaces and line breaks around shortcodes? <b>Be attentive!</b> This option thoroughly tested on our theme, but may affect third party plugins.', 'green'),
					"std" => "yes",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'green'),
					"desc" => esc_html__('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services (for example www.yui.2clics.net) or utility <b>yuicompressor-x.y.z.jar</b>', 'green'),
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"packed_scripts" => array(
					"title" => esc_html__('Use packed css and js files', 'green'),
					"desc" => esc_html__('Do you want to use one packed css and one js file with most theme scripts and styles instead many separate files (for speed up page loading). This reduces the number of HTTP requests when loading pages.', 'green'),
					"std" => "no",
					"options" => $GREEN_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'green'),
					"desc" => esc_html__('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'green'),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'green'),
					"desc" => esc_html__('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'green'),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),

		"info_service_3" => array(
			"title" => esc_html__('API Keys', 'green'),
			"desc" => wp_kses_data( __('API Keys for some Web services', 'green') ),
			"type" => "info"),

		'api_google' => array(
			"title" => esc_html__('Google API Key', 'green'),
			"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'green') ),
			"std" => "",
			"type" => "text"),
		
		"info_service_2" => array(
					"title" => esc_html__('Clear Wordpress cache', 'green'),
					"desc" => esc_html__('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'green'),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'green'),
					"desc" => esc_html__('Clear Wordpress cache data', 'green'),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		);



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		$GREEN_GLOBALS['options']["custom_stylesheet_file"] = array(
			"title" => esc_html__('Custom stylesheet file', 'green'),
			"desc" => esc_html__('Path to the custom stylesheet (stored in the uploads folder)', 'green'),
			"std" => "",
			"type" => "hidden");
		
		$GREEN_GLOBALS['options']["custom_stylesheet_url"] = array(
			"title" => esc_html__('Custom stylesheet url', 'green'),
			"desc" => esc_html__('URL to the custom stylesheet (stored in the uploads folder)', 'green'),
			"std" => "",
			"type" => "hidden");
		*/

	}
}
?>