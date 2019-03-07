<?php
/*
Plugin Name: Green Utilities
Plugin URI: http://ancorathemes.com
Description: Utils for files, directories, post type and taxonomies manipulations
Version: 2.3
Author: AncoraThemes
Author URI: http://ancorathemes.com
*/

// Plugin's storage
if ( ! defined( 'TRX_UTILS_PLUGIN_DIR' ) ) define( 'TRX_UTILS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'TRX_UTILS_PLUGIN_URL' ) ) define( 'TRX_UTILS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'TRX_UTILS_PLUGIN_BASE' ) ) define( 'TRX_UTILS_PLUGIN_BASE', dirname( plugin_basename( __FILE__ ) ) );

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Current version
if ( ! defined( 'TRX_UTILS_VERSION' ) ) {
	define( 'TRX_UTILS_VERSION', '2.3' );
}


/* Types and taxonomies 
------------------------------------------------------ */

// Register theme required types and taxes
if (!function_exists('trx_utils_theme_support')) {	
	function trx_utils_theme_support($type, $name, $args=false) {
		if ($type == 'taxonomy') {
			if ($args===false)
				trx_utils_custom_taxonomy($name);
			else
				register_taxonomy($name, $args['post_type'], $args);
		} else {
			if ($args===false)
				trx_utils_custom_post_type($name);
			else
				register_post_type($name, $args);
		}
	}
}
if (!function_exists('trx_utils_theme_support_pt')) {	
	function trx_utils_theme_support_pt($name, $args=false) {
		if ($args===false)
			trx_utils_custom_post_type($name);
		else
			register_post_type($name, $args);
	}
}
if (!function_exists('trx_utils_theme_support_tx')) {	
	function trx_utils_theme_support_tx($name, $args=false) {
		if ($args===false)
			trx_utils_custom_taxonomy($name);
		else
			register_taxonomy($name, $args['post_type'], $args);
	}
}

// Register custom taxonomies
if (!function_exists('trx_utils_custom_taxonomy')) {	
	function trx_utils_custom_taxonomy($name) {

		if ($name=='clients_group') {

			register_taxonomy( $name, 'clients', array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html__( 'Clients Group', 'trx_utils' ),
					'singular_name'     => esc_html__( 'Group', 'trx_utils' ),
					'search_items'      => esc_html__( 'Search Groups', 'trx_utils' ),
					'all_items'         => esc_html__( 'All Groups', 'trx_utils' ),
					'parent_item'       => esc_html__( 'Parent Group', 'trx_utils' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'trx_utils' ),
					'edit_item'         => esc_html__( 'Edit Group', 'trx_utils' ),
					'update_item'       => esc_html__( 'Update Group', 'trx_utils' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'trx_utils' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'trx_utils' ),
					'menu_name'         => esc_html__( 'Clients Group', 'trx_utils' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'clients_group' ),
				)
			);

		} else if ($name=='services_group') {

			register_taxonomy( $name, 'services', array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html__( 'Services Group', 'trx_utils' ),
					'singular_name'     => esc_html__( 'Group', 'trx_utils' ),
					'search_items'      => esc_html__( 'Search Groups', 'trx_utils' ),
					'all_items'         => esc_html__( 'All Groups', 'trx_utils' ),
					'parent_item'       => esc_html__( 'Parent Group', 'trx_utils' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'trx_utils' ),
					'edit_item'         => esc_html__( 'Edit Group', 'trx_utils' ),
					'update_item'       => esc_html__( 'Update Group', 'trx_utils' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'trx_utils' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'trx_utils' ),
					'menu_name'         => esc_html__( 'Services Group', 'trx_utils' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'services_group' ),
				)
			);

		} else if ($name=='team_group') {

			register_taxonomy( $name, 'team', array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html__( 'Team Group', 'trx_utils' ),
					'singular_name'     => esc_html__( 'Group', 'trx_utils' ),
					'search_items'      => esc_html__( 'Search Groups', 'trx_utils' ),
					'all_items'         => esc_html__( 'All Groups', 'trx_utils' ),
					'parent_item'       => esc_html__( 'Parent Group', 'trx_utils' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'trx_utils' ),
					'edit_item'         => esc_html__( 'Edit Group', 'trx_utils' ),
					'update_item'       => esc_html__( 'Update Group', 'trx_utils' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'trx_utils' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'trx_utils' ),
					'menu_name'         => esc_html__( 'Team Group', 'trx_utils' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'team_group' ),
				)
			);

		} else if ($name=='testimonial_group') {

			register_taxonomy( $name, 'testimonial', array(
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html__( 'Testimonials Group', 'trx_utils' ),
					'singular_name'     => esc_html__( 'Group', 'trx_utils' ),
					'search_items'      => esc_html__( 'Search Groups', 'trx_utils' ),
					'all_items'         => esc_html__( 'All Groups', 'trx_utils' ),
					'parent_item'       => esc_html__( 'Parent Group', 'trx_utils' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'trx_utils' ),
					'edit_item'         => esc_html__( 'Edit Group', 'trx_utils' ),
					'update_item'       => esc_html__( 'Update Group', 'trx_utils' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'trx_utils' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'trx_utils' ),
					'menu_name'         => esc_html__( 'Testimonial Group', 'trx_utils' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'testimonial_group' ),
				)
			);

		} else if ($name=='media_folder') {

			register_taxonomy( $name, 'attachment', array(
				'hierarchical' 		=> true,
				'labels' 			=> array(
					'name'              => esc_html__('Media Folders', 'trx_utils'),
					'singular_name'     => esc_html__('Media Folder', 'trx_utils'),
					'search_items'      => esc_html__('Search Media Folders', 'trx_utils'),
					'all_items'         => esc_html__('All Media Folders', 'trx_utils'),
					'parent_item'       => esc_html__('Parent Media Folder', 'trx_utils'),
					'parent_item_colon' => esc_html__('Parent Media Folder:', 'trx_utils'),
					'edit_item'         => esc_html__('Edit Media Folder', 'trx_utils'),
					'update_item'       => esc_html__('Update Media Folder', 'trx_utils'),
					'add_new_item'      => esc_html__('Add New Media Folder', 'trx_utils'),
					'new_item_name'     => esc_html__('New Media Folder Name', 'trx_utils'),
					'menu_name'         => esc_html__('Media Folders', 'trx_utils'),
				),
				'query_var'			=> true,
				'rewrite' 			=> true,
				'show_admin_column'	=> true
				)
			);

		}
	}
}

// Register custom post_types
if (!function_exists('trx_utils_custom_post_type')) {	
	function trx_utils_custom_post_type($name) {

		if ($name=='clients') {

			register_post_type( $name, array(
				'label'               => esc_html__( 'Clients', 'trx_utils' ),
				'description'         => esc_html__( 'Clients Description', 'trx_utils' ),
				'labels'              => array(
					'name'                => esc_html__( 'Clients', 'trx_utils' ),
					'singular_name'       => esc_html__( 'Client', 'trx_utils' ),
					'menu_name'           => esc_html__( 'Clients', 'trx_utils' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'trx_utils' ),
					'all_items'           => esc_html__( 'All Clients', 'trx_utils' ),
					'view_item'           => esc_html__( 'View Item', 'trx_utils' ),
					'add_new_item'        => esc_html__( 'Add New Client', 'trx_utils' ),
					'add_new'             => esc_html__( 'Add New', 'trx_utils' ),
					'edit_item'           => esc_html__( 'Edit Item', 'trx_utils' ),
					'update_item'         => esc_html__( 'Update Item', 'trx_utils' ),
					'search_items'        => esc_html__( 'Search Item', 'trx_utils' ),
					'not_found'           => esc_html__( 'Not found', 'trx_utils' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'trx_utils' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-admin-users',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => '52.1',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
				)
			);

		} else if ($name=='services') {

			register_post_type( $name, array(
				'label'               => esc_html__( 'Service item', 'trx_utils' ),
				'description'         => esc_html__( 'Service Description', 'trx_utils' ),
				'labels'              => array(
					'name'                => esc_html__( 'Services', 'trx_utils' ),
					'singular_name'       => esc_html__( 'Service item', 'trx_utils' ),
					'menu_name'           => esc_html__( 'Services', 'trx_utils' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'trx_utils' ),
					'all_items'           => esc_html__( 'All Services', 'trx_utils' ),
					'view_item'           => esc_html__( 'View Item', 'trx_utils' ),
					'add_new_item'        => esc_html__( 'Add New Service', 'trx_utils' ),
					'add_new'             => esc_html__( 'Add New', 'trx_utils' ),
					'edit_item'           => esc_html__( 'Edit Item', 'trx_utils' ),
					'update_item'         => esc_html__( 'Update Item', 'trx_utils' ),
					'search_items'        => esc_html__( 'Search Item', 'trx_utils' ),
					'not_found'           => esc_html__( 'Not found', 'trx_utils' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'trx_utils' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-info',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => '52.2',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
				)
			);

		} else if ($name=='team') {

			register_post_type( $name, array(
				'label'               => esc_html__( 'Team member', 'trx_utils' ),
				'description'         => esc_html__( 'Team Description', 'trx_utils' ),
				'labels'              => array(
					'name'                => esc_html__( 'Team', 'trx_utils' ),
					'singular_name'       => esc_html__( 'Team member', 'trx_utils' ),
					'menu_name'           => esc_html__( 'Team', 'trx_utils' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'trx_utils' ),
					'all_items'           => esc_html__( 'All Team', 'trx_utils' ),
					'view_item'           => esc_html__( 'View Item', 'trx_utils' ),
					'add_new_item'        => esc_html__( 'Add New Team member', 'trx_utils' ),
					'add_new'             => esc_html__( 'Add New', 'trx_utils' ),
					'edit_item'           => esc_html__( 'Edit Item', 'trx_utils' ),
					'update_item'         => esc_html__( 'Update Item', 'trx_utils' ),
					'search_items'        => esc_html__( 'Search Item', 'trx_utils' ),
					'not_found'           => esc_html__( 'Not found', 'trx_utils' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'trx_utils' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-admin-users',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => '52.3',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
				)
			);

		} else if ($name=='testimonial') {

			register_post_type( $name, array(
				'label'               => esc_html__( 'Testimonial', 'trx_utils' ),
				'description'         => esc_html__( 'Testimonial Description', 'trx_utils' ),
				'labels'              => array(
					'name'                => esc_html__( 'Testimonials', 'trx_utils' ),
					'singular_name'       => esc_html__( 'Testimonial', 'trx_utils' ),
					'menu_name'           => esc_html__( 'Testimonials', 'trx_utils' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'trx_utils' ),
					'all_items'           => esc_html__( 'All Testimonials', 'trx_utils' ),
					'view_item'           => esc_html__( 'View Item', 'trx_utils' ),
					'add_new_item'        => esc_html__( 'Add New Testimonial', 'trx_utils' ),
					'add_new'             => esc_html__( 'Add New', 'trx_utils' ),
					'edit_item'           => esc_html__( 'Edit Item', 'trx_utils' ),
					'update_item'         => esc_html__( 'Update Item', 'trx_utils' ),
					'search_items'        => esc_html__( 'Search Item', 'trx_utils' ),
					'not_found'           => esc_html__( 'Not found', 'trx_utils' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'trx_utils' ),
				),
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-cloud',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => '52.4',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				)
			);

		}
	}
}


/* Shortcodes
------------------------------------------------------ */

// Register theme required shortcodes
if (!function_exists('trx_utils_require_shortcode')) {	
	function trx_utils_require_shortcode($name, $callback) {
		add_shortcode($name, $callback);
	}
}


/* Twitter API
------------------------------------------------------ */
if (!function_exists('trx_utils_twitter_acquire_data')) {
	function trx_utils_twitter_acquire_data($cfg) {
		if (empty($cfg['mode'])) $cfg['mode'] = 'user_timeline';
		$data = get_transient("twitter_data_".($cfg['mode']));
		if (!$data) {
			require_once( plugin_dir_path( __FILE__ ) . 'lib/tmhOAuth/tmhOAuth.php' );
			$tmhOAuth = new tmhOAuth(array(
				'consumer_key'    => $cfg['consumer_key'],
				'consumer_secret' => $cfg['consumer_secret'],
				'token'           => $cfg['token'],
				'secret'          => $cfg['secret']
			));
			$code = $tmhOAuth->user_request(array(
				'url' => $tmhOAuth->url(trx_utils_twitter_mode_url($cfg['mode']))
			));
			if ($code == 200) {
				$data = json_decode($tmhOAuth->response['response'], true);
				if (isset($data['status'])) {
					$code = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url(trx_utils_twitter_mode_url($cfg['oembed'])),
						'params' => array(
							'id' => $data['status']['id_str']
						)
					));
					if ($code == 200)
						$data = json_decode($tmhOAuth->response['response'], true);
				}
				set_transient("twitter_data_".($cfg['mode']), $data, 60*60);
			}
		} else if (!is_array($data) && substr($data, 0, 2)=='a:') {
			$data = unserialize($data);
		}
		return $data;
	}
}

if (!function_exists('trx_utils_twitter_mode_url')) {
	function trx_utils_twitter_mode_url($mode) {
		$url = '/1.1/statuses/';
		if ($mode == 'user_timeline')
			$url .= $mode;
		else if ($mode == 'home_timeline')
			$url .= $mode;
		return $url;
	}
}



/* LESS compilers
------------------------------------------------------ */

// Compile less-files
if (!function_exists('trx_utils_less_compiler')) {	
	function trx_utils_less_compiler($list, $opt) {

		$success = true;

		// Load and create LESS Parser
		if ($opt['compiler'] == 'lessc') {
			// 1: Compiler Lessc
			require_once( plugin_dir_path( __FILE__ ) . 'lib/lessc/lessc.inc.php' );
		} else {
			// 2: Compiler Less
			require_once( plugin_dir_path( __FILE__ ) . 'lib/less/Less.php' );
		}

		foreach($list as $file) {
			if (empty($file) || !file_exists($file)) continue;
			$file_css = substr_replace($file , 'css', strrpos($file , '.') + 1);
				
			// Check if time of .css file after .less - skip current .less
			if (!empty($opt['check_time']) && file_exists($file_css)) {
				$css_time = filemtime($file_css);
				if ($css_time >= filemtime($file) && ($opt['utils_time']==0 || $css_time > $opt['utils_time'])) continue;
			}
				
			// Compile current .less file
			try {
				// Create Parser
				if ($opt['compiler'] == 'lessc') {
					$parser = new lessc;
					//$parser->registerFunction("replace", "trx_utils_less_func_replace");
					if ($opt['compressed']) $parser->setFormatter("compressed");
				} else {
					if ($opt['compressed'])
						$args = array('compress' => true);
					else {
						$args = array('compress' => false);
						if ($opt['map'] != 'no') {
							$args['sourceMap'] = true;
							if ($opt['map'] == 'external') {
								$args['sourceMapWriteTo'] = $file.'.map';
								$args['sourceMapURL'] = str_replace(
									array(get_template_directory(), get_stylesheet_directory()),
									array(get_template_directory_uri(), get_stylesheet_directory_uri()),
									$file) . '.map';
							}
						}
					}
					$parser = new Less_Parser($args);
				}

				// Parse main file
				$css = '';
				if ($opt['map'] != 'no') {
				// Parse main file
					$parser->parseFile( $file, '');
					// Parse less utils
					if (is_array($opt['utils']) && count($opt['utils']) > 0) {
						foreach($opt['utils'] as $utility) {
							$parser->parseFile( $utility, '');
						}
					}
					// Parse less vars (from Theme Options)
					if (!empty($opt['vars'])) {
						$parser->parse($opt['vars']);
					}
					// Get compiled CSS code
					$css = $parser->getCss();
					// Reset LESS engine
					$parser->Reset();
				} else if (($text = file_get_contents($file))!='') {
					$parts = $opt['separator'] != '' ? explode($opt['separator'], $text) : array($text);
					for ($i=0; $i<count($parts); $i++) {
						$text = $parts[$i]
							. (!empty($opt['utils']) ? $opt['utils'] : '')			// Add less utils
							. (!empty($opt['vars']) ? $opt['vars'] : '');			// Add less vars (from Theme Options)
						// Get compiled CSS code
						if ($opt['compiler']=='lessc')
							$css .= $parser->compile($text);
						else {
							$parser->parse($text);
							$css .= $parser->getCss();
							$parser->Reset();
						}
					}
				}
				if ($css) {
					if ($opt['map']=='no') {
						// If it main theme style - append CSS after header comments
						if ($file == get_template_directory(). '/style.less') {
							// Append to the main Theme Style CSS
							$theme_css = file_get_contents( get_template_directory() . '/style.css' );
							$css = substr($theme_css, 0, strpos($theme_css, '*/')+2) . "\n\n" . $css;
						} else {
							$css =	"/*"
									. "\n"
									. __('Attention! Do not modify this .css-file!', 'trx_utils') 
									. "\n"
									. __('Please, make all necessary changes in the corresponding .less-file!', 'trx_utils')
									. "\n"
									. "*/"
									. "\n"
									. '@charset "utf-8";'
									. "\n\n"
									. $css;
						}
					}
					// Save compiled CSS
					file_put_contents( $file_css, $css);
				}
			} catch (Exception $e) {
				if (function_exists('dfl')) dfl($e->getMessage());
				$success = false;
			}
		}
		return $success;
	}
}

// LESS function
/*
if (!function_exists('trx_utils_less_func_replace')) {	
	function trx_utils_less_func_replace($arg) {
    	return $arg;
	}
}
*/

// Prepare required styles and scripts for admin mode
if ( ! function_exists( 'trx_utils_admin_prepare_scripts' ) ) {
    add_action( 'admin_head', 'trx_utils_admin_prepare_scripts' );
    function trx_utils_admin_prepare_scripts() {
        ?>
        <script>
            if ( typeof TRX_UTILS_GLOBALS == 'undefined' ) var TRX_UTILS_GLOBALS = {};
            jQuery(document).ready(function() {
                TRX_UTILS_GLOBALS['admin_mode'] = true;
                TRX_UTILS_GLOBALS['ajax_nonce'] = "<?php echo wp_create_nonce('ajax_nonce'); ?>";
                TRX_UTILS_GLOBALS['ajax_url'] = "<?php echo admin_url('admin-ajax.php'); ?>";
                TRX_UTILS_GLOBALS['user_logged_in'] = true;
            });
        </script>
        <?php
    }
}

// File functions
if ( file_exists( TRX_UTILS_PLUGIN_DIR . 'includes/plugin.files.php' ) ) {
    require_once TRX_UTILS_PLUGIN_DIR . 'includes/plugin.files.php';
}

// Third-party plugins support
if ( file_exists( TRX_UTILS_PLUGIN_DIR . 'api/api.php' ) ) {
    require_once TRX_UTILS_PLUGIN_DIR . 'api/api.php';
}


// Demo data import/export
if ( file_exists( TRX_UTILS_PLUGIN_DIR . 'importer/importer.php' ) ) {
    require_once TRX_UTILS_PLUGIN_DIR . 'importer/importer.php';
}

// Add class trx_utils_activated
if(!function_exists('trx_utils_add_body_class')) {
    add_filter( 'body_class', 'trx_utils_add_body_class' );
    function trx_utils_add_body_class($classes){
        $classes[] = 'trx_utils_activated';
        return $classes;
    }
}
?>