<?php

function wig_single_view() {

    $scoreboards = get_field('board_sync');

    //echo '<pre>'.print_r($scoreboards,true).'</pre>';

    if($scoreboards && wig_data_test()) {
        echo '<div class="row flex-column flex-sm-row">';
        foreach($scoreboards as $sb) {
            $data = wig_scorebord_data($sb->ID);
            $metrics = get_field('metrics',$sb->ID);
            $timeMeasure = get_field('time_measurement',$sb->ID);
            $showTotal = 0;
            //echo '<pre>'.print_r($data,true).'</pre>';

            if($metrics) {
                foreach($metrics as $m) {
                    $show = $m['show_on_views'];
                    if($show) { $showTotal++; }
                }

                    echo '<div class="col-12 col-sm-6 col-md-4 col-xl-3 multi-metric-card mb-4 ">';
                        echo '<h4 class="titles col-12 bg-primary text-white mb-0 py-1 px-2 border-top border-left border-right"><a href="'.get_permalink($sb->ID).'" class="d-flex h-100 justify-content-between align-items-center"><span>'.$sb->post_title.'</span> <span class="icon"><i class="fal fa-sm fa-long-arrow-right"></i></span></a></h4>';
                        echo '<div class="values bg-dark row mx-0 align-items-md-stretch border-left border-right">';


                            if($showTotal == 0) {
                                echo '<p class="text-center p-3">No metrics have been selected to show on views. <a href="'.get_edit_post_link($sb->ID).'">Click here to edit scoreboard.</a></p>';
                            }

                foreach($metrics as $m) {

                        $show = $m['show_on_views'];
                        $showCount = 1;

                        if($show && $showCount <= 4) {

                            $useDate = get_time_measurement($m['type'], $m['date'], $m['date_monthly']);

                            wig_value_output('multi', $data, $m['type'], $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append'], $m['win_operator'], $m['check_win']);
                            $showCount++;
                            
                        }


                }
                if($showTotal%2 !== 0) {
                    echo '<div class="multi-metric-value blank col-6 p-2 d-flex align-items-center justify-content-center"></div>';
                }
                    echo '</div>';
                echo '</div>';
            }

        }
        echo '</div>';
    } else {
        ?>
        <div class="w-100">
            <img class="mt-3" src="https://media.giphy.com/media/Jd5YlXOVTcQtW/giphy.gif" />
            <h2 class="mt-3">So close... maybe give it a refresh?</h2>
            <p>If the problem persists, please email nick@wellspringchurch.tv</p>
        </div>
        <?php
    }
}
