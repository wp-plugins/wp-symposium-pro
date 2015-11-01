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
	add_submenu_page(get_option('wps_core_admin_icons') ? 'wps_pro' : '', __('Custom CSS', WPS2_TEXT_DOMAIN), __('Custom CSS', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_custom_css', 'wpspro_custom_css');
	add_submenu_page('wps_pro', __('Licence', WPS2_TEXT_DOMAIN), __('Licence', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_licence', 'wps_pro_licence');
	add_submenu_page(get_option('wps_core_admin_icons') ? 'wps_pro' : '', __('Clear all WPS data', WPS2_TEXT_DOMAIN), __('Clear all WPS data', WPS2_TEXT_DOMAIN), 'manage_options', 'wps_pro_reset', 'wps_pro_reset');
	remove_submenu_page('wps_pro','wps_pro');
}

function wpspro_manage() {

    if (!get_option('wps_core_admin_icons')):

	$values = get_option('wps_default_extensions');
	$values = $values ? explode(',', $values) : array();	
/*
	// Core
	if (in_array('ext-alerts-customise', $values)) 	require_once('wp-symposium-pro-alerts-customise/wps_alerts_customise.php');
	if (in_array('ext-login', $values)) 			require_once('wp-symposium-pro-login/wps_login.php');
	if (in_array('ext-system-messages', $values)) 	require_once('wp-symposium-pro-system-messages/wps_system_messages.php');
	if (in_array('ext-menu-alerts', $values)) 		require_once('wp-symposium-pro-menu-alerts/wps_menu_alerts.php');
	// Activity
	if (in_array('ext-attachments', $values)) 		require_once('wp-symposium-pro-attachments/wps_attachments.php');
	if (in_array('ext-soundcloud', $values)) 		require_once('wp-symposium-pro-soundcloud/wps_soundcloud.php');
	if (in_array('ext-youtube', $values)) 			require_once('wp-symposium-pro-youtube/wps_youtube.php');
	if (in_array('ext-remote', $values)) 			require_once('wp-symposium-pro-activity-url-preview/wps_activity_url_preview.php');
	if (in_array('ext-activity-facebook', $values)) require_once('wp-symposium-pro-activity-facebook/wps_activity_facebook.php');
	// Members
	if (in_array('ext-security', $values)) 			require_once('wp-symposium-pro-security/wps_security.php');
	if (in_array('ext-directory', $values)) 		require_once('wp-symposium-pro-directory/wps_directory.php');
	if (in_array('ext-default-friends', $values)) 	require_once('wp-symposium-pro-default-friends/wps_default_friends.php');
	if (in_array('ext-likes', $values)) 			require_once('wp-symposium-pro-likes/wps_likes.php');
	if (in_array('ext-tags', $values)) 			    require_once('wp-symposium-pro-tags/wps_tags.php');
	// Forum
	if (in_array('ext-forum-attachments', $values)) require_once('wp-symposium-pro-forum-attachments/wps_forum_attachments.php');
	if (in_array('ext-forum-search', $values)) 		require_once('wp-symposium-pro-forum-search/wps_forum_search.php');
	if (in_array('ext-forum-security', $values)) 	require_once('wp-symposium-pro-forum-security/wps_forum_security.php');
	if (in_array('ext-forum-signature', $values)) 	require_once('wp-symposium-pro-forum-signature/wps_forum_signature.php');
	if (in_array('ext-forum-to-activity', $values)) require_once('wp-symposium-pro-forum-to-activity/wps_forum_to_activity.php');
	if (in_array('ext-forum-toolbar', $values)) 	require_once('wp-symposium-pro-forum-toolbar/wps_forum_toolbar.php');
	if (in_array('ext-forum-youtube', $values)) 	require_once('wp-symposium-pro-forum-youtube/wps_forum_youtube.php');
	if (in_array('ext-forum-likes', $values)) 		require_once('wp-symposium-pro-forum-likes/wps_forum_likes.php');
	if (in_array('ext-forum-answer', $values)) 		require_once('wp-symposium-pro-forum-answer/wps_forum_answer.php');
	// Groups
	if (in_array('ext-default-groups', $values)) 	require_once('wp-symposium-pro-default-groups/wps_default_groups.php');
	// Mail
	if (in_array('ext-mail-attachments', $values)) 	require_once('wp-symposium-pro-mail-attachments/wps_mail_attachments.php');
	if (in_array('ext-mail-subs', $values)) 		require_once('wp-symposium-pro-mail-subs/wps_mail_subs.php');
	if (in_array('ext-mail-youtube', $values)) 		require_once('wp-symposium-pro-mail-youtube/wps_mail_youtube.php');
	// Miscellaneous
	if (in_array('ext-favourites', $values)) 		require_once('wp-symposium-pro-favourites/wps_favourites.php');
	if (in_array('ext-show-posts', $values)) 		require_once('wp-symposium-pro-show-posts/wps_show_posts.php');
	if (in_array('ext-migrate', $values)) 			require_once('wp-symposium-pro-migrate/wps_migrate.php');
*/

	  	echo '<div id="wps_admin_admin_links">';

		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Configure', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	if (in_array('ext-extended', $values)) 		echo '<li class="wps_icon_profile"><a href="edit.php?post_type=wps_extension">'.__('Setup Profile Extensions', WPS2_TEXT_DOMAIN).'</a></li>';
			  	if (in_array('ext-rewards', $values))		echo '<li class="wps_icon_rewards"><a href="edit.php?post_type=wps_rewards">'.__('Setup Rewards', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '<li class="wps_icon_forums"><a href="edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post">'.__('Advanced Forum Setup', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '<li class="wps_icon_css"><a href="admin.php?page=wps_pro_custom_css">'.__('Custom CSS', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '<li class="wps_icon_reset"><a href="admin.php?page=wps_pro_reset">'.__('Clear all WPS Pro data', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';

		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('User', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_activity"><a href="edit.php?post_type=wps_activity">'.__('Manage Activity Posts', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '<li class="wps_icon_friends"><a href="edit.php?post_type=wps_friendship">'.__('Manage Friendships', WPS2_TEXT_DOMAIN).'</a></li>';
			  	if (in_array('ext-rewards', $values))		echo '<li class="wps_icon_rewards"><a href="edit.php?post_type=wps_reward">'.__('Manage Rewards Given', WPS2_TEXT_DOMAIN).'</a></li>';
			  	if (in_array('ext-crowds', $values))		echo '<li class="wps_icon_whoto"><a href="edit.php?post_type=wps_crowd">'.__('Manage "Who To" lists', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';

		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Forums', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_forums"><a href="admin.php?page=wpspro_forum_setup">'.__('Manage All Forums', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '<li class="wps_icon_forums"><a href="edit.php?post_type=wps_forum_post">'.__('Manage Forum Posts', WPS2_TEXT_DOMAIN).'</a></li>';
			  	if (in_array('ext-forum-extended', $values))echo '<li class="wps_icon_forums"><a href="admin.php?page=wpspro_forum_setup">'.__('Forum Extensions', WPS2_TEXT_DOMAIN).'</a></li>';
			  	if (in_array('ext-forum-subs', $values))	echo '<li class="wps_icon_subs"><a href="edit.php?post_type=wps_forum_subs">'.__('Forum Subscriptions', WPS2_TEXT_DOMAIN).'</a></li>';
			  	if (in_array('ext-forum-subs', $values))	echo '<li class="wps_icon_subs"><a href="edit.php?post_type=wps_subs">'.__('Topic Subscriptions', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';

		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Alerts', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_alerts"><a href="edit.php?post_type=wps_alerts">'.__('Manage Alerts', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
			  	echo '<p>'.__('Clear out your sent and pending alerts regularly.', WPS2_TEXT_DOMAIN).'</p>';
		  	echo '</div>';

		  	if (in_array('ext-groups', $values)):
		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Groups', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_groups"><a href="edit.php?post_type=wps_group">'.__('Manage Groups', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '<li class="wps_icon_groups"><a href="edit.php?post_type=wps_group_members">'.__('Group Members', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';
		  	endif;

		  	if (in_array('ext-gallery', $values)):
		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Galleries', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_galleries"><a href="edit.php?post_type=wps_gallery">'.__('Manage Galleries', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';
		  	endif;

		  	if (in_array('ext-mail', $values)):
		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Private Messages', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_mail"><a href="edit.php?post_type=wps_mail">'.__('Manage Messages', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';
		  	endif;

		  	if (in_array('ext-lounge', $values)):
		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Lounge', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_lounge"><a href="edit.php?post_type=wps_lounge">'.__('Manage Lounge Chat', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';
		  	endif;

		  	if (in_array('ext-calendar', $values)):
		  	echo '<div class="wps_manage_left">';
			  	echo '<h3>'.__('Calendars', WPS2_TEXT_DOMAIN).'</h3>';
			  	echo '<ul class="wps_manage_icons">';
			  	echo '<li class="wps_icon_calendars"><a href="edit.php?post_type=wps_calendar">'.__('Manage Calendars', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '<li class="wps_icon_calendars"><a href="edit.php?post_type=wps_event">'.__('Manage Calendar Events', WPS2_TEXT_DOMAIN).'</a></li>';
			  	echo '</ul>';
		  	echo '</div>';
		  	endif;

		echo '</div>';

		echo '<div style="clear:both"></div>';

	endif;
}

function wpspro_release_notes() {

  	echo '<div class="wrap">';
        	
	  	echo '<style>';
            echo '.wrap { margin-top: 30px !important; margin-left: 10px !important; }';
	  		echo '#wps_release_notes p, td, ol, a { font-size:14px; line-height: 1.3em; font-family:arial; }';
	  		echo '#wps_release_notes h1 { color: #510051; font-weight: bold; line-height: 1.2em; }';
	  		echo '#wps_release_notes h2 { color: #510051; margin-top: 10px; font-weight: bold; }';
	  		echo '#wps_release_notes h3 { color: #333; }';
	  	echo '</style>';
	  	echo '<div id="wps_release_notes">';
	  		echo '<div id="wps_welcome_bar" style="margin-top: 20px;">';
		  		echo '<img id="wps_welcome_logo" style="width:56px; height:56px; float:left;" src="'.plugins_url('../wp-symposium-pro/css/images/wps_logo.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" />';
		  		echo '<div style="font-size:2em; line-height:1em; font-weight:100; color:#fff;">'.__('Welcome to WP Symposium Pro', WPS2_TEXT_DOMAIN).'</div>';
		  		echo '<p style="color:#fff;"><em>'.__('The ultimate social network plugin for WordPress', WPS2_TEXT_DOMAIN).'</em></p>';
	  		echo '</div>';

	  		echo '<div style="font-size:1.4em; margin-top:20px">'.__('Thank you for installing WP Symposium Pro!', WPS2_TEXT_DOMAIN).'</div>';

	  		?>

            <p>
            	<?php echo sprintf(__('If you are new to WP Symposium Pro, you will want to visit the <a href="%s">Setup page</a>...', WPS2_TEXT_DOMAIN), admin_url('admin.php?page=wps_pro_setup')); ?>
            </p>

            <p>
            	<?php echo sprintf(__('Please don\'t forget to like our <a href="%s" target="_blank">Facebook page</a>, or follow @wpsymposium on <a href="%s" target="_blank">Twitter</a>, to get all the latest news and release announcements.', WPS2_TEXT_DOMAIN), 'https://www.facebook.com/wpsymposium', 'http://twitter.com/wpsymposium'); ?>
            </p>

			<em><strong>Simon, WP Symposium developer (and tea drinker)</strong></em></p>

            <div style="border-top: 1px dotted #510051; overflow:auto; margin-top: 20px; padding-top: 20px; padding-bottom: 20px;">

                <img src="<?php echo plugins_url( '', __FILE__ ); ?>/css/images/a_complete_guide_to_wp_symposium_pro.jpg" style="margin-left: 40px; float: right; padding: 5px; border-radius: 5px; background-color:#fff;" />
                <div>
                    <div style="font-size:1.5em; line-height:1.2em; color: #510051; font-weight: bold;"><?php _e('The Complete Guide To WP Symposium Pro', WPS2_TEXT_DOMAIN); ?></div>
                    <p>
                    	<?php echo sprintf(__('You can <a target="_blank" href="%s">read this book online right now</a>. It covers all the shortcodes and options for the core plugin, plus all the features of the WP Symposium Pro Extensions plugin. Also included are examples and additional information for developers. It is formatted such that it can be available as a published print book.', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/a-complete-guide-to-wp-symposium-pro-book/'); ?>
                    </p>
                </div>

            </div>
            <div style="clear:both; border-top: 1px dotted #510051; font-size: 1.4em; padding-top: 20px; margin-bottom: 20px;">Release Notes</div>
	  		<p>
	  			<?php echo sprintf(__('These are the release notes for version %s. Previous release notes are available on the <a href="%s" target="_blank">WP Symposium Pro blog</a>.', WPS2_TEXT_DOMAIN), get_option('wp_symposium_pro_ver'), 'http://www.wpsymposiumpro.com/blog'); ?>
	  		</p>


            <?php
            $cup_position = 'right';
            if ($cup_position == 'left'):
                $cup_of_tea_left = "background-position: bottom left; background-repeat: no-repeat; background-image: url('".plugins_url( '/css/images/cup_of_tea.png', __FILE__ )."');";
                $cup_of_tea_right = "";
            elseif ($cup_position == 'right'):
                $cup_of_tea_left = "";
                $cup_of_tea_right = "background-position: bottom right; background-repeat: no-repeat; background-image: url('".plugins_url( '/css/images/cup_of_tea.png', __FILE__ )."');";
            else: // center
                $cup_of_tea_left = "background-position: bottom center; background-repeat: no-repeat; background-image: url('".plugins_url( '/css/images/cup_of_tea.png', __FILE__ )."');";
                $cup_of_tea_right = "";
            endif;
            ?>

            <table><tr>
				<td valign="top" class="wps_release_notes" style="<?php echo $cup_of_tea_left; ?>width:45%;">

					<div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">Core WP Symposium Pro plugin</div>
					<a href="http://www.wordpress.org/plugins/wp-symposium-pro" target-"_blank">Available from the WordPress repository</a><br />

         			<h3>Edit Profile</h3>
         			<p>If there is a problem when saving, the Tab with the problem in it also highlights as error, so if not on current tab, is clearer.</p>

         			<h3>Activity</h3>
         			<p>Added active_friends as option to [wps-activity] to assist in performance increase</p>

         			<h3>Forum</h3>
                    <p>Improved CSS of mobile forum post view, including removal of initial post author avatar.</p>
                         
                    <h3>Core</h3>
                    <p>Added constraint as second parameter to wps_get_friends().</p>    
				
                </td>
				<td style="width:1%">&nbsp;</td>
				<td valign="top" class="wps_release_notes" style="<?php echo $cup_of_tea_right; ?>">

                    <div style="font-size:1.6em; line-height:1.6em; color: #510051; font-weight: bold;">WP Symposium Pro Extensions</div>
					<a href="http://www.wpsymposiumpro.com/licenses/" target-"_blank">Available from www.wpsymposiumpro.com</a><br />

					<h3>Forum Toolbar</h3>
					<p>Fix for Firefox with BB Code toolbar.</p>

					<h3>Login/Register</h3>
					<p>Fix for Firefox with BB Code toolbar.</p>
                    
					<h3>Licence Code</h3>
					<p>Following its introduction in a recent release, if you don't enter your licence code, a nag message will be shown on your site (not shown on localhost).
					It is a shame that the actions of a few people force this on others. If you have purchased a lifetime licence, we will provide a <a href="mailto:support@wpsymposiumpro.com">one-off code</a>.
					<br /><br />
					If you have purchased the Extensions plugin, simply go to the <a href="http://www.wpsymposiumpro.com/licence-code">licence code page on the WP Symposium Pro website</a>, copy your licence code, and paste it on your <a href="<?php echo admin_url( 'admin.php?page=wps_pro_licence' ); ?>">licence admin page</a>. 
					If you have any problems, please <a href="mailto:support@wpsymposiumpro.com">email support</a>.</p>

					<div style="width:10px;height:200px"><!-- buffer for cup of tea spacing --></div>
					
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
  	echo '<style>';
        echo '.wrap { margin-top: 20px !important; margin-left: 10px !important; }';
  	echo '</style>';

  	echo '<div class="wrap">';
        	
	  	$show_header = get_option('wps_show_welcome_header') ? ' style="display:none; "' : '';

	  	echo '<div '.$show_header.'id="wps_welcome">';
	  		echo '<div id="wps_welcome_bar">';
		  		echo '<img id="wps_welcome_logo" style="width:56px; height:56px; float:left;" src="'.plugins_url('../wp-symposium-pro/css/images/wps_logo.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" />';
		  		echo '<div style="font-size:2em; line-height:1em; font-weight:100; color:#fff;">'.__('Welcome to WP Symposium Pro', WPS2_TEXT_DOMAIN).'</div>';
		  		echo '<p style="color:#fff;"><em>'.__('The ultimate social network plugin for WordPress', WPS2_TEXT_DOMAIN).'</em></p>';
	  		echo '</div>';
	  		echo '<div style="width:30%; min-width:200px; margin-right:10px; float: left;">';
		  		echo '<p style="font-size:1.4em; font-weight:100;">'.__('How to get started...', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-weight:100;">'.__('Use the Quick Start buttons below,', WPS2_TEXT_DOMAIN).'<br />';
		  		echo sprintf(__('add your new pages to your <a href="%s">WordPress menu</a>,  then', WPS2_TEXT_DOMAIN), 'nav-menus.php').'<br />';
		  		echo sprintf(__('customize via <a href="%s">Shortcodes</a> (via the menu).', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wps_pro_shortcodes' )).'</p>';
		  		echo '<p style="font-size:1.4em; font-weight:100;">'.__('How to get support...', WPS2_TEXT_DOMAIN).'</p>';
		  		echo '<p style="font-weight:100;">'.sprintf(__('Support is available at <a target="_blank" href="%s">www.wpsymposiumpro.com</a>', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com').'<br />';
		  		echo sprintf(__('with <a href="%s" target="_blank">forums</a>, <a href="%s" target="_blank">helpdesk</a>, and live chat support.', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/forums/', 'http://www.wpsymposiumpro.com/helpdesk/').'</p>';
		  		echo '<p style="font-weight:100;">'.sprintf(__('We also have more <a target="_blank" href="%s">video tutorials</a>...', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/getting-started-videos/').'</p>';
	  		echo '</div>';
	  		echo '<div class="wps_setup_video_div">';
		  		echo '<p style="font-size:1.4em; font-weight:100;">'.__('Setting up WP Symposium Pro', WPS2_TEXT_DOMAIN).'</p>';
	            echo '<div class="wps_video_container" style="margin-bottom:-30px;">';
				echo '<iframe class="wps_setup_video_iframe" src="//www.youtube.com/embed/8beh25UWQOs?feature=player_embedded&showinfo=0&rel=0&autohide=1&vq=hd720" frameborder="0" allowfullscreen></iframe>';
				echo '</div>';
	  		echo '</div>';
	  		echo '<div class="wps_setup_video_div">';
		  		echo '<p style="font-size:1.4em; font-weight:100;">'.__('Installing and Activating more Extensions', WPS2_TEXT_DOMAIN).'</p>';
	            echo '<div class="wps_video_container" style="margin-bottom:-30px;">';
				echo '<iframe class="wps_setup_video_iframe" src="//www.youtube.com/embed/It3bJ0IGy2M?feature=player_embedded&showinfo=0&rel=0&autohide=1&vq=hd720" frameborder="0" allowfullscreen></iframe>';
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

		// Show and hide header
		echo '<div style="float:right"><a id="wps_hide_welcome_header" style="text-decoration:none;" href="javascript:void(0); return false;">'.__('Show/Hide Welcome', WPS2_TEXT_DOMAIN).'</a></div>';

		// Check that profile pages are set up
		if (!get_option('wpspro_profile_page')):
			echo '<div class="wps_error">'.__('You need to set the Profile pages, under "Profile Page" below...', WPS2_TEXT_DOMAIN).'</div>';
		endif;

		// Quick start hook
		echo '<div style="width: 300px; float: left; font-size:1.8em; margin-bottom:15px;">'.__('Quick Start', WPS2_TEXT_DOMAIN).'</div>';
		echo '<div style="clear: both; margin-bottom:15px;overflow:auto;">';
		do_action( 'wps_admin_quick_start_hook' );
		echo '</div>';

		// Admin links
		$hide_icons = get_option('wps_core_admin_icons');
		if ($hide_icons):
			echo '<div style="float:right"><a id="wps_hide_admin_links_show" style="text-decoration:none;" href="javascript:void(0); return false;">'.__('Move admin links here', WPS2_TEXT_DOMAIN).'</a></div>';
		else:
			echo '<div style="float:right"><a id="wps_hide_admin_links" style="text-decoration:none;" href="javascript:void(0); return false;">'.__('Move admin links to dashboard menu', WPS2_TEXT_DOMAIN).'</a></div>';
		endif;
		wpspro_manage();		

		// Option Sections
	  	echo '<p style="clear:both;">'.__('Click on a section title below to see options and help to get started.', WPS2_TEXT_DOMAIN).'</p>';
		if (!function_exists('__wps__wpspro_extensions_au'))
	  		echo '<p>'.sprintf(__('Loads more features are available from <a href="%s">www.wpsymposiumpro.com</a>.', WPS2_TEXT_DOMAIN), "http://www.wpsymposiumpro.com/licenses").'</p>';

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

function wps_pro_reset() {

  	echo '<div class="wrap">';
        	
	  	echo '<style>';
            echo '.wrap { margin-top: 30px !important; margin-left: 10px !important; }';
	  	echo '</style>';
        	
  		echo '<div id="wps_welcome_bar" style="margin-top: 20px;">';
	  		echo '<img id="wps_welcome_logo" style="width:56px; height:56px; float:left;" src="'.plugins_url('../wp-symposium-pro/css/images/wps_logo.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" />';
	  		echo '<div style="font-size:2em; line-height:1em; font-weight:100; color:#fff;">'.__('Welcome to WP Symposium Pro', WPS2_TEXT_DOMAIN).'</div>';
	  		echo '<p style="color:#fff;"><em>'.__('The ultimate social network plugin for WordPress', WPS2_TEXT_DOMAIN).'</em></p>';
  		echo '</div>';

  		echo '<div style="font-size:1.4em; margin-top:20px">'.__('WP Symposium Pro data removal (reset)', WPS2_TEXT_DOMAIN).'</div>';

		echo '<p>'.__('Use this screen to reset WP Symposium Pro, or remove all data before you uninstall the plugin.', WPS2_TEXT_DOMAIN).'</p>';

		// admins only!
		if (current_user_can('manage_options')):

			// ... instructed to reset?
			if (isset($_POST['wps_pro_reset_confirm'])):
				if (wp_verify_nonce( $_POST['wps_pro_reset_nonce'], 'wps_pro_reset' )) {
					// reset!
                    global $wpdb, $wp_rewrite;
                    if (is_multisite()) {
                        $blogs = $wpdb->get_results("SELECT blog_id FROM ".$wpdb->base_prefix."blogs");
                        if ($blogs) {
                            foreach($blogs as $blog) {
                              switch_to_blog($blog->blog_id);
                              echo '<div class="wps_warning">'.sprintf(__('Switching to blog ID %d', WPS2_TEXT_DOMAIN), $blog->blog_id).'</div>';
                                    echo '<div class="wps_warning">';
                                    __wps_pro_uninstall_delete();
                                    echo __('Removing local files', WPS2_TEXT_DOMAIN).'... ';
                                    __wps_pro_uninstall_rrmdir(WP_CONTENT_DIR.'/wps-pro-content');
                                    echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
                                    echo __('Flushing WordPress', WPS2_TEXT_DOMAIN).'... ';                        
                        			$wp_rewrite->flush_rules();
                        			echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
									echo '</div><div class="wps_success">'.__('Complete', WPS2_TEXT_DOMAIN).'</div>';
									echo '<p>'.__('You will need to remove any pages that you created.', WPS2_TEXT_DOMAIN).'</p>';
                            }
                            restore_current_blog();
                        }   
                    } else {
                    	echo '<div class="wps_warning">';
                        __wps_pro_uninstall_delete();
						echo __('Removing local files', WPS2_TEXT_DOMAIN).'... ';                        
                        __wps_pro_uninstall_rrmdir(WP_CONTENT_DIR.'/wps-pro-content');
						echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
						echo __('Flushing WordPress', WPS2_TEXT_DOMAIN).'... ';                        
                        $wp_rewrite->flush_rules();
						echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
						echo '</div><div class="wps_success">'.__('Complete', WPS2_TEXT_DOMAIN).'</div>';
						echo '<p>'.__('You will need to remove any pages that you created.', WPS2_TEXT_DOMAIN).'</p>';
                    }

				} else {
					echo '<div class="wps_error">'.__('NONCE failed - suspicious activity, reset cancelled', WPS2_TEXT_DOMAIN).'</div>';
				}

			else:

				echo '<div class="wps_warning">'.__('This cannot be un-done - please make sure you take a site database backup first (in case of problems or mistake)!', WPS2_TEXT_DOMAIN).'</div>';

			endif;

			echo '<form onsubmit="return confirm(\''.__('Are you sure? Last chance!', WPS2_TEXT_DOMAIN).'\')" action="'.admin_url( 'admin.php?page=wps_pro_reset' ).'" method="POST">';
				wp_nonce_field( 'wps_pro_reset', 'wps_pro_reset_nonce' );				
				echo '<input type="hidden" name="wps_pro_reset_confirm" value="Y" />';
				echo '<input type="submit" class="button-primary" value="'.__('Clear all WPS Pro data', WPS2_TEXT_DOMAIN).'" />';			
			echo '</form>';

		else:

			echo '<div class="wps_error">'.__('Only available to site administrators.', WPS2_TEXT_DOMAIN).'</div>';

		endif;

}

function wps_pro_licence() {

  	echo '<div class="wrap">';
        	
	  	echo '<style>';
            echo '.wrap { margin-top: 30px !important; margin-left: 10px !important; }';
	  	echo '</style>';
        	
  		echo '<div id="wps_welcome_bar" style="margin-top: 20px;">';
	  		echo '<img id="wps_welcome_logo" style="width:56px; height:56px; float:left;" src="'.plugins_url('../wp-symposium-pro/css/images/wps_logo.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" />';
	  		echo '<div style="font-size:2em; line-height:1em; font-weight:100; color:#fff;">'.__('Welcome to WP Symposium Pro', WPS2_TEXT_DOMAIN).'</div>';
	  		echo '<p style="color:#fff;"><em>'.__('The ultimate social network plugin for WordPress', WPS2_TEXT_DOMAIN).'</em></p>';
  		echo '</div>';

  		echo '<div style="font-size:1.4em; margin-top:20px">'.__('WP Symposium Pro Extensions Plugin Licence', WPS2_TEXT_DOMAIN).'</div>';

		echo '<p>'.sprintf(__('The core WP Symposium Pro plugin is free, available from <a href="%s" target="_blank">wordpress.org</a>. No licence code is required for the core plugin.', WPS2_TEXT_DOMAIN), 'http://www.wordpress.org/plugins/wp-symposium-pro').'</p>';
		echo '<p>'.sprintf(__('If you have the <a href="%s" target="_blank">WP Symposium Pro Extensions plugin</a> activated, you will need to enter a licence code as proof of purchase.<br />Until you do so, a polite notice will be displayed on your site (this is not shown if your site is running on a local development server).', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/licenses/').'</p>';
		echo '<p>'.__('We are sorry that we have to do this, unfortunately there are some people who want to use the plugin for free when the vast majority are nice, and purchase the plugin.', WPS2_TEXT_DOMAIN).'</p>';
		echo '<p>'.sprintf(__('If you have purchased an <a href="%s" target="_blank">Enterprise licence</a> or <a href="%s" target="_blank">Lifetime licence</a>, we can provide you with a one-off licence code - please contact <a href="mailto:">support@wpsymposiumpro.com</a>.', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/licenses/', 'http://www.wpsymposiumpro.com/licenses/').'</p>';

		if (function_exists('__wps__wpspro_extensions_au')):

			// ... need to save the code?
			if (isset($_POST['wps_manage_licence_input']) && isset($_POST['wps_manage_licence_input'])):
				update_option('wps_licence_code', $_POST['wps_manage_licence_input']);
			endif;
			if (isset($_POST['wps_manage_licence_clear']) && isset($_POST['wps_manage_licence_clear'])):
				delete_option('wps_licence_code');
			endif;
			// ... display licence information (and form to enter/update)
			$licence = wps_licence_code();
			$licenced = $licence[0] ? true : false;
			echo '<form action="'.admin_url( 'admin.php?page=wps_pro_licence' ).'" method="POST">';
				if (!$licenced) echo '<br />';
				echo '<div id="wps_manage_licence_code">';
					if ($licence[2]):
						echo '<strong>'.__('Licence code', WPS2_TEXT_DOMAIN).': '.$licence[2];
						if (!$licenced) echo ' - '.__('Invalid!', WPS2_TEXT_DOMAIN);
						echo '</strong>';
					endif;
					echo $licence[1]; // HTML
					echo __('Licence code', WPS2_TEXT_DOMAIN).': ';
					echo '<input type="text" id="wps_manage_licence_input" name="wps_manage_licence_input" value="'.$licence[2].'" />';
					echo '<input type="submit" class="button-primary" value="'.__('Update', WPS2_TEXT_DOMAIN).'" />';			
					echo '<input type="checkbox" style="margin-left:12px" name="wps_manage_licence_clear" />'.__('Clear', WPS2_TEXT_DOMAIN);
				echo '</div>';
			echo '</form>';
		else:
			echo '<p>'.__('The licence is only required if you have the WP Symposium Extensions plugin installed and activated.', WPS2_TEXT_DOMAIN).'</p>';
			echo '<p>'.sprintf(__('To see all the extra features provided by the Extensions Plugin, visit <a href="%s" target="_blank">www.wpsymposiumpro.com</a>', WPS2_TEXT_DOMAIN), 'http://www.wpsymposiumpro.com/licenses/').'</p>';
		endif;		

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


function __wps_pro_uninstall_delete () {
    global $wpdb;

    // delete shortcode options
    $sql = "DELETE FROM ".$wpdb->prefix."options WHERE option_name like 'wps_shortcode_options%'";
    echo __('Removing shortcode options', WPS2_TEXT_DOMAIN).'... '; 
    $wpdb->query($sql);
	echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
    // delete other options
    $sql = "DELETE FROM ".$wpdb->prefix."options WHERE option_name like 'wps_%'";
    echo __('Removing application options', WPS2_TEXT_DOMAIN).'... '; 
    $wpdb->query($sql);
	echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
    // delete user meta data
    echo __('Removing user meta', WPS2_TEXT_DOMAIN).'... ';    
    $wpdb->query("DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key like 'wps_%'");
	echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
	// removing custom posts (core)
    $sql = "DELETE FROM ".$wpdb->prefix."posts WHERE post_type = 'wps_activity' OR post_type = 'wps_alerts' OR post_type = 'wps_forum_post' OR post_type = 'wps_friendship'";
    echo __('Removing core custom post types', WPS2_TEXT_DOMAIN).'... '; 
    $wpdb->query($sql);
	echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
	// removing custom posts (extensions)
    $sql = "DELETE FROM ".$wpdb->prefix."posts WHERE post_type = 'wps_calendar' OR post_type = 'wps_event' OR post_type = 'wps_crowd' OR post_type = 'wps_extension' OR post_type = 'wps_forum_extension' OR post_type = 'wps_forum_subs' OR post_type = 'wps_subs' OR post_type = 'wps_gallery' OR post_type = 'wps_group_members' OR post_type = 'wps_group' OR post_type = 'wps_lounge' OR post_type = 'wps_mail' OR post_type = 'wps_reward' OR post_type = 'wps_rewards'";
    echo __('Removing additional custom post types', WPS2_TEXT_DOMAIN).'... '; 
    $wpdb->query($sql);
	echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
    // clear schedules
    echo __('Removing WordPress schedule', WPS2_TEXT_DOMAIN).'... ';    
    wp_clear_scheduled_hook( 'wps_symposium_pro_alerts_hook' );
	echo __('ok', WPS2_TEXT_DOMAIN).'<br />';
}

function __wps_pro_uninstall_rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") __wps_pro_uninstall_rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
} 
?>