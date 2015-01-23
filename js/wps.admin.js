jQuery(document).ready(function() {
    
    jQuery('#wps_upload_image_button').click(function() {
        formfield = jQuery('#wps_upload_image').attr('name');
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        return false;
    });

    window.send_to_editor = function(html) {
        var imgurl = jQuery('img', html).attr('src');
        if (typeof value === "undefined") imgurl = jQuery(html).attr('src');
        jQuery('#wps_upload_image').val(imgurl);
        tb_remove();
    }

	// Remember which admin section to show after saving
	jQuery('#wps_setup_submit').click(function () {
	    jQuery('#wps_setup').submit(function () {

	    	var wps_expand = '';
			jQuery('.wps_admin_getting_started_content').each(function(i, obj) {
			    if (jQuery(this).css('display') != 'none') {
			    	wps_expand = jQuery(this).attr('id');
			    }
			});

			var input = jQuery("<input>")
			               .attr("type", "hidden")
			               .attr("name", "wps_expand").val(wps_expand);

			jQuery('#wps_setup').append(jQuery(input));

	    });
	});

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
			jQuery(".wps_admin_getting_started_content").slideUp('slow');		
			jQuery('#'+t.attr('rel')).slideDown('slow');
		} else {
			jQuery('#'+t.attr('rel')).slideUp('slow');
		}
	});

});
