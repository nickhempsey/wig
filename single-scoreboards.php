<?php

// Single scoreboards

//remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_entry_header', 'wig_scoreboard_wig' );
//remove_action( 'genesis_loop', 'genesis_do_loop');

//add_action('genesis_loop', 'wig_testing');
add_action('genesis_entry_content', 'wig_single_metrics_board');

add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

function wig_scorebord_data() {
    $sheetID = get_field('sheet_sync');
    $data = get_sheet_data($sheetID);
    return $data;
}

function wig_testing() {
    $sheetID = get_field('sheet_sync');
    $data = get_sheet_data($sheetID);
    $timeMeasure = get_field('time_measurement');

    echo '<div class="container my-5 py-5">';
        echo '<h2>WIG</h2>'.get_wig($data);
        echo '<br>';
        echo '<h2>Goal Desc</h2>'.get_goal_desc($data, '2');
        echo '<br>';
        echo '<h2>Metric Desc</h2>'.get_metric_desc($data, '2');
        /*
        echo '<h2>Goal Row</h2>';
        echo '<pre>'.print_r(get_goal_row($data, '2'), true).'</pre>';

        echo '<h2>Metric Row</h2>';
        echo '<pre>'.print_r(get_metric_row($data, '2'), true).'</pre>';
        */

        echo '<h2>This Week</h2>'.get_this_week();
        echo '<h2>Last Week</h2>'.get_last_week();
        echo '<h2>This Month</h2>'.get_this_month();
        echo '<h2>Last Month</h2>'.get_last_month();


        if($timeMeasure == 'Weekly') {
            echo '<h2>Last Week Metric 1</h2>'.get_metric_value($data, '1', get_last_week());
            echo '<h2>April 5 - Metric 2</h2>'.get_metric_value($data, '2', 'apr5');
            echo '<h2>Last Week Goal 2</h2>'.get_goal_value($data, '2', get_last_week());
            echo '<h2>April 5 - Goal 1</h2>'.get_goal_value($data, '1', 'apr5');

            $apr5Goal = get_goal_value($data, '1', 'apr5');
            $apr5Metric = get_metric_value($data, '1', 'apr5');

            if(is_win($apr5Metric, $apr5Goal)) {
                echo '<h2>April 5th is a Win!</h2>';
                echo 'Goal: '.$apr5Goal.'<br>Metric: '.$apr5Metric;
            }

            if(is_loss($apr5Metric, $apr5Goal)) {
                echo '<h2>April 5th is a loss... better luck next time</h2>';
                echo 'Goal: '.$apr5Goal.'<br>Metric: '.$apr5Metric.'<br>';
            }

            echo '<div class="my-3">';
                echo '<h2>Week of the month</h2>';
                echo 'Last Week Value: '.get_week_of_month(get_last_week('M d, Y')).'<br>';
                echo'Last Week Date: '.date('M d, Y', strtotime(get_last_week('M d, Y'))).'<br>';
                echo 'This Week Value: '.get_week_of_month(get_this_week('M d, Y')).'<br>';
                echo'This Week: '.date('M d, Y', strtotime(get_this_week('M d, Y'))).'<br>';
            echo '</div>';

            echo '<div class="my-3">';
                echo '<h2>Monthly Totals</h2>';
                echo 'Last Month: '.get_month_total($data, get_last_month()).'<br>';
                echo 'This Month: '.get_month_total($data, get_this_month()).'<br>';
                echo 'April: '.get_month_total($data, 'April').'<br>';

                echo '<h2>Monthly Averages</h2>';
                echo 'Current Average: '.get_this_month_avg($data).'<br>';
                echo 'Last Month Average: '.get_last_month_avg($data).'<br>';
                echo 'April Average: '.get_month_avg($data, 'April');

            echo '</div>';
        } elseif($timeMeasure == 'Monthly') {
            echo '<h2>This Month Metric 1</h2>'.get_metric_value($data, '1', strtolower(get_this_month()));
            echo '<h2>April - Metric 2</h2>'.get_metric_value($data, '2', 'april');
            echo '<h2>Last Month Goal 2</h2>'.get_goal_value($data, '2', strtolower(get_last_month()));
            echo '<h2>April - Goal 1</h2>'.get_goal_value($data, '1', 'april');
        }

        echo '<h2>Full Array</h2>';
        echo '<pre>';
            print_r($data);
        echo '</pre>';
    echo '</div>';
}

function wig_scoreboard_wig() {
    $data = wig_scorebord_data();

    echo '<p>'.get_wig($data).'</p>';
}

function wig_single_metrics_board() {
    $metrics = get_field('metrics');
    $timeMeasure = get_field('time_measurement');
    $data = wig_scorebord_data();
    $title = get_field('metric_board_title');

    if($metrics) {

        echo '<div class="row justify-content-between align-items-stretch px-0 mt-5">';
            if($title) {
                echo '<h2 class="col-12">'.$title.'</h2>';
            }
            foreach($metrics as $m) {

                if($timeMeasure == 'Weekly') {
                    $date = $m['date'];
                    if($date == 'This Week')        { $useDate = get_this_week();  }
                    elseif($date == 'Last Week')    { $useDate = get_last_week();  }
                    elseif($date == 'This Month')   { $useDate = get_this_month(); }
                    elseif($date == 'Last Month')   { $useDate = get_last_month(); }
                } elseif($timeMeasure == 'Monthly') {
                    $date = $m['date_monthly'];
                    if($date == 'This Month')       { $useDate = strtolower(get_this_month()); }
                    elseif($date == 'Last Month')   { $useDate = strtolower(get_last_month()); }
                }

                if($m['type'] == 'Goal') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_goal($data, $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                }


                if($m['type'] == 'Metric') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_metric($data, $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append'], $m['win_operator'], $m['check_win']);
                }

                if($m['type'] == 'Total') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_total($data, $m['title'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                }

                if($m['type'] == 'Average') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_avg($data, $m['title'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                }

            }
        echo '</div>';
    }
}

//wig_single_goal($data, 'Weekly Goal', '1', get_this_week());
//wig_single_metric($data, 'Weekly Data', '1', get_this_week());
genesis();
