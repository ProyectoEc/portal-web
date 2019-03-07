<?php
/**
 * ThemeREX Framework: date and time manipulations
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Convert date from MySQL format (YYYY-mm-dd) to Date (dd.mm.YYYY)
if (!function_exists('green_sql_to_date')) {
	function green_sql_to_date($str) {
		return (trim($str)=='' || trim($str)=='0000-00-00' ? '' : trim(green_substr($str,8,2).'.'.green_substr($str,5,2).'.'.green_substr($str,0,4).' '.green_substr($str,11)));
	}
}

// Convert date from Date format (dd.mm.YYYY) to MySQL format (YYYY-mm-dd)
if (!function_exists('green_date_to_sql')) {
	function green_date_to_sql($str) {
		if (trim($str)=='') return '';
		$str = strtr(trim($str),'/\-,','....');
		if (trim($str)=='00.00.0000' || trim($str)=='00.00.00') return '';
		$pos = green_strpos($str,'.');
		$d=trim(green_substr($str,0,$pos));
		$str=green_substr($str,$pos+1);
		$pos = green_strpos($str,'.');
		$m=trim(green_substr($str,0,$pos));
		$y=trim(green_substr($str,$pos+1));
		$y=($y<50?$y+2000:($y<1900?$y+1900:$y));
		return ''.($y).'-'.(green_strlen($m)<2?'0':'').($m).'-'.(green_strlen($d)<2?'0':'').($d);
	}
}

// Return difference or date
if (!function_exists('green_get_date_or_difference')) {
	function green_get_date_or_difference($dt1, $dt2=null, $max_days=-1) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($max_days < 0) $max_days = green_get_theme_option('show_date_after', 30);
		if ($dt2 == null) $dt2 = date('Y-m-d H:i:s');
		$dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		$diff = $dt2n - $dt1n;
		$days = floor($diff / (24*3600));
		if (abs($days) < $max_days)
			return sprintf($days >= 0 ? esc_html__('%s ago', 'green') : esc_html__('after %s', 'green'), green_get_date_difference($days >= 0 ? $dt1 : $dt2, $days >= 0 ? $dt2 : $dt1));
		else
			return green_get_date_translations(date(get_option('date_format'), $dt1n));
	}
}

// Difference between two dates
if (!function_exists('green_get_date_difference')) {
	function green_get_date_difference($dt1, $dt2=null, $short=1, $sec = false) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($dt2 == null) $dt2 = time()+$gmt_offset*3600;
		else $dt2 = strtotime($dt2);
		$dt1 = strtotime($dt1);
		$diff = $dt2 - $dt1;
		$days = floor($diff / (24*3600));
		$months = floor($days / 30);
		$diff -= $days * 24 * 3600;
		$hours = floor($diff / 3600);
		$diff -= $hours * 3600;
		$min = floor($diff / 60);
		$diff -= $min * 60;
		$rez = '';
		if ($months > 0 && $short == 2)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($months > 1 ? esc_html__('%s months', 'green') : esc_html__('%s month', 'green'), $months);
		if ($days > 0 && ($short < 2 || $rez==''))
			$rez .= ($rez!='' ? ' ' : '') . sprintf($days > 1 ? esc_html__('%s days', 'green') : esc_html__('%s day', 'green'), $days);
		if ((!$short || $rez=='') && $hours > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($hours > 1 ? esc_html__('%s hours', 'green') : esc_html__('%s hour', 'green'), $hours);
		if ((!$short || $rez=='') && $min > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($min > 1 ? esc_html__('%s minutes', 'green') : esc_html__('%s minute', 'green'), $min);
		if ($sec || $rez=='')
			$rez .=  $rez!='' || $sec ? (' ' . sprintf($diff > 1 ? esc_html__('%s seconds', 'green') : esc_html__('%s second', 'green'), $diff)) : esc_html__('less then minute', 'green');
		return $rez;
	}
}

// Prepare month names in date for translation
if (!function_exists('green_get_date_translations')) {
	function green_get_date_translations($dt) {
		return str_replace(
			array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			array(
				esc_html__('January', 'green'),
				esc_html__('February', 'green'),
				esc_html__('March', 'green'),
				esc_html__('April', 'green'),
				esc_html__('May', 'green'),
				esc_html__('June', 'green'),
				esc_html__('July', 'green'),
				esc_html__('August', 'green'),
				esc_html__('September', 'green'),
				esc_html__('October', 'green'),
				esc_html__('November', 'green'),
				esc_html__('December', 'green'),
				esc_html__('Jan', 'green'),
				esc_html__('Feb', 'green'),
				esc_html__('Mar', 'green'),
				esc_html__('Apr', 'green'),
				esc_html__('May', 'green'),
				esc_html__('Jun', 'green'),
				esc_html__('Jul', 'green'),
				esc_html__('Aug', 'green'),
				esc_html__('Sep', 'green'),
				esc_html__('Oct', 'green'),
				esc_html__('Nov', 'green'),
				esc_html__('Dec', 'green'),
			),
			$dt);
	}
}
?>