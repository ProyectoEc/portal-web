<?php
/**
 * ThemeREX Framework: Testimonial post type settings
 *
 * @package	green
 * @since	green 1.0
 */

// Theme init
if (!function_exists('green_testimonial_theme_setup')) {
	add_action( 'green_action_before_init_theme', 'green_testimonial_theme_setup' );
	function green_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_action('admin_menu',			'green_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'green_testimonial_save_data');

		// Meta box fields
		global $GREEN_GLOBALS;
		$GREEN_GLOBALS['testimonial_meta_box'] = array(
			'id' => 'testimonial-meta-box',
			'title' => esc_html__('Testimonial Details', 'green'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'green'),
					"desc" => esc_html__("Name of the testimonial's author", 'green'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_author_profession" => array(
					"title" => esc_html__('Testimonial author profession',  'green'),
					"desc" => esc_html__("Name of the testimonial's author profession", 'green'),
					"class" => "testimonial_author_profession",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'green'),
					"desc" => esc_html__("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'green'),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'green'),
					"desc" => esc_html__("URL of the testimonial source or author profile page", 'green'),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);
		
		// Prepare type "Testimonial"
		green_require_data( 'post_type', 'testimonial', array(
			'label'               => esc_html__( 'Testimonial', 'green' ),
			'description'         => esc_html__( 'Testimonial Description', 'green' ),
			'labels'              => array(
				'name'                => _x( 'Testimonials', 'Post Type General Name', 'green' ),
				'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'green' ),
				'menu_name'           => esc_html__( 'Testimonials', 'green' ),
				'parent_item_colon'   => esc_html__( 'Parent Item:', 'green' ),
				'all_items'           => esc_html__( 'All Testimonials', 'green' ),
				'view_item'           => esc_html__( 'View Item', 'green' ),
				'add_new_item'        => esc_html__( 'Add New Testimonial', 'green' ),
				'add_new'             => esc_html__( 'Add New', 'green' ),
				'edit_item'           => esc_html__( 'Edit Item', 'green' ),
				'update_item'         => esc_html__( 'Update Item', 'green' ),
				'search_items'        => esc_html__( 'Search Item', 'green' ),
				'not_found'           => esc_html__( 'Not found', 'green' ),
				'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'green' ),
			),
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'menu_icon'			  => 'dashicons-cloud',
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 25,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			)
		);
		
		// Prepare taxonomy for testimonial
		green_require_data( 'taxonomy', 'testimonial_group', array(
			'post_type'			=> array( 'testimonial' ),
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => _x( 'Testimonials Group', 'taxonomy general name', 'green' ),
				'singular_name'     => _x( 'Group', 'taxonomy singular name', 'green' ),
				'search_items'      => esc_html__( 'Search Groups', 'green' ),
				'all_items'         => esc_html__( 'All Groups', 'green' ),
				'parent_item'       => esc_html__( 'Parent Group', 'green' ),
				'parent_item_colon' => esc_html__( 'Parent Group:', 'green' ),
				'edit_item'         => esc_html__( 'Edit Group', 'green' ),
				'update_item'       => esc_html__( 'Update Group', 'green' ),
				'add_new_item'      => esc_html__( 'Add New Group', 'green' ),
				'new_item_name'     => esc_html__( 'New Group Name', 'green' ),
				'menu_name'         => esc_html__( 'Testimonial Group', 'green' ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'testimonial_group' ),
			)
		);
	}
}


// Add meta box
if (!function_exists('green_testimonial_add_meta_box')) {
	//add_action('admin_menu', 'green_testimonial_add_meta_box');
	function green_testimonial_add_meta_box() {
		global $GREEN_GLOBALS;
		$mb = $GREEN_GLOBALS['testimonial_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'green_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('green_testimonial_show_meta_box')) {
	function green_testimonial_show_meta_box() {
		global $post, $GREEN_GLOBALS;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		$data = get_post_meta($post->ID, 'testimonial_data', true);
	
		$fields = $GREEN_GLOBALS['testimonial_meta_box']['fields'];
		?>
		<table class="testimonial_area">
		<?php
		foreach ($fields as $id=>$field) { 
			$meta = isset($data[$id]) ? $data[$id] : '';
			?>
			<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
				<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
				<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
					<br><small><?php echo esc_attr($field['desc']); ?></small></td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('green_testimonial_save_data')) {
	//add_action('save_post', 'green_testimonial_save_data');
	function green_testimonial_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['meta_box_testimonial_nonce']) || !wp_verify_nonce($_POST['meta_box_testimonial_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $GREEN_GLOBALS;

		$data = array();

		$fields = $GREEN_GLOBALS['testimonial_meta_box']['fields'];

		// Post type specific data handling
		foreach ($fields as $id=>$field) { 
			if (isset($_POST[$id])) 
				$data[$id] = stripslashes($_POST[$id]);
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}
?>