jQuery(document).ready(function() {

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
