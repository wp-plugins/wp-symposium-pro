<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_core');
function wps_admin_getting_started_core() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_core">'.__('Core Options', WPS2_TEXT_DOMAIN).'</div>';

  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_core" style="display:none">';

	do_action( 'wps_admin_getting_started_core_hook' );

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_core_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_core_save', 10, 2);
function wps_admin_getting_started_core_save($the_post) {

	do_action( 'wps_admin_getting_started_core_save_hook', $the_post );

}

?>
