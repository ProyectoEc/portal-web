<?php
/**
 * ThemeREX Framework: Registered Users
 *
 * @package	green
 * @since	green 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('green_users_theme_setup')) {
	add_action( 'green_action_before_init_theme', 'green_users_theme_setup' );
	function green_users_theme_setup() {

		if ( is_admin() ) {
			// Add extra fields in the user profile
			add_action( 'show_user_profile',		'green_add_fields_in_user_profile' );
			add_action( 'edit_user_profile',		'green_add_fields_in_user_profile' );
	
			// Save / update additional fields from profile
			add_action( 'personal_options_update',	'green_save_fields_in_user_profile' );
			add_action( 'edit_user_profile_update',	'green_save_fields_in_user_profile' );
		}

	}
}


// Return (and show) user profiles links
if (!function_exists('green_show_user_socials')) {
	function green_show_user_socials($args) {
		$args = array_merge(array(
			'author_id' => 0,						// author's ID
			'allowed' => array(),					// list of allowed social
			'size' => 'small',						// icons size: tiny|small|big
			'style' => 'bg',						// style for show icons: icons|images|bg
			'echo' => true							// if true - show on page, else - only return as string
			), is_array($args) ? $args 
				: array('author_id' => $args));		// If send one number parameter - use it as author's ID
		$output = '';
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		$social_list = green_get_theme_option('social_icons');
		$list = array();
		foreach ($social_list as $soc) {
			$sn = basename($soc['icon']);
			$sn = $args['style']=='icons' ? green_substr($sn, green_strrpos($sn, '-')+1) : green_substr($sn, 0, green_strrpos($sn, '.'));
			if (($pos=green_strrpos($sn, '_'))!==false)
				$sn = green_substr($sn, 0, $pos);
			if (count($args['allowed'])==0 || in_array($sn, $args['allowed'])) {
				$link = get_the_author_meta('user_' . ($sn), $args['author_id']);
				if ($link) {
					$icon = $args['style']=='icons' || green_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : green_get_socials_url(basename($soc['icon']));
					$list[] = array(
						'icon'	=> $icon,
						'url'	=> $link
					);
				}
			}
		}
		if (count($list) > 0) {
			$output = '<div class="sc_socials sc_socials_size_small">' . trim(green_prepare_socials($list, array( 'style' => $args['style'], 'size' => $args['size']))) . '</div>';
			if ($args['echo']) echo ($output);
		}
		return $output;
	}
}

// Show additional fields in the user profile
if (!function_exists('green_add_fields_in_user_profile')) {
	function green_add_fields_in_user_profile( $user ) { 
	?>
		<h3><?php esc_html_e('User Position', 'green'); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="user_position"><?php esc_html_e('User position', 'green'); ?>:</label></th>
				<td><input type="text" name="user_position" id="user_position" size="55" value="<?php echo esc_attr(get_the_author_meta('user_position', $user->ID)); ?>" />
					<span class="description"><?php esc_html_e('Please, enter your position in the company', 'green'); ?></span>
				</td>
			</tr>
		</table>
	
		<h3><?php esc_html_e('Social links', 'green'); ?></h3>
		<table class="form-table">
		<?php
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		$social_list = green_get_theme_option('social_icons');
		foreach ($social_list as $soc) {
			$sn = basename($soc['icon']);
			$sn = green_substr($sn, 0, green_strrpos($sn, '.'));
			if (($pos=green_strrpos($sn, '_'))!==false)
				$sn = green_substr($sn, 0, $pos);
			if (!empty($sn)) {
				?>
				<tr>
					<th><label for="user_<?php echo esc_attr($sn); ?>"><?php echo trim(green_strtoproper($sn)); ?>:</label></th>
					<td><input type="text" name="user_<?php echo esc_attr($sn); ?>" id="user_<?php echo esc_attr($sn); ?>" size="55" value="<?php echo esc_attr(get_the_author_meta('user_'.($sn), $user->ID)); ?>" />
						<span class="description"><?php echo sprintf(esc_html__('Please, enter your %s link', 'green'), green_strtoproper($sn)); ?></span>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
	<?php
	}
}

// Save / update additional fields
if (!function_exists('green_save_fields_in_user_profile')) {
	function green_save_fields_in_user_profile( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;
		update_user_meta( $user_id, 'user_position', $_POST['user_position'] );
		$social_list = green_get_theme_option('social_icons');
		foreach ($social_list as $soc) {
			$sn = basename($soc['icon']);
			$sn = green_substr($sn, 0, green_strrpos($sn, '.'));
			if (($pos=green_strrpos($sn, '_'))!==false)
				$sn = green_substr($sn, 0, $pos);
			update_user_meta( $user_id, 'user_'.($sn), $_POST['user_'.($sn)] );
		}
	}
}
?>