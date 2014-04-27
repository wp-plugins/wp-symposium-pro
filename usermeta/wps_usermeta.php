<?php 

add_action('show_user_profile', 'wps_usermeta_form');
add_action('edit_user_profile', 'wps_usermeta_form');

add_action( 'personal_options_update', 'wps_usermeta_form_save' );
add_action( 'edit_user_profile_update', 'wps_usermeta_form_save' );

function wps_usermeta_form($user)
{

	global $current_user;
	
	// Check if it is current user or super admin role
	if( $user->ID == $current_user->ID || current_user_can('edit_user', $current_user->ID) || is_super_admin($current_user->ID) )
	{
		?>

		<h3>Extra profile information</h3>

		<table class="form-table">

			<tr>
				<th><label for="wpspro_home"><?php _e('Town/City', WPS2_TEXT_DOMAIN); ?></label></th>
				<td>
					<input type="text" name="wpspro_home" id="wpspro_home" value="<?php echo esc_attr( get_the_author_meta( 'wpspro_home', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description"><?php _e('Please enter your town or city.', WPS2_TEXT_DOMAIN); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="wpspro_country"><?php _e('Country', WPS2_TEXT_DOMAIN); ?></label></th>
				<td>
					<input type="text" name="wpspro_country" id="wpspro_country" value="<?php echo esc_attr( get_the_author_meta( 'wpspro_country', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description"><?php _e('Please enter your country.', WPS2_TEXT_DOMAIN); ?></span>
				</td>
			</tr>

		</table>

		<?php

	}
	
} 

function wps_usermeta_form_save( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_meta($user_id, 'wpspro_home', $_POST['wpspro_home']);
	update_user_meta($user_id, 'wpspro_country', $_POST['wpspro_country']);

	if ($_POST['wpspro_home'] && $_POST['wpspro_country']):

		// Change spaces to %20 for Google maps API and geo-code
		$city = str_replace(' ','%20',$_POST['wpspro_home']);
		$country = str_replace(' ','%20',$_POST['wpspro_country']);
		$fgc = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$city.'+'.$country.'&sensor=false';

		if ($json = @file_get_contents($fgc) ):
			$json_output = json_decode($json, true);
			$lat = $json_output['results'][0]['geometry']['location']['lat'];
			$lng = $json_output['results'][0]['geometry']['location']['lng'];

			update_user_meta($user_id, 'wpspro_lat', $lat);
			update_user_meta($user_id, 'wpspro_long', $lng);
		endif;

	endif;

}


?>