<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_activity_init() {
	// JS and CSS
	wp_enqueue_script('wps-activity-js', plugins_url('wps_activity.js', __FILE__), array('jquery'));	
	wp_localize_script( 'wps-activity-js', 'wps_activity_ajax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'plugins_url' => plugins_url( '', __FILE__ )
	));		
	wp_enqueue_style('wps-activity-css', plugins_url('wps_activity.css', __FILE__), 'css');	
	// Select2 replacement drop-down list from core (ready for dependenent plugins like who-to that only uses hooks/filters)
	wp_enqueue_script('wps-select2-js', plugins_url('../js/select2.min.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-select2-css', plugins_url('../js/select2.css', __FILE__), 'css');
	// Anything else?
	do_action('wps_activity_init_hook');
}


																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_activity_page($atts){

	// Init
	add_action('wp_footer', 'wps_activity_init');

	$html = '';

	// Shortcode parameters
	extract( shortcode_atts( array(
		'avatar_size' => '150',
		'map_style' => 'static',
		'map_size' => '150,150',
		'map_zoom' => '4',
		'town_label' => __('Town/City', WPS2_TEXT_DOMAIN),
		'country_label' => __('Country', WPS2_TEXT_DOMAIN),
	), $atts, 'wps_activity_page' ) );	

	$html .= '<style>.wps_avatar img { border-radius:0px; }</style>';
	$html .= wps_display_name(array('before'=>'<div id="wps_display_name" style="font-size:2em">', 'after'=>'</div>'));
	$html .= '<div style="overflow:auto;margin-bottom:15px">';
	$html .= wps_avatar(array('change_link'=>1, 'size'=>$avatar_size, 'before'=>'<div id="wps_display_avatar" style="float:left; margin-right:5px;">', 'after'=>'</div>'));
	$html .= wps_usermeta(array('meta'=>'wpspro_map', 'map_style'=>$map_style, 'size'=>$map_size, 'zoom'=>$map_zoom, 'before'=>'<div id="wps_display_map" style="float:left;margin-right:15px;">', 'after'=>'</div>'));
	$html .= '<div style="float:left;margin-right:15px;">';
	$html .= wps_usermeta(array('meta'=>'wpspro_home', 'before'=>'<strong>'.$town_label.'</strong><br />', 'after'=>'<br />'));
	$html .= wps_usermeta(array('meta'=>'wpspro_country', 'before'=>'<strong>'.$country_label.'</strong><br />', 'after'=>'<br />'));
	$html .= wps_usermeta_change_link(array());
	$html .= '</div>';
	$html .= '<div id="wps_display_friend_requests" style="margin-left:10px;float:left;min-width:200px;">';
	$html .= wps_friends_pending(array('before'=>'<strong>Friend Requests</strong><br />'));
	$html .= wps_friends_add_button(array());
	$html .= '</div>';
	$html .= '</div>';
	$html .= wps_activity_post(array());
	$html .= wps_activity(array());

	return $html;

}

function wps_activity_post($atts) {

	// Init
	add_action('wp_footer', 'wps_activity_init');

	$html = '';

	global $current_user;

	if (is_user_logged_in()) {	

		// Shortcode parameters
		extract( shortcode_atts( array(
			'class' => '',
			'label' => __('Add Post', WPS2_TEXT_DOMAIN),
			'private_msg' => __('Only friends can post here', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_activity' ) );

		$user_id = wps_get_user_id();

		$friends = wps_are_friends($current_user->ID, $user_id);
		if ($friends['status'] == 'publish'):

			$form_html = '';
			$form_html .= '<div id="wps_activity_post_div" style="display:none">';
				$form_html .= '<form id="theuploadform">';
				$form_html .= '<input type="hidden" id="wps_activity_post_action" name="action" value="wps_activity_post_add" />';
				$form_html .= '<input type="hidden" name="wps_activity_post_author" value="'.$current_user->ID.'" />';
				$form_html .= '<input type="hidden" name="wps_activity_post_target" value="'.$user_id.'" />';

				$form_html = apply_filters( 'wps_activity_post_pre_form_filter', $form_html, $atts, $user_id, $current_user->ID );

				$form_html .= '<textarea id="wps_activity_post" name="wps_activity_post"></textarea>';
				$form_html .= '<input id="wps_activity_post_button" type="submit" class="'.$class.'" value="'.$label.'" />';
				$form_html .= '</form>';
			$form_html .= '</div>';

			$html .= $form_html;


		else:

			$html .= $private_msg;

		endif;

	}

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}

function wps_activity($atts) {

	// Init
	add_action('wp_footer', 'wps_activity_init');

	$html = '';
	global $current_user, $wpdb;

	if (is_user_logged_in()) {

		$html .= '<br style="clear:both" />';

		// Shortcode parameters
		extract( shortcode_atts( array(
			'user_id' => false,
			'post_id' => false,
			'include_self' => 1,
			'include_friends' => 1,
			'count' => 100,
			'get_max' => 100,
			'avatar_size' => 64,
			'more' => 50,
			'more_label' => 'more',
			'hide_until_loaded' => false,
			'type' => '',
			'comment_avatar_size' => 40,
			'label' => __('Comment', WPS2_TEXT_DOMAIN),
			'class' => '',
			'link' => true,
			'private_msg' => __('Activity is private', WPS2_TEXT_DOMAIN),
			'delete_label' => __('Delete', WPS2_TEXT_DOMAIN),
			'sticky_label' => __('Stick', WPS2_TEXT_DOMAIN),
			'unsticky_label' => __('Unstick', WPS2_TEXT_DOMAIN),
			'allow_replies' => 1,
			'date_format' => __('%s ago', WPS2_TEXT_DOMAIN),
			'before' => '',
			'after' => '',
		), $atts, 'wps_activity' ) );

		if (!$user_id):
			$user_id = wps_get_user_id();
		endif;

		// Check for single post view
		if (!$post_id && isset($_GET['view'])) $post_id = $_GET['view'];

		$activity = array();

		// If friends with this user, or the user, get activity
		$friends = wps_are_friends($current_user->ID, $user_id);
		if ($current_user->ID == $user_id || $friends['status'] == 'publish'):

			if (!$post_id):

				if ($type == ''): // Activity only

					// Get user's activity (and posts targetted to user)
					if ($include_self):
						$sql = "SELECT p.ID, p.post_title, p.post_author, p.post_date, c.comment_date, m.meta_value AS target_ids FROM ".$wpdb->prefix."posts p 
							LEFT JOIN ".$wpdb->prefix."comments c ON p.ID = c.comment_post_ID
							LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
							WHERE p.post_type = %s
							AND m.meta_key = 'wps_target'
							AND p.post_status = 'publish'
							AND (
							  	p.post_author = %d OR
							  	p.post_author = %d OR
								c.comment_author = %d OR
								c.comment_author = %d OR
								m.meta_value LIKE '%%\"%d\"%%' OR
								m.meta_value = %d OR 
								m.meta_value LIKE '%%\"%d\"%%' OR
								m.meta_value = %d
							)
							ORDER BY p.ID DESC
							LIMIT 0,%d";

						$results = $wpdb->get_results($wpdb->prepare($sql, 'wps_activity', $user_id, $current_user->ID, $user_id, $current_user->ID, $user_id, $user_id, $current_user->ID, $current_user->ID, $get_max));

						$added_count = 0;
						$added_sticked = 0;
						foreach ($results as $r):

							// Check this is a normal activity post
							$actvity_type = get_post_meta($r->ID, 'wps_activity_type', true);
							if (!$actvity_type):

								$target_users = array();
								$target_ids = $r->target_ids;
								// Make a note of any target users (excluding post author)
								if ($target_ids):
									if (is_array($target_ids) && $target_ids_array = unserialize($target_ids)):
										// Target is to multiple users
										$target_users = array_merge($target_users, $target_ids_array);
									else:
										// Target is one user
										array_push($target_users, $target_ids);
									endif;
								endif;
								$add = false;
								if ($user_id == $current_user->ID):		// ------------ On user's own page
									// If author is this user
									if ($r->post_author == $user_id) { $add = true; };
									// If this user is a target (and a friend)
									if (wps_are_friends($r->post_author, $user_id)) { $add = true; };
									// Exclude if this is just a friend sharing to friends
									if ($r->post_author != $user_id && (string)$r->post_author == $target_ids) { $add = false; };
								else: 									// ------------ On a friends page
									// If to friends, and current user is a friend of this user
									if ($r->post_author == $target_ids && wps_are_friends($r->post_author, $current_user->ID)) { $add = true; };
									// If from this user to current user
									if ($r->post_author == $user_id && in_array((string)$current_user->ID, $target_users)) { $add = true; };
									// If to this user and from current user (handle array)
									if ( preg_match( '/^a:\d+:{.*?}$/', $target_ids ) ): 
										$target_ids_array = unserialize($target_ids);
										if ($r->post_author == $current_user->ID && in_array((int)$user_id, $target_ids_array)) { $add = true; };
									endif;
									// Exclude own posts to friends
									if ($r->post_author != $user_id && $target_ids == $r->post_author) { $add = false; };
								endif;

								if ($add):
									$is_sticky = get_post_meta( $r->ID, 'wps_sticky', true ) ? 2 : 1;
									if ($r->post_date > $r->comment_date):
										array_push($activity, array('ID' => $r->ID, 'date' => $r->post_date, 'is_sticky' => $is_sticky));
									else:
										array_push($activity, array('ID' => $r->ID, 'date' => $r->comment_date, 'is_sticky' => $is_sticky));
									endif;
									$added_count++;
									if ($is_sticky == 2) $added_sticked++;
								endif;
							endif;
						endforeach;
					endif;

					// Get activity from all friends of this page user
					if ($include_friends):
						$friends = wps_get_friends($user_id);
						if ($friends):
							foreach ($friends as $friend):

								$sql = "SELECT p.ID, p.post_date, p.post_author, c.comment_date, m.meta_value AS target_ids FROM ".$wpdb->prefix."posts p 
									LEFT JOIN ".$wpdb->prefix."comments c ON p.ID = c.comment_post_ID
									LEFT JOIN ".$wpdb->prefix."postmeta m ON p.ID = m.post_id
									WHERE p.post_type = %s
									AND m.meta_key = 'wps_target'
									AND p.post_status = 'publish'
									AND (p.post_author = %d OR p.post_author = %d)
									ORDER BY p.ID DESC
									LIMIT 0, %d";
								
								$results = $wpdb->get_results($wpdb->prepare($sql, 'wps_activity', $friend['ID'], $current_user->ID, $count));

								foreach ($results as $r):
									$add = false;
									$target_ids = $r->target_ids;
									if (is_array($target_ids)):
										// Show it this user is in the list of target user IDs
										$target_ids_array = unserialize($target_ids);
										if (in_array((string)$user_id, $target_ids_array)) { $add = true; };
									else:
										// Show if this user is the target, or the user is posting to all friends
										if ($user_id == $target_ids || $r->post_author == $r->target_ids):
											$add = true;
										endif;
									endif;
									// Current user is the author, always show
									if ($r->post_author == $current_user->ID) { $add = true; };

									if ($add):
										if ($r->post_date > $r->comment_date):
											array_push($activity, array('ID' => $r->ID, 'date' => $r->post_date, 'is_sticky' => 1));
										else:
											array_push($activity, array('ID' => $r->ID, 'date' => $r->comment_date, 'is_sticky' => 1));
										endif;									
									endif;
								endforeach;

							endforeach;
						endif;
					endif;

				endif;

				// Any more activity?
				$activity = apply_filters( 'wps_activity_items_filter', $activity, $atts, $user_id, $current_user->ID );

			else:

				// Single post view
				$single = get_post($post_id);
				if ($single) array_push($activity, array('ID' => $post_id, 'date' => $single->post_date, 'is_sticky' => 0));

			endif;

			if ($activity):

				$shown = array();

				// Sort the posts by sticky first, then date (newest first)
				$sort = array();
				foreach($activity as $k=>$v) {
				    $sort['is_sticky'][$k] = $v['is_sticky'];
				    $sort['date'][$k] = $v['date'];
				}
				array_multisort($sort['is_sticky'], SORT_DESC, $sort['date'], SORT_DESC, $activity);


				$html .= '<div id="wps_activity_items"';
					if ($hide_until_loaded) $html .= 'style="display:none"';
					$html .= '>';

					$items = '';
					$shown_count = 0;

					$items = apply_filters( 'wps_activity_pre_filter', $items, $atts, $user_id, $current_user->ID );

					foreach ($activity as $i):

						if (!in_array($i['ID'], $shown) && $i['ID']):

							array_push($shown, $i['ID']);
							$item = get_post($i['ID']);

							$item_html = '';
							$is_sticky = get_post_meta( $item->ID, 'wps_sticky', true );
							$is_sticky_css = $is_sticky ? ' wps_sticky' : '';

							$item_html .= '<div class="wps_activity_item'.$is_sticky_css.'" id="wps_activity_'.$item->ID.'" style="position:relative;padding-left: '.($avatar_size+10).'px">';

								$item_html .= '<div id="wps_activity_'.$item->ID.'_content" class="wps_activity_content">';

									// Settings
									if ($item->post_author == $current_user->ID || current_user_can('manage_options')):
										$item_html .= '<div class="wps_activity_settings" style="display:none">';
											$item_html .= '<img style="height:15px;width:15px;" src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
										$item_html .= '</div>';
										$item_html .= '<div class="wps_activity_settings_options" style="display:none">';
											if (!$is_sticky && $sticky_label) $item_html .= '<a class="wps_activity_settings_sticky" rel="'.$item->ID.'" href="javascript:void(0);">'.$sticky_label.'</a>';
											if ($is_sticky && $unsticky_label) $item_html .= '<a class="wps_activity_settings_unsticky" rel="'.$item->ID.'" href="javascript:void(0);">'.$unsticky_label.'</a>';
											if ($delete_label) $item_html .= '<a class="wps_activity_settings_delete" rel="'.$item->ID.'" href="javascript:void(0);">'.$delete_label.'</a>';
										$item_html .= '</div>';
									endif;

									// Avatar
									$item_html .= '<div class="wps_activity_item_avatar" style="float: left; margin-left: -'.($avatar_size+10).'px">';
										$item_html .= user_avatar_get_avatar($item->post_author, $avatar_size);
									$item_html .= '</div>';

									// Meta
									$recipients = '';
									$item_html .= '<div class="wps_activity_item_meta">';
										$item_html .= wps_display_name(array('user_id'=>$item->post_author, 'link'=>$link));
										$target_ids = get_post_meta( $item->ID, 'wps_target', true );
										if (is_array($target_ids)):
											$c=0;
											$recipients = ' &rarr; ';
											foreach ($target_ids as $target_id):
												if ( $target_id != $item->post_author):
													if ($c) $recipients .= ', ';
													$recipients .= wps_display_name(array('user_id'=>$target_id, 'link'=>$link));
													$c++;
												endif;
											endforeach;	
										else:
											if ( $target_ids != $item->post_author):
												$recipient_display_name = wps_display_name(array('user_id'=>$target_ids, 'link'=>$link));
												if ($recipient_display_name):
													$recipients = ' &rarr; '.$recipient_display_name;
												endif;
											endif;
										endif;

										// In case of changes
										$recipients = apply_filters( 'wps_activity_item_recipients_filter', $recipients, $atts, $target_ids, $item->ID, $user_id, $current_user->ID );
										$item_html .= $recipients;

										// Date
										$item_html .= '<br />';
										$item_html .= '<div class="wps_ago">'.sprintf($date_format, human_time_diff(strtotime($item->post_modified), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN).'</div>';

									$item_html .= '</div>';

									// Post
									$post_words = wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($item->post_title)))));

							        $words = explode(' ', $post_words, $more + 1);
							        if (count($words)> $more) {
							            array_pop($words);
							            array_push($words, '... [<a class="activity_item_more" rel="'.$i['ID'].'" title="'.$more_label.'" href="javascript:void(0)">'.$more_label.'</a>]');
										$item_html .= '<div style="display:none" id="activity_item_full_'.$i['ID'].'">'.str_replace(': ', '<br />', $post_words).'</div>';
							            $post_words = implode(' ', $words);
							        }									
									$p = str_replace(': ', '<br />', $post_words);
									$item_html .= '<div id="activity_item_'.$i['ID'].'">'.$p.'</div>';

									// Filter for handling anything else
									// Passes $item_html, shortcodes options ($atts), current post ID ($item->ID), post title ($item->post_stitle), user page ($user_id), current users ID ($current_user->ID)
									$item_html = apply_filters( 'wps_activity_item_filter', $item_html, $atts, $item->ID, $item->post_title, $user_id, $current_user->ID );

									// Existing Comments
									$args = array(
										'post_id' => $item->ID,
									    'orderby' => 'ID',
									    'order' => 'ASC',
									);
									$comments = get_comments($args);
									if ($comments) {
										foreach($comments as $comment) :
											$item_html .= '<div id="wps_comment_'.$comment->comment_ID.'" class="wps_activity_comment" style="position:relative;padding-left: '.($comment_avatar_size+10).'px">';

												// Settings
												if ($comment->user_id == $current_user->ID || current_user_can('manage_options')):
													$item_html .= '<div class="wps_comment_settings" style="display:none">';
														$item_html .= '<img style="height:15px;width:15px;" src="'.plugins_url('images/wrench'.get_option('wpspro_icon_colors').'.svg', __FILE__).'" />';
													$item_html .= '</div>';
													$item_html .= '<div class="wps_comment_settings_options" style="display:none">';
														$item_html .= '<a class="wps_comment_settings_delete" rel="'.$comment->comment_ID.'" href="javascript:void(0);">'.__('Delete comment', WPS2_TEXT_DOMAIN).'</a>';
													$item_html .= '</div>';
												endif;

												// Avatar
												$item_html .= '<div class="wps_activity_post_comment_avatar" style="float:left; margin-left: -'.($comment_avatar_size+10).'px">';
													$item_html .= user_avatar_get_avatar($comment->user_id, $comment_avatar_size);
												$item_html .= '</div>';

												// The comment
												$item_html .= wps_display_name(array('user_id'=>$comment->user_id, 'link'=>$link));
												$item_html .= '<br />';
												$item_html .= '<div class="wps_ago">'.sprintf($date_format, human_time_diff(strtotime($comment->comment_date), current_time('timestamp', 1)), WPS2_TEXT_DOMAIN).'</div>';
												$item_html .= wps_bbcode_replace(convert_smilies(make_clickable(wpautop(esc_html($comment->comment_content)))));

											$item_html .= '</div>';
										endforeach;
									}

								$item_html .= '</div>';

								// Add new comment	
								if ($allow_replies):						
									$item_html .= '<div class="wps_activity_post_comment_div">';
										$item_html .= '<input type="hidden" id="wps_activity_plugins_url" value="'.plugins_url( '', __FILE__ ).'" />';
										$item_html .= '<textarea class="wps_activity_post_comment" id="post_comment_'.$item->ID.'"></textarea>';
										$item_html .= '<input class="wps_activity_post_comment_button '.$class.'" rel="'.$item->ID.'" type="submit" value="'.$label.'" />';
									$item_html .= '</div>';
								endif;

							$item_html .= '</div>';

							$items .= $item_html;

							$shown_count++;
							if ($shown_count == $count) break;

						endif;

					endforeach;

					$html .= $items;

				$html .= '</div>';

			endif;

		else:

			$html .= '<div id="wps_activity_items">';
			$html .= $private_msg;
			$html .= '</div>';

		endif;

	}

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;
}
if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-activity-page', 'wps_activity_page');
	add_shortcode(WPS_PREFIX.'-activity-post', 'wps_activity_post');
	add_shortcode(WPS_PREFIX.'-activity', 'wps_activity');
}



?>
