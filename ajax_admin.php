<?php
// Hook into core get users AJAX function
add_action( 'wp_ajax_wps_shortcode_options_save', 'wps_shortcode_options_save' ); 


/* SAVE SHORTCODE OPTIONS */
function wps_shortcode_options_save() {
    
    global $current_user;
    
    if ( is_user_logged_in() && current_user_can('manage_options')) {

        $data = $_POST['data'];
        $arr = $data['arr'];
        
        // Get values
        $values = array();
    
        // Now recreate before saving
        foreach ($arr as $row):
        
            if (strpos($row[0], '-')):

                $name = explode('-', $row[0]);

                $function = $name[0];
                $option = $name[1];

                $type = $row[1];
                $form_value = $row[2];

                $v = $form_value ? $form_value : false;

                $values[$option] = $v ? htmlentities (stripslashes($v), ENT_QUOTES) : '';

            endif;

        endforeach;
        
        update_option('wps_shortcode_options_'.$function, $values);

    }
    
    exit;
    
}


?>
