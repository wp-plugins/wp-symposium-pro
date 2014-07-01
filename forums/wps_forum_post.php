<?php

global $post, $current_user;

require_once('wps_forum_edit.php');

// Clicked on Edit via Settings icon
if ( (isset($_GET['forum_action']) && $_GET['forum_action'] == 'edit') ):

	if ( isset($_GET['post_id']) ):
		$html = wps_post_edit($_GET['post_id'], $atts);
	else:
		$html = wps_comment_edit($_GET['comment_id'], $atts);
	endif;

endif;

// Clicked on Delete via Settings icon
if ( (isset($_GET['forum_action']) && $_GET['forum_action'] == 'delete') ):

	if ( isset($_GET['post_id']) ):
		$html = wps_post_delete($_GET['post_id'], $atts);
	else:
		$html = wps_comment_delete($_GET['comment_id'], $atts);
	endif;

endif;

if (!isset($_GET['forum_action']) || ($_GET['forum_action'] != 'edit' && $_GET['forum_action'] != 'delete')):

	// Saving from edit
	if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_forum_post_edit') ) $html .= wps_save_post($_POST, $_FILES, $moved_to);
	if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_forum_comment_edit') ) $html .= wps_save_comment($_POST, $_FILES);

	// Delete confirmed
	if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_forum_comment_delete') ) wps_forum_delete_comment($_POST, $_FILES);

	$post_slug = get_query_var('topic');

	$loop = new WP_Query( array(
		'post_type' => 'wps_forum_post',
		'name' => $post_slug,
		'post_status' => 'publish',	
		'posts_per_page' => 1		
	) );

	if ($loop->have_posts()):
		while ( $loop->have_posts() ) : $loop->the_post();

			// First check can see this post
			$post_terms = get_the_terms( $post->ID, 'wps_forum' );
			if( $post_terms && !is_wp_error( $post_terms ) ):

				$user_can_see = false;
				foreach( $post_terms as $term ):
					if (user_can_see_forum($current_user->ID, $term->term_id) || current_user_can('manage_options')) $user_can_see = true;
				endforeach;

				if ($user_can_see || current_user_can('manage_options')):

					if (user_can_see_post($current_user->ID, $post->ID)):

						// Add read flag for this user
						$read = get_post_meta( $post->ID, 'wps_forum_read', true );
						if (!$read):
							$read = array();
							$read[] = $current_user->user_login;
						else:
							if (!in_array($current_user->user_login, $read)):
								$read[] = $current_user->user_login;
							endif;
						endif;
						update_post_meta ( $post->ID, 'wps_forum_read', $read);

						// Get count of replies
						$count = wp_count_comments($post->ID);
						$num_comments = get_comments_number();
						if ( $num_comments == 0 ) {
							$comments_count = __('No replies');
						} elseif ( $num_comments > 1 ) {
							$comments_count = sprintf(__('%d replies', WPS2_TEXT_DOMAIN), $num_comments);
						} else {
							$comments_count = __('1 reply', WPS2_TEXT_DOMAIN);
						}

						// Prepare pagination
						$page = isset($_GET['page']) ? $_GET['page'] : 1;
						$page = get_query_var('fpage');
						if ($page):
							$page = explode('-', $page);
							$page = (int)$page[1];
						else:
							$page = 1;
						endif;
						$limit = $page_size;
						$offset = ($page * $limit) - $limit;

						$pages = ceil($num_comments/$limit);
						
						// Original Post

						$post_html = '';
						$author = get_user_by('id', $post->post_author);
						$post_html .= '<h2 class="wps_forum_post_title">';
						$post_title = wps_strip_tags($post->post_title);			
						$post_html .= $post_title;
						if ($show_comments_count) $post_html .= ' ('.$comments_count.')';
						$post_html .= '</h2>';

						$post_html = apply_filters( 'wps_forum_post_post_title_filter', $post_html, $post, $atts, $current_user->ID );

						if ($page == 1):

							// Only show original post on page 1

							$post_html .= '<div class="wps_forum_post_comment" style="padding-left: '.($size).'px;">';

								$post_html .= '<div class="wps_forum_post_comment_author" style="max-width: '.($size).'px; margin-left: -'.($size).'px;">';
									$post_html .= '<div class="wps_forum_post_comment_author_avatar">';
										$post_html .= user_avatar_get_avatar( $author->ID, $size );
									$post_html .= '</div>';
									$post_html .= '<div class="wps_forum_post_comment_author_display_name">';
										$post_html .= wps_display_name(array('user_id'=>$author->ID, 'link'=>1));
									$post_html .= '</div>';
									$post_html .= '<div class="wps_forum_post_comment_author_freshness">';
										$post_html .= sprintf($date_format, human_time_diff(strtotime($post->post_date_gmt), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
									$post_html .= '</div>';
								$post_html .= '</div>';

								$post_html .= '<div class="wps_forum_post_comment_content">';

									// Post Settings
									$age = current_time('timestamp', 1) - strtotime($post->post_date);
									$user_can_edit_forum = $post->post_author == $current_user->ID ? true : false;
									$user_can_edit_forum = apply_filters( 'wps_forum_post_user_can_edit_filter', $user_can_edit_forum, $post, $current_user->ID, $term->term_id );
									$user_can_delete_forum = $post->post_author == $current_user->ID ? true : false;
									$user_can_delete_forum = apply_filters( 'wps_forum_post_user_can_delete_filter', $user_can_edit_forum, $post, $current_user->ID, $term->term_id );

									// Check if timed out
									$timed_out = $age > $timeout ? true : false;
									$timed_out = apply_filters( 'wps_forum_post_timed_out_filter', $timed_out, $current_user->ID, $age, $timeout, $term->term_id );

									if ( ( ($user_can_edit_forum || $user_can_delete_forum) && !$timed_out) || current_user_can('manage_options') ):
										$post_html .= '<div class="wps_forum_settings">';
											$post_html .= '<img style="height:15px;width:15px;" src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
										$post_html .= '</div>';
										$post_html .= '<div class="wps_forum_settings_options">';
											$url = wps_curPageURL();
											if ($user_can_edit_forum || current_user_can('manage_options')) $post_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=edit&post_id='.$post->ID.'">'.__('Edit', WPS2_TEXT_DOMAIN).'</a>';
											if (($user_can_edit_forum || current_user_can('manage_options')) && $timeout-$age >= 0) $post_html .= '<br />('.sprintf(__('lock in %d seconds', WPS2_TEXT_DOMAIN), ($timeout-$age)).')';
											if (($user_can_edit_forum && $user_can_delete_forum) || current_user_can('manage_options')) $post_html .= ' | ';
											if ($user_can_delete_forum || current_user_can('manage_options')) $post_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=delete&post_id='.$post->ID.'">'.__('Delete', WPS2_TEXT_DOMAIN).'</a>';
										$post_html .= '</div>';						
									endif;

									$post_content = wps_strip_tags($post->post_content);
									$post_content = wps_bbcode_replace(convert_smilies(make_clickable(wpautop($post_content))));
									$post_content = apply_filters( 'wps_forum_item_content_filter', $post_content, $atts );
									$post_html .= $post_content;

									// Filter for handling anything else
									// Passes $post_html, shortcodes options ($atts), mail post ($post), message ($post->post_content))
									$post_html = apply_filters( 'wps_forum_item_filter', $post_html, $atts, $post, $post->post_content );

								$post_html .= '</div>';

							$post_html .= '</div>';

						else:

							// If page > 1, show "page" subtitle
							if ($page_x_of_y) $post_html .= '<h3>'.sprintf($page_x_of_y, $page, $pages).'</h3>';

						endif;

						if ($pages > 1 && $pagination && $pagination_top):
							$post_html .= '<div id="wps_forum_pagination_top">';
								$post_html .= wps_insert_pagination($page, $pages, $pagination_previous, $pagination_next, get_bloginfo('url').'/'.$term->slug.'/'.$post_slug.'/page-%d');
							$post_html .= '</div>';
						endif;

						// Published comments

						$args = array(
							'status' => 1,
							'orderby' => 'comment_date',
							'order' => 'ASC',
							'post_id' => $post->ID,
							'offset' => $offset,
							'number' => $limit,
						);

						$comments = get_comments($args);
						if ($comments):

							// Get comment's post forum term ID
							$first_comment = $comments[0];
							$the_post = get_post( $first_comment->comment_post_ID );
							$post_terms = get_the_terms( $the_post->ID, 'wps_forum' );
							foreach( $post_terms as $term ):
								$post_term_term_id = $term->term_id;
							endforeach;

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
												$comment_html .= sprintf($date_format, human_time_diff(strtotime($comment->comment_date_gmt), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
											$comment_html .= '</div>';
										$comment_html .= '</div>';

										$comment_html .= '<div class="wps_forum_post_comment_content">';

											$user_can_edit_comment = $comment->user_id == $current_user->ID ? true : false;
											$user_can_edit_comment = apply_filters( 'wps_forum_post_user_can_edit_comment_filter', $user_can_edit_comment, $comment, $current_user->ID, $post_term_term_id );
											$user_can_delete_comment = $comment->user_id == $current_user->ID ? true : false;
											$user_can_delete_comment = apply_filters( 'wps_forum_post_user_can_delete_comment_filter', $user_can_delete_comment, $comment, $current_user->ID, $post_term_term_id );

											// Check if timed out
											$age = current_time('timestamp', 1) - strtotime($comment->comment_date);
											$timed_out = $age > $timeout ? true : false;
											$timed_out = apply_filters( 'wps_forum_post_timed_out_filter', $timed_out, $current_user->ID, $age, $timeout, $term->term_id );

											// Comment Settings
											if ( (($user_can_edit_comment || $user_can_delete_comment) && !$timed_out) || current_user_can('manage_options')):
												$comment_html .= '<div class="wps_forum_settings">';
													$comment_html .= '<img style="height:15px;width:15px;" src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
												$comment_html .= '</div>';
												$comment_html .= '<div class="wps_forum_settings_options">';
													$url = wps_curPageURL();
													if ($user_can_edit_comment || current_user_can('manage_options')) $comment_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=edit&comment_id='.$comment->comment_ID.'">'.__('Edit', WPS2_TEXT_DOMAIN).'</a>';
													if (($user_can_edit_comment || current_user_can('manage_options')) && $timeout-$age >= 0) $comment_html .= '<br />('.sprintf(__('lock in %d seconds', WPS2_TEXT_DOMAIN), ($timeout-$age)).')';
													if (($user_can_edit_comment && $user_can_delete_comment) || current_user_can('manage_options')) $comment_html .= ' | ';
													if ($user_can_delete_comment || current_user_can('manage_options')) $comment_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=delete&comment_id='.$comment->comment_ID.'">'.__('Delete', WPS2_TEXT_DOMAIN).'</a>';
												$comment_html .= '</div>';						
											endif;

											$comment_content = wps_strip_tags($comment->comment_content);
											$comment_content = wps_bbcode_replace(convert_smilies(make_clickable(wpautop($comment_content))));
											$comment_content = apply_filters( 'wps_forum_item_content_filter', $comment_content, $atts );
											$comment_html .= $comment_content;

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
													$comment_html .= sprintf($date_format, human_time_diff(strtotime($comment->comment_date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
												$comment_html .= '</div>';
											$comment_html .= '</div>';

											$comment_html .= '<div class="wps_forum_post_comment_content">';
												if ($comment->comment_approved != 'publish') $post_html .= '<div class="wps_forum_post_comment_pending">'.$comment_pending.'</div>';

												$comment_content_html = wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));
												$comment_content_html = apply_filters( 'wps_forum_item_content_filter', $comment->comment_content, $atts );
												$comment_html .= $comment_content_html;

											$comment_html .= '</div>';

										$comment_html .= '</div>';

										$comment_html = apply_filters( 'wps_forum_post_comment_pending_filter', $comment_html, $comment, $atts, $current_user->ID );							

										$post_html .= $comment_html;

									endif;

								endforeach;

							$post_html .= '</div>';

						endif;

						if ($pages > 1 && $pagination && $pagination_bottom):
							$post_html .= '<div id="wps_forum_pagination_bottom">';
								$post_html .= wps_insert_pagination($page, $pages, $pagination_previous, $pagination_next, get_bloginfo('url').'/'.$term->slug.'/'.$post_slug.'/page-%d');
							$post_html .= '</div>';
						endif;


						$html .= $post_html;

					else:

						$html .= $secure_post_msg;

					endif;

				else:

					$html .= $secure_post_msg;

				endif;

			endif;

		endwhile;
		wp_reset_query();

	else:

		$html .= 'Ooops ('.$slug.')';

	endif;

endif;

function wps_insert_pagination($page, $page_count, $pagination_previous, $pagination_next, $pagination_url) {
	$h = '<div class="wps_pagination_numbers">';

	if ($pagination_previous && $page > 1):
		if (wps_using_permalinks()) {
			$prev_page_url = sprintf($pagination_url, $page-1);
		} else {
			$prev_page_url = sprintf($pagination_url, $page-1);
		}			
		$h .= '<div class="wps_pagination_number">';
		$h .= '<a style="text-decoration: none;" href="'.$prev_page_url.'">'.$pagination_previous.'</a>';
		$h .= '</div>';
	endif;

	for ($x=1; $x<=$page_count; $x++) {
		if (wps_using_permalinks()) {
			$page_url = sprintf($pagination_url, $x);
		} else {
			$page_url = sprintf($pagination_url, $x);
		}
		$h .= '<div class="wps_pagination_number';
		if ($x == $page) $h .= ' wps_pagination_number_current';
		$h .= '">';
		$h .= '<a style="text-decoration: none;" href="'.$page_url.'">'.$x.'</a>';
		$h .= '</div>';
	} 

	if ($pagination_next && $page < $page_count):
		if (wps_using_permalinks()) {
			$prev_page_url = sprintf($pagination_url, $page+1);
		} else {
			$prev_page_url = sprintf($pagination_url, $page+1);
		}			
		$h .= '<div class="wps_pagination_number">';
		$h .= '<a style="text-decoration: none;" href="'.$prev_page_url.'">'.$pagination_next.'</a>';
		$h .= '</div>';
	endif;

	$h .= '</div>';

	return $h;
}






?>