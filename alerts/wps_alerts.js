jQuery(document).ready(function() {

	if (jQuery("#wps_alerts_activity").length) {
		jQuery("#wps_alerts_activity").select2({
			minimumInputLength: -1,
			dropdownCssClass: 'wps_alerts_activity',
		});
	};

	jQuery('#wps_alerts_activity').on("change", function(e) { 

		jQuery("body").addClass("wps_wait_loading");

		var alert_id = jQuery(this).val();
		var selected = jQuery(this).find('option:selected');
		var url = selected.data('url');

		if (url == 'make_all_read') {

			jQuery.post(
			    wps_alerts.ajaxurl,
			    {
			        action : 'wps_alerts_make_all_read',
			        alert_id : alert_id,
			        url : url
			    },
			    function(response) {
					jQuery(".wps_alerts_unread").removeClass("wps_alerts_unread");
					jQuery("#wps_alerts_activity option[value='count']").remove();
					jQuery("body").removeClass("wps_wait_loading");
			    }   
			);

		} else {

			jQuery.post(
			    wps_alerts.ajaxurl,
			    {
			        action : 'wps_alerts_activity_redirect',
			        alert_id : alert_id,
			        url : url
			    },
			    function(response) {
					window.location.assign(response);
			    }   
			);
		}

	});	

	// ***** Users for custom post *****	
	if (jQuery("#wps_alert_recipient").length) {

		if (jQuery("#wps_alert_recipient").val() == '') {
			jQuery("#wps_alert_recipient").select2({
			    minimumInputLength: 1,
			    query: function (query) {
					jQuery.post(
					    wps_alerts.ajaxurl,
					    {
					        action : 'wps_get_users',
					        term : query.term
					    },
					    function(response) {
					    	var json = jQuery.parseJSON(response);
					    	var data = {results: []}, i, j, s;
							for(var i = 0; i < json.length; i++) {
						    	var obj = json[i];
						    	data.results.push({id: obj.value, text: obj.label});
							}
							query.callback(data);	    	
					    }   
					);
			    }
			});
		}	

	}

	// Clear all sent alerts
	jQuery("#wps_alerts_clear_sent").click(function (event) {
		
	});	

})
