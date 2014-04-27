<?php


/* Create forum_post custom taxonomy */

function add_wps_forum_custom_taxonomies() {

	register_taxonomy('wps_forum', 'wps_forum_post', array(

        'hierarchical'               => true,
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Forums', 'taxonomy general name' ),
			'singular_name' => _x( 'Forum', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Forums' ),
			'all_items' => __( 'All Forums' ),
			'parent_item' => __( 'Parent Forum' ),
			'parent_item_colon' => __( 'Parent Forum:' ),
			'edit_item' => __( 'Edit Forum' ),
			'update_item' => __( 'Update Forum' ),
			'add_new_item' => __( 'Add New Forum' ),
			'new_item_name' => __( 'New Forum Name' ),
			'menu_name' => __( 'Forums' ),
		),
        // Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'wps-forums', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));

}
add_action( 'init', 'add_wps_forum_custom_taxonomies', 0 );


add_action("wps_forum_add_form_fields", 'wps_taxonomy_metadata_add', 10, 1);
function wps_taxonomy_metadata_add( $tag ) {
	// Only allow users with capability to publish content
	if ( current_user_can( 'publish_posts' ) ): ?>
	<div class="form-field">
		<label for="wps_forum_public"><?php _e('Visibility', WPS2_TEXT_DOMAIN); ?></label>
		<input name="wps_forum_public" id="wps_forum_public" type="checkbox" style="width:10px" />
		<span class="description"><?php _e('Can this forum be viewed without logging in?', WPS2_TEXT_DOMAIN); ?></span>
	</div>

	<div class="form-field">
		<label for="wps_forum_order"><?php _e('Order', WPS2_TEXT_DOMAIN); ?></label>
		<input type="text" name="wps_forum_order" id="wps_forum_order" style="width:50px" />
		<span class="description"><?php _e('Order in which the forum is shown, in a list of forums.', WPS2_TEXT_DOMAIN); ?></span>
	</div> 

	<div class="form-field">
		<label for="wps_forum_cat_page"><?php _e('WordPress page', WPS2_TEXT_DOMAIN); ?></label>
		<select name="wps_forum_cat_page" name="wps_forum_cat_page">
		 <?php 
		  echo '<option value="0">'.__('Select page...', WPS2_TEXT_DOMAIN).'</option>';
		  $pages = get_pages(); 
		  foreach ( $pages as $page ) {
		  	$option = '<option value="' . $page->ID . '">';
			$option .= $page->post_title;
			$option .= '</option>';
			echo $option;
		  }
		 ?>						
		</select>
		<div class="description"><?php echo sprintf(__('WordPress page that has this forum\'s shortcodes on.<br />See <a href="%s">Getting Started</a> information.', WPS2_TEXT_DOMAIN), 'admin.php?page=wps_pro'); ?></div>
		<div class="description"><br /><strong><? _e('It is highly recommended that you make your forum slug match your forum page slug.', WPS2_TEXT_DOMAIN); ?></strong></div>
	</div> 

	<?php endif;
}

add_action("wps_forum_edit_form_fields", 'wps_taxonomy_metadata_edit', 10, 1);
function wps_taxonomy_metadata_edit( $tag ) {
	// Only allow users with capability to publish content
	if ( current_user_can( 'publish_posts' ) ): ?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_public"><?php _e('Visibility', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<input name="wps_forum_public" id="wps_forum_public" type="checkbox" <?php if ( wps_get_term_meta($tag->term_id, 'wps_forum_public', true) ) echo 'CHECKED'; ?> style="width:10px" />
			<span class="description"><?php _e('Can this forum be viewed without logging in?', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_order"><?php _e('Order', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<input type="text" name="wps_forum_order" id="wps_forum_order" style="width:50px" value="<?php echo wps_get_term_meta($tag->term_id, 'wps_forum_order', true); ?>" />
			<span class="description"><?php _e('Order in which the forum is shown, in a list of forums.', WPS2_TEXT_DOMAIN); ?></span>
		</td>
	</tr> 

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="wps_forum_cat_page"><?php _e('WordPress page', WPS2_TEXT_DOMAIN); ?></label>
		</th>
		<td>
			<?php 
		  	$forum_page = wps_get_term_meta($tag->term_id, 'wps_forum_cat_page', true);
		  	echo '<select name="wps_forum_cat_page">';

			  	if (!$forum_page) echo '<option value="0">'.__('Select page...', WPS2_TEXT_DOMAIN).'</option>';
			  	$pages = get_pages(); 
			  	foreach ( $pages as $page ):
			  		$option = '<option value="' . $page->ID . '"';
			  			if ($page->ID == $forum_page) $option .= ' SELECTED';
			  			$option .= '>';
						$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
				endforeach;

			echo '</select>';
			 ?>						
			<br />
			<span class="description"><?php _e('WordPress page that has this forum\'s shortcodes on.', WPS2_TEXT_DOMAIN); ?>
			<?php if ($forum_page) { ?> [<a href="post.php?post=<?php echo $forum_page; ?>&action=edit"><?php _e('Edit', WPS2_TEXT_DOMAIN); ?></a>]<?php } ?></span><br />
			<br /><span class="description"><strong><? _e('It is highly recommended that you make your forum slug match your forum page slug.', WPS2_TEXT_DOMAIN); ?></strong></span>
		</td>
	</tr> 

	<?php 

	// Any further options?
	do_action('wps_forum_taxonomy_metadata_edit_hook', $tag);

	endif;
}


add_action("created_wps_forum", 'wps_save_taxonomy_metadata', 10, 1);
add_action("edited_wps_forum", 'wps_save_taxonomy_metadata', 10, 1);
function wps_save_taxonomy_metadata( $term_id ) {

	if ( isset($_POST['wps_forum_public']) ):
		wps_update_term_meta( $term_id, 'wps_forum_public', true );
	else:
		wps_update_term_meta( $term_id, 'wps_forum_public', false );
	endif;

	if (isset($_POST['wps_forum_cat_page']))
		wps_update_term_meta( $term_id, 'wps_forum_cat_page', $_POST['wps_forum_cat_page'] );

	if (isset($_POST['wps_forum_order'])):
		wps_update_term_meta( $term_id, 'wps_forum_order', $_POST['wps_forum_order'] );
	else:
		wps_update_term_meta( $term_id, 'wps_forum_order', 0 );
	endif;

	// Any further options to save?
	do_action('wps_forum_taxonomy_metadata_edit_roles_save_hook', $term_id, $_POST);

	// Ready for re-writing
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();

}

/* Add filter to posts screen */

if (!class_exists('wps_Tax_CTP_Filter')){
  /**
    * Tax CTP Filter Class 
    * Simple class to add custom taxonomy dropdown to a custom post type admin edit list
    * @author Ohad Raz <admin@bainternet.info>
    * @version 0.1
    */
    class wps_Tax_CTP_Filter
    {
        /**
         * __construct 
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @param array $cpt [description]
         */
        function __construct($cpt = array()){
            $this->cpt = $cpt;
            // Adding a Taxonomy Filter to Admin List for a Custom Post Type
            add_action( 'restrict_manage_posts', array($this,'my_restrict_manage_posts' ));
        }
  
        /**
         * my_restrict_manage_posts  add the slelect dropdown per taxonomy
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @return void
         */
        public function my_restrict_manage_posts() {
            // only display these taxonomy filters on desired custom post_type listings
            global $typenow;
            $types = array_keys($this->cpt);
            if (in_array($typenow, $types)) {
                // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
                $filters = $this->cpt[$typenow];
                foreach ($filters as $tax_slug) {
                    // retrieve the taxonomy object
                    $tax_obj = get_taxonomy($tax_slug);
                    $tax_name = $tax_obj->labels->name;
  
                    // output html for taxonomy dropdown filter
                    echo "<select name='".strtolower($tax_slug)."' id='".strtolower($tax_slug)."' class='postform'>";
                    echo "<option value=''>Show All $tax_name</option>";
                    $this->generate_taxonomy_options($tax_slug,0,0,(isset($_GET[strtolower($tax_slug)])? $_GET[strtolower($tax_slug)] : null));
                    echo "</select>";
                }
            }
        }
         
        /**
         * generate_taxonomy_options generate dropdown
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @param  string  $tax_slug 
         * @param  string  $parent   
         * @param  integer $level    
         * @param  string  $selected 
         * @return void            
         */
        public function generate_taxonomy_options($tax_slug, $parent = '', $level = 0,$selected = null) {
            $args = array('show_empty' => 1);
            if(!is_null($parent)) {
                $args = array('parent' => $parent);
            }
            $terms = get_terms($tax_slug,$args);
            $tab='';
            for($i=0;$i<$level;$i++){
                $tab.='--';
            }
  
            foreach ($terms as $term) {
                // output each select option line, check against the last $_GET to show the current option selected
                echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->count .')</option>';
                $this->generate_taxonomy_options($tax_slug, $term->term_id, $level+1,$selected);
            }
  
        }
    }//end class
}//end if
new wps_Tax_CTP_Filter(array('wps_forum_post' => array('wps_forum')));



?>