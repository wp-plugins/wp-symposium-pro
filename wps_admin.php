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

            <p><strong>If you are new to WP Symposium Pro, you will want to vist the <a href="<?php echo admin_url('admin.php?page=wps_pro_setup'); ?>">WPS Pro->Setup</a> page.</strong> On there, you will also find some helpful videos and links for support.
	  		These release notes, and previous release notes, are available on the <a href="http://www.wpsymposiumpro.com/blog" target="_blank">WP Symposium Pro blog</a>.</p>

            <p>
            Released on my birthday! This release focuses on the forum adding likes/dislikes, and the ability to mark a forum reply as an answer (if it suits the style of the forum).
            </p>

			<em><strong>Simon, WP Symposium developer</strong></em></p>

            <div style="border-top: 1px dotted #510051; margin-top: 20px; margin-bottom: 20px;"></div>

                <img src="<?php echo plugins_url( '', __FILE__ ); ?>/css/images/a_complete_guide_to_wp_symposium_pro.jpg" style="margin-right: 10px; float: left" />
                <div>
                    <div style="font-size:1.5em; line-height:1.2em; color: #510051; font-weight: bold;">The Complete Guide To WP Symposium Pro</div>
                    <p>A new book is being produced that you can <a href="http://www.wpsymposiumpro.com/a-complete-guide-to-wp-symposium-pro-book/">access online right now</a>! It's work in progress, but as it's nearing completion it will be of use now, so early access is available. It covers the core plugin, all the extensions and information for developers, up to the latest official release. To put everything in one place, this will, from 15.1 release, replace all the individual How-To help web pages. It is formatted such that it will also be available as a published print book when complete.</p>
                </div>

            <div style="clear:both; border-bottom: 1px dotted #510051; padding-top: 20px; margin-bottom: 20px;"></div>

            <table><tr>
				<td valign="top" style="background-position: bottom left; background-repeat: no-repeat; background-image: url('<?php echo plugins_url( '/css/images/cup_of_tea.png', __FILE__ ); ?>');padding-bottom: 220px;width:45%;">

					<div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">Core WP Symposium Pro plugin</div>
					<a href="http://www.wordpress.org/plugins/wp-symposium-pro" target-"_blank">Available from the WordPress repository</a><br />

                    <h3>Forums</h3>
                    <p>Added a new multiline option to [wps-forum-post] so you can set new forum post titles to have multiple lines. For example, multiline="3" to show three lines. Note, does not set the maximum number permitted, only the space initially shown to the user.</p>
                    
                    <h3>Activity</h3>
                    <p>Added logged_out_msg and login_url options for [wps-activity] (for message shown in place of activity when not logged in). The login_url option requires the Login &amp; Register Extension to be activated.</p>
                    <p>When an activity post or comment is deleted (by the owner or administrator), the corresponding alerts are deleted. However, emails may already have been sent depending on the alerts schedule.</p>
                    
                    <h3>Admin Tips</h3>
                    <p>Started to introduce helpful admin tips on the front end (can be dismissed, and only shown to site administrators)</p>
                    
				</td>
				<td style="width:5%">&nbsp;</td>
				<td valign="top" style="">

                    <div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">WP Symposium Pro Extensions</div>
					<a href="http://www.wpsymposiumpro.com/shop/" target-"_blank">Available from www.wpsymposiumpro.com</a><br />

                    <h3>New Forums Extension: Likes &amp; Dislikes</h3>
                    <p>Main options for [wps-forum] are allow_likes="1" and allow_dislikes="1". Includes new rewards. See wps_forum_likes_hooks_and_filters.php for all options. Guide will be updated on release.</p>

                    <h3>New Forums Extension: Answers</h3>
                    <p>Set a reply as an answer. Includes new rewards. Main option for [wps-forum] is allow_answer="1". Cannot select own answers. Rewards are removed if answer is unset. Guide will be updated for release.</p>
                    
                    <h3>Groups</h3>
                    <p>New default layout for group pages (you can delete existing and re-create via WPS Pro->Setup).</p>
                    <p>[wps_my_groups] shortcode now picks up if on a user's profile page to show their groups.</p>
                    
                    <h3>YouTube (Activity/Forum)</h3>
                    <p>Paste a YouTube URL in an activity or forum comment, and the video will now show there too.</p>
                                        
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

		echo '<form id="wps_setup" action="'.admin_url( 'admin.php?page=wps_pro_setup' ).'" method="POST">';
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