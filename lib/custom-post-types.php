<?php

// ADDING CUSTOM POST TYPE
add_action('init', 'all_custom_post_types');
function all_custom_post_types() {
	$types = array(
	  array(
			'the_type' 	=> 'scoreboards',
			'single' 	=> 'Scoreboard',
			'plural' 	=> 'Scoreboards',
			'icon' 		=> 'dashicons-dashboard',
			'hierarchical' => false,
		),
		array(
			'the_type' 	=> 'views',
			'single' 	=> 'View',
			'plural' 	=> 'Views',
			'icon' 		=> 'dashicons-grid-view',
			'hierarchical' => false,
		),

  );
  if($types) {
   	foreach ($types as $type) {
   		$the_type = $type['the_type'];
  	  $single = $type['single'];
  	  $plural = $type['plural'];
  	  $icon = $type['icon'];
   		$labels = array(
  	    'name' => _x($plural, 'post type general name'),
  	    'singular_name' => _x($single, 'post type singular name'),
  	    'add_new' => _x('Add New', $single),
  	    'add_new_item' => __('Add New '. $single),
  	    'edit_item' => __('Edit '.$single),
  	    'new_item' => __('New '.$single),
  	    'view_item' => __('View '.$single),
  	    'search_items' => __('Search '.$plural),
  	    'not_found' =>  __('No '.$plural.' found'),
  	    'not_found_in_trash' => __('No '.$plural.' found in Trash'),
  	    'parent_item_colon' => ''
  	  );
  		$args = array(
  	    'labels' => $labels,
  	    'public' => true,
  	    'publicly_queryable' => true,
  	    'show_ui' => true,
  	    'query_var' => true,
  	    'capability_type' => 'post',
  	    'hierarchical' => $type['hierarchical'],
  	    'has_archive' => true,
  	    'menu_position' => 5,
    		'can_export' => true,
    		'menu_icon' => $icon,
    		//'taxonomies'  => array( 'category' ),
    		'supports' => array( 'title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes', 'genesis-layouts', 'revisions' ),
    		'rewrite' => array( 'slug' => $the_type, 'with_front' => true ),
  	  );
  		  register_post_type($the_type, $args);
   	}
  }
}


function be_register_taxonomies() {
	$taxonomies = array(
		array(
			'slug'         	=>		'product-categories',
			'single_name'  	=>		'Product Category',
			'plural_name'  	=>		'Product Categories',
			'post_type'    	=>		'products',
			'rewrite'      	=>		array( 'slug' => 'product/category' ),
		),
	);
	foreach( $taxonomies as $taxonomy ) {
		$labels = array(
			'name' => $taxonomy['plural_name'],
			'singular_name' => $taxonomy['single_name'],
			'search_items' =>  'Search ' . $taxonomy['plural_name'],
			'all_items' => 'All ' . $taxonomy['plural_name'],
			'parent_item' => 'Parent ' . $taxonomy['single_name'],
			'parent_item_colon' => 'Parent ' . $taxonomy['single_name'] . ':',
			'edit_item' => 'Edit ' . $taxonomy['single_name'],
			'update_item' => 'Update ' . $taxonomy['single_name'],
			'add_new_item' => 'Add New ' . $taxonomy['single_name'],
			'new_item_name' => 'New ' . $taxonomy['single_name'] . ' Name',
			'menu_name' => $taxonomy['plural_name']
		);

		$rewrite = isset( $taxonomy['rewrite'] ) ? $taxonomy['rewrite'] : array( 'slug' => $taxonomy['slug'] );
		$hierarchical = isset( $taxonomy['hierarchical'] ) ? $taxonomy['hierarchical'] : true;

		register_taxonomy( $taxonomy['slug'], $taxonomy['post_type'], array(
			'hierarchical' => $hierarchical,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => $rewrite,
		));
	}

}
//add_action( 'init', 'be_register_taxonomies' );

add_action('admin_head', 'hide_posts_pages');

function hide_posts_pages() {
    global $current_user;
    get_currentuserinfo();
    If($current_user->user_login != 'admin') {
        ?>
        <style>
           #menu-posts, #menu-comments, #menu-media{
                display:none !important;
           }
        </style>
        <?php
    }
}
