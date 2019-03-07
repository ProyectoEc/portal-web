<?php
/**
 * The Header for our theme.
 */

global $GREEN_GLOBALS;

// Theme init - don't remove next row! Load custom options
green_core_init_theme();

$theme_skin = green_esc(green_get_custom_option('theme_skin'));
$blog_style = green_get_custom_option(is_singular() && !green_get_global('blog_streampage') ? 'single_style' : 'blog_style');
$body_style  = green_get_custom_option('body_style');
$logo_style = green_get_custom_option('top_panel_style');
$article_style = green_get_custom_option('article_style');
$top_panel_style = green_get_custom_option('top_panel_style');
$top_panel_opacity = green_get_custom_option('top_panel_opacity');
$top_panel_position = green_get_custom_option('top_panel_position');
$video_bg_show  = green_get_custom_option('show_video_bg')=='yes' && (green_get_custom_option('video_bg_youtube_code')!='' || green_get_custom_option('video_bg_url')!='');
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="format-detection" content="telephone=no">
	<?php
	if (green_get_theme_option('responsive_layouts') == 'yes') {
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<?php
	}
	if (floatval(get_bloginfo('version')) < "4.1") {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	?>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php
	
	if ( !function_exists('has_site_icon') || !has_site_icon() ) {
		$favicon = green_get_custom_option('favicon');
		if (!$favicon) {
			if ( file_exists(green_get_file_dir('skins/'.($theme_skin).'/images/favicon.ico')) )
				$favicon = green_get_file_url('skins/'.($theme_skin).'/images/favicon.ico');
			if ( !$favicon && file_exists(green_get_file_dir('favicon.ico')) )
				$favicon = green_get_file_url('favicon.ico');
		}
		if ($favicon) {
			?><link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>" /><?php
		}
	}
	
	
    wp_head();
    ?>

</head>

<?php
	$class = $style = '';
	if ($body_style=='boxed' || green_get_custom_option('load_bg_image')=='always') {
		$customizer = green_get_theme_option('show_theme_customizer') == 'yes';
		if ($customizer && ($img = (int) green_get_value_gpc('bg_image', 0)) > 0)
			$class = 'bg_image_'.($img);
		else if ($customizer && ($img = (int) green_get_value_gpc('bg_pattern', 0)) > 0)
			$class = 'bg_pattern_'.($img);
		else if ($customizer && ($img = green_get_value_gpc('bg_color', '')) != '')
			$style = 'background-color: '.($img).';';
		else {
			if (($img = green_get_custom_option('bg_custom_image')) != '')
				$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', green_get_custom_option('bg_custom_image_position')) . ' no-repeat fixed;';
			else if (($img = green_get_custom_option('bg_custom_pattern')) != '')
				$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
			else if (($img = green_get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = green_get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'.($img);
			if (($img = green_get_custom_option('bg_color')) != '')
				$style .= 'background-color: '.($img).';';
		}
	}
?>

<body <?php 
	body_class('green_body body_style_' . esc_attr($body_style) 
		. ' body_' . (green_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent')
		. ' theme_skin_' . esc_attr($theme_skin)
		. ' article_style_' . esc_attr($article_style)
		. ' layout_' . esc_attr($blog_style)
		. ' template_' . esc_attr(green_get_template_name($blog_style))
		. ' top_panel_style_' . esc_attr($top_panel_style)
		. ' top_panel_opacity_' . esc_attr($top_panel_opacity)
		. ($top_panel_position  != 'hide' ? ' top_panel_show top_panel_' . esc_attr($top_panel_position) : '')
		. ' menu_' . esc_attr(green_get_custom_option('menu_align'))
		. ' user_menu_' . (green_get_custom_option('show_menu_user')=='yes' ? 'show' : 'hide')
		. ' ' . esc_attr(green_get_sidebar_class(green_get_custom_option('show_sidebar_main'), green_get_custom_option('sidebar_main_position')))
		. ($video_bg_show ? ' video_bg_show' : '')
		. ($class!='' ? ' ' . esc_attr($class) : '')
	);
	if ($style!='') echo ' style="'.esc_attr($style).'"';
	?>
>
	<?php echo force_balance_tags(green_get_custom_option('gtm_code')); ?>

	<?php do_action( 'before' ); ?>

    <?php
    if (function_exists('trx_utils_require_shortcode')) {
        if (green_get_custom_option('menu_toc_home')=='yes') echo do_shortcode( '[trx_anchor id="toc_home" title="'.esc_html__('Home', 'green').'" description="'.esc_html__('{Return to Home} - |navigate to home page of the site', 'green').'" icon="icon-home-1" separator="yes" url="'.esc_url( home_url( '/' ) ).'"]' );
        if (green_get_custom_option('menu_toc_top')=='yes') echo do_shortcode( '[trx_anchor id="toc_top" title="'.esc_html__('To Top', 'green').'" description="'.esc_html__('{Back to top} - |scroll to top of the page', 'green').'" icon="icon-angle-double-up" separator="yes"]' );

    }
    ?>

	<div class="body_wrap">

		<?php
		if ($video_bg_show) {
			$youtube = green_get_custom_option('video_bg_youtube_code');
			$video   = green_get_custom_option('video_bg_url');
			$overlay = green_get_custom_option('video_bg_overlay')=='yes';
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php echo ($overlay ? ' video_bg_overlay' : ''); ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$info = pathinfo($video);
				$ext = !empty($info['extension']) ? $info['extension'] : 'src';
				?>
				<div class="video_bg<?php echo esc_attr($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>

		<div class="page_wrap">

			<?php
			// Top panel and Slider
			if (in_array($top_panel_position, array('above', 'over'))) { require_once( green_get_file_dir('templates/parts/top-panel.php') ); }
			require_once( green_get_file_dir('templates/parts/slider.php') );
			if ($top_panel_position == 'below') { require_once( green_get_file_dir('templates/parts/top-panel.php') ); }

			// User custom header
			if (green_get_custom_option('show_user_header') == 'yes') {
				$user_header = green_strclear(green_get_custom_option('user_header_content'), 'p');
				if (!empty($user_header)) {
					$user_header = green_substitute_all($user_header);
					?>
					<div class="user_header_wrap"><?php echo ($user_header); ?></div>
					<?php
				}
			}

			// Top of page section: page title and breadcrumbs
			$header_style = '';
			if ($top_panel_style=='light') {
				$header_image2 = get_header_image();
				$header_color = green_get_custom_option('show_page_top') == 'yes' ? green_get_link_color(green_get_custom_option('top_panel_bg_color')) : '';
				if (empty($header_image2) && file_exists(green_get_file_dir('skins/'.($theme_skin).'images/bg_over.png'))) {
					$header_image2 = green_get_file_url('skins/'.($theme_skin).'images/bg_over.png');
				}
				if ($header_image2!='' || $header_color != '') { 
					$header_style = ' style="' . ($header_image2!='' ? 'background-image: url('.esc_url($header_image2).'); 
										background-repeat: repeat-x; background-position: center top;' : '') 
									.($header_color ? ' background-color:'.esc_attr($header_color).';' : '') . '"';
				}
			}
			if (green_get_custom_option('show_page_top') == 'yes') {
				$show_title = green_get_custom_option('show_page_title')=='yes';
				$show_breadcrumbs = green_get_custom_option('show_breadcrumbs')=='yes';
				?>
				<div class="page_top_wrap<?php echo ($show_title ? ' page_top_title' : '') . ($show_breadcrumbs ? ' page_top_breadcrumbs' : ''); ?>">
					<div class="content_wrap">
						<?php if ($show_breadcrumbs) { ?>
							<div class="breadcrumbs">
								<?php if (!is_404()) green_show_breadcrumbs(); ?>
							</div>
						<?php } ?>
						<?php if ($show_title) { ?>
							<h1 class="page_title"><?php echo strip_tags(green_get_blog_title()); ?></h1>
						<?php } ?>
					</div>
				</div>
			<?php
			}
			?>

			<div class="page_content_wrap <?php if (green_get_custom_option('show_slider')=='yes') { ?> page_content_wrap_slider <?php } ?>"<?php echo (green_get_custom_option('show_page_top') == 'no' ? ' ' . trim($header_style) : ''); ?>>

				<?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') green_open_wrapper('<div class="content_wrap">');
				
				// Main content wrapper
				green_open_wrapper('<div class="content">');
				?>