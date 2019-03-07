// Interactive change skin custom styles
function ancora_skin_customizer(option, val) {

	var custom_style = '';

	if (option == 'bg_color') {

		jQuery("#custom_options .co_switch_box a[data-value='boxed']").trigger('click');
		jQuery('#custom_options #co_bg_pattern_list .co_pattern_wrapper, #custom_options #co_bg_images_list .co_image_wrapper').removeClass('active');
		jQuery('body').removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3').css('backgroundColor', clr);

	} else if (option == 'bg_pattern') {

		jQuery('body')
			.removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3')
			.css('backgroundColor', 'transparent')
			.addClass('bg_pattern_' + val);

	} else if (option == 'bg_image') {

		jQuery('body')
			.removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3')
			.css('backgroundColor', 'transparent')
			.addClass('bg_image_' + val);

	} else if (option == 'link_color') {

		var clr = val;
		var rgb = ancora_hex2rgb(clr);
		// Link color styles



		// .link_dark_bg\
		// 	{\
		// 		background: '+clr+';\
		// 	}\
		// .post_item:not(.post_item_courses) > .post_content.ih-item.square.effect_shift.colored .info\
		// 	{\
		// 		background-color: rgba('+rgb.r+','+rgb.g+','+rgb.b+', 0.6);\
		// 	}\


		custom_style += '\
		';
		// Link dark color
		hsb = ancora_hex2hsb(clr);
		hsb.s = Math.min(100, hsb.s + 15);
		hsb.b = Math.max(0, hsb.b - 20);
		clr = ancora_hsb2hex(hsb);
		custom_style += '\
		';

	} else if (option == 'menu_color') {

		var clr = val;
		var rgb = ancora_hex2rgb(clr);
		// Menu color styles
		custom_style += '\
		';
		
	} else if (option == 'user_color') {

		var clr = val;
		var rgb = ancora_hex2rgb(clr);
		// User menu color styles
		custom_style += '\
		';
		// User menu dark color
		hsb = ancora_hex2hsb(clr);
		hsb.s = Math.min(100, hsb.s + 15);
		hsb.b = Math.max(0, hsb.b - 20);
		clr = ancora_hsb2hex(hsb);
		custom_style += '\
		';

	//} else if (option == 'body_style') {

		//Uncomment next row to apply changes without reloading page
		//jQuery('body').removeClass('body_style_boxed body_style_wide body_style_fullwide body_style_fullscreen').addClass('body_style_'+val);
	} else {
		ancora_custom_options_show_loader();
		//location.reload();
		var loc = jQuery('#co_site_url').val();
		var params = ANCORA_GLOBALS['co_add_params']!=undefined ? ANCORA_GLOBALS['co_add_params'] : {};
		params[option] = val;
		var pos = -1, pos2 = -1, pos3 = -1;
		for (var option in params) {
			val = params[option];
			pos = pos2 = pos3 = -1;
			if ((pos = loc.indexOf('?')) > 0) {
				if ((pos2 = loc.indexOf(option, pos)) > 0) {
					if ((pos3 = loc.indexOf('&', pos2)) > 0)
						loc = loc.substr(0, pos2) + option+'='+val + loc.substr(pos3);
					else
						loc = loc.substr(0, pos2) + option+'='+val;
				} else
					loc += '&'+option+'='+val;
			} else
				loc += '?'+option+'='+val;
		}
		window.location.href = loc;
		return;

	}

	if (custom_style != '') {
		var styles = jQuery('#ancora-customizer-styles-'+option).length > 0 ? jQuery('#ancora-customizer-styles-'+option) : '';
		if (styles.length == 0)
			jQuery('head').append('<style id="ancora-customizer-styles-'+option+'" type="text/css">'+custom_style+'</style>');
		else
			styles.html(custom_style);
	}
}
