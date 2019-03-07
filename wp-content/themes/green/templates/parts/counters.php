<?php
$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';
 
if ($show_all_counters || green_strpos($post_options['counters'], 'views')!==false) {
	$post_data['post_views'] = green_get_post_views(get_the_ID());
	?>
	<<?php echo ($counters_tag); ?> class="post_counters_item post_counters_views" title="<?php echo sprintf(esc_html__('Views - %s', 'green'), $post_data['post_views']); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"> <span>Views</span> <?php echo ($post_data['post_views']); ?></<?php echo ($counters_tag); ?>>
	<?php
}

if ($show_all_counters || green_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment-1" title="<?php echo sprintf(esc_html__('Comments - %s', 'green'), $post_data['post_comments']); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo ($post_data['post_comments']); ?></span></a>
	<?php 
}
 
$rating = $post_data['post_reviews_'.(green_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || green_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php echo ($counters_tag); ?> class="post_counters_item post_counters_rating icon-star-1" title="<?php echo sprintf(esc_html__('Rating - %s', 'green'), $rating); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo ($rating); ?></span></<?php echo ($counters_tag); ?>>
	<?php
}

if ($show_all_counters || green_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	green_enqueue_messages();
	$likes = isset($_COOKIE['green_likes']) ? $_COOKIE['green_likes'] : '';
	$allow = green_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo ($allow ? 'enabled' : 'disabled'); ?>" title="<?php echo esc_attr($allow ? esc_html__('Like', 'green') : esc_html__('Dislike', 'green')); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_html_e('Like', 'green'); ?>"
		data-title-dislike="<?php esc_html_e('Dislike', 'green'); ?>"><span class="post_counters_number"><?php echo ($post_data['post_likes']); ?></span></a>
	<?php
}

if (is_single() && green_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(green_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(green_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>