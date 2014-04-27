<?php

global $post, $current_user;

require_once('wps_forum_edit.php');

// Edit?
if ( (isset($_GET['forum_action']) && $_GET['forum_action'] == 'edit') ):

	if ( isset($_GET['post_id']) ):
		$html = wps_post_edit($_GET['post_id'], $atts);
	else:
		$html = wps_comment_edit($_GET['comment_id'], $atts);
	endif;

else:

	if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_forum_post_edit') ) wps_save_post($_POST, $_FILES);
	if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_forum_comment_edit') ) wps_save_comment($_POST, $_FILES);

	$post_slug = get_query_var('topic');

	$loop = new WP_Query( array(
		'post_type' => 'wps_forum_post',
		'name' => $post_slug,
		'post_status' => 'publish',	
		'posts_per_page' => 1		
	) );

	if ($loop->have_posts()):
		while ( $loop->have_posts() ) : $loop->the_post();

			$count = wp_count_comments($post->ID);
			$num_comments = get_comments_number();
			if ( $num_comments == 0 ) {
				$comments_count = __('No replies');
			} elseif ( $num_comments > 1 ) {
				$comments_count = sprintf(__('%d replies', WPS2_TEXT_DOMAIN), $num_comments);
			} else {
				$comments_count = __('1 reply', WPS2_TEXT_DOMAIN);
			}

			// Original Post

			$post_html = '';
			$author = get_user_by('id', $post->post_author);
			$post_html .= '<h2 class="wps_forum_post_title">';
			$post_title = $post->post_title;			
			$post_html .= $post_title.' ('.$comments_count.')</h2>';

			$post_html = apply_filters( 'wps_forum_post_post_title_filter', $post_html, $post, $atts, $current_user->ID );

			$post_html .= '<div class="wps_forum_post_comment" style="padding-left: '.($size).'px;">';

				$post_html .= '<div class="wps_forum_post_comment_author" style="max-width: '.($size).'px; margin-left: -'.($size).'px;">';
					$post_html .= '<div class="wps_forum_post_comment_author_avatar">';
						$post_html .= user_avatar_get_avatar( $author->ID, $size );
					$post_html .= '</div>';
					$post_html .= '<div class="wps_forum_post_comment_author_display_name">';
						$post_html .= wps_display_name(array('user_id'=>$author->ID, 'link'=>1));
					$post_html .= '</div>';
					$post_html .= '<div class="wps_forum_post_comment_author_freshness">';
						$post_html .= sprintf(__('%s ago', WPS2_TEXT_DOMAIN), human_time_diff(strtotime($post->post_date_gmt), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
					$post_html .= '</div>';
				$post_html .= '</div>';

				$post_html .= '<div class="wps_forum_post_comment_content">';

					// Settings
					$age = time() - strtotime($post->post_date);
					if ( ($post->post_author == $current_user->ID && $age < $timeout) || current_user_can('manage_options')):
						$post_html .= '<div class="wps_forum_settings">';
							$post_html .= '<img src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
						$post_html .= '</div>';
						$post_html .= '<div class="wps_forum_settings_options">';
							$url = wps_curPageURL();
							$post_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=edit&post_id='.$post->ID.'">'.__('Edit this post', WPS2_TEXT_DOMAIN).'</a>';
							if (current_user_can('manage_options') && ($timeout-$age >= 0)) $post_html .= ' ('.($timeout-$age).')';
						$post_html .= '</div>';						
					endif;

					$post_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($post->post_content)))));

					// Filter for handling anything else
					// Passes $post_html, shortcodes options ($atts), mail post ($post), message ($post->post_content))
					$post_html = apply_filters( 'wps_forum_item_filter', $post_html, $atts, $post, $post->post_content );

				$post_html .= '</div>';

			$post_html .= '</div>';

			// Published comments

			$args = array(
				'status' => 1,
				'orderby' => 'comment_date',
				'order' => 'ASC',
				'post_id' => $post->ID,
			);

			$comments = get_comments($args);
			if ($comments):

				$post_html .= '<div id="wps_forum_post_comments">';

					foreach($comments as $comment) :

						$comment_html = '';

						$comment_html .= '<div class="wps_forum_post_comment" style="padding-left: '.($size).'px;">';

							$comment_html .= '<div class="wps_forum_post_comment_author" style="max-width: '.($size).'px; margin-left: -'.($size).'px;">';
								if ($comment->user_id):
									$comment_html .= '<div class="wps_forum_post_comment_author_avatar">';
										$comment_html .= user_avatar_get_avatar( $comment->user_id, $size );
									$comment_html .= '</div>';
									$comment_html .= '<div class="wps_forum_post_comment_author_display_name">';
										$comment_html .= wps_display_name(array('user_id'=>$comment->user_id, 'link'=>1));
									$comment_html .= '</div>';
								else:
									$comment_html .= '<div style="width:'.$size.'px; height:0"></div>';
								endif;
								$comment_html .= '<div class="wps_forum_post_comment_author_freshness">';
									$comment_html .= sprintf(__('%s ago', WPS2_TEXT_DOMAIN), human_time_diff(strtotime($comment->comment_date_gmt), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
								$comment_html .= '</div>';
							$comment_html .= '</div>';

							$comment_html .= '<div class="wps_forum_post_comment_content">';

								// Settings
								$age = time() - strtotime($comment->comment_date);
								if ( ($comment->comment_author == $current_user->ID && $age < $timeout) || current_user_can('manage_options')):
									$comment_html .= '<div class="wps_forum_settings">';
										$comment_html .= '<img src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
									$comment_html .= '</div>';
									$comment_html .= '<div class="wps_forum_settings_options">';
										$url = wps_curPageURL();
										$comment_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=edit&comment_id='.$comment->comment_ID.'">'.__('Edit this comment', WPS2_TEXT_DOMAIN).'</a>';
									$comment_html .= '</div>';						
								endif;

								$comment_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));

								// Filter for handling anything else
								// Passes $comment_html, shortcodes options ($atts), mail comment ($comment), message ($comment->comment_content))
								$comment_html = apply_filters( 'wps_forum_item_comment_filter', $comment_html, $atts, $comment, $comment->comment_content );

							$comment_html .= '</div>';

						$comment_html .= '</div>';

						$comment_html = apply_filters( 'wps_forum_post_comment_filter', $comment_html, $comment, $atts, $current_user->ID );

						$post_html .= $comment_html;

					endforeach;

				$post_html .= '</div>';

			endif;



			// Pending comments
			$args = array(
				'status' => 0,
				'orderby' => 'comment_date',
				'order' => 'ASC',
				'post_id' => $post->ID,
			);

			$comments = get_comments($args);

			if ($comments):

				$post_html .= '<div class="wps_forum_post_comments">';

					foreach($comments as $comment) :

						if (current_user_can('edit_posts') || $comment->user_id = $current_user->ID):

							$comment_html = '';

							$comment_html .= '<div class="wps_forum_post_comment_pending" style="padding-left: '.($size).'px;">';

								$comment_html .= '<div class="wps_forum_post_comment_author" style="margin-left: -'.($size).'px;">';
									$comment_html .= '<div class="wps_forum_post_comment_author_avatar">';
										$comment_html .= user_avatar_get_avatar( $comment->user_id, $size );
									$comment_html .= '</div>';
									$comment_html .= '<div class="wps_forum_post_comment_author_display_name">';
										$comment_html .= wps_display_name(array('user_id'=>$comment->user_id, 'link'=>1));
									$comment_html .= '</div>';
									$comment_html .= '<div class="wps_forum_post_comment_author_freshness">';
										$comment_html .= sprintf(__('%s ago', WPS2_TEXT_DOMAIN), human_time_diff(strtotime($comment->comment_date), current_time('timestamp')), WPS2_TEXT_DOMAIN);
									$comment_html .= '</div>';
								$comment_html .= '</div>';

								$comment_html .= '<div class="wps_forum_post_comment_content">';
									if ($comment->comment_approved != 'publish') $post_html .= '<div class="wps_forum_post_comment_pending">'.$comment_pending.'</div>';

									$comment_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));

								$comment_html .= '</div>';

							$comment_html .= '</div>';

							$comment_html = apply_filters( 'wps_forum_post_comment_pending_filter', $comment_html, $comment, $atts, $current_user->ID );							

							$post_html .= $comment_html;

						endif;

					endforeach;

				$post_html .= '</div>';

			endif;

			$html .= $post_html;

		endwhile;
		wp_reset_query();

	else:

		$html .= 'Ooops ('.$slug.')';

	endif;

endif;





?>