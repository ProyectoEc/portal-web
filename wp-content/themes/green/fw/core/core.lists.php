<?php
/**
 * ThemeREX Framework: return lists
 *
 * @package green
 * @since green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Return list of the animations
if ( !function_exists( 'green_get_list_animations' ) ) {
	function green_get_list_animations($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_animations']))
			$list = $GREEN_GLOBALS['list_animations'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'green');
			$list['bounced']		= esc_html__('Bounced',		'green');
			$list['flash']			= esc_html__('Flash',		'green');
			$list['flip']			= esc_html__('Flip',		'green');
			$list['pulse']			= esc_html__('Pulse',		'green');
			$list['rubberBand']		= esc_html__('Rubber Band',	'green');
			$list['shake']			= esc_html__('Shake',		'green');
			$list['swing']			= esc_html__('Swing',		'green');
			$list['tada']			= esc_html__('Tada',		'green');
			$list['wobble']			= esc_html__('Wobble',		'green');
			$GREEN_GLOBALS['list_animations'] = $list = apply_filters('green_filter_list_animations', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'green_get_list_animations_in' ) ) {
	function green_get_list_animations_in($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_animations_in']))
			$list = $GREEN_GLOBALS['list_animations_in'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'green');
			$list['bounceIn']		= esc_html__('Bounce In',			'green');
			$list['bounceInUp']		= esc_html__('Bounce In Up',		'green');
			$list['bounceInDown']	= esc_html__('Bounce In Down',		'green');
			$list['bounceInLeft']	= esc_html__('Bounce In Left',		'green');
			$list['bounceInRight']	= esc_html__('Bounce In Right',		'green');
			$list['fadeIn']			= esc_html__('Fade In',				'green');
			$list['fadeInUp']		= esc_html__('Fade In Up',			'green');
			$list['fadeInDown']		= esc_html__('Fade In Down',		'green');
			$list['fadeInLeft']		= esc_html__('Fade In Left',		'green');
			$list['fadeInRight']	= esc_html__('Fade In Right',		'green');
			$list['fadeInUpBig']	= esc_html__('Fade In Up Big',		'green');
			$list['fadeInDownBig']	= esc_html__('Fade In Down Big',	'green');
			$list['fadeInLeftBig']	= esc_html__('Fade In Left Big',	'green');
			$list['fadeInRightBig']	= esc_html__('Fade In Right Big',	'green');
			$list['flipInX']		= esc_html__('Flip In X',			'green');
			$list['flipInY']		= esc_html__('Flip In Y',			'green');
			$list['lightSpeedIn']	= esc_html__('Light Speed In',		'green');
			$list['rotateIn']		= esc_html__('Rotate In',			'green');
			$list['rotateInUpLeft']		= esc_html__('Rotate In Down Left',	'green');
			$list['rotateInUpRight']	= esc_html__('Rotate In Up Right',	'green');
			$list['rotateInDownLeft']	= esc_html__('Rotate In Up Left',	'green');
			$list['rotateInDownRight']	= esc_html__('Rotate In Down Right','green');
			$list['rollIn']				= esc_html__('Roll In',			'green');
			$list['slideInUp']			= esc_html__('Slide In Up',		'green');
			$list['slideInDown']		= esc_html__('Slide In Down',	'green');
			$list['slideInLeft']		= esc_html__('Slide In Left',	'green');
			$list['slideInRight']		= esc_html__('Slide In Right',	'green');
			$list['zoomIn']				= esc_html__('Zoom In',			'green');
			$list['zoomInUp']			= esc_html__('Zoom In Up',		'green');
			$list['zoomInDown']			= esc_html__('Zoom In Down',	'green');
			$list['zoomInLeft']			= esc_html__('Zoom In Left',	'green');
			$list['zoomInRight']		= esc_html__('Zoom In Right',	'green');
			$GREEN_GLOBALS['list_animations_in'] = $list = apply_filters('green_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'green_get_list_animations_out' ) ) {
	function green_get_list_animations_out($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_animations_out']))
			$list = $GREEN_GLOBALS['list_animations_out'];
		else {
			$list = array();
			$list['none']			= esc_html__('- None -',	'green');
			$list['bounceOut']		= esc_html__('Bounce Out',			'green');
			$list['bounceOutUp']	= esc_html__('Bounce Out Up',		'green');
			$list['bounceOutDown']	= esc_html__('Bounce Out Down',		'green');
			$list['bounceOutLeft']	= esc_html__('Bounce Out Left',		'green');
			$list['bounceOutRight']	= esc_html__('Bounce Out Right',	'green');
			$list['fadeOut']		= esc_html__('Fade Out',			'green');
			$list['fadeOutUp']		= esc_html__('Fade Out Up',			'green');
			$list['fadeOutDown']	= esc_html__('Fade Out Down',		'green');
			$list['fadeOutLeft']	= esc_html__('Fade Out Left',		'green');
			$list['fadeOutRight']	= esc_html__('Fade Out Right',		'green');
			$list['fadeOutUpBig']	= esc_html__('Fade Out Up Big',		'green');
			$list['fadeOutDownBig']	= esc_html__('Fade Out Down Big',	'green');
			$list['fadeOutLeftBig']	= esc_html__('Fade Out Left Big',	'green');
			$list['fadeOutRightBig']= esc_html__('Fade Out Right Big',	'green');
			$list['flipOutX']		= esc_html__('Flip Out X',			'green');
			$list['flipOutY']		= esc_html__('Flip Out Y',			'green');
			$list['hinge']			= esc_html__('Hinge Out',			'green');
			$list['lightSpeedOut']	= esc_html__('Light Speed Out',		'green');
			$list['rotateOut']		= esc_html__('Rotate Out',			'green');
			$list['rotateOutUpLeft']	= esc_html__('Rotate Out Down Left',	'green');
			$list['rotateOutUpRight']	= esc_html__('Rotate Out Up Right',		'green');
			$list['rotateOutDownLeft']	= esc_html__('Rotate Out Up Left',		'green');
			$list['rotateOutDownRight']	= esc_html__('Rotate Out Down Right',	'green');
			$list['rollOut']			= esc_html__('Roll Out',		'green');
			$list['slideOutUp']			= esc_html__('Slide Out Up',		'green');
			$list['slideOutDown']		= esc_html__('Slide Out Down',	'green');
			$list['slideOutLeft']		= esc_html__('Slide Out Left',	'green');
			$list['slideOutRight']		= esc_html__('Slide Out Right',	'green');
			$list['zoomOut']			= esc_html__('Zoom Out',			'green');
			$list['zoomOutUp']			= esc_html__('Zoom Out Up',		'green');
			$list['zoomOutDown']		= esc_html__('Zoom Out Down',	'green');
			$list['zoomOutLeft']		= esc_html__('Zoom Out Left',	'green');
			$list['zoomOutRight']		= esc_html__('Zoom Out Right',	'green');
			$GREEN_GLOBALS['list_animations_out'] = $list = apply_filters('green_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'green_get_list_categories' ) ) {
	function green_get_list_categories($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_categories']))
			$list = $GREEN_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			foreach ($taxonomies as $cat) {
				$list[$cat->term_id] = $cat->name;
			}
			$GREEN_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'green_get_list_terms' ) ) {
	function green_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_taxonomies_'.($taxonomy)]))
			$list = $GREEN_GLOBALS['list_taxonomies_'.($taxonomy)];
		else {
			$list = array();
			$args = array(
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => $taxonomy,
				'pad_counts'               => false );
			$taxonomies = get_terms( $taxonomy, $args );
			foreach ($taxonomies as $cat) {
				$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
			}
			$GREEN_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'green_get_list_posts_types' ) ) {
	function green_get_list_posts_types($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_posts_types']))
			$list = $GREEN_GLOBALS['list_posts_types'];
		else {
			$list = array();
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'green');
			foreach ($types as $t) {
				if ($t == 'post') continue;
				$list[$t] = green_strtoproper($t);
			}
			*/
			// Return only theme inheritance supported post types
			$GREEN_GLOBALS['list_posts_types'] = $list = apply_filters('green_filter_list_post_types', array());
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'green_get_list_posts' ) ) {
	function green_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $GREEN_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($GREEN_GLOBALS[$hash]))
			$list = $GREEN_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'green');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			foreach ($posts as $post) {
				$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
			}
			$GREEN_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return list of registered users
if ( !function_exists( 'green_get_list_users' ) ) {
	function green_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_users']))
			$list = $GREEN_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'green');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			foreach ($users as $user) {
				$accept = true;
				if (is_array($user->roles)) {
					if (count($user->roles) > 0) {
						$accept = false;
						foreach ($user->roles as $role) {
							if (in_array($role, $roles)) {
								$accept = true;
								break;
							}
						}
					}
				}
				if ($accept) $list[$user->user_login] = $user->display_name;
			}
			$GREEN_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return sliders list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'green_get_list_sliders' ) ) {
	function green_get_list_sliders($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_sliders']))
			$list = $GREEN_GLOBALS['list_sliders'];
		else {
			$list = array();
			$list["swiper"] = esc_html__("Posts slider (Swiper)", 'green');
			if (green_exists_revslider())
				$list["revo"] = esc_html__("Layer slider (Revolution)", 'green');
			if (green_exists_royalslider())
				$list["royal"] = esc_html__("Layer slider (Royal)", 'green');
			$GREEN_GLOBALS['list_sliders'] = $list = apply_filters('green_filter_list_sliders', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with popup engines
if ( !function_exists( 'green_get_list_popup_engines' ) ) {
	function green_get_list_popup_engines($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_popup_engines']))
			$list = $GREEN_GLOBALS['list_popup_engines'];
		else {
			$list = array();
			$list["pretty"] = esc_html__("Pretty photo", 'green');
			$list["magnific"] = esc_html__("Magnific popup", 'green');
			$GREEN_GLOBALS['list_popup_engines'] = $list = apply_filters('green_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'green_get_list_menus' ) ) {
	function green_get_list_menus($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_menus']))
			$list = $GREEN_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = esc_html__("Default", 'green');
			$menus = wp_get_nav_menus();
			if ($menus) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$GREEN_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'green_get_list_sidebars' ) ) {
	function green_get_list_sidebars($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_sidebars'])) {
			$list = $GREEN_GLOBALS['list_sidebars'];
		} else {
			$list = isset($GREEN_GLOBALS['registered_sidebars']) ? $GREEN_GLOBALS['registered_sidebars'] : array();
			$GREEN_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'green_get_list_sidebars_positions' ) ) {
	function green_get_list_sidebars_positions($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_sidebars_positions']))
			$list = $GREEN_GLOBALS['list_sidebars_positions'];
		else {
			$list = array();
			$list['left']  = esc_html__('Left',  'green');
			$list['right'] = esc_html__('Right', 'green');
			$GREEN_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'green_get_sidebar_class' ) ) {
	function green_get_sidebar_class($style, $pos) {
		return green_sc_param_is_off($style) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($pos);
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'green_get_list_body_styles' ) ) {
	function green_get_list_body_styles($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_body_styles']))
			$list = $GREEN_GLOBALS['list_body_styles'];
		else {
			$list = array();
			$list['boxed']		= esc_html__('Boxed',		'green');
			$list['wide']		= esc_html__('Wide',		'green');
			$list['fullwide']	= esc_html__('Fullwide',	'green');
			$list['fullscreen']	= esc_html__('Fullscreen',	'green');
			$GREEN_GLOBALS['list_body_styles'] = $list = apply_filters('green_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'green_get_list_skins' ) ) {
	function green_get_list_skins($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_skins']))
			$list = $GREEN_GLOBALS['list_skins'];
		else
			$GREEN_GLOBALS['list_skins'] = $list = green_get_list_folders("skins");
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'green_get_list_themes' ) ) {
	function green_get_list_themes($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_themes']))
			$list = $GREEN_GLOBALS['list_themes'];
		else
			$GREEN_GLOBALS['list_themes'] = $list = green_get_list_files("css/themes");
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'green_get_list_templates' ) ) {
	function green_get_list_templates($mode='') {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_templates_'.($mode)]))
			$list = $GREEN_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			foreach ($GREEN_GLOBALS['registered_templates'] as $k=>$v) {
				if ($mode=='' || green_strpos($v['mode'], $mode)!==false)
					$list[$k] = !empty($v['title']) ? $v['title'] : green_strtoproper($v['layout']);
			}
			$GREEN_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'green_get_list_templates_blog' ) ) {
	function green_get_list_templates_blog($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_templates_blog']))
			$list = $GREEN_GLOBALS['list_templates_blog'];
		else {
			$list = green_get_list_templates('blog');
			$GREEN_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'green_get_list_templates_blogger' ) ) {
	function green_get_list_templates_blogger($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_templates_blogger']))
			$list = $GREEN_GLOBALS['list_templates_blogger'];
		else {
			$list = green_array_merge(green_get_list_templates('blogger'), green_get_list_templates('blog'));
			$GREEN_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'green_get_list_templates_single' ) ) {
	function green_get_list_templates_single($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_templates_single']))
			$list = $GREEN_GLOBALS['list_templates_single'];
		else {
			$list = green_get_list_templates('single');
			$GREEN_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'green_get_list_article_styles' ) ) {
	function green_get_list_article_styles($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_article_styles']))
			$list = $GREEN_GLOBALS['list_article_styles'];
		else {
			$list = array();
			$list["boxed"]   = esc_html__('Boxed', 'green');
			$list["stretch"] = esc_html__('Stretch', 'green');
			$GREEN_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return color schemes list, prepended inherit
if ( !function_exists( 'green_get_list_color_schemes' ) ) {
	function green_get_list_color_schemes($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_color_schemes']))
			$list = $GREEN_GLOBALS['list_color_schemes'];
		else {
			$list = array();
			if (!empty($GREEN_GLOBALS['color_schemes'])) {
				foreach ($GREEN_GLOBALS['color_schemes'] as $k=>$v) {
					$list[$k] = $v['title'];
				}
			}
			$GREEN_GLOBALS['list_color_schemes'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return button styles list, prepended inherit
if ( !function_exists( 'green_get_list_button_styles' ) ) {
	function green_get_list_button_styles($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_button_styles']))
			$list = $GREEN_GLOBALS['list_button_styles'];
		else {
			$list = array();
			$list["custom"]	= esc_html__('Custom', 'green');
			$list["link"] 	= esc_html__('As links', 'green');
			$list["menu"] 	= esc_html__('As main menu', 'green');
			$list["user"] 	= esc_html__('As user menu', 'green');
			$GREEN_GLOBALS['list_button_styles'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'green_get_list_post_formats_filters' ) ) {
	function green_get_list_post_formats_filters($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_post_formats_filters']))
			$list = $GREEN_GLOBALS['list_post_formats_filters'];
		else {
			$list = array();
			$list["no"]      = esc_html__('All posts', 'green');
			$list["thumbs"]  = esc_html__('With thumbs', 'green');
			$list["reviews"] = esc_html__('With reviews', 'green');
			$list["video"]   = esc_html__('With videos', 'green');
			$list["audio"]   = esc_html__('With audios', 'green');
			$list["gallery"] = esc_html__('With galleries', 'green');
			$GREEN_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'green_get_list_portfolio_filters' ) ) {
	function green_get_list_portfolio_filters($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_portfolio_filters']))
			$list = $GREEN_GLOBALS['list_portfolio_filters'];
		else {
			$list = array();
			$list["hide"] = esc_html__('Hide', 'green');
			$list["tags"] = esc_html__('Tags', 'green');
			$list["categories"] = esc_html__('Categories', 'green');
			$GREEN_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'green_get_list_hovers' ) ) {
	function green_get_list_hovers($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_hovers']))
			$list = $GREEN_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'green');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'green');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'green');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'green');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'green');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'green');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'green');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'green');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'green');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'green');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'green');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'green');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'green');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'green');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'green');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'green');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'green');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'green');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'green');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'green');
			$list['square effect1']  = esc_html__('Square Effect 1',  'green');
			$list['square effect2']  = esc_html__('Square Effect 2',  'green');
			$list['square effect3']  = esc_html__('Square Effect 3',  'green');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'green');
			$list['square effect5']  = esc_html__('Square Effect 5',  'green');
			$list['square effect6']  = esc_html__('Square Effect 6',  'green');
			$list['square effect7']  = esc_html__('Square Effect 7',  'green');
			$list['square effect8']  = esc_html__('Square Effect 8',  'green');
			$list['square effect9']  = esc_html__('Square Effect 9',  'green');
			$list['square effect10'] = esc_html__('Square Effect 10',  'green');
			$list['square effect11'] = esc_html__('Square Effect 11',  'green');
			$list['square effect12'] = esc_html__('Square Effect 12',  'green');
			$list['square effect13'] = esc_html__('Square Effect 13',  'green');
			$list['square effect14'] = esc_html__('Square Effect 14',  'green');
			$list['square effect15'] = esc_html__('Square Effect 15',  'green');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'green');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'green');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'green');
			$GREEN_GLOBALS['list_hovers'] = $list = apply_filters('green_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'green_get_list_hovers_directions' ) ) {
	function green_get_list_hovers_directions($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_hovers_directions']))
			$list = $GREEN_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = esc_html__('Left to Right',  'green');
			$list['right_to_left'] = esc_html__('Right to Left',  'green');
			$list['top_to_bottom'] = esc_html__('Top to Bottom',  'green');
			$list['bottom_to_top'] = esc_html__('Bottom to Top',  'green');
			$list['scale_up']      = esc_html__('Scale Up',  'green');
			$list['scale_down']    = esc_html__('Scale Down',  'green');
			$list['scale_down_up'] = esc_html__('Scale Down-Up',  'green');
			$list['from_left_and_right'] = esc_html__('From Left and Right',  'green');
			$list['from_top_and_bottom'] = esc_html__('From Top and Bottom',  'green');
			$GREEN_GLOBALS['list_hovers_directions'] = $list = apply_filters('green_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'green_get_list_label_positions' ) ) {
	function green_get_list_label_positions($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_label_positions']))
			$list = $GREEN_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= esc_html__('Top',		'green');
			$list['bottom']	= esc_html__('Bottom',		'green');
			$list['left']	= esc_html__('Left',		'green');
			$list['over']	= esc_html__('Over',		'green');
			$GREEN_GLOBALS['list_label_positions'] = $list = apply_filters('green_filter_label_positions', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return background tints list, prepended inherit
if ( !function_exists( 'green_get_list_bg_tints' ) ) {
	function green_get_list_bg_tints($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_bg_tints']))
			$list = $GREEN_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['none']  = esc_html__('None',  'green');
			$list['light'] = esc_html__('Light','green');
			$list['dark']  = esc_html__('Dark',  'green');
			$GREEN_GLOBALS['list_bg_tints'] = $list = apply_filters('green_filter_bg_tints', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return background tints list for sidebars, prepended inherit
if ( !function_exists( 'green_get_list_sidebar_styles' ) ) {
	function green_get_list_sidebar_styles($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_sidebar_styles']))
			$list = $GREEN_GLOBALS['list_sidebar_styles'];
		else {
			$list = array();
			$list['none']  = esc_html__('None',  'green');
			$list['light white'] = esc_html__('White','green');
			$list['light'] = esc_html__('Light','green');
			$list['dark']  = esc_html__('Dark',  'green');
			$GREEN_GLOBALS['list_sidebar_styles'] = $list = apply_filters('green_filter_sidebar_styles', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'green_get_list_field_types' ) ) {
	function green_get_list_field_types($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_field_types']))
			$list = $GREEN_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = esc_html__('Text',  'green');
			$list['textarea'] = esc_html__('Text Area','green');
			$list['password'] = esc_html__('Password',  'green');
			$list['radio']    = esc_html__('Radio',  'green');
			$list['checkbox'] = esc_html__('Checkbox',  'green');
			$list['button']   = esc_html__('Button','green');
			$GREEN_GLOBALS['list_field_types'] = $list = apply_filters('green_filter_field_types', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'green_get_list_googlemap_styles' ) ) {
	function green_get_list_googlemap_styles($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_googlemap_styles']))
			$list = $GREEN_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = esc_html__('Default', 'green');
			$list['simple'] = esc_html__('Simple', 'green');
			$list['greyscale'] = esc_html__('Greyscale', 'green');
			$list['greyscale2'] = esc_html__('Greyscale 2', 'green');
			$list['invert'] = esc_html__('Invert', 'green');
			$list['dark'] = esc_html__('Dark', 'green');
			$list['style1'] = esc_html__('Custom style 1', 'green');
			$list['style2'] = esc_html__('Custom style 2', 'green');
			$list['style3'] = esc_html__('Custom style 3', 'green');
			$GREEN_GLOBALS['list_googlemap_styles'] = $list = apply_filters('green_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'green_get_list_icons' ) ) {
	function green_get_list_icons($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_icons']))
			$list = $GREEN_GLOBALS['list_icons'];
		else
			$GREEN_GLOBALS['list_icons'] = $list = green_parse_icons_classes(green_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'green_get_list_socials' ) ) {
	function green_get_list_socials($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_socials']))
			$list = $GREEN_GLOBALS['list_socials'];
		else
			$GREEN_GLOBALS['list_socials'] = $list = green_get_list_files("images/socials", "png");
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'green_get_list_flags' ) ) {
	function green_get_list_flags($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_flags']))
			$list = $GREEN_GLOBALS['list_flags'];
		else
			$GREEN_GLOBALS['list_flags'] = $list = green_get_list_files("images/flags", "png");
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'green_get_list_yesno' ) ) {
	function green_get_list_yesno($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_yesno']))
			$list = $GREEN_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = esc_html__("Yes", 'green');
			$list["no"]  = esc_html__("No", 'green');
			$GREEN_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'green_get_list_onoff' ) ) {
	function green_get_list_onoff($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_onoff']))
			$list = $GREEN_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = esc_html__("On", 'green');
			$list["off"] = esc_html__("Off", 'green');
			$GREEN_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'green_get_list_showhide' ) ) {
	function green_get_list_showhide($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_showhide']))
			$list = $GREEN_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = esc_html__("Show", 'green');
			$list["hide"] = esc_html__("Hide", 'green');
			$GREEN_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'green_get_list_orderings' ) ) {
	function green_get_list_orderings($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_orderings']))
			$list = $GREEN_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = esc_html__("Ascending", 'green');
			$list["desc"] = esc_html__("Descending", 'green');
			$GREEN_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'green_get_list_directions' ) ) {
	function green_get_list_directions($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_directions']))
			$list = $GREEN_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = esc_html__("Horizontal", 'green');
			$list["vertical"] = esc_html__("Vertical", 'green');
			$GREEN_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'green_get_list_floats' ) ) {
	function green_get_list_floats($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_floats']))
			$list = $GREEN_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'green');
			$list["left"] = esc_html__("Float Left", 'green');
			$list["right"] = esc_html__("Float Right", 'green');
			$GREEN_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'green_get_list_alignments' ) ) {
	function green_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_alignments']))
			$list = $GREEN_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'green');
			$list["left"] = esc_html__("Left", 'green');
			$list["center"] = esc_html__("Center", 'green');
			$list["right"] = esc_html__("Right", 'green');
			if ($justify) $list["justify"] = esc_html__("Justify", 'green');
			$GREEN_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'green_get_list_sortings' ) ) {
	function green_get_list_sortings($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_sortings']))
			$list = $GREEN_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = esc_html__("Date", 'green');
			$list["title"] = esc_html__("Alphabetically", 'green');
			$list["views"] = esc_html__("Popular (views count)", 'green');
			$list["comments"] = esc_html__("Most commented (comments count)", 'green');
			$list["author_rating"] = esc_html__("Author rating", 'green');
			$list["users_rating"] = esc_html__("Visitors (users) rating", 'green');
			$list["random"] = esc_html__("Random", 'green');
			$GREEN_GLOBALS['list_sortings'] = $list = apply_filters('green_filter_list_sortings', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'green_get_list_columns' ) ) {
	function green_get_list_columns($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_columns']))
			$list = $GREEN_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'green');
			$list["1_1"] = esc_html__("100%", 'green');
			$list["1_2"] = esc_html__("1/2", 'green');
			$list["1_3"] = esc_html__("1/3", 'green');
			$list["2_3"] = esc_html__("2/3", 'green');
			$list["1_4"] = esc_html__("1/4", 'green');
			$list["3_4"] = esc_html__("3/4", 'green');
			$list["1_5"] = esc_html__("1/5", 'green');
			$list["2_5"] = esc_html__("2/5", 'green');
			$list["3_5"] = esc_html__("3/5", 'green');
			$list["4_5"] = esc_html__("4/5", 'green');
			$list["1_6"] = esc_html__("1/6", 'green');
			$list["5_6"] = esc_html__("5/6", 'green');
			$list["1_7"] = esc_html__("1/7", 'green');
			$list["2_7"] = esc_html__("2/7", 'green');
			$list["3_7"] = esc_html__("3/7", 'green');
			$list["4_7"] = esc_html__("4/7", 'green');
			$list["5_7"] = esc_html__("5/7", 'green');
			$list["6_7"] = esc_html__("6/7", 'green');
			$list["1_8"] = esc_html__("1/8", 'green');
			$list["3_8"] = esc_html__("3/8", 'green');
			$list["5_8"] = esc_html__("5/8", 'green');
			$list["7_8"] = esc_html__("7/8", 'green');
			$list["1_9"] = esc_html__("1/9", 'green');
			$list["2_9"] = esc_html__("2/9", 'green');
			$list["4_9"] = esc_html__("4/9", 'green');
			$list["5_9"] = esc_html__("5/9", 'green');
			$list["7_9"] = esc_html__("7/9", 'green');
			$list["8_9"] = esc_html__("8/9", 'green');
			$list["1_10"]= esc_html__("1/10", 'green');
			$list["3_10"]= esc_html__("3/10", 'green');
			$list["7_10"]= esc_html__("7/10", 'green');
			$list["9_10"]= esc_html__("9/10", 'green');
			$list["1_11"]= esc_html__("1/11", 'green');
			$list["2_11"]= esc_html__("2/11", 'green');
			$list["3_11"]= esc_html__("3/11", 'green');
			$list["4_11"]= esc_html__("4/11", 'green');
			$list["5_11"]= esc_html__("5/11", 'green');
			$list["6_11"]= esc_html__("6/11", 'green');
			$list["7_11"]= esc_html__("7/11", 'green');
			$list["8_11"]= esc_html__("8/11", 'green');
			$list["9_11"]= esc_html__("9/11", 'green');
			$list["10_11"]= esc_html__("10/11", 'green');
			$list["1_12"]= esc_html__("1/12", 'green');
			$list["5_12"]= esc_html__("5/12", 'green');
			$list["7_12"]= esc_html__("7/12", 'green');
			$list["10_12"]= esc_html__("10/12", 'green');
			$list["11_12"]= esc_html__("11/12", 'green');
			$GREEN_GLOBALS['list_columns'] = $list = apply_filters('green_filter_list_columns', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'green_get_list_dedicated_locations' ) ) {
	function green_get_list_dedicated_locations($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_dedicated_locations']))
			$list = $GREEN_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = esc_html__('As in the post defined', 'green');
			$list["center"]  = esc_html__('Above the text of the post', 'green');
			$list["left"]    = esc_html__('To the left the text of the post', 'green');
			$list["right"]   = esc_html__('To the right the text of the post', 'green');
			$list["alter"]   = esc_html__('Alternates for each post', 'green');
			$GREEN_GLOBALS['list_dedicated_locations'] = $list = apply_filters('green_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'green_get_post_format_name' ) ) {
	function green_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'green') : esc_html__('galleries', 'green');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'green') : esc_html__('videos', 'green');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'green') : esc_html__('audios', 'green');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'green') : esc_html__('images', 'green');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'green') : esc_html__('quotes', 'green');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'green') : esc_html__('links', 'green');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'green') : esc_html__('statuses', 'green');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'green') : esc_html__('asides', 'green');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'green') : esc_html__('chats', 'green');
		else						$name = $single ? esc_html__('standard', 'green') : esc_html__('standards', 'green');
		return apply_filters('green_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'green_get_post_format_icon' ) ) {
	function green_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'picture-2';
		else if ($format=='video')	$icon .= 'video-2';
		else if ($format=='audio')	$icon .= 'musical-2';
		else if ($format=='image')	$icon .= 'picture-boxed-2';
		else if ($format=='quote')	$icon .= 'quote-2';
		else if ($format=='link')	$icon .= 'link-2';
		else if ($format=='status')	$icon .= 'agenda-2';
		else if ($format=='aside')	$icon .= 'chat-2';
		else if ($format=='chat')	$icon .= 'chat-all-2';
		else						$icon .= 'book-2';
		return apply_filters('green_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'green_get_list_fonts_styles' ) ) {
	function green_get_list_fonts_styles($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_fonts_styles']))
			$list = $GREEN_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = esc_html__('I','green');
			$list['u'] = esc_html__('U', 'green');
			$GREEN_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'green_get_list_fonts' ) ) {
	function green_get_list_fonts($prepend_inherit=false) {
		global $GREEN_GLOBALS;
		if (isset($GREEN_GLOBALS['list_fonts']))
			$list = $GREEN_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = green_array_merge($list, green_get_list_fonts_custom());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>green_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$GREEN_GLOBALS['list_fonts'] = $list = apply_filters('green_filter_list_fonts', $list);
		}
		return $prepend_inherit ? green_array_merge(array('inherit' => esc_html__("Inherit", 'green')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'green_get_list_fonts_custom' ) ) {
	function green_get_list_fonts_custom($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = green_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? green_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? green_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'green').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>