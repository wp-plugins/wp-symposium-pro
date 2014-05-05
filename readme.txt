=== WP Symposium Pro Social Network plugin ===
Author: WP Symposium Pro
Contributors: Simon Goodchild
Donate link: http://www.wpsymposiumpro.com
Link: http://www.wpsymposiumpro.com
Tags: wp symposium pro, social network, social networking, social media, wpsymposium pro, wp-symposium, wp symposium, symposium
Requires at least: 3.0
Tested up to: 3.9
License: GPLv2 or later
Stable tag: 0.58.3

Create your own social network in WordPress!

== Description ==

**This is the ultimate social network plugin for WordPress.** You can create your own social network, on your WordPress website.

With profiles, activity (wall), unlimited forums, friends, email alerts and so much more, it's perfect for clubs, schools, interest groups, engaging with customers, support sites, gaming sites, dating sites, limited only by your imagination!

Just add the plugin, click a button and you have your own social network, simple as that. :)

Incredibly compatible with themes and plugins. Find out more, including additional plugins for WP Symposium Pro, at http://www.wpsymposiumpro.com.

Want to change something, the layout, text, button labels? WP Symposium Pro’s real power lies behind shortcodes with options galore, that allow you to change just about everything, and design your social network pages the way you want them!

Multi-lingual site? No problem! Easily change all the text your users will see through options. Using WPML? Works happily with that plugin too!

Extra plugins are available that add features galore! Private mail, groups, choose who to share activity with, image and YouTube attachments, and many more! Please note that these are separate plugins.

Find out all about it at http://www.wpsymposiumpro.com.

Oh, and drink tea, tea is good...

== Installation ==

Go to plugins in your admin dashboard and click Add New, and search for WP Symposium Pro.

Once activated, to get started quickly, go to WPS Pro->Setup (a new menu item), and click on the "Add Profile Pages" button (once).

Erm... that's it!

This will install a default profile page (with activity, etc), an edit profile page, a change avatar page and a friendships.

Then click on the "Add Forum" button for as many forums as you want!

You may then want to add your new WordPress pages to your site menu via Appearance->Menus.

To manually install, download the ZIP file, extract contents and place in wp_content/wp-symposium-pro folder.

== Frequently Asked Questions ==

Please visit http://www.wpsymposiumpro.com/frequently-asked-questions

== Screenshots ==

The best way to see it in action, and try it out for free, is visit http://www.wpsymposiumpro.com !

== Changelog ==

0.58.3 New shortcode [wps-friends-status] to show status of friendship

0.58.2 Fixed potential security risk when viewing individual posts
       Added secure_post_msg to [wps-forum]
0.58.1 Minor changes
0.58   Can change sticky status when editing a post (admin only)
       Forum can now be locked (via forum setup) to stop new posts/replies
       Added options to [wps-forums]:
       -> show_summary, show_posts and show_posts_header
       Added translation options for [wps-forums], [wps-forum] and [wps-forum-page]:
       -> forum_title, forum_count, forum_last_activity and forum_freshness
       -> header_title, header_count, header_last_activity

0.57   Can now delete forum posts/replies if you are the owner or admin
       Various options added to [wps-forum] and [wps-usermeta] to support translations and delete feature

0.56.4 Update to wps_display_name() to support WPML plugin
0.56.3 Fixed bug when editing a forum post/comment when is the author
0.56.2 Minor fix to link from forum post on sub-folder installation
0.56.1 Minor code change for repository
0.56   First release to WordPress repository

0.55.2 Parameter to include user_id for [wps-usermeta]
0.55.1 Added IDs to some DIVs to help with styling
0.55   Prepared for release on WordPress repository
       Added option to edit profile page to switch off alerts for activity

0.54.1 Minor change
0.54   Added [wps-forum-page] shortcode

0.53.3 Improved Forum help on setup
0.53.2 Fix to shortcodes inserts when added a new quick-start forum
0.53.1 Update to setup to make new page clear
0.53   Bug fix (removed rogue </div> in forum setup)

0.52.1 Quickly setup unlimited forums via WPS Pro->Setup (quick start)
0.52   Forums plugin now included in core plugin. Forums plugin no longer required.

0.51   Alerts plugin now included in core plugin. Alerts plugin no longer required.

0.50.1 Changed default map type to dynamic
0.50   Made the getting started page more attractive and useful

0.49   Activity now included in core plugin. Activity plugin no longer required.

0.48   Added hooks to allow other plugins to hook into core setup

0.47.1 select2 only loaded when necessary

0.47   Removed external AJAX files (now handled through WP ajax)
       Added select2 to core
       
0.46   Added [map]address[/map] and [map zoom=n]address[/map] to BB Codes

0.45   Removed dependency on Taxonomy Metadata plugin

0.44.2 Added support for BB Codes: list and [*]
0.44.1 Added missing translations
0.44   Small changes to allow .mo translation file to be placed in plugins folder

0.43.3 Fix to Friendship cancellation
0.43.2 Moved BB Code CSS into core
0.43.1 Fix to [wps-friends]
0.43   Move wps_bbcode_replace() to core

0.41.2 Fixed count="n" for [wps-friends]
0.41.1 Fix to load CSS for friendship shortcodes
0.41   Only loads JS/CSS when required

0.40.2 Localised admin labels
0.40.1 Bug fix to hide when friend was last active if they haven't been active
0.40   If a user has not uploaded an avatar, the Wordpress default is shown instead

0.39   Removed use of jQuery UI from front-end, only used in admin

0.38   Added options to skip loading jQuery UI/CSS (WPS Pro->Setup->Core Options)

0.37.2 wps_are_friends() function improved
0.37.1 Added show_last_active option to [wps-friends]
0.37   Added generic wait jQuery modal, just use jQuery("body").addClass("wps_wait_loading");

0.36   Added wps_admin_setup_form_get_hook to admin Getting Started

0.35.1 Fixed wps_display_name to handle no permalinks
0.35   Added Quick Start to setup, including button to quickly create profile pages

0.34.1 Added wps_error CSS style
0.34   Added wps_query_mark() function

0.33   Added wps_get_words() function

0.32   Improved Getting Started/Setup hooks for easy extension

0.31   Added hook for Getting Started info (under WPS Pro->Setup)

0.30   Added map_style option to [wps-usermeta], static or dynamic

0.29.1 Changed wps_curPageURL() function to use HTTP_POST instead of SERVER_NAME

0.29   Added support for language translation

0.28.2 Added [wps-usermeta-change] to editor toolbar button
0.28.1 Bug fix
0.28   Added WordPress TinyMCE editor toolbar button that can be extended by other plugins
       Requires WordPress v3.9+
	   Added [wps-display-name] and [wps-avatar] to WPS editor toolbar button

0.27   Custom CSS fixed to support quotes

0.26   Removed dependency on profile page being setup in wps_display_name() function

0.25   Added wps_curPageURL() function to core

0.24   Added Custom CSS admin screen (persistent across updates)

0.23.1 Fix to JS path
0.23   Included font icon logo in menu
       Added button to WYSIWYG editor (in preparation)
     
0.22   Various code changes

0.21   Added wpspro_last_active meta for user, updated on every page load via wp_footer hook

0.20   Added password change to [wps-usermeta-change]

0.19   Added display_name and user_email to [wps-usermeta-change]

0.18   Added hook and filter to wps-usermeta-change shortcode (edit profile)

0.17   Added hooks to integrate with WPS Pro setup page
       Fixed missing file upload load

0.16   Added wps_get_friends function

0.15   Removed fileinput to attachments plugin

0.14   Removed bootstrap to ensure theme/plugin compatibility

0.13   Standardise private and none options

0.12   Added support for before and after options on shortcodes

0.11   Implemented friendship requests

0.10   Added link to edit profile page [wps-usermeta-change-link]

0.9    Added front end edit profile [wps-usermeta-change]

0.8    Added change avatar to front end and link [wps_avatar_change_link] and [wps_avatar_change]

0.7    Added admin choice for icons color scheme

0.6    Added profile page selection to setup page

0.5    Initial external release

0.1-4  Internal development

== Upgrade Notice ==

Latest news and information on http://www.wpsymposiumpro.com
