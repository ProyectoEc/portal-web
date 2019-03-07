<?php
/**
 * ThemeREX Framework: global variables storage
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('green_get_global')) {
	function green_get_global($var_name) {
		global $GREEN_GLOBALS;
		return isset($GREEN_GLOBALS[$var_name]) ? $GREEN_GLOBALS[$var_name] : '';
	}
}

// Set global variable
if (!function_exists('green_set_global')) {
	function green_set_global($var_name, $value) {
		global $GREEN_GLOBALS;
		$GREEN_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('green_inc_global')) {
	function green_inc_global($var_name, $value=1) {
		global $GREEN_GLOBALS;
		$GREEN_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('green_concat_global')) {
	function green_concat_global($var_name, $value) {
		global $GREEN_GLOBALS;
		$GREEN_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('green_get_global_array')) {
	function green_get_global_array($var_name, $key) {
		global $GREEN_GLOBALS;
		return isset($GREEN_GLOBALS[$var_name][$key]) ? $GREEN_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('green_set_global_array')) {
	function green_set_global_array($var_name, $key, $value) {
		global $GREEN_GLOBALS;
		if (!isset($GREEN_GLOBALS[$var_name])) $GREEN_GLOBALS[$var_name] = array();
		$GREEN_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('green_inc_global_array')) {
	function green_inc_global_array($var_name, $key, $value=1) {
		global $GREEN_GLOBALS;
		$GREEN_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('green_concat_global_array')) {
	function green_concat_global_array($var_name, $key, $value) {
		global $GREEN_GLOBALS;
		$GREEN_GLOBALS[$var_name][$key] .= $value;
	}
}
?>