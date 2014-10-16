<?php


// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_core');
function wps_admin_getting_started_core() {

  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_core">'.__('Core Options', WPS2_TEXT_DOMAIN).'</div>';

	$display = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_core' ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_core" style="display:'.$display.'">';

	?>
	<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_core_options_strip"><?php _e('Content security', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<input type="checkbox" style="width:10px" name="wps_core_options_strip" <?php if (get_option('wps_core_options_strip')) echo 'CHECKED'; ?> /><span class="description"><?php _e('Use wp_kses instead of strip_tags (limits permitted styling).', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 
	<?php
		do_action( 'wps_admin_getting_started_core_hook' );
	?>
	
	</table>
	<?php

	echo '</div>';

}

add_action('wps_admin_setup_form_get_hook', 'wps_admin_getting_started_core_save', 10, 2);
add_action('wps_admin_setup_form_save_hook', 'wps_admin_getting_started_core_save', 10, 2);
function wps_admin_getting_started_core_save($the_post) {

	if (isset($the_post['wps_core_options_strip'])):
		update_option('wps_core_options_strip', true);
	else:
		delete_option('wps_core_options_strip');
	endif;

	do_action( 'wps_admin_getting_started_core_save_hook', $the_post );

}

?>
