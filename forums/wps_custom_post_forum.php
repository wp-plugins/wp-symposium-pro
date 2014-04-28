<?php

/* Create forum_post custom post type */

/* =========================== LABELS FOR ADMIN =========================== */


function wps_custom_post_forum_post() {
	$labels = array(
		'name'               => _x( 'Posts', 'post type general name' ),
		'singular_name'      => _x( 'Post', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'wps_forum_post' ),
		'add_new_item'       => __( 'Add New post' ),
		'edit_item'          => __( 'Edit post' ),
		'new_item'           => __( 'New post' ),
		'all_items'          => __( 'Forum Posts' ),
		'view_item'          => __( 'View Forum Post' ),
		'search_items'       => __( 'Search Forum Posts' ),
		'not_found'          => __( 'No forum post found' ),
		'not_found_in_trash' => __( 'No forum post found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Forum Posts'
	);
	$args = array(
		'labels'        		=> $labels,
		'description'   		=> 'Holds our forum post specific data',
		'public'        		=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'wps_pro',
		'publicly_queryable'	=> false,
		'has_archive'			=> false,
		'rewrite'				=> false,
		'supports'      		=> array( 'title', 'editor', 'comments' ),
		'has_archive'   		=> false,
	);
	register_post_type( 'wps_forum_post', $args );
}
add_action( 'init', 'wps_custom_post_forum_post' );

/* =========================== MESSAGES FOR ADMIN =========================== */

function wps_updated_forum_post_messages( $messages ) {
	global $post, $post_ID;
	$messages['wps_forum_post'] = array(
		0 => '', 
		1 => __('Post updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Post updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Post restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Post published.'),
		7 => __('Post saved.'),
		8 => __('Post submitted.'),
		9 => sprintf( __('Post scheduled for: <strong>%1$s</strong>.'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Post draft updated.'),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'wps_updated_forum_post_messages' );


/* =========================== META FIELDS CONTENT BOX WHEN EDITING =========================== */


add_action( 'add_meta_boxes', 'forum_post_info_box' );
function forum_post_info_box() {
    add_meta_box( 
        'forum_post_info_box',
        __( 'Post Details', WPS2_TEXT_DOMAIN ),
        'forum_post_info_box_content',
        'wps_forum_post',
        'side',
        'high'
    );
}

function forum_post_info_box_content( $post ) {
	global $wpdb;
	wp_nonce_field( 'forum_post_info_box_content', 'forum_post_info_box_content_nonce' );

	echo '<strong>'.__('Post author', WPS2_TEXT_DOMAIN).'</strong><br />';
	$author = get_user_by('id', $post->post_author);
	echo $author->display_name.'<br />';
	echo 'ID: '.$author->ID;

	echo '<br /><br >';
	echo '<input type="checkbox" name="wps_sticky"';
		if (get_post_meta($post->ID, 'wps_sticky', true)) echo ' CHECKED';
		echo '> '.__('Stick to top of posts?', WPS2_TEXT_DOMAIN);
}

add_action( 'save_post', 'forum_post_info_box_save' );
function forum_post_info_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	return;

	if ( !isset($_POST['forum_post_info_box_content_nonce']) || !wp_verify_nonce( $_POST['forum_post_info_box_content_nonce'], 'forum_post_info_box_content' ) )
	return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;

	if (isset($_POST['wps_sticky'])) update_post_meta($post_id, 'wps_sticky', true);


}

/* =========================== COLUMNS WHEN VIEWING =========================== */

/* Columns for Posts list */
add_filter('manage_posts_columns', 'forum_post_columns_head');
add_action('manage_posts_custom_column', 'forum_post_columns_content', 10, 2);

// ADD NEW COLUMN
function forum_post_columns_head($defaults) {
    global $post;
	if ($post->post_type == 'wps_forum_post') {
    }
    return $defaults;
}
 
// SHOW THE COLUMN CONTENT
function forum_post_columns_content($column_name, $post_ID) {

}


?>