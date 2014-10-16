<?php

function wps_menu() {
	$menu_label = (defined('WPS_MENU')) ? WPS_MENU : 'WPS Pro';
	add_menu_page($menu_label, $menu_label, 'manage_options', 'wps_pro', 'wpspro_setup', 'none'); 
	add_submenu_page('wps_pro', __('Setup', WPS2_TEXT_DOMAIN), __('Setup', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_setup', 'wpspro_setup');
	add_submenu_page('wps_pro', __('Custom CSS', WPS2_TEXT_DOMAIN), __('Custom CSS', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_custom_css', 'wpspro_custom_css');
	add_submenu_page('wps_pro', __('Release notes', WPS2_TEXT_DOMAIN), __('Release notes', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_release_notes', 'wpspro_release_notes');
}

function wpspro_release_notes() {

  	echo '<div class="wrap" style="border-radius:3px; border: 1px solid #000; background-color: #fff; padding: 0 20px 0 20px; margin: 30px 50px 40px 50px;">';
        	
	  	echo '<div id="icon-themes" class="icon32"><br /></div>';
	  	echo '<style>';
	  		echo '#wps_release_notes p, td, ol, a { font-size:14px; line-height: 1.3em; font-family:arial; }';
	  		echo '#wps_release_notes h1 { color: #510051; font-weight: bold; line-height: 1.2em; }';
	  		echo '#wps_release_notes h2 { color: #510051; margin-top: 10px; font-weight: bold; }';
	  		echo '#wps_release_notes h3 { color: #333; }';
	  	echo '</style>';
	  	echo '<div id="wps_release_notes"><br />';
	  	?>
	  		<img style="float:right; margin-left: 50px; margin-bottom: 50px;" src='<?php echo plugins_url( '', __FILE__ ); ?>/css/images/wps_logo.png' />
	  		<h1>WP Symposium Pro Release Notes (14.10)</h1>

            <p><strong>14.10.1: Additional items added to Groups, plus fix for YouTube Profile Extension</strong><br />
            <strong>14.10.2: Fix to activity posts when adding images to a gallery, and fix for Enfold theme</strong></p>

	  		<p>Current version of WP Symposium Pro installed: <?php echo get_option('wp_symposium_pro_ver'); ?></p>

	  		<p><em>These release notes are also available on the <a href="http://www.wpsymposiumpro.com/blog" target="_blank">WP Symposium Pro blog</a>. 
	  		They are shown automatically (just once) after updating the core plugin, and can be read again via your WPS Pro->Release notes admin menu item.</em></p>

			<p><strong>If you are new to WP Symposium Pro, you will want to vist the <a href="<?php echo admin_url('admin.php?page=wps_pro_setup'); ?>">WPS Pro->Setup</a> page. On there are some helpful videos and links for support.</strong></p>

			<p>This release is a bit of a monster, considering it's only a few weeks since the last one! There are a large number of additional features, and changes, so please read through them all.</p>

			<p>The most important ones to highlight are:</p>
			<ol>
				<li>For the Mail Extension, you <strong>must</strong> set your Mail page via WPS Pro-&gt;Setup-&gt;Mail after upgrading.</li>
				<li>For Forums, you probably want to set the period of time it takes for a post to automatically close, now available under WPS Pro-&gt;Setup-&gt;Forum as it's own setting, rather than using WordPress's own Settings-&gt;Discussion-&gt;"Automatically close comments on articles older than x days".</li>
				<li>If you were using the [wps-login-form] shortcode from the Login Redirect extension (now called "Login and Register"), and you have custom styles previously used, classes may have changed, please check!</li>
			</ol>
			<p>Thank you for supporting WP Symposium.<br />
			However you use it, I hope you have fun!<br />
			<em><strong>Simon, WP Symposium developer</strong> (and tea drinker)</em></p>

			<table><tr>
				<td valign="top" style="width:45%; background-repeat: no-repeat; background-position: bottom left; background-image: url('<?php echo plugins_url( '', __FILE__ ); ?>/css/images/cup_of_tea.png');">

					<h2>Core WP Symposium Pro plugin</h2>
					<a href="http://www.wordpress.org/plugins/wp-symposium-pro" target-"_blank">Available from the WordPress repository</a><br />

					<h3>Activity</h3>
					<ol>
						<li>Members can now hide posts on their activity posted by others</li>
						<li>Avatar now shown when adding a comment to activity posts. If you are using activity attachments extension, the page will reload (at your comment), otherwise no page reload.</li>
						<li>Can now set number of comments shown with [wps-activity comment_size=x]. Set text with comment_size_text_plural/comment_size_text_singular parameters.</li>
					</ol>
					<h3>Forums</h3>
					<ol>
						<li>Comments on forum post replies are now available (can be disabled with show_comments=0 on [wps-forum] shortcode)</li>
						<li>New option in WPS Pro-&gt;Setup-&gt;Forum to set when a forum post automatically closes due to inactivity to replace use of Settings-&gt;Discussion-&gt;"Automatically close comments on articles older than x days"</li>
						<li>Improved SEO meta settings for individual forum indexing (if accessible)</li>
					</ol>
					<h3>Miscellaneous</h3>
					<ol>
						<li>Added .wps_submit class to all &lt;input type="submit" /&gt; in WPS for quick and easy styling of buttons, e.g.: .wps_submit { border: 3px solid red !important; }</li>
						<li>Added wps_is_forum_page($id) core function to check if WordPress page with ID of $id is a forum page</li>
					</ol>

				</td>
				<td style="width:5%">&nbsp;</td>
				<td valign="top">

                    <h2>WP Symposium Pro Extensions</h2>
					<a href="http://www.wpsymposiumpro.com/shop/" target-"_blank">Available from www.wpsymposiumpro.com</a><br />

					<h3>Mail (Messages)</h3>
					<ol>
						<li>Mail renamed to Messages (to avoid confusion with email and notifications/alerts)</li>
                        <li>Added WPS Pro-&gt;Setup-&gt;Mail - the Mail page must be selected with this new option</li>
						<li>Can now delete mail items (icon beside mail message)</li>
						<li>Added show_hidden_text and hide_hidden_text to [wps-mail] shortcode</li>
					</ol>
					<h3>Individual Group</h3>
					<ol>
						<li>Groups can now be private! Edit a group to set it as private (admin must approve request to join)</li>
						<li>Requests to join private groups must be accepted by group admin (via list of group members)</li>
						<li>Added text_private to [wps-group-join-button] to inform user that group is private</li>
						<li>When deleting a group, confirmation is asked for</li>
                        <li>New shortcode [wps-group-image] to show group image, add to the top of your <strong>Group page</strong> (see below) [14.10.1]</li>
					</ol>
					<h3>Groups (list)</h3>
					<ol>
						<li>Can now upload a group image to show on the group page [14.10.1]</li>
                        <li>Changed date_label option for [wps-groups], now defaults to 'Last active %s ago'</li>
						<li>Changed default of show_date for [wps-groups] to 1 (change to 0 to hide last active text)</li>
						<li>Added orderby (active [default], created, title) and order (ASC or DESC [default]). When a group is visited, it is deemed as active, does not depend on posting activity content.</li>
                        <li>Default changed for [wps-group-post]. before='&lt;div style="clear:both"&gt;' and after = '&lt;/div&gt;' [14.10.1]</li>
                        <li>Added width as parameter, set to 0 to hide (replaced redundant avatar_size) [14.10.1]</li>
					</ol>
                    
					<h3>Forums</h3>
					<ol>
						<li>Can now automatically subscribe new users to forums (via WPS Pro->All Forums or Edit Forum)</li>
					</ol>
					<h3>Forum Security</h3>
					<ol>
						<li>Now applies to subscription links/buttons</li>
					</ol>
					<h3>Galleries</h3>
					<ol>
						<li>Set user_id = "all" with [wps-gallery-grid] and [wps-gallery-list] to show all users galleries</li>
					</ol>
					<h3>Profile Extensions</h3>
					<ol>
						<li>YouTube: can now set height to auto (responsive)</li>
						<li>Divider: new type to display title and/or any content on Edit Profile page (not used for data)</li>
					</ol>
					<h3>Likes/Dislikes</h3>
					<ol>
						<li>Rewards type added for likes and dislikes</li>
					</ol>
					<h3>Login Redirect (now called &quot;Login and Register&quot;)</h3>
					<ol>
						<li>Extra options added to the login form (eg. forgotten password)</li>
						<li>Have your own register page!</li>
                        <li>Optionally ask for display name and nickname on registration</li>
                        <li>Ask for profile extensions to be filled (dependent on type) and optionally mandatory</li>
						<li>If you have custom styles previously used, classes may have changed, please check!</li>
					</ol>
					<h3>Default Friends</h3>
					<ol>
						<li>Can now make friends with all existing users via WPS Pro-&gt;Setup-&gt;Default Friends</li>
					</ol>

				</td>
			</tr></table>

			<form action="<?php echo admin_url('index.php'); ?>" method="POST">
			<div style="background-color:#510051; border-radius:3px; padding: 20px; margin-top: 20px; width:100%; text-align:center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;">
				<input type="submit" class="wps_button" style="color:#000 !important;" value="Thanks, I've read them (or ignored them). Hide them now..." />
			</div>
			</form>
			<br style="clear:both">
	  	<?php
	  	echo '</div>';
		
	echo '</div>';	  	
	echo '<div style="border-bottom:2px solid #000;"></div>';

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