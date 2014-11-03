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
	  		<h1>WP Symposium Pro Release Notes (14.11)</h1>

	  		<p>Current version of WP Symposium Pro installed: <?php echo get_option('wp_symposium_pro_ver'); ?></p>

	  		<p><em>These release notes are also available on the <a href="http://www.wpsymposiumpro.com/blog" target="_blank">WP Symposium Pro blog</a>. 
	  		They are shown automatically (just once) after updating the core plugin, and can be read again via your WPS Pro->Release notes admin menu item.</em></p>

			<p><strong>If you are new to WP Symposium Pro, you will want to vist the <a href="<?php echo admin_url('admin.php?page=wps_pro_setup'); ?>">WPS Pro->Setup</a> page. On there are some helpful videos and links for support.</strong></p>

            <p>Following last months "monster" release, things have continued at the same pace! Here's a few highlights:</p>

            <ol>
                <li>New extension: Favorites, save any alert post as a favourite for later viewing using [wps-favorites].</li>
                <li>Set all site members as friends with each other, always - great for social networks where members tend to know each other</li>
                <li>New summary version of [wps-forum-show-posts], eg: <u>simon</u> replied to <u>A Topic</u> 5 mins ago <em>This is what I think...</em></li>
                <li>Gallery extensions, improving user's gallery albums with a new slideshow feature</li>
                <li>Login &amp; Register extension, giving you more flexibility on how users register on your site</li>
            </ol>

			<p>The number of ways WP Symposium Pro is being used now is just remarkable - thanks as always for your support!<br />
			<em><strong>Simon, WP Symposium developer</strong> (and tea drinker)</em></p>

			<table><tr>
				<td valign="top">

					<h2>Core WP Symposium Pro plugin</h2>
					<a href="http://www.wordpress.org/plugins/wp-symposium-pro" target-"_blank">Available from the WordPress repository</a><br />

                    <h3>Core Options</h3>
                    <p>Moved icon color (dark or light) from Profile Page to Core Options (WPS Pro->Setup->Core Options)</p>
                    <p>Added option for external links to open in new window, with optional suffix after external links (WPS Pro->Setup->Core Options)</p>
                    
                    <h3>Alerts</h3>
                    <p>You can now show alerts as a flag with count with the <a href="http://www.wpsymposiumpro.com/shortcodes/alerts/">[wps-alerts-activity]</a> shortcode, as on <a href="http://www.wpsymposiumpro.com">www.wpsymposiumpro.com</a>.</p>
                    <p>Added ability to mark all as read, delete individual alerts and delete all alerts when <a href="http://www.wpsymposiumpro.com/shortcodes/alerts/">shown on a webpage</a>.</p>
                    <p>Added make_all_read_text and delete_all_text (shown with <a href="http://www.wpsymposiumpro.com/shortcodes/alerts/">[wps-alerts-activity]</a>).</p>
                    
                    <h3>Friendships</h3>
                    <p>New option (under WPS Pro->Setup->Friendships) to set all site members as friends with each other, always.</p>
                    <p>This will over-ride all other settings, and particularly relevant to sites where members will tend to know each other, so the process of having to make friends is not required.</p>
                    <p>Can show pending friendship requests as a flag with count with <a href="http://www.wpsymposiumpro.com/shortcodes/friendships/">[wps-alerts-friends]</a>, as on <a href="http://www.wpsymposiumpro.com">www.wpsymposiumpro.com</a>.</p>
                    
                    <h3>Forum</h3>
                    <p>Added a new summary version of <a href="http://www.wpsymposiumpro.com/shortcodes/forum/">[wps-forum-show-posts]</a> to show a list of new posts, replies and comments (with options, see below). Please note that include_comments now refers to comments, and include_replies refers to replies.</p>
					<ol>
                        <li>changed include_replies option, now used for forum post replies (default 1)</li>
						<li>added include_comments option used for forum post reply comments (default 0)</li>
					</ol>
                    <p>The following options all relate to the summary view of the output, and assume that summary=1 (apart from summary itself):</p>
					<ol>
                        <li>summary (whether to show as summary information, default 0)</li>
                        <li>summary_format (if summary=1, format of text, default '%s %s %s %s ago %s')<br />
                            <em>example of output: [simon] [replied to] [This topic] [5 mins] ago [the snippet]</em></li>
                        <li>summary_started (text for 'started')</li>
                        <li>summary_replied (text for 'replied to')</li>
                        <li>summary_commented (text for 'commented on')</li>
                        <li>summary_title_length (maximum length of title, default 50)</li>
                        <li>summary_snippet_length (maximum length of snippet, default 50, set to 0 to hide)</li>
                        <li>summary_avatar_size (size of avatar, default 32, set to 0 to hide)</li>
                        <li>summary_show_unread (whether to bold topics with unread content, default 1)</li>
					</ol>                    
                    <p>For example:</p>
                    <div style="text-align: center; font-family:courier; font-size: 0.9em; background-color: #efefef; padding:4px;">[wps-forum-show-posts include_comments="1" summary_snippet_length="200"]</div>
                    <p>Added title_length to [wps-forum], [wps-forums] and [wps_forum_show_posts] to limit characters of forum titles (default, 50)</p>
                    <p>Added show_count, show_last_activity and show_freshess to [wps-forums] and [wps-forum]</p>
                    <p>Removed show_replies from [wps-forum] (replaced with show_count for consistency)</p>
                    <p>Added level_0_links to [wps-forums] set to 0, to disable links for top level forums</p>
                        
				</td>
				<td style="width:5%">&nbsp;</td>
				<td valign="top" style="background-position: bottom right; padding-bottom: 210px;width:45%; background-repeat: no-repeat; background-image: url('<?php echo plugins_url( '/css/images/cup_of_tea.png', __FILE__ ); ?>');">

                    <h2>WP Symposium Pro Extensions</h2>
					<a href="http://www.wpsymposiumpro.com/shop/" target-"_blank">Available from www.wpsymposiumpro.com</a><br />

                    <h3>Favourites (New Extension)</h3>
                    <p>Let members save activity posts as favourites, using the <a href="http://www.wpsymposiumpro.com/shortcodes/favorites/">[wps-favorites]</a> shortcode to display them</p>
                    
                    <h3>Private Messages (previously Mail)</h3>
                    <p>Can now show new message(s) alert as a flag with count with <a href="http://www.wpsymposiumpro.com/shortcodes/private-mail/">[wps-alerts-mail]</a> as on <a href="http://www.wpsymposiumpro.com">www.wpsymposiumpro.com</a></p>
                    <p>Can now mark all as read, added mark_all_read_text to <a href="http://www.wpsymposiumpro.com/shortcodes/private-mail/">[wps-mail]</a></p>
                    
					<h3>Gallery</h3>
                    <p>Added a new slideshow feature, with the following new options for <a href="http://www.wpsymposiumpro.com/shortcodes/galleries">[wps-gallery]</a>. As well as the icons, you can also use left/right on your keyboard to navigate or click on the "dots" that relate to the album images. Click the image to zoom (or use the icon). The slideshow is responsive to mobile devices.</p>
					<ol>
                        <li>show_slideshow (whether default view of album is as slideshow instead of thumbnails/comments, defaults to 0)</li>
						<li>slideshow_link and slideshow_link_hide (text of links).</li>
                        <li>Set slideshow_link to '' to disable slideshow.</li>
					</ol>

                    <h3>Groups</h3>
                    <p>Fixed single post view and added back_to to <a href="http://www.wpsymposiumpro.com/shortcodes/groups/">[wps-group-activity]</a> for link back to group, default "Back to %s..."</p>
                    <p>Added header_text as option to <a href="http://www.wpsymposiumpro.com/shortcodes/groups/">[wps-groups]</a>, default '&lt;h2&gt;Groups&lt;/h2&gt;'&gt;.</p>
                    <p>New shortcode <a href="http://www.wpsymposiumpro.com/shortcodes/groups/">[wps-my-groups]</a> to display groups current user is a member of, similar parameters as [wps-groups].</p>
                    
					<h3>Login &amp; Register</h3>
					<ol>
						<li>Added password option to set whether users enter a password when registering, default 0 (sent via email).</li>
						<li>Added name option to set whether users enter first/family name when registering, default 1.</li>
						<li>Added registration_url to redirect users after registration (perhaps to a page with more info or next steps?)</li>
                        <li>Added register_auto to automatically login after registration, default 0.</strong></li>
					</ol>

                    <p>An example combination for the new <a href="http://www.wpsymposiumpro.com/shortcodes/login-redirect/">Login &amp; Register</a> options, to allow users to register on your site, entering a password as they do so, and then redirect them to their change avatar page (for example, /change-avatar) would be:</p>
                    <div style="text-align: center; font-family:courier; font-size: 0.9em; background-color: #efefef; padding:4px;">[wps-login-form password="1" register_auto="1" registration_url="/change-avatar"]</div>
                    <p><strong><em>Whilst this streamlines the registration process, I urge you to consider whether allowing visitors to set their password and automatically login is appropriate and suitably secure for your site.</em></strong></p>

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