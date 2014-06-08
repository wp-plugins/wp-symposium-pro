jQuery(document).ready(function() {

	// Editor button
	jQuery("#wps_admin_shortcodes_button").click(function (event) {		
		jQuery('#wps_admin_shortcodes').toggle();
	});

	// Editor button menu item
	jQuery(".wps_admin_shortcodes_menu").click(function (event) {		
		jQuery('#wps_admin_shortcodes').hide();
	});

	// Show content on menu click
	jQuery(".wps_admin_getting_started_menu_item").click(function (event) {
		// Tidy up
		var t = jQuery(this);
		if (jQuery('#'+t.attr('rel')).css('display') == 'none') {
			jQuery(".wps_admin_getting_started_content").slideUp('fast');		
			jQuery('#'+t.attr('rel')).slideDown('fast');
		} else {
			jQuery('#'+t.attr('rel')).slideUp('fast');
		}
	});

});
