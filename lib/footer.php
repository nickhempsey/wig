<?php

/*
*
*
* Structure the footer
*
*
*/
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
remove_action( 'genesis_footer', 'genesis_do_footer');
//add_action('genesis_footer', 'bsg_footer');

add_action( 'genesis_before_footer', 'wig_sidebar');

function wig_sidebar() {
    ?>

    <aside class="wig-sidebar bg-dark py-5">
        <?php wig_rankings(); ?>
    </aside>

    <?php
}
