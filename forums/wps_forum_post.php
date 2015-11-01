<?php

global $wpdb, $post, $current_user;

$this_user = $current_user;

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

	// Delete comment confirmed
	if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_forum_comment_delete') ) wps_forum_delete_comment($_POST, $_FILES);

	if (!isset($_GET['topic_id'])):
		$post_slug = get_query_var('topic');
	else:
		$the_post = get_post($_GET['topic_id']);
		if ($the_post):
			$post_slug = $the_post->post_name;
		else:
			echo '<div class="wps_error">'.__('Failed to find forum post with topic_id', WPS2_TEXT_DOMAIN).'</div>';
		endif;
	endif;

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
                $locked = false;
				foreach( $post_terms as $term ):
					if (user_can_see_forum($current_user->ID, $term->term_id) || current_user_can('manage_options')) $user_can_see = true;
                    if (wps_get_term_meta($term->term_id, 'wps_forum_closed', true)) $locked = true;
				endforeach;
    
                $current_user = $this_user;

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

                        // Update view counter for this post (if not post author)
                        if ($post->post_author != $current_user->ID):
                            $view_count = get_post_meta( $post->ID, 'wps_forum_view_count', true );
                            if (!$view_count) $view_count = 0;
                            $view_count++;
                            update_post_meta ( $post->ID, 'wps_forum_view_count', $view_count);
                        endif;

						// Get count of replies
						$sql = "SELECT COUNT(*) FROM ".$wpdb->prefix."comments WHERE comment_post_ID = %d AND comment_parent = 0";
                        $num_comments = $wpdb->get_var($wpdb->prepare($sql, $post->ID));
						if ( $num_comments == 0 ) {
							$comments_count = __('No replies');
						} elseif ( $num_comments > 1 ) {
							$comments_count = sprintf(__('%d replies', WPS2_TEXT_DOMAIN), $num_comments);
						} else {
							$comments_count = __('1 reply', WPS2_TEXT_DOMAIN);
						}

						// Prepare pagination
						if (isset($_GET['page'])):
							$page = isset($_GET['page']) ? $_GET['page'] : 1; // No permalinks
						else:
							$page = get_query_var('fpage'); // Permalinks
							if ($page):
								$page = explode('-', $page);
								$page = (int)$page[1];
							else:
								$page = 1;
							endif;
						endif;
						$limit = $page_size;
						$offset = ($page * $limit) - $limit;

						$pages = ceil($num_comments/$limit);
						if ($page > $pages):
							$page = 1;
							$offset = 0;
						endif;
						
						// Original Post

						$post_html = '';
						$author = get_user_by('id', $post->post_author);

						$post_title_html = '<h2 class="wps_forum_post_title">';
                        
                        $wps_post_avatar = user_avatar_get_avatar( $author->ID, $size, true );
                        $post_title_avatar_html = '<div class="wps_author_meta_avatar_mobile">';
                            $post_title_avatar_html .= $wps_post_avatar;
                        $post_title_avatar_html .= '</div>';
                        //$post_title_html .= $post_title_avatar_html;

						$post_title = wps_strip_tags($post->post_title);
                        $multiline = (strpos($post_title, chr(10)) !== false) ? true : false;
                        if ($multiline) $post_title = str_replace(chr(10), '<br />', $post_title);
						if (strlen($post_title) > $title_length) $post_title = substr($post_title, 0, $title_length).' ...';
						$post_title_html .= $post_title;
						if ($show_comments_count):
                            if ($multiline) $post_title_html .= '<br />';
                            $post_title_html .= ' ('.$comments_count.')';
                        endif;
						$post_title_html .= '</h2>';

						$post_title_html = apply_filters( 'wps_forum_post_post_title_filter', $post_title_html, $post, $atts, $current_user->ID );

						if ($page == 1 || !$hide_initial):

							// Only show original post on page 1

                            $post_html .= '<div id="wps_initial_post">';
                                
                                $post_html .= $post_title_html;

                                $post_html .= '<div class="wps_forum_post_comment" style="padding-left: '.($size).'px;">';

                                    $post_html .= '<div class="wps_forum_post_comment_author" style="max-width: '.($size).'px; margin-left: -'.($size).'px;">';
                                        $post_html .= '<div class="wps_forum_post_comment_author_avatar">';
                                            $post_html .= $wps_post_avatar;
                                        $post_html .= '</div>';
                                        $post_html .= '<div class="wps_forum_post_comment_author_display_name">';
                                            $wps_display_name = wps_display_name(array('user_id'=>$author->ID, 'link'=>1));
                                            $post_html .= $wps_display_name;
                                        $post_html .= '</div>';
                                        $post_html .= '<div class="wps_forum_post_comment_author_freshness">';
                                            $date = $base_date == 'post_date_gmt' ? $post->post_date_gmt : $post->post_date;
                                            $wps_timestamp = sprintf($date_format, human_time_diff(strtotime($date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
                                            $post_html .= $wps_timestamp;
                                        $post_html .= '</div>';
                                    $post_html .= '</div>';

                                    $post_html .= '<div class="wps_forum_post_comment_content">';

                                        $post_html .= '<div class="wps_author_meta_mobile">';
                                            $post_html .= '<span class="wps_author_meta_mobile_display_name">'.$wps_display_name.'</span>';
                                            $post_html .= ' <span class="wps_author_meta_mobile_date">'.$wps_timestamp.'</span>';
                                        $post_html .= '</div>';

                                        // Post Settings
                                        $age = current_time('timestamp', 1) - strtotime($post->post_date);
                                        $user_can_edit_forum = $post->post_author == $current_user->ID ? true : false;
                                        $user_can_edit_forum = apply_filters( 'wps_forum_post_user_can_edit_filter', $user_can_edit_forum, $post, $current_user->ID, $term->term_id );
                                        $user_can_delete_forum = $post->post_author == $current_user->ID ? true : false;
                                        $user_can_delete_forum = apply_filters( 'wps_forum_post_user_can_delete_filter', $user_can_delete_forum, $post, $current_user->ID, $term->term_id );

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
                                        $post_content = convert_smilies(wps_make_clickable(wpautop(wps_bbcode_replace(html_entity_decode($post_content)))));
                                        $post_content = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $post_content);  
                                        $post_content = apply_filters( 'wps_forum_item_content_filter', $post_content, $post, $atts );
                                        $post_html .= $post_content;

                                        // Filter for handling anything else
                                        // Passes $post_html, shortcodes options ($atts), mail post ($post), message ($post->post_content))
                                        $post_html = apply_filters( 'wps_forum_item_filter', $post_html, $atts, $post, $post->post_content );

                                    $post_html .= '</div>';

                                $post_html .= '</div>';

                            $post_html .= '</div>';

						else:

                            $post_html .= $post_title_html;

							// If page > 1, show "page" subtitle
							if ($page_x_of_y) $post_html .= '<h3>'.sprintf($page_x_of_y, $page, $pages).'</h3>';

						endif;

						if ($pages > 1 && $pagination && $pagination_top):
							$post_html .= '<div id="wps_forum_pagination_top">';
								if (wps_using_permalinks()):
									$post_html .= wps_insert_pagination($page, $pages, $pagination_previous, $pagination_next, get_bloginfo('url').'/'.$term->slug.'/'.$post_slug.'/page-%d');
								else:
									$post_html .= wps_insert_pagination($page, $pages, $pagination_previous, $pagination_next, get_bloginfo('url').'?page_id='.$_GET['page_id'].'&topic='.$_GET['topic'].'&page=%d');
								endif;
							$post_html .= '</div>';
						endif;

						// Published replies

						$args = array(
							'status' => 1,
							'orderby' => 'comment_date',
							'order' => 'ASC',
							'post_id' => $post->ID,
							'offset' => $offset,
							'number' => $limit,
							'parent' => 0
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

                                    $private = get_comment_meta( $comment->comment_ID, 'wps_private_post', true );
                                    if (!$private || $current_user->ID == $post->post_author || $comment->user_id == $current_user->ID || current_user_can('manage_options')):

                                        $comment_html = '';
                                        $private_div_style = $private ? ' wps_private_post_div' : '';
                                        $comment_html .= '<div class="wps_forum_post_comment'.$private_div_style.'" style="padding-left: '.($size).'px;">';
                                            if ($private) $comment_html .= '<div class="wps_private_post" style="margin-left: -'.($size/2).'px;">'.$private_reply_msg.'</div>';

                                            $comment_html .= '<div class="wps_forum_post_comment_author" style="max-width: '.($size).'px; margin-left: -'.($size).'px;">';
                                                if ($comment->user_id):
                                                    $comment_html .= '<div class="wps_forum_post_comment_author_avatar">';
                                                        $comment_html .= user_avatar_get_avatar( $comment->user_id, $size, true );
                                                    $comment_html .= '</div>';
                                                    $comment_html .= '<div class="wps_forum_post_comment_author_display_name">';
                                                        $wps_display_name = wps_display_name(array('user_id'=>$comment->user_id, 'link'=>1));
                                                        $comment_html .= $wps_display_name;
                                                    $comment_html .= '</div>';
                                                else:
                                                    $comment_html .= '<div style="width:'.$size.'px; height:0"></div>';
                                                endif;
                                                $comment_html .= '<div class="wps_forum_post_comment_author_freshness">';
                                                    $date = $base_date == 'post_date_gmt' ? $comment->comment_date_gmt : $comment->comment_date;
                                                    $wps_timestamp = sprintf($date_format, human_time_diff(strtotime($date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
                                                    $comment_html .= $wps_timestamp;
                                                $comment_html .= '</div>';
                                            $comment_html .= '</div>';

                                            $comment_html .= '<div class="wps_forum_post_comment_content">';

                                                $comment_html .= '<div class="wps_author_meta_mobile">';
                                                    $comment_html .= '<span class="wps_author_meta_mobile_display_name">'.$wps_display_name.'</span>';
                                                    $comment_html .= ' <span class="wps_author_meta_mobile_date">'.$wps_timestamp.'</span>';
                                                $comment_html .= '</div>';

                                                $comment_html = apply_filters( 'wps_forum_item_pre_comment_filter', $comment_html, $atts, $comment, $comment->comment_content );

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
												$comment_content = convert_smilies(wps_make_clickable(wpautop(wps_bbcode_replace(html_entity_decode($comment_content)))));
                                                $comment_content = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $comment_content);  
                                                $comment_content = apply_filters( 'wps_forum_item_comment_content_filter', $comment_content, $comment, $atts );
                                                $comment_html .= $comment_content;

                                                // Filter for handling anything else
                                                // Passes $comment_html, shortcodes options ($atts), mail comment ($comment), message ($comment->comment_content))
                                                $comment_html = apply_filters( 'wps_forum_item_comment_filter', $comment_html, $atts, $comment, $comment->comment_content );

                                                // Comments to replies (if allowed)
                                                if ($show_comments):

                                                    // Show all comments so far

                                                    $args = array(
                                                        'status' => 1,
                                                        'orderby' => 'comment_ID',
                                                        'order' => 'ASC',
                                                        'post_id' => $post->ID,
                                                        'offset' => 0,
                                                        'number' => 100,
                                                        'parent' => $comment->comment_ID
                                                    );

                                                    $subcomments = get_comments($args);

                                                    if ($subcomments):

                                                        $comment_html .= '<div class="wps_forum_post_subcomments">';

                                                            foreach($subcomments as $subcomment) :

                                                                $sub_comment_html = '';

                                                                $sub_comment_html .= '<div id="wps_forum_post_comments_'.$comment->comment_ID.'" class="wps_forum_post_subcomment" style="padding-left: '.($comments_avatar_size).'px;">';

                                                                    $sub_comment_html .= '<div class="wps_forum_post_comment_author" style="max-width: '.($comments_avatar_size).'px; margin-left: -'.($comments_avatar_size).'px;">';
                                                                        if ($subcomment->user_id):
                                                                            $sub_comment_html .= '<div class="wps_forum_post_comment_author_avatar">';
                                                                                $sub_comment_html .= user_avatar_get_avatar( $subcomment->user_id, $comments_avatar_size, true );
                                                                            $sub_comment_html .= '</div>';
                                                                        else:
                                                                            $sub_comment_html .= '<div style="width:'.$comments_avatar_size.'px; height:0"></div>';
                                                                        endif;
                                                                    $sub_comment_html .= '</div>';

                                                                    $sub_comment_html .= '<div class="wps_forum_post_comment_content">';

                                                                        $user_can_edit_comment = $subcomment->user_id == $current_user->ID ? true : false;
                                                                        $user_can_delete_comment = $subcomment->user_id == $current_user->ID ? true : false;

                                                                        // Comment Settings
                                                                        if ( ($user_can_edit_comment || $user_can_delete_comment) || current_user_can('manage_options') ):
                                                                            $sub_comment_html .= '<div class="wps_forum_settings">';
                                                                                $sub_comment_html .= '<img style="height:15px;width:15px;" src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
                                                                            $sub_comment_html .= '</div>';
                                                                            $sub_comment_html .= '<div class="wps_forum_settings_options">';
                                                                                $url = wps_curPageURL();
                                                                                if ($user_can_edit_comment || current_user_can('manage_options')) $sub_comment_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=edit&comment_id='.$subcomment->comment_ID.'">'.__('Edit', WPS2_TEXT_DOMAIN).'</a>';
                                                                                if (($user_can_edit_comment || current_user_can('manage_options')) && $timeout-$age >= 0) $sub_comment_html .= '<br />('.sprintf(__('lock in %d seconds', WPS2_TEXT_DOMAIN), ($timeout-$age)).')';
                                                                                if (($user_can_edit_comment && $user_can_delete_comment) || current_user_can('manage_options')) $sub_comment_html .= ' | ';
                                                                                if ($user_can_delete_comment || current_user_can('manage_options')) $sub_comment_html .= '<a href="'.$url.wps_query_mark($url).'forum_action=delete&comment_id='.$subcomment->comment_ID.'">'.__('Delete', WPS2_TEXT_DOMAIN).'</a>';
                                                                            $sub_comment_html .= '</div>';						
                                                                        endif;

                                                                        $sub_comment_content = wps_strip_tags($subcomment->comment_content);
																		$sub_comment_content = convert_smilies(wps_make_clickable(wpautop(wps_bbcode_replace(html_entity_decode($sub_comment_content)))));
                                                                        $sub_comment_content = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $sub_comment_content);  
                                                                        $sub_comment_content = apply_filters( 'wps_forum_item_sub_comment_content_filter', $sub_comment_content, $subcomment, $atts );

                                                                        $sub_comment_author = '<div class="wps_forum_post_comment_author_display_name">';
                                                                            $sub_comment_author .= wps_display_name(array('user_id'=>$subcomment->user_id, 'link'=>1));
                                                                        $sub_comment_author .= '</div>';

                                                                        $sub_comment_date = '<div class="wps_forum_post_comment_author_freshness">';
                                                                            $date = $base_date == 'post_date_gmt' ? $subcomment->comment_date_gmt : $subcomment->comment_date;
                                                                            $sub_comment_date .= sprintf($date_format, human_time_diff(strtotime($date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
                                                                        $sub_comment_date .= '</div>';
                                                                        $sub_comment_html .= $sub_comment_author . $sub_comment_date . $sub_comment_content;

                                                                        // Filter for handling anything else
                                                                        // Passes $comment_html, shortcodes options ($atts), mail comment ($comment), message ($comment->comment_content))
                                                                        $sub_comment_html = apply_filters( 'wps_forum_item_sub_comment_filter', $sub_comment_html, $atts, $subcomment, $subcomment->comment_content );

                                                                    $sub_comment_html .= '</div>';

                                                                $sub_comment_html .= '</div>';

                                                                $comment_html .= $sub_comment_html;

                                                            endforeach;

                                                        $comment_html .= '</div>';

                                                    endif;

                                                    if ($allow_comments&& !$locked && $post->comment_status != 'closed' && is_user_logged_in()):

                                                        $show = $show_comment_form ? '' : 'display:none';
                                                        $sub_comment_form = '<div id="sub_comment_div_'.$comment->comment_ID.'" class="wps_forum_post_comment_div" style="'.$show.'">';
                                                            $sub_comment_form .= '<textarea id="sub_comment_'.$comment->comment_ID.'" class="wps_forum_post_comment_form"></textarea>';
                                                        $sub_comment_form .= '</div>';
                                                        $sub_comment_form .= '<button class="wps_submit wps_forum_post_comment_form_submit '.$comment_class.'" data-post-id="'.$post->ID.'" data-size="'.$comments_avatar_size.'" rel="'.$comment->comment_ID.'">'.$comment_add_label.'</button>';
// removed for button  $sub_comment_form .= '<input type="submit" class="wps_submit wps_forum_post_comment_form_submit '.$comment_class.'" data-post-id="'.$post->ID.'" data-size="'.$comments_avatar_size.'" rel="'.$comment->comment_ID.'" value="'.$comment_add_label.'" />';
                                                        $comment_html .= $sub_comment_form;

                                                    endif;

                                                endif;

                                            $comment_html .= '</div>';

                                        $comment_html .= '</div>';

                                        $comment_html = apply_filters( 'wps_forum_post_comment_filter', $comment_html, $comment, $atts, $current_user->ID );

                                        $post_html .= $comment_html;

                                    endif;

								endforeach;

							$post_html .= '</div>';

						endif;

						// Pending replies
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
													$comment_html .= user_avatar_get_avatar( $comment->user_id, $size, true );
												$comment_html .= '</div>';
												$comment_html .= '<div class="wps_forum_post_comment_author_display_name">';
													$comment_html .= wps_display_name(array('user_id'=>$comment->user_id, 'link'=>1));
												$comment_html .= '</div>';
												$comment_html .= '<div class="wps_forum_post_comment_author_freshness">';
													$date = $base_date == 'post_date_gmt' ? $comment->comment_date_gmt : $comment->comment_date;
													$comment_html .= sprintf($date_format, human_time_diff(strtotime($date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
												$comment_html .= '</div>';
											$comment_html .= '</div>';

											$comment_html .= '<div class="wps_forum_post_comment_content">';
												if ($comment->comment_approved != 'publish') $post_html .= '<div class="wps_forum_post_comment_pending">'.$comment_pending.'</div>';

												$comment_content_html = wps_bbcode_replace(convert_smilies(wps_make_clickable(wpautop(esc_html($comment->comment_content)))));
                                                $comment_content_html = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $comment_content_html);  
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
								if (wps_using_permalinks()):
									$post_html .= wps_insert_pagination($page, $pages, $pagination_previous, $pagination_next, get_bloginfo('url').'/'.$term->slug.'/'.$post_slug.'/page-%d');
								else:
									$post_html .= wps_insert_pagination($page, $pages, $pagination_previous, $pagination_next, get_bloginfo('url').'?page_id='.$_GET['page_id'].'&topic='.$_GET['topic'].'&page=%d');
								endif;
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

		$html .= 'Ooops ('.$slug.'/'.$post_slug.')';

	endif;

endif;






?>