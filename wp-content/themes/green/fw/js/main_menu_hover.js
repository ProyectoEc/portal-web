jQuery( document ).ready(function() {

	jQuery( "#menu_main li a" ).each(function() {
	    var arr = jQuery(this).text();

	    jQuery(this).attr('data-text', arr );

	    jQuery(this).html('');


		var v = '<span>' + arr + '</span>';

		jQuery(this).append(v) ;
	});

});

