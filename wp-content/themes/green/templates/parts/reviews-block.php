<?php
// Reviews block
$reviews_markup = '';
if ($avg_author > 0 || $avg_users > 0) {
	$reviews_first_author = green_get_theme_option('reviews_first')=='author';
	$reviews_second_hide = green_get_theme_option('reviews_second')=='hide';
	$use_tabs = !$reviews_second_hide; // && $avg_author > 0 && $avg_users > 0;
	if ($use_tabs) green_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
	$max_level = max(5, (int) green_get_custom_option('reviews_max_level'));
	$allow_user_marks = (!$reviews_first_author || !$reviews_second_hide) && (!isset($_COOKIE['green_votes']) || green_strpos($_COOKIE['green_votes'], ','.($post_data['post_id']).',')===false) && (green_get_theme_option('reviews_can_vote')=='all' || is_user_logged_in());
	$reviews_markup = '<div class="reviews_block'.($use_tabs ? ' sc_tabs sc_tabs_style_2' : '').'">';
	$output = $marks = $users = '';
	if ($use_tabs) {
		$author_tab = '<li class="sc_tabs_title"><a href="#author_marks" class="theme_button">'.esc_html__('Author', 'green').'</a></li>';
		$users_tab = '<li class="sc_tabs_title"><a href="#users_marks" class="theme_button">'.esc_html__('Users', 'green').'</a></li>';
		$output .= '<ul class="sc_tabs_titles">' . ($reviews_first_author ? ($author_tab) . ($users_tab) : ($users_tab) . ($author_tab)) . '</ul>';
	}
	// Criterias list
	$field = array(
		"options" => green_get_theme_option('reviews_criterias')
	);
	if (!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms)) {
		foreach ($post_data['post_terms'][$post_data['post_taxonomy']]->terms as $cat) {
			$id = (int) $cat->term_id;
			$prop = green_taxonomy_get_inherited_property($post_data['post_taxonomy'], $id, 'reviews_criterias');
			if (!empty($prop) && !green_is_inherit_option($prop)) {
				$field['options'] = $prop;
				break;
			}
		}
	}
	// Author marks
	if ($reviews_first_author || !$reviews_second_hide) {
		$field["id"] = "reviews_marks_author";
		$field["descr"] = strip_tags($post_data['post_excerpt']);
		$field["accept"] = false;
		$marks = green_reviews_marks_to_display(green_reviews_marks_prepare(green_get_custom_option('reviews_marks'), count($field['options'])));
		$output .= '<div id="author_marks" class="sc_tabs_content">' . trim(green_reviews_get_markup($field, $marks, false, false, $reviews_first_author)) . '</div>';
	}
	// Users marks
	if (!$reviews_first_author || !$reviews_second_hide) {
		$marks = green_reviews_marks_to_display(green_reviews_marks_prepare(get_post_meta($post_data['post_id'], 'reviews_marks2', true), count($field['options'])));
		$users = max(0, get_post_meta($post_data['post_id'], 'reviews_users', true));
		$field["id"] = "reviews_marks_users";
		$field["descr"] = sprintf(esc_html__("Summary rating from <b>%s</b> user's marks.", 'green'), $users) . ' ' 
			.(!isset($_COOKIE['green_votes']) || green_strpos($_COOKIE['green_votes'], ','.($post_data['post_id']).',')===false
				? esc_html__('You can set own marks for this article - just click on stars above and press "Accept".', 'green')
				: esc_html__('Thanks for your vote!', 'green'));
		$field["accept"] = $allow_user_marks;
		$output .= '<div id="users_marks" class="sc_tabs_content"'.(!$output ? ' style="display: block;"' : '') . '>' . trim(green_reviews_get_markup($field, $marks, $allow_user_marks, false, !$reviews_first_author)) . '</div>';
	}
	$reviews_markup .= $output . '</div>';
	if ($allow_user_marks) {
		green_enqueue_script('jquery-ui-draggable', false, array('jquery', 'jquery-ui-core'), null, true);
		$reviews_markup .= '
			<script type="text/javascript">
				jQuery(document).ready(function() {
					GREEN_GLOBALS["reviews_allow_user_marks"] = '.($allow_user_marks ? 'true' : 'false').';
					GREEN_GLOBALS["reviews_max_level"] = '.($max_level).';
					GREEN_GLOBALS["reviews_levels"] = "'.trim(green_get_theme_option('reviews_criterias_levels')).'";
					GREEN_GLOBALS["reviews_vote"] = "'.(isset($_COOKIE['green_votes']) ? $_COOKIE['green_votes'] : '').'";
					GREEN_GLOBALS["reviews_marks"] = "'.($marks).'".split(",");
					GREEN_GLOBALS["reviews_users"] = '.max(0, $users).';
					GREEN_GLOBALS["post_id"] = '.($post_data['post_id']).';
				});
			</script>
		';
	}
	global $GREEN_GLOBALS;
	$GREEN_GLOBALS['reviews_markup'] = $reviews_markup;
}
?>