<?php

function wps_post_edit($post_id, $atts) {

	global $current_user;

	$html = '';

	$the_post = get_post($post_id);

	if ($current_user->ID == $the_post->post_author || current_user_can('manage_options')):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'title_label' => 'Post title',
			'content_label' => 'Post',
			'cancel_label' => __('Cancel', WPS2_TEXT_DOMAIN),
			'update_label' => __('Update Topic', WPS2_TEXT_DOMAIN),
			'moderate_msg' => __('Your post will appear once it has been moderated.', WPS2_TEXT_DOMAIN),
			'moderate' => false,
			'slug' => '',
			'before' => '',
			'after' => '',
		), $atts, 'wps_forum_post' ) );

		$form_html = '';
		$form_html .= '<div id="wps_forum_post_edit_div">';
			
			$form_html .= '<div id="wps_forum_post_edit_form">';

				$url = wps_curPageURL();
				$url = preg_replace("/[&?]forum_action=edit&post_id=[0-9]+/","",$url);

				$form_html .= '<form ACTION="'.$url.'" METHOD="POST">';
				$form_html .= '<input type="hidden" name="action" value="wps_forum_post_edit" />';
				$form_html .= '<input type="hidden" name="wps_post_id" value="'.$post_id.'" />';
				$form_html .= '<input type="hidden" name="wps_forum_moderate" value="'.$moderate.'" />';

				$form_html .= '<div id="wps_forum_post_title_label">'.$title_label.'</div>';
				$form_html .= '<input type="text" id="wps_forum_post_edit_title" name="wps_forum_post_edit_title" value="'.$the_post->post_title.'" />';

				$form_html .= '<div id="wps_forum_post_content_label">'.$content_label.'</div>';
				$form_html = apply_filters( 'wps_forum_post_pre_form_filter', $form_html, $atts, $current_user->ID );
				$form_html .= '<textarea id="wps_forum_post_edit_textarea" name="wps_forum_post_edit_textarea">'.$the_post->post_content.'</textarea>';

				if ($moderate) $form_html .= '<div id="wps_forum_post_edit_moderate">'.$moderate_msg.'</div>';
				$form_html = apply_filters( 'wps_forum_post_post_form_filter', $form_html, $atts, $current_user->ID );

			$form_html .= '</div>';

			$form_html .= '<input id="wps_forum_post_edit_button" type="submit" class="'.$class.'" value="'.$update_label.'" />';
			$form_html .= '</form>';
			$form_html .= '<form ACTION="'.$url.'" METHOD="POST">';
				$form_html .= '<input id="wps_forum_post_cancel_button" type="submit" class="'.$class.'" value="'.$cancel_label.'" />';
			$form_html .= '</form>';
		
		$form_html .= '</div>';

		$html .= $form_html;

	else:

		$html .= __('Not the post owner', WPS2_TEXT_DOMAIN);

	endif;

	return $html;

}

function wps_comment_edit($comment_id, $atts) {

	global $current_user;

	$html = '';

	$the_comment = get_comment($comment_id);

	if ($current_user->ID == $the_comment->user_id || current_user_can('manage_options')):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'content_label' => '',
			'cancel_label' => __('Cancel', WPS2_TEXT_DOMAIN),
			'update_label' => __('Update Comment', WPS2_TEXT_DOMAIN),
			'moderate' => false,
			'moderate_msg' => __('Your comment will appear once it has been moderated.', WPS2_TEXT_DOMAIN),
			'slug' => '',
			'before' => '',
			'after' => '',
		), $atts, 'wps_forum_comment' ) );

		$form_html = '';
		$form_html .= '<div id="wps_forum_post_edit_div">';
			
			$form_html .= '<div id="wps_forum_post_edit_form">';

				$url = wps_curPageURL();
				$url = preg_replace("/[&?]forum_action=edit&comment_id=[0-9]+/","",$url);

				$form_html .= '<form ACTION="'.$url.'" METHOD="POST">';
				$form_html .= '<input type="hidden" name="action" value="wps_forum_comment_edit" />';
				$form_html .= '<input type="hidden" name="wps_comment_id" value="'.$comment_id.'" />';
				$form_html .= '<input type="hidden" name="wps_forum_moderate" value="'.$moderate.'" />';

				$form_html .= '<div id="wps_forum_comment_content_label">'.$content_label.'</div>';
				$form_html = apply_filters( 'wps_forum_comment_edit_pre_form_filter', $form_html, $atts, $current_user->ID );
				$form_html .= '<textarea id="wps_forum_comment_edit_textarea" name="wps_forum_comment_edit_textarea">'.$the_comment->comment_content.'</textarea>';

				if ($moderate) $form_html .= '<div id="wps_forum_comment_edit_moderate">'.$moderate_msg.'</div>';
				$form_html = apply_filters( 'wps_forum_comment_edit_post_form_filter', $form_html, $atts, $current_user->ID );

			$form_html .= '</div>';

			$form_html .= '<input id="wps_forum_comment_edit_button" type="submit" class="'.$class.'" value="'.$update_label.'" />';
			$form_html .= '</form>';
			$form_html .= '<form ACTION="'.$url.'" METHOD="POST">';
				$form_html .= '<input id="wps_forum_post_cancel_button" type="submit" class="'.$class.'" value="'.$cancel_label.'" />';
			$form_html .= '</form>';
		
		$form_html .= '</div>';

		$html .= $form_html;

	else:

		$html .= __('Not the comment owner', WPS2_TEXT_DOMAIN);

	endif;

	return $html;

}

function wps_save_post($post_data, $files_data) {

	global $current_user;
	
	$post_id = $post_data['wps_post_id'];
	if ($post_id):

		$current_post = get_post($post_id);
		if ( $current_user == $current_post->post_author || current_user_can('manage_options') ):

		  	$my_post = array(
		      	'ID'           	=> $post_id,
		      	'post_title' 	=> $post_data['wps_forum_post_edit_title'],
		      	'post_content' 	=> $post_data['wps_forum_post_edit_textarea'],
		  	);
		  	wp_update_post( $my_post );				

			// Any further actions?
			do_action( 'wps_forum_post_edit_hook', $post_data, $files_data, $post_id );

		endif;

	endif;

}

function wps_save_comment($post_data, $files_data) {

	global $current_user;
	
	$comment_id = $post_data['wps_comment_id'];
	if ($comment_id):

		$current_comment = get_comment($comment_id);
		if ( $current_user == $current_comment->comment_author || current_user_can('manage_options') ):

			$commentarr = array();
			$commentarr['comment_ID'] = $comment_id;
			$commentarr['comment_content'] = $post_data['wps_forum_comment_edit_textarea'];
			wp_update_comment( $commentarr );			

			// Any further actions?
			do_action( 'wps_forum_comment_edit_hook', $post_data, $files_data, $comment_id );

		endif;

	endif;

}

?>
