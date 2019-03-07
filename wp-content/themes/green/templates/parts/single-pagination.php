<?php
if ($post_data['post_type'] == 'lesson') {
	
} else {
	wp_link_pages( array( 
		'before' => '<nav class="pagination_single" role="navigation"><span class="pager_pages">' . esc_html__( 'Pages:', 'green' ) . '</span>', 
		'after' => '</nav>',
		'link_before' => '<span class="pager_numbers">',
		'link_after' => '</span>'
		)
	); 
}
?>