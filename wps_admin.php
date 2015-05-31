<?php
// Admin dependencies
add_action('admin_enqueue_scripts', 'wps_usermeta_admin_init');
function wps_usermeta_admin_init() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wps-usermeta-js', plugins_url('usermeta/wps_usermeta.js', __FILE__), array('wp-color-picker'));	
}

function wps_menu() {
	$menu_label = (defined('WPS_MENU')) ? WPS_MENU : 'WPS Pro';
	add_menu_page($menu_label, $menu_label, 'manage_options', 'wps_pro', 'wpspro_setup', 'none'); 
	add_submenu_page('wps_pro', __('Release notes', WPS2_TEXT_DOMAIN), __('Release notes', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_release_notes', 'wpspro_release_notes');
	add_submenu_page('wps_pro', __('Setup', WPS2_TEXT_DOMAIN), __('Setup', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_setup', 'wpspro_setup');
	add_submenu_page('wps_pro', __('Shortcodes', WPS2_TEXT_DOMAIN), __('Shortcodes', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_shortcodes', 'wps_pro_shortcodes');
	add_submenu_page('wps_pro', __('Custom CSS', WPS2_TEXT_DOMAIN), __('Custom CSS', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_custom_css', 'wpspro_custom_css');
}

function wpspro_release_notes() {

  	echo '<div class="wrap" style="border-radius:3px; border: 1px solid #000; background-color: #fff; padding: 0 20px 0 20px; margin: 0px 30px 0px 30px;">';
        	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';
	  	echo '<style>';
            echo '.wrap { margin-top: 30px !important; margin-left: 10px !important; }';
	  		echo '#wps_release_notes p, td, ol, a { font-size:14px; line-height: 1.3em; font-family:arial; }';
	  		echo '#wps_release_notes h1 { color: #510051; font-weight: bold; line-height: 1.2em; }';
	  		echo '#wps_release_notes h2 { color: #510051; margin-top: 10px; font-weight: bold; }';
	  		echo '#wps_release_notes h3 { color: #333; }';
	  	echo '</style>';
	  	echo '<div id="wps_release_notes"><br />';
	  	?>
	  		<img style="float:right; margin-left: 50px; margin-bottom: 50px;" src='<?php echo plugins_url( '', __FILE__ ); ?>/css/images/wps_logo.png' />
	  		<div style="font-size:2em; line-height:1.6em; color: #510051; font-weight: bold;">WP Symposium Pro Release Notes (<?php echo get_option('wp_symposium_pro_ver'); ?>)</div>

            <p><strong>If you are new to WP Symposium Pro, you will want to visit the <a href="<?php echo admin_url('admin.php?page=wps_pro_setup'); ?>">WPS Pro->Setup</a> page.</strong>
	  		These release notes, and previous release notes, are available on the <a href="http://www.wpsymposiumpro.com/blog" target="_blank">WP Symposium Pro blog</a>.</p>

            <p>
            Don't forget to like our <a href="https://www.facebook.com/wpsymposium" target="_blank">Facebook page</a>, or follow @wpsymposium on <a href="http://twitter.com/wpsymposium" target="_blank">Twitter</a>, to get all the latest news in between releases.
            </p>

			<em><strong>Simon, WP Symposium developer</strong></em></p>

            <div style="border-top: 1px dotted #510051; margin-top: 20px; margin-bottom: 20px;"></div>

                <img src="<?php echo plugins_url( '', __FILE__ ); ?>/css/images/a_complete_guide_to_wp_symposium_pro.jpg" style="margin-right: 10px; float: left" />
                <div>
                    <div style="font-size:1.5em; line-height:1.2em; color: #510051; font-weight: bold;">The Complete Guide To WP Symposium Pro</div>
                    <p>A new book has been produced that you can <a target="_blank" href="http://www.wpsymposiumpro.com/a-complete-guide-to-wp-symposium-pro-book/">access online right now</a>! It's work in progress, but as it's nearing completion it will be of use now, so early access is available. It covers all the shortcodes and options for the core plugin and all the extensions. Also included are examples and additional information for developers. It is formatted such that it can be available as a published print book.</p>
                </div>

            <div style="clear:both; border-bottom: 1px dotted #510051; padding-top: 20px; margin-bottom: 20px;"></div>

            <?php
            $cup_position = 'left';
            if ($cup_position == 'left'):
                $cup_of_tea_left = "background-position: bottom left; background-repeat: no-repeat; background-image: url('".plugins_url( '/css/images/cup_of_tea.png', __FILE__ )."');padding-bottom: 220px;";
                $cup_of_tea_right = "";
            else:
                $cup_of_tea_left = "";
                $cup_of_tea_right = "background-position: bottom right; background-repeat: no-repeat; background-image: url('".plugins_url( '/css/images/cup_of_tea.png', __FILE__ )."');padding-bottom: 220px;";
            endif;
            ?>

            <table><tr>
				<td valign="top" style="<?php echo $cup_of_tea_left; ?>width:45%;">

					<div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">Core WP Symposium Pro plugin</div>
					<a href="http://www.wordpress.org/plugins/wp-symposium-pro" target-"_blank">Available from the WordPress repository</a><br />

                    <h3>New admin pages for shortcode values</h3>
                    <p><strong>Easily view and change all the settings of WP Symposium Pro shortcodes!</strong></p>
                    <p>Through this new admin page you can set the default values of WP Symposium Pro shortcodes, effectively removing the need to add shortcode options by editing pages and widget text areas. You can always over-ride your default settings by adding them to a shortcode directly as with previous releases.</p>
                    <p>There are a few shortcode options not included with this release, but they will be in the next release (usually where the Extension does not have a shortcode of it's own, like Activity YouTube videos or the new @tags).</p>

                    <h3>Add styles to all shortcodes</h3>
                    <p>Add styles to all shortcodes (can avoid with styles="0" on a shortcode). Note this is the style of the area in which the shortcode output appears, not the actual shortcode output itself (which will still follow your theme styles).</p>
                    
                    <h3>Edit Profile</h3>
                    <p>Set up Tabs on your Edit Profile Page and keep things looking tidy! Create tabs and place items into them. If you're using the Profile Extensions feature (in Extensions Plugin) you can place them to! This feature is available via WPS Pro->Setup->Edit Profile Page.</p>

                    <h3>Forum</h3>
                    <p>Added show_closed as new option to [wps-forums].<br />
                    Fixed bug with show_closed option on [wps-forum].</p> 
                                        
                    <h3>Admin</h3>
                    <p>Improved <a href="<?php echo admin_url( 'admin.php?page=wps_pro_setup' ); ?>">WPS Pro->Setup</a> to show current selected section after saving without needing to scroll.</p> 
                                        
				</td>
				<td style="width:5%">&nbsp;</td>
				<td valign="top" style="<?php echo $cup_of_tea_right; ?>">

                    <div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">WP Symposium Pro Extensions</div>
					<a href="http://www.wpsymposiumpro.com/shop/" target-"_blank">Available from www.wpsymposiumpro.com</a><br />

                    <p>In addition to a small number of minor bug fixes, the following new features were added:</p>
                    
                    <h3>New Feature! @tags</h3>
                    <p>Just type @ in an activity post or reply, and tag a friend who will get an alert with a link to your mention of them. Also works on forum posts, replies and comments if the WYSIWYG toolbar is <em>not</em> being used as unfortunately not compatible.</p>
                    
                    <h3>Profile Extensions</h3>
                    <p>New extension type ("Avatar Badge") to overlay an image on top of avatars (can be set for member use, or set by admins only).<br />
                    New shortcode [wps-extended-list], to list one or more profile extension values, and can also show user meta information.</p>
                    
                    <h3>Forum Subscriptions</h3>
                    <p>Set the email subject and alert text for new forum posts, replies and comments via WPS Pro->Setup->Forum Subscriptions.</p>
                    
                    <h3>Login &amp; Register</h3>
                    <p>The site administrator now gets an email when a user registers. Added town_mandatory and country_mandatory to [wps-login-form].</p>
                    
                    <h3>New Filter</h3>
                    <p>wps_registration_form_filter added to the registration form (Login/Register) allowing you to customise it further.</p>
                
                    <h3>Groups</h3>
                    <p>Fixed a bug that showed group posts on activity whilst membership still pending.</p>
                    
                    <h3>Group</h3>
                    <p>New shortcode [wps-group-members-link] to show a link to a page to display group members.<br />
                    New shortcode [wps-group-backto] to show a link back to the group (for use with [wps-group-members].</p>
                    
                    <h3>Mail to User button</h3>
                    <p>Added width and height to [wps-mail-to-user] for popup dimensions</p>
                    
				</td>
			</tr></table>

			<br style="clear:both">
	  	<?php
	  	echo '</div>';
		
	echo '</div>';	

}

function wps_add_shortcode_button( $page = null, $target = null ) {
	echo '<div style="float:left; position:relative;">';
	echo '<a id="wps_admin_shortcodes_button" name="WP Symposium Pro" class="button" title="'.__( 'WP Symposium Pro shortcodes', WPS2_TEXT_DOMAIN ).'"></a>';

	echo '<div id="wps_admin_shortcodes">';
		$items = array();

		// Filter for more items
		$items = apply_filters( 'wps_admin_shortcodes', $items );

		$sort = array();
		foreach($items as $k=>$v) {
		    $sort['label'][$k] = $v['label'];
		}
		array_multisort($sort['label'], SORT_ASC, $items);

		// Default menu items
		echo '<div><a name="WP Symposium Pro" href="http://www.wpsymposiumpro.com/getting-started/" target="_blank">'.__('Getting Started...', WPS2_TEXT_DOMAIN).'</a></div>';
		echo '<div><a name="WP Symposium Pro" href="http://www.wpsymposiumpro.com/shortcodes/" target="_blank">'.__('Shortcodes "How-To"', WPS2_TEXT_DOMAIN).'</a></div>';
		echo '<div><a name="WP Symposium Pro" href="http://www.wpsymposiumpro.com/getting-started-videos/" target="_blank">'.__('Tutorial Videos', WPS2_TEXT_DOMAIN).'</a></div>';
		echo '<hr />';
				
		foreach ($items as $item):
			echo '<div id="'.$item['div'].'_menu">';
			echo '<a name="WP Symposium Pro" href="#TB_inline?width=600&height=550&inlineId='.$item['div'].'" class="thickbox wps_admin_shortcodes_menu">'.$item['label'].'</a>';
			echo '</div>';				
		endforeach;
	echo '</div>';
	do_action( 'wps_admin_shortcodes_dialog' );

	echo '</div>';
}
add_action( 'media_buttons', 'wps_add_shortcode_button' );

function wpspro_setup() {

	// Flush re-write rules, good idea if problem with linking, saves having to re-save permalink
	global $wp_rewrite;
	$wp_rewrite->flush_rules();	

  	echo '<div class="wrap">';
        	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';

	  	echo '<div id="wps_welcome">';
	  		echo '<img style="width:56px; height:56px; margin-right:15px; float:left;" src="'.plugins_url('../wp-symposium-pro/css/images/wps_logo.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" />';
	  		echo '<div style="font-size:2em; line-height:1em; font-weight:100; color:#fff;">'.__('Welcome to WP Symposium Pro', WPS2_TEXT_DOMAIN).'</div>';
	  		echo '<p style="color:#fff;"><em>'.__('The ultimate social network plugin for WordPress', WPS2_TEXT_DOMAIN).'</em></p>';
	  		echo '<div style="width:30%; min-width:200px; margin-right:10px; float: left;">';
		  		echo '<p style="font-size:1.2em; font-weight:100;">'.__('Quick Start', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-weight:100;">'.__('Use the Quick Start buttons below,', WPS2_TEXT_DOMAIN).'<br />';
		  		echo sprintf(__('your new pages to your <a href="%s">WordPress menu</a>,  then', WPS2_TEXT_DOMAIN), 'nav-menus.php').'<br />';
		  		echo __('customize in the "Default Shortcode Setting" below.', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-size:1.2em; font-weight:100;">'.__('Support', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-weight:100;">'.sprintf(__('Support is available at <a target="_blank" href="%s">www.wpsymposiumpro.com</a>', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com').'<br />';
		  		echo sprintf(__('with <a href="%s" target="_blank">forums</a>, <a href="%s" target="_blank">helpdesk</a>, and live chat support.', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/forums/', 'http://www.wpsymposiumpro.com/helpdesk/').'</p>';
		  		echo '<p style="font-weight:100;">'.sprintf(__('We also have more <a target="_blank" href="%s">video tutorials</a>...', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/getting-started-videos/').'</p>';
	  		echo '</div>';
	  		echo '<div style="width:30%; min-width:320px; margin-right:10px; float: left;">';
		  		echo '<p style="font-size:1.2em; font-weight:100;">'.__('Setting up WP Symposium Pro', WPS2_TEXT_DOMAIN).'</p>';
	            echo '<div class="wps_video_container" style="margin-bottom:-30px;">';
				echo '<iframe style="max-width:320px;max-height:180px" src="//www.youtube.com/embed/8beh25UWQOs?feature=player_embedded&showinfo=0&rel=0&autohide=1&vq=hd720" frameborder="0" allowfullscreen></iframe>';
				echo '</div>';
	  		echo '</div>';
	  		echo '<div style="width:30%; min-width:320px; margin-right:10px; float: left;" >';
		  		echo '<p style="font-size:1.2em; font-weight:100;">'.__('Installing and Activating more Extensions', WPS2_TEXT_DOMAIN).'</p>';
	            echo '<div class="wps_video_container" style="margin-bottom:-30px;">';
				echo '<iframe style="max-width:320px;max-height:180px" src="//www.youtube.com/embed/It3bJ0IGy2M?feature=player_embedded&showinfo=0&rel=0&autohide=1&vq=hd720" frameborder="0" allowfullscreen></iframe>';
				echo '</div>';
	  		echo '</div>';
	  	echo '</div>';

		// Do any saving from quick start hook
		if (isset($_POST)):
            if (isset($_POST['wps_expand'])) echo '<input type="hidden" id="wps_expand" value="'.$_POST['wps_expand'].'" />';
			if (isset($_POST['wpspro_quick_start'])):
				do_action( 'wps_admin_quick_start_form_save_hook', $_POST);
			endif;
		endif;

		// Check that profile pages are set up
		if (!get_option('wpspro_profile_page')):
			echo '<div class="wps_error">'.__('You need to set the Profile pages, under "Profile Page" below...', WPS2_TEXT_DOMAIN).'</div>';
		endif;

		// Quick start hook
		echo '<div style="margin-top:15px;margin-bottom:15px;overflow:auto;">';
		do_action( 'wps_admin_quick_start_hook' );
		echo '</div>';

	  	echo '<p style="clear:both;">'.__('Click on a section title below to see options and help to get started.', WPS2_TEXT_DOMAIN).'</p>';
		if (!function_exists('__wps__wpspro_extensions_au'))
	  		echo '<p>'.sprintf(__('Loads more features are available from <a href="%s">www.wpsymposiumpro.com</a>.', WPS2_TEXT_DOMAIN), "http://www.wpsymposiumpro.com/shop").'</p>';

		// Do any saving
		if (isset($_POST['wpspro_update']) && $_POST['wpspro_update'] == 'yes'):
			do_action( 'wps_admin_setup_form_save_hook', $_POST);
		endif;
		if ( isset($_GET['wpspro_update']) ):
			do_action( 'wps_admin_setup_form_get_hook', $_GET);
		endif;

		echo '<form id="wps_setup" action="'.admin_url( 'admin.php?page=wps_pro_setup' ).'" method="POST">';
		echo '<input type="hidden" name="wpspro_update" value="yes" />';

			// Getting Started/Help hook
			do_action( 'wps_admin_getting_started_hook' );

		echo '<p><input type="submit" id="wps_setup_submit" name="Submit" class="button-primary" value="'.__('Save Changes', WPS2_TEXT_DOMAIN).'" /></p>';
			
		echo '</form>';

		
	echo '</div>';	  	

}

function wps_pro_shortcodes() {

	// Flush re-write rules, good idea if problem with linking, saves having to re-save permalink
	global $wp_rewrite;
	$wp_rewrite->flush_rules();	
    
    // Do any saving
    if (isset($_POST['wpspro_update']) && $_POST['wpspro_update'] == 'yes'):
        do_action( 'wps_admin_getting_started_shortcodes_save_hook', $_POST);
    endif;

  	echo '<div class="wrap">';
        	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';

        // Getting Started/Help hook
        do_action( 'wps_admin_getting_started_shortcodes_hook' );
		
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