<?php
function wps_post_delete($post_id, $atts) {

	global $current_user;

	$html = '';

	$the_post = get_post($post_id);

	if ($the_post && $the_post->post_author == $current_user->ID || current_user_can('manage_options')):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'delete_msg' => __('Are you sure you want to delete this post and all the replies?', WPS2_TEXT_DOMAIN),
			'delete_label' => __('Yes, delete the post', WPS2_TEXT_DOMAIN),
			'delete_cancel_label' => __('No', WPS2_TEXT_DOMAIN),
		), $atts, 'wps_forum_post' ) );

		$the_post_content = wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($the_post->post_content)))));
		$html .= '<h2>'.$delete_msg.'</h2>';

		$html .= $the_post_content;

		$url = wps_curPageURL();
		$url = preg_replace("/[&?]forum_action=delete&post_id=[0-9]+/","",$url);

		$html .= '<div id="wps_forum_post_edit_form">';

			$html .= '<form action="'.$url.'" method="POST">';
				$html .= '<input type="hidden" name="action" value="wps_forum_post_delete" />';
				$html .= '<input type="hidden" name="wps_post_id" value="'.$post_id.'" />';
				$html .= '<input id="wps_forum_comment_delete_button" type="submit" class="'.$class.'" value="'.$delete_label.'" />';
			$html .= '</form>';
			$html .= '<form ACTION="'.$url.'" METHOD="POST">';
				$html .= '<input id="wps_forum_comment_cancel_button" type="submit" class="'.$class.'" value="'.$delete_cancel_label.'" />';
			$html .= '</form>';

		$html .= '</div>';

	endif;

	return $html;

}

function wps_comment_delete($comment_id, $atts) {

	global $current_user;

	$html = '';

	$comment = get_comment($comment_id);

	if ($comment && $comment->comment_author == $current_user->user_login || current_user_can('manage_options')):

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'delete_comment_msg' => __('Are you sure you want to delete this comment?', WPS2_TEXT_DOMAIN),
			'delete_comment_label' => __('Yes, delete the comment', WPS2_TEXT_DOMAIN),
			'delete_comment_cancel_label' => __('No', WPS2_TEXT_DOMAIN),
		), $atts, 'wps_forum_post' ) );

		$comment_content = wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));
		$html .= '<h2>'.$delete_comment_msg.'</h2>';

		$html .= $comment_content;

		$url = wps_curPageURL();
		$url = preg_replace("/[&?]forum_action=delete&comment_id=[0-9]+/","",$url);

		$html .= '<div id="wps_forum_post_edit_form">';

			$html .= '<form action="'.$url.'" method="POST">';
				$html .= '<input type="hidden" name="action" value="wps_forum_comment_delete" />';
				$html .= '<input type="hidden" name="wps_comment_id" value="'.$comment_id.'" />';
				$html .= '<input id="wps_forum_comment_delete_button" type="submit" class="'.$class.'" value="'.$delete_comment_label.'" />';
			$html .= '</form>';
			$html .= '<form ACTION="'.$url.'" METHOD="POST">';
				$html .= '<input id="wps_forum_comment_cancel_button" type="submit" class="'.$class.'" value="'.$delete_comment_cancel_label.'" />';
			$html .= '</form>';

		$html .= '</div>';

	endif;

	return $html;

}

function wps_forum_delete_comment($post_data, $files_data) {

	global $current_user;
	
	$comment_id = $post_data['wps_comment_id'];
	if ($comment_id):

		$current_comment = get_comment($comment_id);
		if ( $current_user->user_login == $current_comment->comment_author || current_user_can('manage_options') ):

			wp_delete_comment($comment_id, true);

			// Any further actions?
			do_action( 'wps_forum_comment_delete_hook', $post_data, $files_data, $comment_id );

		endif;

	endif;

}


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
			'can_move_forum' => true,
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
				$form_html = apply_filters( 'wps_forum_post_edit_pre_form_filter', $form_html, $atts, $current_user->ID );
				$form_html .= '<textarea id="wps_forum_post_edit_textarea" name="wps_forum_post_edit_textarea">'.$the_post->post_content.'</textarea>';

				// Get post forum term ID
				$post_terms = get_the_terms( $the_post->ID, 'wps_forum' );
				$the_post_terms = $post_terms[0];
				$post_term_slug = $the_post_terms->slug;
				$post_term_term_id = $the_post_terms->term_id;

				if (current_user_can('manage_options')):

					$terms = get_terms( "wps_forum", array(
					    'hide_empty'    => false, 
					    'fields'        => 'all', 
					    'hierarchical'  => false, 
					) );

					if ($can_move_forum):
				        $form_html .= '<select name="wps_post_forum_slug" style="float:right; width:50%;" id="wps_post_forum_slug">';

							foreach ( $terms as $term ):
					            $selected_as_default = ($post_term_slug == $term->slug) ? ' SELECTED' : '';
					            $form_html .= '<option value="'.$term->slug.'" '.$selected_as_default.'>'.$term->name.'</option>';
						    endforeach;

				        $form_html .= '</select>';
				    else:
						$form_html .= '<input type="hidden" name="wps_post_forum_slug" value="'.$term_id.'" />';
				    endif;

					$form_html .= '<input type="checkbox" name="wps_sticky"';
						if (get_post_meta($post_id, 'wps_sticky', true)) $form_html .= ' CHECKED';
						$form_html .= '> '.__('Stick to top of posts?', WPS2_TEXT_DOMAIN);

				else:

					$form_html .= '<input type="hidden" name="wps_post_forum_slug" value="'.$term_id.'" />';
					$form_html .= '<input type="checkbox" style="display:none" name="wps_sticky"';
						if (get_post_meta($post_id, 'wps_sticky', true)) $form_html .= ' CHECKED';
						$form_html .= '>';
				endif;
				$form_html .= '<input type="hidden" name="wps_post_forum_term_id" value="'.$post_term_term_id.'" />';


				if ($moderate) $form_html .= '<div id="wps_forum_post_edit_moderate">'.$moderate_msg.'</div>';

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

function wps_save_post($post_data, $files_data, $moved_to) {

	global $current_user;
	$return_html = '';
	
	$post_id = $post_data['wps_post_id'];
	if ($post_id):

		$current_post = get_post($post_id);
		if ( $current_user->ID == $current_post->post_author || current_user_can('manage_options') ):

		  	$my_post = array(
		      	'ID'           	=> $post_id,
		      	'post_title' 	=> $post_data['wps_forum_post_edit_title'],
		      	'post_content' 	=> $post_data['wps_forum_post_edit_textarea'],
		  	);
		  	wp_update_post( $my_post );		

		  	// Sticky?
		  	if (isset($_POST['wps_sticky'])):
		  		update_post_meta($post_id, 'wps_sticky', true);
		  	else:
		  		delete_post_meta($post_id, 'wps_sticky', true);
		  	endif;

			// Change forum?
			$current_post_terms = get_the_terms( $post_id, 'wps_forum' );
			$current_post_term = $current_post_terms[0];
			if ($current_post_term->slug != $post_data['wps_post_forum_slug']):

				$the_post = get_post($post_id);
				if (is_multisite()) {

					$blog_details = get_blog_details($blog->blog_id);
					$url = $blog_details->path.$post_data['wps_post_forum_slug'].'/'.$the_post->post_name;
					$forum_url = $blog_details->path.$post_data['wps_post_forum_slug'];


				} else {

					$permalink_structure = get_option( 'permalink_structure' );
					if ($permalink_structure):
						$url = get_bloginfo('url').'/'.$post_data['wps_post_forum_slug'].'/'.$the_post->post_name;
						$forum_url = get_bloginfo('url').'/'.$post_data['wps_post_forum_slug'];
					else:
						$forum_page_id = wps_get_term_meta($post_data['wps_post_forum_term_id'], 'wps_forum_cat_page', true);
						$url = get_bloginfo('url')."/?page_id=".$forum_page_id."&topic=".$the_post->post_name;
						$forum_url = get_bloginfo('url')."/?page_id=".$forum_page_id;
					endif;

				}
				
				$new_term = get_term_by('slug', $post_data['wps_post_forum_slug'], 'wps_forum');
				$return_html = '<div class="wps_success">'.sprintf($moved_to, '<a href="'.$url.'">'.esc_attr($the_post->post_title).'</a>', '<a href="'.$forum_url.'">'.esc_attr($new_term->name).'</a>').'</div>';

			  	// Save post forum (term)
			  	wp_set_object_terms( $post_id, $post_data['wps_post_forum_slug'], 'wps_forum' );

			endif;

			// Any further actions?
			do_action( 'wps_forum_post_edit_hook', $post_data, $files_data, $post_id );

		endif;

	endif;

	return $return_html;

}

function wps_save_comment($post_data, $files_data) {

	global $current_user;
	
	$comment_id = $post_data['wps_comment_id'];
	if ($comment_id):

		$current_comment = get_comment($comment_id);
		if ( $current_user->user_login == $current_comment->comment_author || current_user_can('manage_options') ):

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
