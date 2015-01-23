jQuery(document).ready(function() {

	if (jQuery("#wps_lounge").length) {

        // Repeat check
        var refresh = jQuery('#wps_lounge').data('refresh') * 1000;
        jQuery('#wps_lounge').html('<img src="'+wps_lounge_ajax.wait+'" />');
        lounge_polling(); 
		setInterval( function() { 
            lounge_polling(); 
        }, refresh); 

	}

    // Enter to submit
	jQuery('#wps_lounge_chat').keyup(function(e) {
        if (jQuery('#wps_lounge_chat_pending_chat').length == 0) {
            if (e.keyCode == 13) { 
                var chat = jQuery('#wps_lounge_chat').val();
                chat = chat.replace(/(<([^>]+)>)/ig,"");
                wait = jQuery('#wps_lounge').data('wait');
                wait = wait.replace(/%s/ig,chat);
                jQuery('#wps_lounge_chat').val('');   
                jQuery('#wps_lounge_chat_pending').remove();
                jQuery('#wps_lounge').prepend('<div id="wps_lounge_chat_pending" class="wps_lounge_chat_div">'+wait+'</div>');
                jQuery('#wps_lounge').prepend('<div id="wps_lounge_chat_pending_chat">'+chat+'</div>');
                jQuery('#wps_lounge_chat').hide();
            };
            if (e.keyCode == 27) { 
                jQuery('#wps_lounge_chat').val('');
            };
        }
	});
    
    
	function lounge_polling() {

        if (jQuery('#wps_audit').val() != 'polling via AJAX...') {
            jQuery('#wps_audit').val('polling via AJAX...');
            
            // Add any pending
            var new_chat = '';
            if (jQuery('#wps_lounge_chat_pending_chat').length) {
                new_chat = jQuery('#wps_lounge_chat_pending_chat').html();
            }
            // And get posts
            jQuery.post(
                wps_lounge_ajax.ajaxurl,
                {
                    action: 'wps_lounge_get_chats',
                    count: jQuery('#wps_lounge').data('chats'),
                    format: jQuery('#wps_lounge').data('format'),
                    new_chat: new_chat,
                },
                function(str) {
                    jQuery('#wps_audit').val('retrieved posts');
                    // AJAX function return JSON array of comments to create HTML
                    var items = "";
                    var rows = jQuery.parseJSON(str);

                    if (rows != null) {
                        var row_count = 0;
                        jQuery.each(rows, function(i, row) {
                            items += '<div class="wps_lounge_chat_div">';
                                items += '<div class="wps_lounge_added_by">' + row.added + '</div>';
                                items += '<div class="wps_lounge_chat">' + row.post_title_html + '</div>';
                            items += '</div>';
                            if (row_count == 0 && row.post_author == wps_lounge_ajax.current_user && row.post_title == new_chat) {
                                jQuery('#wps_lounge_chat_pending').remove();
                                jQuery('#wps_lounge_chat_pending_chat').remove()
                                row_count++;
                            }
                        });
                    }
                    if (jQuery('#wps_lounge_chat_pending_chat').length && jQuery('#wps_lounge_chat_pending_chat').html() != '') {
                        var wait = jQuery('#wps_lounge_chat_pending').html();
                        var chat = jQuery('#wps_lounge_chat_pending_chat').html();                
                        jQuery('#wps_lounge').html('<div id="wps_lounge_chat_pending" class="wps_lounge_chat_div">'+wait+'</div>' + '<div id="wps_lounge_chat_pending_chat">'+chat+'</div>' + items);
                        jQuery('#wps_audit').val('still pending...');
                    } else {
                        jQuery('#wps_lounge').html(items);
                        jQuery('#wps_lounge_chat').show();
                        jQuery('#wps_audit').val('up to date');
                    }
                    if (jQuery('#wps_audit').val() == 'polling via AJAX...') {
                        jQuery('#wps_audit').val('clear flag to allow re-poll');
                    }
                    
                }
            );
                    
        }

	}

});
