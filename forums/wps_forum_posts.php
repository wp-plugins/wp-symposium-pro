<?php

$term = get_term_by('slug', $slug, 'wps_forum');
$term_children = get_term_children($term->term_id, 'wps_forum');

$forum_posts = array();
global $post, $current_user;

// Get posts
$loop = new WP_Query( array(
	'post_type' => 'wps_forum_post',
	'posts_per_page' => 100, // Maximum, $count is used further down as may exclude due to comment_status
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'wps_forum',
			'field' => 'slug',
			'terms' => $slug,
		),
		array( 
			'taxonomy' => 'wps_forum',
			'field' => 'id',
			'terms' => $term_children,
			'operator' => 'NOT IN'
			)
	    ),
) );

if ($loop->have_posts()):

	$wps_forum_author = wps_get_term_meta($term->term_id, 'wps_forum_author', true);

	while ( $loop->have_posts() ) : $loop->the_post();

		if (!$wps_forum_author || $post->post_author == $current_user->ID || current_user_can('manage_options')):

			// Check status
			$the_post = get_post($post->ID);
			if ($status == '' || $status == $post->comment_status):

				$forum_post = array();
				$forum_post['ID'] = $post->ID;
				$forum_post['post_status'] = $post->post_status;
				$forum_post['post_author'] = $post->post_author;
				$forum_post['post_name'] = $post->post_name;
				$forum_post['post_title'] = $post->post_title;
				$forum_post['post_content'] = $post->post_content;
				$forum_post['post_date'] = $post->post_date;
				$forum_post['post_date_gmt'] = $post->post_date_gmt;
				$forum_post['comment_status'] = $post->comment_status;
				$forum_post['is_sticky'] = get_post_meta($post->ID, 'wps_sticky');

				$read = get_post_meta( $post->ID, 'wps_forum_read', true );
				if ($read && in_array($current_user->user_login, $read)):
					$forum_post['read'] = true;
				else:
					$forum_post['read'] = false;
				endif;

				$args = array(
					'status' => 1,
					'orderby' => 'comment_ID',
					'number' => 99999,
					'order' => 'DESC',
					'post_id' => $post->ID,
				);
				$comments = get_comments($args);
				$forum_post['commented'] = 0;
				if ($comments):
					$forum_post['last_comment'] = $comments[0]->comment_date;
					foreach ($comments as $comment):
						if ($comment->comment_author == $current_user->user_login) $forum_post['commented']++;
					endforeach;
				else:
					$forum_post['last_comment'] = $post->post_date;
				endif;

				$forum_posts[$post->ID] = $forum_post;

			endif;

		endif;

	endwhile;

endif;

if ($forum_posts):

    if ($closed_switch):
        $closed_switch_state = is_user_logged_in() ? get_user_meta($current_user->ID, 'forum_closed_switch', true) : $closed_switch;
        if (!$closed_switch_state) $closed_switch_state = 'on';
        $html .= '<input type="checkbox" id="closed_switch"';
            if ($closed_switch_state == 'on') $html .= ' CHECKED';
            $html .= ' /> ';
        $html .= $closed_switch_msg;
    endif;

    // Now display based on style
    if ($style):
        $file = dirname(__FILE__).'/wps_forum_posts_'.$style.'.php';
        if( file_exists($file) ):
            include($file);
        else:
            $html .= sprintf(__('Forum file does not exist ("%s").', WPS2_TEXT_DOMAIN), $file);
        endif;
    else:
        $html .= sprintf(__('Invalid "style" option for forum shortcode ("%s").', WPS2_TEXT_DOMAIN), $style);
    endif;

else:

	$html .= '<div style="clear: both">'.$empty_msg.'</div>';

endif;

wp_reset_query();

?>

