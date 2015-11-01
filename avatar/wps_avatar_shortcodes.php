<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_avatar_init() {
	wp_enqueue_script("thickbox");
	wp_enqueue_style("thickbox");
	wp_enqueue_script('wps-avatar-js', plugins_url('wps_avatar.js', __FILE__), array('jquery'));	
	wp_enqueue_style('user-avatar', plugins_url('user-avatar.css', __FILE__), 'css');
	wp_enqueue_style('imgareaselect');
	wp_enqueue_script('imgareaselect');
	// Anything else?
	do_action('wps_avatar_init_hook');
}

																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_avatar($atts) {

	// Init
	add_action('wp_footer', 'wps_avatar_init');

	global $current_user;
	$html = '';

	// Shortcode parameters
    $values = wps_get_shortcode_options('wps_avatar');  
	extract( shortcode_atts( array(
		'user_id' => false,
		'size' => wps_get_shortcode_value($values, 'wps_avatar-size', 256),
		'change_link' => wps_get_shortcode_value($values, 'wps_avatar-change_link', false),
        'profile_link' => wps_get_shortcode_value($values, 'wps_avatar-profile_link', false), // only if avatar is NOT current user
		'styles' => true,
        'after' => '',
		'before' => '',
	), $atts, 'wps_avatar' ) );

    if (!$user_id) $user_id = wps_get_user_id();

	$friends = wps_are_friends($current_user->ID, $user_id);
	// By default same user, and friends of user, can see profile
	$user_can_see_profile = ($current_user->ID == $user_id || $friends['status'] == 'publish') ? true : false;
	$user_can_see_profile = apply_filters( 'wps_check_profile_security_filter', $user_can_see_profile, $user_id, $current_user->ID );
	
	if ($user_can_see_profile):

		if ($user_id != $current_user->ID) {
            if ($profile_link)
                $html .= '<a href="'.get_page_link(get_option('wpspro_profile_page')).'?user_id='.$user_id.'">';
			$html .= user_avatar_get_avatar( $user_id, $size );
            if ($profile_link)
                $html .= '</a>';
		} else {
			$profile = get_user_by('id', $user_id);
			global $current_user;

			$html .= sprintf('<div class="wps_avatar" style="width: %dpx; height: %dpx;">', $size, $size);
			$html .= user_avatar_get_avatar( $user_id, $size );
			if ($change_link) $html .= '<a id="user-avatar-link" style="text-decoration: none;opacity:0.7;background-color: #000; color:#fff !important; padding: 3px 8px 3px 8px; position:absolute; bottom:18px; left: 10px;" href="'.get_page_link(get_option('wpspro_change_avatar_page')).'?user_id='.$user_id.'&action=change_avatar" title="'.__('Upload and Crop an Image to be Displayed', WPS2_TEXT_DOMAIN).'" >'.__('Change Picture', WPS2_TEXT_DOMAIN).'</a>';
			$html .= '</div>';
		}

        if ($html) $html = apply_filters ('wps_wrap_shortcode_styles_filter', $html, 'wps_avatar', $before, $after, $styles, $values, $size, $size);

	endif;

	return $html;

}


function wps_avatar_change_link($atts) {

	// Init
	add_action('wp_footer', 'wps_avatar_init');

	global $current_user;
	$html = '';

	if (is_user_logged_in()) {
        
        // Shortcode parameters
		$values = wps_get_shortcode_options('wps_avatar_change_link');
		extract( shortcode_atts( array(
			'text' => wps_get_shortcode_value($values, 'wps_avatar_change_link-text', __('Change Picture', WPS2_TEXT_DOMAIN)),
			'styles' => true,
            'after' => '',
			'before' => '',
		), $atts, 'wps_avatar_change' ) );

		$user_id = wps_get_user_id();

		if ($current_user->ID == $user_id)
			$html .= '<a href="'.get_page_link(get_option('wpspro_change_avatar_page')).'?user_id='.$user_id.'">'.$text.'</a>';

	}

	if ($html) $html = apply_filters ('wps_wrap_shortcode_styles_filter', $html, 'wps_avatar_change_link', $before, $after, $styles, $values);
	return $html;

}

function wps_avatar_change($atts) {

	// Init
	add_action('wp_footer', 'wps_avatar_init');

	global $current_user;
	$html = '';

    // Shortcode parameters
    $values = wps_get_shortcode_options('wps_avatar_change');
    extract( shortcode_atts( array(
        'label' => wps_get_shortcode_value($values, 'wps_avatar_change-label', __('Upload', WPS2_TEXT_DOMAIN)),
        'choose' => wps_get_shortcode_value($values, 'wps_avatar_change-choose', __('Click here to choose an image...', WPS2_TEXT_DOMAIN)),
        'try_again_msg' => wps_get_shortcode_value($values, 'wps_avatar_change-try_again_msg', __('Try again...', WPS2_TEXT_DOMAIN)),
        'file_types_msg' => wps_get_shortcode_value($values, 'wps_avatar_change-file_types_msg', __("Please upload an image file (.jpeg, .gif, .png).", WPS2_TEXT_DOMAIN)),
        'not_permitted' => wps_get_shortcode_value($values, 'wps_avatar_change-not_permitted', __('You are not allowed to change this avatar.', WPS2_TEXT_DOMAIN)),
        'crop' => wps_get_shortcode_value($values, 'wps_avatar_change-crop', true),
        'logged_out_msg' => wps_get_shortcode_value($values, 'wps_avatar_change-logged_out_msg', __('You must be logged in to view this page.', WPS2_TEXT_DOMAIN)),
        'login_url' => wps_get_shortcode_value($values, 'wps_avatar_change-login_url', ''),
        'styles' => true,
    ), $atts, 'wps_avatar_change' ) );
    
	if (is_user_logged_in()):

		if (isset($_POST['user_id'])):
			$user_id = $_POST['user_id'];
		else:
			$user_id = wps_get_user_id();
		endif;

        if (current_user_can('manage_options') && !$login_url && function_exists('wps_login_init')):
            $html = wps_admin_tip($html, 'wps_avatar_change', __('Add login_url="/example" to the [wps-avatar-change] shortcode to let users login and redirect back here when not logged in.', WPS2_TEXT_DOMAIN));
        endif;        
    
        $useragent=$_SERVER['HTTP_USER_AGENT'];

        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
            $crop = false;
        }

		if ($current_user->ID == $user_id || current_user_can('manage_options') || is_super_admin($current_user->ID) ):

			include_once ABSPATH . 'wp-admin/includes/media.php';
			include_once ABSPATH . 'wp-admin/includes/file.php';
			include_once ABSPATH . 'wp-admin/includes/image.php';

			if (!isset($_POST['wps_avatar_change_step'])):

				$html .= '<form enctype="multipart/form-data" id="avatarUploadForm" method="POST" action="#" >';
					$html .= '<input type="hidden" name="wps_avatar_change_step" value="2" />';
					$html .= '<input type="hidden" name="user_id" value="'.$user_id.'" />';
					$html .= '<input title="'.$choose.'" type="file" id="avatar_file_upload" name="uploadedfile" style="display:none" /><br /><br />';
					wp_nonce_field('user-avatar');
					$html .= '<button class="wps_submit">'.$label.'</button>';
// removed for button					$html .= '<input type="submit" class="wps_submit" value="'.$label.'" />';
				$html .= '</form>';

			elseif ($_POST['wps_avatar_change_step'] == '2' && $crop):

				if (!(($_FILES["uploadedfile"]["type"] == "image/gif") || ($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/png") || ($_FILES["uploadedfile"]["type"] == "image/pjpeg") || ($_FILES["uploadedfile"]["type"] == "image/x-png"))):
					
					$html .= "<div class='wps_error'>".$file_types_msg."</div>";
					$html .= "<p><a href=''>".$try_again_msg.'</a></p>';

				else:

					$overrides = array('test_form' => false);
					$file = wp_handle_upload($_FILES['uploadedfile'], $overrides);

					if ( isset($file['error']) ){
						die( $file['error'] );
					}
					
					$url = $file['url'];
					$type = $file['type'];
					$file = $file['file'];
					$filename = basename($file);
					
					set_transient( 'avatar_file_'.$user_id, $file, 60 * 60 * 5 );
					// Construct the object array
					$object = array(
					'post_title' => $filename,
					'post_content' => $url,
					'post_mime_type' => $type,
					'guid' => $url);

					// Save the data
					list($width, $height, $type, $attr) = getimagesize( $file );
					
					if ( $width > 420 ) {
						$oitar = $width / 420;
						$image = wp_crop_image($file, 0, 0, $width, $height, 420, $height / $oitar, false, str_replace(basename($file), 'midsize-'.basename($file), $file));

						$url = str_replace(basename($url), basename($image), $url);
						$width = $width / $oitar;
						$height = $height / $oitar;
					} else {
						$oitar = 1;
					}
					
					$html .= '<form id="iframe-crop-form" method="POST" action="#">';
					$html .= '<input type="hidden" name="wps_avatar_change_step" value="3" />';
					$html .= '<input type="hidden" name="user_id" value="'.$user_id.'" />';					

					$html .= '<div style="margin-bottom:20px">';
					$html .= '<img src="'.$url.'" id="wps_upload" width="'.esc_attr($width).'" height="'.esc_attr($height).'" />';
					$html .= '</div>';
					
					$html .= '<div id="wps_preview" style="float: left; width: 150px; height: 150px; overflow: hidden;">';
					$html .= '<img src="'.esc_url_raw($url).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" style="max-width:none" />';
					$html .= '</div>';
					
					$html .= '<input type="hidden" name="x1" id="x1" value="0" />';
					$html .= '<input type="hidden" name="y1" id="y1" value="0" />';
					$html .= '<input type="hidden" name="x2" id="x2" />';
					$html .= '<input type="hidden" name="y2" id="y2" />';
					$html .= '<input type="hidden" name="width" id="width" value="'.esc_attr($width).'" />';
					$html .= '<input type="hidden" name="height" id="height" value="'.esc_attr($height).'" />';
					$html .= '<input type="hidden" id="init_width" value="'.esc_attr($width).'" />';
					$html .= '<input type="hidden" id="init_height" value="'.esc_attr($height).'" />';
					
					$html .= '<input type="hidden" name="oitar" id="oitar" value="'.esc_attr($oitar).'" />';
					wp_nonce_field('user-avatar');
					$html .= '<button class="wps_submit" style="margin-left:20px;" id="user-avatar-crop-button">'.__('Crop', WPS2_TEXT_DOMAIN).'</button>';
// removed for button					$html .= '<input type="submit" class="wps_submit" style="margin-left:20px;" id="user-avatar-crop-button" value="'.__('Crop', WPS2_TEXT_DOMAIN).'" />';
					$html .= '</form>';

				endif;

			else:

                if (isset($_POST['oitar'])):
    
                    // Doing crop
    
                    if ( $_POST['oitar'] > 1 ):
                        $_POST['x1'] = $_POST['x1'] * $_POST['oitar'];
                        $_POST['y1'] = $_POST['y1'] * $_POST['oitar'];
                        $_POST['width'] = $_POST['width'] * $_POST['oitar'];
                        $_POST['height'] = $_POST['height'] * $_POST['oitar'];
                    endif;

                    $original_file = get_transient( 'avatar_file_'.$user_id );
                    delete_transient('avatar_file_'.$user_id );

                    if( !file_exists($original_file) ):

                        $html .= "<div class='error'><p>". __('Sorry, no file available', WPS2_TEXT_DOMAIN)."</p></div>";

                    else:

                        // Create avatar folder if not already existing
                        $continue = true;
                        if( !file_exists(WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/") ):
    
                            if (!mkdir(WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/", 0777 ,true)):
                                $error = error_get_last();
                                $html .= $error['message'].'<br />';
                                $html .= WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/<br>";
                                $continue = false;
                            else:
                                $path = WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/";
                                $cropped_full = $path.time()."-wpsfull.jpg";
                                $cropped_thumb = $path.time()."-wpsthumb.jpg";
                            endif;
                        else:
                            $cropped_full = user_avatar_core_avatar_upload_path($user_id).time()."-wpsfull.jpg";
                            $cropped_thumb = user_avatar_core_avatar_upload_path($user_id).time()."-wpsthumb.jpg";
                        endif;

                        if ($continue):

                            // delete the previous files
                            user_avatar_delete_files($user_id);
                            @mkdir(WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/", 0777 ,true);

                            if (!class_exists('SimpleImage')) require_once('SimpleImage.php');

                            // update the files 
                            $img = $original_file;
	                            $image = new SimpleImage();
	                            $image->load($img);
	                            $image->cut($_POST['x1'], $_POST['y1'], $_POST['width'], $_POST['height']);
	                            $image->save($cropped_full);
    
                            $img = $original_file;
	                            $image = new SimpleImage();
	                            $image->load($img);
	                            $image->cut($_POST['x1'], $_POST['y1'], $_POST['width'], $_POST['height']);
	                            $image->save($cropped_thumb);
    
                            if ( is_wp_error( $cropped_full ) ):
                                $html .= __( 'Image could not be processed. Please try again.', WPS2_TEXT_DOMAIN);	
                                var_dump($cropped_full);	
                            else:
                                /* Remove the original */
                                @unlink( $original_file );
                                $html .= '<script>window.location.replace("'.get_page_link(get_option('wpspro_profile_page')).'?user_id='.$user_id.'");</script>';
                            endif;

                        endif;

                    endif;
    
                else:
    
                    // Skip crop
    
					$overrides = array('test_form' => false);
					$file = wp_handle_upload($_FILES['uploadedfile'], $overrides);

					if ( isset($file['error']) ){
						die( $file['error'] );
					}

					$url = $file['url'];
					$type = $file['type'];
					$original_file = $file['file'];
					$filename = basename($original_file);

                    if( !file_exists($original_file) ):

                        $html .= "<div class='error'><p>". __('Sorry, no file available', WPS2_TEXT_DOMAIN)."</p></div>";

                    else:

                        // Create avatar folder if not already existing
                        $continue = true;
                        if( !file_exists(WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/") ):
                            if (!mkdir(WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/", 0777 ,true)):
                                $error = error_get_last();
                                $html .= $error['message'].'<br />';
                                $html .= WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/<br>";
                                $continue = false;
                            else:
                                $path = WP_CONTENT_DIR."/wps-pro-content/members/".$user_id."/avatar/";
                                $cropped_full = $path.time()."-wpsfull.jpg";
                                $cropped_thumb = $path.time()."-wpsthumb.jpg";
                            endif;
                        else:
                            $cropped_full = user_avatar_core_avatar_upload_path($user_id).time()."-wpsfull.jpg";
                            $cropped_thumb = user_avatar_core_avatar_upload_path($user_id).time()."-wpsthumb.jpg";
                        endif;

                        if ($continue):

                            // delete the previous files
                            user_avatar_delete_files($user_id);

                            // update the files 
                            list($width, $height, $type, $attr) = getimagesize( $original_file );    
                            $cropped_full = wp_crop_image( $original_file, 0, 0, $width, $height, 300, 300, false, $cropped_full );
                            $cropped_thumb = wp_crop_image( $original_file, 0, 0, $width, $height, 300, 300, false, $cropped_thumb );

                            if ( is_wp_error( $cropped_full ) ):
                                $html .= __( 'Image could not be processed. Please try again.', WPS2_TEXT_DOMAIN);	
                                var_dump($cropped_full);	
                            else:
                                /* Remove the original */
                                @unlink( $original_file );
                                $html .= '<script>window.location.replace("'.get_page_link(get_option('wpspro_profile_page')).'?user_id='.$user_id.'");</script>';
                            endif;

                        endif;

                    endif;
    
                endif;


			endif;

		else:

			$html .= $not_permitted;

		endif;

    else:

        if (!is_user_logged_in() && $logged_out_msg):
            $query = wps_query_mark(get_bloginfo('url').$login_url);
            if ($login_url) $html .= sprintf('<a href="%s%s%sredirect=%s">', get_bloginfo('url'), $login_url, $query, wps_root( $_SERVER['REQUEST_URI'] ));
            $html .= $logged_out_msg;
            if ($login_url) $html .= '</a>';
        endif;
    
    endif;

    if ($html) $html = apply_filters ('wps_wrap_shortcode_styles_filter', $html, 'wps_avatar_change', '', '', $styles, $values);
    
	return $html;
}



add_shortcode(WPS_PREFIX.'-avatar', 'wps_avatar');
add_shortcode(WPS_PREFIX.'-avatar-change-link', 'wps_avatar_change_link');
add_shortcode(WPS_PREFIX.'-avatar-change', 'wps_avatar_change');
?>