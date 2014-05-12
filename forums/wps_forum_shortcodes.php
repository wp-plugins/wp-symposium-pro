<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_forum_init() {
	// JS and CSS
	wp_enqueue_script('wps-forum-js', plugins_url('wps_forum.js', __FILE__), array('jquery'));	
	wp_localize_script( 'wps-forum-js', 'wps_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		
	wp_enqueue_style('wps-forum-css', plugins_url('wps_forum.css', __FILE__), 'css');
	// Select2 replacement drop-down list from core (ready for dependenent plugins like who-to that only uses hooks/filters)
	wp_enqueue_script('wps-select2-js', plugins_url('../js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../js/select2.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_forum_init_hook');
}
																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_forum_page($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_init');

	$html = '';

	global $current_user;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'slug' => '',
		'header_title' => __('Topic', WPS2_TEXT_DOMAIN),
		'header_count' => __('Replies', WPS2_TEXT_DOMAIN),
		'header_last_activity' => __('Last activity', WPS2_TEXT_DOMAIN),
	), $atts, 'wps_forum_page' ) );

	if ($slug == ''):

		$html .= sprintf(__('Please add slug="xxx" to the shortcode, where xxx is the <a href="%s">slug of the forum</a>.', WPS2_TEXT_DOMAIN), admin_url('edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post'));

	else:

		$html .= wps_forum_post(array('slug'=>$slug));
		$html .= wps_forum_backto(array('slug'=>$slug));
		$html .= wps_forum(array('slug'=>$slug, 'header_title'=>$header_title, 'header_count'=>$header_count, 'header_last_activity' => $header_last_activity));
		$html .= wps_forum_comment(array('slug'=>$slug));
		$html .= wps_forum_backto(array('slug'=>$slug));

	endif;

	return $html;

}

function wps_forum_show_posts($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_init');

	$html = '';

	global $current_user;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'slug' => '',
		'order' => 'date',
		'orderby' => 'DESC',
		'status' => '', // all (or '')|open|closed
		'include_posts' => 1,
		'include_comments' => 0,
		'closed_prefix' => __('closed', WPS2_TEXT_DOMAIN),
		'show_author' => 1,
		'author_format' => __('By', WPS2_TEXT_DOMAIN).' %s',
		'author_link' => 1,
		'show_date' => 1,
		'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),
		'show_snippet' => 1,
		'snippet_length' => 30,
		'max' => 10,
		'before' => '',
		'after' => '',
	), $atts, 'wps_forum_show_posts' ) );

	$forum_posts = array();
	global $post, $current_user;

	// Get posts
	if ($include_posts):
		$loop = new WP_Query( array(
			'post_type' => 'wps_forum_post',
			'posts_per_page' => 500,
		) );

		if ($loop->have_posts()):

			$forum_posts = array();

			while ( $loop->have_posts() ) : $loop->the_post();

				if ($status == 'all' || $status == '' || $status == $post->comment_status):

					$forum_post = array();
					$forum_post['ID'] = $post->ID;
					$forum_post['post_author'] = $post->post_author;
					$forum_post['post_name'] = $post->post_name;
					$forum_post['post_title'] = $post->post_title;
					$forum_post['post_title_lower'] = strtolower($post->post_title);
					$forum_post['post_date'] = $post->post_date;
					$forum_post['post_date_gmt'] = $post->post_date_gmt;
					$forum_post['post_content'] = $post->post_content;
					$forum_post['comment_status'] = $post->comment_status;
					$forum_post['type'] = 'post';

					$forum_posts['p_'.$post->ID] = $forum_post;

				endif;

			endwhile;

		endif;

	endif;

	// Get comments
	if ($include_comments):

		global $wpdb;
		$sql = "SELECT * FROM ".$wpdb->prefix."comments c LEFT JOIN ".$wpdb->prefix."posts p ON c.comment_post_ID = p.ID WHERE comment_approved=1 AND p.post_type = %s LIMIT %d, %d";
		$comments = $wpdb->get_results($wpdb->prepare($sql, 'wps_forum_post', 0, 500));

		if ($comments):
			foreach($comments as $comment):

				$forum_post = array();
				$forum_post['post_author'] = $comment->user_id;
				$forum_post['post_date'] = $comment->comment_date;
				$forum_post['post_date_gmt'] = $comment->comment_date_gmt;
				$forum_post['post_content'] = $comment->comment_content;

				$parent_post = get_post($comment->comment_post_ID);
				$forum_post['ID'] = $parent_post->ID;
				$forum_post['post_name'] = $parent_post->post_name;
				$forum_post['post_title'] = $parent_post->post_title;
				$forum_post['post_title_lower'] = strtolower($parent_post->post_title);
				$forum_post['comment_status'] = $parent_post->comment_status;

				$forum_post['type'] = 'post';

				$forum_posts['c_'.$comment->comment_ID] = $forum_post;
			
			endforeach;
		endif;

	endif;

	// Show results
	if ( !empty( $forum_posts ) ):

		// Sort the posts by "order", then name
		$sort = array();
		$order = $order != 'title' ? $order : 'title_lower';
		$order = 'post_'.$order;
		foreach($forum_posts as $k=>$v) {
		    $sort[$order][$k] = $v[$order];
		    $sort['post_title'][$k] = $v['post_title'];
		}
		$orderby = strtoupper($orderby);
		if ($orderby != 'RAND'):
			$orderby = $orderby == "ASC" ? SORT_ASC : SORT_DESC;
			array_multisort($sort[$order], $orderby, $sort['post_title'], $orderby, $forum_posts);
		else:
			uksort($forum_posts, "wps_rand_cmp");
		endif;

		// Show results
		$html .= '<div class="wps_forum_get_posts">';

			$c = 0;
			$continue = true;
			$previous_title = '';

			foreach ($forum_posts as $forum_post):

				$post_terms = get_the_terms( $forum_post['ID'], 'wps_forum' );

				if( $post_terms && !is_wp_error( $post_terms ) ):
				    foreach( $post_terms as $term ):

				    	if (!$slug || $slug == $term->slug):

							if (user_can_see_forum($current_user->ID, $term->term_id)):

								// Only see own posts?
								if (user_can_see_post($current_user->ID, $forum_post['ID'])):

									$forum_html = '';

									$forum_html .= '<div class="wps_forum_get_post">';

										if ($show_snippet && $previous_title != esc_attr($forum_post['post_title'])):
											$forum_html .= '<div class="wps_forum_get_title">';

												if (is_multisite()) {

													$blog_details = get_blog_details($blog->blog_id);
													$url = $blog_details->path.$term->slug.'/'.$forum_post['post_name'];


												} else {

													$permalink_structure = get_option( 'permalink_structure' );
													if ($permalink_structure):
														$url = '/'.$term->slug.'/'.$forum_post['post_name'];
													else:
														$forum_page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
														$url = "/?page_id=".$forum_page_id."&topic=".$forum_post['post_name'];
													endif;

												}

												$the_title = esc_attr($forum_post['post_title']);
												$the_title = str_replace(
												  array('[', ']'), 
												  array('&#91;', '&#93;'), 
												  $the_title
												);											
												if ($forum_post['comment_status'] == 'closed' && $closed_prefix) $forum_html .= '['.$closed_prefix.'] ';
												$forum_html .= '<a href="'.$url.'">'.$the_title.'</a>';
											$forum_html .= '</div>';
											$previous_title = esc_attr($forum_post['post_title']);
										endif;

										if ($show_date):
											$forum_html .= '<div class="wps_forum_get_date">';
												$forum_html .= sprintf($date_format, human_time_diff(strtotime($forum_post['post_date_gmt']), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN);
											$forum_html .= '</div>';										
										endif;

										if ($show_author):
											$forum_html .= '<div class="wps_forum_get_author">';
												$forum_html .= sprintf($author_format, wps_display_name(array('user_id'=>$forum_post['post_author'], 'link'=>$author_link)));
											$forum_html .= '</div>';										
										endif;

										if ($show_snippet):
											$forum_html .= '<div class="wps_forum_get_snippet">';
												$content = convert_smilies(make_clickable(wpautop(esc_html($forum_post['post_content']))));
												$snippet_text = strip_tags($content);
												$snippet_text = wps_get_words($snippet_text, $snippet_length);
												$snippet_text = str_replace(
												  array('[', ']'), 
												  array('&#91;', '&#93;'), 
												  $snippet_text
												);											
												$forum_html .= $snippet_text;
											$forum_html .= '</div>';
										endif;

									$forum_html .= '</div>';

									$forum_html = apply_filters( 'wps_forum_get_post_item', $forum_html );
									$html .= $forum_html;

									$c++;
									if ($c == $max) $continue = false;

								endif;

							endif;

							if (!$continue) break; // maximum reached

						endif;

				    endforeach;

				endif;

				if (!$continue) break; // maximum reached

			endforeach;

		$html .= '</div>';

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}

function wps_forum_backto($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_init');

	$html = '';

	if ( ( (!isset($_GET['forum_action']) && !isset($_POST['action'])) || ($_POST['action'] == 'wps_forum_post_edit') ) && get_query_var('topic') ): // showing a single post

		global $current_user;

		// Shortcode parameters
		extract( shortcode_atts( array(
			'slug' => '',
			'label' => __('Back to %s...', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_forum_comment' ) );

		if ($slug == ''):

			$html .= __('Please add slug="xxx" to the shortcode, where xxx is the slug of the forum.', WPS2_TEXT_DOMAIN);

		else:

			$term = get_term_by( 'slug', $slug, 'wps_forum' );
			if (user_can_see_forum($current_user->ID, $term->term_id)):

				$page_id = wps_get_term_meta($term->term_id, 'wps_forum_cat_page', true);
				$url = get_permalink($page_id);
				$html .= '<a href="'.$url.'">'.sprintf($label, $term->name).'</a>';

			endif;

		endif;

		if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	endif;

	return $html;

}


function wps_forum_comment($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_init');

	$html = '';

	if (is_user_logged_in() && (!isset($_GET['forum_action']) || ($_GET['forum_action'] != 'edit' && $_GET['forum_action'] != 'delete')) && get_query_var('topic') ): // showing a single post

		global $current_user;

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'content_label' => '',
			'label' => __('Add Comment', WPS2_TEXT_DOMAIN),
			'private_msg' => '',
			'moderate' => false,
			'show' => 1,
			'moderate_msg' => __('Your comment will appear once it has been moderated.', WPS2_TEXT_DOMAIN),
			'close_msg' => __('Tick to close this post', WPS2_TEXT_DOMAIN),
			'comments_closed_msg' => __('This post is closed.', WPS2_TEXT_DOMAIN),
			'reopen_label' => __('Re-open this post', WPS2_TEXT_DOMAIN),
			'locked_msg' => __('This forum is locked. New posts and replies are not allowed.', WPS2_TEXT_DOMAIN).' ',
			'slug' => '',
			'before' => '',
			'after' => '',
		), $atts, 'wps_forum_comment' ) );

		if ($slug == ''):

			$html .= __('Please add slug="xxx" to the shortcode, where xxx is the slug of the forum.', WPS2_TEXT_DOMAIN);

		else:

			$term = get_term_by( 'slug', $slug, 'wps_forum' );
			if (user_can_see_forum($current_user->ID, $term->term_id)):

				if (!wps_get_term_meta($term->term_id, 'wps_forum_closed', true)):

					$args=array(
						'name' => get_query_var('topic'),
						'post_type' => 'wps_forum_post',
						'posts_per_page' => 1
					);
					$my_posts = get_posts( $args );
					if ( $my_posts ):

						if (user_can_see_post($current_user->ID, $my_posts[0]->ID)):

							$form_html = '';

							if ($my_posts[0]->comment_status != 'closed'):

								$form_html .= '<div id="wps_forum_comment_div">';
									
									$form_html .= '<div id="wps_forum_comment_form"';

										if (!$show) $form_html .= ' style="display:none;"';
										$form_html .= '>';

										$form_html .= '<form enctype="multipart/form-data" id="wps_forum_comment_theuploadform">';
										$form_html .= '<input type="hidden" id="wps_forum_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
										$form_html .= '<input type="hidden" name="action" value="wps_forum_comment_add" />';
										$form_html .= '<input type="hidden" name="post_id" value="'.$my_posts[0]->ID.'" />';
										$form_html .= '<input type="hidden" name="wps_forum_slug" value="'.$slug.'" />';
										$form_html .= '<input type="hidden" name="wps_forum_moderate" value="'.$moderate.'" />';

										$form_html .= '<div id="wps_forum_comment_content_label">'.$content_label.'</div>';
										$form_html = apply_filters( 'wps_forum_comment_pre_form_filter', $form_html, $atts, $current_user->ID );
										$form_html .= '<textarea id="wps_forum_comment" name="wps_forum_comment"></textarea>';

										if ($my_posts[0]->post_author == $current_user->ID || current_user_can('edit_posts'))
											$form_html .= '<input type="checkbox" name="wps_close_post" id="wps_close_post" style="float:left; margin-right:10px;" /><label for="wps_close_post">'.$close_msg.'</label>';

										if ($moderate) $form_html .= '<div id="wps_forum_comment_moderate">'.$moderate_msg.'</div>';

										$form_html = apply_filters( 'wps_forum_comment_post_form_filter', $form_html, $atts, $current_user->ID );

									$form_html .= '</div>';

									$form_html .= '<input id="wps_forum_comment_button" type="submit" class="'.$class.'" value="'.$label.'" />';

									$form_html .= '</form>';
								
								$form_html .= '</div>';

							else:

								$form_html .= $comments_closed_msg;

								if ($my_posts[0]->post_author == $current_user->ID || current_user_can('edit_posts')):
									$form_html .= '<form id="wps_forum_comment_reopen_theuploadform">';
										$form_html .= '<input type="hidden" id="wps_forum_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
										$form_html .= '<input type="hidden" name="action" value="wps_forum_comment_reopen" />';
										$form_html .= '<input type="hidden" id="reopen_post_id" value="'.$my_posts[0]->ID.'" />';
										$form_html .= '<input id="wps_forum_comment_reopen_button" type="submit" class="'.$class.'" value="'.$reopen_label.'" />';
									$form_html .= '</form>';
								endif;


							endif;

							$html .= $form_html;

						endif;

					endif;

				else:

					$html .= $locked_msg;

				endif;

			else:

				$html .= $private_msg;

			endif;

		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_forum_post($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_init');

	$html = '';

	if ( is_user_logged_in() && ( 
		(!isset($_GET['forum_action']) && !get_query_var('topic')) || 
		(isset($_POST['action']) && $_POST['action'] == 'wps_forum_post_delete') 
		) ): // not showing a single post or just deleted a topic

		global $current_user;

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'title_label' => 'Post title',
			'content_label' => 'Post',
			'label' => __('Add Topic', WPS2_TEXT_DOMAIN),
			'moderate_msg' => __('Your post will appear once it has been moderated.', WPS2_TEXT_DOMAIN),
			'locked_msg' => __('This forum is locked. New posts and replies are not allowed.', WPS2_TEXT_DOMAIN),
			'private_msg' => '',
			'moderate' => false,
			'show' => 0,
			'slug' => '',
			'before' => '',
			'after' => '',
		), $atts, 'wps_forum_post' ) );

		if ($slug == ''):

			$html .= __('Please add slug="xxx" to the shortcode, where xxx is the slug of the forum.', WPS2_TEXT_DOMAIN);

		else:

			$term = get_term_by( 'slug', $slug, 'wps_forum' );
				
			if (!wps_get_term_meta($term->term_id, 'wps_forum_closed', true)):

				$user_can_see_forum = user_can_see_forum($current_user->ID, $term->term_id);

				// Filter to add additional conditions
				$user_can_see_forum = apply_filters( 'wps_forum_post_user_can_post_filter', $user_can_see_forum, $current_user->ID, $term->term_id );

				if ($user_can_see_forum):

					$form_html = '';			
					$form_html .= '<div id="wps_forum_post_div">';
						
						$form_html .= '<div id="wps_forum_post_form"';
							if (!$show) $form_html .= ' style="display:none;"';
							$form_html .= '>';

							$form_html .= '<form enctype="multipart/form-data" id="wps_forum_post_theuploadform">';
							$form_html .= '<input type="hidden" id="wps_forum_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
							$form_html .= '<input type="hidden" name="action" value="wps_forum_post_add" />';
							$form_html .= '<input type="hidden" name="wps_forum_slug" value="'.$slug.'" />';
							$form_html .= '<input type="hidden" name="wps_forum_moderate" value="'.$moderate.'" />';

							$form_html .= '<div id="wps_forum_post_title_label">'.$title_label.'</div>';
							$form_html .= '<input type="text" id="wps_forum_post_title" name="wps_forum_post_title" />';

							$form_html .= '<div id="wps_forum_post_content_label">'.$content_label.'</div>';
							$form_html = apply_filters( 'wps_forum_post_pre_form_filter', $form_html, $atts, $current_user->ID );
							$form_html .= '<textarea id="wps_forum_post_textarea" name="wps_forum_post_textarea"></textarea>';

							if ($moderate) $form_html .= '<div id="wps_forum_post_moderate">'.$moderate_msg.'</div>';
							$form_html = apply_filters( 'wps_forum_post_post_form_filter', $form_html, $atts, $current_user->ID );

						$form_html .= '</div>';

						$form_html .= '<input id="wps_forum_post_button" type="submit" class="'.$class.'" value="'.$label.'" />';
						$form_html .= '</form>';
					
					$form_html .= '</div>';

					$html .= $form_html;

				else:

					$html .= $private_msg;

				endif;

			else:

				$html .= $locked_msg;

			endif;

		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}


function wps_forum($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_init');

	global $current_user;
	
	$html = '';

	// Shortcode parameters
	extract( shortcode_atts( array(
		'parent' => 0,
		'status' => '', // open|closed (ie. post comment_status, default to all, ie. '')
		'show_header' => true,
		'private_msg' => __('You must be logged in to view this forum.', WPS2_TEXT_DOMAIN),
		'login_url' => '',
		'secure_msg' => __('You do not have permission to view this forum.', WPS2_TEXT_DOMAIN),
		'secure_post_msg' => __('You do not have permission to view this post.', WPS2_TEXT_DOMAIN),
		'empty_msg' => __('No forum posts.', WPS2_TEXT_DOMAIN),
		'slug' => '',
		'pending' => '('.__('pending', WPS2_TEXT_DOMAIN).')',
		'comment_pending' => '('.__('pending', WPS2_TEXT_DOMAIN).')',
		'closed_prefix' => __('closed', WPS2_TEXT_DOMAIN),
		'header_title' => __('Topic', WPS2_TEXT_DOMAIN),
		'header_count' => __('Replies', WPS2_TEXT_DOMAIN),
		'header_last_activity' => __('Last activity', WPS2_TEXT_DOMAIN),
		'moved_to' => __('%s successfully moved to %s', WPS2_TEXT_DOMAIN),
		'timeout' => 120,
		'count' => -1,
		'size' => 96,
		'before' => '',
		'after' => '',
	), $atts, 'wps_forum' ) );	

	if ($slug == ''):

		$html .= __('Please add slug="xxx" to the [wps-forum] shortcode, where xxx is the slug of the forum.', WPS2_TEXT_DOMAIN);

	else:

		$term = get_term_by( 'slug', $slug, 'wps_forum' );

		if (user_can_see_forum($current_user->ID, $term->term_id)):

			if ( ( isset($_POST['action']) && $_POST['action'] == 'wps_forum_post_delete') ):
			
				// Delete entire post and show remaining forum posts
				$post_id = $_POST['wps_post_id'];
				if ($post_id):
					$current_post = get_post($post_id);
					if ( $current_post && ($current_user->ID == $current_post->post_author || current_user_can('manage_options')) ):

						wp_delete_post($post_id, true);

						// Any further actions?
						do_action( 'wps_forum_post_delete_hook', $_POST, $_FILES, $post_id );

					endif;
				endif;
				require('wps_forum_posts.php');

			else:

				// check if viewing single post
				if ( get_query_var('topic') ):

					require('wps_forum_post.php');	

				else:

					require('wps_forum_posts.php');

				endif;

			endif;

		else:

			$public = wps_get_term_meta($term->term_id, 'wps_forum_public', true);
			if (!$public && !is_user_logged_in()) {
				$query = wps_query_mark(get_bloginfo('url').$login_url);
				if ($login_url) $html .= sprintf('<a href="%s%s%sredirect=%s">', get_bloginfo('url'), $login_url, $query, site_url( $_SERVER['REQUEST_URI'] ));
				$html .= $private_msg;
				if ($login_url) $html .= '</a>';
			} else {
				$html .= $secure_msg;
			}

		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_forums($atts) {

	// Init
	add_action('wp_footer', 'wps_forum_init');

	$html = '';

	global $post, $current_user;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'show_children' => 1,
		'parent' => 0,
		'show_header' => 1,
		'slug' => '',
		'before' => '',
		'after' => '',
		'forum_title' => __('Forum', WPS2_TEXT_DOMAIN),
		'forum_count' => __('Count', WPS2_TEXT_DOMAIN),
		'forum_last_activity' => __('Last Poster', WPS2_TEXT_DOMAIN),		
		'forum_freshness' => __('Freshness', WPS2_TEXT_DOMAIN),		
	), $atts, 'wps_forums' ) );

	if ($show_header):
		$html .= '<div class="wps_forum_categories_header">';
			$html .= '<div class="wps_forum_categories_description">'.$forum_title.'</div>';
			$html .= '<div class="wps_forum_categories_count">'.$forum_count.'</div>';
			$html .= '<div class="wps_forum_categories_last_poster">'.$forum_last_activity.'</div>';
			$html .= '<div class="wps_forum_categories_freshness">'.$forum_freshness.'</div>';
		$html .= '</div>';
	endif;

	$html = wps_forum_categories_children($html, $slug, $parent, $show_children, $atts, 0);

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_forum_categories_children($html, $slug, $forum_id, $show_children, $atts, $indent) {

	global $current_user;

	// Shortcode parameters
	extract( shortcode_atts( array(
		'show_posts_header' => 1,
	    'show_posts'		=> 0,		
	    'show_summary' 		=> 1,
		'header_title' => __('Topic', WPS2_TEXT_DOMAIN),
		'header_count' => __('Replies', WPS2_TEXT_DOMAIN),
		'header_last_activity' => __('Last activity', WPS2_TEXT_DOMAIN),
	), $atts, 'wps_forums' ) );

	$terms = get_terms( "wps_forum", array(
		'parent'		=> $forum_id,
	    'hide_empty'    => false, 
	    'fields'        => 'all', 
	    'slug'			=> $slug,
	    'hierarchical'  => false, 
	    'child_of'      => $forum_id, 
	) );

	if ( count($terms) > 0 ):

		$forums = array();

		foreach ( $terms as $term ):

			if (user_can_see_forum($current_user->ID, $term->term_id)):

				$forum = array();
				$forum['term_id'] = $term->term_id;
				$forum['order'] = wps_get_term_meta($term->term_id, 'wps_forum_order', true);
				$forum['name'] = $term->name;
				$forum['slug'] = $term->slug;
				if ($term->description):
					$forum['description'] = $term->description;
				else:
					$forum['description'] = '&nbsp;';
				endif;
				$forum['count'] = $term->count;

				$forums[$term->term_id] = $forum;

			endif;

		endforeach;

		if ($forums):

			// Sort the forums by order first, then name
			$sort = array();
			foreach($forums as $k=>$v) {
			    $sort['order'][$k] = $v['order'];
			    $sort['name'][$k] = $v['name'];
			}
			array_multisort($sort['order'], SORT_ASC, $sort['name'], SORT_ASC, $forums);

			foreach ($forums as $forum):

				$loop = new WP_Query( array(
					'post_type' => 'wps_forum_post',
					'posts_per_page' => 1,
					'post_status' => 'publish',
					'tax_query' => array(
						array(
							'taxonomy' => 'wps_forum',
							'field' => 'slug',
							'terms' => $forum['slug'],
						)
					)				
				) );

				global $post;
				if ($loop->have_posts()):
					while ( $loop->have_posts() ) : $loop->the_post();
						$user = get_user_by('id', $post->post_author);
						$author = $user->display_name;
						$created = sprintf(__('%s ago', WPS2_TEXT_DOMAIN), human_time_diff(strtotime($post->post_date), current_time('timestamp')), WPS2_TEXT_DOMAIN);
					endwhile;
				else:
					$author = '-';
					$created = '-';
				endif;
				wp_reset_query();

				$page_id = wps_get_term_meta($forum['term_id'], 'wps_forum_cat_page', true);
				$url = get_permalink($page_id);

				$forum_html = '';
				$forum_html .= '<div class="wps_forum_categories_item">';
					$forum_html .= '<div class="wps_forum_categories_name" style="padding-left:'.($indent*20).'px;">';
					$forum_html .= '<h'.($indent+2).'><a href="'.$url.'">'.$forum['name'].'</a></h'.($indent+2).'>';
					$forum_html .= '</div>';
					$forum_html .= '<div class="wps_forum_categories_description" style="padding-left:'.($indent*20).'px;">';
						$forum_html .= $forum['description'];
					$forum_html .= '</div>';
					if ($show_summary):
						$forum_html .= '<div class="wps_forum_categories_count">';
							$forum_html .= $forum['count'];
						$forum_html .= '</div>';
						$forum_html .= '<div class="wps_forum_categories_last_poster">';
							$forum_html .= $author;
						$forum_html .= '</div>';
						$forum_html .= '<div class="wps_forum_categories_freshness">';
							$forum_html .= $created;
						$forum_html .= '</div>';
					endif;
				$forum_html .= '</div>';

				if ($show_posts):
					$forum_html .= '<div class="wps_forum_categories_item_sep"></div>';
					$forum_html .= wps_forum(array('slug'=> $forum['slug'], 'show_header' => $show_posts_header, 'count' => $show_posts, 'header_title'=>$header_title, 'header_count'=>$header_count, 'header_last_activity' => $header_last_activity));
				endif;

				$forum_html = apply_filters( 'wps_forum_categories_item_filter', $forum_html );
				$html .= $forum_html;

				if ($show_children)
					$html = wps_forum_categories_children ($html, $slug, $forum['term_id'], $show_children, $atts, $indent+1);

			endforeach;

		endif;

	endif;

	return $html;

}

if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum-page', 'wps_forum_page');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum-backto', 'wps_forum_backto');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-forums', 'wps_forums');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum-post', 'wps_forum_post');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum-comment', 'wps_forum_comment');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum', 'wps_forum');
if (!is_admin()) add_shortcode(WPS_PREFIX.'-forum-show-posts', 'wps_forum_show_posts');


// Function used to sort randomly
function wps_rand_cmp($a, $b){
    return rand() > rand();
}


?>
