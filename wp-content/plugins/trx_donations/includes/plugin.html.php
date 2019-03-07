<?php
/**
 * ThemeREX Framework: html manipulations
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



/* CSS values
-------------------------------------------------------------------------------- */

// Return string with position rules for the style attr
if (!function_exists('themerex_plugins_get_css_position_from_values')) {
	function themerex_plugins_get_css_position_from_values($top='',$right='',$bottom='',$left='',$width='',$height='') {
		if (!is_array($top)) {
			$top = compact('top','right','bottom','left','width','height');
		}
		$output = '';
		if (is_array($top) && count($top) > 0) {
			foreach ($top as $k=>$v) {
				$imp = substr($v, 0, 1);
				if ($imp == '!') $v = substr($v, 1);
				if ($v != '') $output .= ($k=='width' ? 'width' : ($k=='height' ? 'height' : 'margin-'.esc_attr($k))) . ':' . esc_attr(themerex_plugins_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
			}
		}
		return $output;
	}
}

// Return value for the style attr
if (!function_exists('themerex_plugins_prepare_css_value')) {
	function themerex_plugins_prepare_css_value($val) {
		if ($val != '') {
			$ed = substr($val, -1);
			if ('0'<=$ed && $ed<='9') $val .= 'px';
		}
		return $val;
	}
}




/* Other utils
-------------------------------------------------------------------------------- */

// Add parameters to URL
if (!function_exists('themerex_plugins_add_to_url')) {
	function themerex_plugins_add_to_url($url, $prm) {
		if (is_array($prm) && count($prm) > 0) {
			$separator = strpos($url, '?')===false ? '?' : '&';
			foreach ($prm as $k=>$v) {
				$url .= $separator . urlencode($k) . '=' . urlencode($v);
				$separator = '&';
			}
		}
		return $url;
	}
}

// Set e-mail content type
if (!function_exists('themerex_plugins_set_html_content_type')) {
	function themerex_plugins_set_html_content_type() {
		return 'text/html';
	}
	add_filter( 'wp_mail_content_type', 'themerex_plugins_set_html_content_type' );
}

// Decode html-entities in the shortcode parameters
if (!function_exists('themerex_plugins_html_decode')) {
	function themerex_plugins_html_decode($prm) {
		if (is_array($prm) && count($prm) > 0) {
			foreach ($prm as $k=>$v) {
				if (is_string($v))
					$prm[$k] = htmlspecialchars_decode($v, ENT_QUOTES);
			}
		}
		return $prm;
	}
}

// Return GET or POST value
if (!function_exists('themerex_plugins_get_value_gp')) {
	function themerex_plugins_get_value_gp($name, $defa='') {
		global $_GET, $_POST;
		$rez = $defa;
		$magic = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1;
		if (isset($_GET[$name])) {
			$rez = $magic ? stripslashes(trim($_GET[$name])) : trim($_GET[$name]);
		} else if (isset($_POST[$name])) {
			$rez = $magic ? stripslashes(trim($_POST[$name])) : trim($_POST[$name]);
		}
		return $rez;
	}
}
?>