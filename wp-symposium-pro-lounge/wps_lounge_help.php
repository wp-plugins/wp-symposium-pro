<?php
// Quick Start
add_action('wps_admin_quick_start_hook', 'wps_admin_quick_start_lounge');
function wps_admin_quick_start_lounge() {

	echo '<div style="margin-right:10px; float:left">';
	echo '<form action="" method="POST">';
	echo '<input type="hidden" name="wpspro_quick_start" value="lounge" />';
	echo '<input type="submit" class="button-secondary" value="'.__('Add Lounge Page', WPS2_TEXT_DOMAIN).'" />';
	echo '</form></div>';
}

add_action('wps_admin_quick_start_form_save_hook', 'wps_admin_quick_start_lounge_save', 10, 1);
function wps_admin_quick_start_lounge_save($the_post) {

	if (isset($the_post['wpspro_quick_start']) && $the_post['wpspro_quick_start'] == 'lounge'):

        $post_content = '['.WPS_PREFIX.'-lounge]';

		// Events Page
		$post = array(
		  'post_content'   => $post_content,
		  'post_name'      => 'lounge',
		  'post_title'     => __('Lounge', WPS2_TEXT_DOMAIN),
		  'post_status'    => 'publish',
		  'post_type'      => 'page',
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);  

		$new_id = wp_insert_post( $post );

		echo '<div class="wps_success">';
			echo sprintf(__('Lounge Page (%s) added. [<a href="%s">view</a>]', WPS2_TEXT_DOMAIN), get_permalink($new_id), get_permalink($new_id)).'<br /><br />';
			echo '<strong>'.__('Do not add it again or you will create another WordPress page!', WPS2_TEXT_DOMAIN).'</strong><br /><br />';
			echo sprintf(__('You might want to add it to your <a href="%s">WordPress menu</a>.', WPS2_TEXT_DOMAIN), "nav-menus.php");
		echo '</div>';

	endif;

}
?>