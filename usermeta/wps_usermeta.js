jQuery(document).ready(function() {

    // Join site (Multisite only)
	jQuery("#wps_join_site").click(function (event) {
        jQuery.post(
            wps_usermeta.ajaxurl,
            {
                action : 'wps_add_to_site',
            },
            function(response) {
                window.location.href=(response);
            }   
        );       
    });

    // ... click required fields message when one is focussed on
    jQuery(".wps_mandatory_field").click(function (event) {
        jQuery('#wps_required_msg').slideUp('slow');
    });

	jQuery( "#wps_usermeta_change" ).submit(function( event ) {

        // ... first change for mandatory fields
        var all_filled = true;
        // ... but first clear any previous highlights
        // ... and then add if necessary
        jQuery('.wps_mandatory_field').each(function(i, obj) {
            if (jQuery(this).val().trim() == '') {
                if (jQuery('#s2id_'+jQuery(this).attr('id')).length > 0) {
                    jQuery('#s2id_'+jQuery(this).attr('id')).addClass('wps_field_error');
                } else {
                    jQuery(this).addClass('wps_field_error');
                    jQuery(this).val(''); // in case spaces entered, remove them
                }
                all_filled = false;
            } else {
                if (jQuery('#s2id_'+jQuery(this).attr('id')).length > 0) {
                    jQuery('#s2id_'+jQuery(this).attr('id')).removeClass('wps_field_error');
                } else {
                    jQuery(this).removeClass('wps_field_error');
                }
            }
        });

        if (all_filled) {

            // ... check passwords match (if entered)
    	  	if (jQuery('#wpspro_password').length) {
    			if (jQuery('#wpspro_password').val() != jQuery('#wpspro_password2').val()) {
    				jQuery('#wpspro_password').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');				
    				jQuery('#wpspro_password2').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');				
    				event.preventDefault();
    			}
    		}

        } else {

            jQuery('#wps_required_msg').slideDown('fast');
            event.preventDefault();

        }

	});

	// wps_user_button

	jQuery(".wps_user_button").click(function (event) {

		var url = jQuery(this).attr('rel');		
		event.preventDefault();

		window.location = url;

	});
    
    // wps_close_account
    
    jQuery('#wps_close_account').click(function (event) {
       
        var answer = confirm(jQuery(this).data('sure'));
        if (answer) {
            jQuery.post(
                wps_usermeta.ajaxurl,
                {
                    action : 'wps_deactivate_account',
                    user_id: jQuery(this).data('user'),
                },
                function(response) {
                    alert(jQuery('#wps_close_account').data('logout'));
                    var url = jQuery('#wps_close_account').data('url');
                    if (url) {
                        window.location = url;
                    } else {
                        location.reload();
                    }
                }   
            );
        }
    });

    // Edit Profile Page Tabs
    jQuery('.wps-tabs .wps-tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
         
        // Show/Hide Tabs
        if (wps_usermeta.animation == 'fade')
            jQuery('.wps-tabs ' + currentAttrValue).fadeIn(800).siblings().hide();
        if (wps_usermeta.animation == 'slide')
            jQuery('.wps-tabs ' + currentAttrValue).slideDown(800).siblings().slideUp(800);
        if (wps_usermeta.animation == 'none')
            jQuery('.wps-tabs ' + currentAttrValue).show().siblings().hide();
 
        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });

})
