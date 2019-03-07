<?php
/**
 * ThemeREX Framework: debug utilities (for internal use only!)
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $THEMEREX_PLUGINS_GLOBALS;
if (!isset($THEMEREX_PLUGINS_GLOBALS)) $THEMEREX_PLUGINS_GLOBALS = array();
$THEMEREX_PLUGINS_GLOBALS['debug_file_name'] = 'debug.log';
$THEMEREX_PLUGINS_GLOBALS['max_dump_level'] = -1;

// Short analogs for debug functions
if (!function_exists('pdcl')) {	function pdcl($msg)	{ 					if (is_user_logged_in()) echo '<br>"' . esc_html($msg) . '"<br>'; } }				// Console log - output any message on the screen
if (!function_exists('pdco')) {	function pdco(&$var, $lvl=5)	{ 		if (is_user_logged_in()) themerex_plugins_debug_dump_screen($var, $lvl); } }		// Console obj - output object structure on the screen
if (!function_exists('pdcs')) {	function pdcs($lvl=-1){ 				if (is_user_logged_in()) themerex_plugins_debug_calls_stack_screen($lvl); } }		// Console stack - output calls stack on the screen
if (!function_exists('pdcw')) {	function pdcw()		{					if (is_user_logged_in()) themerex_plugins_debug_dump_wp(); } }						// Console WP - output WP is_... states on the screen
if (!function_exists('pddo')) {	function pddo(&$var, $lvl=5)	{ 		if (is_user_logged_in()) return themerex_plugins_debug_dump_var($var, 0, $lvl); } }	// Return obj - return object structure
if (!function_exists('pdfl')) {	function pdfl($var)	{					if (is_user_logged_in()) themerex_plugins_debug_trace_message($var); } }			// File log - output any message into file debug.log
if (!function_exists('pdfo')) {	function pdfo(&$var, $lvl=5)	{ 		if (is_user_logged_in()) themerex_plugins_debug_dump_file($var, $lvl); } }			// File obj - output object structure into file debug.log
if (!function_exists('pdfs')) {	function pdfs($lvl=-1){ 				if (is_user_logged_in()) themerex_plugins_debug_calls_stack_file($lvl); } }			// File stack - output calls stack into file debug.log

if (!function_exists('themerex_plugins_debug_set_max_dump_level')) {
	function themerex_plugins_debug_set_max_dump_level($lvl) {
		global $THEMEREX_PLUGINS_GLOBALS;
		$THEMEREX_PLUGINS_GLOBALS['max_dump_level'] = $lvl;
	}
}

if (!function_exists('themerex_plugins_debug_die_message')) {
	function themerex_plugins_debug_die_message($msg) {
		themerex_plugins_debug_trace_message($msg);
		die($msg);
	}
}

if (!function_exists('themerex_plugins_debug_trace_message')) {
	function themerex_plugins_debug_trace_message($msg) {
		global $THEMEREX_PLUGINS_GLOBALS;
		file_put_contents(substr(plugin_dir_path(__FILE__), 0, -9).($THEMEREX_PLUGINS_GLOBALS['debug_file_name']), date('d.m.Y H:i:s')." $msg\n", FILE_APPEND);
	}
}

if (!function_exists('themerex_plugins_debug_calls_stack_screen')) {
	function themerex_plugins_debug_calls_stack_screen($level=-1) {
		$s = debug_backtrace();
		array_shift($s);
		themerex_plugins_debug_dump_screen($s, $level);
	}
}

if (!function_exists('themerex_plugins_debug_calls_stack_file')) {
	function themerex_plugins_debug_calls_stack_file($level=-1) {
		$s = debug_backtrace();
		array_shift($s);
		themerex_plugins_debug_dump_file($s, $level);
	}
}

if (!function_exists('themerex_plugins_debug_dump_screen')) {
	function themerex_plugins_debug_dump_screen(&$var, $level=-1) {
		if ((is_array($var) || is_object($var)) && count($var))
			echo "<pre>\n".nl2br(esc_html(themerex_plugins_debug_dump_var($var, 0, $level)))."</pre>\n";
		else
			echo "<tt>".nl2br(esc_html(themerex_plugins_debug_dump_var($var, 0, $level)))."</tt>\n";
	}
}

if (!function_exists('themerex_plugins_debug_dump_file')) {
	function themerex_plugins_debug_dump_file(&$var, $level=-1) {
		themerex_plugins_debug_trace_message("\n\n".themerex_plugins_debug_dump_var($var, 0, $level));
	}
}

if (!function_exists('themerex_plugins_debug_dump_var')) {
	function themerex_plugins_debug_dump_var(&$var, $level=0, $max_level=-1)  {
		global $THEMEREX_PLUGINS_GLOBALS;
		if ($max_level < 0) $max_level = $THEMEREX_PLUGINS_GLOBALS['max_dump_level'];
		if (is_array($var)) $type="Array[".count($var)."]";
		else if (is_object($var)) $type="Object";
		else $type="";
		if ($type) {
			$rez = "$type\n";
			if ($max_level<0 || $level < $max_level) {
				for (Reset($var), $level++; list($k, $v)=each($var); ) {
					if (is_array($v) && $k==="GLOBALS") continue;
					for ($i=0; $i<$level*3; $i++) $rez .= " ";
					$rez .= $k.' => '. themerex_plugins_debug_dump_var($v, $level, $max_level);
				}
			}
		} else if (is_bool($var))
			$rez = ($var ? 'true' : 'false')."\n";
		else if (is_long($var) || is_float($var) || intval($var) != 0)
			$rez = $var."\n";
		else
			$rez = '"'.($var).'"'."\n";
		return $rez;
	}
}

if (!function_exists('themerex_plugins_debug_dump_wp')) {
	function themerex_plugins_debug_dump_wp($query=null) {
		global $wp_query;
		if (!$query) $query = $wp_query;
		echo "<tt>"
			."<br>admin=".is_admin()
			."<br>mobile=".wp_is_mobile()
			."<br>custom preview=".is_custom_preview()
			."<br>main_query=".is_main_query()."  query=".esc_html($query->is_main_query())
			."<br>home=".is_home()."  query=".esc_html($query->is_home())
			."<br>fp=".is_front_page()."  query=".esc_html($query->is_front_page())
			."<br>query->is_posts_page=".esc_html($query->is_posts_page)
			."<br>search=".is_search()."  query=".esc_html($query->is_search())
			."<br>category=".is_category()."  query=".esc_html($query->is_category())
			."<br>tag=".is_tag()."  query=".esc_html($query->is_tag())
			."<br>archive=".is_archive()."  query=".esc_html($query->is_archive())
			."<br>day=".is_day()."  query=".esc_html($query->is_day())
			."<br>month=".is_month()."  query=".esc_html($query->is_month())
			."<br>year=".is_year()."  query=".esc_html($query->is_year())
			."<br>author=".is_author()."  query=".esc_html($query->is_author())
			."<br>page=".is_page()."  query=".esc_html($query->is_page())
			."<br>single=".is_single()."  query=".esc_html($query->is_single())
			."<br>singular=".is_singular()."  query=".esc_html($query->is_singular())
			."<br>attachment=".is_attachment()."  query=".esc_html($query->is_attachment())
			."<br><br />"
			."</tt>";
	}
}
?>