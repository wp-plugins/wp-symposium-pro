<?php
// Hook into core get users AJAX function
add_action( 'wp_ajax_wps_get_users', 'wps_get_users_ajax' ); 

// AJAX functions for activity
add_action( 'wp_ajax_wps_activity_comment_add', 'wps_activity_comment_add' ); 
add_action( 'wp_ajax_wps_activity_settings_delete', 'wps_activity_settings_delete' ); 
add_action( 'wp_ajax_wps_activity_settings_sticky', 'wps_activity_settings_sticky' ); 
add_action( 'wp_ajax_wps_activity_settings_unsticky', 'wps_activity_settings_unsticky' ); 
add_action( 'wp_ajax_wps_comment_settings_delete', 'wps_comment_settings_delete' ); 

/* MAKE POST STICKY */
function wps_activity_settings_sticky() {

	if (update_post_meta( $_POST['post_id'], 'wps_sticky', true )) {
		echo $_POST['post_id'];
	} else {
		echo 0;
	}

}

/* MAKE POST UNSTICKY */
function wps_activity_settings_unsticky() {

	if (delete_post_meta( $_POST['post_id'], 'wps_sticky' )) {
		echo $_POST['post_id'];
	} else {
		echo 0;
	}

}

/* ADD COMMENT */
function wps_activity_comment_add() {

	global $current_user;
	$data = array(
	    'comment_post_ID' => $_POST['post_id'],
	    'comment_content' => $_POST['comment_content'],
	    'comment_type' => '',
	    'comment_parent' => 0,
	    'comment_author' => $current_user->user_login,
	    'user_id' => $current_user->ID,
	    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
	    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
	    'comment_approved' => 1,
	);

	$new_id = wp_insert_comment($data);

	if ($new_id):

		// Any further actions?
		do_action( 'wps_activity_comment_add_hook', $_POST, $new_id );
		echo $new_id;
		
	else:
		echo 0;
	endif;

}

/* DELETE POST */
function wps_activity_settings_delete() {

	$id = $_POST['id'];
	if ($id):
		global $current_user;
		$post = get_post($id);
		if ($post->post_author == $current_user->ID || current_user_can('manage_options')):
			if (wp_delete_post($id, true)):
				echo 'success';
			else:
				echo 'failed to delete post '.$id;
			endif;
		else:
			echo 'not owner';
		endif;
	endif;

}

/* DELETE COMMENT */
function wps_comment_settings_delete() {

	$id = $_POST['id'];
	if ($id):
		global $current_user;
		$comment = get_comment($id);
		if ($comment->user_id == $current_user->ID || current_user_can('manage_options')):
			if (wp_delete_comment($id, true)):
				echo 'success';
			else:
				echo 'failed to delete comment '.$id;
			endif;
		else:
			echo 'not owner';
		endif;
	endif;

}

?>
