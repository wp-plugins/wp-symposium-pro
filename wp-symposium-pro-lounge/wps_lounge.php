<?php
// Custom Post Type
require_once('wps_custom_post_lounge.php');

// Re-write rules
add_filter( 'rewrite_rules_array','wps_lounge_insert_rewrite_rules' );
add_action( 'wp_loaded','wps_lounge_flush_rewrite_rules' );

function wps_lounge_insert_rewrite_rules( $rules )
{
	global $wp_rewrite;
	$newrules = array();
	
	$newrules['wps_lounge/?'] = '/';

	return $newrules + $rules;
}
// Flush re-write rules if need be
function wps_lounge_flush_rewrite_rules(){
	
	$rules = get_option( 'rewrite_rules' );
	$flush = false;

	if ( ! isset( $rules['wps_lounge/?'] ) ) $flush = true;		

	if ($flush) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();			
	}

}

// Shortcodes
require_once('wps_lounge_shortcodes.php');

// AJAX
require_once('ajax_lounge.php');

// Getting Started/Help
if (is_admin())
	require_once('wps_lounge_help.php');


?>