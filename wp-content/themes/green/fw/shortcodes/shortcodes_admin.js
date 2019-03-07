// Init scripts
jQuery(document).ready(function(){
	"use strict";
	
	// Settings and constants
	GREEN_GLOBALS['shortcodes_delimiter'] = ',';		// Delimiter for multiple values
	GREEN_GLOBALS['shortcodes_popup'] = null;		// Popup with current shortcode settings
	GREEN_GLOBALS['shortcodes_current_idx'] = '';	// Current shortcode's index
	GREEN_GLOBALS['shortcodes_tab_clone_tab'] = '<li id="green_shortcodes_tab_{id}" data-id="{id}"><a href="#green_shortcodes_tab_{id}_content"><span class="iconadmin-{icon}"></span>{title}</a></li>';
	GREEN_GLOBALS['shortcodes_tab_clone_content'] = '';

	// Shortcode selector - "change" event handler - add selected shortcode in editor
	jQuery('body').on('change', ".sc_selector", function() {
		"use strict";
		GREEN_GLOBALS['shortcodes_current_idx'] = jQuery(this).find(":selected").val();
		if (GREEN_GLOBALS['shortcodes_current_idx'] == '') return;
		var sc = green_clone_object(GREEN_GLOBALS['shortcodes'][GREEN_GLOBALS['shortcodes_current_idx']]);
		var hdr = sc.title;
		var content = "";
		try {
			content = tinyMCE.activeEditor ? tinyMCE.activeEditor.selection.getContent({format : 'raw'}) : jQuery('#wp-content-editor-container textarea').selection();
		} catch(e) {};
		if (content) {
			for (var i in sc.params) {
				if (i == '_content_') {
					sc.params[i].value = content;
					break;
				}
			}
		}
		var html = (!green_empty(sc.desc) ? '<p>'+sc.desc+'</p>' : '')
			+ green_shortcodes_prepare_layout(sc);

		// Show Dialog popup
		GREEN_GLOBALS['shortcodes_popup'] = green_message_dialog(html, hdr,
			function(popup) {
				"use strict";
				green_options_init(popup);
				popup.find('.green_options_tab_content').css({
					maxHeight: jQuery(window).height() - 300 + 'px',
					overflow: 'auto'
				});
			},
			function(btn, popup) {
				"use strict";
				if (btn != 1) return;
				var sc = green_shortcodes_get_code(GREEN_GLOBALS['shortcodes_popup']);
				if (tinyMCE.activeEditor) {
					if ( !tinyMCE.activeEditor.isHidden() )
						tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, sc );
					//else if (typeof wpActiveEditor != 'undefined' && wpActiveEditor != '') {
					//	document.getElementById( wpActiveEditor ).value += sc;
					else
						send_to_editor(sc);
				} else
					send_to_editor(sc);
			});

		// Set first item active
		jQuery(this).get(0).options[0].selected = true;

		// Add new child tab
		GREEN_GLOBALS['shortcodes_popup'].find('.green_shortcodes_tab').on('tabsbeforeactivate', function (e, ui) {
			if (ui.newTab.data('id')=='add') {
				green_shortcodes_add_tab(ui.newTab);
				e.stopImmediatePropagation();
				e.preventDefault();
				return false;
			}
		});

		// Delete child tab
		GREEN_GLOBALS['shortcodes_popup'].find('.green_shortcodes_tab > ul').on('click', '> li+li > a > span', function (e) {
			var tab = jQuery(this).parents('li');
			var idx = tab.data('id');
			if (parseInt(idx) > 1) {
				if (tab.hasClass('ui-state-active')) {
					tab.prev().find('a').trigger('click');
				}
				tab.parents('.green_shortcodes_tab').find('.green_options_tab_content').eq(idx).remove();
				tab.remove();
				e.preventDefault();
				return false;
			}
		});

		return false;
	});

});



// Return result code
//------------------------------------------------------------------------------------------
function green_shortcodes_get_code(popup) {
	GREEN_GLOBALS['sc_custom'] = '';
	
	var sc_name = GREEN_GLOBALS['shortcodes_current_idx'];
	var sc = GREEN_GLOBALS['shortcodes'][sc_name];
	var tabs = popup.find('.green_shortcodes_tab > ul > li');
	var decor = !green_isset(sc.decorate) || sc.decorate;
	var rez = '[' + sc_name + green_shortcodes_get_code_from_tab(popup.find('#green_shortcodes_tab_0_content').eq(0)) + ']'
			// + (decor ? '\n' : '')
			;
	if (green_isset(sc.children)) {
		if (GREEN_GLOBALS['sc_custom']!='no') {
			var decor2 = !green_isset(sc.children.decorate) || sc.children.decorate;
			for (var i=0; i<tabs.length; i++) {
				var tab = tabs.eq(i);
				var idx = tab.data('id');
				if (isNaN(idx) || parseInt(idx) < 1) continue;
				var content = popup.find('#green_shortcodes_tab_' + idx + '_content').eq(0);
				rez += (decor2 ? '\n\t' : '') + '[' + sc.children.name + green_shortcodes_get_code_from_tab(content) + ']';	// + (decor2 ? '\n' : '');
				if (green_isset(sc.children.container) && sc.children.container) {
					if (content.find('[data-param="_content_"]').length > 0) {
						rez += 
							//(decor2 ? '\t\t' : '') + 
							content.find('[data-param="_content_"]').val()
							// + (decor2 ? '\n' : '')
							;
					}
					rez += 
						//(decor2 ? '\t' : '') + 
						'[/' + sc.children.name + ']'
						// + (decor ? '\n' : '')
						;
				}
			}
		}
	} else if (green_isset(sc.container) && sc.container && popup.find('#green_shortcodes_tab_0_content [data-param="_content_"]').length > 0) {
		rez += 
			//(decor ? '\t' : '') + 
			popup.find('#green_shortcodes_tab_0_content [data-param="_content_"]').val()
			// + (decor ? '\n' : '')
			;
	}
	if (green_isset(sc.container) && sc.container || green_isset(sc.children))
		rez += 
			(green_isset(sc.children) && decor && GREEN_GLOBALS['sc_custom']!='no' ? '\n' : '')
			+ '[/' + sc_name + ']'
			 //+ (decor ? '\n' : '')
			 ;
	return rez;
}

// Collect all parameters from tab into string
function green_shortcodes_get_code_from_tab(tab) {
	var rez = ''
	var mainTab = tab.attr('id').indexOf('tab_0') > 0;
	tab.find('[data-param]').each(function () {
		var field = jQuery(this);
		var param = field.data('param');
		if (!field.parents('.green_options_field').hasClass('green_options_no_use') && param.substr(0, 1)!='_' && !green_empty(field.val()) && field.val()!='none' && (field.attr('type') != 'checkbox' || field.get(0).checked)) {
			rez += ' '+param+'="'+green_shortcodes_prepare_value(field.val())+'"';
		}
		// On main tab detect param "custom"
		if (mainTab && param=='custom') {
			GREEN_GLOBALS['sc_custom'] = field.val();
		}
	});
	// Get additional params for general tab from items tabs
	if (GREEN_GLOBALS['sc_custom']!='no' && mainTab) {
		var sc = GREEN_GLOBALS['shortcodes'][GREEN_GLOBALS['shortcodes_current_idx']];
		var sc_name = GREEN_GLOBALS['shortcodes_current_idx'];
		if (sc_name == 'trx_columns' || sc_name == 'trx_skills' || sc_name == 'trx_team') {	// Determine "count" parameter
			var cnt = 0;
			tab.siblings('div').each(function() {
				var item_tab = jQuery(this);
				var merge = parseInt(item_tab.find('[data-param="span"]').val());
				cnt += !isNaN(merge) && merge > 0 ? merge : 1;
			});
			rez += ' count="'+cnt+'"';
		}
	}
	return rez;
}


// Shortcode parameters builder
//-------------------------------------------------------------------------------------------

// Prepare layout from shortcode object (array)
function green_shortcodes_prepare_layout(field) {
	"use strict";
	// Make params cloneable
	field['params'] = [field['params']];
	if (!green_empty(field.children)) {
		field.children['params'] = [field.children['params']];
	}
	// Prepare output
	var output = '<div class="green_shortcodes_body green_options_body"><form>';
	output += green_shortcodes_show_tabs(field);
	output += green_shortcodes_show_field(field, 0);
	if (!green_empty(field.children)) {
		GREEN_GLOBALS['shortcodes_tab_clone_content'] = green_shortcodes_show_field(field.children, 1);
		output += GREEN_GLOBALS['shortcodes_tab_clone_content'];
	}
	output += '</div></form></div>';
	return output;
}



// Show tabs
function green_shortcodes_show_tabs(field) {
	"use strict";
	// html output
	var output = '<div class="green_shortcodes_tab green_options_container green_options_tab">'
		+ '<ul>'
		+ GREEN_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, 0).replace('{icon}', 'cog').replace('{title}', 'General');
	if (green_isset(field.children)) {
		for (var i=0; i<field.children.params.length; i++)
			output += GREEN_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, i+1).replace('{icon}', 'cancel').replace('{title}', field.children.title + ' ' + (i+1));
		output += GREEN_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, 'add').replace('{icon}', 'list-add').replace('{title}', '');
	}
	output += '</ul>';
	return output;
}

// Add new tab
function green_shortcodes_add_tab(tab) {
	"use strict";
	var idx = 0;
	tab.siblings().each(function () {
		"use strict";
		var i = parseInt(jQuery(this).data('id'));
		if (i > idx) idx = i;
	});
	idx++;
	tab.before( GREEN_GLOBALS['shortcodes_tab_clone_tab'].replace(/{id}/g, idx).replace('{icon}', 'cancel').replace('{title}', GREEN_GLOBALS['shortcodes'][GREEN_GLOBALS['shortcodes_current_idx']].children.title + ' ' + idx) );
	tab.parents('.green_shortcodes_tab').append(GREEN_GLOBALS['shortcodes_tab_clone_content'].replace(/tab_1_/g, 'tab_' + idx + '_'));
	tab.parents('.green_shortcodes_tab').tabs('refresh');
	green_options_init(tab.parents('.green_shortcodes_tab').find('.green_options_tab_content').eq(idx));
	tab.prev().find('a').trigger('click');
}



// Show one field layout
function green_shortcodes_show_field(field, tab_idx) {
	"use strict";
	
	// html output
	var output = '';

	// Parse field params
	for (var clone_num in field['params']) {
		var tab_id = 'tab_' + (parseInt(tab_idx) + parseInt(clone_num));
		output += '<div id="green_shortcodes_' + tab_id + '_content" class="green_options_content green_options_tab_content">';

		for (var param_num in field['params'][clone_num]) {
			
			var param = field['params'][clone_num][param_num];
			var id = tab_id + '_' + param_num;
	
			// Divider after field
			var divider = green_isset(param['divider']) && param['divider'] ? ' green_options_divider' : '';
		
			// Setup default parameters
			if (param['type']=='media') {
				if (!green_isset(param['before'])) {
					param['before'] = {
						'title': 'Choose image',
						'action': 'media_upload',
						'type': 'image',
						'multiple': false,
						'linked_field': '',
						'captions': { 	
							'choose': 'Choose image',
							'update': 'Select image'
							}
					};
				}
				if (!green_isset(param['after'])) {
					param['after'] = {
						'icon': 'iconadmin-cancel',
						'action': 'media_reset'
					};
				}
			}
		
			// Buttons before and after field
			var before = '', after = '', buttons_classes = '', rez, rez2, i, key, opt;
			
			if (green_isset(param['before'])) {
				rez = green_shortcodes_action_button(param['before'], 'before');
				before = rez[0];
				buttons_classes += rez[1];
			}
			if (green_isset(param['after'])) {
				rez = green_shortcodes_action_button(param['after'], 'after');
				after = rez[0];
				buttons_classes += rez[1];
			}
			if (green_in_array(param['type'], ['list', 'select', 'fonts']) || (param['type']=='socials' && (green_empty(param['style']) || param['style']=='icons'))) {
				buttons_classes += ' green_options_button_after_small';
			}

			if (param['type'] != 'hidden') {
				output += '<div class="green_options_field'
					+ ' green_options_field_' + (green_in_array(param['type'], ['list','fonts']) ? 'select' : param['type'])
					+ (green_in_array(param['type'], ['media', 'fonts', 'list', 'select', 'socials', 'date', 'time']) ? ' green_options_field_text'  : '')
					+ (param['type']=='socials' && !green_empty(param['style']) && param['style']=='images' ? ' green_options_field_images'  : '')
					+ (param['type']=='socials' && (green_empty(param['style']) || param['style']=='icons') ? ' green_options_field_icons'  : '')
					+ (green_isset(param['dir']) && param['dir']=='vertical' ? ' green_options_vertical' : '')
					+ (!green_empty(param['multiple']) ? ' green_options_multiple' : '')
					+ (green_isset(param['size']) ? ' green_options_size_'+param['size'] : '')
					+ (green_isset(param['class']) ? ' ' + param['class'] : '')
					+ divider 
					+ '">' 
					+ "\n"
					+ '<label class="green_options_field_label" for="' + id + '">' + param['title']
					+ '</label>'
					+ "\n"
					+ '<div class="green_options_field_content'
					+ buttons_classes
					+ '">'
					+ "\n";
			}
			
			if (!green_isset(param['value'])) {
				param['value'] = '';
			}
			

			switch ( param['type'] ) {
	
			case 'hidden':
				output += '<input class="green_options_input green_options_input_hidden" name="' + id + '" id="' + id + '" type="hidden" value="' + green_shortcodes_prepare_value(param['value']) + '" data-param="' + green_shortcodes_prepare_value(param_num) + '" />';
			break;

			case 'date':
				if (green_isset(param['style']) && param['style']=='inline') {
					output += '<div class="green_options_input_date"'
						+ ' id="' + id + '_calendar"'
						+ ' data-format="' + (!green_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!green_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-linked-field="' + (!green_empty(data['linked_field']) ? data['linked_field'] : id) + '"'
						+ '></div>'
						+ '<input id="' + id + '"'
							+ ' name="' + id + '"'
							+ ' type="hidden"'
							+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
							+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
							+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
							+ ' />';
				} else {
					output += '<input class="green_options_input green_options_input_date' + (!green_empty(param['mask']) ? ' green_options_input_masked' : '') + '"'
						+ ' name="' + id + '"'
						+ ' id="' + id + '"'
						+ ' type="text"'
						+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-format="' + (!green_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!green_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
						+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
						+ before 
						+ after;
				}
			break;

			case 'text':
				output += '<input class="green_options_input green_options_input_text' + (!green_empty(param['mask']) ? ' green_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
					+ (!green_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
				+ before 
				+ after;
			break;
		
			case 'textarea':
				var cols = green_isset(param['cols']) && param['cols'] > 10 ? param['cols'] : '40';
				var rows = green_isset(param['rows']) && param['rows'] > 1 ? param['rows'] : '8';
				output += '<textarea class="green_options_input green_options_input_textarea"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' cols="' + cols + '"'
					+ ' rows="' + rows + '"'
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ '>'
					+ param['value']
					+ '</textarea>';
			break;

			case 'spinner':
				output += '<input class="green_options_input green_options_input_spinner' + (!green_empty(param['mask']) ? ' green_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"' 
					+ (!green_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ (green_isset(param['min']) ? ' data-min="'+param['min']+'"' : '') 
					+ (green_isset(param['max']) ? ' data-max="'+param['max']+'"' : '') 
					+ (!green_empty(param['step']) ? ' data-step="'+param['step']+'"' : '') 
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />' 
					+ '<span class="green_options_arrows"><span class="green_options_arrow_up iconadmin-up-dir"></span><span class="green_options_arrow_down iconadmin-down-dir"></span></span>';
			break;

			case 'tags':
				var tags = param['value'].split(GREEN_GLOBALS['shortcodes_delimiter']);
				if (tags.length > 0) {
					for (i=0; i<tags.length; i++) {
						if (green_empty(tags[i])) continue;
						output += '<span class="green_options_tag iconadmin-cancel">' + tags[i] + '</span>';
					}
				}
				output += '<input class="green_options_input_tags"'
					+ ' type="text"'
					+ ' value=""'
					+ ' />'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
						+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case "checkbox": 
				output += '<input type="checkbox" class="green_options_input green_options_input_checkbox"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' value="true"' 
					+ (param['value'] == 'true' ? ' checked="checked"' : '') 
					+ (!green_empty(param['disabled']) ? ' readonly="readonly"' : '') 
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<label for="' + id + '" class="' + (!green_empty(param['disabled']) ? 'green_options_state_disabled' : '') + (param['value']=='true' ? ' green_options_state_checked' : '') + '"><span class="green_options_input_checkbox_image iconadmin-check"></span>' + (!green_empty(param['label']) ? param['label'] : param['title']) + '</label>';
			break;
		
			case "radio":
				for (key in param['options']) { 
					output += '<span class="green_options_radioitem"><input class="green_options_input green_options_input_radio" type="radio"'
						+ ' name="' + id + '"'
						+ ' value="' + green_shortcodes_prepare_value(key) + '"'
						+ ' data-value="' + green_shortcodes_prepare_value(key) + '"'
						+ (param['value'] == key ? ' checked="checked"' : '') 
						+ ' id="' + id + '_' + key + '"'
						+ ' />'
						+ '<label for="' + id + '_' + key + '"' + (param['value'] == key ? ' class="green_options_state_checked"' : '') + '><span class="green_options_input_radio_image iconadmin-circle-empty' + (param['value'] == key ? ' iconadmin-dot-circled' : '') + '"></span>' + param['options'][key] + '</label></span>';
				}
				output += '<input type="hidden"'
						+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
						+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';

			break;
		
			case "switch":
				opt = [];
				i = 0;
				for (key in param['options']) {
					opt[i++] = {'key': key, 'title': param['options'][key]};
					if (i==2) break;
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + green_shortcodes_prepare_value(green_empty(param['value']) ? opt[0]['key'] : param['value']) + '"'
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<span class="green_options_switch' + (param['value']==opt[1]['key'] ? ' green_options_state_off' : '') + '"><span class="green_options_switch_inner iconadmin-circle"><span class="green_options_switch_val1" data-value="' + opt[0]['key'] + '">' + opt[0]['title'] + '</span><span class="green_options_switch_val2" data-value="' + opt[1]['key'] + '">' + opt[1]['title'] + '</span></span></span>';
			break;

			case 'media':
				output += '<input class="green_options_input green_options_input_text green_options_input_media"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
					+ (!green_isset(param['readonly']) || param['readonly'] ? ' readonly="readonly"' : '')
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before 
					+ after;
				if (!green_empty(param['value'])) {
					var fname = green_get_file_name(param['value']);
					var fext  = green_get_file_ext(param['value']);
					output += '<a class="green_options_image_preview" rel="prettyPhoto" target="_blank" href="' + param['value'] + '">' + (fext!='' && green_in_list('jpg,png,gif', fext, ',') ? '<img src="'+param['value']+'" alt="" />' : '<span>'+fname+'</span>') + '</a>';
				}
			break;
		
			case 'button':
				rez = green_shortcodes_action_button(param, 'button');
				output += rez[0];
			break;

			case 'range':
				output += '<div class="green_options_input_range" data-step="'+(!green_empty(param['step']) ? param['step'] : 1) + '">'
					+ '<span class="green_options_range_scale"><span class="green_options_range_scale_filled"></span></span>';
				if (param['value'].toString().indexOf(GREEN_GLOBALS['shortcodes_delimiter']) == -1)
					param['value'] = Math.min(param['max'], Math.max(param['min'], param['value']));
				var sliders = param['value'].toString().split(GREEN_GLOBALS['shortcodes_delimiter']);
				for (i=0; i<sliders.length; i++) {
					output += '<span class="green_options_range_slider"><span class="green_options_range_slider_value">' + sliders[i] + '</span><span class="green_options_range_slider_button"></span></span>';
				}
				output += '<span class="green_options_range_min">' + param['min'] + '</span><span class="green_options_range_max">' + param['max'] + '</span>'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
						+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
					+ '</div>';			
			break;
		
			case "checklist":
				for (key in param['options']) { 
					output += '<span class="green_options_listitem'
						+ (green_in_list(param['value'], key, GREEN_GLOBALS['shortcodes_delimiter']) ? ' green_options_state_checked' : '') + '"'
						+ ' data-value="' + green_shortcodes_prepare_value(key) + '"'
						+ '>'
						+ param['options'][key]
						+ '</span>';
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />';
			break;
		
			case 'fonts':
				for (key in param['options']) {
					param['options'][key] = key;
				}
			case 'list':
			case 'select':
				if (!green_isset(param['options']) && !green_empty(param['from']) && !green_empty(param['to'])) {
					param['options'] = [];
					for (i = param['from']; i <= param['to']; i+=(!green_empty(param['step']) ? param['step'] : 1)) {
						param['options'][i] = i;
					}
				}
				rez = green_shortcodes_menu_list(param);
				if (green_empty(param['style']) || param['style']=='select') {
					output += '<input class="green_options_input green_options_input_select" type="text" value="' + green_shortcodes_prepare_value(rez[1]) + '"'
						+ ' readonly="readonly"'
						//+ (!green_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
						+ ' />'
						+ '<span class="green_options_field_after green_options_with_action iconadmin-down-open" onchange="green_options_action_show_menu(this);return false;"></span>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
						+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'images':
				rez = green_shortcodes_menu_list(param);
				if (green_empty(param['style']) || param['style']=='select') {
					output += '<div class="green_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
						+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case 'icons':
				rez = green_shortcodes_menu_list(param);
				if (green_empty(param['style']) || param['style']=='select') {
					output += '<div class="green_options_caption_icon iconadmin-down-open"><span class="' + rez[1] + '"></span></div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
						+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'socials':
				if (!green_is_object(param['value'])) param['value'] = {'url': '', 'icon': ''};
				rez = green_shortcodes_menu_list(param);
				if (green_empty(param['style']) || param['style']=='icons') {
					rez2 = green_shortcodes_action_button({
						'action': green_empty(param['style']) || param['style']=='icons' ? 'select_icon' : '',
						'icon': (green_empty(param['style']) || param['style']=='icons') && !green_empty(param['value']['icon']) ? param['value']['icon'] : 'iconadmin-users'
						}, 'after');
				} else
					rez2 = ['', ''];
				output += '<input class="green_options_input green_options_input_text green_options_input_socials' 
					+ (!green_empty(param['mask']) ? ' green_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text" value="' + green_shortcodes_prepare_value(param['value']['url']) + '"' 
					+ (!green_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ rez2[0];
				if (!green_empty(param['style']) && param['style']=='images') {
					output += '<div class="green_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '_icon' + '" type="hidden" value="' + green_shortcodes_prepare_value(param['value']['icon']) + '" />';
			break;

			case "color":
				output += '<input class="green_options_input green_options_input_color'+(GREEN_GLOBALS['shortcodes_cp']=='internal' || (green_isset(param['style']) && param['style']=='custom') ? ' green_options_input_color_custom' : '')+'"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + green_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + green_shortcodes_prepare_value(param_num) + '"'
					+ (!green_empty(param['action']) ? ' onchange="green_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ (GREEN_GLOBALS['shortcodes_cp']=='internal' || (green_isset(param['style']) && param['style']=='custom') ? '<span class="iColorPicker"></span>' : '');
			break;   
	
			}

			if (param['type'] != 'hidden') {
				output += '</div>';
				if (!green_empty(param['desc']))
					output += '<div class="green_options_desc">' + param['desc'] + '</div>' + "\n";
				output += '</div>' + "\n";
			}

		}

		output += '</div>';
	}

	
	return output;
}



// Return menu items list (menu, images or icons)
function green_shortcodes_menu_list(field) {
	"use strict";
	if (field['type'] == 'socials') field['value'] = field['value']['icon'];
	var list = '<div class="green_options_input_menu ' + (green_empty(field['style']) ? '' : ' green_options_input_menu_' + field['style']) + '">';
	var caption = '';
	for (var key in field['options']) {
		var value = field['options'][key];
		if (green_in_array(field['type'], ['list', 'icons', 'socials'])) key = value;
		var selected = '';
		if (green_in_list(field['value'], key, GREEN_GLOBALS['shortcodes_delimiter'])) {
			caption = value;
			selected = ' green_options_state_checked';
		}
		list += '<span class="green_options_menuitem' 
			+ selected 
			+ '" data-value="' + green_shortcodes_prepare_value(key) + '"'
			+ '>';
		if (green_in_array(field['type'], ['list', 'select', 'fonts']))
			list += value;
		else if (field['type'] == 'icons' || (field['type'] == 'socials' && field['style'] == 'icons'))
			list += '<span class="' + value + '"></span>';
		else if (field['type'] == 'images' || (field['type'] == 'socials' && field['style'] == 'images'))
			//list += '<img src="' + value + '" data-icon="' + key + '" alt="" class="green_options_input_image" />';
			list += '<span style="background-image:url(' + value + ')" data-src="' + value + '" data-icon="' + key + '" class="green_options_input_image"></span>';
		list += '</span>';
	}
	list += '</div>';
	return [list, caption];
}



// Return action button
function green_shortcodes_action_button(data, type) {
	"use strict";
	var class_name = ' green_options_button_' + type + (green_empty(data['title']) ? ' green_options_button_'+type+'_small' : '');
	var output = '<span class="' 
				+ (type == 'button' ? 'green_options_input_button'  : 'green_options_field_'+type)
				+ (!green_empty(data['action']) ? ' green_options_with_action' : '')
				+ (!green_empty(data['icon']) ? ' '+data['icon'] : '')
				+ '"'
				+ (!green_empty(data['icon']) && !green_empty(data['title']) ? ' title="'+green_shortcodes_prepare_value(data['title'])+'"' : '')
				+ (!green_empty(data['action']) ? ' onclick="green_options_action_'+data['action']+'(this);return false;"' : '')
				+ (!green_empty(data['type']) ? ' data-type="'+data['type']+'"' : '')
				+ (!green_empty(data['multiple']) ? ' data-multiple="'+data['multiple']+'"' : '')
				+ (!green_empty(data['linked_field']) ? ' data-linked-field="'+data['linked_field']+'"' : '')
				+ (!green_empty(data['captions']) && !green_empty(data['captions']['choose']) ? ' data-caption-choose="'+green_shortcodes_prepare_value(data['captions']['choose'])+'"' : '')
				+ (!green_empty(data['captions']) && !green_empty(data['captions']['update']) ? ' data-caption-update="'+green_shortcodes_prepare_value(data['captions']['update'])+'"' : '')
				+ '>'
				+ (type == 'button' || (green_empty(data['icon']) && !green_empty(data['title'])) ? data['title'] : '')
				+ '</span>';
	return [output, class_name];
}

// Prepare string to insert as parameter's value
function green_shortcodes_prepare_value(val) {
	return typeof val == 'string' ? val.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;') : val;
}
