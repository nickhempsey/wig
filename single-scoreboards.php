<?php

// Single scoreboards


remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_entry_header', 'wig_scoreboard_wig' );
//add_action('genesis_loop', 'wig_testing');
add_action('genesis_entry_content', 'wig_single_metrics_board');
add_action('genesis_entry_content', 'wig_chart');
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );



genesis();
