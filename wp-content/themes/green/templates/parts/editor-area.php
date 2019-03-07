<?php
//====================================== Editor area ========================================
if ($post_data['post_edit_enable']) {
	wp_register_script( 'wp-color-picker', get_site_url().'/wp-admin/js/color-picker.min.js', array('jquery'), '1.0', true);
	green_enqueue_style ( 'fontello-admin',        green_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null);
	green_enqueue_style ( 'frontend-editor-style', green_get_file_url('js/core.editor/core.editor.css'), array(), null );
	green_enqueue_script( 'frontend-editor',       green_get_file_url('js/core.editor/core.editor.js'),  array(), null, true );
	green_enqueue_messages();
	green_options_load_scripts();
	green_options_prepare_scripts($post_data['post_type']);
	green_sc_load_scripts();
	green_sc_prepare_scripts();
	?>
	<div id="frontend_editor">
		<div id="frontend_editor_inner">
			<form method="post">
				<label id="frontend_editor_post_title_label" for="frontend_editor_post_title"><?php esc_html_e('Title', 'green'); ?></label>
				<input type="text" name="frontend_editor_post_title" id="frontend_editor_post_title" value="<?php echo esc_attr($post_data['post_title']); ?>" />
				<?php
				wp_editor($post_data['post_content_original'], 'frontend_editor_post_content', array(
					'wpautop' => true,
					'textarea_rows' => 16
				));
				?>
				<label id="frontend_editor_post_excerpt_label" for="frontend_editor_post_excerpt"><?php esc_html_e('Excerpt', 'green'); ?></label>
				<textarea name="frontend_editor_post_excerpt" id="frontend_editor_post_excerpt"><?php echo htmlspecialchars($post_data['post_excerpt_original']); ?></textarea>
				<input type="button" id="frontend_editor_button_save" value="<?php echo esc_attr(esc_html__('Save', 'green')); ?>" />
				<input type="button" id="frontend_editor_button_cancel" value="<?php echo esc_attr(esc_html__('Cancel', 'green')); ?>" />
				<input type="hidden" id="frontend_editor_post_id" name="frontend_editor_post_id" value="<?php echo esc_attr($post_data['post_id']); ?>" />
			</form>
		</div>
	</div>
	<?php
}
?>
