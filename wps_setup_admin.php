<?php

// System Options header
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_core_header', 0.5);
function wps_admin_getting_started_core_header() {
    echo '<h2>'.__('Options', WPS2_TEXT_DOMAIN).'</h2>';
}

// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_core', 1);
function wps_admin_getting_started_core() {

    echo '<a name="core"></a>';
  	echo '<div class="wps_admin_getting_started_menu_item" rel="wps_admin_getting_started_core">'.__('System Options', WPS2_TEXT_DOMAIN).'</div>';

	$display = (isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_core') || (isset($_GET['wps_expand']) && $_GET['wps_expand'] == 'wps_admin_getting_started_core') ? 'block' : 'none';
  	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_core" style="display:'.$display.'">';

	?>
	<table class="form-table">

    <tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_core_options_tips"><?php _e('Admin tips', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<input type="checkbox" style="width:10px" name="wps_core_options_tips" />
            <span class="description"><?php echo __('Switch on all admin tips.', WPS2_TEXT_DOMAIN); ?></span>
            <?php if (isset($_POST['wps_core_options_tips'])):
                echo '<div style="margin-top:15px" class="wps_success">'.__('Admin tips switch on', WPS2_TEXT_DOMAIN).'</div>';
            endif; ?>
		</td>
	</tr> 
        
    <tr class="form-field">
        <th scope="row" valign="top"><label for="icon_colors"><?php echo __('Icon Colors', WPS2_TEXT_DOMAIN); ?></label></th>
        <td>
            <select name="icon_colors">
             <?php 
                $icon_colors = get_option('wpspro_icon_colors');
                echo '<option value="dark"';
                    if ($icon_colors != "_light") echo ' SELECTED';
                    echo'>'.__('Dark', WPS2_TEXT_DOMAIN).'</option>';
                echo '<option value="light"';
                    if ($icon_colors == "_light") echo ' SELECTED';
                    echo '>'.__('Light', WPS2_TEXT_DOMAIN).'</option>';
             ?>						
            </select>
            <span class="description"><?php echo __('Icon color scheme to use.', WPS2_TEXT_DOMAIN); ?></span>
        </td> 
    </tr> 

    <tr class="form-field">
        <th scope="row" valign="top"><label for="flag_colors"><?php echo __('Flag Colors', WPS2_TEXT_DOMAIN); ?></label></th>
        <td>
            <select name="flag_colors">
             <?php 
                $flag_colors = get_option('wpspro_flag_colors');
                echo '<option value="dark"';
                    if ($flag_colors != "_light") echo ' SELECTED';
                    echo'>'.__('Dark', WPS2_TEXT_DOMAIN).'</option>';
                echo '<option value="light"';
                    if ($flag_colors == "_light") echo ' SELECTED';
                    echo '>'.__('Light', WPS2_TEXT_DOMAIN).'</option>';
             ?>						
            </select>
            <span class="description"><?php echo __('Flag icon color scheme to use.', WPS2_TEXT_DOMAIN); ?></span>
        </td> 
    </tr> 
        
    <tr class="form-field">
        <th scope="row" valign="top"><label for="wps_external_links"><?php echo __('External links', WPS2_TEXT_DOMAIN); ?></label></th>
        <td>
            <input name="wps_external_links" style="width: 100px" value="<?php echo get_option('wps_external_links'); ?>" />
            <br /><span class="description"><?php echo __('To force external links in new browser tab, enter a suffix to append to relevant links, eg. &quot;&amp;raquo;&quot; for &raquo;. Enter - to force, but not show anything after.', WPS2_TEXT_DOMAIN); ?></span>
        </td> 
    </tr> 

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

	if (isset($the_post['wps_core_options_tips'])):
		delete_option('wps_admin_tips');
	endif;
        
	if (isset($the_post['wps_external_links'])):
		update_option('wps_external_links', $the_post['wps_external_links']);
	else:
		delete_option('wps_external_links');
	endif;

	if (isset($the_post['wps_core_options_strip'])):
		update_option('wps_core_options_strip', true);
	else:
		delete_option('wps_core_options_strip');
	endif;


	if (isset($the_post['icon_colors']) && $the_post['icon_colors'] == 'light'):
		update_option('wpspro_icon_colors', '_light');
	else:
		delete_option('wpspro_icon_colors');
	endif;

    if (isset($the_post['flag_colors']) && $the_post['flag_colors'] == 'light'):
		update_option('wpspro_flag_colors', '_light');
	else:
		delete_option('wpspro_flag_colors');
	endif;
    
	do_action( 'wps_admin_getting_started_core_save_hook', $the_post );

}

?>
