<?php

// Single scoreboards

remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_loop', 'genesis_do_loop');
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );


genesis();
