<?php
if ($style == 'classic'):

	// Sort the posts by sticky first, then last contributed to, finally last added
	$sort = array();
	foreach($forum_posts as $k=>$v) {
	    $sort['is_sticky'][$k] = $v['is_sticky'];
	    $sort['read'][$k] = $v['read'];
	    $sort['last_comment'][$k] = $v['last_comment'];
	    $sort['ID'][$k] = $v['ID'];
	}
	array_multisort($sort['is_sticky'], SORT_DESC, $sort['last_comment'], SORT_DESC, $sort['read'], SORT_ASC, $sort['ID'], SORT_DESC, $forum_posts);

    $html .= '<div class="'.$slug.' wps_forum_posts_classic">';

		$c = 0;
        global $wpdb;

		foreach ($forum_posts as $forum_post):

			if ($forum_post['post_status'] == 'publish' || current_user_can('edit_posts') || $forum_post['post_author'] = $current_user->ID):

				$c++;

                $author = wps_display_name(array('user_id'=>$forum_post['post_author'], 'link'=>1));
                $created = sprintf($date_format, human_time_diff(strtotime($forum_post[$base_date]), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);

				$forum_html = '';

				$forum_html .= '<div class="wps_forum_post_classic';
						if ($forum_post['comment_status'] == 'closed') $forum_html .= ' wps_forum_post_closed';
						if ($forum_post['is_sticky']) $forum_html .= ' wps_forum_post_sticky';
					$forum_html .= '"'; // end of class

					// Hide if closed and chosen not to show
					if ($closed_switch && $forum_post['comment_status'] == 'closed' && $closed_switch_state == 'off' && !$forum_post['is_sticky']) $forum_html .= ' style="display:none"';

					$forum_html .= '>'; // end of opening div

					// Any more columns? (apply float:right)
					$forum_html = apply_filters( 'wps_forum_post_columns_filter', $forum_html, $forum_post['ID'], $atts );

                    // Show title
                    global $blog;
                    if ( wps_using_permalinks() ):
                        if (!is_multisite()):
                            $url = get_bloginfo('url').'/'.$slug.'/'.$forum_post['post_name'];
                        else:
                            $blog_details = get_blog_details(get_current_blog_id());
                            $url = $blog_details->path.$slug.'/'.$forum_post['post_name'];
                        endif;
                    else:
                        if (!is_multisite()):
                            $forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
                            $url = get_bloginfo('url')."/?page_id=".$forum_page_id."&topic=".$forum_post['post_name'];
                        else:
                            $forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
                            $blog_details = get_blog_details(get_current_blog_id());
                            $url = $blog_details->path."?page_id=".$forum_page_id."&topic=".$forum_post['post_name'];
                        endif;
                    endif;

                    $forum_title = esc_attr($forum_post['post_title']);
                    $forum_title = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $forum_title);  
                    if (strlen($forum_title) > $title_length) $forum_title = substr($forum_title, 0, $title_length).'...';
                    $multiline = (strpos($forum_title, chr(10)) !== false) ? true : false;
                    if ($multiline) $forum_title = str_replace(chr(10), '<br />', $forum_title);

                    $forum_html .= '<div class="wps_forum_title_classic_content_title"><a href="'.$url.'">'.$forum_title.'</a></div>';

					$forum_html .= '<div class="wps_forum_title_classic_content_row" style="padding-left: '.($size_posts).'px;">';

                        $forum_html .= '<div class="wps_forum_title_classic_content_avatar" style="margin-left: -'.($size_posts).'px;">';
                            $forum_html .= user_avatar_get_avatar( $forum_post['post_author'], $size_posts, true );
                        $forum_html .= '</div>';

                        // Counts
                        $sql = "SELECT * FROM ".$wpdb->prefix."comments WHERE comment_post_ID = %d";
                        $comments = $wpdb->get_results($wpdb->prepare($sql, $forum_post['ID']));
                        $comment_count = 0;
                        foreach ($comments as $comment):
                            $private = get_comment_meta( $comment->comment_ID, 'wps_private_post', true );
                            if (!$private) $comment_count++;
                        endforeach;

                        // Replies/comments count
                        $forum_html .= '<div class="wps_forum_count_classic">';
                            $forum_html .= $comment_count;
                            $label = ($comment_count != 1) ? $replies_count_label : $reply_count_label;
                            $forum_html .= '<div class="wps_forum_count_classic_label">'.$label.'</div>';
                        $forum_html .= '</div>';
    
                        // Views count
                        $forum_html .= '<div class="wps_forum_count_classic">';
                            $view_count = get_post_meta( $forum_post['ID'], 'wps_forum_view_count', true );
                            if (!$view_count) $view_count = 0;
                            $forum_html .= $view_count;
                            $label = ($view_count != 1) ? $views_count_label : $view_count_label;
                            $forum_html .= '<div class="wps_forum_count_classic_label">'.$label.'</div>';
                        $forum_html .= '</div>';
    
                        $forum_html .= '<div class="wps_forum_title_classic_content';
                            if ($reply_icon && $forum_post['commented']) $forum_html .= ' wps_forum_post_classic_commented';
                            $forum_html .= '">';
                            // Filter for suffix after name
                            $started_string = apply_filters( 'wps_forum_post_name_filter', $started, $forum_post['ID'] );
                            if ($forum_post['comment_status'] == 'closed' && $closed_prefix) $started_string .= ' ['.$closed_prefix.']';

                            $forum_html .= sprintf($started_string, $author, $created);
                            // Add post content
                            $forum_post_content = $forum_post['post_content'];
                            $forum_post_content = strip_tags(html_entity_decode(htmlspecialchars_decode(strip_tags($forum_post_content), ENT_QUOTES)));
                            $forum_post_content = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $forum_post_content);  
                            if (strlen($forum_post_content) > $post_preview) $forum_post_content = substr($forum_post_content, 0, $post_preview).'...';
                            $forum_html .= '<br />'.$forum_post_content;

                            // Get all recent replies, record most recent, but check for later comments just in case

                            $sql = "SELECT * FROM ".$wpdb->prefix."comments WHERE ( comment_approved = '1' ) AND comment_post_ID = %d AND comment_parent = 0 AND user_id > 0 ORDER BY comment_ID DESC LIMIT 0,20";
                            $replies = $wpdb->get_results($wpdb->prepare($sql, $forum_post['ID']));

                            $r = 0;
                            $author = false; // in case none to show, if private?

                            if ($replies):

                                foreach($replies as $reply) :

                                    $private = get_comment_meta( $reply->comment_ID, 'wps_private_post', true );
                                    if (!$private || $current_user->ID == $post->post_author || $reply->user_id == $current_user->ID || current_user_can('manage_options')):
                                    
                                        $r++;
                                        if ($r == 1):   
                                            // This is most recent reply, so store (and use if no later comments)
                                            $content = $reply->comment_content;
                                            $author = $reply->user_id;
                                            $date = sprintf($date_format, human_time_diff(strtotime($reply->comment_date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
                                            $original_date = $reply->comment_date;
                                            $action = $replied;
                                        endif;

                                        // check for comments (in case later than latest reply)
                                        $sql = "SELECT * FROM ".$wpdb->prefix."comments WHERE ( comment_approved = '1' ) AND comment_post_ID = %d AND comment_parent = %d AND user_id > 0 ORDER BY comment_ID DESC LIMIT 1";
                                        $comments = $wpdb->get_results($wpdb->prepare($sql, $forum_post['ID'], $reply->comment_ID));

                                        if ($comments):
                                            // There is a comment, this is latest one, so check date in case later than last reply
                                            foreach ($comments as $comment):
                                                if ($comment->comment_date > $original_date):
                                                    $content = $comment->comment_content;
                                                    $date = sprintf($date_format, human_time_diff(strtotime($comment->comment_date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
                                                    $author = $comment->user_id;
                                                    $action = $commented;
                                                endif;
                                            endforeach;
                                        endif;

                                    endif;

                                endforeach;

                                if ($author): // latest reply found, and not private

                                // Get comment's post forum term ID
                                $forum_html .= '<div class="wps_forum_post_classic_comments';
                                    if (!$forum_post['read']) $forum_html .= ' wps_forum_post_unread';
                                    $forum_html .= '" style="padding-left: '.($size_replies).'px;">';

                                    $forum_html .= '<div class="wps_forum_post_classic_comment_avatar" style="margin-left: -'.($size_replies).'px;">';
                                        $forum_html .= user_avatar_get_avatar( $author, $size_replies, true );
                                    $forum_html .= '</div>';
                                    $forum_html .= '<div class="wps_forum_post_classic_comment_content">';
                                        $forum_html .= sprintf($action, wps_display_name(array('user_id'=>$author, 'link'=>1)), $date);

                                        // Add post content
                                        $forum_post_content = $content;
                                        $forum_post_content = strip_tags(html_entity_decode(htmlspecialchars_decode($forum_post_content, ENT_QUOTES)));
                                        $forum_post_content = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $forum_post_content);  
                                        if (strlen($forum_post_content) > $reply_preview) $forum_post_content = substr($forum_post_content, 0, $reply_preview).'...';
                                        $forum_html .= '<br />'.$forum_post_content;

                                    $forum_html .= '</div>';

                                $forum_html .= '</div>';

                                endif;

                            else:

                                // No replies

                            endif;

                        $forum_html .= '</div>';

					$forum_html .= '</div>';

				$forum_html .= '</div>';

				$forum_html = apply_filters( 'wps_forum_post_item', $forum_html );
				$html .= $forum_html;

			endif;

			if ($c == $count) break;

		endforeach;

	$html .= '</div>';
    
endif;
?>