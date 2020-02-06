<?php

remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
//add_action( 'genesis_entry_header', 'wig_scoreboard_wig' );

add_action('genesis_entry_content', 'wig_single_view');
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );


function wig_single_view() {

    $scoreboards = get_field('board_sync');

    //echo '<pre>'.print_r($scoreboards,true).'</pre>';

    if($scoreboards) {
        echo '<div class="row flex-column flex-md-row align-items-stretch justify-content-between">';
        foreach($scoreboards as $sb) {
            $data = wig_scorebord_data($sb->ID);
            $metrics = get_field('metrics',$sb->ID);
            $timeMeasure = get_field('time_measurement',$sb->ID);

            //echo '<pre>'.print_r($data,true).'</pre>';

            if($metrics) {

                    echo '<div class="col-12 col-md-4 multi-metric-card mb-4">';
                        echo '<h4 class="w-100 bg-primary text-white mb-0 py-1 px-2"><a href="'.get_permalink($sb->ID).'">'.$sb->post_title.'</a></h4>';
                        echo '<div class="bg-dark h-100 row mx-0 align-items-md-stretch">';


                foreach($metrics as $m) {

                        $useDate = get_time_measurement($timeMeasure, $m['date'], $m['date_monthly']);

                        if($m['type'] == 'Goal') {

                            wig_single_goal('multi', $data, $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                        }


                        if($m['type'] == 'Metric') {

                            wig_single_metric('multi', $data, $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append'], $m['win_operator'], $m['check_win']);
                        }

                        if($m['type'] == 'Total') {

                            wig_single_total('multi', $data, $m['title'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                        }

                        if($m['type'] == 'Average') {

                            wig_single_avg('multi', $data, $m['title'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                        }

                }
                    echo '</div>';
                echo '</div>';
            }

        }
        echo '</div>';
    }

}


genesis();
