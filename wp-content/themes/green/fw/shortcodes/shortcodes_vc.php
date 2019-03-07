<?php

// Width and height params
if ( !function_exists( 'green_vc_width' ) ) {
	function green_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", "green"),
			"description" => esc_html__("Width (in pixels or percent) of the current element", "green"),
			"group" => esc_html__('Size &amp; Margins', 'green'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'green_vc_height' ) ) {
	function green_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", "green"),
			"description" => esc_html__("Height (only in pixels) of the current element", "green"),
			"group" => esc_html__('Size &amp; Margins', 'green'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'green_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'green_shortcodes_vc_scripts_admin' );
	function green_shortcodes_vc_scripts_admin() {
		// Include CSS 
		green_enqueue_style ( 'shortcodes_vc-style', green_get_file_url('shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		green_enqueue_script( 'shortcodes_vc-script', green_get_file_url('shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'green_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'green_shortcodes_vc_scripts_front' );
	function green_shortcodes_vc_scripts_front() {
		if (green_vc_is_frontend()) {
			// Include CSS 
			green_enqueue_style ( 'shortcodes_vc-style', green_get_file_url('shortcodes/shortcodes_vc_front.css'), array(), null );
			// Include JS
			green_enqueue_script( 'shortcodes_vc-script', green_get_file_url('shortcodes/shortcodes_vc_front.js'), array(), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'green_shortcodes_vc_add_init_script' ) ) {
	//add_filter('green_shortcode_output', 'green_shortcodes_vc_add_init_script', 10, 4);
	function green_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (green_strpos($output, 'green_vc_init_shortcodes')===false) {
				$id = "green_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							green_init_post_formats();
							green_init_shortcodes(jQuery("body").eq(0));
							green_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_shortcodes_vc_theme_setup' ) ) {
	//if ( green_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'green_action_before_init_theme', 'green_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'green_action_after_init_theme', 'green_shortcodes_vc_theme_setup' );
	function green_shortcodes_vc_theme_setup() {
		if (green_shortcodes_is_used()) {
			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'green_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'green_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('green_shortcode_output', 'green_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_tweetmeme");
			vc_remove_element("vc_googleplus");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
			vc_remove_element("vc_carousel");
			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
			vc_remove_element("vc_separator");
			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $GREEN_GLOBALS;
			
			$GREEN_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => esc_html__("ThemeREX shortcodes", "green"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", "green"),
					"description" => esc_html__("ID for current element", "green"),
					"group" => esc_html__('Size &amp; Margins', 'green'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", "green"),
					"description" => esc_html__("CSS class for current element", "green"),
					"group" => esc_html__('Size &amp; Margins', 'green'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", "green"),
					"description" => esc_html__("Select animation while object enter in the visible area of page", "green"),
					"class" => "",
					"value" => array_flip($GREEN_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", "green"),
					"description" => esc_html__("Any additional CSS rules (if need)", "green"),
					"group" => esc_html__('Size &amp; Margins', 'green'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", "green"),
					"description" => esc_html__("Top margin (in pixels).", "green"),
					"group" => esc_html__('Size &amp; Margins', 'green'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", "green"),
					"description" => esc_html__("Bottom margin (in pixels).", "green"),
					"group" => esc_html__('Size &amp; Margins', 'green'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", "green"),
					"description" => esc_html__("Left margin (in pixels).", "green"),
					"group" => esc_html__('Size &amp; Margins', 'green'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", "green"),
					"description" => esc_html__("Right margin (in pixels).", "green"),
					"group" => esc_html__('Size &amp; Margins', 'green'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => esc_html__("Accordion", "green"),
				"description" => esc_html__("Accordion items", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Accordion style", "green"),
						"description" => esc_html__("Select style for display accordion", "green"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Style 1', 'green') => 1,
							esc_html__('Style 2', 'green') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => esc_html__("Counter", "green"),
						"description" => esc_html__("Display counter before each accordion title", "green"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened item", "green"),
						"description" => esc_html__("Number of initially opened item", "green"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "green"),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "green"),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'green' ) . '"][/trx_accordion_item]
					[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'green' ) . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.esc_html__("Add item", "green").'">'.esc_html__("Add item", "green").'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => esc_html__("Accordion item", "green"),
				"description" => esc_html__("Inner accordion item", "green"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Title for current accordion item", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),

					array(
						"param_name" => "image",
						"heading" => esc_html__("or image icon", "green"),
						"description" => esc_html__("Select image icon for the title instead icon above (if style=iconed)", "green"),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'green'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GREEN_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => esc_html__("or select uploaded image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "green"),
						"group" => esc_html__('Icon &amp; Image', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),

					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "green"),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "green"),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends GREEN_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends GREEN_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => esc_html__("Anchor", "green"),
				"description" => esc_html__("Insert anchor for the TOC (table of content)", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Anchor's icon", "green"),
						"description" => esc_html__("Select icon for the anchor from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Short title", "green"),
						"description" => esc_html__("Short title of the anchor (for the table of content)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Long description", "green"),
						"description" => esc_html__("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("External URL", "green"),
						"description" => esc_html__("External URL for this TOC item", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => esc_html__("Add separator", "green"),
						"description" => esc_html__("Add separator under item in the TOC", "green"),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$GREEN_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => esc_html__("Audio", "green"),
				"description" => esc_html__("Insert audio player", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for audio file", "green"),
						"description" => esc_html__("Put here URL for audio file", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for audio cover", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Title of the audio file", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", "green"),
						"description" => esc_html__("Author of the audio file", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", "green"),
						"description" => esc_html__("Show/hide controls", "green"),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay", "green"),
						"description" => esc_html__("Autoplay audio on page load", "green"),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Select block alignment", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => esc_html__("Block container", "green"),
				"description" => esc_html__("Container for any block ([section] analog - to enable nesting)", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", "green"),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Use as dedicated content', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Select block alignment", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", "green"),
						"description" => esc_html__("Select width for columns emulation", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", "green"),
						"description" => esc_html__("Use pan effect to show section content", "green"),
						"group" => esc_html__('Scroll', 'green'),
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "green"),
						"description" => esc_html__("Use scroller to show section content", "green"),
						"group" => esc_html__('Scroll', 'green'),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll direction", "green"),
						"description" => esc_html__("Scroll direction (if Use scroller = yes)", "green"),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'green'),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", "green"),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", "green"),
						"class" => "",
						"group" => esc_html__('Scroll', 'green'),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "green"),
						"description" => esc_html__("Any color for objects in this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", "green"),
						"description" => esc_html__("Main background tint: dark or light", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Any background color for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "green"),
						"description" => esc_html__("Select background image from library for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "green"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "green"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "green"),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "green"),
						"description" => esc_html__("Font weight of the text", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'green') => 'inherit',
							esc_html__('Thin (100)', 'green') => '100',
							esc_html__('Light (300)', 'green') => '300',
							esc_html__('Normal (400)', 'green') => '400',
							esc_html__('Bold (700)', 'green') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "green"),
						"description" => esc_html__("Content for section container", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends GREEN_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => esc_html__("Blogger", "green"),
				"description" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Output style", "green"),
						"description" => esc_html__("Select desired style for posts output", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "filters",
						"heading" => esc_html__("Show filters", "green"),
						"description" => esc_html__("Use post's tags or categories as filter buttons", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover",
						"heading" => esc_html__("Hover effect", "green"),
						"description" => esc_html__("Select hover effect (only if style=Portfolio)", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['hovers']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover_dir",
						"heading" => esc_html__("Hover direction", "green"),
						"description" => esc_html__("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['hovers_dir']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => esc_html__("Dedicated content location", "green"),
						"description" => esc_html__("Select position for dedicated content (only for style=excerpt)", "green"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Posts direction", "green"),
						"description" => esc_html__("Display posts in horizontal or vertical direction", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "rating",
						"heading" => esc_html__("Show rating stars", "green"),
						"description" => esc_html__("Show rating stars under post's header", "green"),
						"group" => esc_html__('Details', 'green'),
						"class" => "",
						"value" => array(esc_html__('Show rating', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => esc_html__("Show post info block", "green"),
						"description" => esc_html__("Show post info block (author, date, tags, etc.)", "green"),
						"class" => "",
						"value" => array(esc_html__('Show info', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => esc_html__("Description length", "green"),
						"description" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", "green"),
						"group" => esc_html__('Details', 'green'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Allow links to the post", "green"),
						"description" => esc_html__("Allow links to the post from each blogger item", "green"),
						"group" => esc_html__('Details', 'green'),
						"class" => "",
						"value" => array(esc_html__('Allow links', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("More link text", "green"),
						"description" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", "green"),
						"group" => esc_html__('Details', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", "green"),
						"description" => esc_html__("Select post type to show", "green"),
						"group" => esc_html__('Query', 'green'),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", "green"),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "green"),
						"group" => esc_html__('Query', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", "green"),
						"description" => esc_html__("Put here comma separated category slugs or ids. If empty - show posts from any category or from IDs list", "green"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'green'),
						"class" => "",
						"value" => array_flip(green_array_merge(array(0 => esc_html__('- Select category -', 'green')), $GREEN_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Total posts to show", "green"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => esc_html__('Query', 'green'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns number", "green"),
						"description" => esc_html__("How many columns used to display posts?", "green"),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"group" => esc_html__('Query', 'green'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "green"),
						"description" => esc_html__("Skip posts before select next part.", "green"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'green'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post order by", "green"),
						"description" => esc_html__("Select desired posts sorting method", "green"),
						"class" => "",
						"group" => esc_html__('Query', 'green'),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "green"),
						"description" => esc_html__("Select desired posts order", "green"),
						"class" => "",
						"group" => esc_html__('Query', 'green'),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => esc_html__("Select posts only", "green"),
						"description" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", "green"),
						"class" => "",
						"group" => esc_html__('Query', 'green'),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "green"),
						"description" => esc_html__("Use scroller to show all posts", "green"),
						"group" => esc_html__('Scroll', 'green'),
						"class" => "",
						"value" => array(esc_html__('Use scroller', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show slider controls", "green"),
						"description" => esc_html__("Show arrows to control scroll slider", "green"),
						"group" => esc_html__('Scroll', 'green'),
						"class" => "",
						"value" => array(esc_html__('Show controls', 'green') => 'yes'),
						"type" => "checkbox"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => esc_html__("Line break", "green"),
				"description" => esc_html__("Line break or Clear Floating", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => esc_html__("Clear floating", "green"),
						"description" => esc_html__("Select clear side (if need)", "green"),
						"class" => "",
						"value" => "",
						"value" => array(
							esc_html__('None', 'green') => 'none',
							esc_html__('Left', 'green') => 'left',
							esc_html__('Right', 'green') => 'right',
							esc_html__('Both', 'green') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => esc_html__("Button", "green"),
				"description" => esc_html__("Button with link", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Caption", "green"),
						"description" => esc_html__("Button caption", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Button's shape", "green"),
						"description" => esc_html__("Select button's shape", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Square', 'green') => 'square',
							esc_html__('Round', 'green') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Button's style", "green"),
						"description" => esc_html__("Select button's style", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Filled', 'green') => 'filled',
							esc_html__('Border', 'green') => 'border'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => esc_html__("Button's size", "green"),
						"description" => esc_html__("Select button's size", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Small', 'green') => 'mini',
							esc_html__('Medium', 'green') => 'medium',
							esc_html__('Large', 'green') => 'big'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Button's icon", "green"),
						"description" => esc_html__("Select icon for the title from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => esc_html__("Button's color scheme", "green"),
						"description" => esc_html__("Select button's color scheme", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Button's text color", "green"),
						"description" => esc_html__("Any color for button's caption", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Button's backcolor", "green"),
						"description" => esc_html__("Any color for button's background", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Button's alignment", "green"),
						"description" => esc_html__("Align button to left, center or right", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "green"),
						"description" => esc_html__("URL for the link on button click", "green"),
						"class" => "",
						"group" => esc_html__('Link', 'green'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", "green"),
						"description" => esc_html__("Target for the link on button click", "green"),
						"class" => "",
						"group" => esc_html__('Link', 'green'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => esc_html__("Open link in popup", "green"),
						"description" => esc_html__("Open link target in popup window", "green"),
						"class" => "",
						"group" => esc_html__('Link', 'green'),
						"value" => array(esc_html__('Open in popup', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => esc_html__("Rel attribute", "green"),
						"description" => esc_html__("Rel attribute for the button's link (if need", "green"),
						"class" => "",
						"group" => esc_html__('Link', 'green'),
						"value" => "",
						"type" => "textfield"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => esc_html__("Chat", "green"),
				"description" => esc_html__("Chat message", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Item title", "green"),
						"description" => esc_html__("Title for current chat item", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Item photo", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "green"),
						"description" => esc_html__("URL for the link on chat title click", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Chat item content", "green"),
						"description" => esc_html__("Current chat item content", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends GREEN_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => esc_html__("Columns", "green"),
				"description" => esc_html__("Insert columns with margins", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => esc_html__("Columns count", "green"),
						"description" => esc_html__("Number of the columns in the container.", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => esc_html__("Fluid columns", "green"),
						"description" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "green"),
						"class" => "",
						"value" => array(esc_html__('Fluid columns', 'green') => 'yes'),
						"type" => "checkbox"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => esc_html__("Column", "green"),
				"description" => esc_html__("Column item", "green"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => esc_html__("Merge columns", "green"),
						"description" => esc_html__("Count merged columns from current", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Alignment text in the column", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "green"),
						"description" => esc_html__("Any color for objects in this column", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Any background color for this column", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("URL for background image file", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for the background", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Column's content", "green"),
						"description" => esc_html__("Content of the current column", "green"),
						"class" => "",
						"value" => "",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends GREEN_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends GREEN_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_contact_form",
				"name" => esc_html__("Contact form", "green"),
				"description" => esc_html__("Insert contact form", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_contact_form',
				"class" => "trx_sc_collection trx_sc_contact_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_form_item'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "green"),
						"description" => esc_html__("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "green"),
						"class" => "",
						"value" => array(esc_html__('Create custom form', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "action",
						"heading" => esc_html__("Action", "green"),
						"description" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Select form alignment", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Title above contact form", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description (under the title)", "green"),
						"description" => esc_html__("Contact form description", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					green_vc_width(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => esc_html__("Form item (custom field)", "green"),
				"description" => esc_html__("Custom field for the contact form", "green"),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", "green"),
						"description" => esc_html__("Select type of the custom field", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", "green"),
						"description" => esc_html__("Name of the custom field", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Default value", "green"),
						"description" => esc_html__("Default value of the custom field", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => esc_html__("Label", "green"),
						"description" => esc_html__("Label for the custom field", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => esc_html__("Label position", "green"),
						"description" => esc_html__("Label position relative to the field", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Contact_Form extends GREEN_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends GREEN_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => esc_html__("Content block", "green"),
				"description" => esc_html__("Container for main content block (use it only on fullscreen pages)", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "green"),
						"description" => esc_html__("Content for section container", "green"),
						"class" => "",
						"value" => "",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends GREEN_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => esc_html__("Countdown", "green"),
				"description" => esc_html__("Insert countdown object", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => esc_html__("Date", "green"),
						"description" => esc_html__("Upcoming date (format: yyyy-mm-dd)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => esc_html__("Time", "green"),
						"description" => esc_html__("Upcoming time (format: HH:mm:ss)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "green"),
						"description" => esc_html__("Countdown style", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'green') => 1,
							esc_html__('Style 2', 'green') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Align counter to left, center or right", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => esc_html__("Dropcaps", "green"),
				"description" => esc_html__("Make first letter of the text as dropcaps", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_container trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "green"),
						"description" => esc_html__("Dropcaps style", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'green') => 1,
							esc_html__('Style 2', 'green') => 2,
							esc_html__('Style 3', 'green') => 3,
							esc_html__('Style 4', 'green') => 4
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Paragraph text", "green"),
						"description" => esc_html__("Paragraph with dropcaps content", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends GREEN_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => esc_html__("E-mail collector", "green"),
				"description" => esc_html__("Collect e-mails into specified group", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => esc_html__("Group", "green"),
						"description" => esc_html__("The name of group to collect e-mail address", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Opened", "green"),
						"description" => esc_html__("Initially open the input field on show object", "green"),
						"class" => "",
						"value" => array(esc_html__('Initially opened', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Align field to left, center or right", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => esc_html__("Gap", "green"),
				"description" => esc_html__("Insert gap (fullwidth area) in the post content", "green"),
				"category" => esc_html__('Structure', 'green'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Gap content", "green"),
						"description" => esc_html__("Gap inner content", "green"),
						"class" => "",
						"value" => "",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends GREEN_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => esc_html__("Google map", "green"),
				"description" => esc_html__("Insert Google map with desired address or coordinates", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_single trx_sc_googlemap",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => esc_html__("Address", "green"),
						"description" => esc_html__("Address to show in map center", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => esc_html__("Latitude and Longtitude", "green"),
						"description" => esc_html__("Comma separated map center coorditanes (instead Address)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "zoom",
						"heading" => esc_html__("Zoom", "green"),
						"description" => esc_html__("Map zoom factor", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "green"),
						"description" => esc_html__("Map custom style", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
					),
					green_vc_width('100%'),
					green_vc_height(240),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => esc_html__("Highlight text", "green"),
				"description" => esc_html__("Highlight text with selected color, background color and other styles", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_container trx_sc_highlight",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", "green"),
						"description" => esc_html__("Highlight type", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Custom', 'green') => 0,
								esc_html__('Type 1', 'green') => 1,
								esc_html__('Type 2', 'green') => 2,
								esc_html__('Type 3', 'green') => 3
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "green"),
						"description" => esc_html__("Color for the highlighted text", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Background color for the highlighted text", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "green"),
						"description" => esc_html__("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Highlight text", "green"),
						"description" => esc_html__("Content for highlight", "green"),
						"class" => "",
						"value" => "",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends GREEN_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => esc_html__("Icon", "green"),
				"description" => esc_html__("Insert the icon", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "green"),
						"description" => esc_html__("Select icon class from Fontello icons set", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "green"),
						"description" => esc_html__("Icon's color", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Background color for the icon", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => esc_html__("Background shape", "green"),
						"description" => esc_html__("Shape of the icon background", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('None', 'green') => 'none',
							esc_html__('Round', 'green') => 'round',
							esc_html__('Square', 'green') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => esc_html__("Icon's color scheme", "green"),
						"description" => esc_html__("Select icon's color scheme", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "green"),
						"description" => esc_html__("Icon's font size", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "green"),
						"description" => esc_html__("Icon's font weight", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'green') => 'inherit',
							esc_html__('Thin (100)', 'green') => '100',
							esc_html__('Light (300)', 'green') => '300',
							esc_html__('Normal (400)', 'green') => '400',
							esc_html__('Bold (700)', 'green') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Icon's alignment", "green"),
						"description" => esc_html__("Align icon to left, center or right", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "green"),
						"description" => esc_html__("Link URL from this icon (if not empty)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => esc_html__("Image", "green"),
				"description" => esc_html__("Insert image", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("Select image", "green"),
						"description" => esc_html__("Select image from library", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Image alignment", "green"),
						"description" => esc_html__("Align image to left or right side", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => esc_html__("Image shape", "green"),
						"description" => esc_html__("Shape of the image: square or round", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Square', 'green') => 'square',
							esc_html__('Round', 'green') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Image's title", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "green"),
						"description" => esc_html__("The link URL from the image", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title's icon", "green"),
						"description" => esc_html__("Select icon for the title from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => esc_html__("Infobox", "green"),
				"description" => esc_html__("Box with info or error message", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "green"),
						"description" => esc_html__("Infobox style", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Regular', 'green') => 'regular',
								esc_html__('Info', 'green') => 'info',
								esc_html__('Success', 'green') => 'success',
								esc_html__('Error', 'green') => 'error',
								esc_html__('Result', 'green') => 'result'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => esc_html__("Closeable", "green"),
						"description" => esc_html__("Create closeable box (with close button)", "green"),
						"class" => "",
						"value" => array(esc_html__('Close button', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Custom icon", "green"),
						"description" => esc_html__("Select icon for the infobox from Fontello icons set. If empty - use default icon", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "green"),
						"description" => esc_html__("Any color for the text and headers", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Any background color for this infobox", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Message text", "green"),
						"description" => esc_html__("Message for the infobox", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends GREEN_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => esc_html__("Line", "green"),
				"description" => esc_html__("Insert line (delimiter)", "green"),
				"category" => esc_html__('Content', 'green'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "green"),
						"description" => esc_html__("Line style", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Solid', 'green') => 'solid',
								esc_html__('Dashed', 'green') => 'dashed',
								esc_html__('Dotted', 'green') => 'dotted',
								esc_html__('Double', 'green') => 'double',
								esc_html__('Shadow', 'green') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Line color", "green"),
						"description" => esc_html__("Line color", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => esc_html__("List", "green"),
				"description" => esc_html__("List items with specific bullets", "green"),
				"category" => esc_html__('Content', 'green'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Bullet's style", "green"),
						"description" => esc_html__("Bullet's style for each list item", "green"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($GREEN_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "green"),
						"description" => esc_html__("List items color", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List icon", "green"),
						"description" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)", "green"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", "green"),
						"description" => esc_html__("List icons color", "green"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_list_item]' . esc_html__( 'Item 1', 'green' ) . '[/trx_list_item]
					[trx_list_item]' . esc_html__( 'Item 2', 'green' ) . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => esc_html__("List item", "green"),
				"description" => esc_html__("List item with specific bullet", "green"),
				"class" => "trx_sc_single trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("List item title", "green"),
						"description" => esc_html__("Title for the current list item (show it as tooltip)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "green"),
						"description" => esc_html__("Link URL for the current list item", "green"),
						"admin_label" => true,
						"group" => esc_html__('Link', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", "green"),
						"description" => esc_html__("Link target for the current list item", "green"),
						"admin_label" => true,
						"group" => esc_html__('Link', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "green"),
						"description" => esc_html__("Text color for this item", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List item icon", "green"),
						"description" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", "green"),
						"description" => esc_html__("Icon color for this item", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("List item text", "green"),
						"description" => esc_html__("Current list item content", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_List extends GREEN_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => esc_html__("Number", "green"),
				"description" => esc_html__("Insert number or any word as set of separated characters", "green"),
				"category" => esc_html__('Content', 'green'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", "green"),
						"description" => esc_html__("Number or any word to separate", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Select block alignment", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends GREEN_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => esc_html__("Parallax", "green"),
				"description" => esc_html__("Create the parallax container (with asinc background image)", "green"),
				"category" => esc_html__('Structure', 'green'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => esc_html__("Create gap", "green"),
						"description" => esc_html__("Create gap around parallax container (not need in fullscreen pages)", "green"),
						"class" => "",
						"value" => array(esc_html__('Create gap', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", "green"),
						"description" => esc_html__("Scroll direction for the parallax background", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Up', 'green') => 'up',
								esc_html__('Down', 'green') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => esc_html__("Speed", "green"),
						"description" => esc_html__("Parallax background motion speed (from 0.0 to 1.0)", "green"),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "green"),
						"description" => esc_html__("Select color for text object inside parallax block", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Bg tint", "green"),
						"description" => esc_html__("Select tint of the parallax background (for correct font color choise)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Light', 'green') => 'light',
								esc_html__('Dark', 'green') => 'dark'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Backgroud color", "green"),
						"description" => esc_html__("Select color for parallax background", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for the parallax background", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => esc_html__("Image X position", "green"),
						"description" => esc_html__("Parallax background X position (in percents)", "green"),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => esc_html__("Video background", "green"),
						"description" => esc_html__("Paste URL for video file to show it as parallax background", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => esc_html__("Video ratio", "green"),
						"description" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "green"),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "green"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "green"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Content", "green"),
						"description" => esc_html__("Content for the parallax container", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends GREEN_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => esc_html__("Popup window", "green"),
				"description" => esc_html__("Container for any html-block with desired class and style for popup window", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "green"),
						"description" => esc_html__("Content for popup container", "green"),
						"class" => "",
						"value" => "",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends GREEN_VC_ShortCodeCollection {}
						
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => esc_html__("Quote", "green"),
				"description" => esc_html__("Quote text", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_container trx_sc_quote",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "cite",
						"heading" => esc_html__("Quote cite", "green"),
						"description" => esc_html__("URL for the quote cite link", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title (author)", "green"),
						"description" => esc_html__("Quote title (author name)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Quote content", "green"),
						"description" => esc_html__("Quote content", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					green_vc_width(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends GREEN_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => esc_html__("Reviews", "green"),
				"description" => esc_html__("Insert reviews block in the single post", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Align counter to left, center or right", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => esc_html__("Search form", "green"),
				"description" => esc_html__("Insert search form", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "green"),
						"description" => esc_html__("Select style to display search field", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Regular', 'green') => "regular",
							esc_html__('Flat', 'green') => "flat"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Title (placeholder) for the search field", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => esc_html__("Search &hellip;", 'green'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => esc_html__("AJAX", "green"),
						"description" => esc_html__("Search via AJAX or reload page", "green"),
						"class" => "",
						"value" => array(esc_html__('Use AJAX search', 'green') => 'yes'),
						"type" => "checkbox"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => esc_html__("Section container", "green"),
				"description" => esc_html__("Container for any block ([block] analog - to enable nesting)", "green"),
				"category" => esc_html__('Content', 'green'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", "green"),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Use as dedicated content', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Select block alignment", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", "green"),
						"description" => esc_html__("Select width for columns emulation", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", "green"),
						"description" => esc_html__("Use pan effect to show section content", "green"),
						"group" => esc_html__('Scroll', 'green'),
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "green"),
						"description" => esc_html__("Use scroller to show section content", "green"),
						"group" => esc_html__('Scroll', 'green'),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll and Pan direction", "green"),
						"description" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "green"),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'green'),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", "green"),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", "green"),
						"class" => "",
						"group" => esc_html__('Scroll', 'green'),
						"value" => array_flip($GREEN_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "green"),
						"description" => esc_html__("Any color for objects in this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", "green"),
						"description" => esc_html__("Main background tint: dark or light", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Any background color for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "green"),
						"description" => esc_html__("Select background image from library for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "green"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "green"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "green"),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "green"),
						"description" => esc_html__("Font weight of the text", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'green') => 'inherit',
							esc_html__('Thin (100)', 'green') => '100',
							esc_html__('Light (300)', 'green') => '300',
							esc_html__('Normal (400)', 'green') => '400',
							esc_html__('Bold (700)', 'green') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "green"),
						"description" => esc_html__("Content for section container", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends GREEN_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => esc_html__("Skills", "green"),
				"description" => esc_html__("Insert skills diagramm", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => esc_html__("Max value", "green"),
						"description" => esc_html__("Max value for skills items", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Skills type", "green"),
						"description" => esc_html__("Select type of skills block", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Bar', 'green') => 'bar',
							esc_html__('Pie chart', 'green') => 'pie',
							esc_html__('Counter', 'green') => 'counter',
							esc_html__('Arc', 'green') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => esc_html__("Skills layout", "green"),
						"description" => esc_html__("Select layout of skills block", "green"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie')
						),
						"class" => "",
						"value" => array(
							esc_html__('Rows', 'green') => 'rows',
							esc_html__('Columns', 'green') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", "green"),
						"description" => esc_html__("Select direction of skills block", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Counters style", "green"),
						"description" => esc_html__("Select style of skills items (only for type=counter)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'green') => '1',
							esc_html__('Style 2', 'green') => '2',
							esc_html__('Style 3', 'green') => '3',
							esc_html__('Style 4', 'green') => '4'
						),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns count", "green"),
						"description" => esc_html__("Skills columns count (required)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "green"),
						"description" => esc_html__("Color for all skills items", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Background color for all skills items (only for type=pie)", "green"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", "green"),
						"description" => esc_html__("Border color for all skills items (only for type=pie)", "green"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Title of the skills block", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "green"),
						"description" => esc_html__("Default subtitle of the skills block (only if type=arc)", "green"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Align skills block to left or right side", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => esc_html__("Skill", "green"),
				"description" => esc_html__("Skills item", "green"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Title for the current skills item", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", "green"),
						"description" => esc_html__("Value for the current skills item", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "50",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "green"),
						"description" => esc_html__("Color for current skills item", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Background color for current skills item (only for type=pie)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", "green"),
						"description" => esc_html__("Border color for current skills item (only for type=pie)", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Item style", "green"),
						"description" => esc_html__("Select style for the current skills item (only for type=counter)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'green') => '1',
							esc_html__('Style 2', 'green') => '2',
							esc_html__('Style 3', 'green') => '3',
							esc_html__('Style 4', 'green') => '4'
						),
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends GREEN_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => esc_html__("Slider", "green"),
				"description" => esc_html__("Insert slider", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => esc_html__("Engine", "green"),
						"description" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Float slider", "green"),
						"description" => esc_html__("Float slider to left or right side", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom slides", "green"),
						"description" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "green"),
						"class" => "",
						"value" => array(esc_html__('Custom slides', 'green') => 'yes'),
						"type" => "checkbox"
					)
					),
					green_exists_revslider() || green_exists_royalslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => esc_html__("Revolution slider alias or Royal Slider ID", "green"),
						"description" => esc_html__("Alias for Revolution slider or Royal slider ID", "green"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo','royal')
						),
						"value" => "",
						"type" => "textfield"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", "green"),
						"description" => esc_html__("Select category. If empty - show posts from any category or from IDs list", "green"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(green_array_merge(array(0 => esc_html__('- Select category -', 'green')), $GREEN_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Swiper: Number of posts", "green"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Swiper: Offset before select posts", "green"),
						"description" => esc_html__("Skip posts before select next part.", "green"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Swiper: Post sorting", "green"),
						"description" => esc_html__("Select desired posts sorting method", "green"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Swiper: Post order", "green"),
						"description" => esc_html__("Select desired posts order", "green"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Swiper: Post IDs list", "green"),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "green"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Swiper: Show slider controls", "green"),
						"description" => esc_html__("Show arrows inside slider", "green"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Show controls', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => esc_html__("Swiper: Show slider pagination", "green"),
						"description" => esc_html__("Show bullets or titles to switch slides", "green"),
						"group" => esc_html__('Details', 'green'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								esc_html__('Dots', 'green') => 'yes', 
								esc_html__('Side Titles', 'green') => 'full',
								esc_html__('Over Titles', 'green') => 'over',
								esc_html__('None', 'green') => 'no'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => esc_html__("Swiper: Show titles section", "green"),
						"description" => esc_html__("Show section with post's title and short post's description", "green"),
						"group" => esc_html__('Details', 'green'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								esc_html__('Not show', 'green') => "no",
								esc_html__('Show/Hide info', 'green') => "slide",
								esc_html__('Fixed info', 'green') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => esc_html__("Swiper: Post descriptions", "green"),
						"description" => esc_html__("Show post's excerpt max length (characters)", "green"),
						"group" => esc_html__('Details', 'green'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Swiper: Post's title as link", "green"),
						"description" => esc_html__("Make links from post's titles", "green"),
						"group" => esc_html__('Details', 'green'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Titles as a links', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => esc_html__("Swiper: Crop images", "green"),
						"description" => esc_html__("Crop images in each slide or live it unchanged", "green"),
						"group" => esc_html__('Details', 'green'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Crop images', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Swiper: Autoheight", "green"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "green"),
						"group" => esc_html__('Details', 'green'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Autoheight', 'green') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Swiper: Slides change interval", "green"),
						"description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "green"),
						"group" => esc_html__('Details', 'green'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => esc_html__("Slide", "green"),
				"description" => esc_html__("Slider item - single slide", "green"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => esc_html__("URL (source) for image file", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for the current slide", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends GREEN_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => esc_html__("Social icons", "green"),
				"description" => esc_html__("Custom social icons", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "size",
						"heading" => esc_html__("Icon's size", "green"),
						"description" => esc_html__("Size of the icons", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Tiny', 'green') => 'tiny',
							esc_html__('Small', 'green') => 'small',
							esc_html__('Large', 'green') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Manual socials list", "green"),
						"description" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom socials", "green"),
						"description" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", "green"),
						"class" => "",
						"value" => array(esc_html__('Custom socials', 'green') => 'yes'),
						"type" => "checkbox"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => esc_html__("Custom social item", "green"),
				"description" => esc_html__("Custom social item: name, profile url and icon url", "green"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Social name", "green"),
						"description" => esc_html__("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("Your profile URL", "green"),
						"description" => esc_html__("URL of your profile in specified social network", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("URL (source) for icon file", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for the current social icon", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends GREEN_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends GREEN_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => esc_html__("Table", "green"),
				"description" => esc_html__("Insert a table", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Cells content alignment", "green"),
						"description" => esc_html__("Select alignment for each table cell", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Table content", "green"),
						"description" => esc_html__("Content, created with any table-generator", "green"),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						/*"holder" => "div",*/
						"type" => "textarea_html"
					),
					green_vc_width(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends GREEN_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => esc_html__("Tabs", "green"),
				"description" => esc_html__("Tabs", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Tabs style", "green"),
						"description" => esc_html__("Select style of tabs items", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'green') => '1',
							esc_html__('Style 2', 'green') => '2'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened tab", "green"),
						"description" => esc_html__("Number of initially opened tab", "green"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Scroller", "green"),
						"description" => esc_html__("Use scroller to show tab content (height parameter required)", "green"),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_tab title="' . esc_html__( 'Tab 1', 'green' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . esc_html__( 'Tab 2', 'green' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => esc_html__("Tab item", "green"),
				"description" => esc_html__("Single tab item", "green"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Tab title", "green"),
						"description" => esc_html__("Title for current tab", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),

					array(
						"param_name" => "image",
						"heading" => esc_html__("or image icon", "green"),
						"description" => esc_html__("Select image icon for the title instead icon above (if style=iconed)", "green"),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'green'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GREEN_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => esc_html__("or select uploaded image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "green"),
						"group" => esc_html__('Icon &amp; Image', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					

					array(
						"param_name" => "tab_id",
						"heading" => esc_html__("Tab ID", "green"),
						"description" => esc_html__("ID for current tab (required). Please, start it from letter.", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends GREEN_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends GREEN_VC_ShortCodeTab {}
			
			
			
			
			// Team
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_team",
				"name" => esc_html__("Team", "green"),
				"description" => esc_html__("Insert team members", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_team',
				"class" => "trx_sc_columns trx_sc_team",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_team_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Team style", "green"),
						"description" => esc_html__("Select style to display team members", "green"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Style 1', 'green') => 1,
							esc_html__('Style 2', 'green') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "green"),
						"description" => esc_html__("How many columns use to show team members", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "green"),
						"description" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "green"),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "green"),
						"description" => esc_html__("Put here comma separated categories (ids or slugs) to show team members. If empty - select team members from any category (group) or from IDs list", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "green"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "green"),
						"description" => esc_html__("Skip posts before select next part.", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "green"),
						"description" => esc_html__("Select desired posts sorting method", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "green"),
						"description" => esc_html__("Select desired posts order", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Team member's IDs list", "green"),
						"description" => esc_html__("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_team_item user="' . esc_html__( 'Member 1', 'green' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 2', 'green' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_team_item",
				"name" => esc_html__("Team member", "green"),
				"description" => esc_html__("Team member - all data pull out from it account on your site", "green"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_item trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Registered user", "green"),
						"description" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['users']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => esc_html__("Team member", "green"),
						"description" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['members']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "green"),
						"description" => esc_html__("Link on team member's personal page", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", "green"),
						"description" => esc_html__("Team member's name", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", "green"),
						"description" => esc_html__("Team member's position", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", "green"),
						"description" => esc_html__("Team member's e-mail", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Member's Photo", "green"),
						"description" => esc_html__("Team member's photo (avatar", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Socials", "green"),
						"description" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Team extends GREEN_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Team_Item extends GREEN_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Testimonials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", "green"),
				"description" => esc_html__("Insert testimonials slider", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_collection trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show arrows", "green"),
						"description" => esc_html__("Show control buttons", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Testimonials change interval", "green"),
						"description" => esc_html__("Testimonials change interval (in milliseconds: 1000ms = 1s)", "green"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Alignment of the testimonials block", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "green"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "green"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "green"),
						"description" => esc_html__("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "green"),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "green"),
						"description" => esc_html__("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "green"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "green"),
						"description" => esc_html__("Skip posts before select next part.", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "green"),
						"description" => esc_html__("Select desired posts sorting method", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "green"),
						"description" => esc_html__("Select desired posts order", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", "green"),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "green"),
						"group" => esc_html__('Query', 'green'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", "green"),
						"description" => esc_html__("Main background tint: dark or light", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Any background color for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "green"),
						"description" => esc_html__("Select background image from library for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "green"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "green"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", "green"),
				"description" => esc_html__("Single testimonials item", "green"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_container trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", "green"),
						"description" => esc_html__("Name of the testimonmials author", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author_profession",
						"heading" => esc_html__("Author Profession", "green"),
						"description" => esc_html__("Name of the testimonmials author profession", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "green"),
						"description" => esc_html__("Link URL to the testimonmials author page", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", "green"),
						"description" => esc_html__("E-mail of the testimonmials author", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", "green"),
						"description" => esc_html__("Select or upload photo of testimonmials author or write URL of photo from other site", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Testimonials text", "green"),
						"description" => esc_html__("Current testimonials text", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Testimonials extends GREEN_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Testimonials_Item extends GREEN_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => esc_html__("Title", "green"),
				"description" => esc_html__("Create header tag (1-6 level) with many styles", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Title content", "green"),
						"description" => esc_html__("Title content", "green"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Title type", "green"),
						"description" => esc_html__("Title type (header level)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Header 1', 'green') => '1',
							esc_html__('Header 2', 'green') => '2',
							esc_html__('Header 3', 'green') => '3',
							esc_html__('Header 4', 'green') => '4',
							esc_html__('Header 5', 'green') => '5',
							esc_html__('Header 6', 'green') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Title style", "green"),
						"description" => esc_html__("Title style: only text (regular) or with icon/image (iconed)", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Regular', 'green') => 'regular',
							esc_html__('Underline', 'green') => 'underline',
							esc_html__('Divider', 'green') => 'divider',
							esc_html__('With icon (image)', 'green') => 'iconed'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Title text alignment", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "green"),
						"description" => esc_html__("Custom font size. If empty - use theme default", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "green"),
						"description" => esc_html__("Custom font weight. If empty or inherit - use theme default", "green"),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'green') => 'inherit',
							esc_html__('Thin (100)', 'green') => '100',
							esc_html__('Light (300)', 'green') => '300',
							esc_html__('Normal (400)', 'green') => '400',
							esc_html__('Semibold (600)', 'green') => '600',
							esc_html__('Bold (700)', 'green') => '700',
							esc_html__('Black (900)', 'green') => '900'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Title color", "green"),
						"description" => esc_html__("Select color for the title", "green"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title font icon", "green"),
						"description" => esc_html__("Select font icon for the title from Fontello icons set (if style=iconed)", "green"),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'green'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("or image icon", "green"),
						"description" => esc_html__("Select image icon for the title instead icon above (if style=iconed)", "green"),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'green'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $GREEN_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => esc_html__("or select uploaded image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "green"),
						"group" => esc_html__('Icon &amp; Image', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => esc_html__("Image (picture) size", "green"),
						"description" => esc_html__("Select image (picture) size (if style=iconed)", "green"),
						"group" => esc_html__('Icon &amp; Image', 'green'),
						"class" => "",
						"value" => array(
							esc_html__('Small', 'green') => 'small',
							esc_html__('Medium', 'green') => 'medium',
							esc_html__('Large', 'green') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Icon (image) position", "green"),
						"description" => esc_html__("Select icon (image) position (if style=iconed)", "green"),
						"group" => esc_html__('Icon &amp; Image', 'green'),
						"class" => "",
						"value" => array(
							esc_html__('Top', 'green') => 'top',
							esc_html__('Left', 'green') => 'left'
						),
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => esc_html__("Toggles", "green"),
				"description" => esc_html__("Toggles items", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Toggles style", "green"),
						"description" => esc_html__("Select style for display toggles", "green"),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Style 1', 'green') => 1,
							esc_html__('Style 2', 'green') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => esc_html__("Counter", "green"),
						"description" => esc_html__("Display counter before each toggles title", "green"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "green"),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "green"),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class']
				),
				'default_content' => '
					[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'green' ) . '"][/trx_toggles_item]
					[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'green' ) . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.esc_html__("Add item", "green").'">'.esc_html__("Add item", "green").'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => esc_html__("Toggles item", "green"),
				"description" => esc_html__("Single toggles item", "green"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "green"),
						"description" => esc_html__("Title for current toggles item", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Open on show", "green"),
						"description" => esc_html__("Open current toggle item on show", "green"),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "green"),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "green"),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", "green"),
						"class" => "",
						"value" => $GREEN_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends GREEN_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends GREEN_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => esc_html__("Twitter", "green"),
				"description" => esc_html__("Insert twitter feed into post (page)", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Twitter Username", "green"),
						"description" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => esc_html__("Consumer Key", "green"),
						"description" => esc_html__("Consumer Key from the twitter account", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => esc_html__("Consumer Secret", "green"),
						"description" => esc_html__("Consumer Secret from the twitter account", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => esc_html__("Token Key", "green"),
						"description" => esc_html__("Token Key from the twitter account", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => esc_html__("Token Secret", "green"),
						"description" => esc_html__("Token Secret from the twitter account", "green"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Tweets number", "green"),
						"description" => esc_html__("Number tweets to show", "green"),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show arrows", "green"),
						"description" => esc_html__("Show control buttons", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Tweets change interval", "green"),
						"description" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", "green"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Alignment of the tweets block", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "green"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "green"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", "green"),
						"description" => esc_html__("Main background tint: dark or light", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "green"),
						"description" => esc_html__("Any background color for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "green"),
						"description" => esc_html__("Select background image from library for this section", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "green"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "green"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "green"),
						"group" => esc_html__('Colors and Images', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => esc_html__("Video", "green"),
				"description" => esc_html__("Insert video player", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for video file", "green"),
						"description" => esc_html__("Paste URL for video file", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => esc_html__("Ratio", "green"),
						"description" => esc_html__("Select ratio for display video", "green"),
						"class" => "",
						"value" => array(
							esc_html__('16:9', 'green') => "16:9",
							esc_html__('4:3', 'green') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay video", "green"),
						"description" => esc_html__("Autoplay video on page load", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Select block alignment", "green"),
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for video preview", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", "green"),
						"description" => esc_html__("Top offset (padding) from background image to video block (in percent). For example: 3%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", "green"),
						"description" => esc_html__("Bottom offset (padding) from background image to video block (in percent). For example: 3%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", "green"),
						"description" => esc_html__("Left offset (padding) from background image to video block (in percent). For example: 20%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", "green"),
						"description" => esc_html__("Right offset (padding) from background image to video block (in percent). For example: 12%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => esc_html__("Zoom", "green"),
				"description" => esc_html__("Insert the image with zoom/lens effect", "green"),
				"category" => esc_html__('Content', 'green'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => esc_html__("Effect", "green"),
						"description" => esc_html__("Select effect to display overlapping image", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Lens', 'green') => 'lens',
							esc_html__('Zoom', 'green') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("Main image", "green"),
						"description" => esc_html__("Select or upload main image", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => esc_html__("Overlaping image", "green"),
						"description" => esc_html__("Select or upload overlaping image", "green"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "green"),
						"description" => esc_html__("Float zoom to left or right side", "green"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($GREEN_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "green"),
						"description" => esc_html__("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", "green"),
						"description" => esc_html__("Top offset (padding) from background image to zoom block (in percent). For example: 3%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", "green"),
						"description" => esc_html__("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", "green"),
						"description" => esc_html__("Left offset (padding) from background image to zoom block (in percent). For example: 20%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", "green"),
						"description" => esc_html__("Right offset (padding) from background image to zoom block (in percent). For example: 12%", "green"),
						"group" => esc_html__('Background', 'green'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					green_vc_width(),
					green_vc_height(),
					$GREEN_GLOBALS['vc_params']['margin_top'],
					$GREEN_GLOBALS['vc_params']['margin_bottom'],
					$GREEN_GLOBALS['vc_params']['margin_left'],
					$GREEN_GLOBALS['vc_params']['margin_right'],
					$GREEN_GLOBALS['vc_params']['id'],
					$GREEN_GLOBALS['vc_params']['class'],
					$GREEN_GLOBALS['vc_params']['animation'],
					$GREEN_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends GREEN_VC_ShortCodeSingle {}
			

			do_action('green_action_shortcodes_list_vc');
			
			
			if (green_exists_woocommerce()) {
			
				// WooCommerce - Cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_cart",
					"name" => esc_html__("Cart", "green"),
					"description" => esc_html__("WooCommerce shortcode: show cart page", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_wooc_cart',
					"class" => "trx_sc_alone trx_sc_woocommerce_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Cart extends GREEN_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Checkout
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_checkout",
					"name" => esc_html__("Checkout", "green"),
					"description" => esc_html__("WooCommerce shortcode: show checkout page", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_wooc_checkout',
					"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Checkout extends GREEN_VC_ShortCodeAlone {}
			
			
				// WooCommerce - My Account
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_my_account",
					"name" => esc_html__("My Account", "green"),
					"description" => esc_html__("WooCommerce shortcode: show my account page", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_wooc_my_account',
					"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_My_Account extends GREEN_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Order Tracking
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_order_tracking",
					"name" => esc_html__("Order Tracking", "green"),
					"description" => esc_html__("WooCommerce shortcode: show order tracking page", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_wooc_order_tracking',
					"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Order_Tracking extends GREEN_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Shop Messages
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "shop_messages",
					"name" => esc_html__("Shop Messages", "green"),
					"description" => esc_html__("WooCommerce shortcode: show shop messages", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_wooc_shop_messages',
					"class" => "trx_sc_alone trx_sc_shop_messages",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Shop_Messages extends GREEN_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Product Page
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_page",
					"name" => esc_html__("Product Page", "green"),
					"description" => esc_html__("WooCommerce shortcode: display single product page", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_product_page',
					"class" => "trx_sc_single trx_sc_product_page",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", "green"),
							"description" => esc_html__("SKU code of displayed product", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", "green"),
							"description" => esc_html__("ID of displayed product", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "posts_per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_type",
							"heading" => esc_html__("Post type", "green"),
							"description" => esc_html__("Post type for the WP query (leave 'product')", "green"),
							"class" => "",
							"value" => "product",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_status",
							"heading" => esc_html__("Post status", "green"),
							"description" => esc_html__("Display posts only with this status", "green"),
							"class" => "",
							"value" => array(
								esc_html__('Publish', 'green') => 'publish',
								esc_html__('Protected', 'green') => 'protected',
								esc_html__('Private', 'green') => 'private',
								esc_html__('Pending', 'green') => 'pending',
								esc_html__('Draft', 'green') => 'draft'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Page extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product",
					"name" => esc_html__("Product", "green"),
					"description" => esc_html__("WooCommerce shortcode: display one product", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_product',
					"class" => "trx_sc_single trx_sc_product",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", "green"),
							"description" => esc_html__("Product's SKU code", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", "green"),
							"description" => esc_html__("Product's ID", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product extends GREEN_VC_ShortCodeSingle {}
			
			
				// WooCommerce - Best Selling Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "best_selling_products",
					"name" => esc_html__("Best Selling Products", "green"),
					"description" => esc_html__("WooCommerce shortcode: show best selling products", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_best_selling_products',
					"class" => "trx_sc_single trx_sc_best_selling_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Best_Selling_Products extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Recent Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "recent_products",
					"name" => esc_html__("Recent Products", "green"),
					"description" => esc_html__("WooCommerce shortcode: show recent products", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_recent_products',
					"class" => "trx_sc_single trx_sc_recent_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Recent_Products extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Related Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "related_products",
					"name" => esc_html__("Related Products", "green"),
					"description" => esc_html__("WooCommerce shortcode: show related products", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_related_products',
					"class" => "trx_sc_single trx_sc_related_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "posts_per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Related_Products extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Featured Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "featured_products",
					"name" => esc_html__("Featured Products", "green"),
					"description" => esc_html__("WooCommerce shortcode: show featured products", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_featured_products',
					"class" => "trx_sc_single trx_sc_featured_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Featured_Products extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Top Rated Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "top_rated_products",
					"name" => esc_html__("Top Rated Products", "green"),
					"description" => esc_html__("WooCommerce shortcode: show top rated products", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_top_rated_products',
					"class" => "trx_sc_single trx_sc_top_rated_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Top_Rated_Products extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Sale Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "sale_products",
					"name" => esc_html__("Sale Products", "green"),
					"description" => esc_html__("WooCommerce shortcode: list products on sale", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_sale_products',
					"class" => "trx_sc_single trx_sc_sale_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Sale_Products extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product Category
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_category",
					"name" => esc_html__("Products from category", "green"),
					"description" => esc_html__("WooCommerce shortcode: list products in specified category(-ies)", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_product_category',
					"class" => "trx_sc_single trx_sc_product_category",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "category",
							"heading" => esc_html__("Categories", "green"),
							"description" => esc_html__("Comma separated category slugs", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "operator",
							"heading" => esc_html__("Operator", "green"),
							"description" => esc_html__("Categories operator", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('IN', 'green') => 'IN',
								esc_html__('NOT IN', 'green') => 'NOT IN',
								esc_html__('AND', 'green') => 'AND'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Category extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "products",
					"name" => esc_html__("Products", "green"),
					"description" => esc_html__("WooCommerce shortcode: list all products", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_products',
					"class" => "trx_sc_single trx_sc_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "skus",
							"heading" => esc_html__("SKUs", "green"),
							"description" => esc_html__("Comma separated SKU codes of products", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => esc_html__("IDs", "green"),
							"description" => esc_html__("Comma separated ID of products", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Products extends GREEN_VC_ShortCodeSingle {}
			
			
			
			
				// WooCommerce - Product Attribute
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_attribute",
					"name" => esc_html__("Products by Attribute", "green"),
					"description" => esc_html__("WooCommerce shortcode: show products with specified attribute", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_product_attribute',
					"class" => "trx_sc_single trx_sc_product_attribute",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many products showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "attribute",
							"heading" => esc_html__("Attribute", "green"),
							"description" => esc_html__("Attribute name", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "filter",
							"heading" => esc_html__("Filter", "green"),
							"description" => esc_html__("Attribute value", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Attribute extends GREEN_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products Categories
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_categories",
					"name" => esc_html__("Product Categories", "green"),
					"description" => esc_html__("WooCommerce shortcode: show categories with products", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_product_categories',
					"class" => "trx_sc_single trx_sc_product_categories",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "number",
							"heading" => esc_html__("Number", "green"),
							"description" => esc_html__("How many categories showed", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "green"),
							"description" => esc_html__("How many columns per row use for categories output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'green') => 'date',
								esc_html__('Title', 'green') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "green"),
							"description" => esc_html__("Sorting order for products output", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($GREEN_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "parent",
							"heading" => esc_html__("Parent", "green"),
							"description" => esc_html__("Parent category slug", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "date",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => esc_html__("IDs", "green"),
							"description" => esc_html__("Comma separated ID of products", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "hide_empty",
							"heading" => esc_html__("Hide empty", "green"),
							"description" => esc_html__("Hide empty categories", "green"),
							"class" => "",
							"value" => array("Hide empty" => "1" ),
							"type" => "checkbox"
						)
					)
				) );
				
				class WPBakeryShortCode_Products_Categories extends GREEN_VC_ShortCodeSingle {}
			
				/*
			
				// WooCommerce - Add to cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "add_to_cart",
					"name" => esc_html__("Add to cart", "green"),
					"description" => esc_html__("WooCommerce shortcode: Display a single product price + cart button", "green"),
					"category" => esc_html__('WooCommerce', 'green'),
					'icon' => 'icon_trx_add_to_cart',
					"class" => "trx_sc_single trx_sc_add_to_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", "green"),
							"description" => esc_html__("Product's ID", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", "green"),
							"description" => esc_html__("Product's SKU code", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "quantity",
							"heading" => esc_html__("Quantity", "green"),
							"description" => esc_html__("How many item add", "green"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_price",
							"heading" => esc_html__("Show price", "green"),
							"description" => esc_html__("Show price near button", "green"),
							"class" => "",
							"value" => array("Show price" => "true" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "class",
							"heading" => esc_html__("Class", "green"),
							"description" => esc_html__("CSS class", "green"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => esc_html__("CSS style", "green"),
							"description" => esc_html__("CSS style for additional decoration", "green"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Add_To_Cart extends GREEN_VC_ShortCodeSingle {}
				*/
			}

		}
	}
}
?>