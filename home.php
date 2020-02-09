<?php

// Template Name: Home
//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action('genesis_entry_content', 'wig_single_view');
genesis();
