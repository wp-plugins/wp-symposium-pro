<?php
// AJAX functions for activity
add_action( 'wp_ajax_wps_forum_comment_add', 'wps_forum_comment_add' ); 
add_action( 'wp_ajax_wps_forum_comment_reopen', 'wps_forum_comment_reopen' ); 

/* REOPEN COMMENT */
function wps_forum_comment_reopen() {

	global $current_user;
	$the_post = $_POST;

	$my_post = array(
	      'ID'           	=> $the_post['post_id'],
	      'comment_status' 	=> 'open',
	);
	wp_update_post( $my_post );

	// Add re-opened flag/datetime
	update_post_meta($the_post['post_id'], 'wps_reopened_date', date('Y-m-d H:i:s'));

	// Any further actions?
	do_action( 'wps_forum_post_reopen_hook', $the_post, $_FILES, $the_post['post_id'] );

}


?>
