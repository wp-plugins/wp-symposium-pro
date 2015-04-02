<?php

/* Create Lounge custom post type */


/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_lounge() {
	$labels = array(
		'name'               => __( 'Lounge', WPS2_TEXT_DOMAIN ),
		'singular_name'      => __( 'Chat', WPS2_TEXT_DOMAIN ),
		'add_new'            => __( 'Add New', WPS2_TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Chat', WPS2_TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Chat', WPS2_TEXT_DOMAIN ),
		'new_item'           => __( 'New Chat', WPS2_TEXT_DOMAIN ),
		'all_items'          => __( 'Lounge', WPS2_TEXT_DOMAIN ),
		'view_item'          => __( 'View Chat', WPS2_TEXT_DOMAIN ),
		'search_items'       => __( 'Search Chats', WPS2_TEXT_DOMAIN ),
		'not_found'          => __( 'No chat found', WPS2_TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No chat found in the Trash', WPS2_TEXT_DOMAIN ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Lounge', WPS2_TEXT_DOMAIN),
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our lounge specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'supports'      		=> array( 'title' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_lounge', $args );
}
add_action( 'init', 'wps_custom_post_lounge' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_lounge_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_lounge'] = array(
		0 => '', 
		1 => __('Chat updated.', WPS2_TEXT_DOMAIN),
		2 => __('Custom field updated.', WPS2_TEXT_DOMAIN),
		3 => __('Custom field deleted.', WPS2_TEXT_DOMAIN),
		4 => __('Chat updated.', WPS2_TEXT_DOMAIN),
		5 => isset($_GET['revision']) ? sprintf( __('Chat restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Chat published.', WPS2_TEXT_DOMAIN),
		7 => __('Chat saved.', WPS2_TEXT_DOMAIN),
		8 => __('Chat submitted.', WPS2_TEXT_DOMAIN),
		9 => sprintf( __('Chat scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Chat draft updated.', WPS2_TEXT_DOMAIN),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_lounge_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */

/* Not applicable */

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'lounge_columns_head');
add_action('manage_posts_custom_column', 'lounge_columns_content', 10, 2);

// ADD NEW COLUMN
function lounge_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_lounge') {
		$defaults['col_lounge_author'] = 'Author';
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function lounge_columns_content($column_name, $post_ID) {
    if ($column_name == 'col_lounge_author') {
    	$post = get_post($post_ID);
    	$author = get_user_by('id', $post->post_author);
    	echo $author->display_name.' ('.$author->user_login.')';
    }
}

/* =========================== ALTER VIEW POST LINKS =========================== */

/* Not applicable */

?>