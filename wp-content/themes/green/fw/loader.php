<?php
/**
 * ThemeREX Framework
 *
 * @package green
 * @since green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'GREEN_FW_DIR' ) )		define( 'GREEN_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'GREEN_START_TIME' ) )	define( 'GREEN_START_TIME', microtime());			// Framework start time
if ( ! defined( 'GREEN_START_MEMORY' ) )	define( 'GREEN_START_MEMORY', memory_get_usage());	// Memory usage before core loading

// Global variables storage
global $GREEN_GLOBALS;
$GREEN_GLOBALS = array();

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'green_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'green_loader_theme_setup', 20 );
	function green_loader_theme_setup() {
		// Before init theme
		do_action('green_action_before_init_theme');

		// Load current values for main theme options
		green_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			green_core_init_theme();
		}
	}
}

// Global variables storage
global $GREEN_GLOBALS;

$GREEN_GLOBALS = array(
    'theme_slug' => 'green', // Theme slug (used as prefix for theme's functions, text domain, global variables, etc.)
    'page_template' => '',   // Storage for current page template name (used in the inheritance system)
    'allowed_tags' => array(  // Allowed tags list (with attributes) in translations
        'b' => array(),
        'br' => array(),
        'strong' => array(),
        'i' => array(),
        'em' => array(),
        'u' => array(),
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'id' => array(),
            'class' => array()
        ),
        'span' => array(
            'id' => array(),
            'class' => array()
        )
    )
);

/* Include core parts
------------------------------------------------------------------------ */
// core.strings must be first - we use green_str...() in the green_get_file_dir()
// core.files must be first - we use green_get_file_dir() to include all rest parts
require_once( (file_exists(get_stylesheet_directory().(GREEN_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(GREEN_FW_DIR).'core/core.strings.php' );
require_once( (file_exists(get_stylesheet_directory().(GREEN_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(GREEN_FW_DIR).'core/core.files.php' );

green_autoload_folder( 'core' );

// Include custom theme files
green_autoload_folder( 'includes' );

// Include theme templates
green_autoload_folder( 'templates' );

// Include theme widgets
green_autoload_folder( 'widgets' );





?>