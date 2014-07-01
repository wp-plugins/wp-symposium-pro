jQuery(document).ready(function() {

	// ***** Check passwords match on save user meta *****	

	jQuery( "#wps_usermeta_change" ).submit(function( event ) {

	  	if (jQuery('#wpspro_password').length) {
			if (jQuery('#wpspro_password').val() != jQuery('#wpspro_password2').val()) {
				jQuery('#wpspro_password').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');				
				jQuery('#wpspro_password2').css('border', '1px solid red').css('background-color', '#faa').css('color', '#000');				
				event.preventDefault();
			}
		}
	});

	// wps_user_button

	jQuery(".wps_user_button").click(function (event) {

		var url = jQuery(this).attr('rel');		
		event.preventDefault();

		window.location = url;

	});


})
