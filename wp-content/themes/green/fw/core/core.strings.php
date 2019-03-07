<?php
/**
 * ThemeREX Framework: strings manipulations
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'GREEN_MULTIBYTE' ) ) define( 'GREEN_MULTIBYTE', function_exists('mb_strlen') ? 'UTF-8' : false );

if (!function_exists('green_strlen')) {
	function green_strlen($text) {
		return GREEN_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('green_strpos')) {
	function green_strpos($text, $char, $from=0) {
		return GREEN_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('green_strrpos')) {
	function green_strrpos($text, $char, $from=0) {
		return GREEN_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('green_substr')) {
	function green_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = green_strlen($text)-$from;
		}
		return GREEN_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('green_strtolower')) {
	function green_strtolower($text) {
		return GREEN_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('green_strtoupper')) {
	function green_strtoupper($text) {
		return GREEN_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('green_strtoproper')) {
	function green_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<green_strlen($text); $i++) {
			$ch = green_substr($text, $i, 1);
			$rez .= green_strpos(' .,:;?!()[]{}+=', $last)!==false ? green_strtoupper($ch) : green_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('green_strrepeat')) {
	function green_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('green_strshort')) {
	function green_strshort($str, $maxlength, $add='...') {
	//	if ($add && green_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return '';
		if ($maxlength < 1 || $maxlength >= green_strlen($str)) 
			return strip_tags($str);
		$str = green_substr(strip_tags($str), 0, $maxlength - green_strlen($add));
		$ch = green_substr($str, $maxlength - green_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = green_strlen($str) - 1; $i > 0; $i--)
				if (green_substr($str, $i, 1) == ' ') break;
			$str = trim(green_substr($str, 0, $i));
		}
		if (!empty($str) && green_strpos(',.:;-', green_substr($str, -1))!==false) $str = green_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('green_strclear')) {
	function green_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (green_substr($text, 0, green_strlen($open))==$open) {
					$pos = green_strpos($text, '>');
					if ($pos!==false) $text = green_substr($text, $pos+1);
				}
				if (green_substr($text, -green_strlen($close))==$close) $text = green_substr($text, 0, green_strlen($text) - green_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('green_get_slug')) {
	function green_get_slug($title) {
		return green_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}
?>