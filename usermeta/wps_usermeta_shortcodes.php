<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_usermeta_init() {
    
    $tabs_array = get_option('wps_profile_tabs');
    $wps_profile_tab_animation = (isset($tabs_array['wps_profile_tab_animation'])) ? $tabs_array['wps_profile_tab_animation'] : 'slide';
    
	// JS and CSS
	wp_enqueue_style('wps-usermeta-css', plugins_url('wps_usermeta.css', __FILE__), 'css');
	wp_enqueue_script('wps-usermeta-js', plugins_url('wps_usermeta.js', __FILE__), array('jquery'));	
	wp_localize_script('wps-usermeta-js', 'wps_usermeta', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'animation' => $wps_profile_tab_animation ));    	
	// Anything else?
	do_action('wps_usermeta_init_hook');

}

function wps_add_tab_css(){    
    
    $tabs_array = get_option('wps_profile_tabs');
    $wps_profile_tab_active_color = (isset($tabs_array['wps_profile_tab_active_color'])) ? $tabs_array['wps_profile_tab_active_color'] : '#fff';
    $wps_profile_tab_inactive_color = (isset($tabs_array['wps_profile_tab_inactive_color'])) ? $tabs_array['wps_profile_tab_inactive_color'] : '#d2d2d2';
    $wps_profile_tab_active_text_color = (isset($tabs_array['wps_profile_tab_active_text_color'])) ? $tabs_array['wps_profile_tab_active_text_color'] : '#000';
    $wps_profile_tab_inactive_text_color = (isset($tabs_array['wps_profile_tab_inactive_text_color'])) ? $tabs_array['wps_profile_tab_inactive_text_color'] : '#000';

    echo '<style>';
    
    echo '.wps-tab-links a:hover {';
    echo 'background-color:'.$wps_profile_tab_active_color.';';
    echo 'color:'.$wps_profile_tab_active_text_color.';';    
    echo 'border-bottom: 1px solid '.$wps_profile_tab_inactive_color.';';
    echo '}';

    echo '.wps-tab-links li.active a:hover {';
    echo 'border-bottom: 1px solid transparent;';
    echo '}';
    
    echo '.wps-tab-content {';
    echo 'background-color:'.$wps_profile_tab_active_color.';';
    echo 'border: 1px solid '.$wps_profile_tab_inactive_color.';';
    echo '}';
    
    echo '.wps-tab-links li a, .wps-tab-links li a:visited {';
    echo 'border-top: 1px solid '.$wps_profile_tab_inactive_color.';';
    echo 'border-left: 1px solid '.$wps_profile_tab_inactive_color.';';
    echo 'border-right: 1px solid '.$wps_profile_tab_inactive_color.';';
    echo 'border-bottom: 1px solid transparent;';
    echo 'background-color:'.$wps_profile_tab_inactive_color.';';
    echo 'color:'.$wps_profile_tab_inactive_text_color.';';
    echo 'text-decoration:none;';
    echo '}';
    
    echo '.wps-tab-links li.active a {';
    echo 'background-color:'.$wps_profile_tab_active_color.' !important;';
    echo 'color:'.$wps_profile_tab_active_text_color.' !important;';
    echo '}';
        
    echo '</style>';
    
    
}   


																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */

function wps_usermeta_button($atts) {

	// Init
	add_action('wp_footer', 'wps_usermeta_init');

	global $current_user;
	$html = '';

	// Shortcode parameters
    $values = wps_get_shortcode_options('wps_usermeta_button');        
	extract( shortcode_atts( array(
		'user_id' => false,
		'url' => wps_get_shortcode_value($values, 'wps_usermeta_button-url', ''),
		'value' => wps_get_shortcode_value($values, 'wps_usermeta_button-value', __('Go', WPS2_TEXT_DOMAIN)),
		'class' => wps_get_shortcode_value($values, 'wps_usermeta_button-class', ''),
		'styles' => true,
        'after' => '',
		'before' => '',
	), $atts, 'wps_usermeta_button' ) );

	if (!$user_id) $user_id = wps_get_user_id();

	if (!$url):

		$html .= '<div class="wps_error">'.__('Please set URL option in the shortcode.', WPS2_TEXT_DOMAIN).'</div>';

	else:

		$html .= '<form action="" method="POST">';
		$url .= wps_query_mark($url).'user_id='.$user_id;
		$html .= '<input class="wps_user_button '.$class.'" rel="'.$url.'" type="submit" class="wps_submit '.$class.'" value="'.$value.'" />';
		$html .= '</form>';

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;	
}

function wps_usermeta($atts) {

	// Init
	add_action('wp_footer', 'wps_usermeta_init');

	global $current_user;
	$html = '';

	// Shortcode parameters
    $values = wps_get_shortcode_options('wps_usermeta');    
	extract( shortcode_atts( array(
		'user_id' => false,
		'meta' => wps_get_shortcode_value($values, 'wps_usermeta-meta', 'wpspro_home'),
		'label' => wps_get_shortcode_value($values, 'wps_usermeta-label', ''),
		'size' => wps_get_shortcode_value($values, 'wps_usermeta-size', '250,250'),
		'map_style' => wps_get_shortcode_value($values, 'wps_usermeta-map_style', 'dynamic'),
		'zoom' => wps_get_shortcode_value($values, 'wps_usermeta-zoom', 5),
        'link' => wps_get_shortcode_value($values, 'wps_usermeta-link', true),
		'styles' => true,
        'after' => '',
		'before' => '',
	), $atts, 'wps_usermeta' ) );
	$size = explode(',', $size);

	if (!$user_id) $user_id = wps_get_user_id();

	$friends = wps_are_friends($current_user->ID, $user_id);
	// By default same user, and friends of user, can see profile
	$user_can_see_profile = ($current_user->ID == $user_id || $friends['status'] == 'publish') ? true : false;
	$user_can_see_profile = apply_filters( 'wps_check_profile_security_filter', $user_can_see_profile, $user_id, $current_user->ID );

	if ($user_can_see_profile):

		$user = get_user_by('id', $user_id);
		if ($user):
			if ($meta != 'wpspro_map'):
    
                $user_values = array('display_name', 'user_login', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_status');
                if (in_array($meta, $user_values)) {
    
                    if ($label) $html .= '<span class="wps_usermeta_label">'.$label.'</span> ';    
                    if ($meta == 'user_email' && $link) {
                        $html .= '<a href="mailto:'.$user->$meta.'">'.$user->$meta.'</a>';
                    } else {
                        $html .= $user->$meta;
                    }
    
                } else {

                    if ($value = get_user_meta( $user_id, $meta, true )) {
                        if ($label) $html .= '<span class="wps_usermeta_label">'.$label.'</span> ';
                    } else {
                        if ($value = get_user_meta( $user_id, 'wps_'.$meta, true )):
                            // Filter for value
                            $value = apply_filters( 'wps_usermeta_value_filter', $value, $atts, $user_id );
                        endif;
                    }
                    $html .= $value;
    
                }
    
            else:
    
				$city = get_user_meta( $user_id, 'wpspro_home', true );
				$country = get_user_meta( $user_id, 'wpspro_country', true );
				if ($city && $country):
					if ($map_style == "static"):
						$html .= '<a target="_blank" href="http://maps.google.co.uk/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q='.$city.',+'.$country.'&amp;ie=UTF8&amp;hq=&amp;hnear='.$city.',+'.$country.'&amp;output=embed&amp;z=5" alt="Click on map to enlarge" title="Click on map to enlarge">';
						$html .= '<img src="http://maps.google.com/maps/api/staticmap?center='.$city.',.+'.$country.'&size='.$size[0].'x'.$size[1].'&zoom='.$zoom.'&maptype=roadmap&markers=color:blue|label:&nbsp;|'.$city.',+'.$country.'&sensor=false" />';
						$html .= "</a>";
					else:
						$html .= "<iframe width='".$size[0]."' height='".$size[1]."' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.co.uk/maps?q=".$city.",+".$country."&amp;z=".$zoom."&amp;output=embed&amp;iwloc=near'></iframe>";
					endif;

				endif;

			endif;
		endif;

	endif;

	if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

	return $html;

}

function wps_usermeta_change($atts) {

	// Init
	add_action('wp_footer', 'wps_usermeta_init');
    add_action('wp_footer', 'wps_add_tab_css');

	global $current_user;
	$html = '';

    // Shortcode parameters
    $values = wps_get_shortcode_options('wps_usermeta_change');    
    extract( shortcode_atts( array(
        'user_id' => 0,
        'meta_class' => 'wps_usermeta_change_label',
        'show_town' => wps_get_shortcode_value($values, 'wps_usermeta_change-show_town', true),
        'show_country' => wps_get_shortcode_value($values, 'wps_usermeta_change-show_country', true),
        'show_name' => wps_get_shortcode_value($values, 'wps_usermeta_change-show_name', true),
        'class' => wps_get_shortcode_value($values, 'wps_usermeta_change-class', ''),
        'label' => wps_get_shortcode_value($values, 'wps_usermeta_change-label', __('Update', WPS2_TEXT_DOMAIN)),
        'town' => wps_get_shortcode_value($values, 'wps_usermeta_change-town', __('Town/City', WPS2_TEXT_DOMAIN)),
        'country' => wps_get_shortcode_value($values, 'wps_usermeta_change-country', __('Country', WPS2_TEXT_DOMAIN)),
        'displayname' => wps_get_shortcode_value($values, 'wps_usermeta_change-displayname', __('Display Name', WPS2_TEXT_DOMAIN)),
        'name' => wps_get_shortcode_value($values, 'wps_usermeta_change-name', __('Your first name and family name', WPS2_TEXT_DOMAIN)),
        'password' => wps_get_shortcode_value($values, 'wps_usermeta_change-password', __('Change your password', WPS2_TEXT_DOMAIN)),
        'password2' => wps_get_shortcode_value($values, 'wps_usermeta_change-password2', __('Re-type your password', WPS2_TEXT_DOMAIN)),
        'password_msg' => wps_get_shortcode_value($values, 'wps_usermeta_change-password_msg', __('Password changed, please log in again.', WPS2_TEXT_DOMAIN)),
        'email' => wps_get_shortcode_value($values, 'wps_usermeta_change-email', __('Email address', WPS2_TEXT_DOMAIN)),
        'logged_out_msg' => wps_get_shortcode_value($values, 'wps_usermeta_change-logged_out_msg', __('You must be logged in to view this page.', WPS2_TEXT_DOMAIN)),
        'mandatory' => wps_get_shortcode_value($values, 'wps_usermeta_change-mandatory', '<span style="color:red;"> *</span>'),        
        'login_url' => wps_get_shortcode_value($values, 'wps_usermeta_change-login_url', ''),
        'required_msg' => wps_get_shortcode_value($values, 'wps_usermeta_change-required_msg', __('Please check for required fields', WPS2_TEXT_DOMAIN)),
        'styles' => true,
        'after' => '',
        'before' => '',

    ), $atts, 'wps_usermeta' ) );

	if (is_user_logged_in()) {
    
		if (!$user_id)
			$user_id = wps_get_user_id();

		$user_can_see_profile = ($current_user->ID == $user_id || current_user_can('manage_options')) ? true : false;

        if (current_user_can('manage_options') && !$login_url && function_exists('wps_login_init')):
            $html = wps_admin_tip($html, 'wps_usermeta_change', __('Add login_url="/example" to the [wps-usermeta-change] shortcode to let users login and redirect back here when not logged in.', WPS2_TEXT_DOMAIN));
        endif;    
        
		if ($user_can_see_profile):
        
            $mandatory = html_entity_decode($mandatory, ENT_QUOTES);

			// Start building tabs array
			$tabs = array();
            $tabs_array = get_option('wps_profile_tabs');        
                
			// Update if POSTing
			if (isset($_POST['wps_usermeta_change_update'])):

				if ($display_name = $_POST['wpspro_display_name'])
					wp_update_user( array ( 'ID' => $user_id, 'display_name' => $display_name ) ) ;

				if ($first_name = $_POST['wpspro_firstname'])
					wp_update_user( array ( 'ID' => $user_id, 'first_name' => $first_name ) ) ;
				if ($last_name = $_POST['wpspro_lastname'])
					wp_update_user( array ( 'ID' => $user_id, 'last_name' => $last_name ) ) ;
        
				if ($user_email = $_POST['wpspro_email'])
					wp_update_user( array ( 'ID' => $user_id, 'user_email' => $user_email ) ) ;

				if (isset($_POST['wpsro_home'])) update_user_meta( $user_id, 'wpspro_home', $_POST['wpsro_home']);
				if (isset($_POST['wpspro_country'])) update_user_meta( $user_id, 'wpspro_country', $_POST['wpspro_country']);

				if (isset($_POST['wpspro_password']) && $_POST['wpspro_password'] != ''):
					$pw = $_POST['wpspro_password'];
					wp_set_password($pw, $user_id);
					$html .= '<div class="wps_success password_msg">'.$password_msg.'</div>';
				endif;
        
				do_action( 'wps_usermeta_change_hook', $user_id, $atts, $_POST, $_FILES );

			endif;

			if (!isset($_POST['wpspro_password']) || $_POST['wpspro_password'] == ''):

                $the_user = get_user_by('id', $user_id);

                $value = isset($_POST['wpspro_display_name']) ? stripslashes($_POST['wpspro_display_name']) : $the_user->display_name;
                    $form_html = '<div class="wps_usermeta_change_item">';
                    $form_html .= '<div class="'.$meta_class.'">'.$displayname.'</div>';
                    $form_html .= '<input type="text" id="wpspro_display_name" name="wpspro_display_name" value="'.$value.'" />';
                    $form_html .= '</div>';
                    $tab_row['tab'] = isset($tabs_array['wps_profile_tab_names']) ? $tabs_array['wps_profile_tab_names'] : 1;
                    $tab_row['html'] = $form_html;        
                    $tab_row['mandatory'] = false;     
                    array_push($tabs,$tab_row);        

                if ($name && $show_name):
                    $firstname = isset($_POST['wpspro_firstname']) ? $_POST['wpspro_firstname'] : $the_user->first_name;
                    $lastname = isset($_POST['wpspro_lastname']) ? $_POST['wpspro_lastname'] : $the_user->last_name;
                    $form_html = '<div class="wps_usermeta_change_item">';
                        $form_html .= '<div class="'.$meta_class.'">'.$name.'</div>';
                        $form_html .= '<div class="wps_usermeta_change_name"><input type="text" name="wpspro_firstname" class="wps_mandatory_field" value="'.$firstname.'"> ';
                        $form_html .= '<input type="text" name="wpspro_lastname" class="wps_mandatory_field" value="'.$lastname.'">'.$mandatory.'</div>';
                    $form_html .= '</div>';
                    $tab_row['tab'] = isset($tabs_array['wps_profile_tab_names']) ? $tabs_array['wps_profile_tab_names'] : 1;;
                    $tab_row['html'] = $form_html;        
                    $tab_row['mandatory'] = false;     
                    array_push($tabs,$tab_row);                
                endif;

                $value = isset($_POST['wpspro_email']) ? $_POST['wpspro_email'] : $the_user->user_email;
                    $form_html = '<div class="wps_usermeta_change_item">';
                    $form_html .= '<div class="'.$meta_class.'">'.$email.'</div>';
                    $form_html .= '<input type="text" id="wpspro_email" name="wpspro_email" style="width:250px" value="'.$value.'" />';
                    $form_html .= '</div>';
                    $tab_row['tab'] = isset($tabs_array['wps_profile_tab_email']) ? $tabs_array['wps_profile_tab_email'] : 1;;
                    $tab_row['html'] = $form_html;        
                    $tab_row['mandatory'] = false;     
                    array_push($tabs,$tab_row);                

                $value = get_user_meta( $user_id, 'wpspro_home', true );
                    if ($town && $show_town):
                        $form_html = '<div id="wpspro_home" class="wps_usermeta_change_item">';
                        $form_html .= '<div class="'.$meta_class.'">'.$town.'</div>';
                        $form_html .= '<input type="text" id="wpspro_home" name="wpsro_home" value="'.$value.'" />';
                        $form_html .= '</div>';
                        $tab_row['tab'] = isset($tabs_array['wps_profile_tab_location']) ? $tabs_array['wps_profile_tab_location'] : 1;;
                        $tab_row['html'] = $form_html;        
                        $tab_row['mandatory'] = false;     
                        array_push($tabs,$tab_row);                
                    endif;

                $value = get_user_meta( $user_id, 'wpspro_country', true );
                    if ($country && $show_country):
                        $form_html = '<div id="wpspro_country" class="wps_usermeta_change_item">';
                        $form_html .= '<div class="'.$meta_class.'">'.$country.'</div>';
                        $form_html .= '<input type="text" id="wpspro_country" name="wpspro_country" value="'.$value.'" />';
                        $form_html .= '</div>';
                        $tab_row['tab'] = isset($tabs_array['wps_profile_tab_location']) ? $tabs_array['wps_profile_tab_location'] : 1;;;
                        $tab_row['html'] = $form_html;        
                        $tab_row['mandatory'] = false;     
                        array_push($tabs,$tab_row);                
                    endif;

                // Password change
                    $form_html = '<div class="wps_usermeta_change_item">';
                    $form_html .= '<div class="'.$meta_class.'">'.$password.'</div>';
                    $form_html .= '<input type="password" name="wpspro_password" id="wpspro_password" />';
                    $form_html .= '<div class="'.$meta_class.'">'.$password2.'</div>';
                    $form_html .= '<input type="password" name="wpspro_password2" id="wpspro_password2" />';
                    $form_html .= '</div>';
                    $tab_row['tab'] = isset($tabs_array['wps_profile_tab_password']) ? $tabs_array['wps_profile_tab_password'] : 1;;;
                    $tab_row['html'] = $form_html;        
                    $tab_row['mandatory'] = false;     
                    array_push($tabs,$tab_row);                

                // Anything else?
                $tabs = apply_filters( 'wps_usermeta_change_filter', $tabs, $atts, $user_id );

			endif;

            // Build output
            $wps_profile_tab1 = (isset($tabs_array['wps_profile_tab1'])) ? $tabs_array['wps_profile_tab1'] : false;
            $wps_profile_tab2 = (isset($tabs_array['wps_profile_tab2'])) ? $tabs_array['wps_profile_tab2'] : false;
            $wps_profile_tab3 = (isset($tabs_array['wps_profile_tab3'])) ? $tabs_array['wps_profile_tab3'] : false;
            $wps_profile_tab4 = (isset($tabs_array['wps_profile_tab4'])) ? $tabs_array['wps_profile_tab4'] : false;
            $wps_profile_tab5 = (isset($tabs_array['wps_profile_tab5'])) ? $tabs_array['wps_profile_tab5'] : false;
            $wps_profile_tab6 = (isset($tabs_array['wps_profile_tab6'])) ? $tabs_array['wps_profile_tab6'] : false;
            $wps_profile_tab7 = (isset($tabs_array['wps_profile_tab7'])) ? $tabs_array['wps_profile_tab7'] : false;
            $wps_profile_tab8 = (isset($tabs_array['wps_profile_tab8'])) ? $tabs_array['wps_profile_tab8'] : false;
            $wps_profile_tab9 = (isset($tabs_array['wps_profile_tab9'])) ? $tabs_array['wps_profile_tab9'] : false;
            $wps_profile_tab10 = (isset($tabs_array['wps_profile_tab10'])) ? $tabs_array['wps_profile_tab10'] : false;

            $tab_ptr = 1;
            $max_tabs = false;
            if ($wps_profile_tab1) $max_tabs = 1;
            if ($wps_profile_tab2) $max_tabs = 2;
            if ($wps_profile_tab3) $max_tabs = 3;
            if ($wps_profile_tab4) $max_tabs = 4;
            if ($wps_profile_tab5) $max_tabs = 5;
            if ($wps_profile_tab6) $max_tabs = 6;
            if ($wps_profile_tab7) $max_tabs = 7;
            if ($wps_profile_tab8) $max_tabs = 8;
            if ($wps_profile_tab9) $max_tabs = 9;
            if ($wps_profile_tab10) $max_tabs = 10;
        
            // Show form
            $html .= '<form enctype="multipart/form-data" id="wps_usermeta_change" action="#" method="POST">';
                $html .= '<input type="hidden" name="wps_usermeta_change_update" value="yes" />';

                if ($max_tabs):
        
                    $html .= '<div class="wps-tabs">';
                        $html .= '<ul class="wps-tab-links">';
                            $html .= '<li id="wps-tab1" class="active"><a href="#tab1">'.$wps_profile_tab1.'</a></li>';
                            if ($wps_profile_tab2) $html .= '<li id="wps-tab2" ><a href="#tab2">'.$wps_profile_tab2.'</a></li>';
                            if ($wps_profile_tab3) $html .= '<li id="wps-tab3" ><a href="#tab3">'.$wps_profile_tab3.'</a></li>';
                            if ($wps_profile_tab4) $html .= '<li id="wps-tab4" ><a href="#tab4">'.$wps_profile_tab4.'</a></li>';
                            if ($wps_profile_tab5) $html .= '<li id="wps-tab5" ><a href="#tab5">'.$wps_profile_tab5.'</a></li>';
                            if ($wps_profile_tab6) $html .= '<li id="wps-tab6" ><a href="#tab6">'.$wps_profile_tab6.'</a></li>';
                            if ($wps_profile_tab7) $html .= '<li id="wps-tab7" ><a href="#tab7">'.$wps_profile_tab7.'</a></li>';
                            if ($wps_profile_tab8) $html .= '<li id="wps-tab8" ><a href="#tab8">'.$wps_profile_tab8.'</a></li>';
                            if ($wps_profile_tab9) $html .= '<li id="wps-tab9" ><a href="#tab9">'.$wps_profile_tab9.'</a></li>';
                            if ($wps_profile_tab10) $html .= '<li id="wps-tab10" ><a href="#tab10">'.$wps_profile_tab10.'</a></li>';
                        $html .= '</ul>';

                        $html .= '<div class="wps-tab-content">';

                            while ($tab_ptr <= $max_tabs)
                            {
                                $html .= '<div id="tab'.$tab_ptr.'" class="wps-tab ';
                                if ($tab_ptr == 1) $html .= 'active';
                                $html .= '"><div id="wps-tab-content-'.$tab_ptr.'" class="wps-tab-content-inner">';
                                foreach ($tabs as $tab):
                                    if ($tab['tab'] == $tab_ptr):
                                        $html .= '<p>'.$tab['html'].'</p>';     
                                    endif;
                                endforeach;
                                $html .= '</div></div>';
                                $tab_ptr++;
                            }


                        $html .= '</div>';
                    $html .= '</div>';
        
                else:
        
                    while ($tab_ptr <= 10)
                    {
                        foreach ($tabs as $tab):
                            if ($tab['tab'] == $tab_ptr):
                                $html .= $tab['html'];  
                            endif;
                        endforeach;
                        $tab_ptr++;
                    }
        
                endif;

                $html .= '<div id="wps_required_msg" class="wps_error" style="display:none">'.$required_msg.'</div>';
                $html .= '<input type="submit" id="wps_usermeta_change_submit" class="wps_submit '.$class.'" value="'.$label.'" />';
            $html .= '</form>';
        

		endif;

		if ($html) $html = apply_filters ('wps_wrap_shortcode_styles_filter', $html, 'wps_usermeta_change', $before, $after, $styles, $values);

    } else {

        if (!is_user_logged_in() && $logged_out_msg):
            $query = wps_query_mark(get_bloginfo('url').$login_url);
            if ($login_url) $html .= sprintf('<a href="%s%s%sredirect=%s">', get_bloginfo('url'), $login_url, $query, wps_root( $_SERVER['REQUEST_URI'] ));
            $html .= $logged_out_msg;
            if ($login_url) $html .= '</a>';
        endif;
    
    }

	return $html;

}

function wps_usermeta_change_link($atts) {

	// Init
	add_action('wp_footer', 'wps_usermeta_init');

	global $current_user;
	$html = '';

	if (is_user_logged_in()) {

		// Shortcode parameters
        $values = wps_get_shortcode_options('wps_usermeta_change_link');    
		extract( shortcode_atts( array(
			'text' => wps_get_shortcode_value($values, 'wps_usermeta_change_link-text', __('Edit Profile', WPS2_TEXT_DOMAIN)),
			'user_id' => 0,
			'styles' => true,
            'styles' => true,
            'after' => '',
			'before' => '',
		), $atts, 'wps_usermeta_change_link' ) );

		if (!$user_id)
			$user_id = wps_get_user_id();

		if ($current_user->ID == $user_id || current_user_can('manage_options')):
			$url = get_page_link(get_option('wpspro_edit_profile_page'));
			$html .= '<a href="'.$url.wps_query_mark($url).'user_id='.$user_id.'">'.$text.'</a>';
		endif;

		if ($html) $html = apply_filters ('wps_wrap_shortcode_styles_filter', $html, 'wps_usermeta_change_link', $before, $after, $styles, $values);

	}

	return $html;

}

function wps_close_account($atts) {

	// Init
	add_action('wp_footer', 'wps_usermeta_init');

	global $current_user;
	$html = '';

	if (is_user_logged_in()) {
        
		// Shortcode parameters
        $values = wps_get_shortcode_options('wps_close_account');    
		extract( shortcode_atts( array(
			'class' => wps_get_shortcode_value($values, 'wps_close_account-class', ''),
			'label' => wps_get_shortcode_value($values, 'wps_close_account-label', __('Close account', WPS2_TEXT_DOMAIN)),
			'are_you_sure_text' => wps_get_shortcode_value($values, 'wps_close_account-are_you_sure_text', __('Are you sure? You cannot re-open a closed account.', WPS2_TEXT_DOMAIN)),
			'logout_text' => wps_get_shortcode_value($values, 'wps_close_account-logout_text', __('Your account has been closed.', WPS2_TEXT_DOMAIN)),
            'url' => wps_get_shortcode_value($values, 'wps_close_account-url', '/'), // set URL to go to after de-activation, probably a logout page, or '' for current page
			'styles' => true,
            'after' => '',
			'before' => '',

		), $atts, 'wps_usermeta' ) );
		
        $user_id = wps_get_user_id();
        if ($user_id == $current_user->ID || current_user_can('manage_options')):

            $html .= '<input type="button" data-sure="'.$are_you_sure_text.'" data-url="'.$url.'" data-logout="'.$logout_text.'" id="wps_close_account" data-user="'.$user_id.'" class="wps_submit '.$class.'" value="'.$label.'" />';

            if ($html) $html = apply_filters ('wps_wrap_shortcode_styles_filter', $html, 'wps_close_account', $before, $after, $styles, $values);

        endif;
        
            
    }

    return $html;
}

function wps_join_site($atts) {
    
    $html = '';

    // Shortcode parameters
    $values = wps_get_shortcode_options('wps_join_site');    
    extract( shortcode_atts( array(
        'class' => wps_get_shortcode_value($values, 'wps_join_site-label', ''),
        'label' => wps_get_shortcode_value($values, 'wps_join_site-label', __('Join this site', WPS2_TEXT_DOMAIN)),
        'style' => wps_get_shortcode_value($values, 'wps_join_site-label', 'button'), // button|text
        'styles' => true,
        'after' => '',
        'before' => '',

    ), $atts, 'wps_join_site' ) );
    
    if (is_multisite()):
    
        if ($style == 'button'):
            $html .= '<input type="button" class="wps_submit '.$class.'" id="wps_join_site" value="'.$label.'" />';
        else:
            $html .= '<a href="javascript:void(0);" id="wps_join_site">'.$label.'</a>';
        endif;

        if ($html) $html = apply_filters ('wps_wrap_shortcode_styles_filter', $html, 'wps_join_site', $before, $after, $styles, $values);

    endif;
    
    return $html;
    
}


add_shortcode(WPS_PREFIX.'-usermeta', 'wps_usermeta');
add_shortcode(WPS_PREFIX.'-usermeta-change', 'wps_usermeta_change');
add_shortcode(WPS_PREFIX.'-usermeta-change-link', 'wps_usermeta_change_link');
add_shortcode(WPS_PREFIX.'-usermeta-button', 'wps_usermeta_button');
add_shortcode(WPS_PREFIX.'-close-account', 'wps_close_account');
add_shortcode(WPS_PREFIX.'-join-site', 'wps_join_site');

?>
