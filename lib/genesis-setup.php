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
    $attributes['class'] = 'scrollbar';
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


/* Modify the Bootstrap Classes being applied based on the Genesis template chosen
 */


//* Remove .site-inner, .site-container, .content-sidebar, entry-content
add_filter( 'genesis_markup_site-container', '__return_null' );
add_filter( 'genesis_markup_site-inner', '__return_null' );
add_filter( 'genesis_markup_content-sidebar-wrap', '__return_null' );
add_filter( 'genesis_markup_entry-content', '__return_null' );
//Remove Entry Header
// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
// remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
// remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

//Remove Entry Footer
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );



add_filter( 'body_class', 'custom_body_class' );
function custom_body_class( $classes ) {
    //$classes[] = ' d-flex flex-column flex-md-row align-items-start align-items-md-stretch justify-content-start justify-content-md-between menu-closed sidebar-present bg-dark';

    $classes[] = ' d-flex flex-column flex-md-row justify-content-start justify-content-md-between menu-closed sidebar-present bg-dark';
    return $classes;
}


add_filter( 'genesis_attr_content', 'bsg_filter_content' );
function bsg_filter_content( $attributes ) {
    $attributes['class'] .= ' p-3 scrollbar';
    if(wig_data_test()) {
        $attributes['class'] .= ' data-loaded';
    } else {
        $attributes['class'] .= ' data-not-loaded';
    }
    return $attributes;
}

add_action('genesis_after_loop', 'wig_edit_link');
function wig_edit_link() {
    if(is_user_logged_in()) {
        $label = (
            is_singular('views') ? 'Edit View' :
            is_singular('scoreboards') ? 'Edit Scoreboard' :
            'Edit'
        );

        echo '<div class="my-5"><a href="'.get_edit_post_link().'">'.$label.'</a></div>';
    }
}


add_action('genesis_before_header', 'wig_loader');
function wig_loader() {
    ?>
    <style>
        header.site-header, main.content, aside.wig-sidebar {
            opacity: 0;
            transition: opacity 1s ease;
        }
        html.loaded header.site-header, html.loaded main.content, html.loaded aside.wig-sidebar {
            opacity: 1;
            transition: opacity 1s ease;
        }
        .wig-loader {
            position: fixed;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100000000000;
            background: #323232;
        }
        .wig-loader svg {
            height: 150px;
            width: 150px;
            animation: rotation 4s infinite linear;
        }
        html.loaded .wig-loader {
            opacity: 0;
            transition: opacity 1s ease;
            z-index: -1;
        }
        @keyframes rotation {
          0% {
            transform: rotate(0deg);
            transition-property: color;
            transition-timing-function: ease-in-out;
            color: #4893B3;
          }
          25% {
            color: #28a745;
          }
          50% {
            transform: rotate(359deg);
            color: #dc3545;
          }
          75% {
            color: #28a745;
          }
          100% {
            transform: rotate(719deg);
            color: #4893B3;
          }
        }
    </style>
    <div class="wig-loader">
        <svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="spinner-third" class="" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M478.71 364.58zm-22 6.11l-27.83-15.9a15.92 15.92 0 0 1-6.94-19.2A184 184 0 1 1 256 72c5.89 0 11.71.29 17.46.83-.74-.07-1.48-.15-2.23-.21-8.49-.69-15.23-7.31-15.23-15.83v-32a16 16 0 0 1 15.34-16C266.24 8.46 261.18 8 256 8 119 8 8 119 8 256s111 248 248 248c98 0 182.42-56.95 222.71-139.42-4.13 7.86-14.23 10.55-22 6.11z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M271.23 72.62c-8.49-.69-15.23-7.31-15.23-15.83V24.73c0-9.11 7.67-16.78 16.77-16.17C401.92 17.18 504 124.67 504 256a246 246 0 0 1-25 108.24c-4 8.17-14.37 11-22.26 6.45l-27.84-15.9c-7.41-4.23-9.83-13.35-6.2-21.07A182.53 182.53 0 0 0 440 256c0-96.49-74.27-175.63-168.77-183.38z"></path></g></svg>
    </div>
    <?php
}
