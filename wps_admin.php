<?php

function wps_menu() {
	$menu_label = (defined('WPS_MENU')) ? WPS_MENU : 'WPS Pro';
	add_menu_page($menu_label, $menu_label, 'manage_options', 'wps_pro', 'wpspro_setup', 'none'); 
	add_submenu_page('wps_pro', __('Setup', WPS2_TEXT_DOMAIN), __('Setup', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_setup', 'wpspro_setup');
	add_submenu_page('wps_pro', __('Custom CSS', WPS2_TEXT_DOMAIN), __('Custom CSS', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_custom_css', 'wpspro_custom_css');
	add_submenu_page('wps_pro', __('Release notes', WPS2_TEXT_DOMAIN), __('Release notes', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_release_notes', 'wpspro_release_notes');
}

function wpspro_release_notes() {

  	echo '<div class="wrap" style="border-radius:3px; border: 1px solid #000; background-color: #fff; padding: 0 20px 0 20px; margin: 0px 30px 0px 30px;">';
        	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';
	  	echo '<style>';
			$show_button = ((!isset($_GET['page']) || (isset($_GET['page']) && $_GET['page'] != 'wps_pro_release_notes')));
			if (!$show_button)
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

	  		<p>These release notes are also available on the <a href="http://www.wpsymposiumpro.com/blog" target="_blank">WP Symposium Pro blog</a>. 
	  		They are shown automatically after updating the core plugin on your site, can be read again via your <a href="<?php echo admin_url('admin.php?page=wps_pro_release_notes&wps=hide'); ?>">WPS Pro->Release notes</a> admin menu item, and <a href="<?php echo admin_url('index.php?wps_hide_release_notes=1'); ?>">hidden</a> once you've read them.</p>

            <p><strong>If you are new to WP Symposium Pro, you will want to vist the <a href="<?php echo admin_url('admin.php?page=wps_pro_setup'); ?>">WPS Pro->Setup</a> page. On there, you will also find some helpful videos and links for support.</strong></p>

            <p>
            Welcome to 2015! There are some nice additions in this release, my favourite the profile page header image, hope you enjoy! Note that from this release, the <a href="http://www.wpsymposiumpro.com/a-complete-guide-to-wp-symposium-pro-book/">admin guide</a> is now going to replace the individual (and rather random) How-To web pages on <a href="www.wpsymposiumpro.com">www.wpsymposiumpro.com</a>.</p>

			<em><strong>Simon, WP Symposium developer</strong></em></p>

			<?php 
				if ($show_button):?>
				<form action="<?php echo admin_url('index.php?wps_hide_release_notes=1'); ?>" method="POST">
				<div style="background-color:#510051; border-radius:3px; padding: 20px; margin-top: 20px; width:100%; text-align:center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
					<input type="submit" class="wps_button" style="color:#000 !important;" value="Thanks, I've read them (or ignored them). Click here to hide them now..." />
				</div>
				</form>
			<?php endif; ?>

            <div style="border-top: 1px dotted #510051; margin-top: 20px; margin-bottom: 20px;"></div>

                <img src="<?php echo plugins_url( '', __FILE__ ); ?>/css/images/a_complete_guide_to_wp_symposium_pro.jpg" style="margin-right: 10px; float: left" />
                <div>
                    <div style="font-size:1.5em; line-height:1.2em; color: #510051; font-weight: bold;">The Complete Guide To WP Symposium Pro</div>
                    <p>A new book is being produced that you can <a href="http://www.wpsymposiumpro.com/a-complete-guide-to-wp-symposium-pro-book/">access online right now</a>! It's work in progress, but as it's nearing completion it will be of use now, so early access is available. It covers the core plugin, all the extensions and information for developers, up to the latest official release. To put everything in one place, this will, from 15.1 release, replace all the individual How-To help web pages. It is formatted such that it will also be available as a published print book when complete.</p>
                </div>

            <div style="clear:both; border-bottom: 1px dotted #510051; padding-top: 20px; margin-bottom: 20px;"></div>

            <table><tr>
				<td valign="top" style="padding-bottom: 110px;width:45%;">

					<div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">Core WP Symposium Pro plugin</div>
					<a href="http://www.wordpress.org/plugins/wp-symposium-pro" target-"_blank">Available from the WordPress repository</a><br />

                    <h3>Forums</h3>
                    <p>An optional alternative style option for forums can now be used, which mimicks the original WP Symposium forum. It makes more use of avatars and is considered a bit more friendly in appearance. Note that views start counting from activation, and exclude post owner views of the post. The following are for [wps-forum]:</p>
                    <ul>
                    	<li>'style' => 'classic' (defaults to 'table', if used, the following are applicable:)</li>
			            <li>'started' => 'Started by %s %s'</li>
			            <li>'replied' => 'Last replied to by %s %s'</li>
			            <li>'commented' => 'Last commented on by %s %s'</li>
			            <li>'size_posts' => 96 (size of avatar)</li>
			            <li>'size_replies' => 48 (size of avatar)</li>
			            <li>'post_preview' => 250 (maximum length in pixels)</li>
			            <li>'reply_preview' => 120 (maximum length in pixels)</li>
			            <li>'view_count_label' => 'VIEW' (singular)</li>
			            <li>'views_count_label' => VIEWS' (plural)</li>
			            <li>'reply_count_label' => 'REPLY' (singular)</li>
			            <li>'replies_count_label' => 'REPLIES' (plural)</li>
                    </ul>
                    <p>Add a featured image to forums (WPS Pro->Forum Setup->Edit) to include with [wps-forums]. Also added featured_image_width as an option (px) to [wps-forums].</p>
                    
                    <h3>Profile</h3>
                    <p>To hide town and country from edit profile set shortcode option town="" and country="" (or just one of them as you like).</p>
                    <p>Users are not subscribed to activity alerts by default now, to support local laws about opting in, but can opt-in via their Edit Profile page.</p>
                    
                    <h3>Developers</h3>
                    <p>Added wps_activity_post_post_form_filter filter to [wps-activity-post]</p>
                                            
				</td>
				<td style="width:5%">&nbsp;</td>
				<td valign="top" style="background-position: bottom right; background-repeat: no-repeat; background-image: url('<?php echo plugins_url( '/css/images/cup_of_tea.png', __FILE__ ); ?>');">

                    <div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">WP Symposium Pro Extensions</div>
					<a href="http://www.wpsymposiumpro.com/shop/" target-"_blank">Available from www.wpsymposiumpro.com</a><br />
                    
                    <h3>New extension: Share activity posts with Facebook</h3>
                    <p>Let your users share activity posts with their Facebook account. Activate the new extension via WPS Pro->Setup and see the new admin section entitled "Activity to Facebook" (you may need to refresh your page). You will need to create a new Facebook application. Instructions for this are all in the admin guide referred to above.</p>
                    
                    <h3>Profile Page Header Image</h3>
                    <p>Let your users upload a profile header image and use "behind" their avatar and display name. Create a profile extension as an image, and there is a new option to embed avatar and display name.</p>
                    
                    <h3>Gallery</h3>
                    <p>New 'target' option for [wps-gallery-grid], defaults to 'contents' (which will show album items below on the same page), or set to 'gallery' to go to gallery album page.</p>
                    <p>New 'areyousure' option for [wps-gallery], allowing you to tailor the confirmation message shown before deleting a gallery album.</p>

                    <h3>Login and Register</h3>
                    <p>Set an optional code required for registration via WPS Pro->Setup->Login & Register. Maybe to support private events? For this, a new 'registration_code' shortcode option for [wps-login-form].</p>
                    
                    <h3>Directory</h3>
                    <p>New shortcode option for [wps-directory] 'roles' as comma seperated list of WordPress roles to include, eg. 'contributor,subscriber'.</p>
                                        
				</td>
			</tr></table>

			<?php 
				if ($show_button):?>
				<form action="<?php echo admin_url('index.php?wps_hide_release_notes=1'); ?>" method="POST">
				<div style="background-color:#510051; border-radius:3px; padding: 20px; margin-top: 20px; width:100%; text-align:center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
					<input type="submit" class="wps_button" style="color:#000 !important;" value="Thanks, I've read them (or ignored them). Click here to hide them now..." />
				</div>
				</form>
			<?php endif; ?>
			<br style="clear:both">
	  	<?php
	  	echo '</div>';
		
	echo '</div>';	

	// Mark as read (in case coming from direct link)
	if (isset($_GET['wps']) && $_GET['wps'] == 'hide')
		delete_option('wp_symposium_pro_ver_show_release_notes');

	if ($show_button):
		echo '<p>&nbsp;</p>';
		echo '<style>#wpwrap { display: none; }</style>';
	endif;

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
		  		echo '<p style="font-weight:100;">'.__('Use the Quick Start buttons below, then', WPS2_TEXT_DOMAIN).'<br />';
		  		echo sprintf(__('add your new pages to your <a href="%s">WordPress menu</a>.', WPS2_TEXT_DOMAIN).'</p>', 'nav-menus.php').'</p>';
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

		echo '<form id="wps_setup" action="" method="POST">';
		echo '<input type="hidden" name="wpspro_update" value="yes" />';

			// Getting Started/Help hook
			do_action( 'wps_admin_getting_started_hook' );

		echo '<p><input type="submit" id="wps_setup_submit" name="Submit" class="button-primary" value="'.__('Save Changes', WPS2_TEXT_DOMAIN).'" /></p>';
			
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