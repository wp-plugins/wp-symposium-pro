<?php

function wps_menu() {
	$menu_label = (defined('WPS_MENU')) ? WPS_MENU : 'WPS Pro';
	add_menu_page($menu_label, $menu_label, 'manage_options', 'wps_pro', 'wpspro_setup', 'none', 30); 
	add_submenu_page('wps_pro', __('Setup', WPS2_TEXT_DOMAIN), __('Setup', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_setup', 'wpspro_setup');
	add_submenu_page('wps_pro', __('Custom CSS', WPS2_TEXT_DOMAIN), __('Custom CSS', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_custom_css', 'wpspro_custom_css');
}

// Drop down items for WPS Pro Editor button
global $wp_version;
if ($wp_version >= 3.9):
	function my_custom_admin_head(){
		echo '<div id="wps_admin_shortcodes">';
			$items = array();
			$items = apply_filters( 'wps_admin_shortcodes', $items );

			$sort = array();
			foreach($items as $k=>$v) {
			    $sort['label'][$k] = $v['label'];
			}
			array_multisort($sort['label'], SORT_ASC, $items);

			foreach ($items as $item):
				echo '<div id="'.$item['div'].'_menu">';
				echo '<a name="WP Symposium Pro" href="#TB_inline?width=600&height=550&inlineId='.$item['div'].'" class="thickbox">'.$item['label'].'</a>';
				echo '</div>';				
			endforeach;
		echo '</div>';
		do_action( 'wps_admin_shortcodes_dialog' );
	}
	add_action('admin_head', 'my_custom_admin_head');

	// Editor button
	add_action( 'init', 'wps_editor_button' );
	function wps_editor_button() {
	    add_filter( "mce_external_plugins", "wps_editor_add_buttons" );
	    add_filter( 'mce_buttons', 'wps_editor_register_buttons' );
	}
	function wps_editor_add_buttons( $plugin_array ) {
	    $plugin_array['wps_pro'] = plugins_url('js/wps.editor-button.js', __FILE__);
	    return $plugin_array;
	}
	function wps_editor_register_buttons( $buttons ) {
	    array_push( $buttons, 'wps_pro', 'pushortcodes' ); 
	    return $buttons;
	}
endif;

function wpspro_setup() {

  	echo '<div class="wrap">';
        	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';

	  	echo '<div style="background-color:#efcfcf; overflow:auto; color: #000; margin-top:25px; margin-bottom:10px; padding:20px;">';
	  		echo '<div style="font-size:2em; line-height:1em; font-weight:100;">'.__('Welcome to WP Symposium Pro', WPS2_TEXT_DOMAIN).'</div>';
	  		echo '<p><em>'.__('The premier social network plugin for WordPress', WPS2_TEXT_DOMAIN).'</em></p>';
	  		echo '<div style="width:30%; min-width:200px; margin-right:10px; float: left;">';
		  		echo '<p style="font-size:1.2em; font-weight:100;">'.__('Quick Start', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-weight:100;">'.__('Use the Quick Start buttons below, then', WPS2_TEXT_DOMAIN).'<br />';
		  		echo sprintf(__('add your new pages to your <a href="%s">WordPress menu</a>', WPS2_TEXT_DOMAIN).'</p>', 'nav-menus.php').'</p>';
	  		echo '</div>';
	  		echo '<div style="width:30%; min-width:200px; margin-right:10px; float: left;">';
		  		echo '<p style="font-size:1.2em; font-weight:100;">'.__('Support', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-weight:100;">'.sprintf(__('Support is available at <a target="_blank" href="%s">www.wpsymposiumpro.com</a>', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com').'<br />';
		  		echo __('with forums and live chat support.', WPS2_TEXT_DOMAIN).'</p>';
	  		echo '</div>';
	  		echo '<div style="width:30%; min-width:200px; margin-right:10px; float: left;">';
		  		echo '<p style="font-size:1.2em; font-weight:100;">'.__('Extend', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-weight:100;">'.__('Make your social network even better with', WPS2_TEXT_DOMAIN).'<br />';
		  		echo sprintf(__('additional plugins from  <a target="_blank" href="%s">www.wpsymposiumpro.com</a>', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/shop').'</p>';
	  		echo '</div>';
	  	echo '</div>';

	  	echo '<h3>'.__('Quick Start', WPS2_TEXT_DOMAIN).'</h3>';

	  	echo '<p>'.sprintf(__('Read the quick start guide on <a href="%s">www.wpsymposiumpro.com</a>, or click a button below to quickly create pages!', WPS2_TEXT_DOMAIN), "http://www.wpsymposiumpro.com/getting-started/").'</p>';

		// Do any saving from quick start hook
		if (isset($_POST)):
			if (isset($_POST['wpspro_quick_start'])):
				do_action( 'wps_admin_quick_start_form_save_hook', $_POST);
			endif;
		endif;

		// Quick start hook
		do_action( 'wps_admin_quick_start_hook' );

	  	echo '<h3 style="clear:both;margin-top: 65px;">'.__('Settings', WPS2_TEXT_DOMAIN).'</h3>';
	  	echo '<p>'.__('Click on a section title below to see options and help to get started.', WPS2_TEXT_DOMAIN).'</p>';
	  	echo '<p>'.sprintf(__('More plugins for extra features can be downloaded from <a href="%s">www.wpsymposiumpro.com</a>.', WPS2_TEXT_DOMAIN), "http://www.wpsymposiumpro.com/shop").'</p>';

		// Do any saving
		if (isset($_POST['wpspro_update']) && $_POST['wpspro_update'] == 'yes'):
			do_action( 'wps_admin_setup_form_save_hook', $_POST);
		endif;
		if ( isset($_GET['wpspro_update']) ):
			do_action( 'wps_admin_setup_form_get_hook', $_GET);
		endif;

		echo '<form action="" method="POST">';
		echo '<input type="hidden" name="wpspro_update" value="yes" />';

			// Getting Started/Help hook
			do_action( 'wps_admin_getting_started_hook' );

		echo '<p><input type="submit" name="Submit" class="button-primary" value="'.__('Save Changes', WPS2_TEXT_DOMAIN).'" /></p>';
			
		echo '</form>';

		
	echo '</div>';	  	

}

function wpspro_custom_css() {

	// React to POSTed information
	if (isset($_POST['wpspro_update_css'])):

		update_option('wpspro_custom_css', $_POST['wpspro_custom_css']);

		// Re-act to any more options?
		do_action( 'wps_admin_custom_css_form_save_hook', $_POST );

	endif;
	

  	echo '<div class="wrap">';
        	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';
	  	echo '<h2>'.__('Custom CSS', WPS2_TEXT_DOMAIN).'</h2>';

	  	echo __('To over-ride theme styles, you may need to add !important to styles.', WPS2_TEXT_DOMAIN);
	  	?>
		<form action="" method="POST">

			<input type="hidden" name="wpspro_update_css" value="yes" />

			<table class="form-table">

				<tr><td colspan="2">

					<textarea name="wpspro_custom_css" id="wpspro_custom_css" style="width:100%; height:500px"><?php echo stripslashes(get_option('wpspro_custom_css')); ?></textarea>

				</td></tr>

				<?php 
				// Any more options?
				do_action( 'wps_admin_custom_css_form_hook' );
				?>

			</table> 
			
			<p style="margin-left:6px"> 
			<input type="submit" name="Submit" class="button-primary" value="<?php echo __('Save Changes', WPS2_TEXT_DOMAIN); ?>" /> 
			</p> 
			
		</form> 
		<?php

	echo '</div>';	  	

}



?>