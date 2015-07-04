<?php 
																	/* ************* */
																	/* HOOKS/FILTERS */
																	/* ************* */


// Updates last active date via wp_footer hook which every theme should have
function wps_update_last_active() {
    global $current_user;
    update_user_meta($current_user->ID, 'wpspro_last_active', current_time('mysql', 1));
}
if (!is_admin()) add_action('wp_footer', 'wps_update_last_active');		

// Exclude WPS Forum replies and activity comments from Recent Comments widget
function wps_filter_recent_comments( $array ) {

	if (!get_option('wps_filter_recent_comments')):
		$array = array(
		    'parent' => 0,  
		    'post_type' => 'post',
		    'number' => $array['number'],
		    'status' => $array['status'],
		    'post_status' => $array['post_status']
		);
	endif;
	return $array;
}
add_filter( 'widget_comments_args', 'wps_filter_recent_comments' );


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
				if (function_exists('icl_link_to_element')):
					$icl_object_id = icl_object_id(get_option('wpspro_profile_page'), 'page', true);
					$url = get_permalink($icl_object_id);
					$html = '<a href="'.$url.wps_query_mark($url).'user_id='.$user_id.'">'.$user->display_name.'</a>';
				elseif (get_option('wpspro_profile_permalinks')):
					$url = get_page_link(get_option('wpspro_profile_page'));
					$html = '<a href="'.$url.wps_query_mark($url).'user_id='.$user_id.'">'.$user->display_name.'</a>';
				else:
					$url = get_page_link(get_option('wpspro_profile_page'));
					if ( wps_using_permalinks() ):
						$html = '<a href="'.$url.urlencode($user->user_login).'">'.$user->display_name.'</a>';
					else:
						$html = '<a href="'.$url.wps_query_mark($url).'user_id='.$user_id.'">'.$user->display_name.'</a>';
					endif;
				endif;
			else:
				$html = $user->display_name;
			endif;

		else:
			$html = $user->display_name;
		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);
    
    $html = apply_filters( 'wps_display_name', $html );
	return $html;

}
add_shortcode('wps-display-name', 'wps_display_name');


																	/* ********* */
																	/* FUNCTIONS */
																	/* ********* */

// Get link to a user's profile page
function wps_profile_link($user_id) {
    
    if (!$user_id) $user_id = wps_get_user_id();
    $user = get_user_by('id', $user_id);
    $url = false;
    
    if ($user) {
    
        if (function_exists('icl_link_to_element')):
            $icl_object_id = icl_object_id(get_option('wpspro_profile_page'), 'page', true);
            $url = get_permalink($icl_object_id);
            $url = $url.wps_query_mark($url).'user_id='.$user_id;
        elseif (get_option('wpspro_profile_permalinks')):
            $url = get_page_link(get_option('wpspro_profile_page'));
            $url = $url.wps_query_mark($url).'user_id='.$user_id;
        else:
            $url = get_page_link(get_option('wpspro_profile_page'));
            if ( wps_using_permalinks() ):
                $url = $url.urlencode($user->user_login);
            else:
                $url = $url.wps_query_mark($url).'user_id='.$user_id;
            endif;
        endif;    
        
    }
    
    return $url;
}

// Is account closed?
function wps_is_account_closed($user_id) {    
    return get_user_meta($user_id, 'wps_account_closed', true);
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
  if (is_array($value) && strpos($value, $search) !== false) {
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
	        '@\[(?i)center\](.*?)\[/(?i)center\]@si',
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
	        '<div class="wps_bbcode_center">\\1</div>',
	        '<li class="wps_bbcode_list_item">\\1</li>',
	        '<li class="wps_bbcode_list_item">\\1</li>',
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

function wps_make_clickable($text) {

    $internal_link = strpos($text, get_bloginfo('url')) ? 1 : 0;
    $text = make_clickable($text);
    $suffix = get_option('wps_external_links');
    
    if ($suffix && !$internal_link):
    
        $text = str_replace('<a ', '<a class="wps_external_link" target="_blank" ', $text);
        if ($suffix != '-') $text = str_replace('</a>', '</a> '.$suffix, $text);
    
    endif;

    return $text;
}

function wps_get_user_id() {

	global $current_user;
	if (get_query_var('user')):
		$username = get_query_var('user');
		$get_user = get_user_by('login', urldecode($username));
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

// Automatically close forum comments older than a certain number of days based
// on setting in admin panel for discussion
function wps_forum_close_comments( $posts ) {
	
	if (sizeof($posts) == 1):

		if ( 'wps_forum_post' == get_post_type($posts[0]->ID) && $posts[0]->comment_status != 'closed'):

			$wps_forum_auto_close = get_option( 'wps_forum_auto_close' );
			if ($wps_forum_auto_close):

				$passed_time = time() - strtotime( $posts[0]->post_date_gmt ) > ( $wps_forum_auto_close * 24 * 60 * 60 );
				if ( $passed_time ):

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
						    'comment_author_email' => '',
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
				
				endif;

			endif;
		
		endif;

	endif;

	return $posts;
}
add_action( 'the_posts', 'wps_forum_close_comments' );

// Checks if a user can see a forum category
function user_can_see_forum($user_id, $term_id) {

	global $current_user;
	$see = false;

	// If public, or logged in, can see
	$public = wps_get_term_meta($term_id, 'wps_forum_public', true);
	if ($public || is_user_logged_in()) $see = true;

	// Any more checking?
	$see = apply_filters('user_can_see_forum_filter', $see, $user_id, $term_id);

	// Final check if logged in only forum
	if (!$public && !is_user_logged_in()) $see = false;

	// Admin can always see
	if (current_user_can('manage_options')) $see = true;

	return $see;

}

// Checks if a user can see a post (ie. author/admin only?)
function user_can_see_post($user_id, $post_id) {

	$user_can_see = true;
	$post_terms = get_the_terms( $post_id, 'wps_forum' );
	if( $post_terms && !is_wp_error( $post_terms ) ):

		foreach( $post_terms as $term ):

			if (user_can_see_forum($user_id, $term->term_id)):

				$author = wps_get_term_meta($term->term_id, 'wps_forum_author', true);
				$the_post = get_post($post_id);
				if ($author && ($user_id != $the_post->post_author && !current_user_can('manage_options'))):
					$user_can_see = false;
				endif;

			else:

				$user_can_see = false;

			endif;

		endforeach;

	endif;

	return $user_can_see;

}

// See if WordPress using permalinks (or using root of multisite)
function wps_using_permalinks() {

	if (!get_option( 'permalink_structure' ))
		return false;

	if (is_multisite()):
        $current_blog = get_current_blog_id();
        if ($current_blog > 1 || is_admin()):
        	return true;
        else:
        	return false;
        endif;
    else:
    	return true;
    endif;

}

// Modifed strip_tags
if (!function_exists('wps_strip_tags')) {
	function wps_strip_tags($content) {

		$allowedtags = array(
		    'a' => array(
		        'href' => true,
		        'title' => true,
		    ),
		    'abbr' => array(
		        'title' => true,
		    ),
		    'acronym' => array(
		        'title' => true,
		    ),
		    'b' => array(),
		    'blockquote' => array(
		        'cite' => true,
		    ),
		    'br' => array(),
		    'cite' => array(),
		    'code' => array(),
		    'del' => array(
		        'datetime' => true,
		    ),
		    'div' => array(),
		    'em' => array(),
		    'h1' => array(),
		    'h2' => array(),
		    'h3' => array(),
		    'h4' => array(),
		    'h5' => array(),
		    'hr' => array(),
		    'i' => array(),
		    'li' => array(),
		    'ol' => array(),
		    'p' => array(
		        'style' => true,
		    ),
		    'pre' => array(),
		    'q' => array(
		        'cite' => true,
		    ),
		    'span' => array(
		        'style' => true,
		    ),
		    'strike' => array(),
		    'strong' => array(),
		    'table' => array(),
		    'tr' => array(),
		    'td' => array(),
		    'ul' => array(),
		);

		if (!get_option('wps_core_options_strip')):
		    $content = strip_tags($content, '<h1><h2><h3><h4><h5><p><a><ul><ol><li><div><table><tr><td><img><br><strong><em><strike><del><span><pre><blockquote><hr><code>');  
		else:
			$content = wp_kses($content, $allowedtags);
		endif;

		return $content;
	}
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

// Dismiss an admin tip
function wps_admin_tip($html, $tip, $msg) {
    if (isset($_GET['dismiss']) && $_GET['dismiss'] == $tip):
        $dismissed = get_option('wps_admin_tips');
        if (!$dismissed):
            $dismissed = array($tip);
        else:
            if (!in_array($tip, $dismissed)) array_push($dismissed, $tip);
        endif;
        update_option('wps_admin_tips', $dismissed);
    else:
        $dismissed = get_option('wps_admin_tips');
        if (!$dismissed || !in_array($tip, $dismissed)):
            $url = wps_curPageURL();
            $url .= wps_query_mark($url).'dismiss='.$tip;
            $html .= '<div class="wps_admin_tip">';
            $html .= '<div style="font-size: 0.8em; float:right; margin-left: 20px; margin-bottom; 5px;"><a href="'.$url.'">'.__('Dismiss Tip', WPS2_TEXT_DOMAIN).'</a></div>';
            $html .= __('Tip', WPS2_TEXT_DOMAIN).': '.__($msg, WPS2_TEXT_DOMAIN);
            $html .= '<div style="font-style:italic; margin-top: 15px; font-size:0.8em;">'.sprintf(__('This tip is only visible to site administrators and if dismissed can be re-activated via WPS Pro->Setup-><a href="%s">Core Options</a>.', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wps_pro_setup&wps_expand=wps_admin_getting_started_core#core' )).'</div>';
            $html .= '</div>';
        endif;
    endif;
    return $html;
}

// Global function for site URL, so that it can be filtered by others
function wps_root($url) {
    $url = site_url($url);
    $url = apply_filters( 'wps_pro_root_filter', $url );
	return $url;
}

// Get shortcode options from setup
function wps_get_shortcode_options($function) {    
    return get_option('wps_shortcode_options_'.$function) ? get_option('wps_shortcode_options_'.$function) : array();
}

// Get shortcode value from setup
function wps_get_shortcode_value($values, $name, $default) {
    
    // Remove function if passed in format function-option
    if (strpos($name, '-')):
        $arr = explode('-',$name);
        $name = $arr[1];
    endif;
    
    if ($default === false || $default === true) {
        $v = isset($values[$name]) && $values[$name] ? $values[$name] : false;
        if ($v) {
            $v = $v == 'on' ? true : false; 
        } else {
            $v = $default;
        }
    } else {
        $v = isset($values[$name]) && $values[$name] ? $values[$name] : $default;
    }
    return htmlspecialchars_decode($v);
}

// Wrapper for all returned HTML from shortodes, including optional admin styling
add_filter( 'wps_wrap_shortcode_styles_filter', 'wps_wrap_shortcode_styles', 10, 8 );
function wps_wrap_shortcode_styles($html, $function, $before, $after, $styles, $values, $width_force=false, $height_force=false) {
    
    if ($styles):
    
        $margin_top = wps_get_shortcode_value($values, $function.'-margin_top', 0);
        $margin_bottom = wps_get_shortcode_value($values, $function.'-margin_bottom', 0);
        $margin_left = wps_get_shortcode_value($values, $function.'-margin_left', 0);
        $margin_right = wps_get_shortcode_value($values, $function.'-margin_right', 0);
        $padding_top = wps_get_shortcode_value($values, $function.'-padding_top', 0);
        $padding_bottom = wps_get_shortcode_value($values, $function.'-padding_bottom', 0);
        $padding_left = wps_get_shortcode_value($values, $function.'-padding_left', 0);
        $padding_right = wps_get_shortcode_value($values, $function.'-padding_right', 0);
        $clear = wps_get_shortcode_value($values, $function.'-clear', false);
        $border_size = wps_get_shortcode_value($values, $function.'-border_size', 0);
        $border_color = wps_get_shortcode_value($values, $function.'-border_color', '#000');
        $border_radius = wps_get_shortcode_value($values, $function.'-border_radius', 0);
        $border_style = wps_get_shortcode_value($values, $function.'-border_style', 'solid');
        $background_color = wps_get_shortcode_value($values, $function.'-background_color', 'transparent');
        $style_width = 'width:'.wps_get_shortcode_value($values, $function.'-style_width', '100%');
        $style_height = wps_get_shortcode_value($values, $function.'-style_height', '');

        $style_width = $width_force ? 'width:'.($width_force+($border_size*2)).'px;' : $style_width;
        $style_height = $height_force ? 'height:'.($height_force+($border_size*2)).'px;' : $style_height;

        $div_top = '<div style="overflow:hidden;'.$style_width.$style_height.';clear:'.($clear ? 'both' : 'none').';background-color:'.$background_color.'; 
        margin-top:'.$margin_top.'px; margin-bottom:'.$margin_bottom.'px; margin-left:'.$margin_left.'px; margin-right:'.$margin_right.'px; 
        padding-top:'.$padding_top.'px; padding-bottom:'.$padding_bottom.'px; padding-left:'.$padding_left.'px; padding-right:'.$padding_right.'px; 
        border:'.$border_size.'px '.$border_style.' '.$border_color.'; border-radius:'.$border_radius.'px;">';
        $div_bottom = '</div>';

        $html = $div_top.htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after).$div_bottom;
    
    endif;
    
    return $html;
}

function wps_licence_code() {
	
	global $current_user;

	if (is_user_logged_in()) {

		$saved_code = get_option('wps_licence_code') ? get_option('wps_licence_code') : false;

		if (substr($saved_code,0,3) == 'vip' || substr($saved_code,0,3) == 'wps') {
			$days = 365;
			if (substr($saved_code,0,3) == 'vip') {
				$text = __('This is a lifetime WP Symposium Pro Extensions plugin licence.', WPS2_TEXT_DOMAIN);
			} else {
				$text = __('This is a temporary WP Symposium Pro Extensions plugin licence, it should not be used permenantly.', WPS2_TEXT_DOMAIN);
			}
			
		} else {

			if ($saved_code) {
	        
		        $code = preg_replace('#[^0-9]#','',$saved_code);
		        $diff = $code - time();
			
				$days = floor($diff / (60*60*24));
				$diff = $diff - ($days*60*60*24);
				
				$hours = floor($diff / (60*60));
				$diff = $diff - ($hours*60*60);
			
				$minutes = floor($diff / (60));
				$seconds = $diff - ($minutes*60);
				
				$minutes = $minutes > 9 ? $minutes : '0'.(string)$minutes;
				$seconds = $seconds > 9 ? $seconds : '0'.(string)$seconds;

				if ($days > 0 && $days < 366) {
					$text = sprintf(__('Your current WP Symposium Pro Extensions plugin licence expires at %s on %s', WPS2_TEXT_DOMAIN), @date('H:i', $code), @date('l d F Y', $code)).' ('.sprintf(__('%d days', WPS2_TEXT_DOMAIN), $days).')'.', '.sprintf(__('<a href="%s" target="_new">update your licence code</a> before it runs out.', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/licence-code');
				} else {
					$days = 0;
					$text = __('<strong>Invalid WP Symposium Pro Extensions plugin licence</strong> - <a href="http://www.wpsymposiumpro.com/licence-code" target="_new">please get a valid licence code</a>', WPS2_TEXT_DOMAIN);
				}

			} else {
				$days = 0;
				$text = __('<strong>No WP Symposium Pro Extensions plugin licence entered</strong> - <a href="http://www.wpsymposiumpro.com/licence-code" target="_new">click here to get your licence code</a>', WPS2_TEXT_DOMAIN);
			}
			
		}

		if ($days>0) {
			$text = '<p>'.$text.'</p>';
		} else {
			$text = '<p class="wps_error">'.$text.'</p>';
		}

	} else {
		$days = 0;
		$text = '';
		$saved_code = '';
	}

	return array($days, $text, $saved_code);
}

?>