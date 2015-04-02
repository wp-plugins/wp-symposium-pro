<?php

																	/* **** */
																	/* INIT */
																	/* **** */

function wps_lounge_init() {
    global $current_user;
	// JS and CSS
	wp_enqueue_script('wps-lounge-js', plugins_url('wps_lounge.js', __FILE__), array('jquery'));	
	wp_enqueue_style('wps-lounge-css', plugins_url('wps_lounge.css', __FILE__), 'css');
	wp_localize_script( 'wps-lounge-js', 'wps_lounge_ajax', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'wait' => plugins_url('../../wp-symposium-pro/css/images/wait.gif', __FILE__),
        'current_user' => $current_user->ID
    ));		
	do_action('wps_lounges_init_hook');

}

																	/* ********** */
																	/* SHORTCODES */
																	/* ********** */


function wps_lounge($atts) {

    // Init
    add_action('wp_footer', 'wps_lounge_init');

    $html = '';

    if ( is_user_logged_in() ):

        // Shortcode parameters
        extract( shortcode_atts( array(
            'chats' => 30,
            'refresh' => 5,
            'please_wait' => __('Posting %s, please wait...', WPS2_TEXT_DOMAIN),
            'date_format' => __('%s, %s ago', WPS2_TEXT_DOMAIN),
            'before' => '',
            'after' => '',
        ), $atts, 'wps_lounge' ) );

        $html .= '<input type="text" style="display:none;" id="wps_audit" />';
        $html .= '<input type="text" id="wps_lounge_chat" />';
        $html .= '<div id="wps_lounge" data-format="'.$date_format.'" data-wait="'.$please_wait.'" data-chats="'.$chats.'" data-refresh="'.$refresh.'"></div>';
        
    endif;

    if ($html) $html = htmlspecialchars_decode($before).$html.htmlspecialchars_decode($after);

    return $html;    
        
}


if (!is_admin()) {
	add_shortcode(WPS_PREFIX.'-lounge', 'wps_lounge');
}



?>
