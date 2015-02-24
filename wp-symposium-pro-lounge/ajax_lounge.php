<?php
// AJAX functions for lounge
add_action( 'wp_ajax_wps_lounge_get_chats', 'wps_lounge_get_chats' ); 

/* GET CHATS */
function wps_lounge_get_chats() {

    // Pending post to insert?
    $last_chat = '';
    if ($_POST['new_chat'] != ''):
    
        global $current_user, $wpdb;
    
        // Get previous post by this user to avoid duplicate posts
        $sql = "SELECT post_title from ".$wpdb->prefix."posts WHERE post_type = 'wps_lounge' AND post_author = %d ORDER BY ID DESC LIMIT 0,1";
        $last_chat = $wpdb->get_var($wpdb->prepare($sql, $current_user->ID));
        if ($last_chat):
            $last_chat_content = $last_chat;
        else:
            $last_chat_content = '';
        endif;
    
        $content = $_POST['new_chat'];
        if ($content != $last_chat_content):
            $post = array(
              'post_content'   => '',
              'post_title'     => $content,
              'post_status'    => 'publish',
              'post_type'      => 'wps_lounge',
              'ping_status'    => 'closed',
              'comment_status' => 'open'
            );  
            $new_chat_id = wp_insert_post( $post );	
        endif;

    endif;
    
    // Get chats
	$chats = get_posts(array(
            'posts_per_page' => $_POST['count'],
            'post_type' => 'wps_lounge',
            'post_status' => 'publish',
    ));
    
	// Prepare to return comments in JSON format
	$return_arr = array();
	
	// Loop through comments, adding to array if any exist
	if ($chats) {
		foreach ($chats as $chat) {

			$row_array['lid'] = $chat->ID;
			$row_array['post_title'] = $chat->post_title;
            $row_array['post_title_html'] = $post_words = wps_bbcode_replace(convert_smilies(wps_make_clickable(wpautop(esc_html($chat->post_title)))));;
			$row_array['post_author'] = $chat->post_author;
			$row_array['added'] = sprintf($_POST['format'], wps_display_name(array('user_id'=>$chat->post_author, 'link'=>1)), human_time_diff(strtotime($chat->post_date_gmt), current_time('timestamp', 1)));

			array_push($return_arr, $row_array);
		}	
	} 
	
	
	echo json_encode($return_arr);

    
	exit;

}


?>