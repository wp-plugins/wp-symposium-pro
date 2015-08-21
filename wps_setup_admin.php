<?php

// Default settings header
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_options_header', 0.5);
function wps_admin_getting_started_options_header() {
    // Default settings hook
    do_action( 'wps_admin_getting_started_options_hook' );
    
}

// Add Default settings information
add_action('wps_admin_getting_started_shortcodes_hook', 'wps_admin_getting_started_options', 1);
function wps_admin_getting_started_options() {
    
    echo '<div class="wrap">';
            
        echo '<style>';
            echo '.wrap { margin-top: 30px !important; margin-right: 10px !important; margin-left: 5px !important; }';
        echo '</style>';
        echo '<div id="wps_release_notes">';
            echo '<div id="wps_welcome_bar" style="margin-top: 20px;">';
                echo '<img id="wps_welcome_logo" style="width:56px; height:56px; float:left;" src="'.plugins_url('../wp-symposium-pro/css/images/wps_logo.png', __FILE__).'" title="'.__('help', WPS2_TEXT_DOMAIN).'" />';
                echo '<div style="font-size:2em; line-height:1em; font-weight:100; color:#fff;">'.__('Welcome to WP Symposium Pro', WPS2_TEXT_DOMAIN).'</div>';
                echo '<p style="color:#fff;"><em>'.__('The ultimate social network plugin for WordPress', WPS2_TEXT_DOMAIN).'</em></p>';
            echo '</div>';

            $css = 'wps_admin_getting_started_menu_item_remove_icon ';    
          	echo '<div style="margin-top:25px" class="'.$css.'wps_admin_getting_started_menu_item_no_click" >'.__('Default Shortcode Settings', WPS2_TEXT_DOMAIN).'</div>';    
        	$display = 'block';
          	echo '<div class="wps_admin_getting_started_content" id="wps_admin_getting_started_options" style="display:'.$display.'">';
            
                echo '<div id="wps_admin_getting_started_options_outline">';
            
                    // reset options?
                    if (isset($_GET['wps_reset_options'])) {

                        global $wpdb;
                        $sql = "DELETE FROM ".$wpdb->prefix."options WHERE option_name like 'wps_shortcode_options%'";
                        $wpdb->query($sql);
                        echo '<div class="wps_success" style="margin-top:20px">';
                            echo sprintf(__('WP Symposium Pro shortcode options all reset! <a href="%s">Continue...</a>', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wps_pro_shortcodes' ));
                        echo '</div>';

                    } else {
            
                        echo '<div id="wps_admin_getting_started_options_help" style="margin-bottom:20px;'.(true || !isset($_POST['wps_expand_shortcode']) ? '' : 'display:none;').'">';
                        echo __('This section provides a quick and easy way to view and customise all WP Symposium Pro shortcodes, each of which can be added to any WordPress Page, Post or Text Widget.', WPS2_TEXT_DOMAIN).'<br />';
                        echo sprintf(__('If you are not sure which shortcode is being used on a WordPress page, <a href="%s">edit the page</a> and look in the page content editor.', WPS2_TEXT_DOMAIN), admin_url( 'edit.php?post_type=page' )).'</p>';
                        echo '<p style="margin-top:-8px">'.sprintf(__('In the left column, select a general area, then a shortcode that is shown. You can then see and set the default values and get further help for that shortcode. To reset a value, remove the value and Save, or <a href="%s">reset all shortcode options</a>.', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wps_pro_shortcodes&wps_reset_options=1' )).' ';
                        echo sprintf(__('You can also add options to each shortcode when editing a Page/Post/Widget by using shortcode options, as shown in brackets for each shortcode (%s).', WPS2_TEXT_DOMAIN), '<a href="javascript:void(0);" id="wps_show_shortcodes_show">show</a><a href="javascript:void(0);" id="wps_show_shortcodes_hide" style="display:none">hide</a>').'</p>';
                        echo '</div>';

                        echo '<div id="wps_admin_getting_started_options_please_wait">';
                            echo __('Please wait, loading values....', WPS2_TEXT_DOMAIN);
                        echo '</div>';

                        echo '<div id="wps_admin_getting_started_options_left_and_middle" style="display: none;">';
                            echo '<div id="wps_admin_getting_started_options_left">';
                                /* TABS (1st column) */
                                $wps_expand_tab = isset($_POST['wps_expand_tab']) ? $_POST['wps_expand_tab'] : 'activity';
                                $tabs = array();
                                array_push($tabs, array('tab' => 'wps_option_activity',     'option' => 'activity',     'title' => __('Activity', WPS2_TEXT_DOMAIN)));
                                array_push($tabs, array('tab' => 'wps_option_alerts',       'option' => 'alerts',       'title' => __('Alerts', WPS2_TEXT_DOMAIN)));
                                array_push($tabs, array('tab' => 'wps_option_avatar',       'option' => 'avatar',       'title' => __('Avatar', WPS2_TEXT_DOMAIN)));
                                array_push($tabs, array('tab' => 'wps_option_forums',       'option' => 'forums',       'title' => __('Forums', WPS2_TEXT_DOMAIN)));
                                array_push($tabs, array('tab' => 'wps_option_friends',      'option' => 'friends',      'title' => __('Friends', WPS2_TEXT_DOMAIN)));
                                array_push($tabs, array('tab' => 'wps_option_profile',      'option' => 'profile',      'title' => __('Profile', WPS2_TEXT_DOMAIN)));

                                // any more tabs?
                                $tabs = apply_filters( 'wps_options_show_tab_filter', $tabs );

                                $sort = array();
                                foreach($tabs as $k=>$v) {
                                    $sort['title'][$k] = $v['title'];
                                }
                                array_multisort($sort['title'], SORT_ASC, $tabs);    

                                foreach ($tabs as $tab):
                                    echo wps_show_tab($wps_expand_tab, $tab['tab'], $tab['option'], $tab['title']);
                                endforeach;

                                echo '<div id="wps_options_save_button" style="text-align:left"><input type="submit" id="wps_shortcode_options_save_submit" name="Submit" class="button-primary" value="'.__('Save Changes', WPS2_TEXT_DOMAIN).'" /><span class="spinner"></span></div>';

                            echo '</div>';

                            echo '<div id="wps_admin_getting_started_options_middle">';
                                /* SHORTCODES (2nd column) */
                                $wps_expand_shortcode = isset($_POST['wps_expand_shortcode']) ? $_POST['wps_expand_shortcode'] : 'wps_activity_page_tab';
                                // Activity Tab
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'activity', 'wps_activity_tab', WPS_PREFIX.'-activity');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'activity', 'wps_activity_page_tab', WPS_PREFIX.'-activity-page');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'activity', 'wps_activity_post_tab', WPS_PREFIX.'-activity-post');
                                // Alerts Tab
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'alerts', 'wps_alerts_activity_tab', WPS_PREFIX.'-alerts-activity');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'alerts', 'wps_alerts_friends_tab', WPS_PREFIX.'-alerts-friends');
                                // Avatar Tab
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'avatar', 'wps_avatar_tab', WPS_PREFIX.'-avatar');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'avatar', 'wps_avatar_change_tab', WPS_PREFIX.'-avatar-change');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'avatar', 'wps_avatar_change_link_tab', WPS_PREFIX.'-avatar-change-link');
                                // Forums
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forum_tab', WPS_PREFIX.'-forum');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forum_backto_tab', WPS_PREFIX.'-forum-backto');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forum_comment_tab', WPS_PREFIX.'-forum-comment');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forum_page_tab', WPS_PREFIX.'-forum-page');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forum_post_tab', WPS_PREFIX.'-forum-post');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forums_tab', WPS_PREFIX.'-forums');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forum_sharethis_insert_tab', WPS_PREFIX.'-forum-sharethis');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'forums', 'wps_forum_show_posts_tab', WPS_PREFIX.'-forum-show-posts');
                                // Friendships
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'friends', 'wps_friends_tab', WPS_PREFIX.'-friends');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'friends', 'wps_friends_add_button_tab', WPS_PREFIX.'-friends-add-button');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'friends', 'wps_friends_status_tab', WPS_PREFIX.'-friends-status');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'friends', 'wps_friends_pending_tab', WPS_PREFIX.'-friends-pending');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'friends', 'wps_alerts_friends_tab', WPS_PREFIX.'-alerts-friends');
                                // Profile Tab
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'profile', 'wps_activity_page_tab', WPS_PREFIX.'-activity-page');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'profile', 'wps_usermeta_button_tab', WPS_PREFIX.'-usermeta-button');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'profile', 'wps_usermeta_change_tab', WPS_PREFIX.'-usermeta-change');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'profile', 'wps_usermeta_change_link_tab', WPS_PREFIX.'-usermeta-change-link');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'profile', 'wps_usermeta_tab', WPS_PREFIX.'-usermeta');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'profile', 'wps_close_account_tab', WPS_PREFIX.'-close-account');
                                echo wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, 'profile', 'wps_join_site_tab', WPS_PREFIX.'-join-site');

                                // any more shortcodes?
                                do_action('wps_options_shortcode_hook', $wps_expand_tab, $wps_expand_shortcode);    

                            echo '</div>';
                        echo '</div>';    

                        echo '<div id="wps_admin_getting_started_options_right" style="display: none;">';

                            /* ----------------------- ACTIVITY TAB ----------------------- */    

                            // [wps-activity]
                            $values = get_option('wps_shortcode_options_'.'wps_activity') ? get_option('wps_shortcode_options_'.'wps_activity') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_activity_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays activity feed of the user.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-activity] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Include user's own activity", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $include_self = wps_get_shortcode_default($values, 'wps_activity-include_self', true);
                                        echo '<input type="checkbox" name="wps_activity-include_self"'.($include_self ? ' CHECKED' : '').'></td><td>(include_self="'.($include_self ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Include user's friends activity", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $include_friends = wps_get_shortcode_default($values, 'wps_activity-include_friends', true);
                                        echo '<input type="checkbox" name="wps_activity-include_friends"'.($include_friends ? ' CHECKED' : '').'></td><td>(include_friends="'.($include_friends ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Maximum activity posts shown', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $count = wps_get_shortcode_default($values, 'wps_activity-count', 100);
                                        echo '<input type="text" name="wps_activity-count" value="'.$count.'" /></td><td>(count="'.$count.'")</td></tr>';
                                    echo '<tr><td>'.__('Maximum activity posts retrieved', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $get_max = wps_get_shortcode_default($values, 'wps_activity-get_max', 100);
                                        echo '<input type="text" name="wps_activity-get_max" value="'.$get_max.'" /></td><td>(get_max="'.$get_max.'")</td></tr>';
                                    echo '<tr><td>'.__('Avatar size', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $avatar_size = wps_get_shortcode_default($values, 'wps_activity-avatar_size', 64);
                                        echo '<input type="text" name="wps_activity-avatar_size" value="'.$avatar_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(avatar_size="'.$avatar_size.'")</td></tr>';
                                    echo '<tr><td>'.__('Word limit for posts', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $more = wps_get_shortcode_default($values, 'wps_activity-more', 50);
                                        echo '<input type="text" name="wps_activity-more" value="'.$more.'" /> '.__('words', WPS2_TEXT_DOMAIN).'</td><td>(more="'.$more.'")</td></tr>';
                                    echo '<tr><td>'.__('Text to show reset of truncated posts', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $more_label = wps_get_shortcode_default($values, 'wps_activity-more_label', __('more', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-more_label" value="'.$more_label.'" /></td><td>(more_label="'.$more_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Hide activity until fully loaded", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $hide_until_loaded = wps_get_shortcode_default($values, 'wps_activity-hide_until_loaded', false);
                                        echo '<input type="checkbox" name="wps_activity-hide_until_loaded"'.($hide_until_loaded ? ' CHECKED' : '').'></td><td>(hide_until_loaded="'.($hide_until_loaded ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Comment avatar size', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_avatar_size = wps_get_shortcode_default($values, 'wps_activity-comment_avatar_size', 40);
                                        echo '<input type="text" name="wps_activity-comment_avatar_size" value="'.$comment_avatar_size.'" /></td><td>(comment_avatar_size="'.$comment_avatar_size.'")</td></tr>';
                                    echo '<tr><td>'.__('Number of comments shown', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_size = wps_get_shortcode_default($values, 'wps_activity-comment_size', 5);
                                        echo '<input type="text" name="wps_activity-comment_size" value="'.$comment_size.'" /></td><td>(comment_size="'.$comment_size.'")</td></tr>';
                                    echo '<tr><td>'.__('Text for multiple previous comments', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_size_text_plural = wps_get_shortcode_default($values, 'wps_activity-comment_size_text_plural', __('Show previous %d comments...', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-comment_size_text_plural" value="'.$comment_size_text_plural.'" /></td><td>(comment_size_text_plural="'.$comment_size_text_plural.'")</td></tr>';
                                    echo '<tr><td>'.__('Text for one previous comment', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_size_text_singular = wps_get_shortcode_default($values, 'wps_activity-comment_size_text_singular', __('Show previous comment...', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-comment_size_text_singular" value="'.$comment_size_text_singular.'" /></td><td>(comment_size_text_singular="'.$comment_size_text_singular.'")</td></tr>';
                                    echo '<tr><td>'.__('Label for Comment button', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_activity-label', __('Comment', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__('Optional CSS class for button', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_activity-class', '');
                                        echo '<input type="text" name="wps_activity-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';
                                    echo '<tr><td>'.__("User names link to profile page", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $link = wps_get_shortcode_default($values, 'wps_activity-link', true);
                                        echo '<input type="checkbox" name="wps_activity-link"'.($link ? ' CHECKED' : '').'></td><td>(link="'.$link.'")</td></tr>';
                                    echo '<tr><td>'.__('Activity is private message', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private_msg = wps_get_shortcode_default($values, 'wps_activity-private_msg', __('Activity is private', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-private_msg" value="'.$private_msg.'" /></td><td>(private_msg="'.$private_msg.'")</td></tr>';
                                    echo '<tr><td>'.__('Post no longer exists message', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $not_found = wps_get_shortcode_default($values, 'wps_activity-not_found', __('Sorry, this activity post is not longer available.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-not_found" value="'.$not_found.'" /></td><td>(not_found="'.$not_found.'")</td></tr>';
                                    echo '<tr><td>'.__('Delete option label', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $delete_label = wps_get_shortcode_default($values, 'wps_activity-delete_label', __('Delete', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-delete_label" value="'.$delete_label.'" /></td><td>(delete_label="'.$delete_label.'")</td></tr>';
                                    echo '<tr><td>'.__('Stick option label', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $sticky_label = wps_get_shortcode_default($values, 'wps_activity-sticky_label', __('Stick', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-sticky_label" value="'.$sticky_label.'" /></td><td>(sticky_label="'.$sticky_label.'")</td></tr>';
                                    echo '<tr><td>'.__('Unstick option label', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $unsticky_label = wps_get_shortcode_default($values, 'wps_activity-unsticky_label', __('Unstick', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-unsticky_label" value="'.$unsticky_label.'" /></td><td>(unsticky_label="'.$unsticky_label.'")</td></tr>';
                                    echo '<tr><td>'.__('Hide option label', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $hide_label = wps_get_shortcode_default($values, 'wps_activity-hide_label', __('Hide', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-hide_label" value="'.$hide_label.'" /></td><td>(hide_label="'.$hide_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Honour friends sticky posts", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $stick_others = wps_get_shortcode_default($values, 'wps_activity-stick_others', false);
                                        echo '<input type="checkbox" name="wps_activity-stick_others"'.($stick_others ? ' CHECKED' : '').'></td><td>(stick_others="'.($stick_others ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Allow comments", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $allow_replies = wps_get_shortcode_default($values, 'wps_activity-allow_replies', true);
                                        echo '<input type="checkbox" name="wps_activity-allow_replies"'.($allow_replies ? ' CHECKED' : '').'></td><td>(allow_replies="'.($allow_replies ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Date format', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $date_format = wps_get_shortcode_default($values, 'wps_activity-date_format', __('%s ago', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-date_format" value="'.$date_format.'" /></td><td>(date_format="'.$date_format.'")</td></tr>';
                                    echo '<tr><td>'.__('Logged out message', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $logged_out_msg = wps_get_shortcode_default($values, 'wps_activity-logged_out_msg', __('You must be logged in to view the profile page.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity-logged_out_msg" value="'.$logged_out_msg.'" /></td><td>(logged_out_msg="'.$logged_out_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional URL to login", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $login_url = wps_get_shortcode_default($values, 'wps_activity-login_url', '');
                                        echo '<input type="text" name="wps_activity-login_url" value="'.$login_url.'" /></td><td>(login_url="'.$login_url.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_activity', $values);        

                                echo '</table>';
                            echo '</div>';    

                            // [wps-activity-page]
                            $values = get_option('wps_shortcode_options_'.'wps_activity_page') ? get_option('wps_shortcode_options_'.'wps_activity_page') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_activity_page_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__('Displays a default profile page with common elements all set up.', WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-activity-page] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong></p>';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Size of the user's avatar", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $user_avatar_size = wps_get_shortcode_default($values, 'wps_activity_page-user_avatar_size', 150);
                                        echo '<input type="text" name="wps_activity_page-user_avatar_size" value="'.$user_avatar_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(user_avatar_size="'.$user_avatar_size.'")</td></tr>';
                                    echo '<tr><td>'.__('Style of Google Map', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $map_style = wps_get_shortcode_default($values, 'wps_activity_page-map_style', 'static');
                                        echo '<select name="wps_activity_page-map_style">';
                                            echo '<option value="static"'.($map_style == 'static' ? ' SELECTED' : '').'>'.__('Static', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="dynamic"'.($map_style == 'dynamic' ? ' SELECTED' : '').'>'.__('Dynamic', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(map_style="'.$map_style.'")</td></tr>';
                                    echo '<tr><td>'.__('Size of Google Map', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $map_size = wps_get_shortcode_default($values, 'wps_activity_page-map_size', '150,150');
                                        echo '<input type="text" name="wps_activity_page-map_size" value="'.$map_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(map_size="'.$map_size.'")</td></tr>';
                                    echo '<tr><td>'.__('Zoom level of Google Map', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $map_zoom = wps_get_shortcode_default($values, 'wps_activity_page-map_zoom', 4);
                                        echo '<input type="text" name="wps_activity_page-map_zoom" value="'.$map_zoom.'" /></td><td>(map_zoom="'.$map_zoom.'")</td></tr>';
                                    echo '<tr><td>'.__('Label for Town/City', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $town_label = wps_get_shortcode_default($values, 'wps_activity_page-town_label', __('Town/City', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity_page-town_label" value="'.$town_label.'" /></td><td>(town_label="'.$town_label.'")</td></tr>';
                                    echo '<tr><td>'.__('Label for Country', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $country_label = wps_get_shortcode_default($values, 'wps_activity_page-country_label', __('Country', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity_page-country_label" value="'.$country_label.'" /></td><td>(country_label="'.$country_label.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_activity_page', $values);        

                                echo '</table>';
                            echo '</div>';

                            // [wps-activity-post]
                            $values = get_option('wps_shortcode_options_'.'wps_activity_post') ? get_option('wps_shortcode_options_'.'wps_activity_post') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_activity_post_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a text area for adding an activity post.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-activity-post] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Optional CSS class for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_activity_post-class', '');
                                        echo '<input type="text" name="wps_activity_post-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_activity_post-label', __('Add Post', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity_post-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__("Message that only friends can post", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private_msg = wps_get_shortcode_default($values, 'wps_activity_post-private_msg', __('Only friends can post here', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity_post-private_msg" value="'.$private_msg.'" /></td><td>(private_msg="'.$private_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Message that account is closed", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $account_closed_msg = wps_get_shortcode_default($values, 'wps_activity_post-account_closed_msg', __('Account closed.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_activity_post-account_closed_msg" value="'.$account_closed_msg.'" /></td><td>(account_closed_msg="'.$account_closed_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon in new activity post textarea", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $background_icon = wps_get_shortcode_default($values, 'wps_activity_post-background_icon', false);
                                        echo '<input type="checkbox" name="wps_activity_post-background_icon"'.($background_icon ? ' CHECKED' : '').'></td><td>(background_icon="'.($background_icon ? '1' : '0').'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_activity_post', $values);

                                echo '</table>';
                            echo '</div>';

                            /* ----------------------- ALERTS TAB ----------------------- */

                            // [wps-alerts-activity]
                            $values = get_option('wps_shortcode_options_'.'wps_alerts_activity') ? get_option('wps_shortcode_options_'.'wps_alerts_activity') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_alerts_activity_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a drop-down list of alerts for the user.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-alerts-activity] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Style of List', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $style = wps_get_shortcode_default($values, 'wps_alerts_activity-style', 'dropdown');
                                        echo '<select name="wps_alerts_activity-style">';
                                            echo '<option value="dropdown"'.($style == 'dropdown' ? ' SELECTED' : '').'>'.__('Dropdown list', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="list"'.($style == 'list' ? ' SELECTED' : '').'>'.__('List', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="flag"'.($style == 'flag' ? ' SELECTED' : '').'>'.__('Icon', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(style="'.$style.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon size", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_size = wps_get_shortcode_default($values, 'wps_alerts_activity-flag_size', 24);
                                        echo '<input type="text" name="wps_alerts_activity-flag_size" value="'.$flag_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_size="'.$flag_size.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon unread number size", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_unread_size = wps_get_shortcode_default($values, 'wps_alerts_activity-flag_unread_size', 10);
                                        echo '<input type="text" name="wps_alerts_activity-flag_unread_size" value="'.$flag_unread_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_unread_size="'.$flag_unread_size.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon unread number top margin", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_unread_top = wps_get_shortcode_default($values, 'wps_alerts_activity-flag_unread_top', 6);
                                        echo '<input type="text" name="wps_alerts_activity-flag_unread_top" value="'.$flag_unread_top.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_unread_top="'.$flag_unread_top.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon unread number left margin", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_unread_left = wps_get_shortcode_default($values, 'wps_alerts_activity-flag_unread_left', 8);
                                        echo '<input type="text" name="wps_alerts_activity-flag_unread_left" value="'.$flag_unread_left.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_unread_left="'.$flag_unread_left.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon unread number radius", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_unread_radius = wps_get_shortcode_default($values, 'wps_alerts_activity-flag_unread_radius', 8);
                                        echo '<input type="text" name="wps_alerts_activity-flag_unread_radius" value="'.$flag_unread_radius.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_unread_radius="'.$flag_unread_radius.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon URL", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_url = wps_get_shortcode_default($values, 'wps_alerts_activity-flag_url', '');
                                        echo '<input type="text" name="wps_alerts_activity-flag_url" value="'.$flag_url.'" /></td><td>(flag_url="'.$flag_url.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon image alernative URL", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_src = wps_get_shortcode_default($values, 'wps_alerts_activity-flag_src', '');
                                        echo '<input type="text" name="wps_alerts_activity-flag_src" value="'.$flag_src.'" /></td><td>(flag_src="'.$flag_src.'")</td></tr>';
                                    echo '<tr><td>'.__("Recent alerts text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $recent_alerts_text = wps_get_shortcode_default($values, 'wps_alerts_activity-recent_alerts_text', __('Recent alerts...', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_alerts_activity-recent_alerts_text" value="'.$recent_alerts_text.'" /></td><td>(recent_alerts_text="'.$recent_alerts_text.'")</td></tr>';
                                    echo '<tr><td>'.__("No alerts text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $no_activity_text = wps_get_shortcode_default($values, 'wps_alerts_activity-no_activity_text', __('No activity alerts', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_alerts_activity-no_activity_text" value="'.$no_activity_text.'" /></td><td>(no_activity_text="'.$no_activity_text.'")</td></tr>';
                                    echo '<tr><td>'.__("Text for new alerts, seperated by commas", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $select_activity_text = wps_get_shortcode_default($values, 'wps_alerts_activity-select_activity_text', __('You have 1 new alert,You have %d new alerts,You have no new alerts', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_alerts_activity-select_activity_text" value="'.$select_activity_text.'" /></td><td>(select_activity_text="'.$select_activity_text.'")</td></tr>';
                                    echo '<tr><td>'.__("Mark all as read text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $make_all_read_text = wps_get_shortcode_default($values, 'wps_alerts_activity-make_all_read_text', __('Mark all as read', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_alerts_activity-make_all_read_text" value="'.$make_all_read_text.'" /></td><td>(make_all_read_text="'.$make_all_read_text.'")</td></tr>';
                                    echo '<tr><td>'.__("Delete all text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $delete_all_text = wps_get_shortcode_default($values, 'wps_alerts_activity-delete_all_text', __('Delete all', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_alerts_activity-delete_all_text" value="'.$delete_all_text.'" /></td><td>(delete_all_text="'.$delete_all_text.'")</td></tr>';
                                    echo '<tr><td>'.__("Date format", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $date_format = wps_get_shortcode_default($values, 'wps_alerts_activity-date_format', __('%s ago', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_alerts_activity-date_format" value="'.$date_format.'" /></td><td>(date_format)</td></tr>';
                                    echo '<tr><td>'.__("Delete on click", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $delete_on_click = wps_get_shortcode_default($values, 'wps_alerts_activity-delete_on_click', false);
                                        echo '<input type="checkbox" name="wps_alerts_activity-delete_on_click"'.($delete_on_click ? ' CHECKED' : '').'></td><td>(delete_on_click="'.($delete_on_click ? '1' : '0').'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_alerts_activity', $values);        

                                echo '</table>';
                            echo '</div>';   

                            /* ----------------------- AVATAR TAB ----------------------- */

                            // [wps-avatar]
                            $values = get_option('wps_shortcode_options_'.'wps_avatar') ? get_option('wps_shortcode_options_'.'wps_avatar') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_avatar_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a user's avatar.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-avatar] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Size of the avatar", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $size = wps_get_shortcode_default($values, 'wps_avatar-size', 256);
                                        echo '<input type="text" name="wps_avatar-size" value="'.$size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(size="'.$size.'")</td></tr>';
                                    echo '<tr><td>'.__('Show link to change avatar', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $change_link = wps_get_shortcode_default($values, 'wps_avatar-change_link', false);
                                        echo '<input type="checkbox" name="wps_avatar-change_link"'.($change_link ? ' CHECKED' : '').'></td><td>(change_link="'.($change_link ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Avatar links to profile page (if not current user's avatar)", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $profile_link = wps_get_shortcode_default($values, 'wps_avatar-profile_link', false);
                                        echo '<input type="checkbox" name="wps_avatar-profile_link"'.($profile_link ? ' CHECKED' : '').'></td><td>(profile_link="'.($profile_link ? '1' : '0').'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_avatar', $values);        

                                echo '</table>';    
                            echo '</div>';    

                            // [wps-avatar-change]
                            $values = get_option('wps_shortcode_options_'.'wps_avatar_change') ? get_option('wps_shortcode_options_'.'wps_avatar_change') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_avatar_change_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays the form to let users upload an avatar.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-avatar-change] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Label for Upload button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_avatar_change-label', __('Upload', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_avatar_change-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__("Text prompt to choose an image", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $choose = wps_get_shortcode_default($values, 'wps_avatar_change-choose', __('Click here to choose an image...', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_avatar_change-choose" value="'.$choose.'" /></td><td>(choose="'.$choose.'")</td></tr>';
                                    echo '<tr><td>'.__("Try again message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $try_again_msg = wps_get_shortcode_default($values, 'wps_avatar_change-try_again_msg', __('Try again...', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_avatar_change-try_again_msg" value="'.$try_again_msg.'" /></td><td>(try_again_msg="'.$try_again_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Allowed file types", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $file_types_msg = wps_get_shortcode_default($values, 'wps_avatar_change-file_types_msg', __("Please upload an image file (.jpeg, .gif, .png).", WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_avatar_change-file_types_msg" value="'.$file_types_msg.'" /></td><td>(file_types_msg="'.$file_types_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Not allowed to change avatar message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $not_permitted = wps_get_shortcode_default($values, 'wps_avatar_change-not_permitted', __("You are not allowed to change this avatar.", WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_avatar_change-not_permitted" value="'.$not_permitted.'" /></td><td>(not_permitted="'.$not_permitted.'")</td></tr>';
                                    echo '<tr><td>'.__('Allow users to crop avatars', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $crop = wps_get_shortcode_default($values, 'wps_avatar_change-crop', true);
                                        echo '<input type="checkbox" name="wps_avatar_change-crop"'.($crop ? ' CHECKED' : '').'></td><td>(crop="'.($crop ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Must be logged in message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $logged_out_msg = wps_get_shortcode_default($values, 'wps_avatar_change-logged_out_msg', __("You must be logged in to view this page.", WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_avatar_change-logged_out_msg" value="'.$logged_out_msg.'" /></td><td>(logged_out_msg="'.$logged_out_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional URL to login", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $login_url = wps_get_shortcode_default($values, 'wps_avatar_change-login_url', '');
                                        echo '<input type="text" name="wps_avatar_change-login_url" value="'.$login_url.'" /></td><td>(login_url="'.$login_url.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_avatar_change', $values);        

                                echo '</table>';    
                            echo '</div>';  

                            // [wps-avatar-change-link]
                            $values = get_option('wps_shortcode_options_'.'wps_avatar_change_link') ? get_option('wps_shortcode_options_'.'wps_avatar_change_link') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_avatar_change_link_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a link to let a user change their avatar.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-avatar-change-link] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Text shown for the link", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $link_text = wps_get_shortcode_default($values, 'wps_avatar_change_link-text', __('Change Picture', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_avatar_change_link-text" value="'.$link_text.'" /></td><td>(link-text="'.$link_text.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_avatar_change_link', $values);            

                                echo '</table>';    
                            echo '</div>';   

                            /* ----------------------- FORUMS TAB ----------------------- */

                            // [wps-forum]
                            $values = get_option('wps_shortcode_options_'.'wps_forum') ? get_option('wps_shortcode_options_'.'wps_forum') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_forum_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays topics (and replies if style is set to 'classic') of a forum.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__('Add [wps-forum slug="xxx"] to a WordPress Page where "xxx" is the <a href="%s">slug of your forum</a>.', WPS2_TEXT_DOMAIN), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' ));
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Style', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $style = wps_get_shortcode_default($values, 'wps_forum-style', 'table');
                                        echo '<select name="wps_forum-style">';
                                            echo '<option value="table"'.($style == 'table' ? ' SELECTED' : '').'>'.__('Table', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="classic"'.($style == 'classic' ? ' SELECTED' : '').'>'.__('Classic', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(style="'.$style.'")</td></tr>';    
                                    echo '<tr><td>'.__('Base date', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $base_date = wps_get_shortcode_default($values, 'wps_forum_page-base_date', 'post_date_gmt');
                                        echo '<select name="wps_forum_page-base_date">';
                                            echo '<option value="post_date_gmt"'.($base_date == 'post_date_gmt' ? ' SELECTED' : '').'>'.__('GMT', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="post_date"'.($base_date == 'post_date' ? ' SELECTED' : '').'>'.__('Local', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(base_date="'.$base_date.'")</td></tr>';
                                    echo '<tr><td>'.__('Comment base date', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_base_date = wps_get_shortcode_default($values, 'wps_forum_page-comment_base_date', 'comment_date_gmt');
                                        echo '<select name="wps_forum_page-comment_base_date">';
                                            echo '<option value="comment_date_gmt"'.($base_date == 'comment_date_gmt' ? ' SELECTED' : '').'>'.__('GMT', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="comment_date"'.($base_date == 'comment_date' ? ' SELECTED' : '').'>'.__('Local', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(comment_base_date="'.$base_date.'")</td></tr>';
                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('For table style...', WPS2_TEXT_DOMAIN).'</strong></td></tr>';
                                    echo '<tr><td>'.__('Show table header', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_header = wps_get_shortcode_default($values, 'wps_forum-show_header', true);
                                        echo '<input type="checkbox" name="wps_forum-show_header"'.($show_header ? ' CHECKED' : '').'></td><td>(show_header="'.($show_header ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show closed topics', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_closed = wps_get_shortcode_default($values, 'wps_forum-show_closed', true);
                                        echo '<input type="checkbox" name="wps_forum-show_closed"'.($show_closed ? ' CHECKED' : '').'></td><td>(show_closed="'.($show_closed ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show topic cound', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_count = wps_get_shortcode_default($values, 'wps_forum-show_count', true);
                                        echo '<input type="checkbox" name="wps_forum-show_count"'.($show_count ? ' CHECKED' : '').'></td><td>(show_count="'.($show_count ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show freshness', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_freshness = wps_get_shortcode_default($values, 'wps_forum-show_freshness', true);
                                        echo '<input type="checkbox" name="wps_forum-show_freshness"'.($show_freshness ? ' CHECKED' : '').'></td><td>(show_freshness="'.($show_freshness ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show last activity', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_last_activity = wps_get_shortcode_default($values, 'wps_forum-show_last_activity', true);
                                        echo '<input type="checkbox" name="wps_forum-show_last_activity"'.($show_last_activity ? ' CHECKED' : '').'></td><td>(show_last_activity="'.($show_last_activity ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show comment count', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_comments_count = wps_get_shortcode_default($values, 'wps_forum-show_comments_count', true);
                                        echo '<input type="checkbox" name="wps_forum-show_comments_count"'.($show_comments_count ? ' CHECKED' : '').'></td><td>(show_comments_count="'.($show_comments_count ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('For classic style...', WPS2_TEXT_DOMAIN).'</strong></td></tr>';
                                    echo '<tr><td>'.__("Text for topic started", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $started = wps_get_shortcode_default($values, 'wps_forum-started', __('Started by %s %s', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-started" value="'.$started.'" /></td><td>(started="'.$started.'")</td></tr>';
                                    echo '<tr><td>'.__("Text for last reply", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $replied = wps_get_shortcode_default($values, 'wps_forum-replied', __('Last replied to by %s %s', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-replied" value="'.$replied.'" /></td><td>(replied="'.$replied.'")</td></tr>';
                                    echo '<tr><td>'.__("Text for last comment", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $commented = wps_get_shortcode_default($values, 'wps_forum-commented', __('Last commented on by %s %s', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-commented" value="'.$commented.'" /></td><td>(commented="'.$commented.'")</td></tr>';
                                    echo '<tr><td>'.__("Avatar size for topics", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $size_posts = wps_get_shortcode_default($values, 'wps_forum-size_posts', 96);
                                        echo '<input type="text" name="wps_forum-size_posts" value="'.$size_posts.'" /></td><td>(size_posts="'.$size_posts.'")</td></tr>';
                                    echo '<tr><td>'.__("Avatar size for replies", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $size_replies = wps_get_shortcode_default($values, 'wps_forum-size_replies', 48);
                                        echo '<input type="text" name="wps_forum-size_replies" value="'.$size_replies.'" /></td><td>(size_replies="'.$size_replies.'")</td></tr>';
                                    echo '<tr><td>'.__("Maximum size of topic preview", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $post_preview = wps_get_shortcode_default($values, 'wps_forum-post_preview', 250);
                                        echo '<input type="text" name="wps_forum-post_preview" value="'.$post_preview.'" /> '.__('characters', WPS2_TEXT_DOMAIN).'</td><td>(post_preview="'.$post_preview.'")</td></tr>';
                                    echo '<tr><td>'.__("Maximum size of reply preview", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $reply_preview = wps_get_shortcode_default($values, 'wps_forum-reply_preview', 120);
                                        echo '<input type="text" name="wps_forum-reply_preview" value="'.$reply_preview.'" /> '.__('characters', WPS2_TEXT_DOMAIN).'</td><td>(reply_preview="'.$reply_preview.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for view count", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $view_count_label = wps_get_shortcode_default($values, 'wps_forum-view_count_label', __('VIEW', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-view_count_label" value="'.$view_count_label.'" /> '.__('singular', WPS2_TEXT_DOMAIN).'</td><td>(view_count_label="'.$view_count_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for view count", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $views_count_label = wps_get_shortcode_default($values, 'wps_forum-views_count_label', __('VIEWS', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-views_count_label" value="'.$views_count_label.'" /> '.__('plural', WPS2_TEXT_DOMAIN).'</td><td>(views_count_label="'.$views_count_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for reply count", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $reply_count_label = wps_get_shortcode_default($values, 'wps_forum-reply_count_label', __('REPLY', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-reply_count_label" value="'.$reply_count_label.'" /> '.__('singular', WPS2_TEXT_DOMAIN).'</td><td>(reply_count_label="'.$reply_count_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for view count", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $replies_count_label = wps_get_shortcode_default($values, 'wps_forum-replies_count_label', __('REPLIES', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-replies_count_label" value="'.$replies_count_label.'" /> '.__('plural', WPS2_TEXT_DOMAIN).'</td><td>(replies_count_label="'.$replies_count_label.'")</td></tr>';
                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('For both styles...', WPS2_TEXT_DOMAIN).'</strong></td></tr>';
                                    echo '<tr><td>'.__('Show reply icon', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $reply_icon = wps_get_shortcode_default($values, 'wps_forum-reply_icon', true);
                                        echo '<input type="checkbox" name="wps_forum-reply_icon" '.($reply_icon ? ' CHECKED' : '').'></td><td>(reply_icon="'.($reply_icon ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Maximum title length", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $title_length = wps_get_shortcode_default($values, 'wps_forum-title_length', 150);
                                        echo '<input type="text" name="wps_forum-title_length" value="'.$title_length.'" /> '.__('characters', WPS2_TEXT_DOMAIN).'</td><td>(title_length="'.$title_length.'")</td></tr>';
                                    echo '<tr><td>'.__('Reply status', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $status = wps_get_shortcode_default($values, 'wps_forum-status', '');
                                        echo '<select name="wps_forum-status">';
                                            echo '<option value=""'.($status == '' ? ' SELECTED' : '').'>'.__('Open and closed', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="open"'.($status == 'open' ? ' SELECTED' : '').'>'.__('Open', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="closed"'.($status == 'closed' ? ' SELECTED' : '').'>'.__('Closed', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(status="'.$status.'")</td></tr>';    
                                    echo '<tr><td>'.__('Default state of closed switch', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $closed_switch = wps_get_shortcode_default($values, 'wps_forum-closed_switch', '');
                                        echo '<select name="wps_forum-closed_switch">';
                                            echo '<option value=""'.($status == '' ? ' SELECTED' : '').'>'.__('Do not show', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="on"'.($status == 'on' ? ' SELECTED' : '').'>'.__('On', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="off"'.($status == 'off' ? ' SELECTED' : '').'>'.__('Off', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(closed_switch="'.$closed_switch.'")</td></tr>';    
                                    echo '<tr><td>'.__("Closed switch prompt", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $closed_switch_msg = wps_get_shortcode_default($values, 'wps_forum-closed_switch_msg', __('Include closed posts', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-closed_switch_msg" value="'.$closed_switch_msg.'" /></td><td>(closed_switch_msg="'.$closed_switch_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Must be logged in message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private_msg = wps_get_shortcode_default($values, 'wps_forum-private_msg', __('You must be logged in to view this forum.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-private_msg" value="'.$private_msg.'" /></td><td>(private_msg="'.$private_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional URL to login", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $login_url = wps_get_shortcode_default($values, 'wps_forum-login_url', '');
                                        echo '<input type="text" name="wps_forum-login_url" value="'.$login_url.'" /></td><td>(login_url)</td></tr>';
                                    echo '<tr><td>'.__("Don't have permission message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $secure_msg = wps_get_shortcode_default($values, 'wps_forum-secure_msg', __('You do not have permission to view this forum.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-secure_msg" value="'.$secure_msg.'" /> '.__('for forum', WPS2_TEXT_DOMAIN).'</td><td>(secure_msg="'.$secure_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Don't have permission message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $secure_post_msg = wps_get_shortcode_default($values, 'wps_forum-secure_post_msg', __('You do not have permission to view this post.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-secure_post_msg" value="'.$secure_post_msg.'" /> '.__('for topic', WPS2_TEXT_DOMAIN).'</td><td>(secure_post_msg="'.$secure_post_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Empty forum message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $empty_msg = wps_get_shortcode_default($values, 'wps_forum-empty_msg', __('No forum posts.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-empty_msg" value="'.$empty_msg.'" /></td><td>(empty_msg="'.$empty_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Topic deleted message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $post_deleted = wps_get_shortcode_default($values, 'wps_forum-post_deleted', __('Post deleted.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-post_deleted" value="'.$post_deleted.'" /></td><td>(post_deleted="'.$post_deleted.'")</td></tr>';
                                    echo '<tr><td>'.__("Word for pending topic", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $pending = wps_get_shortcode_default($values, 'wps_forum-pending', '('.__('pending', WPS2_TEXT_DOMAIN).')');
                                        echo '<input type="text" name="wps_forum-pending" value="'.$pending.'" /></td><td>(pending="'.$pending.'")</td></tr>';
                                    echo '<tr><td>'.__("Word for pending reply", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_pending = wps_get_shortcode_default($values, 'wps_forum-comment_pending', '('.__('pending', WPS2_TEXT_DOMAIN).')');
                                        echo '<input type="text" name="wps_forum-comment_pending" value="'.$comment_pending.'" /></td><td>(comment_pending="'.$comment_pending.'")</td></tr>';
                                    echo '<tr><td>'.__("Closed prefix", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $closed_prefix = wps_get_shortcode_default($values, 'wps_forum-closed_prefix', __('closed', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-closed_prefix" value="'.$closed_prefix.'" /></td><td>(closed_prefix="'.$closed_prefix.'")</td></tr>';
                                    echo '<tr><td>'.__("Header title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_title = wps_get_shortcode_default($values, 'wps_forum-header_title', __('Topic', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-header_title" value="'.$header_title.'" /></td><td>(header_title="'.$header_title.'")</td></tr>';
                                    echo '<tr><td>'.__("Replies title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_count = wps_get_shortcode_default($values, 'wps_forum-header_count', __('Replies', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-header_count" value="'.$header_count.'" /></td><td>(header_count="'.$header_count.'")</td></tr>';
                                    echo '<tr><td>'.__("Last activity title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_last_activity = wps_get_shortcode_default($values, 'wps_forum-header_last_activity', __('Last activity', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-header_last_activity" value="'.$header_last_activity.'" /></td><td>(header_last_activity="'.$header_last_activity.'")</td></tr>';
                                    echo '<tr><td>'.__("Topic moved message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $moved_to = wps_get_shortcode_default($values, 'wps_forum-moved_to', __('%s successfully moved to %s', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-moved_to" value="'.$moved_to.'" /></td><td>(moved_to)</td></tr>';
                                    echo '<tr><td>'.__("Date format", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $date_format = wps_get_shortcode_default($values, 'wps_forum-date_format', __('%s ago', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-date_format" value="'.$date_format.'" /></td><td>(date_format="'.$date_format.'")</td></tr>';
                                    echo '<tr><td>'.__("Timeout before can't edit", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $timeout = wps_get_shortcode_default($values, 'wps_forum-timeout', 120);
                                        echo '<input type="text" name="wps_forum-timeout" value="'.$timeout.'" /> '.__('seconds', WPS2_TEXT_DOMAIN).'</td><td>(timeout="'.$timeout.'")</td></tr>';
                                    echo '<tr><td>'.__("Number of topics", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $count = wps_get_shortcode_default($values, 'wps_forum-count', 0);
                                        echo '<input type="text" name="wps_forum-count" value="'.$count.'" /> '.__('0 = all', WPS2_TEXT_DOMAIN).'</td><td>(count="'.$count.'")</td></tr>';
                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('For single topic view...', WPS2_TEXT_DOMAIN).'</strong></td></tr>';    
                                    echo '<tr><td>'.__("Topic author avatar", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $size = wps_get_shortcode_default($values, 'wps_forum-size', 96);
                                        echo '<input type="text" name="wps_forum-size" value="'.$size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(size="'.$size.'")</td></tr>';
                                    echo '<tr><td>'.__("Reply author avatar", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comments_avatar_size = wps_get_shortcode_default($values, 'wps_forum-comments_avatar_size', 96);
                                        echo '<input type="text" name="wps_forum-comments_avatar_size" value="'.$comments_avatar_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(comments_avatar_size="'.$comments_avatar_size.'")</td></tr>';
                                    echo '<tr><td>'.__('Enable pagination', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $pagination = wps_get_shortcode_default($values, 'wps_forum-pagination', true);
                                        echo '<input type="checkbox" name="wps_forum-pagination"'.($pagination ? ' CHECKED' : '').'></td><td>(pagination="'.($pagination ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Pagination above replies', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $pagination_top = wps_get_shortcode_default($values, 'wps_forum-pagination_top', true);
                                        echo '<input type="checkbox" name="wps_forum-pagination_top"'.($pagination_top ? ' CHECKED' : '').'></td><td>(pagination_top="'.($pagination_top ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Pagination below replies', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $pagination_bottom = wps_get_shortcode_default($values, 'wps_forum-pagination_bottom', true);
                                        echo '<input type="checkbox" name="wps_forum-pagination_bottom"'.($pagination_bottom ? ' CHECKED' : '').'></td><td>(pagination_bottom="'.($pagination_bottom ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Pagination page size", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $page_size = wps_get_shortcode_default($values, 'wps_forum-page_size', 10);
                                        echo '<input type="text" name="wps_forum-page_size" value="'.$page_size.'" /></td><td>(page_size="'.$page_size.'")</td></tr>';
                                    echo '<tr><td>'.__("Pagination previous label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $pagination_previous = wps_get_shortcode_default($values, 'wps_forum-pagination_previous', __('Previous', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-pagination_previous" value="'.$pagination_previous.'" /></td><td>(pagination_previous="'.$pagination_previous.'")</td></tr>';
                                    echo '<tr><td>'.__("Pagination next label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $pagination_next = wps_get_shortcode_default($values, 'wps_forum-pagination_next', __('Next', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-pagination_next" value="'.$pagination_next.'" /></td><td>(pagination_next="'.$pagination_next.'")</td></tr>';
                                    echo '<tr><td>'.__("Pagination current page text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $page_x_of_y = wps_get_shortcode_default($values, 'wps_forum-page_x_of_y', __('Showing page %d of %d', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-page_x_of_y" value="'.$page_x_of_y.'" /></td><td>(page_x_of_y="'.$page_x_of_y.'")</td></tr>';
                                    echo '<tr><td>'.__('Enable comments', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_comments = wps_get_shortcode_default($values, 'wps_forum-show_comments', true);
                                        echo '<input type="checkbox" name="wps_forum-show_comments"'.($show_comments ? ' CHECKED' : '').'></td><td>(show_comments="'.($show_comments ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show comment as default', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_comment_form = wps_get_shortcode_default($values, 'wps_forum-show_comment_form', true);
                                        echo '<input type="checkbox" name="wps_forum-show_comment_form"'.($show_comment_form ? ' CHECKED' : '').'></td><td>(show_comment_form="'.($show_comment_form ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Allow new comments', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $allow_comments = wps_get_shortcode_default($values, 'wps_forum-allow_comments', true);
                                        echo '<input type="checkbox" name="wps_forum-allow_comments"'.($allow_comments ? ' CHECKED' : '').'></td><td>(allow_comments="'.($allow_comments ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Label for comment button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_add_label = wps_get_shortcode_default($values, 'wps_forum-comment_add_label', __('Add comment', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-comment_add_label" value="'.$comment_add_label.'" /></td><td>(comment_add_label="'.$comment_add_label.'")</td></tr>';
                                    echo '<tr><td>'.__('Optional CSS class for comment button', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comment_class = wps_get_shortcode_default($values, 'wps_forum-comment_class', '');
                                        echo '<input type="text" name="wps_forum-comment_class" value="'.$comment_class.'" /></td><td>(comment_class="'.$comment_class.'")</td></tr>';
                                    echo '<tr><td>'.__('Text shown for private comments', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private_reply_msg = wps_get_shortcode_default($values, 'wps_forum-private_reply_msg', __('PRIVATE REPLY', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum-private_reply_msg" value="'.$private_reply_msg.'" /></td><td>(private_reply_msg="'.$private_reply_msg.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_forum', $values);            

                                    echo '</table>';    
                            echo '</div>';    

                            // [wps-forum-backto]
                            $values = get_option('wps_shortcode_options_'.'wps_forum_backto') ? get_option('wps_shortcode_options_'.'wps_forum_backto') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_forum_backto_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a link back to the forum topics. Only shown when viewing a single topic.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__('Add [wps-forum-backto slug="xxx"] to the WordPress Page of your forum (click on the <strong>Page</strong> link <a href="%s">here</a>) where "xxx" is the <a href="%s">slug of your forum</a>.', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wpspro_forum_setup' ), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' ));
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Text for the link", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_forum_backto-label', __('Back to %s...', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_backto-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_forum_backto', $values);            

                                echo '</table>';    
                            echo '</div>';    

                            // [wps-forum-comment]
                            $values = get_option('wps_shortcode_options_'.'wps_forum_comment') ? get_option('wps_shortcode_options_'.'wps_forum_comment') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_forum_comment_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a text area to add a reply to a forum topic. Only shown when viewing a single topic.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__('Add [wps-forum-comment slug="xxx"] to the WordPress Page of your forum (click on the <strong>Page</strong> link <a href="%s">here</a>) where "xxx" is the <a href="%s">slug of your forum</a>.', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wpspro_forum_setup' ), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' ));
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Label for add reply button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_forum_comment-label', __('Add Reply', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional CSS class for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_forum_comment-class', '');
                                        echo '<input type="text" name="wps_forum_comment-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';
                                    echo '<tr><td>'.__("Text above reply text area", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $content_label = wps_get_shortcode_default($values, 'wps_forum_comment-content_label', '');
                                        echo '<input type="text" name="wps_forum_comment-content_label" value="'.$content_label.'" /></td><td>(content_label="'.$content_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Don't have permission to view topic message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private_msg = wps_get_shortcode_default($values, 'wps_forum_comment-private_msg', '');
                                        echo '<input type="text" name="wps_forum_comment-private_msg" value="'.$private_msg.'" /></td><td>(private_msg="'.$private_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Don't have permission to reply message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $no_permission_msg = wps_get_shortcode_default($values, 'wps_forum_comment-no_permission_msg', __('You do not have permission to reply on this forum.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-no_permission_msg" value="'.$no_permission_msg.'" /></td><td>(no_permission_msg="'.$no_permission_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Forum is locked message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $locked_msg = wps_get_shortcode_default($values, 'wps_forum_comment-locked_msg', __('This forum is locked. New posts and replies are not allowed.', WPS2_TEXT_DOMAIN).' ');
                                        echo '<input type="text" name="wps_forum_comment-locked_msg" value="'.$locked_msg.'" /></td><td>(locked_msg="'.$locked_msg.'")</td></tr>';
                                    echo '<tr><td>'.__('Enable reply moderation', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $moderate = wps_get_shortcode_default($values, 'wps_forum_comment-moderate', false);
                                        echo '<input type="checkbox" name="wps_forum_comment-moderate"'.($moderate ? ' CHECKED' : '').'></td><td>(moderate="'.($moderate ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Moderation message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $moderate_msg = wps_get_shortcode_default($values, 'wps_forum_comment-moderate_msg', __('Your comment will appear once it has been moderated.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-moderate_msg" value="'.$moderate_msg.'" /></td><td>(moderate_msg="'.$moderate_msg.'")</td></tr>';
                                    echo '<tr><td>'.__('Show reply textarea by default', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show = wps_get_shortcode_default($values, 'wps_forum_comment-show', true);
                                        echo '<input type="checkbox" name="wps_forum_comment-show"'.($show ? ' CHECKED' : '').'></td><td>(show="'.($show ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Label to close topic", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $close_msg = wps_get_shortcode_default($values, 'wps_forum_comment-close_msg', __('Tick to close this post', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-close_msg" value="'.$close_msg.'" /></td><td>(close_msg="'.$close_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Message that topic is closed", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $comments_closed_msg = wps_get_shortcode_default($values, 'wps_forum_comment-comments_closed_msg', __('This post is closed.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-comments_closed_msg" value="'.$comments_closed_msg.'" /></td><td>(comments_closed_msg="'.$comments_closed_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Label to re-open topic", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $reopen_label = wps_get_shortcode_default($values, 'wps_forum_comment-reopen_label', __('Re-open this post', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-reopen_label" value="'.$reopen_label.'" /></td><td>(reopen_label="'.$reopen_label.'")</td></tr>';
                                    echo '<tr><td>'.__('Allow private replies', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $allow_private = wps_get_shortcode_default($values, 'wps_forum_comment-allow_private', false);
                                        echo '<input type="checkbox" name="wps_forum_comment-allow_private"'.($allow_private ? ' CHECKED' : '').'></td><td>(allow_private="'.($allow_private ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Private reply label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private_reply_check_msg = wps_get_shortcode_default($values, 'wps_forum_comment-private_reply_check_msg', __('Only share reply with %s', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-private_reply_check_msg" value="'.$private_reply_check_msg.'" /></td><td>(private_reply_check_msg="'.$private_reply_check_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Show in (which forum) label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_in_label = wps_get_shortcode_default($values, 'wps_forum_comment-show_in_label', __('Show in:', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_comment-show_in_label" value="'.$show_in_label.'" /></td><td>(show_in_label="'.$show_in_label.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_forum_comment', $values);            

                                echo '</table>';    
                            echo '</div>';    

                            // [wps-forum-page]
                            $values = get_option('wps_shortcode_options_'.'wps_forum_page') ? get_option('wps_shortcode_options_'.'wps_forum_page') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_forum_page_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a ready made page for a forum.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__('Add [wps-forum-page slug="xxx"] to a WordPress Page where "xxx" is the <a href="%s">slug of your forum</a>.', WPS2_TEXT_DOMAIN), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' ));
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Style', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $style = wps_get_shortcode_default($values, 'wps_forum_page-style', 'table');
                                        echo '<select name="wps_forum_page-style">';
                                            echo '<option value="table"'.($style == 'table' ? ' SELECTED' : '').'>'.__('Table', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="classic"'.($style == 'classic' ? ' SELECTED' : '').'>'.__('Classic', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(style="'.$style.'")</td></tr>';    
                                    echo '<tr><td>'.__('Show new topic form', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show = wps_get_shortcode_default($values, 'wps_forum_page-show', false);
                                        echo '<input type="checkbox" name="wps_forum_page-show"'.($show ? ' CHECKED' : '').'></td><td>(show="'.($show ? '1' : '0').'")</td></tr>';    
                                    echo '<tr><td>'.__("Title header text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_title = wps_get_shortcode_default($values, 'wps_forum_page-header_title', __('Topic', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_page-header_title" value="'.$header_title.'" /></td><td>(header_title="'.$header_title.'")</td></tr>';
                                    echo '<tr><td>'.__("Replies header text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_count = wps_get_shortcode_default($values, 'wps_forum_page-header_count', __('Replies', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_page-header_count" value="'.$header_count.'" /></td><td>(header_count="'.$header_count.'")</td></tr>';
                                    echo '<tr><td>'.__("Last activity header text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_last_activity = wps_get_shortcode_default($values, 'wps_forum_page-header_last_activity', __('Last activity', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_page-header_last_activity" value="'.$header_last_activity.'" /></td><td>(header_last_activity="'.$header_last_activity.'")</td></tr>';
                                    echo '<tr><td>'.__('Base date', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $base_date = wps_get_shortcode_default($values, 'wps_forum_page-base_date', 'post_date_gmt');
                                        echo '<select name="wps_forum_page-base_date">';
                                            echo '<option value="post_date_gmt"'.($base_date == 'post_date_gmt' ? ' SELECTED' : '').'>'.__('GMT', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="post_date"'.($base_date == 'post_date' ? ' SELECTED' : '').'>'.__('Local', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(base_date="'.$base_date.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_forum_page', $values);                

                                echo '</table>';    
                            echo '</div>';    

                            // [wps-forum-post]
                            $values = get_option('wps_shortcode_options_'.'wps_forum_post') ? get_option('wps_shortcode_options_'.'wps_forum_post') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_forum_post_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a textarea for adding a forum topic.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__('Add [wps-forum-post slug="xxx"] to a WordPress Page where "xxx" is the <a href="%s">slug of your forum</a>.', WPS2_TEXT_DOMAIN), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' ));
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Post title text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $title_label = wps_get_shortcode_default($values, 'wps_forum_post-title_label', __('Post title', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_post-title_label" value="'.$title_label.'" /></td><td>(title_label="'.$title_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Topic content text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $content_label = wps_get_shortcode_default($values, 'wps_forum_post-content_label', __('Post', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_post-content_label" value="'.$content_label.'" /></td><td>(content_label="'.$content_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Add topic button label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_forum_post-label', __('Add Topic', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_post-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional CSS class for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_forum_post-class', '');
                                        echo '<input type="text" name="wps_forum_post-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';
                                    echo '<tr><td>'.__('Enable moderation', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $moderate = wps_get_shortcode_default($values, 'wps_forum_post-moderate', false);
                                        echo '<input type="checkbox" name="wps_forum_post-moderate"'.($moderate ? ' CHECKED' : '').'></td><td>(moderate="'.($moderate ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Awaiting moderation message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $moderate_msg = wps_get_shortcode_default($values, 'wps_forum_post-moderate_msg', __('Your post will appear once it has been moderated.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_post-moderate_msg" value="'.$moderate_msg.'" /></td><td>(moderate_msg="'.$moderate_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Permission denied message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private_msg = wps_get_shortcode_default($values, 'wps_forum_post-private_msg', '');
                                        echo '<input type="text" name="wps_forum_post-private_msg" value="'.$private_msg.'" /></td><td>(private_msg="'.$private_msg.'")</td></tr>';
                                    echo '<tr><td>'.__('Set post title as multiline', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $multiline = wps_get_shortcode_default($values, 'wps_forum_post-multiline', 0);
                                        echo '<select name="wps_forum_post-multiline">';
                                            echo '<option value="0"'.($multiline == '0' ? ' SELECTED' : '').'>'.__('Disabled', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="1"'.($multiline == '1' ? ' SELECTED' : '').'>'.__('1 line', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="2"'.($multiline == '2' ? ' SELECTED' : '').'>'.__('2 lines', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="3"'.($multiline == '3' ? ' SELECTED' : '').'>'.__('3 lines', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="4"'.($multiline == '4' ? ' SELECTED' : '').'>'.__('4 lines', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="5"'.($multiline == '5' ? ' SELECTED' : '').'>'.__('5 lines', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(multiline="'.$multiline.'")</td></tr>';
                                    echo '<tr><td>'.__('Show new topic form', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show = wps_get_shortcode_default($values, 'wps_forum_post-show', false);
                                        echo '<input type="checkbox" name="wps_forum_post-show"'.($show ? ' CHECKED' : '').'></td><td>(show="'.($show ? '1' : '0').'")</td></tr>';        

                                    do_action('wps_show_styling_options_hook', 'wps_forum_post', $values);                

                                echo '</table>';  
                            echo '</div>';   

                            // [wps-forums]
                            $values = get_option('wps_shortcode_options_'.'wps_forums') ? get_option('wps_shortcode_options_'.'wps_forums') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_forums_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a top level of all forums.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-forums] to a WordPress Page, Post or Text Widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Forum title header text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $forum_title = wps_get_shortcode_default($values, 'wps_forums-forum_title', __('Forum', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forums-forum_title" value="'.$forum_title.'" /></td><td>(forum_title="'.$forum_title.'")</td></tr>';
                                    echo '<tr><td>'.__("Topic count header text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $forum_count = wps_get_shortcode_default($values, 'wps_forums-forum_count', __('Count', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forums-forum_count" value="'.$forum_count.'" /></td><td>(forum_count="'.$forum_count.'")</td></tr>';
                                    echo '<tr><td>'.__("Last Poster header text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $forum_last_activity = wps_get_shortcode_default($values, 'wps_forums-forum_last_activity', __('Last Poster', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forums-forum_last_activity" value="'.$forum_last_activity.'" /></td><td>(forum_last_activity="'.$forum_last_activity.'")</td></tr>';
                                    echo '<tr><td>'.__("Freshness header text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $forum_freshness = wps_get_shortcode_default($values, 'wps_forums-forum_freshness', __('Freshness', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forums-forum_freshness" value="'.$forum_freshness.'" /></td><td>(forum_freshness="'.$forum_freshness.'")</td></tr>';
                                    echo '<tr><td>'.__('Include closed topics', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_closed = wps_get_shortcode_default($values, 'wps_forums-show_closed', true);
                                        echo '<input type="checkbox" name="wps_forums-show_closed"'.($show_closed ? ' CHECKED' : '').'></td><td>(show_closed="'.($show_closed ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show header', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_header = wps_get_shortcode_default($values, 'wps_forums-show_header', false);
                                        echo '<input type="checkbox" name="wps_forums-show_header"'.($show_header ? ' CHECKED' : '').'></td><td>(show_header="'.($show_header ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show topic count header text', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_count = wps_get_shortcode_default($values, 'wps_forums-show_count', true);
                                        echo '<input type="checkbox" name="wps_forums-show_count"'.($show_count ? ' CHECKED' : '').'></td><td>(show_count="'.($show_count ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show last poster header text', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_last_activity = wps_get_shortcode_default($values, 'wps_forums-show_last_activity', true);
                                        echo '<input type="checkbox" name="wps_forums-show_last_activity"'.($show_last_activity ? ' CHECKED' : '').'></td><td>(show_last_activity="'.($show_last_activity ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show freshness header text', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_freshness = wps_get_shortcode_default($values, 'wps_forums-show_freshness', true);
                                        echo '<input type="checkbox" name="wps_forums-show_freshness"'.($show_freshness ? ' CHECKED' : '').'></td><td>(show_freshness="'.($show_freshness ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Top level as links', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $level_0_links = wps_get_shortcode_default($values, 'wps_forums-level_0_links', true);
                                        echo '<input type="checkbox" name="wps_forums-level_0_links"'.($level_0_links ? ' CHECKED' : '').'></td><td>(level_0_links="'.($level_0_links ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Base date', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $base_date = wps_get_shortcode_default($values, 'wps_forums-base_date', 'post_date_gmt');
                                        echo '<select name="wps_forums-base_date">';
                                            echo '<option value="post_date_gmt"'.($base_date == 'post_date_gmt' ? ' SELECTED' : '').'>'.__('GMT', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="post_date"'.($base_date == 'post_date' ? ' SELECTED' : '').'>'.__('Local', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(base_date="'.$base_date.'")</td></tr>';

                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('Most recent activity shown below each forum', WPS2_TEXT_DOMAIN).'</strong></td></tr>';    
                                    echo '<tr><td>'.__("Number of topics to show", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_posts = wps_get_shortcode_default($values, 'wps_forums-show_posts', 3);
                                        echo '<input type="text" name="wps_forums-show_posts" value="'.$show_posts.'" /></td><td>(show_posts="'.$show_posts.'")</td></tr>';
                                    echo '<tr><td>'.__('Show topics header', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_posts_header = wps_get_shortcode_default($values, 'wps_forums-show_posts_header', true);
                                        echo '<input type="checkbox" name="wps_forums-show_posts_header"'.($show_posts_header ? ' CHECKED' : '').'></td><td>(show_posts_header="'.($show_posts_header ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show count totals above topics', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_summary = wps_get_shortcode_default($values, 'wps_forums-show_summary', false);
                                        echo '<input type="checkbox" name="wps_forums-show_summary"'.($show_summary ? ' CHECKED' : '').'></td><td>(show_summary="'.($show_summary ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Include replies in above', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $count_include_replies = wps_get_shortcode_default($values, 'wps_forums-count_include_replies', true);
                                        echo '<input type="checkbox" name="wps_forums-count_include_replies"'.($count_include_replies ? ' CHECKED' : '').'></td><td>(count_include_replies="'.($count_include_replies ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Header title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_title = wps_get_shortcode_default($values, 'wps_forums-header_title', __('Topic', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forums-header_title" value="'.$header_title.'" /></td><td>(header_title="'.$header_title.'")</td></tr>';
                                    echo '<tr><td>'.__("Replies title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_count = wps_get_shortcode_default($values, 'wps_forums-header_count', __('Replies', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forums-header_count" value="'.$header_count.'" /></td><td>(header_count="'.$header_count.'")</td></tr>';
                                    echo '<tr><td>'.__("Last activity title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $header_last_activity = wps_get_shortcode_default($values, 'wps_forums-header_last_activity', __('Last activity', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forums-header_last_activity" value="'.$header_last_activity.'" /></td><td>(header_last_activity="'.$header_last_activity.'")</td></tr>';
                                    echo '<tr><td>'.__("Limit on title length", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $title_length = wps_get_shortcode_default($values, 'wps_forums-title_length', 50);
                                        echo '<input type="text" name="wps_forums-title_length" value="'.$title_length.'" /> '.__('characters', WPS2_TEXT_DOMAIN).'</td><td>(title_length="'.$title_length.'")</td></tr>';
                                    echo '<tr><td>'.__('Do not indent', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $no_indent = wps_get_shortcode_default($values, 'wps_forums-no_indent', true);
                                        echo '<input type="checkbox" name="wps_forums-no_indent"'.($no_indent ? ' CHECKED' : '').'></td><td>(no_indent="'.($no_indent ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.sprintf(__("Forum image width (add via <a href='%s'>Forum Edit</a>)", WPS2_TEXT_DOMAIN), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' )).'</td><td>';
                                        $featured_image_width = wps_get_shortcode_default($values, 'wps_forums-featured_image_width', 0);
                                        echo '<input type="text" name="wps_forums-featured_image_width" value="'.$featured_image_width.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(featured_image_width="'.$featured_image_width.'")</td></tr>';    

                                    do_action('wps_show_styling_options_hook', 'wps_forums', $values);                

                                echo '</table>';  
                            echo '</div>';   

                            // [wps-forum-show-posts]
                            $values = get_option('wps_shortcode_options_'.'wps_forum_show_posts') ? get_option('wps_shortcode_options_'.'wps_forum_show_posts') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_forum_show_posts_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Flexible way to show forum posts.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__('Add [wps-forum-show-posts slug="xxx"] to the WordPress Page of your forum (click on the <strong>Page</strong> link <a href="%s">here</a>) where "xxx" is the <a href="%s">slug of your forum</a>.', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wpspro_forum_setup' ), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' ));    
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Order value', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $order = wps_get_shortcode_default($values, 'wps_forum_show_posts-order', 'date');
                                        echo '<select name="wps_forum_post-order">';
                                            echo '<option value="author"'.($order == 'author' ? ' SELECTED' : '').'>'.__('Author', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="content"'.($order == 'content' ? ' SELECTED' : '').'>'.__('Content', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="date"'.($order == 'date' ? ' SELECTED' : '').'>'.__('Date', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="title"'.($order == 'title' ? ' SELECTED' : '').'>'.__('Title', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(order="'.$order.'")</td></tr>';
                                    echo '<tr><td>'.__('Order', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $orderby = wps_get_shortcode_default($values, 'wps_forum_show_posts-orderby', 'DESC');
                                        echo '<select name="wps_forum_post-orderby">';
                                            echo '<option value="ASC"'.($order == 'ASC' ? ' SELECTED' : '').'>'.__('Ascending', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="DESC"'.($order == 'DESC' ? ' SELECTED' : '').'>'.__('Descending', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(orderby="'.$orderby.'")</td></tr>';
                                    echo '<tr><td>'.__('Status', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $orderby = wps_get_shortcode_default($values, 'wps_forum_show_posts-status', '');
                                        echo '<select name="wps_forum_post-status">';
                                            echo '<option value=""'.($order == '' ? ' SELECTED' : '').'>'.__('All', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="open"'.($order == 'open' ? ' SELECTED' : '').'>'.__('Open', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="closed"'.($order == 'closed' ? ' SELECTED' : '').'>'.__('Closed', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(status="'.$status.'")</td></tr>';
                                    echo '<tr><td>'.__('Include topics', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $include_posts = wps_get_shortcode_default($values, 'wps_forum_show_posts-include_posts', true);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-include_posts"'.($include_posts ? ' CHECKED' : '').'></td><td>(include_posts="'.($include_posts ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Include replies', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $include_replies = wps_get_shortcode_default($values, 'wps_forum_show_posts-include_replies', true);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-include_replies"'.($include_replies ? ' CHECKED' : '').'></td><td>(include_replies="'.($include_replies ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Include comments', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $include_comments = wps_get_shortcode_default($values, 'wps_forum_show_posts-include_comments', false);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-include_comments"'.($include_comments ? ' CHECKED' : '').'></td><td>(include_comments="'.($include_comments ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Closed prefix", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $closed_prefix = wps_get_shortcode_default($values, 'wps_forum_show_posts-closed_prefix', __('closed', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_show_posts-closed_prefix" value="'.$closed_prefix.'" /></td><td>(closed_prefix="'.$closed_prefix.'")</td></tr>';    
                                    echo '<tr><td>'.__('Show author', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_author = wps_get_shortcode_default($values, 'wps_forum_show_posts-show_author', true);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-show_author"'.($show_author ? ' CHECKED' : '').'></td><td>(show_author="'.($show_author ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Format of author text (for above)", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $author_format = wps_get_shortcode_default($values, 'wps_forum_show_posts-author_format', __('By %s', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_show_posts-author_format" value="'.$author_format.'" /></td><td>(author_format="'.$author_format.'")</td></tr>';    
                                    echo '<tr><td>'.__('Link author to profile page', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $author_link = wps_get_shortcode_default($values, 'wps_forum_show_posts-author_link', true);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-author_link"'.($author_link ? ' CHECKED' : '').'></td><td>(author_link="'.($author_link ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show date', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_date = wps_get_shortcode_default($values, 'wps_forum_show_posts-show_date', true);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-show_date"'.($show_date ? ' CHECKED' : '').'></td><td>(show_date="'.($show_date ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Format of date text (for above)", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $date_format = wps_get_shortcode_default($values, 'wps_forum_show_posts-date_format', __('%s ago', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_show_posts-date_format" value="'.$date_format.'" /></td><td>(date_format="'.$date_format.'")</td></tr>';    
                                    echo '<tr><td>'.__('Show snippet', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_snippet = wps_get_shortcode_default($values, 'wps_forum_show_posts-show_snippet', true);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-show_snippet"'.($show_snippet ? ' CHECKED' : '').'></td><td>(show_snippet="'.($show_snippet ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Maximum length of title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $title_length = wps_get_shortcode_default($values, 'wps_forum_show_posts-title_length', 50);
                                        echo '<input type="text" name="wps_forum_show_posts-title_length" value="'.$title_length.'" /></td><td>(title_length="'.$title_length.'")</td></tr>';    
                                    echo '<tr><td>'.__("Maximum length of snippet", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $snippet_length = wps_get_shortcode_default($values, 'wps_forum_show_posts-snippet_length', 30);
                                        echo '<input type="text" name="wps_forum_show_posts-snippet_length" value="'.$snippet_length.'" /></td><td>(snippet_length="'.$snippet_length.'")</td></tr>';    
                                    echo '<tr><td>'.__("Number of posts displayed", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $max = wps_get_shortcode_default($values, 'wps_forum_show_posts-max', 10);
                                        echo '<input type="text" name="wps_forum_show_posts-max" value="'.$max.'" /></td><td>(max="'.$max.'")</td></tr>';    
                                    echo '<tr><td>'.__('Base date', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $base_date = wps_get_shortcode_default($values, 'wps_forum_show_posts-base_date', 'post_date_gmt');
                                        echo '<select name="wps_forum_show_posts-base_date">';
                                            echo '<option value="post_date_gmt"'.($base_date == 'post_date_gmt' ? ' SELECTED' : '').'>'.__('GMT', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="post_date"'.($base_date == 'post_date' ? ' SELECTED' : '').'>'.__('Local', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(base_date="'.$base_date.'")</td></tr>';

                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('Summary sentence and author avatar', WPS2_TEXT_DOMAIN).'</strong></td></tr>';        
                                    echo '<tr><td>'.__('Show summary sentence and avatar', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary', false);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-summary"'.($summary ? ' CHECKED' : '').'></td><td>(summary="'.($summary ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Format", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_format = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_format', __('%s %s %s %s ago %s', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_show_posts-summary_format" value="'.$summary_format.'" /></td><td>(summary_format="'.$summary_format.'")</td></tr>';    
                                    echo '<tr><td>'.__("Size of avatar", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_avatar_size = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_avatar_size', 32);
                                        echo '<input type="text" name="wps_forum_show_posts-summary_avatar_size" value="'.$summary_avatar_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(summary_avatar_size="'.$summary_avatar_size.'")</td></tr>';    
                                    echo '<tr><td>'.__("Text for started", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_started = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_started', __('started', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_show_posts-summary_started" value="'.$summary_started.'" /></td><td>(summary_started="'.$summary_started.'")</td></tr>';    
                                    echo '<tr><td>'.__("Text for replied to", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_replied = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_replied', __('replied to', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_show_posts-summary_replied" value="'.$summary_replied.'" /></td><td>(summary_replied="'.$summary_replied.'")</td></tr>';    
                                    echo '<tr><td>'.__("Text for commented on", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_commented = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_commented', __('commented on', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_forum_show_posts-summary_commented" value="'.$summary_commented.'" /></td><td>(summary_commented="'.$summary_commented.'")</td></tr>';    
                                    echo '<tr><td>'.__("Maximum length for title", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_title_length = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_title_length', 150);
                                        echo '<input type="text" name="wps_forum_show_posts-summary_title_length" value="'.$summary_title_length.'" /> '.__('characters', WPS2_TEXT_DOMAIN).'</td><td>(summary_title_length="'.$summary_title_length.'")</td></tr>';    
                                    echo '<tr><td>'.__("Maximum length for content snippet", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_snippet_length = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_snippet_length', 50);
                                        echo '<input type="text" name="wps_forum_show_posts-summary_snippet_length" value="'.$summary_snippet_length.'" /> '.__('characters', WPS2_TEXT_DOMAIN).'</td><td>(summary_snippet_length="'.$summary_snippet_length.'")</td></tr>';    
                                    echo '<tr><td>'.__('Show unread if applicable', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $summary_show_unread = wps_get_shortcode_default($values, 'wps_forum_show_posts-summary_show_unread', true);
                                        echo '<input type="checkbox" name="wps_forum_show_posts-summary_show_unread"'.($summary_show_unread ? ' CHECKED' : '').'></td><td>(summary_show_unread="'.($summary_show_unread ? '1' : '0').'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_forum_show_posts', $values);                

                                echo '</table>';  
                            echo '</div>';       

                            // [wps-forum-sharethis]
                            echo wps_show_options($wps_expand_shortcode, 'wps_forum_sharethis_insert_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__("Inserts ShareThis code added to <em>any</em> forum <a href='%s'>here</a>.", WPS2_TEXT_DOMAIN), admin_url( 'edit-tags.php?action=edit&taxonomy=wps_forum&tag_ID=6&post_type=wps_forum_post' )).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.sprintf(__('Add [wps-forum-sharethis slug="xxx"] to a WordPress Page where "xxx" is the <a href="%s">slug of your forum</a>.', WPS2_TEXT_DOMAIN), admin_url( 'edit-tags.php?taxonomy=wps_forum&post_type=wps_forum_post' ));
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('No options', WPS2_TEXT_DOMAIN).'</td></tr>';

                                echo '</table>';  
                            echo '</div>';          

                            /* ----------------------- FRIENDS TAB ----------------------- */    

                            // [wps-friends]
                            $values = get_option('wps_shortcode_options_'.'wps_friends') ? get_option('wps_shortcode_options_'.'wps_friends') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_friends_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a user's friends.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-friends] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Number of friends to show', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $count = wps_get_shortcode_default($values, 'wps_friends-count', 10);
                                        echo '<input type="text" name="wps_friends-count" value="'.$count.'" /></td><td>(count="'.$count.'")</td></tr>';
                                    echo '<tr><td>'.__('Avatar size', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $size = wps_get_shortcode_default($values, 'wps_friends-size', 64);
                                        echo '<input type="text" name="wps_friends-size" value="'.$size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(size="'.$size.'")</td></tr>';
                                    echo '<tr><td>'.__('Link display names to profile page', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $link = wps_get_shortcode_default($values, 'wps_friends-link', true);
                                        echo '<input type="checkbox" name="wps_friends-link"'.($link ? ' CHECKED' : '').'></td><td>(link="'.($link ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Show when last active', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_last_active = wps_get_shortcode_default($values, 'wps_friends-show_last_active', true);
                                        echo '<input type="checkbox" name="wps_friends-show_last_active"'.($show_last_active ? ' CHECKED' : '').'></td><td>(show_last_active="'.($show_last_active ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__('Text for when last active', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $last_active_text = wps_get_shortcode_default($values, 'wps_friends-last_active_text', __('Last seen:', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends-last_active_text" value="'.$last_active_text.'" /></td><td>(last_active_text="'.$last_active_text.'")</td></tr>';
                                    echo '<tr><td>'.__('Format for when last active', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $last_active_format = wps_get_shortcode_default($values, 'wps_friends-last_active_format', __('%s ago', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends-last_active_format" value="'.$last_active_format.'" /></td><td>(last_active_format="'.$last_active_format.'")</td></tr>';
                                    echo '<tr><td>'.__('Text for private', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $private = wps_get_shortcode_default($values, 'wps_friends-private', __('Private information', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends-private" value="'.$private.'" /></td><td>(private="'.$private.'")</td></tr>';
                                    echo '<tr><td>'.__('Text for no friends', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $none = wps_get_shortcode_default($values, 'wps_friends-none', __('No friends', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends-none" value="'.$none.'" /></td><td>(none="'.$none.'")</td></tr>';
                                    echo '<tr><td>'.__('Layout', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $layout = wps_get_shortcode_default($values, 'wps_friends-layout', 'list');
                                        echo '<select name="wps_friends-layout">';
                                            echo '<option value="list"'.($layout == 'list' ? ' SELECTED' : '').'>'.__('List', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="fluid"'.($layout == 'fluid' ? ' SELECTED' : '').'>'.__('Fluid', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(layout="'.$layout.'")</td></tr>';
                                    echo '<tr><td>'.__('Logged out text', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $logged_out_msg = wps_get_shortcode_default($values, 'wps_friends-logged_out_msg', __('You must be logged in to view this page.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends-logged_out_msg" value="'.$logged_out_msg.'" /></td><td>(logged_out_msg="'.$logged_out_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional URL to login", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $login_url = wps_get_shortcode_default($values, 'wps_friends-login_url', '');
                                        echo '<input type="text" name="wps_friends-login_url" value="'.$login_url.'" /></td><td>(login_url="'.$login_url.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_friends', $values);                    

                                echo '</table>';
                            echo '</div>';        

                            // [wps-friends-status]
                            $values = get_option('wps_shortcode_options_'.'wps_friends_status') ? get_option('wps_shortcode_options_'.'wps_friends_status') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_friends_status_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays the friendship status of a user with the current user.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-friends-status] to the WordPress Page being used as the profile page.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("You are friends text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $friends_yes = wps_get_shortcode_default($values, 'wps_friends_status-friends_yes', __('You are friends', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_status-friends_yes" value="'.$friends_yes.'" /></td><td>(friends_yes="'.$friends_yes.'")</td></tr>';
                                    echo '<tr><td>'.__("Friend request pending text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $friends_pending = wps_get_shortcode_default($values, 'wps_friends_status-friends_pending', __('You have requested to be friends', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_status-friends_pending" value="'.$friends_pending.'" /></td><td>(friends_pending="'.$friends_pending.'")</td></tr>';
                                    echo '<tr><td>'.__("You have a friend request text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $friend_request = wps_get_shortcode_default($values, 'wps_friends_status-friend_request', __('You have a friends request', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_status-friend_request" value="'.$friend_request.'" /></td><td>(friend_request="'.$friend_request.'")</td></tr>';
                                    echo '<tr><td>'.__("You are not friends text", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $friends_no = wps_get_shortcode_default($values, 'wps_friends_status-friends_no', __('You are not friends', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_status-friends_no" value="'.$friends_no.'" /></td><td>(friends_no="'.$friends_no.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_friends_status', $values);                    

                                echo '</table>';
                            echo '</div>';        

                            // [wps-friends-pending]
                            $values = get_option('wps_shortcode_options_'.'wps_friends_pending') ? get_option('wps_shortcode_options_'.'wps_friends_pending') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_friends_pending_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays pending friendship requests for the current user.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-friends-pending] to the WordPress Page being used as the profile page.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Maximum number of requests to show", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $count = wps_get_shortcode_default($values, 'wps_friends_pending-count', 10);
                                        echo '<input type="text" name="wps_friends_pending-count" value="'.$count.'" /></td><td>(count="'.$count.'")</td></tr>';
                                    echo '<tr><td>'.__("Avatar size", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $size = wps_get_shortcode_default($values, 'wps_friends_pending-size', 64);
                                        echo '<input type="text" name="wps_friends_pending-size" value="'.$size.'" /></td><td>(size="'.$size.'")</td></tr>';
                                    echo '<tr><td>'.__('Link avatar to profile page', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $link = wps_get_shortcode_default($values, 'wps_friends_pending-link', true);
                                        echo '<input type="checkbox" name="wps_friends_pending-link"'.($link ? ' CHECKED' : '').'></td><td>(link="'.($link ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Optional CSS class for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_friends_pending-class', '');
                                        echo '<input type="text" name="wps_friends_pending-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for accept button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $accept_request_label = wps_get_shortcode_default($values, 'wps_friends_pending-accept_request_label', __('Accept', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_pending-accept_request_label" value="'.$accept_request_label.'" /></td><td>(accept_request_label="'.$accept_request_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for reject button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $reject_request_label = wps_get_shortcode_default($values, 'wps_friends_pending-reject_request_label', __('Reject', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_pending-reject_request_label" value="'.$reject_request_label.'" /></td><td>(reject_request_label="'.$reject_request_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Text for no friendship requests", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $none = wps_get_shortcode_default($values, 'wps_friends_pending-none', '');
                                        echo '<input type="text" name="wps_friends_pending-none" value="'.$none.'" /></td><td>(none="'.$none.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_friends_pending', $values);                    

                                echo '</table>';
                            echo '</div>';       

                            // [wps-friends-add-button]
                            $values = get_option('wps_shortcode_options_'.'wps_friends_add_button') ? get_option('wps_shortcode_options_'.'wps_friends_add_button') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_friends_add_button_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a button to make a request to another user as a friend.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-friends-add-button] to the WordPress Page being used as the profile page.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Label for the button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_friends_add_button-label', __('Make friends', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_add_button-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__("Cancel friendship label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $cancel_label = wps_get_shortcode_default($values, 'wps_friends_add_button-cancel_label', __('Cancel friendship', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_add_button-cancel_label" value="'.$cancel_label.'" /></td><td>(cancel_label="'.$cancel_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Cancel friendship request label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $cancel_request_label = wps_get_shortcode_default($values, 'wps_friends_add_button-cancel_request_label', __('Cancel friendship request', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_friends_add_button-cancel_request_label" value="'.$cancel_request_label.'" /></td><td>(cancel_request_label="'.$cancel_request_label.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional CSS class for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_friends_add_button-class', '');
                                        echo '<input type="text" name="wps_friends_add_button-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_friends_add_button', $values);                    

                                echo '</table>';
                            echo '</div>';         

                            // [wps-alerts-friends]
                            $values = get_option('wps_shortcode_options_'.'wps_alerts_friends') ? get_option('wps_shortcode_options_'.'wps_alerts_friends') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_alerts_friends_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays an icon for pending friendship requests.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-alerts-friends] to a WordPress Page, Post of Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Icon size", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_size = wps_get_shortcode_default($values, 'wps_alerts_friends-flag_size', 24);
                                        echo '<input type="text" name="wps_alerts_friends-flag_size" value="'.$flag_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_size="'.$flag_size.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon pending number size", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_pending_size = wps_get_shortcode_default($values, 'wps_alerts_friends-flag_pending_size', 10);
                                        echo '<input type="text" name="wps_alerts_friends-flag_pending_size" value="'.$flag_pending_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_pending_size="'.$flag_pending_size.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon pending number top margin", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_pending_top = wps_get_shortcode_default($values, 'wps_alerts_friends-flag_pending_top', 6);
                                        echo '<input type="text" name="wps_alerts_friends-flag_pending_top" value="'.$flag_pending_top.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_pending_top="'.$flag_pending_top.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon pending number left margin", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_pending_left = wps_get_shortcode_default($values, 'wps_alerts_friends-flag_pending_left', 8);
                                        echo '<input type="text" name="wps_alerts_friends-flag_pending_left" value="'.$flag_pending_left.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_pending_left="'.$flag_pending_left.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon pending number radius", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_pending_radius = wps_get_shortcode_default($values, 'wps_alerts_friends-flag_pending_radius', 8);
                                        echo '<input type="text" name="wps_alerts_friends-flag_pending_radius" value="'.$flag_pending_radius.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(flag_pending_radius="'.$flag_pending_radius.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon URL", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_url = wps_get_shortcode_default($values, 'wps_alerts_friends-flag_url', '');
                                        echo '<input type="text" name="wps_alerts_friends-flag_url" value="'.$flag_url.'" /></td><td>(flag_url="'.$flag_url.'")</td></tr>';
                                    echo '<tr><td>'.__("Icon image alernative URL", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $flag_src = wps_get_shortcode_default($values, 'wps_alerts_friends-flag_src', '');
                                        echo '<input type="text" name="wps_alerts_friends-flag_src" value="'.$flag_src.'" /></td><td>(flag_src="'.$flag_src.'")</td></tr>';    

                                    do_action('wps_show_styling_options_hook', 'wps_alerts_friends', $values);                    

                                echo '</table>';
                            echo '</div>';       

                            /* ----------------------- PROFILE TAB ----------------------- */    

                            // [wps-usermeta]
                            $values = get_option('wps_shortcode_options_'.'wps_usermeta') ? get_option('wps_shortcode_options_'.'wps_usermeta') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_usermeta_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a profile value (meta) of a user, including standard WordPress meta values such as display_name.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-usermeta] to a WordPress Page, Post of Text widget. Can be added more than once.', WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('Tip:', WPS2_TEXT_DOMAIN).'</strong> '.__('Choose options below (and save) to see how you can add [wps-usermeta meta="<em>value</em>"] to build up your profile page.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_usermeta-label', '');
                                        echo '<input type="text" name="wps_usermeta-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__('Meta value', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $meta = wps_get_shortcode_default($values, 'wps_usermeta-meta', 'wpspro_home');
                                        echo '<select name="wps_usermeta-meta">';
                                            echo '<option value="description"'.($meta == 'description' ? ' SELECTED' : '').'>'.__('Description', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="wpspro_last_active"'.($meta == 'wpspro_last_active' ? ' SELECTED' : '').'>'.__('Last active', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="display_name"'.($meta == 'display_name' ? ' SELECTED' : '').'>'.__('Display name', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="first_name"'.($meta == 'first_name' ? ' SELECTED' : '').'>'.__('First name', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="last_name"'.($meta == 'last_name' ? ' SELECTED' : '').'>'.__('Last name', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="user_login"'.($meta == 'user_login' ? ' SELECTED' : '').'>'.__('Username', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="user_email"'.($meta == 'user_email' ? ' SELECTED' : '').'>'.__('User email', WPS2_TEXT_DOMAIN).'</option>';    
                                            echo '<option value="user_nicename"'.($meta == 'user_nicename' ? ' SELECTED' : '').'>'.__('User nice name', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="user_registered"'.($meta == 'user_registered' ? ' SELECTED' : '').'>'.__('User registration date', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="user_url"'.($meta == 'user_url' ? ' SELECTED' : '').'>'.__('User URL', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="user_status"'.($meta == 'user_status' ? ' SELECTED' : '').'>'.__('User status', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="wpspro_home"'.($meta == 'wpspro_home' ? ' SELECTED' : '').'>'.__('Town/City', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="wpspro_country"'.($meta == 'wpspro_country' ? ' SELECTED' : '').'>'.__('Country', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="wpspro_map"'.($meta == 'wpspro_map' ? ' SELECTED' : '').'>'.__('Map', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(meta="'.$meta.'")</td></tr>';

                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('If Map selected above...', WPS2_TEXT_DOMAIN).'</strong></td></tr>';            
                                    echo '<tr><td>'.__("Size", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $size = wps_get_shortcode_default($values, 'wps_usermeta-label', '250,250');
                                        echo '<input type="text" name="wps_usermeta-size" value="'.$size.'" /> ',__('pixels', WPS2_TEXT_DOMAIN).'</td><td>(size="'.$size.'")</td></tr>';
                                    echo '<tr><td>'.__('Map style', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $map_style = wps_get_shortcode_default($values, 'wps_usermeta-map_style', 'dynamic');
                                        echo '<select name="wps_usermeta-map_style">';
                                            echo '<option value="dynamic"'.($layout == 'dynamic' ? ' SELECTED' : '').'>'.__('Dynamic', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="static"'.($layout == 'static' ? ' SELECTED' : '').'>'.__('Static', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(map_style="'.$map_style.'")</td></tr>';
                                    echo '<tr><td>'.__('Zoom level', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $zoom = wps_get_shortcode_default($values, 'wps_usermeta-zoom', 5);
                                        echo '<select name="wps_usermeta-zoom">';
                                            echo '<option value="1"'.($zoom == '1' ? ' SELECTED' : '').'>1</option>';
                                            echo '<option value="2"'.($zoom == '2' ? ' SELECTED' : '').'>2</option>';
                                            echo '<option value="3"'.($zoom == '3' ? ' SELECTED' : '').'>3</option>';
                                            echo '<option value="4"'.($zoom == '4' ? ' SELECTED' : '').'>4</option>';
                                            echo '<option value="5"'.($zoom == '5' ? ' SELECTED' : '').'>5</option>';
                                            echo '<option value="6"'.($zoom == '6' ? ' SELECTED' : '').'>6</option>';
                                            echo '<option value="7"'.($zoom == '7' ? ' SELECTED' : '').'>7</option>';
                                            echo '<option value="8"'.($zoom == '8' ? ' SELECTED' : '').'>8</option>';
                                            echo '<option value="9"'.($zoom == '9' ? ' SELECTED' : '').'>9</option>';
                                            echo '<option value="10"'.($zoom == '10' ? ' SELECTED' : '').'>10</option>';
                                            echo '<option value="11"'.($zoom == '11' ? ' SELECTED' : '').'>11</option>';
                                            echo '<option value="12"'.($zoom == '12' ? ' SELECTED' : '').'>12</option>';
                                            echo '<option value="13"'.($zoom == '13' ? ' SELECTED' : '').'>13</option>';
                                            echo '<option value="14"'.($zoom == '14' ? ' SELECTED' : '').'>14</option>';
                                            echo '<option value="15"'.($zoom == '15' ? ' SELECTED' : '').'>15</option>';
                                        echo '</select></td><td>(zoom="'.$zoom.'")</td></tr>';

                                    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('If User email selected above...', WPS2_TEXT_DOMAIN).'</strong></td></tr>';                
                                    echo '<tr><td>'.__('Email as hyperlink', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $link = wps_get_shortcode_default($values, 'wps_usermeta-link', true);
                                        echo '<input type="checkbox" name="wps_usermeta-link"'.($link ? ' CHECKED' : '').'></td><td>(link="'.($link ? '1' : '0').'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_usermeta', $values);                    

                                echo '</table>';
                            echo '</div>';   

                            // [wps-usermeta-button]
                            $values = get_option('wps_shortcode_options_'.'wps_usermeta_button') ? get_option('wps_shortcode_options_'.'wps_usermeta_button') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_usermeta_button_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a button to link to a URL, passing the user's ID as a parameter.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-usermeta-button] to a WordPress Page.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("URL for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $url = wps_get_shortcode_default($values, 'wps_usermeta_button-url', '');
                                        echo '<input type="text" name="wps_usermeta_button-url" value="'.$url.'" /></td><td>(url="'.$url.'")</td></tr>';
                                    echo '<tr><td>'.__("Label for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $value = wps_get_shortcode_default($values, 'wps_usermeta_button-value', __('Go', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_button-value" value="'.$value.'" /></td><td>(value="'.$value.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional CSS class for button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_usermeta_button-class', '');
                                        echo '<input type="text" name="wps_usermeta_button-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_usermeta_button', $values);                    

                                echo '</table>';
                            echo '</div>';         

                            // [wps-usermeta-change]
                            $values = get_option('wps_shortcode_options_'.'wps_usermeta_change') ? get_option('wps_shortcode_options_'.'wps_usermeta_change') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_usermeta_change_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays profile fields for the user, which they can edit. This is their Edit Profile page.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-usermeta-change] to a WordPress Page.', WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('Tip:', WPS2_TEXT_DOMAIN).'</strong> '.__('Use "Edit Profile" setup below to add Tabs to your edit profile page.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__("Label for update button", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_usermeta_change-label', __('Update', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__('Show Town/City', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_town = wps_get_shortcode_default($values, 'wps_usermeta_change-show_town', true);
                                        echo '<input type="checkbox" name="wps_usermeta_change-show_town"'.($show_town ? ' CHECKED' : '').'></td><td>(show_town="'.($show_town ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Town/City label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $town = wps_get_shortcode_default($values, 'wps_usermeta_change-town', __('Town/City', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-town" value="'.$town.'" /></td><td>(town="'.$town.'")</td></tr>';
                                    echo '<tr><td>'.__('Show Country', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_country = wps_get_shortcode_default($values, 'wps_usermeta_change-show_country', true);
                                        echo '<input type="checkbox" name="wps_usermeta_change-show_country"'.($show_country ? ' CHECKED' : '').'></td><td>(show_country="'.($show_country ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("Country label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $country = wps_get_shortcode_default($values, 'wps_usermeta_change-country', __('Country', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-country" value="'.$country.'" /></td><td>(country="'.$country.'")</td></tr>';
                                    echo '<tr><td>'.__("Display name label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $displayname = wps_get_shortcode_default($values, 'wps_usermeta_change-displayname', __('Display Name', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-displayname" value="'.$displayname.'" /></td><td>(displayname="'.$displayname.'")</td></tr>';
                                    echo '<tr><td>'.__('Show First/family names', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $show_name = wps_get_shortcode_default($values, 'wps_usermeta_change-show_name', true);
                                        echo '<input type="checkbox" name="wps_usermeta_change-show_name"'.($show_name ? ' CHECKED' : '').'></td><td>(show_name="'.($show_name ? '1' : '0').'")</td></tr>';
                                    echo '<tr><td>'.__("First/family name label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $name = wps_get_shortcode_default($values, 'wps_usermeta_change-name', __('Your first name and family name', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-name" value="'.$name.'" /></td><td>(name="'.$name.'")</td></tr>';
                                    echo '<tr><td>'.__("Password label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $password = wps_get_shortcode_default($values, 'wps_usermeta_change-password', __('Change your password', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-password" value="'.$password.'" /></td><td>(password="'.$password.'")</td></tr>';
                                    echo '<tr><td>'.__("Re-type your password label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $password2 = wps_get_shortcode_default($values, 'wps_usermeta_change-password2', __('Re-type your password', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-password2" value="'.$password2.'" /></td><td>(password2="'.$password2.'")</td></tr>';
                                    echo '<tr><td>'.__("Password change, log in again message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $password_msg = wps_get_shortcode_default($values, 'wps_usermeta_change-password_msg', __('Password changed, please log in again.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-password_msg" value="'.$password_msg.'" /></td><td>(password_msg="'.$password_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Email label", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $email = wps_get_shortcode_default($values, 'wps_usermeta_change-email', __('Email address', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-email" value="'.$email.'" /></td><td>(email="'.$email.'")</td></tr>';
                                    echo '<tr><td>'.__("Logged out message", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $logged_out_msg = wps_get_shortcode_default($values, 'wps_usermeta_change-logged_out_msg', __('You must be logged in to view this page.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-logged_out_msg" value="'.$logged_out_msg.'" /></td><td>(logged_out_msg="'.$logged_out_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Mandatory suffix", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $mandatory = wps_get_shortcode_default($values, 'wps_usermeta_change-mandatory', '&lt;span style=\'color:red;\'&gt; *&lt;/span&gt;');
                                        echo '<input type="text" name="wps_usermeta_change-mandatory" value="'.$mandatory.'" /></td><td>(mandatory="'.$mandatory.'")</td></tr>';
                                    echo '<tr><td>'.__("Required fields alert", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $required_msg = wps_get_shortcode_default($values, 'wps_usermeta_change-required_msg', __('Please check for required fields', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change-required_msg" value="'.$required_msg.'" /></td><td>(required_msg="'.$required_msg.'")</td></tr>';
                                    echo '<tr><td>'.__("Optional URL to login", WPS2_TEXT_DOMAIN).'</td><td>';
                                        $login_url = wps_get_shortcode_default($values, 'wps_usermeta_change-login_url', '');
                                        echo '<input type="text" name="wps_usermeta_change-login_url" value="'.$login_url.'" /></td><td>(login_url="'.$login_url.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_usermeta_change', $values);                    

                                echo '</table>';
                            echo '</div>';       

                            // [wps-usermeta-change-link]    
                            $values = get_option('wps_shortcode_options_'.'wps_usermeta_change_link') ? get_option('wps_shortcode_options_'.'wps_usermeta_change_link') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_usermeta_change_link_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a link to the Edit Profile page.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-usermeta-change-link] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Text for the link', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $text = wps_get_shortcode_default($values, 'wps_usermeta_change_link-text', __('Edit Profile', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_usermeta_change_link-text" value="'.$text.'" /></td><td>(text="'.$text.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_usermeta_change_link', $values);                    

                                echo '</table>';
                            echo '</div>'; 

                            // [wps-close-account]
                            $values = get_option('wps_shortcode_options_'.'wps_close_account') ? get_option('wps_shortcode_options_'.'wps_close_account') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_close_account_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays button for users to close their account.", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-close-account] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Optional CSS class for button', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_close_account-class', '');
                                        echo '<input type="text" name="wps_close_account-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';
                                    echo '<tr><td>'.__('Label for button', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_close_account-label', __('Close account', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_close_account-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__('Are you sure text', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $are_you_sure_text = wps_get_shortcode_default($values, 'wps_close_account-are_you_sure_text', __('Are you sure? You cannot re-open a closed account.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_close_account-are_you_sure_text" value="'.$are_you_sure_text.'" /></td><td>(are_you_sure_text="'.$are_you_sure_text.'")</td></tr>';
                                    echo '<tr><td>'.__('Account has been closed text', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $logout_text = wps_get_shortcode_default($values, 'wps_close_account-logout_text', __('Your account has been closed.', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_close_account-logout_text" value="'.$logout_text.'" /></td><td>(logout_text="'.$logout_text.'")</td></tr>';
                                    echo '<tr><td>'.__('URL after account is closed', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $url = wps_get_shortcode_default($values, 'wps_close_account-url', '/');
                                        echo '<input type="text" name="wps_close_account-url" value="'.$url.'" /></td><td>(url="'.$url.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_close_account', $values);                    

                                echo '</table>';
                            echo '</div>';             

                            // [wps-join-site] 
                            $values = get_option('wps_shortcode_options_'.'wps_join_site') ? get_option('wps_shortcode_options_'.'wps_join_site') : array();   
                            echo wps_show_options($wps_expand_shortcode, 'wps_join_site_tab');
                                echo '<strong>'.__('Purpose:', WPS2_TEXT_DOMAIN).'</strong> '.__("Displays a link or button to join a site (multisite only).", WPS2_TEXT_DOMAIN).'<br />';
                                echo '<strong>'.__('How to use:', WPS2_TEXT_DOMAIN).'</strong> '.__('Add [wps-join-site] to a WordPress Page, Post or Text widget.', WPS2_TEXT_DOMAIN);
                                echo '<p><strong>'.__('Options', WPS2_TEXT_DOMAIN).'</strong><br />';
                                echo '<table cellpadding="0" cellspacing="0"  class="wps_shortcode_value_row">';
                                    echo '<tr><td>'.__('Optional CSS class for button', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $class = wps_get_shortcode_default($values, 'wps_join_site-class', '');
                                        echo '<input type="text" name="wps_join_site-class" value="'.$class.'" /></td><td>(class="'.$class.'")</td></tr>';
                                    echo '<tr><td>'.__('Label for link/button', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $label = wps_get_shortcode_default($values, 'wps_join_site-label', __('Join this site', WPS2_TEXT_DOMAIN));
                                        echo '<input type="text" name="wps_join_site-label" value="'.$label.'" /></td><td>(label="'.$label.'")</td></tr>';
                                    echo '<tr><td>'.__('Style', WPS2_TEXT_DOMAIN).'</td><td>';
                                        $style = wps_get_shortcode_default($values, 'wps_join_site-style', 'button');
                                        echo '<select name="wps_join_site-style">';
                                            echo '<option value="button"'.($style == 'button' ? ' SELECTED' : '').'>'.__('Button', WPS2_TEXT_DOMAIN).'</option>';
                                            echo '<option value="text"'.($style == 'text' ? ' SELECTED' : '').'>'.__('Text', WPS2_TEXT_DOMAIN).'</option>';
                                        echo '</select></td><td>(style="'.$style.'")</td></tr>';

                                    do_action('wps_show_styling_options_hook', 'wps_join_site', $values);                    

                                echo '</table>';
                            echo '</div>';             


                            /* OTHERS */

                            do_action('wps_options_shortcode_options_hook', $wps_expand_shortcode);        


                        echo '</div>';

                        
                    }
            
                echo '</div>';

            echo '</div>';

        echo '</div>';

}


function wps_show_tab($wps_expand_tab, $tab, $option, $text) {
    return '<div class="'.($wps_expand_tab == $option ? 'wps_admin_getting_started_active' : '').' wps_admin_getting_started_option" id="'.$option.'" data-shortcode="'.$option.'">'.$text.'</div>';
}

function wps_show_shortcode($wps_expand_tab, $wps_expand_shortcode, $tab, $function, $shortcode) {
    return '<div rel="'.$function.'" class="'.($wps_expand_shortcode == $function ? 'wps_admin_getting_started_active' : '').' wps_'.$tab.' wps_admin_getting_started_option_shortcode" data-tab="'.$function.'" style="display:'.($wps_expand_tab == $tab ? 'block' : 'none').'">['.$shortcode.']</div>';
}

function wps_show_options($wps_expand_shortcode, $function) {
    return '<div id="'.$function.'" class="wps_admin_getting_started_option_value" style="display:'.($wps_expand_shortcode == $function ? 'block' : 'none').'">';
}

function wps_get_shortcode_default($values, $name, $default) {

    // Remove function if passed in format function-option
    if (strpos($name, '-')):
        $arr = explode('-',$name);
        $name = $arr[1];
    endif;

    // Now calculate value stored
    if ($default === false || $default === true) {
        $v = isset($values[$name]) && ($values[$name] == 'on' || $values[$name] == 'off' ) ? $values[$name] : false;
        if ($v) {
            $v = $v == 'on' ? true : false; 
        } else {
            $v = $default;
        }
    } else {
        $v = isset($values[$name]) && $values[$name] ? $values[$name] : $default;
    }
    return $v;
}

function wps_save_option($values, $the_post, $name, $checkbox=false) {
    if (!$checkbox) {
        $v = isset($the_post[$name]) ? $the_post[$name] : false;
    } else {
        $v = isset($the_post[$name]) ? 'on' : 'off';        
    }
    $values[$name] = $v ? htmlentities (htmlspecialcharacters_decode(stripslashes($v), ENT_QUOTES)) : '';
    return $values;
}

// Show styling options in setup
if (is_admin()) add_action('wps_show_styling_options_hook', 'wps_show_styling_options', 10, 2);		
function wps_show_styling_options($function, $values) {

    echo '<tr><td colspan=3 style="background-color:#efefef"><strong>'.__('Style (not available via shortcode options)...', WPS2_TEXT_DOMAIN).'</strong><br />'.__('Add style="0" to a shortcode to avoid using the following styles.', WPS2_TEXT_DOMAIN).'</td></tr>';    
    echo '<tr><td>'.__('Top margin', WPS2_TEXT_DOMAIN).'</td><td>';
        $margin_top = wps_get_shortcode_default($values, $function.'-margin_top', 0);
        echo '<input type="text" name="'.$function.'-margin_top" value="'.$margin_top.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Right margin', WPS2_TEXT_DOMAIN).'</td><td>';
        $margin_right = wps_get_shortcode_default($values, $function.'-margin_right', 0);
        echo '<input type="text" name="'.$function.'-margin_right" value="'.$margin_right.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Bottom margin', WPS2_TEXT_DOMAIN).'</td><td>';
        $margin_bottom = wps_get_shortcode_default($values, $function.'-margin_bottom', 0);
        echo '<input type="text" name="'.$function.'-margin_bottom" value="'.$margin_bottom.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Left margin', WPS2_TEXT_DOMAIN).'</td><td>';
        $margin_left = wps_get_shortcode_default($values, $function.'-margin_left', 0);
        echo '<input type="text" name="'.$function.'-margin_left" value="'.$margin_left.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Top padding', WPS2_TEXT_DOMAIN).'</td><td>';
        $padding_top = wps_get_shortcode_default($values, $function.'-padding_top', 0);
        echo '<input type="text" name="'.$function.'-padding_top" value="'.$padding_top.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Right padding', WPS2_TEXT_DOMAIN).'</td><td>';
        $padding_right = wps_get_shortcode_default($values, $function.'-padding_right', 0);
        echo '<input type="text" name="'.$function.'-padding_right" value="'.$padding_right.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Bottom padding', WPS2_TEXT_DOMAIN).'</td><td>';
        $padding_bottom = wps_get_shortcode_default($values, $function.'-padding_bottom', 0);
        echo '<input type="text" name="'.$function.'-padding_bottom" value="'.$padding_bottom.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Left padding', WPS2_TEXT_DOMAIN).'</td><td>';
        $padding_left = wps_get_shortcode_default($values, $function.'-padding_left', 0);
        echo '<input type="text" name="'.$function.'-padding_left" value="'.$padding_left.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__("Clear previous float", WPS2_TEXT_DOMAIN).'</td><td>';
        $clear = wps_get_shortcode_default($values, $function.'-clear', true);
        echo '<input type="checkbox" name="'.$function.'-clear"'.($clear ? ' CHECKED' : '').'> <em>clear: '.($clear ? 'both' : 'none').'</em></td><td></td></tr>';
    echo '<tr><td>'.__('Background color', WPS2_TEXT_DOMAIN).'</td><td>';
        $background_color = wps_get_shortcode_default($values, $function.'-background_color', 'transparent');
        echo '<input type="text" name="'.$function.'-background_color" class="wps-color-picker" data-default-color="transparent" value="'.$background_color.'" /></td><td></td></tr>';
    echo '<tr><td>'.__('Border size', WPS2_TEXT_DOMAIN).'</td><td>';
        $border_size = wps_get_shortcode_default($values, $function.'-border_size', 0);
        echo '<input type="text" name="'.$function.'-border_size" value="'.$border_size.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Border color', WPS2_TEXT_DOMAIN).'</td><td>';
        $border_color = wps_get_shortcode_default($values, $function.'-border_color', '#000');
        echo '<input type="text" name="'.$function.'-border_color" class="wps-color-picker" data-default-color="#000" value="'.$border_color.'" /></td><td></td></tr>';
    echo '<tr><td>'.__('Border radius', WPS2_TEXT_DOMAIN).'</td><td>';
        $border_radius = wps_get_shortcode_default($values, $function.'-border_radius', 0);
        echo '<input type="text" name="'.$function.'-border_radius" value="'.$border_radius.'" /> '.__('pixels', WPS2_TEXT_DOMAIN).'</td><td></td></tr>';
    echo '<tr><td>'.__('Border style', WPS2_TEXT_DOMAIN).'</td><td>';
        $border_style = wps_get_shortcode_default($values, $function.'-border_style', 'solid');
        echo '<select name="'.$function.'-border_style">';
            echo '<option value="solid"'.($border_style == 'solid' ? ' SELECTED' : '').'>'.__('Solid', WPS2_TEXT_DOMAIN).'</option>';
            echo '<option value="dotted"'.($border_style == 'dotted' ? ' SELECTED' : '').'>'.__('Dotted', WPS2_TEXT_DOMAIN).'</option>';
            echo '<option value="dashed"'.($border_style == 'dashed' ? ' SELECTED' : '').'>'.__('Dashed', WPS2_TEXT_DOMAIN).'</option>';
        echo '</select></td><td></td></tr>';
    echo '<tr><td>'.__('Width', WPS2_TEXT_DOMAIN).'</td><td>';
        $style_width = wps_get_shortcode_default($values, $function.'-style_width', '100%');
        echo '<input type="text" name="'.$function.'-style_width" value="'.$style_width.'" /> ('.__('include px or %', WPS2_TEXT_DOMAIN).')</td><td></td></tr>';
    echo '<tr><td>'.__('Height', WPS2_TEXT_DOMAIN).'</td><td>';
        $style_height = wps_get_shortcode_default($values, $function.'-style_height', '');
        echo '<input type="text" name="'.$function.'-style_height" value="'.$style_height.'" /> ('.__('include px', WPS2_TEXT_DOMAIN).')</td><td></td></tr>';
    
}
if (is_admin()) add_filter( 'wps_show_styling_options_save_filter', 'wps_show_styling_options_save', 10, 3 );
function wps_show_styling_options_save($function, $the_post, $values) {
    
    $values = wps_save_option($values, $the_post, $function.'-margin_top');      
    $values = wps_save_option($values, $the_post, $function.'-margin_bottom');
    $values = wps_save_option($values, $the_post, $function.'-margin_left');
    $values = wps_save_option($values, $the_post, $function.'-margin_right');
    $values = wps_save_option($values, $the_post, $function.'-padding_top');      
    $values = wps_save_option($values, $the_post, $function.'-padding_bottom');
    $values = wps_save_option($values, $the_post, $function.'-padding_left');
    $values = wps_save_option($values, $the_post, $function.'-padding_right');
    $values = wps_save_option($values, $the_post, $function.'-clear', true);
    $values = wps_save_option($values, $the_post, $function.'-border_size');
    $values = wps_save_option($values, $the_post, $function.'-border_color');
    $values = wps_save_option($values, $the_post, $function.'-border_radius');
    $values = wps_save_option($values, $the_post, $function.'-border_style');
    $values = wps_save_option($values, $the_post, $function.'-background_color');
    $values = wps_save_option($values, $the_post, $function.'-style_width');
    $values = wps_save_option($values, $the_post, $function.'-style_height');

    return $values;
    
}

// System Options header
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_core_header', 0.5);
function wps_admin_getting_started_core_header() {
    echo '<h2>'.sprintf(__('Options (not set via <a href="%s">shortcode</a>)', WPS2_TEXT_DOMAIN), admin_url( 'admin.php?page=wps_pro_shortcodes' )).'</h2>';
}

// Add to Getting Started information
add_action('wps_admin_getting_started_hook', 'wps_admin_getting_started_core', 1);
function wps_admin_getting_started_core() {

    echo '<a name="core"></a>';
    $css = isset($_POST['wps_expand']) && $_POST['wps_expand'] == 'wps_admin_getting_started_core' ? 'wps_admin_getting_started_menu_item_remove_icon ' : '';    
  	echo '<div class="'.$css.'wps_admin_getting_started_menu_item" id="wps_admin_getting_started_core_div" rel="wps_admin_getting_started_core">'.__('System Options', WPS2_TEXT_DOMAIN).'</div>';

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
