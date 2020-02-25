<?php

// Template Name: Home
//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
//add_action('genesis_entry_content', 'wig_single_view');
add_action('genesis_entry_content', 'wig_home');
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );


function wig_home() {

    ?>

    <div class="swiper-container fullwidth-swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">

                <h2 class="border-bottom pb-3 mb-3">First Time Guests</h2>

                <?php
                $args = array(
                    'post_type' => 'scoreboards',
                    'post__in' => array(26),
                    'posts_per_page' => 1,
                );
                $scoreboard = new WP_Query($args);

                if($scoreboard->have_posts()) {
                    while($scoreboard->have_posts()) {
                        $scoreboard->the_post();

                        wig_single_metrics_board($scoreboard->ID);

                        wig_chart($scoreboard->ID);

                    }
                }

                wp_reset_postdata();

                ?>
            </div>

            <div class="swiper-slide">
                <h2 class="border-bottom pb-3 mb-3">Department Overview</h2>
                <?php wig_single_view(); ?>
            </div>
        </div>

        <div class="swiper-pagination"></div>
    </div>
    <?php
}


genesis();
