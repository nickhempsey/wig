<?php

// replace style.css - Theme Information (no css)
// with css/style.css -  Compressed CSS for Theme
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
add_action( 'wp_enqueue_scripts', 'bsg_enqueue_css_js' );

function bsg_enqueue_css_js() {

    // Fonts
    wp_enqueue_style( 'bsg_fonts_opensans', 'https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,700,900&display=swap' );


    // Font Awesome
    wp_enqueue_script( 'bsg_fontawesome_js', 'https://kit.fontawesome.com/c4b7a60a1d.js', array(), '', true );


    // Animations
    // wp_enqueue_style( 'bsg_animate_css', get_stylesheet_directory_uri() . '/css/animate.min.css' );
    //wp_enqueue_style( 'bsg_animate_on_scroll_css', 'https://unpkg.com/aos@next/dist/aos.css' );
    //wp_enqueue_script( 'bsg_animate_on_scroll_js', 'https://unpkg.com/aos@next/dist/aos.js', array( 'jquery' ), '1.0.0', true );


    //wp_enqueue_script( 'bsg_chart_js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js', array(), '1.0.0', false );
    // Bootstrap
    wp_enqueue_script( 'bsg_popper_js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'bsg_bootstrap_js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_style( 'bsg_bootstrap_css', get_stylesheet_directory_uri() . '/css/bootstrap/bootstrap.min.css' );


    // Common Theme
    wp_enqueue_style( 'bsg_style_css', get_stylesheet_directory_uri() . '/css/style.min.css' );
    wp_enqueue_script( 'bsg_common_js', get_stylesheet_directory_uri() . '/js/common.js', array( 'jquery' ), '1.0.0', true );

}

/**
 * Remove the .no-js class from the html element via JavaScript
 *
 * This allows styles targetting browsers without JavaScript
 */
add_action( 'wp_head', 'bsg_remove_no_js_class', 1 );
function bsg_remove_no_js_class() {
    echo "<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>";
}
