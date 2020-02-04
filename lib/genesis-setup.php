<?php
// Add HTML5 markup structure
add_theme_support( 'html5' );

// Remove structural Wraps
remove_theme_support( 'genesis-structural-wraps' );

/**
 * Filter genesis_attr_structural-wrap to use BS .container classes
 */
add_filter( 'genesis_attr_structural-wrap', 'bsg_attributes_structural_wrap' );
function bsg_attributes_structural_wrap( $attributes ) {
    $attributes['class'] = '';
    return $attributes;
}

/* Remove Genesis Page Templates
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/remove-genesis-page-templates
 *
 * @param array $page_templates
 * @return array
 */
function bsg_be_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_filter( 'theme_page_templates', 'bsg_be_remove_genesis_page_templates' );


/* Modify the Bootstrap Classes being applied
 * based on the Genesis template chosen
 */


//* Remove .site-inner, .site-container, .content-sidebar, entry-content
add_filter( 'genesis_markup_site-container', '__return_null' );
add_filter( 'genesis_markup_site-inner', '__return_null' );
add_filter( 'genesis_markup_content-sidebar-wrap', '__return_null' );
add_filter( 'genesis_markup_entry-content', '__return_null' );
//Remove Entry Header
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//Remove Entry Footer
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
