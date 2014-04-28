jQuery(document).ready(function() {

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
