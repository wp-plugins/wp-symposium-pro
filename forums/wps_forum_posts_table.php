<?php
if ($style == 'table'):

    if ($show_header):
        $html .= '<div class="wps_forum_posts_header">';

            // Any more column headers? (apply float:right)
            $html = apply_filters( 'wps_forum_post_columns_header_filter', $html, $term->term_id, $atts );

            $html .= '<div class="wps_forum_title_header">'.$header_title.'</div>';
            if ($show_last_activity) $html .= '<div class="wps_forum_last_poster_header">'.$header_last_activity.'</div>';
            if ($show_count) $html .= '<div class="wps_forum_count_header">'.$header_count.'</div>';
        $html .= '</div>';
    endif;

	// Sort the posts by sticky first, then last contributed to, finally last added
	$sort = array();
	foreach($forum_posts as $k=>$v) {
	    $sort['is_sticky'][$k] = $v['is_sticky'];
	    $sort['read'][$k] = $v['read'];
	    $sort['last_comment'][$k] = $v['last_comment'];
	    $sort['ID'][$k] = $v['ID'];
	}
	array_multisort($sort['is_sticky'], SORT_DESC, $sort['last_comment'], SORT_DESC, $sort['read'], SORT_ASC, $sort['ID'], SORT_DESC, $forum_posts);

    $html .= '<div class="'.$slug.' wps_forum_posts">';

		$c = 0;
		foreach ($forum_posts as $forum_post):

			if ($forum_post['post_status'] == 'publish' || current_user_can('edit_posts') || $forum_post['post_author'] = $current_user->ID):

				$c++;
				$args = array(
					'status' => 1,
					'orderby' => 'comment_ID',
					'order' => 'DESC',
					'post_id' => $forum_post['ID'],
				);
				$comments = get_comments($args);
				$created = false; // set in case nothing to show
				$author = false;  // set in case nothing to show
				$date = false;  // set in case nothing to show

                $comment_count = 0;
				if ($comments):
                    $comments_ptr = 0;
                    foreach($comments as $comment):
                    	if ($comment->user_id): // not an auto-close comment
	                        $private = get_comment_meta( $comment->comment_ID, 'wps_private_post', true );
	                        if (!$private || $current_user->ID == $post->post_author || $comment->user_id == $current_user->ID || current_user_can('manage_options')):
	                            if ($comment->user_id):
	                            	if (!$comments_ptr):
	                                	$author = wps_display_name(array('user_id'=>$comment->user_id, 'link'=>1));
	                                	$date = $base_date == 'post_date_gmt' ? $comment->comment_date_gmt : $comment->comment_date;
	                                	$created = sprintf($date_format, human_time_diff(strtotime($date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
	                            	endif;
		                            $comment_count++;
		                            $comments_ptr++;
		                       	endif;   
	                        endif;
	                    endif;
                    endforeach;
				else:
					$author = wps_display_name(array('user_id'=>$forum_post['post_author'], 'link'=>1));
					$created = sprintf($date_format, human_time_diff(strtotime($forum_post[$base_date]), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
				endif;

				$forum_html = '';

				$forum_html .= '<div class="wps_forum_post';
						if ($forum_post['comment_status'] == 'closed') $forum_html .= ' wps_forum_post_closed';
						if ($forum_post['is_sticky']) $forum_html .= ' wps_forum_post_sticky';
					$forum_html .= '"'; // end of class

					// Hide if closed and chosen not to show
					if ($closed_switch && $forum_post['comment_status'] == 'closed' && $closed_switch_state == 'off' && !$forum_post['is_sticky']) $forum_html .= ' style="display:none"';

					$forum_html .= '>'; // end of opening div

					// Any more columns? (apply float:right)
					$forum_html = apply_filters( 'wps_forum_post_columns_filter', $forum_html, $forum_post['ID'], $atts );

					$forum_html .= '<div class="wps_forum_title';
						if (!$forum_post['read']) $forum_html .= ' wps_forum_post_unread';
						if ($reply_icon && $forum_post['commented']) $forum_html .= ' wps_forum_post_commented';
						$forum_html .= '">';
						if ($forum_post['comment_status'] == 'closed' && $closed_prefix) $forum_html .= '['.$closed_prefix.'] ';
						if ($forum_post['post_status'] == 'publish'):

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
							$forum_html .= '<a href="'.$url.'">'.$forum_title.'</a>';
						else:
                            $forum_title = esc_attr($forum_post['post_title']);
                            $forum_title = str_replace(array_keys(array('[' => '&#91;',']' => '&#93;',)), array_values(array('[' => '&#91;',']' => '&#93;',)), $forum_title);  
							$forum_html .= $forum_title.$pending;						
						endif;
                        // Filter for suffix after name
                        $forum_html = apply_filters( 'wps_forum_post_name_filter', $forum_html, $forum_post['ID'] );

					$forum_html .= '</div>';
					if ($show_freshness):
						$forum_html .= '<div class="wps_forum_freshness">';
						$forum_html .= $created ? $created : '&nbsp;';
						$forum_html .= '</div>';
					endif;
					if ($show_last_activity):
						$forum_html .= '<div class="wps_forum_last_poster">';
						$forum_html .= $author ? $author : '&nbsp;';
						$forum_html .= '</div>';
					endif;
					if ($show_count):
						$forum_html .= '<div class="wps_forum_count">';
						$forum_html .= $comment_count;
						$forum_html .= '</div>';
					endif;

				$forum_html .= '</div>';

				$forum_html = apply_filters( 'wps_forum_post_item', $forum_html );
				$html .= $forum_html;

			endif;

			if ($c == $count) break;

		endforeach;

	$html .= '</div>';
    
endif;
?>