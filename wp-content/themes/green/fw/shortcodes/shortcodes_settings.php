<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'green_shortcodes_is_used' ) ) {
	function green_shortcodes_is_used() {
		return green_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| green_vc_is_frontend();															// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'green_shortcodes_width' ) ) {
	function green_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", "green"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'green_shortcodes_height' ) ) {
	function green_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", "green"),
			"desc" => esc_html__("Width (in pixels or percent) and height (only in pixels) of element", "green"),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_shortcodes_settings_theme_setup' ) ) {
//	if ( green_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'green_action_before_init_theme', 'green_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'green_action_after_init_theme', 'green_shortcodes_settings_theme_setup' );
	function green_shortcodes_settings_theme_setup() {
		if (green_shortcodes_is_used()) {
			global $GREEN_GLOBALS;

			// Prepare arrays 
			$GREEN_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", "green"),
					"desc" => esc_html__("ID for current element", "green"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", "green"),
					"desc" => esc_html__("CSS class for current element (optional)", "green"),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", "green"),
					"desc" => esc_html__("Any additional CSS rules (if need)", "green"),
					"value" => "",
					"type" => "text"
				),
			
				// Margins params
				'top' => array(
					"title" => esc_html__("Top margin", "green"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				'bottom' => array(
					"title" => esc_html__("Bottom margin", "green"),
					"value" => "",
					"type" => "text"
				),
			
				'left' => array(
					"title" => esc_html__("Left margin", "green"),
					"value" => "",
					"type" => "text"
				),
			
				'right' => array(
					"title" => esc_html__("Right margin", "green"),
					"desc" => esc_html__("Margins around list (in pixels).", "green"),
					"value" => "",
					"type" => "text"
				),
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'green'),
					'ol'	=> esc_html__('Ordered', 'green'),
					'iconed'=> esc_html__('Iconed', 'green')
				),
				'yes_no'	=> green_get_list_yesno(),
				'on_off'	=> green_get_list_onoff(),
				'dir' 		=> green_get_list_directions(),
				'align'		=> green_get_list_alignments(),
				'float'		=> green_get_list_floats(),
				'show_hide'	=> green_get_list_showhide(),
				'sorting' 	=> green_get_list_sortings(),
				'ordering' 	=> green_get_list_orderings(),
				'sliders'	=> green_get_list_sliders(),
				'users'		=> green_get_list_users(),
				'members'	=> green_get_list_posts(false, array('post_type'=>'team', 'orderby'=>'title', 'order'=>'asc', 'return'=>'title')),
				'categories'=> green_get_list_categories(),
				'testimonials_groups'=> green_get_list_terms(false, 'testimonial_group'),
				'team_groups'=> green_get_list_terms(false, 'team_group'),
				'columns'	=> green_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), green_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), green_get_list_icons()),
				'locations'	=> green_get_list_dedicated_locations(),
				'filters'	=> green_get_list_portfolio_filters(),
				'formats'	=> green_get_list_post_formats_filters(),
				'hovers'	=> green_get_list_hovers(),
				'hovers_dir'=> green_get_list_hovers_directions(),
				'tint'		=> green_get_list_bg_tints(),
				'animations'=> green_get_list_animations_in(),
				'blogger_styles'	=> green_get_list_templates_blogger(),
				'posts_types'		=> green_get_list_posts_types(),
				'button_styles'		=> green_get_list_button_styles(),
				'googlemap_styles'	=> green_get_list_googlemap_styles(),
				'field_types'		=> green_get_list_field_types(),
				'label_positions'	=> green_get_list_label_positions()
			);

			$GREEN_GLOBALS['sc_params']['animation'] = array(
				"title" => esc_html__("Animation",  'green'),
				"desc" => esc_html__('Select animation while object enter in the visible area of page',  'green'),
				"value" => "none",
				"type" => "select",
				"options" => $GREEN_GLOBALS['sc_params']['animations']
			);
	
			// Shortcodes list
			//------------------------------------------------------------------
			$GREEN_GLOBALS['shortcodes'] = array(
			
				// Accordion
				"trx_accordion" => array(
					"title" => esc_html__("Accordion", "green"),
					"desc" => esc_html__("Accordion items", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Accordion style", "green"),
							"desc" => esc_html__("Select style for display accordion", "green"),
							"value" => 1,
							"options" => array(
								1 => esc_html__('Style 1', 'green'),
								2 => esc_html__('Style 2', 'green')
							),
							"type" => "radio"
						),
						"counter" => array(
							"title" => esc_html__("Counter", "green"),
							"desc" => esc_html__("Display counter before each accordion title", "green"),
							"value" => "off",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['on_off']
						),
						"initial" => array(
							"title" => esc_html__("Initially opened item", "green"),
							"desc" => esc_html__("Number of initially opened item", "green"),
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'green'),
							"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'green'),
							"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_accordion_item",
						"title" => esc_html__("Item", "green"),
						"desc" => esc_html__("Accordion item", "green"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Accordion item title", "green"),
								"desc" => esc_html__("Title for current accordion item", "green"),
								"value" => "",
								"type" => "text"
							),

							"image" => array(
								"title" => esc_html__('or image icon',  'green'),
								"desc" => esc_html__("Select image icon for the title instead icon above (if style=iconed)",  'green'),
								"dependency" => array(
									'style' => array('iconed')
								),
								"value" => "",
								"type" => "images",
								"size" => "small",
								"options" => $GREEN_GLOBALS['sc_params']['images']
							),
							"picture" => array(
								"title" => esc_html__('or URL for image file', "green"),
								"desc" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "green"),
								"dependency" => array(
									'style' => array('iconed')
								),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),

							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'green'),
								"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'green'),
								"value" => "",
								"type" => "icons",
								"options" => $GREEN_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'green'),
								"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'green'),
								"value" => "",
								"type" => "icons",
								"options" => $GREEN_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Accordion item content", "green"),
								"desc" => esc_html__("Current accordion item content", "green"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Anchor
				"trx_anchor" => array(
					"title" => esc_html__("Anchor", "green"),
					"desc" => esc_html__("Insert anchor for the TOC (table of content)", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__("Anchor's icon",  'green'),
							"desc" => esc_html__('Select icon for the anchor from Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"title" => array(
							"title" => esc_html__("Short title", "green"),
							"desc" => esc_html__("Short title of the anchor (for the table of content)", "green"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Long description", "green"),
							"desc" => esc_html__("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "green"),
							"value" => "",
							"type" => "text"
						),
						"url" => array(
							"title" => esc_html__("External URL", "green"),
							"desc" => esc_html__("External URL for this TOC item", "green"),
							"value" => "",
							"type" => "text"
						),
						"separator" => array(
							"title" => esc_html__("Add separator", "green"),
							"desc" => esc_html__("Add separator under item in the TOC", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"id" => $GREEN_GLOBALS['sc_params']['id']
					)
				),
			
			
				// Audio
				"trx_audio" => array(
					"title" => esc_html__("Audio", "green"),
					"desc" => esc_html__("Insert audio player", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for audio file", "green"),
							"desc" => esc_html__("URL for audio file", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose audio', 'green'),
								'action' => 'media_upload',
								'type' => 'audio',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose audio file', 'green'),
									'update' => esc_html__('Select audio file', 'green')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"image" => array(
							"title" => esc_html__("Cover image", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for audio cover", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => esc_html__("Title", "green"),
							"desc" => esc_html__("Title of the audio file", "green"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"author" => array(
							"title" => esc_html__("Author", "green"),
							"desc" => esc_html__("Author of the audio file", "green"),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => esc_html__("Show controls", "green"),
							"desc" => esc_html__("Show controls in audio player", "green"),
							"divider" => true,
							"size" => "medium",
							"value" => "show",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['show_hide']
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay audio", "green"),
							"desc" => esc_html__("Autoplay audio on page load", "green"),
							"value" => "off",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", "green"),
							"desc" => esc_html__("Select block alignment", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Block
				"trx_block" => array(
					"title" => esc_html__("Block container", "green"),
					"desc" => esc_html__("Container for any block ([section] analog - to enable nesting)", "green"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", "green"),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", "green"),
							"desc" => esc_html__("Select block alignment", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", "green"),
							"desc" => esc_html__("Select width for columns emulation", "green"),
							"value" => "none",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", "green"),
							"desc" => esc_html__("Use pan effect to show section content", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "green"),
							"desc" => esc_html__("Use scroller to show section content", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll direction", "green"),
							"desc" => esc_html__("Scroll direction (if Use scroller = yes)", "green"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", "green"),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", "green"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => esc_html__("Fore color", "green"),
							"desc" => esc_html__("Any color for objects in this section", "green"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", "green"),
							"desc" => esc_html__("Main background tint: dark or light", "green"),
							"value" => "",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Any background color for this section", "green"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "green"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "green"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "green"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "green"),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "green"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "green"),
							"desc" => esc_html__("Font weight of the text", "green"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'green'),
								'300' => esc_html__('Light (300)', 'green'),
								'400' => esc_html__('Normal (400)', 'green'),
								'700' => esc_html__('Bold (700)', 'green')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "green"),
							"desc" => esc_html__("Content for section container", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Blogger
				"trx_blogger" => array(
					"title" => esc_html__("Blogger", "green"),
					"desc" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Posts output style", "green"),
							"desc" => esc_html__("Select desired style for posts output", "green"),
							"value" => "regular",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['blogger_styles']
						),
						"filters" => array(
							"title" => esc_html__("Show filters", "green"),
							"desc" => esc_html__("Use post's tags or categories as filter buttons", "green"),
							"value" => "no",
							"dir" => "horizontal",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['filters']
						),
						"hover" => array(
							"title" => esc_html__("Hover effect", "green"),
							"desc" => esc_html__("Select hover effect (only if style=Portfolio)", "green"),
							"dependency" => array(
								'style' => array('portfolio','grid','square')
							),
							"value" => "",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['hovers']
						),
						"hover_dir" => array(
							"title" => esc_html__("Hover direction", "green"),
							"desc" => esc_html__("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "green"),
							"dependency" => array(
								'style' => array('portfolio','grid','square'),
								'hover' => array('square','circle')
							),
							"value" => "left_to_right",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['hovers_dir']
						),
						"dir" => array(
							"title" => esc_html__("Posts direction", "green"),
							"desc" => esc_html__("Display posts in horizontal or vertical direction", "green"),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['dir']
						),
						"post_type" => array(
							"title" => esc_html__("Post type", "green"),
							"desc" => esc_html__("Select post type to show", "green"),
							"value" => "post",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['posts_types']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "green"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "green"),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories list", "green"),
							"desc" => esc_html__("Select the desired categories. If not selected - show posts from any category or from IDs list", "green"),
							"dependency" => array(
								'ids' => array('is_empty'),
								'post_type' => array('refresh')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $GREEN_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => esc_html__("Total posts to show", "green"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns number", "green"),
							"desc" => esc_html__("How many columns used to show posts? If empty or 0 - equal to posts number", "green"),
							"dependency" => array(
								'dir' => array('horizontal')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "green"),
							"desc" => esc_html__("Skip posts before select next part.", "green"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "green"),
							"desc" => esc_html__("Select desired posts sorting method", "green"),
							"value" => "date",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "green"),
							"desc" => esc_html__("Select desired posts order", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						),
						"only" => array(
							"title" => esc_html__("Select posts only", "green"),
							"desc" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", "green"),
							"value" => "no",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['formats']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "green"),
							"desc" => esc_html__("Use scroller to show all posts", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Show slider controls", "green"),
							"desc" => esc_html__("Show arrows to control scroll slider", "green"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"location" => array(
							"title" => esc_html__("Dedicated content location", "green"),
							"desc" => esc_html__("Select position for dedicated content (only for style=excerpt)", "green"),
							"divider" => true,
							"dependency" => array(
								'style' => array('excerpt')
							),
							"value" => "default",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['locations']
						),
						"rating" => array(
							"title" => esc_html__("Show rating stars", "green"),
							"desc" => esc_html__("Show rating stars under post's header", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"info" => array(
							"title" => esc_html__("Show post info block", "green"),
							"desc" => esc_html__("Show post info block (author, date, tags, etc.)", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"links" => array(
							"title" => esc_html__("Allow links on the post", "green"),
							"desc" => esc_html__("Allow links on the post from each blogger item", "green"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"descr" => array(
							"title" => esc_html__("Description length", "green"),
							"desc" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", "green"),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"readmore" => array(
							"title" => esc_html__("More link text", "green"),
							"desc" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", "green"),
							"value" => "",
							"type" => "text"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Br
				"trx_br" => array(
					"title" => esc_html__("Break", "green"),
					"desc" => esc_html__("Line break with clear floating (if need)", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"clear" => 	array(
							"title" => esc_html__("Clear floating", "green"),
							"desc" => esc_html__("Clear floating (if need)", "green"),
							"value" => "",
							"type" => "checklist",
							"options" => array(
								'none' => esc_html__('None', 'green'),
								'left' => esc_html__('Left', 'green'),
								'right' => esc_html__('Right', 'green'),
								'both' => esc_html__('Both', 'green')
							)
						)
					)
				),
			
			
			
			
				// Button
				"trx_button" => array(
					"title" => esc_html__("Button", "green"),
					"desc" => esc_html__("Button with link", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Caption", "green"),
							"desc" => esc_html__("Button caption", "green"),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => esc_html__("Button's shape", "green"),
							"desc" => esc_html__("Select button's shape", "green"),
							"value" => "square",
							"size" => "medium",
							"options" => array(
								'square' => esc_html__('Square', 'green'),
								'round' => esc_html__('Round', 'green')
							),
							"type" => "switch"
						), 
						"style" => array(
							"title" => esc_html__("Button's style", "green"),
							"desc" => esc_html__("Select button's style", "green"),
							"value" => "default",
							"dir" => "horizontal",
							"options" => array(
								'filled' => esc_html__('Filled', 'green'),
								'border' => esc_html__('Border', 'green')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => esc_html__("Button's size", "green"),
							"desc" => esc_html__("Select button's size", "green"),
							"value" => "small",
							"dir" => "horizontal",
							"options" => array(
								'small' => esc_html__('Small', 'green'),
								'medium' => esc_html__('Medium', 'green'),
								'large' => esc_html__('Large', 'green')
							),
							"type" => "checklist"
						), 
						"icon" => array(
							"title" => esc_html__("Button's icon",  'green'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"bg_style" => array(
							"title" => esc_html__("Button's color scheme", "green"),
							"desc" => esc_html__("Select button's color scheme", "green"),
							"value" => "custom",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['button_styles']
						), 
						"color" => array(
							"title" => esc_html__("Button's text color", "green"),
							"desc" => esc_html__("Any color for button's caption", "green"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Button's backcolor", "green"),
							"desc" => esc_html__("Any color for button's background", "green"),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => esc_html__("Button's alignment", "green"),
							"desc" => esc_html__("Align button to left, center or right", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", "green"),
							"desc" => esc_html__("URL for link on button click", "green"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"target" => array(
							"title" => esc_html__("Link target", "green"),
							"desc" => esc_html__("Target for link on button click", "green"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"popup" => array(
							"title" => esc_html__("Open link in popup", "green"),
							"desc" => esc_html__("Open link target in popup window", "green"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						), 
						"rel" => array(
							"title" => esc_html__("Rel attribute", "green"),
							"desc" => esc_html__("Rel attribute for button's link (if need)", "green"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Chat
				"trx_chat" => array(
					"title" => esc_html__("Chat", "green"),
					"desc" => esc_html__("Chat message", "green"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Item title", "green"),
							"desc" => esc_html__("Chat item title", "green"),
							"value" => "",
							"type" => "text"
						),
						"photo" => array(
							"title" => esc_html__("Item photo", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"link" => array(
							"title" => esc_html__("Item link", "green"),
							"desc" => esc_html__("Chat item link", "green"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Chat item content", "green"),
							"desc" => esc_html__("Current chat item content", "green"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Columns
				"trx_columns" => array(
					"title" => esc_html__("Columns", "green"),
					"desc" => esc_html__("Insert up to 5 columns in your page (post)", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"fluid" => array(
							"title" => esc_html__("Fluid columns", "green"),
							"desc" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						), 
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_column_item",
						"title" => esc_html__("Column", "green"),
						"desc" => esc_html__("Column item", "green"),
						"container" => true,
						"params" => array(
							"span" => array(
								"title" => esc_html__("Merge columns", "green"),
								"desc" => esc_html__("Count merged columns from current", "green"),
								"value" => "",
								"type" => "text"
							),
							"align" => array(
								"title" => esc_html__("Alignment", "green"),
								"desc" => esc_html__("Alignment text in the column", "green"),
								"value" => "",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $GREEN_GLOBALS['sc_params']['align']
							),
							"color" => array(
								"title" => esc_html__("Fore color", "green"),
								"desc" => esc_html__("Any color for objects in this column", "green"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", "green"),
								"desc" => esc_html__("Any background color for this column", "green"),
								"value" => "",
								"type" => "color"
							),
							"bg_image" => array(
								"title" => esc_html__("URL for background image file", "green"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the background", "green"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Column item content", "green"),
								"desc" => esc_html__("Current column item content", "green"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"animation" => $GREEN_GLOBALS['sc_params']['animation'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Contact form
				"trx_contact_form" => array(
					"title" => esc_html__("Contact form", "green"),
					"desc" => esc_html__("Insert contact form", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"custom" => array(
							"title" => esc_html__("Custom", "green"),
							"desc" => esc_html__("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						), 
						"action" => array(
							"title" => esc_html__("Action", "green"),
							"desc" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", "green"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", "green"),
							"desc" => esc_html__("Select form alignment", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"title" => array(
							"title" => esc_html__("Title", "green"),
							"desc" => esc_html__("Contact form title", "green"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "green"),
							"desc" => esc_html__("Short description for contact form", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => green_shortcodes_width(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_form_item",
						"title" => esc_html__("Field", "green"),
						"desc" => esc_html__("Custom field", "green"),
						"container" => false,
						"params" => array(
							"type" => array(
								"title" => esc_html__("Type", "green"),
								"desc" => esc_html__("Type of the custom field", "green"),
								"value" => "text",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $GREEN_GLOBALS['sc_params']['field_types']
							), 
							"name" => array(
								"title" => esc_html__("Name", "green"),
								"desc" => esc_html__("Name of the custom field", "green"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Default value", "green"),
								"desc" => esc_html__("Default value of the custom field", "green"),
								"value" => "",
								"type" => "text"
							),
							"label" => array(
								"title" => esc_html__("Label", "green"),
								"desc" => esc_html__("Label for the custom field", "green"),
								"value" => "",
								"type" => "text"
							),
							"label_position" => array(
								"title" => esc_html__("Label position", "green"),
								"desc" => esc_html__("Label position relative to the field", "green"),
								"value" => "top",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $GREEN_GLOBALS['sc_params']['label_positions']
							), 
							"top" => $GREEN_GLOBALS['sc_params']['top'],
							"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
							"left" => $GREEN_GLOBALS['sc_params']['left'],
							"right" => $GREEN_GLOBALS['sc_params']['right'],
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"animation" => $GREEN_GLOBALS['sc_params']['animation'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Content block on fullscreen page
				"trx_content" => array(
					"title" => esc_html__("Content block", "green"),
					"desc" => esc_html__("Container for main content block with desired class and style (use it only on fullscreen pages)", "green"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Container content", "green"),
							"desc" => esc_html__("Content for section container", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Countdown
				"trx_countdown" => array(
					"title" => esc_html__("Countdown", "green"),
					"desc" => esc_html__("Insert countdown object", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"date" => array(
							"title" => esc_html__("Date", "green"),
							"desc" => esc_html__("Upcoming date (format: yyyy-mm-dd)", "green"),
							"value" => "",
							"format" => "yy-mm-dd",
							"type" => "date"
						),
						"time" => array(
							"title" => esc_html__("Time", "green"),
							"desc" => esc_html__("Upcoming time (format: HH:mm:ss)", "green"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Style", "green"),
							"desc" => esc_html__("Countdown style", "green"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => esc_html__('Style 1', 'green'),
								2 => esc_html__('Style 2', 'green')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "green"),
							"desc" => esc_html__("Align counter to left, center or right", "green"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						), 
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Dropcaps
				"trx_dropcaps" => array(
					"title" => esc_html__("Dropcaps", "green"),
					"desc" => esc_html__("Make first letter as dropcaps", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "green"),
							"desc" => esc_html__("Dropcaps style", "green"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => esc_html__('Style 1', 'green'),
								2 => esc_html__('Style 2', 'green'),
								3 => esc_html__('Style 3', 'green'),
								4 => esc_html__('Style 4', 'green')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Paragraph content", "green"),
							"desc" => esc_html__("Paragraph with dropcaps content", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Emailer
				"trx_emailer" => array(
					"title" => esc_html__("E-mail collector", "green"),
					"desc" => esc_html__("Collect the e-mail address into specified group", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"group" => array(
							"title" => esc_html__("Group", "green"),
							"desc" => esc_html__("The name of group to collect e-mail address", "green"),
							"value" => "",
							"type" => "text"
						),
						"open" => array(
							"title" => esc_html__("Open", "green"),
							"desc" => esc_html__("Initially open the input field on show object", "green"),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "green"),
							"desc" => esc_html__("Align object to left, center or right", "green"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						), 
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Gap
				"trx_gap" => array(
					"title" => esc_html__("Gap", "green"),
					"desc" => esc_html__("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "green"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Gap content", "green"),
							"desc" => esc_html__("Gap inner content", "green"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						)
					)
				),
			
			
			
			
			
				// Google map
				"trx_googlemap" => array(
					"title" => esc_html__("Google map", "green"),
					"desc" => esc_html__("Insert Google map with desired address or coordinates", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"address" => array(
							"title" => esc_html__("Address", "green"),
							"desc" => esc_html__("Address to show in map center", "green"),
							"value" => "",
							"type" => "text"
						),
						"latlng" => array(
							"title" => esc_html__("Latitude and Longtitude", "green"),
							"desc" => esc_html__("Comma separated map center coorditanes (instead Address)", "green"),
							"value" => "",
							"type" => "text"
						),
						"zoom" => array(
							"title" => esc_html__("Zoom", "green"),
							"desc" => esc_html__("Map zoom factor", "green"),
							"divider" => true,
							"value" => 16,
							"min" => 1,
							"max" => 20,
							"type" => "spinner"
						),
						"style" => array(
							"title" => esc_html__("Map style", "green"),
							"desc" => esc_html__("Select map style", "green"),
							"value" => "default",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['googlemap_styles']
						),
						"width" => green_shortcodes_width('100%'),
						"height" => green_shortcodes_height(240),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Hide or show any block
				"trx_hide" => array(
					"title" => esc_html__("Hide/Show any block", "green"),
					"desc" => esc_html__("Hide or Show any block with desired CSS-selector", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"selector" => array(
							"title" => esc_html__("Selector", "green"),
							"desc" => esc_html__("Any block's CSS-selector", "green"),
							"value" => "",
							"type" => "text"
						),
						"hide" => array(
							"title" => esc_html__("Hide or Show", "green"),
							"desc" => esc_html__("New state for the block: hide or show", "green"),
							"value" => "yes",
							"size" => "small",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						)
					)
				),
			
			
			
				// Highlght text
				"trx_highlight" => array(
					"title" => esc_html__("Highlight text", "green"),
					"desc" => esc_html__("Highlight text with selected color, background color and other styles", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"type" => array(
							"title" => esc_html__("Type", "green"),
							"desc" => esc_html__("Highlight type", "green"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								0 => esc_html__('Custom', 'green'),
								1 => esc_html__('Type 1', 'green'),
								2 => esc_html__('Type 2', 'green'),
								3 => esc_html__('Type 3', 'green')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", "green"),
							"desc" => esc_html__("Color for the highlighted text", "green"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Background color for the highlighted text", "green"),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "green"),
							"desc" => esc_html__("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", "green"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Highlighting content", "green"),
							"desc" => esc_html__("Content for highlight", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Icon
				"trx_icon" => array(
					"title" => esc_html__("Icon", "green"),
					"desc" => esc_html__("Insert icon", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__('Icon',  'green'),
							"desc" => esc_html__('Select font icon from the Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Icon's color", "green"),
							"desc" => esc_html__("Icon's color", "green"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "color"
						),
						"bg_shape" => array(
							"title" => esc_html__("Background shape", "green"),
							"desc" => esc_html__("Shape of the icon background", "green"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "none",
							"type" => "radio",
							"options" => array(
								'none' => esc_html__('None', 'green'),
								'round' => esc_html__('Round', 'green'),
								'square' => esc_html__('Square', 'green')
							)
						),
						"bg_style" => array(
							"title" => esc_html__("Background style", "green"),
							"desc" => esc_html__("Select icon's color scheme", "green"),
							"value" => "custom",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['button_styles']
						), 
						"bg_color" => array(
							"title" => esc_html__("Icon's background color", "green"),
							"desc" => esc_html__("Icon's background color", "green"),
							"dependency" => array(
								'icon' => array('not_empty'),
								'background' => array('round','square')
							),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "green"),
							"desc" => esc_html__("Icon's font size", "green"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "spinner",
							"min" => 8,
							"max" => 240
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "green"),
							"desc" => esc_html__("Icon font weight", "green"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'green'),
								'300' => esc_html__('Light (300)', 'green'),
								'400' => esc_html__('Normal (400)', 'green'),
								'700' => esc_html__('Bold (700)', 'green')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "green"),
							"desc" => esc_html__("Icon text alignment", "green"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", "green"),
							"desc" => esc_html__("Link URL from this icon (if not empty)", "green"),
							"value" => "",
							"type" => "text"
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Image
				"trx_image" => array(
					"title" => esc_html__("Image", "green"),
					"desc" => esc_html__("Insert image into your post (page)", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for image file", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => esc_html__("Title", "green"),
							"desc" => esc_html__("Image title (if need)", "green"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon before title",  'green'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => esc_html__("Float image", "green"),
							"desc" => esc_html__("Float image to left or right side", "green"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['float']
						), 
						"shape" => array(
							"title" => esc_html__("Image Shape", "green"),
							"desc" => esc_html__("Shape of the image: square (rectangle) or round", "green"),
							"value" => "square",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"square" => esc_html__('Square', 'green'),
								"round" => esc_html__('Round', 'green')
							)
						), 
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Infobox
				"trx_infobox" => array(
					"title" => esc_html__("Infobox", "green"),
					"desc" => esc_html__("Insert infobox into your post (page)", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "green"),
							"desc" => esc_html__("Infobox style", "green"),
							"value" => "regular",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'regular' => esc_html__('Regular', 'green'),
								'info' => esc_html__('Info', 'green'),
								'success' => esc_html__('Success', 'green'),
								'error' => esc_html__('Error', 'green')
							)
						),
						"closeable" => array(
							"title" => esc_html__("Closeable box", "green"),
							"desc" => esc_html__("Create closeable box (with close button)", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"icon" => array(
							"title" => esc_html__("Custom icon",  'green'),
							"desc" => esc_html__('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Text color", "green"),
							"desc" => esc_html__("Any color for text and headers", "green"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Any background color for this infobox", "green"),
							"value" => "",
							"type" => "color"
						),
						"_content_" => array(
							"title" => esc_html__("Infobox content", "green"),
							"desc" => esc_html__("Content for infobox", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Line
				"trx_line" => array(
					"title" => esc_html__("Line", "green"),
					"desc" => esc_html__("Insert Line into your post (page)", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "green"),
							"desc" => esc_html__("Line style", "green"),
							"value" => "solid",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'solid' => esc_html__('Solid', 'green'),
								'dashed' => esc_html__('Dashed', 'green'),
								'dotted' => esc_html__('Dotted', 'green'),
								'double' => esc_html__('Double', 'green')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", "green"),
							"desc" => esc_html__("Line color", "green"),
							"value" => "",
							"type" => "color"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// List
				"trx_list" => array(
					"title" => esc_html__("List", "green"),
					"desc" => esc_html__("List items with specific bullets", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Bullet's style", "green"),
							"desc" => esc_html__("Bullet's style for each list item", "green"),
							"value" => "ul",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['list_styles']
						), 
						"color" => array(
							"title" => esc_html__("Color", "green"),
							"desc" => esc_html__("List items color", "green"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('List icon',  'green'),
							"desc" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)",  'green'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"icon_color" => array(
							"title" => esc_html__("Icon color", "green"),
							"desc" => esc_html__("List icons color", "green"),
							"value" => "",
							"dependency" => array(
								'style' => array('iconed')
							),
							"type" => "color"
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_list_item",
						"title" => esc_html__("Item", "green"),
						"desc" => esc_html__("List item with specific bullet", "green"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"_content_" => array(
								"title" => esc_html__("List item content", "green"),
								"desc" => esc_html__("Current list item content", "green"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"title" => array(
								"title" => esc_html__("List item title", "green"),
								"desc" => esc_html__("Current list item title (show it as tooltip)", "green"),
								"value" => "",
								"type" => "text"
							),
							"color" => array(
								"title" => esc_html__("Color", "green"),
								"desc" => esc_html__("Text color for this item", "green"),
								"value" => "",
								"type" => "color"
							),
							"icon" => array(
								"title" => esc_html__('List icon',  'green'),
								"desc" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)",  'green'),
								"value" => "",
								"type" => "icons",
								"options" => $GREEN_GLOBALS['sc_params']['icons']
							),
							"icon_color" => array(
								"title" => esc_html__("Icon color", "green"),
								"desc" => esc_html__("Icon color for this item", "green"),
								"value" => "",
								"type" => "color"
							),
							"link" => array(
								"title" => esc_html__("Link URL", "green"),
								"desc" => esc_html__("Link URL for the current list item", "green"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"target" => array(
								"title" => esc_html__("Link target", "green"),
								"desc" => esc_html__("Link target for the current list item", "green"),
								"value" => "",
								"type" => "text"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
				// Number
				"trx_number" => array(
					"title" => esc_html__("Number", "green"),
					"desc" => esc_html__("Insert number or any word as set separate characters", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"value" => array(
							"title" => esc_html__("Value", "green"),
							"desc" => esc_html__("Number or any word", "green"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", "green"),
							"desc" => esc_html__("Select block alignment", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Parallax
				"trx_parallax" => array(
					"title" => esc_html__("Parallax", "green"),
					"desc" => esc_html__("Create the parallax container (with asinc background image)", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"gap" => array(
							"title" => esc_html__("Create gap", "green"),
							"desc" => esc_html__("Create gap around parallax container", "green"),
							"value" => "no",
							"size" => "small",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						), 
						"dir" => array(
							"title" => esc_html__("Dir", "green"),
							"desc" => esc_html__("Scroll direction for the parallax background", "green"),
							"value" => "up",
							"size" => "medium",
							"options" => array(
								'up' => esc_html__('Up', 'green'),
								'down' => esc_html__('Down', 'green')
							),
							"type" => "switch"
						), 
						"speed" => array(
							"title" => esc_html__("Speed", "green"),
							"desc" => esc_html__("Image motion speed (from 0.0 to 1.0)", "green"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0.3",
							"type" => "spinner"
						),
						"color" => array(
							"title" => esc_html__("Text color", "green"),
							"desc" => esc_html__("Select color for text object inside parallax block", "green"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => esc_html__("Bg tint", "green"),
							"desc" => esc_html__("Select tint of the parallax background (for correct font color choise)", "green"),
							"value" => "light",
							"size" => "medium",
							"options" => array(
								'light' => esc_html__('Light', 'green'),
								'dark' => esc_html__('Dark', 'green')
							),
							"type" => "switch"
						), 
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Select color for parallax background", "green"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the parallax background", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image_x" => array(
							"title" => esc_html__("Image X position", "green"),
							"desc" => esc_html__("Image horizontal position (as background of the parallax block) - in percent", "green"),
							"min" => "0",
							"max" => "100",
							"value" => "50",
							"type" => "spinner"
						),
						"bg_video" => array(
							"title" => esc_html__("Video background", "green"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site to show it as parallax background", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose video', 'green'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose video file', 'green'),
									'update' => esc_html__('Select video file', 'green')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"bg_video_ratio" => array(
							"title" => esc_html__("Video ratio", "green"),
							"desc" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "green"),
							"value" => "16:9",
							"type" => "text"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "green"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "green"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "green"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"_content_" => array(
							"title" => esc_html__("Content", "green"),
							"desc" => esc_html__("Content for the parallax container", "green"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Popup
				"trx_popup" => array(
					"title" => esc_html__("Popup window", "green"),
					"desc" => esc_html__("Container for any html-block with desired class and style for popup window", "green"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Container content", "green"),
							"desc" => esc_html__("Content for section container", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Quote
				"trx_quote" => array(
					"title" => esc_html__("Quote", "green"),
					"desc" => esc_html__("Quote text", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"cite" => array(
							"title" => esc_html__("Quote cite", "green"),
							"desc" => esc_html__("URL for quote cite", "green"),
							"value" => "",
							"type" => "text"
						),
						"title" => array(
							"title" => esc_html__("Title (author)", "green"),
							"desc" => esc_html__("Quote title (author name)", "green"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Quote content", "green"),
							"desc" => esc_html__("Quote content", "green"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => green_shortcodes_width(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Reviews
				"trx_reviews" => array(
					"title" => esc_html__("Reviews", "green"),
					"desc" => esc_html__("Insert reviews block in the single post", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Alignment", "green"),
							"desc" => esc_html__("Align counter to left, center or right", "green"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						), 
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Search
				"trx_search" => array(
					"title" => esc_html__("Search", "green"),
					"desc" => esc_html__("Show search form", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"ajax" => array(
							"title" => esc_html__("Style", "green"),
							"desc" => esc_html__("Select style to display search field", "green"),
							"value" => "regular",
							"options" => array(
								"regular" => esc_html__('Regular', 'green'),
								"flat" => esc_html__('Flat', 'green')
							),
							"type" => "checklist"
						),
						"title" => array(
							"title" => esc_html__("Title", "green"),
							"desc" => esc_html__("Title (placeholder) for the search field", "green"),
							"value" => esc_html__("Search &hellip;", 'green'),
							"type" => "text"
						),
						"ajax" => array(
							"title" => esc_html__("AJAX", "green"),
							"desc" => esc_html__("Search via AJAX or reload page", "green"),
							"value" => "yes",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Section
				"trx_section" => array(
					"title" => esc_html__("Section container", "green"),
					"desc" => esc_html__("Container for any block with desired class and style", "green"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", "green"),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", "green"),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", "green"),
							"desc" => esc_html__("Select block alignment", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", "green"),
							"desc" => esc_html__("Select width for columns emulation", "green"),
							"value" => "none",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", "green"),
							"desc" => esc_html__("Use pan effect to show section content", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "green"),
							"desc" => esc_html__("Use scroller to show section content", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll and Pan direction", "green"),
							"desc" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "green"),
							"dependency" => array(
								'pan' => array('yes'),
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", "green"),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", "green"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => esc_html__("Fore color", "green"),
							"desc" => esc_html__("Any color for objects in this section", "green"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", "green"),
							"desc" => esc_html__("Main background tint: dark or light", "green"),
							"value" => "",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Any background color for this section", "green"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "green"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "green"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "green"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "green"),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "green"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "green"),
							"desc" => esc_html__("Font weight of the text", "green"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'green'),
								'300' => esc_html__('Light (300)', 'green'),
								'400' => esc_html__('Normal (400)', 'green'),
								'700' => esc_html__('Bold (700)', 'green')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "green"),
							"desc" => esc_html__("Content for section container", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Skills
				"trx_skills" => array(
					"title" => esc_html__("Skills", "green"),
					"desc" => esc_html__("Insert skills diagramm in your page (post)", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"max_value" => array(
							"title" => esc_html__("Max value", "green"),
							"desc" => esc_html__("Max value for skills items", "green"),
							"value" => 100,
							"min" => 1,
							"type" => "spinner"
						),
						"type" => array(
							"title" => esc_html__("Skills type", "green"),
							"desc" => esc_html__("Select type of skills block", "green"),
							"value" => "bar",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'bar' => esc_html__('Bar', 'green'),
								'pie' => esc_html__('Pie chart', 'green'),
								'counter' => esc_html__('Counter', 'green'),
								'arc' => esc_html__('Arc', 'green')
							)
						), 
						"layout" => array(
							"title" => esc_html__("Skills layout", "green"),
							"desc" => esc_html__("Select layout of skills block", "green"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "rows",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'rows' => esc_html__('Rows', 'green'),
								'columns' => esc_html__('Columns', 'green')
							)
						),
						"dir" => array(
							"title" => esc_html__("Direction", "green"),
							"desc" => esc_html__("Select direction of skills block", "green"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "horizontal",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['dir']
						), 
						"style" => array(
							"title" => esc_html__("Counters style", "green"),
							"desc" => esc_html__("Select style of skills items (only for type=counter)", "green"),
							"dependency" => array(
								'type' => array('counter')
							),
							"value" => 1,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						), 
						// "columns" - autodetect, not set manual
						"color" => array(
							"title" => esc_html__("Skills items color", "green"),
							"desc" => esc_html__("Color for all skills items", "green"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Background color for all skills items (only for type=pie)", "green"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"border_color" => array(
							"title" => esc_html__("Border color", "green"),
							"desc" => esc_html__("Border color for all skills items (only for type=pie)", "green"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"title" => array(
							"title" => esc_html__("Skills title", "green"),
							"desc" => esc_html__("Skills block title", "green"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Skills subtitle", "green"),
							"desc" => esc_html__("Skills block subtitle - text in the center (only for type=arc)", "green"),
							"dependency" => array(
								'type' => array('arc')
							),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align skills block", "green"),
							"desc" => esc_html__("Align skills block to left or right side", "green"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['float']
						), 
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_skills_item",
						"title" => esc_html__("Skill", "green"),
						"desc" => esc_html__("Skills item", "green"),
						"container" => false,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", "green"),
								"desc" => esc_html__("Current skills item title", "green"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Value", "green"),
								"desc" => esc_html__("Current skills level", "green"),
								"value" => 50,
								"min" => 0,
								"step" => 1,
								"type" => "spinner"
							),
							"color" => array(
								"title" => esc_html__("Color", "green"),
								"desc" => esc_html__("Current skills item color", "green"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", "green"),
								"desc" => esc_html__("Current skills item background color (only for type=pie)", "green"),
								"value" => "",
								"type" => "color"
							),
							"border_color" => array(
								"title" => esc_html__("Border color", "green"),
								"desc" => esc_html__("Current skills item border color (only for type=pie)", "green"),
								"value" => "",
								"type" => "color"
							),
							"style" => array(
								"title" => esc_html__("Counter tyle", "green"),
								"desc" => esc_html__("Select style for the current skills item (only for type=counter)", "green"),
								"value" => 1,
								"min" => 1,
								"max" => 4,
								"type" => "spinner"
							), 
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Slider
				"trx_slider" => array(
					"title" => esc_html__("Slider", "green"),
					"desc" => esc_html__("Insert slider into your post (page)", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array_merge(array(
						"engine" => array(
							"title" => esc_html__("Slider engine", "green"),
							"desc" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "green"),
							"value" => "swiper",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['sliders']
						),
						"align" => array(
							"title" => esc_html__("Float slider", "green"),
							"desc" => esc_html__("Float slider to left or right side", "green"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['float']
						),
						"custom" => array(
							"title" => esc_html__("Custom slides", "green"),
							"desc" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						)
						),
						green_exists_revslider() || green_exists_royalslider() ? array(
						"alias" => array(
							"title" => esc_html__("Revolution slider alias or Royal Slider ID", "green"),
							"desc" => esc_html__("Alias for Revolution slider or Royal slider ID", "green"),
							"dependency" => array(
								'engine' => array('revo','royal')
							),
							"divider" => true,
							"value" => "",
							"type" => "text"
						)) : array(), array(
						"cat" => array(
							"title" => esc_html__("Swiper: Category list", "green"),
							"desc" => esc_html__("Comma separated list of category slugs. If empty - select posts from any category or from IDs list", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $GREEN_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => esc_html__("Swiper: Number of posts", "green"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Swiper: Offset before select posts", "green"),
							"desc" => esc_html__("Skip posts before select next part.", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Swiper: Post order by", "green"),
							"desc" => esc_html__("Select desired posts sorting method", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "date",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Swiper: Post order", "green"),
							"desc" => esc_html__("Select desired posts order", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Swiper: Post IDs list", "green"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => esc_html__("Swiper: Show slider controls", "green"),
							"desc" => esc_html__("Show arrows inside slider", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"pagination" => array(
							"title" => esc_html__("Swiper: Show slider pagination", "green"),
							"desc" => esc_html__("Show bullets for switch slides", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "checklist",
							"options" => array(
								'yes'  => esc_html__('Dots', 'green'), 
								'full' => esc_html__('Side Titles', 'green'),
								'over' => esc_html__('Over Titles', 'green'),
								'no'   => esc_html__('None', 'green')
							)
						),
						"titles" => array(
							"title" => esc_html__("Swiper: Show titles section", "green"),
							"desc" => esc_html__("Show section with post's title and short post's description", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								"no"    => esc_html__('Not show', 'green'),
								"slide" => esc_html__('Show/Hide info', 'green'),
								"fixed" => esc_html__('Fixed info', 'green')
							)
						),
						"descriptions" => array(
							"title" => esc_html__("Swiper: Post descriptions", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"desc" => esc_html__("Show post's excerpt max length (characters)", "green"),
							"value" => 0,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"links" => array(
							"title" => esc_html__("Swiper: Post's title as link", "green"),
							"desc" => esc_html__("Make links from post's titles", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"crop" => array(
							"title" => esc_html__("Swiper: Crop images", "green"),
							"desc" => esc_html__("Crop images in each slide or live it unchanged", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"autoheight" => array(
							"title" => esc_html__("Swiper: Autoheight", "green"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => esc_html__("Swiper: Slides change interval", "green"),
							"desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "green"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 5000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)),
					"children" => array(
						"name" => "trx_slider_item",
						"title" => esc_html__("Slide", "green"),
						"desc" => esc_html__("Slider item", "green"),
						"container" => false,
						"params" => array(
							"src" => array(
								"title" => esc_html__("URL (source) for image file", "green"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the current slide", "green"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Socials
				"trx_socials" => array(
					"title" => esc_html__("Social icons", "green"),
					"desc" => esc_html__("List of social icons (with hovers)", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"size" => array(
							"title" => esc_html__("Icon's size", "green"),
							"desc" => esc_html__("Size of the icons", "green"),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								"tiny" => esc_html__('Tiny', 'green'),
								"small" => esc_html__('Small', 'green'),
								"large" => esc_html__('Large', 'green')
							)
						), 
						"socials" => array(
							"title" => esc_html__("Manual socials list", "green"),
							"desc" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "green"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => esc_html__("Custom socials", "green"),
							"desc" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_social_item",
						"title" => esc_html__("Custom social item", "green"),
						"desc" => esc_html__("Custom social item: name, profile url and icon url", "green"),
						"decorate" => false,
						"container" => false,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Social name", "green"),
								"desc" => esc_html__("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "green"),
								"value" => "",
								"type" => "text"
							),
							"url" => array(
								"title" => esc_html__("Your profile URL", "green"),
								"desc" => esc_html__("URL of your profile in specified social network", "green"),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("URL (source) for icon file", "green"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the current social icon", "green"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							)
						)
					)
				),
			
			
			
			
				// Table
				"trx_table" => array(
					"title" => esc_html__("Table", "green"),
					"desc" => esc_html__("Insert a table into post (page). ", "green"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Content alignment", "green"),
							"desc" => esc_html__("Select alignment for each table cell", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"_content_" => array(
							"title" => esc_html__("Table content", "green"),
							"desc" => esc_html__("Content, created with any table-generator", "green"),
							"divider" => true,
							"rows" => 8,
							"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
							"type" => "textarea"
						),
						"width" => green_shortcodes_width(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Tabs
				"trx_tabs" => array(
					"title" => esc_html__("Tabs", "green"),
					"desc" => esc_html__("Insert tabs in your page (post)", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Tabs style", "green"),
							"desc" => esc_html__("Select style for tabs items", "green"),
							"value" => 1,
							"options" => array(
								1 => esc_html__('Style 1', 'green'),
								2 => esc_html__('Style 2', 'green')
							),
							"type" => "radio"
						),
						"initial" => array(
							"title" => esc_html__("Initially opened tab", "green"),
							"desc" => esc_html__("Number of initially opened tab", "green"),
							"divider" => true,
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "green"),
							"desc" => esc_html__("Use scroller to show tab content (height parameter required)", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_tab",
						"title" => esc_html__("Tab", "green"),
						"desc" => esc_html__("Tab item", "green"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Tab title", "green"),
								"desc" => esc_html__("Current tab title", "green"),
								"value" => "",
								"type" => "text"
							),

							"image" => array(
								"title" => esc_html__('or image icon',  'green'),
								"desc" => esc_html__("Select image icon for the title instead icon above (if style=iconed)",  'green'),
								"dependency" => array(
									'style' => array('iconed')
								),
								"value" => "",
								"type" => "images",
								"size" => "small",
								"options" => $GREEN_GLOBALS['sc_params']['images']
							),
							"picture" => array(
								"title" => esc_html__('or URL for image file', "green"),
								"desc" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "green"),
								"dependency" => array(
									'style' => array('iconed')
								),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),

							"_content_" => array(
								"title" => esc_html__("Tab content", "green"),
								"desc" => esc_html__("Current tab content", "green"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Team
				"trx_team" => array(
					"title" => esc_html__("Team", "green"),
					"desc" => esc_html__("Insert team in your page (post)", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Team style", "green"),
							"desc" => esc_html__("Select style to display team members", "green"),
							"value" => "1",
							"type" => "select",
							"options" => array(
								1 => esc_html__('Style 1', 'green'),
								2 => esc_html__('Style 2', 'green')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns use to show team members", "green"),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
						),
						"custom" => array(
							"title" => esc_html__("Custom", "green"),
							"desc" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "green"),
							"desc" => esc_html__("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $GREEN_GLOBALS['sc_params']['team_groups']
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "green"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "green"),
							"desc" => esc_html__("Skip posts before select next part.", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "green"),
							"desc" => esc_html__("Select desired posts sorting method", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "green"),
							"desc" => esc_html__("Select desired posts order", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "green"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_team_item",
						"title" => esc_html__("Member", "green"),
						"desc" => esc_html__("Team member", "green"),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => esc_html__("Registerd user", "green"),
								"desc" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", "green"),
								"value" => "",
								"type" => "select",
								"options" => $GREEN_GLOBALS['sc_params']['users']
							),
							"member" => array(
								"title" => esc_html__("Team member", "green"),
								"desc" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", "green"),
								"value" => "",
								"type" => "select",
								"options" => $GREEN_GLOBALS['sc_params']['members']
							),
							"link" => array(
								"title" => esc_html__("Link", "green"),
								"desc" => esc_html__("Link on team member's personal page", "green"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => esc_html__("Name", "green"),
								"desc" => esc_html__("Team member's name", "green"),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", "green"),
								"desc" => esc_html__("Team member's position", "green"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", "green"),
								"desc" => esc_html__("Team member's e-mail", "green"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", "green"),
								"desc" => esc_html__("Team member's photo (avatar)", "green"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => esc_html__("Socials", "green"),
								"desc" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "green"),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "green"),
								"desc" => esc_html__("Team member's short description", "green"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"animation" => $GREEN_GLOBALS['sc_params']['animation'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", "green"),
					"desc" => esc_html__("Insert testimonials into post (page)", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"controls" => array(
							"title" => esc_html__("Show arrows", "green"),
							"desc" => esc_html__("Show control buttons", "green"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => esc_html__("Testimonials change interval", "green"),
							"desc" => esc_html__("Testimonials change interval (in milliseconds: 1000ms = 1s)", "green"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "green"),
							"desc" => esc_html__("Alignment of the testimonials block", "green"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "green"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "green"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "green"),
							"desc" => esc_html__("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "green"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "green"),
							"desc" => esc_html__("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $GREEN_GLOBALS['sc_params']['testimonials_groups']
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "green"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "green"),
							"desc" => esc_html__("Skip posts before select next part.", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "green"),
							"desc" => esc_html__("Select desired posts sorting method", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => $GREEN_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "green"),
							"desc" => esc_html__("Select desired posts order", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "green"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "green"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", "green"),
							"desc" => esc_html__("Main background tint: dark or light", "green"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Any background color for this section", "green"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "green"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "green"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "green"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", "green"),
						"desc" => esc_html__("Testimonials item", "green"),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", "green"),
								"desc" => esc_html__("Name of the testimonmials author", "green"),
								"value" => "",
								"type" => "text"
							),
							"author_profession" => array(
								"title" => esc_html__("Author Profession", "green"),
								"desc" => esc_html__("Name of the testimonmials author", "green"),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", "green"),
								"desc" => esc_html__("Link URL to the testimonmials author page", "green"),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", "green"),
								"desc" => esc_html__("E-mail of the testimonmials author (to get gravatar)", "green"),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", "green"),
								"desc" => esc_html__("Select or upload photo of testimonmials author or write URL of photo from other site", "green"),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", "green"),
								"desc" => esc_html__("Current testimonials text", "green"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Title
				"trx_title" => array(
					"title" => esc_html__("Title", "green"),
					"desc" => esc_html__("Create header tag (1-6 level) with many styles", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Title content", "green"),
							"desc" => esc_html__("Title content", "green"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"type" => array(
							"title" => esc_html__("Title type", "green"),
							"desc" => esc_html__("Title type (header level)", "green"),
							"divider" => true,
							"value" => "1",
							"type" => "select",
							"options" => array(
								'1' => esc_html__('Header 1', 'green'),
								'2' => esc_html__('Header 2', 'green'),
								'3' => esc_html__('Header 3', 'green'),
								'4' => esc_html__('Header 4', 'green'),
								'5' => esc_html__('Header 5', 'green'),
								'6' => esc_html__('Header 6', 'green'),
							)
						),
						"style" => array(
							"title" => esc_html__("Title style", "green"),
							"desc" => esc_html__("Title style", "green"),
							"value" => "regular",
							"type" => "select",
							"options" => array(
								'regular' => esc_html__('Regular', 'green'),
								'underline' => esc_html__('Underline', 'green'),
								'divider' => esc_html__('Divider', 'green'),
								'iconed' => esc_html__('With icon (image)', 'green')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "green"),
							"desc" => esc_html__("Title text alignment", "green"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						), 
						"font_size" => array(
							"title" => esc_html__("Font_size", "green"),
							"desc" => esc_html__("Custom font size. If empty - use theme default", "green"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "green"),
							"desc" => esc_html__("Custom font weight. If empty or inherit - use theme default", "green"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'inherit' => esc_html__('Default', 'green'),
								'100' => esc_html__('Thin (100)', 'green'),
								'300' => esc_html__('Light (300)', 'green'),
								'400' => esc_html__('Normal (400)', 'green'),
								'600' => esc_html__('Semibold (600)', 'green'),
								'700' => esc_html__('Bold (700)', 'green'),
								'900' => esc_html__('Black (900)', 'green')
							)
						),
						"color" => array(
							"title" => esc_html__("Title color", "green"),
							"desc" => esc_html__("Select color for the title", "green"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('Title font icon',  'green'),
							"desc" => esc_html__("Select font icon for the title from Fontello icons set (if style=iconed)",  'green'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"image" => array(
							"title" => esc_html__('or image icon',  'green'),
							"desc" => esc_html__("Select image icon for the title instead icon above (if style=iconed)",  'green'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "images",
							"size" => "small",
							"options" => $GREEN_GLOBALS['sc_params']['images']
						),
						"picture" => array(
							"title" => esc_html__('or URL for image file', "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", "green"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"image_size" => array(
							"title" => esc_html__('Image (picture) size', "green"),
							"desc" => esc_html__("Select image (picture) size (if style='iconed')", "green"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								'small' => esc_html__('Small', 'green'),
								'medium' => esc_html__('Medium', 'green'),
								'large' => esc_html__('Large', 'green')
							)
						),
						"position" => array(
							"title" => esc_html__('Icon (image) position', "green"),
							"desc" => esc_html__("Select icon (image) position (if style=iconed)", "green"),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "left",
							"type" => "checklist",
							"options" => array(
								'top' => esc_html__('Top', 'green'),
								'left' => esc_html__('Left', 'green')
							)
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Toggles
				"trx_toggles" => array(
					"title" => esc_html__("Toggles", "green"),
					"desc" => esc_html__("Toggles items", "green"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Toggles style", "green"),
							"desc" => esc_html__("Select style for display toggles", "green"),
							"value" => 1,
							"options" => array(
								1 => esc_html__('Style 1', 'green'),
								2 => esc_html__('Style 2', 'green')
							),
							"type" => "radio"
						),
						"counter" => array(
							"title" => esc_html__("Counter", "green"),
							"desc" => esc_html__("Display counter before each toggles title", "green"),
							"value" => "off",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['on_off']
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'green'),
							"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'green'),
							"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'green'),
							"value" => "",
							"type" => "icons",
							"options" => $GREEN_GLOBALS['sc_params']['icons']
						),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_toggles_item",
						"title" => esc_html__("Toggles item", "green"),
						"desc" => esc_html__("Toggles item", "green"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Toggles item title", "green"),
								"desc" => esc_html__("Title for current toggles item", "green"),
								"value" => "",
								"type" => "text"
							),
							"open" => array(
								"title" => esc_html__("Open on show", "green"),
								"desc" => esc_html__("Open current toggles item on show", "green"),
								"value" => "no",
								"type" => "switch",
								"options" => $GREEN_GLOBALS['sc_params']['yes_no']
							),
							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'green'),
								"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'green'),
								"value" => "",
								"type" => "icons",
								"options" => $GREEN_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'green'),
								"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'green'),
								"value" => "",
								"type" => "icons",
								"options" => $GREEN_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Toggles item content", "green"),
								"desc" => esc_html__("Current toggles item content", "green"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $GREEN_GLOBALS['sc_params']['id'],
							"class" => $GREEN_GLOBALS['sc_params']['class'],
							"css" => $GREEN_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Tooltip
				"trx_tooltip" => array(
					"title" => esc_html__("Tooltip", "green"),
					"desc" => esc_html__("Create tooltip for selected text", "green"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "green"),
							"desc" => esc_html__("Tooltip title (required)", "green"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Tipped content", "green"),
							"desc" => esc_html__("Highlighted content with tooltip", "green"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Twitter
				"trx_twitter" => array(
					"title" => esc_html__("Twitter", "green"),
					"desc" => esc_html__("Insert twitter feed into post (page)", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"user" => array(
							"title" => esc_html__("Twitter Username", "green"),
							"desc" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", "green"),
							"value" => "",
							"type" => "text"
						),
						"consumer_key" => array(
							"title" => esc_html__("Consumer Key", "green"),
							"desc" => esc_html__("Consumer Key from the twitter account", "green"),
							"value" => "",
							"type" => "text"
						),
						"consumer_secret" => array(
							"title" => esc_html__("Consumer Secret", "green"),
							"desc" => esc_html__("Consumer Secret from the twitter account", "green"),
							"value" => "",
							"type" => "text"
						),
						"token_key" => array(
							"title" => esc_html__("Token Key", "green"),
							"desc" => esc_html__("Token Key from the twitter account", "green"),
							"value" => "",
							"type" => "text"
						),
						"token_secret" => array(
							"title" => esc_html__("Token Secret", "green"),
							"desc" => esc_html__("Token Secret from the twitter account", "green"),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => esc_html__("Tweets number", "green"),
							"desc" => esc_html__("Tweets number to show", "green"),
							"divider" => true,
							"value" => 3,
							"max" => 20,
							"min" => 1,
							"type" => "spinner"
						),
						"controls" => array(
							"title" => esc_html__("Show arrows", "green"),
							"desc" => esc_html__("Show control buttons", "green"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => esc_html__("Tweets change interval", "green"),
							"desc" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", "green"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "green"),
							"desc" => esc_html__("Alignment of the tweets block", "green"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "green"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "green"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", "green"),
							"desc" => esc_html__("Main background tint: dark or light", "green"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $GREEN_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "green"),
							"desc" => esc_html__("Any background color for this section", "green"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "green"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "green"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "green"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "green"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Video
				"trx_video" => array(
					"title" => esc_html__("Video", "green"),
					"desc" => esc_html__("Insert video player", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for video file", "green"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose video', 'green'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose video file', 'green'),
									'update' => esc_html__('Select video file', 'green')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"ratio" => array(
							"title" => esc_html__("Ratio", "green"),
							"desc" => esc_html__("Ratio of the video", "green"),
							"value" => "16:9",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"16:9" => esc_html__("16:9", 'green'),
								"4:3" => esc_html__("4:3", 'green')
							)
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay video", "green"),
							"desc" => esc_html__("Autoplay video on page load", "green"),
							"value" => "off",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", "green"),
							"desc" => esc_html__("Select block alignment", "green"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['align']
						),
						"image" => array(
							"title" => esc_html__("Cover image", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for video preview", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "green"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", "green"),
							"desc" => esc_html__("Top offset (padding) inside background image to video block (in percent). For example: 3%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", "green"),
							"desc" => esc_html__("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", "green"),
							"desc" => esc_html__("Left offset (padding) inside background image to video block (in percent). For example: 20%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", "green"),
							"desc" => esc_html__("Right offset (padding) inside background image to video block (in percent). For example: 12%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Zoom
				"trx_zoom" => array(
					"title" => esc_html__("Zoom", "green"),
					"desc" => esc_html__("Insert the image with zoom/lens effect", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"effect" => array(
							"title" => esc_html__("Effect", "green"),
							"desc" => esc_html__("Select effect to display overlapping image", "green"),
							"value" => "lens",
							"size" => "medium",
							"type" => "switch",
							"options" => array(
								"lens" => esc_html__('Lens', 'green'),
								"zoom" => esc_html__('Zoom', 'green')
							)
						),
						"url" => array(
							"title" => esc_html__("Main image", "green"),
							"desc" => esc_html__("Select or upload main image", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"over" => array(
							"title" => esc_html__("Overlaping image", "green"),
							"desc" => esc_html__("Select or upload overlaping image", "green"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"align" => array(
							"title" => esc_html__("Float zoom", "green"),
							"desc" => esc_html__("Float zoom to left or right side", "green"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $GREEN_GLOBALS['sc_params']['float']
						), 
						"bg_image" => array(
							"title" => esc_html__("Background image", "green"),
							"desc" => esc_html__("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", "green"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", "green"),
							"desc" => esc_html__("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", "green"),
							"desc" => esc_html__("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", "green"),
							"desc" => esc_html__("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", "green"),
							"desc" => esc_html__("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", "green"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => green_shortcodes_width(),
						"height" => green_shortcodes_height(),
						"top" => $GREEN_GLOBALS['sc_params']['top'],
						"bottom" => $GREEN_GLOBALS['sc_params']['bottom'],
						"left" => $GREEN_GLOBALS['sc_params']['left'],
						"right" => $GREEN_GLOBALS['sc_params']['right'],
						"id" => $GREEN_GLOBALS['sc_params']['id'],
						"class" => $GREEN_GLOBALS['sc_params']['class'],
						"animation" => $GREEN_GLOBALS['sc_params']['animation'],
						"css" => $GREEN_GLOBALS['sc_params']['css']
					)
				)
			);
	
			// Woocommerce Shortcodes list
			//------------------------------------------------------------------
			if (green_exists_woocommerce()) {
				
				// WooCommerce - Cart
				$GREEN_GLOBALS['shortcodes']["woocommerce_cart"] = array(
					"title" => esc_html__("Woocommerce: Cart", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show Cart page", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Checkout
				$GREEN_GLOBALS['shortcodes']["woocommerce_checkout"] = array(
					"title" => esc_html__("Woocommerce: Checkout", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show Checkout page", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - My Account
				$GREEN_GLOBALS['shortcodes']["woocommerce_my_account"] = array(
					"title" => esc_html__("Woocommerce: My Account", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show My Account page", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Order Tracking
				$GREEN_GLOBALS['shortcodes']["woocommerce_order_tracking"] = array(
					"title" => esc_html__("Woocommerce: Order Tracking", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show Order Tracking page", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Shop Messages
				$GREEN_GLOBALS['shortcodes']["shop_messages"] = array(
					"title" => esc_html__("Woocommerce: Shop Messages", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show shop messages", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Product Page
				$GREEN_GLOBALS['shortcodes']["product_page"] = array(
					"title" => esc_html__("Woocommerce: Product Page", "green"),
					"desc" => esc_html__("WooCommerce shortcode: display single product page", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => esc_html__("SKU", "green"),
							"desc" => esc_html__("SKU code of displayed product", "green"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => esc_html__("ID", "green"),
							"desc" => esc_html__("ID of displayed product", "green"),
							"value" => "",
							"type" => "text"
						),
						"posts_per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => "1",
							"min" => 1,
							"type" => "spinner"
						),
						"post_type" => array(
							"title" => esc_html__("Post type", "green"),
							"desc" => esc_html__("Post type for the WP query (leave 'product')", "green"),
							"value" => "product",
							"type" => "text"
						),
						"post_status" => array(
							"title" => esc_html__("Post status", "green"),
							"desc" => esc_html__("Display posts only with this status", "green"),
							"value" => "publish",
							"type" => "select",
							"options" => array(
								"publish" => esc_html__('Publish', 'green'),
								"protected" => esc_html__('Protected', 'green'),
								"private" => esc_html__('Private', 'green'),
								"pending" => esc_html__('Pending', 'green'),
								"draft" => esc_html__('Draft', 'green')
							)
						)
					)
				);
				
				// WooCommerce - Product
				$GREEN_GLOBALS['shortcodes']["product"] = array(
					"title" => esc_html__("Woocommerce: Product", "green"),
					"desc" => esc_html__("WooCommerce shortcode: display one product", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => esc_html__("SKU", "green"),
							"desc" => esc_html__("SKU code of displayed product", "green"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => esc_html__("ID", "green"),
							"desc" => esc_html__("ID of displayed product", "green"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Best Selling Products
				$GREEN_GLOBALS['shortcodes']["best_selling_products"] = array(
					"title" => esc_html__("Woocommerce: Best Selling Products", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show best selling products", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						)
					)
				);
				
				// WooCommerce - Recent Products
				$GREEN_GLOBALS['shortcodes']["recent_products"] = array(
					"title" => esc_html__("Woocommerce: Recent Products", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show recent products", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Related Products
				$GREEN_GLOBALS['shortcodes']["related_products"] = array(
					"title" => esc_html__("Woocommerce: Related Products", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show related products", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"posts_per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 3,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						)
					)
				);
				
				// WooCommerce - Featured Products
				$GREEN_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Featured Products", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show featured products", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Top Rated Products
				$GREEN_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Top Rated Products", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show top rated products", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Sale Products
				$GREEN_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Sale Products", "green"),
					"desc" => esc_html__("WooCommerce shortcode: list products on sale", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product Category
				$GREEN_GLOBALS['shortcodes']["product_category"] = array(
					"title" => esc_html__("Woocommerce: Products from category", "green"),
					"desc" => esc_html__("WooCommerce shortcode: list products in specified category(-ies)", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						),
						"category" => array(
							"title" => esc_html__("Categories", "green"),
							"desc" => esc_html__("Comma separated category slugs", "green"),
							"value" => '',
							"type" => "text"
						),
						"operator" => array(
							"title" => esc_html__("Operator", "green"),
							"desc" => esc_html__("Categories operator", "green"),
							"value" => "IN",
							"type" => "checklist",
							"size" => "medium",
							"options" => array(
								"IN" => esc_html__('IN', 'green'),
								"NOT IN" => esc_html__('NOT IN', 'green'),
								"AND" => esc_html__('AND', 'green')
							)
						)
					)
				);
				
				// WooCommerce - Products
				$GREEN_GLOBALS['shortcodes']["products"] = array(
					"title" => esc_html__("Woocommerce: Products", "green"),
					"desc" => esc_html__("WooCommerce shortcode: list all products", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"skus" => array(
							"title" => esc_html__("SKUs", "green"),
							"desc" => esc_html__("Comma separated SKU codes of products", "green"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => esc_html__("IDs", "green"),
							"desc" => esc_html__("Comma separated ID of products", "green"),
							"value" => "",
							"type" => "text"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product attribute
				$GREEN_GLOBALS['shortcodes']["product_attribute"] = array(
					"title" => esc_html__("Woocommerce: Products by Attribute", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show products with specified attribute", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many products showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for products output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						),
						"attribute" => array(
							"title" => esc_html__("Attribute", "green"),
							"desc" => esc_html__("Attribute name", "green"),
							"value" => "",
							"type" => "text"
						),
						"filter" => array(
							"title" => esc_html__("Filter", "green"),
							"desc" => esc_html__("Attribute value", "green"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Products Categories
				$GREEN_GLOBALS['shortcodes']["product_categories"] = array(
					"title" => esc_html__("Woocommerce: Product Categories", "green"),
					"desc" => esc_html__("WooCommerce shortcode: show categories with products", "green"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"number" => array(
							"title" => esc_html__("Number", "green"),
							"desc" => esc_html__("How many categories showed", "green"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "green"),
							"desc" => esc_html__("How many columns per row use for categories output", "green"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'green'),
								"title" => esc_html__('Title', 'green')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "green"),
							"desc" => esc_html__("Sorting order for products output", "green"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $GREEN_GLOBALS['sc_params']['ordering']
						),
						"parent" => array(
							"title" => esc_html__("Parent", "green"),
							"desc" => esc_html__("Parent category slug", "green"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => esc_html__("IDs", "green"),
							"desc" => esc_html__("Comma separated ID of products", "green"),
							"value" => "",
							"type" => "text"
						),
						"hide_empty" => array(
							"title" => esc_html__("Hide empty", "green"),
							"desc" => esc_html__("Hide empty categories", "green"),
							"value" => "yes",
							"type" => "switch",
							"options" => $GREEN_GLOBALS['sc_params']['yes_no']
						)
					)
				);

			}
			
			do_action('green_action_shortcodes_list');

		}
	}
}
?>