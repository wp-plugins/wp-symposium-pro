<?php 
																	/* ************* */
																	/* HOOKS/FILTERS */
																	/* ************* */


// Updates last active date via wp_footer which every theme should have
function wps_update_last_active() {
    global $current_user;
    update_user_meta($current_user->ID, 'wpspro_last_active', current_time('mysql'));
}
if (!is_admin()) add_action('wp_footer', 'wps_update_last_active');																	


																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_display_name($atts) {

	extract( shortcode_atts( array(
		'user_id' 	=> false,
		'link'		=> false,
		'before'	=> '',
		'after'		=> '',
	), $atts, 'wps_display_name' ) );

	if (!$user_id) $user_id = wps_get_user_id();

	$user = get_user_by('id', $user_id);
	$html = '';

	if ($user):

		if (get_option('wpspro_profile_page')):

			if ($link):
				if ( get_option( 'permalink_structure' ) ):
					$url = get_page_link(get_option('wpspro_profile_page'));
					$html = '<a href="'.$url.$user->user_login.'">'.$user->display_name.'</a>';
				else:
					$url = get_page_link(get_option('wpspro_profile_page'));
					$html = '<a href="'.$url.wps_query_mark($url).'user_id='.$user_id.'">'.$user->display_name.'</a>';
				endif;
			else:
				$html = $user->display_name;
			endif;

		else:
			$html = $user->display_name;
		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
	return $html;

}
add_shortcode('wps-display-name', 'wps_display_name');


																	/* ********* */
																	/* FUNCTIONS */
																	/* ********* */

// How long ago as text
function wpspro__time_ago($date,$granularity=1) {
	
	$retval = '';
    $date = strtotime($date);
    $difference = (time() - $date) + 1;
    $periods = array(__('decade', WPS2_TEXT_DOMAIN) => 315360000,
        'year' => 31536000,
        'month' => 2628000,
        'week' => 604800, 
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1);

	if ($difference > 315360000) {

	    $return = sprintf (__('a while ago', WPS2_TEXT_DOMAIN), $retval);
		
	} else {
		
		if ($difference < 1) {
			
		    $return = sprintf (__('just now', WPS2_TEXT_DOMAIN), $retval);
		    
		} else {
                                 
		    foreach ($periods as $key => $value) {
		        if ($difference >= $value) {
		            $time = floor($difference/$value);
		            $difference %= $value;
		            $retval .= ($retval ? ' ' : '').$time.' ';
		            $key = (($time > 1) ? $key.'s' : $key);
		            if ($key == 'year') { $key = __('year', WPS2_TEXT_DOMAIN); }
		            if ($key == 'years') { $key = __('years', WPS2_TEXT_DOMAIN); }
		            if ($key == 'month') { $key = __('month', WPS2_TEXT_DOMAIN); }
		            if ($key == 'months') { $key = __('months', WPS2_TEXT_DOMAIN); }
		            if ($key == 'week') { $key = __('week', WPS2_TEXT_DOMAIN); }
		            if ($key == 'weeks') { $key = __('weeks', WPS2_TEXT_DOMAIN); }
		            if ($key == 'day') { $key = __('day', WPS2_TEXT_DOMAIN); }
		            if ($key == 'days') { $key = __('days', WPS2_TEXT_DOMAIN); }
		            if ($key == 'hour') { $key = __('hour', WPS2_TEXT_DOMAIN); }
		            if ($key == 'hours') { $key = __('hours', WPS2_TEXT_DOMAIN); }
		            if ($key == 'minute') { $key = __('minute', WPS2_TEXT_DOMAIN); }
		            if ($key == 'minutes') { $key = __('minutes', WPS2_TEXT_DOMAIN); }
		            if ($key == 'second') { $key = __('second', WPS2_TEXT_DOMAIN); }
		            if ($key == 'seconds') { $key = __('seconds', WPS2_TEXT_DOMAIN); }
		            $retval .= $key;
		            $granularity--;
		        }
		        if ($granularity == '0') { break; }
		    }

		    $return = sprintf (__('%s ago', WPS2_TEXT_DOMAIN), $retval);
		    
		}
    

	}
    return $return;

}

// Cut to number of words
function wps_get_words($text, $words, $more='...') {
	$array = explode(" ", $text, $words+1);
	if (count($array) > $words):
		unset($array[$words]);
		$text = implode(" ", $array).' '.$more;
	else:
		$text = implode(" ", $array);
	endif;
	return $text;
}


// Display array contents (for debugging only)
function wps_display_array($arrayname,$tab="&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp",$indent=0) {

 $curtab ="";
 $returnvalues = "";
 while(list($key, $value) = each($arrayname)) {
  for($i=0; $i<$indent; $i++) {
   $curtab .= $tab;
   }
  if (is_array($value)) {
   $returnvalues .= "$curtab$key : Array: <br />$curtab{<br />\n";
   $returnvalues .= wps_display_array($value,$tab,$indent+1)."$curtab}<br />\n";
   }
  else $returnvalues .= "$curtab$key => $value<br />\n";
  $curtab = NULL;
  }
 return $returnvalues;
}

// Get current URL (without parameters)
function wps_curPageURL() {
 	$pageURL = 'http';
 	if (isset($_SERVER["HTTPS"])) { $pageURL .= "s"; }
 	$pageURL .= "://";
 	if ($_SERVER["SERVER_PORT"] != "80") {
  		$pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER['REQUEST_URI'];
 	} else {
  		$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];
 	}
 	return $pageURL;
}

// Permalinks or not?
function wps_query_mark($url) {
	if ($url):
		$q = (strpos($url, '?') !== FALSE) ? '&' : '?';
		return $q;
	else:
		return $url;
	endif;
}

// BB Code rules
function wps_bbcode_replace($text_to_search) {

	$text_to_search = str_replace('http://youtu.be/', 'http://www.youtube.com/watch?v=', $text_to_search);

	$search = array(
	        '@\[(?i)quote\](.*?)\[/(?i)quote\]@si',
	        '@\[(?i)list\](.*?)\[/(?i)list\]@si',
	        '@\[(?i)ul\](.*?)\[/(?i)ul\]@si',
	        '@\[(?i)ol\](.*?)\[/(?i)ol\]@si',
	        '@\[(?i)li\](.*?)\[/(?i)li\]@si',
	        '@\[(?i)\*\](.*?)<br \/>@si',
	        '@\[(?i)b\](.*?)\[/(?i)b\]@si',
	        '@\[(?i)i\](.*?)\[/(?i)i\]@si',
	        '@\[(?i)s\](.*?)\[/(?i)s\]@si',
	        '@\[(?i)u\](.*?)\[/(?i)u\]@si',
	        '@\[(?i)img\](.*?)\[/(?i)img\]@si',
	        '@\[(?i)url\](.*?)\[/(?i)url\]@si',
	        '@\[(?i)url=(.*?)\](.*?)\[/(?i)url\]@si',
	        '@\[(?i)code\](.*?)\[/(?i)code\]@si',
			'@\[youtube\].*?(?:v=)?([^?&[]+)(&[^[]*)?\[/youtube\]@is',
	        '@\[(?i)map\](.*?)\[/(?i)map\]@si',
	        '@\[(?i)map zoom=(.*?)\](.*?)\[/(?i)map\]@si'
	);
	$search = apply_filters( 'wps_bbcode_search_filter', $search );

	$replace = array(
	        '<div class="wps_bbcode_quote">\\1</div>',
	        '</p><ul class="wps_bbcode_list">\\1</ul><p>',
	        '</p><ul class="wps_bbcode_list">\\1</ul><p>',
	        '</p><ol class="wps_bbcode_list">\\1</ol><p>',
	        '<li>\\1</li>',
	        '<li>\\1</li>',
	        '<strong>\\1</strong>',
	        '<em>\\1</em>',
	        '<s>\\1</s>',
	        '<u>\\1</u>',
	        '<img src="\\1">',
	        '<a href="\\1">\\1</a>',
	        '<a href="\\1">\\2</a>',
	        '<div class="wps_bbcode_code">\\1</div>',
	        '<iframe title="YouTube video player" width="475" height="290" src="http://www.youtube.com/embed/\\1" frameborder="0" allowfullscreen></iframe>',
	        '<a target="_blank" href="https://www.google.com/maps/preview?q=\\1"><img src="http://maps.google.com/maps/api/staticmap?center=\\1&zoom=11&size=400x200&maptype=roadmap&markers=color:ORANGE|label:A|\\1&sensor=false"></a>',
	        '<a target="_blank" href="https://www.google.com/maps/preview?q=\\2"><img src="http://maps.google.com/maps/api/staticmap?center=\\2&zoom=\\1&size=400x200&maptype=roadmap&markers=color:ORANGE|label:A|\\2&sensor=false"></a>'
	);
	$search = apply_filters( 'wps_bbcode_replace_filter', $search );

	$r = preg_replace($search, $replace, $text_to_search);

   	return $r;

}
																	/* ********* */
																	/* FUNCTIONS */
																	/* ********* */

function wps_get_user_id() {

	global $current_user;
	if (get_query_var('user')):
		$username = get_query_var('user');
		$get_user = get_user_by('login', $username);
		$user_id = $get_user->ID;
	else:
		if (isset($_GET['user_id'])):
			$user_id = $_GET['user_id'];
		else:
			$user_id = $current_user->ID;
		endif;
	endif;
	return $user_id;

}

// Automatically close comments older than a certain number of days based
// on setting in admin panel for discussion
function wps_forum_close_comments( $posts ) {
	
	if (sizeof($posts) == 1) {

		if ( 'wps_forum_post' == get_post_type($posts[0]->ID) && $posts[0]->comment_status != 'closed') {

			if ( time() - strtotime( $posts[0]->post_date_gmt ) > ( get_option( 'close_comments_days_old' ) * 24 * 60 * 60 ) ) {

				if (!get_post_meta($posts[0]->ID, 'wps_reopened_date', true)):

					$posts[0]->comment_status = 'closed';
					$posts[0]->ping_status    = 'closed';
					wp_update_post( $posts[0] );

					$data = array(
					    'comment_post_ID' => $posts[0]->ID,
					    'comment_content' => __('Closed due to inactivity.', WPS2_TEXT_DOMAIN),
					    'comment_type' => '',
					    'comment_parent' => 0,
					    'comment_author' => 0,
					    'comment_author_email' => get_bloginfo('admin_email'),
					    'user_id' => 0,
					    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
					    'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
					    'comment_approved' => 1,
					);

					$new_id = wp_insert_comment($data);

					if ($new_id):

						// Any further actions?
						do_action( 'wps_forum_auto_close_hook', $posts[0]->ID );

					endif;

				endif;
			}
		}

	}
	return $posts;
}
add_action( 'the_posts', 'wps_forum_close_comments' );

// Checks if a user can see a forum category
function user_can_see_forum($user_id, $term_id) {

	global $current_user;
	$see = true;

	$public = wps_get_term_meta($term_id, 'wps_forum_public', true);

	// Any more checking?
	$see = apply_filters('user_can_see_forum_filter', $see, $user_id, $term_id);

	// Final check if not public and not logged in
	if ($public) $see = true;

	return $see;

}

// ****************** CORE ******************

// Print generic modal box for general use (wp_footer hook must exist in theme)
function wps_add_wait_modal_box() {
	echo '<div class="wps_wait_modal"></div>';
}

// Print internal CSS codes in the head section
function wps_add_custom_css() {
	$css = '';
	if ($value = stripslashes( get_option('wpspro_custom_css') )) $css .= $value;
	echo '<style>/* WP Symposium custom CSS */' . chr(13) . chr(10) . $css . '</style>';
}


