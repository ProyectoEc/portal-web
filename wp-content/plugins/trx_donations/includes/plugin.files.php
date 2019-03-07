<?php
/**
 * ThemeREX Framework: File system manipulations
 *
 * @package ThemeREX Donations
 * @since ThemeREX Donations 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Enqueue scripts and styles from child or main theme directory and use .min version
------------------------------------------------------------------------------------- */

// Enqueue .min.css (if exists and filetime .min.css > filetime .css) instead .css
if (!function_exists('themerex_plugins_enqueue_style')) {	
	function themerex_plugins_enqueue_style($handle, $src=false, $depts=array(), $ver=null, $media='all') {
		$load = true;
		if (!is_array($src) && $src !== false && $src !== '') {
			$plugin_dir  = substr(plugin_dir_path(__FILE__), 0, -9);
			$plugin_url  = substr(plugin_dir_url(__FILE__), 0, -9);
			$plugin_base = explode('/', plugin_basename(__FILE__));
			$theme_dir = get_template_directory().$plugin_base[0].'/';
			$theme_url = get_template_directory_uri().$plugin_base[0].'/';
			$child_dir = get_stylesheet_directory().$plugin_base[0].'/';
			$child_url = get_stylesheet_directory_uri().$plugin_base[0].'/';
			$dir = $url = '';
			if (strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			} else if (strpos($src, $plugin_url)===0) {
				$dir = $plugin_dir;
				$url = $plugin_url;
			}
			if ($dir != '') {
				if (substr($src, -4)=='.css') {
					if (substr($src, -8)!='.min.css') {
						$src_min = substr($src, 0, strlen($src)-4).'.min.css';
						$file_src = $dir . substr($src, strlen($url));
						$file_min = $dir . substr($src_min, strlen($url));
						if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
					}
				}
				$file_src = $dir . substr($src, strlen($url));
				$load = file_exists($file_src) && filesize($file_src) > 0;
			}
		}
		if ($load) {
			if (is_array($src))
				wp_enqueue_style( $handle, $depts, $ver, $media );
			else
				wp_enqueue_style( $handle, $src, $depts, $ver, $media );
		}
	}
}

// Enqueue .min.js (if exists and filetime .min.js > filetime .js) instead .js
if (!function_exists('themerex_plugins_enqueue_script')) {	
	function themerex_plugins_enqueue_script($handle, $src=false, $depts=array(), $ver=null, $in_footer=true) {
		$load = true;
		if (!is_array($src) && $src !== false && $src !== '') {
			$plugin_dir  = substr(plugin_dir_path(__FILE__), 0, -9);
			$plugin_url  = substr(plugin_dir_url(__FILE__), 0, -9);
			$plugin_base = explode('/', plugin_basename(__FILE__));
			$theme_dir = get_template_directory().$plugin_base[0].'/';
			$theme_url = get_template_directory_uri().$plugin_base[0].'/';
			$child_dir = get_stylesheet_directory().$plugin_base[0].'/';
			$child_url = get_stylesheet_directory_uri().$plugin_base[0].'/';
			$dir = $url = '';
			if (strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			} else if (strpos($src, $plugin_url)===0) {
				$dir = $plugin_dir;
				$url = $plugin_url;
			}
			if ($dir != '') {
				if (substr($src, -3)=='.js') {
					if (substr($src, -7)!='.min.js') {
						$src_min  = substr($src, 0, strlen($src)-3).'.min.js';
						$file_src = $dir . substr($src, strlen($url));
						$file_min = $dir . substr($src_min, strlen($url));
						if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
					}
				}
				$file_src = $dir . substr($src, strlen($url));
				$load = file_exists($file_src) && filesize($file_src) > 0;
			}
		}
		if ($load) {
			if (is_array($src)) {
				wp_enqueue_script( $handle, $depts, $ver, $in_footer );
			} else {
				wp_enqueue_script( $handle, $src, $depts, $ver, $in_footer );
			}
		}
	}
}


/* Check if file/folder present in the child theme and return path (url) to it. 
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */
if (!function_exists('themerex_plugins_get_file_dir')) {	
	function themerex_plugins_get_file_dir($file, $return_url=false) {
		if ($file[0]=='/') $file = substr($file, 1);
		$plugin_dir  = substr(plugin_dir_path(__FILE__), 0, -9);
		$plugin_url  = substr(plugin_dir_url(__FILE__), 0, -9);
		$plugin_base = explode('/', plugin_basename(__FILE__));
		$theme_dir = get_template_directory().$plugin_base[0].'/';
		$theme_url = get_template_directory_uri().$plugin_base[0].'/';
		$child_dir = get_stylesheet_directory().$plugin_base[0].'/';
		$child_url = get_stylesheet_directory_uri().$plugin_base[0].'/';
		$dir = '';
		if (file_exists(($child_dir).($file)))
			$dir = ($return_url ? $child_url : $child_dir).($file);
		else if (file_exists(($theme_dir).($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).($file);
		else if (file_exists(($plugin_dir).($file)))
			$dir = ($return_url ? $plugin_url : $plugin_dir).($file);
		return $dir;
	}
}

if (!function_exists('themerex_plugins_get_file_url')) {	
	function themerex_plugins_get_file_url($file) {
		return themerex_plugins_get_file_dir($file, true);
	}
}
?>