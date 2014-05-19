<?php
while(!is_file('wp-config.php')){
	if(is_dir('../')) chdir('../');
	else die('Could not find WordPress config file.');
}
include_once( 'wp-config.php' );

$action = isset($_POST['action']) ? $_POST['action'] : false;

if ($action) {

	global $current_user;
	get_currentuserinfo();

	if ( is_user_logged_in() ) {

		/* ADD POST */
		if ($action == 'wps_forum_post_add') {

			$the_post = $_POST;
			$status = $the_post['wps_forum_moderate'] == '1' ? 'pending' : 'publish';

			$post = array(
			  'post_title'     => $the_post['wps_forum_post_title'],
			  'post_content'   => $the_post['wps_forum_post_textarea'],
			  'post_status'    => $status,
			  'author'		   => $current_user->ID,
			  'post_type'      => 'wps_forum_post',
			  'post_author'    => $current_user->ID,
			  'ping_status'    => 'closed',
			  'comment_status' => 'open',
			);  
			$new_id = wp_insert_post( $post );

			wp_set_object_terms( $new_id, $the_post['wps_forum_slug'], 'wps_forum' );

			if ($new_id):

				// Any further actions?
				do_action( 'wps_forum_post_add_hook', $the_post, $_FILES, $new_id );

			endif;

		}

		/* ADD COMMENT */
		if ($action == 'wps_forum_comment_add') {

			$the_comment = $_POST;
			$status = $the_comment['wps_forum_moderate'] == '1' ? '0' : '1';

			$data = array(
			    'comment_post_ID' => $the_comment['post_id'],
			    'comment_content' => $the_comment['wps_forum_comment'],
			    'comment_type' => '',
			    'comment_parent' => 0,
			    'comment_author' => $current_user->user_login,
			    'comment_author_email' => $current_user->user_email,
			    'user_id' => $current_user->ID,
			    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
			    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
			    'comment_approved' => $status,
			);

			$new_id = wp_insert_comment($data);

			if ($new_id):

				// Close Post?
				if (isset($_POST['wps_close_post']) && $_POST['wps_close_post'] == 'on'):

					$my_post = array(
					      'ID'           	=> $the_comment['post_id'],
					      'comment_status' 	=> 'closed',
					);
					wp_update_post( $my_post );

				endif;

				// Any further actions?
				do_action( 'wps_forum_comment_add_hook', $the_comment, $_FILES, $new_id );

			endif;

		}
		
	}


}

?>
