<?php
/**
 * ThemeREX Framework: shortcodes manipulations
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('green_sc_theme_setup')) {
	add_action( 'green_action_before_init_theme', 'green_sc_theme_setup' );
	function green_sc_theme_setup() {

		if ( !is_admin() ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'green_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('green_sc_prepare_content')) green_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('green_shortcode_output', 'green_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_contact_form',			'green_sc_contact_form_send');
		add_action('wp_ajax_nopriv_send_contact_form',	'green_sc_contact_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',						'green_sc_selector_add_in_toolbar', 11);

	}
}


// Add shortcodes init scripts and styles
if ( !function_exists( 'green_sc_add_scripts' ) ) {
	//add_filter('green_shortcode_output', 'green_sc_add_scripts', 10, 4);
	function green_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		global $GREEN_GLOBALS;
		
		if (empty($GREEN_GLOBALS['shortcodes_scripts_added'])) {
			$GREEN_GLOBALS['shortcodes_scripts_added'] = true;
			//if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('css/__packed.css')))
			//	green_enqueue_style( 'green-shortcodes-style', green_get_file_url('shortcodes/shortcodes.css'), array(), null );
			if (green_get_theme_option('debug_mode')=='yes' || green_get_theme_option('packed_scripts')=='no' || !file_exists(green_get_file_dir('css/__packed.js')))
				green_enqueue_script( 'green-shortcodes-script', green_get_file_url('shortcodes/shortcodes.js'), array('jquery'), null, true );	
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('green_sc_prepare_content')) {
	function green_sc_prepare_content() {
		if (function_exists('green_sc_clear_around')) {
			$filters = array(
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (green_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			foreach ($filters as $flt)
				add_filter(join('_', $flt), 'green_sc_clear_around', 1);
		}
		if (function_exists('green_sc_clear_around')) {
			$filters = array(
				array('green', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (green_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			foreach ($filters as $flt)
				add_filter(join('_', $flt), 'green_sc_clear_around');
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('green_sc_excerpt_shortcodes')) {
	function green_sc_excerpt_shortcodes($content) {
		$content = do_shortcode($content);
		//$content = strip_shortcodes($content);
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('green_sc_clear_around')) {
	function green_sc_clear_around($content) {
		$content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// ThemeREX shortcodes load scripts
if (!function_exists('green_sc_load_scripts')) {
	function green_sc_load_scripts() {
		green_enqueue_script( 'green-shortcodes-script', green_get_file_url('shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		green_enqueue_script( 'green-selection-script',  green_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
	}
}

// ThemeREX shortcodes prepare scripts
if (!function_exists('green_sc_prepare_scripts')) {
	function green_sc_prepare_scripts() {
		global $GREEN_GLOBALS;
		if (!isset($GREEN_GLOBALS['shortcodes_prepared'])) {
			$GREEN_GLOBALS['shortcodes_prepared'] = true;
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					GREEN_GLOBALS['shortcodes'] = JSON.parse('<?php echo str_replace("'", "\\'", json_encode(green_array_prepare_to_json($GREEN_GLOBALS['shortcodes']))); ?>');
					GREEN_GLOBALS['shortcodes_cp'] = '<?php echo is_admin() ? 'wp' : 'internal'; ?>';
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('green_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','green_sc_selector_add_in_toolbar', 11);
	function green_sc_selector_add_in_toolbar(){

		if ( !green_options_is_used() ) return;

		green_sc_load_scripts();
		green_sc_prepare_scripts();

		global $GREEN_GLOBALS;

		$shortcodes = $GREEN_GLOBALS['shortcodes'];
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'green').'&nbsp;</option>';

		foreach ($shortcodes as $idx => $sc) {
			$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_attr($sc['title']).'</option>';
		}

		$shortcodes_list .= '</select>';

		echo ($shortcodes_list);
	}
}

// Check shortcodes params
if (!function_exists('green_sc_param_is_on')) {
	function green_sc_param_is_on($prm) {
		return $prm>0 || in_array(green_strtolower($prm), array('true', 'on', 'yes', 'show'));
	}
}
if (!function_exists('green_sc_param_is_off')) {
	function green_sc_param_is_off($prm) {
		return empty($prm) || $prm===0 || in_array(green_strtolower($prm), array('false', 'off', 'no', 'none', 'hide'));
	}
}
if (!function_exists('green_sc_param_is_inherit')) {
	function green_sc_param_is_inherit($prm) {
		return in_array(green_strtolower($prm), array('inherit', 'default'));
	}
}

// Return classes list for the specified animation
if (!function_exists('green_sc_get_animation_classes')) {
	function green_sc_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return green_sc_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!green_sc_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}

// Decode html-entities in the shortcode parameters
if (!function_exists('green_sc_html_decode')) {
	function green_sc_html_decode($prm) {
		if (count($prm) > 0) {
			foreach ($prm as $k=>$v) {
				if (is_string($v))
					$prm[$k] = wp_specialchars_decode($v, ENT_QUOTES);
			}
		}
		return $prm;
	}
}


require_once( green_get_file_dir('shortcodes/shortcodes_settings.php') );

if ( class_exists('WPBakeryShortCode') 
		&& ( 
			is_admin() 
			|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
			) 
	) {
	require_once( green_get_file_dir('shortcodes/shortcodes_vc_classes.php') );
	require_once( green_get_file_dir('shortcodes/shortcodes_vc.php') );
}

require_once( green_get_file_dir('shortcodes/shortcodes.php') );
?>