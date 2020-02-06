<?php

// Adjust structure for the header
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );
add_action('genesis_header', 'bsg_site_header_container_open', 6);
add_action('genesis_header', 'genesis_do_nav', 11 );
add_action('genesis_header', 'wig_close_header', 12 );
add_action('genesis_header', 'bsg_site_header_container_close', 14);



// Change the wrap classes for the site-header.
add_filter( 'genesis_attr_site-header', 'bsg_filter_site_header' );
function bsg_filter_site_header( $attributes ) {
    $attributes['class'] = $attributes['class'].' py-5 px-3 closed';
    // $attributes['data-aos'] = "fade-right";
    // $attributes['data-aos-offset'] = "0";
    // $attributes['data-aos-delay'] = "0";
    // $attributes['data-aos-duration'] = "500";
    // $attributes['data-aos-easing'] = "ease-in-out";
    return $attributes;
}

// Open the two container elements
function bsg_site_header_container_open() {
    genesis_markup( array(
		'html5'   => '<div %s>',
		'context' => 'site-header-container',
	) );

    genesis_markup( array(
		'html5'   => '<div %s>',
		'context' => 'site-header-row',
	) );

}

// add classes to the containers
add_filter('genesis_attr_site-header-container','bsg_filter_site_header_container');
function bsg_filter_site_header_container($attributes) {
  $attributes['class'] = $attributes['class'].' ';
  return $attributes;
}

add_filter('genesis_attr_site-header-row','bsg_filter_site_header_row');
function bsg_filter_site_header_row($attributes) {
  $attributes['class'] = $attributes['class'].' ';
  return $attributes;
}

// Close the containers
function bsg_site_header_container_close() {
    genesis_markup( array(
        'close'   => '</div>',
        'context' => 'site-header-row',
    ) );

    genesis_markup( array(
        'close'   => '</div>',
        'context' => 'site-header-container',
    ) );
}



// Add the Logo to the header
add_filter( 'genesis_seo_title', 'bsg_image_title', 10, 3 );
function bsg_image_title( $title, $inside, $wrap ) {

    $inside = sprintf(
        '<div class="logo"><a class="logo-scroll" href="%s">%s</a></div>',
            get_bloginfo('url'),
            '<img src="'.get_stylesheet_directory_uri().'/images/logo.svg" alt="'.esc_attr( get_bloginfo( 'name' ) ).'"><br><p class="text-uppercase small mt-3 mb-0">Wellspring\'s Important Goals</p>'
        );

    return $inside;

}

// Change the wrap classed for the logo.
add_filter( 'genesis_attr_title-area', 'bsg_filter_title_area' );
function bsg_filter_title_area( $attributes ) {
    $attributes['class'] = $attributes['class'].' ';
    $attributes['style'] = 'display:none;';
    return $attributes;
}


function wig_close_header() {
    ?>
    <div class="toggle-header">
        <span class="header-open"><i class="fal fa-lg fa-bars"></i></span>
        <span class="header-close"><i class="fal fa-lg fa-times-circle"></i></span>
    </div>
    <?php
}


add_filter('genesis_attr_nav-primary','bsg_filter_nav_primary');
function bsg_filter_nav_primary($attributes) {
  //$attributes['class'] = $attributes['class'].'';
  $attributes['style'] = 'display:none;';
  return $attributes;
}
