<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'green_add_theme_inheritance' ) ) {
	function green_add_theme_inheritance($options, $append=true) {
		global $GREEN_GLOBALS;
		if (!isset($GREEN_GLOBALS["inheritance"])) $GREEN_GLOBALS["inheritance"] = array();
		$GREEN_GLOBALS['inheritance'] = $append 
			? green_array_merge($GREEN_GLOBALS['inheritance'], $options) 
			: green_array_merge($options, $GREEN_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'green_get_theme_inheritance' ) ) {
	function green_get_theme_inheritance($key = '') {
		global $GREEN_GLOBALS;
		return $key ? $GREEN_GLOBALS['inheritance'][$key] : $GREEN_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'green_detect_inheritance_key' ) ) {
	function green_detect_inheritance_key() {
		static $inheritance_key = '';
		if (!empty($inheritance_key)) return $inheritance_key;
		$inheritance_key = apply_filters('green_filter_detect_inheritance_key', '');
		return $inheritance_key;
	}
}


// Return key for override parameter
if ( !function_exists( 'green_get_override_key' ) ) {
	function green_get_override_key($value, $by) {
		$key = '';
		$inheritance = green_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'green_get_taxonomy_categories_by_post_type' ) ) {
	function green_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = green_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'green_get_taxonomy_tags_by_post_type' ) ) {
	function green_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = green_get_theme_inheritance();
		if (!empty($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>