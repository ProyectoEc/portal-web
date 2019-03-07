<?php
/**
 * ThemeREX Framework: Theme specific actions
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'green_core_theme_setup' ) ) {
	add_action( 'green_action_before_init_theme', 'green_core_theme_setup', 11 );
	function green_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 

 		// Autogenerate title tag
		add_theme_support('title-tag');

		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(green_get_file_url('css/editor-style.css'));	
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'green', green_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */

			// Get theme calendar (instead standard WP calendar) to support Events
			add_filter( 'get_calendar',						'green_get_calendar' );

			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'green_wp_title', 10, 2);
			}

			// Add main menu classes
			//add_filter('wp_nav_menu_objects', 			'green_add_mainmenu_classes', 10, 2);

			// Prepare logo text
			add_filter('green_filter_prepare_logo_text',	'green_prepare_logo_text', 10, 1);

			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'green_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'green_callback_frontend_editor_save');
			add_action('wp_ajax_nopriv_frontend_editor_save', 'green_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'green_callback_frontend_editor_delete');
			add_action('wp_ajax_nopriv_frontend_editor_delete', 'green_callback_frontend_editor_delete');

			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'green_core_frontend_scripts');
			add_action('wp_footer',		 					'green_core_frontend_scripts_inline');
			add_action('green_action_add_scripts_inline','green_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('green_action_prepare_globals',	'green_core_prepare_globals');

		}

		// Register theme specific nav menus
		green_register_theme_menus();

		// Register theme specific sidebars
		green_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function green_core_init_theme() {
	global $GREEN_GLOBALS;
	if (!empty($GREEN_GLOBALS['theme_inited'])) return;
	$GREEN_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (green_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	green_load_custom_options();

	// Load skin
	$skin = green_esc(green_get_custom_option('theme_skin'));
	$GREEN_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(green_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once( green_get_file_dir('skins/'.($skin).'/skin.php') );
	}

	// Fire init theme actions (after custom options loaded)
	do_action('green_action_init_theme');

	// Prepare theme core global variables
	do_action('green_action_prepare_globals');

	// Fire after init theme actions
	do_action('green_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'green_core_prepare_globals' ) ) {
	function green_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $GREEN_GLOBALS;
			$GREEN_GLOBALS['ajax_nonce'] = wp_create_nonce('ajax_nonce');
			$GREEN_GLOBALS['ajax_url'] = admin_url('admin-ajax.php');

			// Logo text and slogan
			$GREEN_GLOBALS['logo_text'] = apply_filters('green_filter_prepare_logo_text', green_get_custom_option('logo_text'));
			$slogan = green_get_custom_option('logo_slogan');
			if (!$slogan) $slogan = get_bloginfo ( 'description' );
			$GREEN_GLOBALS['logo_slogan'] = $slogan;
			
			// Logo image and icons from skin
			$logo_side   = green_get_logo_icon('logo_side');
			$logo_fixed  = green_get_logo_icon('logo_fixed');
			$logo_footer = green_get_logo_icon('logo_footer');
			$GREEN_GLOBALS['logo_icon']   = green_get_logo_icon('logo_icon');
			$GREEN_GLOBALS['logo_dark']   = green_get_logo_icon('logo_dark');
			$GREEN_GLOBALS['logo_light']  = green_get_logo_icon('logo_light');
			$GREEN_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $GREEN_GLOBALS['logo_dark'];
			$GREEN_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $GREEN_GLOBALS['logo_dark'];
			$GREEN_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $GREEN_GLOBALS['logo_dark'];

			$shop_mode = '';
			if (green_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = green_get_value_gpc('green_shop_mode');
			if (empty($shop_mode))
				$shop_mode = green_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$GREEN_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'green_get_logo_icon' ) ) {
	function green_get_logo_icon($slug) {
		global $GREEN_GLOBALS;
		$skin = green_esc($GREEN_GLOBALS['theme_skin']);
		$logo_icon = green_get_custom_option($slug);
		if (empty($logo_icon) && green_get_theme_option('logo_from_skin')=='yes' && file_exists(green_get_file_dir('skins/' . ($skin) . '/images/' . ($slug) . '.png')))
			$logo_icon = green_get_file_url('skins/' . ($skin) . '/images/' . ($slug) . '.png');
		return $logo_icon;
	}
}


// Add menu locations
if ( !function_exists( 'green_register_theme_menus' ) ) {
	function green_register_theme_menus() {
		register_nav_menus(apply_filters('green_filter_add_theme_menus', array(
			'menu_main' => esc_html__('Main Menu', 'green'),
			'menu_user' => esc_html__('User Menu', 'green'),
			'menu_side' => esc_html__('Side Menu', 'green')
			)));
	}
}


// Register widgetized area
if ( !function_exists( 'green_register_theme_sidebars' ) ) {
	function green_register_theme_sidebars($sidebars=array()) {
		global $GREEN_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = green_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'green_filter_add_theme_sidebars', $sidebars );
		$GREEN_GLOBALS['registered_sidebars'] = $sidebars;
		if (count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h5 class="widget_title">',
					'after_title'   => '</h5>',
					) );
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'green_core_frontend_scripts' ) ) {
	function green_core_frontend_scripts() {
		global $wp_styles, $GREEN_GLOBALS;
		
		// Modernizr will load in head before other scripts and styles
		//green_enqueue_script( 'green-core-modernizr-script', green_get_file_url('js/modernizr.js'), array(), null, false );
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = green_get_list_fonts(false);
		$theme_fonts = array();
		if (green_get_custom_option('typography_custom')=='yes') {
			$selectors = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
			foreach ($selectors as $s) {
				$font = green_get_custom_option('typography_'.($s).'_font');
				if (!empty($font)) $theme_fonts[$font] = 1;
			}
		}

		if ( is_singular() && !green_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', 'wp-includes/js/comment-reply', array(), false, true );
		}



        // Prepare current skin fonts
        $theme_fonts = apply_filters('green_filter_used_fonts', $theme_fonts);
        // Link to selected fonts
        foreach ($theme_fonts as $font=>$v) {
            if (isset($fonts[$font])) {
                $font_name = ($pos=green_strpos($font,' ('))!==false ? green_substr($font, 0, $pos) : $font;
                $css = !empty($fonts[$font]['css'])
                    ? $fonts[$font]['css']
                    : green_get_protocol().'://fonts.googleapis.com/css?family='
                    .(!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':100,100italic,300,300italic,400,400italic,700,700italic')
                    .(empty($fonts[$font]['link']) || green_strpos($fonts[$font]['link'], 'subset=')===false ? '&subset=latin,latin-ext,cyrillic,cyrillic-ext' : '');
                green_enqueue_style( 'theme-font-'.str_replace(' ', '-', $font_name), $css, array(), null );
            }
        }
				

		// Fontello styles must be loaded before main stylesheet
		green_enqueue_style( 'green-fontello-style',  green_get_file_url('css/fontello/css/fontello.css'),  array(), null);
		//green_enqueue_style( 'green-fontello-animation-style', green_get_file_url('css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		green_enqueue_style( 'green-main-style', get_stylesheet_uri(), array(), null );
		
		if (green_get_theme_option('debug_mode')=='no' && green_get_theme_option('packed_scripts')=='yes' && file_exists(green_get_file_dir('css/__packed.css'))) {
			// Load packed styles
			green_enqueue_style( 'green-packed-style',  		green_get_file_url('css/__packed.css'), array(), null );
		} else {
			// Shortcodes
			green_enqueue_style( 'green-shortcodes-style',	green_get_file_url('shortcodes/shortcodes.css'), array(), null );
			// Animations
			if (green_get_theme_option('css_animation')=='yes')
				green_enqueue_style( 'green-animation-style',	green_get_file_url('css/core.animation.css'), array(), null );
		}
		// Theme skin stylesheet
		do_action('green_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		green_enqueue_custom_styles();

		// Responsive
		if (green_get_theme_option('responsive_layouts') == 'yes') {
			green_enqueue_style( 'green-responsive-style', green_get_file_url('css/responsive.css'), array(), null );
			do_action('green_action_add_responsive');
			if (green_get_custom_option('theme_skin')!='') {
				$css = apply_filters('green_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'green-responsive-style', $css );
			}
		}


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		if (green_get_theme_option('debug_mode')=='no' && green_get_theme_option('packed_scripts')=='yes' && file_exists(green_get_file_dir('js/__packed.js'))) {
			// Load packed theme scripts
			green_enqueue_script( 'green-packed-scripts', green_get_file_url('js/__packed.js'), array('jquery'), null, true);
		} else {
			// Load separate theme scripts
			green_enqueue_script( 'superfish', green_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
			if (green_get_theme_option('menu_slider')=='yes') {
				green_enqueue_script( 'green-slidemenu-script', green_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
				//green_enqueue_script( 'green-jquery-easing-script', green_get_file_url('js/jquery.easing.js'), array('jquery'), null, true );
			}
			
			// Load this script only if any shortcode run
			//green_enqueue_script( 'green-shortcodes-script', green_get_file_url('shortcodes/shortcodes.js'), array('jquery'), null, true );	

			if ( is_single() && green_get_custom_option('show_reviews')=='yes' ) {
				green_enqueue_script( 'green-core-reviews-script', green_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
			}

			green_enqueue_script( 'green-core-utils-script', green_get_file_url('js/core.utils.js'), array('jquery'), null, true );
			green_enqueue_script( 'green-core-init-script', green_get_file_url('js/core.init.js'), array('jquery'), null, true );	
		}

		// Media elements library	
		if (green_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			global $wp_scripts;
			$wp_scripts->done[]	= 'mediaelement';
			$wp_scripts->done[]	= 'wp-mediaelement';
			$wp_styles->done[]	= 'mediaelement';
			$wp_styles->done[]	= 'wp-mediaelement';
		}
		
		// Video background
		if (green_get_custom_option('show_video_bg') == 'yes' && green_get_custom_option('video_bg_youtube_code') != '') {
			green_enqueue_script( 'green-video-bg-script', green_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		if ( green_get_custom_option('show_googlemap')=='yes' ) { 
			$api_key = green_get_theme_option('api_google');
			green_enqueue_script( 'googlemap', green_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
			green_enqueue_script( 'green-googlemap-script', green_get_file_url('js/core.googlemap.js'), array(), null, true );
		}


		// Social share buttons
		if (is_singular() && !green_get_global('blog_streampage') && green_get_custom_option('show_share')!='hide') {
			green_enqueue_script( 'green-social-share-script', green_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Custom panel
		if (green_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(green_get_file_dir('core/core.customizer/front.customizer.css')))
				green_enqueue_style(  'green-customizer-style',  green_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(green_get_file_dir('core/core.customizer/front.customizer.js')))
				green_enqueue_script( 'green-customizer-script', green_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );	
		}
		
		//Debug utils
		if (green_get_theme_option('debug_mode')=='yes') {
			green_enqueue_script( 'green-core-debug-script', green_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('green_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'green_enqueue_slider' ) ) {
	function green_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('css/__packed.css'))) {
				green_enqueue_style( 'green-swiperslider-style', green_get_file_url('js/swiper/idangerous.swiper.css'), array(), null );
			}
			if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('js/__packed.js'))) {
				green_enqueue_script( 'green-swiperslider-script', 			green_get_file_url('js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
				green_enqueue_script( 'green-swiperslider-scrollbar-script',	green_get_file_url('js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
			}
		}
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'green_enqueue_messages' ) ) {
	function green_enqueue_messages() {
		if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('css/__packed.css'))) {
			green_enqueue_style( 'green-messages-style',		green_get_file_url('js/core.messages/core.messages.css'), array(), null );
		}
		if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('js/__packed.js'))) {
			green_enqueue_script( 'green-messages-script',	green_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
		}
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'green_enqueue_portfolio' ) ) {
	function green_enqueue_portfolio($hover='') {
		if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('css/__packed.css'))) {
			green_enqueue_style( 'green-portfolio-style',  green_get_file_url('css/core.portfolio.css'), array(), null );
			if (green_strpos($hover, 'effect_dir')!==false)
				green_enqueue_script( 'hoverdir', green_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
		}
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'green_enqueue_diagram' ) ) {
	function green_enqueue_diagram($type='all') {
		if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('js/__packed.js'))) {
			if ($type=='all' || $type=='pie') green_enqueue_script( 'green-diagram-chart-script',	green_get_file_url('js/diagram/chart.min.js'), array(), null, true );
			if ($type=='all' || $type=='arc') green_enqueue_script( 'green-diagram-raphael-script',	green_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
		}
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'green_enqueue_popup' ) ) {
	function green_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && green_get_theme_option('popup_engine')=='pretty')) {
			green_enqueue_style(  'green-prettyphoto-style',	green_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			green_enqueue_script( 'green-prettyphoto-script',	green_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && green_get_theme_option('popup_engine')=='magnific')) {
			green_enqueue_style(  'green-magnific-style',	green_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			green_enqueue_script( 'green-magnific-script',green_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && green_get_theme_option('popup_engine')=='internal')) {
			green_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'green_core_frontend_scripts_inline' ) ) {
	function green_core_frontend_scripts_inline() {
		do_action('green_action_add_scripts_inline');
	}
}

//  Add inline scripts in the footer
if (!function_exists('green_core_add_scripts_inline')) {
	function green_core_add_scripts_inline() {
		global $GREEN_GLOBALS;

		$msg = green_get_system_message(true); 
		if (!empty($msg['message'])) green_enqueue_messages();

		echo "<script type=\"text/javascript\">"
		. "jQuery(document).ready(function() {"

			// AJAX parameters
		. "GREEN_GLOBALS['ajax_url']			= '" . esc_url($GREEN_GLOBALS['ajax_url']) . "';"
		. "GREEN_GLOBALS['ajax_nonce']		= '" . esc_attr($GREEN_GLOBALS['ajax_nonce']) . "';"
		. "GREEN_GLOBALS['ajax_nonce_editor'] = '" . esc_attr(wp_create_nonce('green_editor_nonce')) . "';"

			// Site base url
		. "GREEN_GLOBALS['site_url']			= '" . get_site_url() . "';"

			// VC frontend edit mode
		. "GREEN_GLOBALS['vc_edit_mode']		= " . (green_vc_is_frontend() ? 'true' : 'false') . ";"

			// Theme base font
		. "GREEN_GLOBALS['theme_font']		= '" . (green_get_custom_option('typography_custom')=='yes' ? green_get_custom_option('typography_p_font') : '') . "';"

			// Theme skin
		. "GREEN_GLOBALS['theme_skin']		= '" . esc_attr(green_get_custom_option('theme_skin')) . "';"
		. "GREEN_GLOBALS['theme_skin_bg']	= '" . green_get_theme_bgcolor() . "';"

			// Slider height
		. "GREEN_GLOBALS['slider_height']	= " . max(100, green_get_custom_option('slider_height')) . ";"

			// System message
		. "GREEN_GLOBALS['system_message']	= {"
		. "message: '" . addslashes($msg['message']) . "',"
		. "status: '"  . addslashes($msg['status'])  . "',"
		. "header: '"  . addslashes($msg['header'])  . "'"
		. "};"

			// User logged in
		. "GREEN_GLOBALS['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"

			// Show table of content for the current page
		. "GREEN_GLOBALS['toc_menu']		= '" . esc_attr(green_get_custom_option('menu_toc')) . "';"
		. "GREEN_GLOBALS['toc_menu_home']	= " . (green_get_custom_option('menu_toc')!='hide' && green_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
		. "GREEN_GLOBALS['toc_menu_top']	= " . (green_get_custom_option('menu_toc')!='hide' && green_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"

			// Fix main menu
		. "GREEN_GLOBALS['menu_fixed']		= " . (green_get_theme_option('menu_position')=='fixed' ? 'true' : 'false') . ";"

			// Use responsive version for main menu
		. "GREEN_GLOBALS['menu_relayout']	= " . max(0, (int) green_get_theme_option('menu_relayout')) . ";"
		. "GREEN_GLOBALS['menu_responsive']	= " . (green_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) green_get_theme_option('menu_responsive')) : 0) . ";"
		. "GREEN_GLOBALS['menu_slider']     = " . (green_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"

			// Right panel demo timer
		. "GREEN_GLOBALS['demo_time']		= " . (green_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) green_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
		. "GREEN_GLOBALS['media_elements_enabled'] = " . (green_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"

			// Use AJAX search
		. "GREEN_GLOBALS['ajax_search_enabled'] 	= " . (green_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
		. "GREEN_GLOBALS['ajax_search_min_length']	= " . min(3, green_get_theme_option('ajax_search_min_length')) . ";"
		. "GREEN_GLOBALS['ajax_search_delay']		= " . min(200, max(1000, green_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
		. "GREEN_GLOBALS['css_animation']      = " . (green_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
		. "GREEN_GLOBALS['menu_animation_in']  = '" . esc_attr(green_get_theme_option('menu_animation_in')) . "';"
		. "GREEN_GLOBALS['menu_animation_out'] = '" . esc_attr(green_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
		. "GREEN_GLOBALS['popup_engine']	= '" . esc_attr(green_get_theme_option('popup_engine')) . "';"
		. "GREEN_GLOBALS['popup_gallery']	= " . (green_get_theme_option('popup_gallery')=='yes' ? 'true' : 'false') . ";"

			// E-mail mask
		. "GREEN_GLOBALS['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"

			// Messages max length
		. "GREEN_GLOBALS['contacts_maxlength']	= " . intval(green_get_theme_option('message_maxlength_contacts')) . ";"
		. "GREEN_GLOBALS['comments_maxlength']	= " . intval(green_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
		. "GREEN_GLOBALS['remember_visitors_settings']	= " . (green_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
		. "GREEN_GLOBALS['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
		. "GREEN_GLOBALS['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
		. "GREEN_GLOBALS['error_message_box']	= null;"
			// Waiting for the viewmore results
		. "GREEN_GLOBALS['viewmore_busy']		= false;"
		. "GREEN_GLOBALS['video_resize_inited']	= false;"
		. "GREEN_GLOBALS['top_panel_height']		= 0;"
		. "});"
		. "</script>";
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'green_enqueue_custom_styles' ) ) {
	function green_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';	//green_get_custom_option('custom_stylesheet_url');
		green_enqueue_style( 'green-custom-style', $custom_css ? $custom_css : green_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'green-custom-style', green_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'green_add_widget_number' ) ) {
	function green_add_widget_number($prm) {
		global $GREEN_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = $GREEN_GLOBALS['current_sidebar'];
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) green_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
// add_filter( 'wp_title', 'green_wp_title', 10, 2 );
if ( !function_exists( 'green_wp_title' ) ) {
	function green_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = green_get_custom_option('logo_slogan');
			if (empty($site_description)) 
				$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'green' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
// add_filter('wp_nav_menu_objects', 'green_add_mainmenu_classes', 10, 2);
if ( !function_exists( 'green_add_mainmenu_classes' ) ) {
	function green_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && green_get_theme_option('menu_colored')=='yes') {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = green_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !green_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'green_callback_frontend_editor_save' ) ) {
	function green_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'green_editor_nonce' ) )
			die();

		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( green_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
					));
				if ($rez == 0) 
					$response['error'] = esc_html__('Post update error!', 'green');
			} else {
				$response['error'] = esc_html__('Post update error!', 'green');
			}
		} else
		$response['error'] = esc_html__('Post update denied!', 'green');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'green_callback_frontend_editor_delete' ) ) {
	function green_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'green_editor_nonce' ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( green_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = esc_html__('Post delete error!', 'green');
			} else {
				$response['error'] = esc_html__('Post delete error!', 'green');
			}
		} else
		$response['error'] = esc_html__('Post delete denied!', 'green');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'green_prepare_logo_text' ) ) {
	function green_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>