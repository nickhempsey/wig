<?php

/**** Utility Functions
--------------------------------------------------- */

function wig_scorebord_data($postID = '') {
    if($postID) {
        $postID = $postID;
    } else {
        global $post;
        $postID = $post->id;
    }
    $sheetID = get_field('sheet_sync', $postID);
    $data = get_sheet_data($sheetID);
    return $data;
}

function get_wig($array) {
    if($array) {
        return $array['columns']['description'][0];
    }
}

function get_metric_desc($array, $id) {
    if($array && $id) {
        $key = array_search('Metric '.$id, $array['columns']['title']);

        return $array['columns']['description'][$key];
    }
}

function get_goal_desc($array, $id) {
    if($array && $id) {
        $key = array_search('Goal '.$id, $array['columns']['title']);

        return $array['columns']['description'][$key];
    }
}

function get_goal_row($array, $id) {
    if($array && $id) {
        $key = array_search('Goal '.$id, $array['columns']['title']);

        return $array['rows'][$key];
    }
}

function get_metric_row($array, $id) {
    if($array && $id) {
        $key = array_search('Metric '.$id, $array['columns']['title']);

        return $array['rows'][$key];
    }
}

function get_this_week($format = 'Mj') {
    // returns key in format Mj
    return strtolower(date($format, strtotime('last sunday')));
}

function get_last_week($format = 'Mj') {
    // returns key in format Mj
    return strtolower(date($format, strtotime('last sunday -7 days')));
}

function get_this_month($format = 'F') {
    // returns key in format F
    return date($format, strtotime('this month'));
}

function get_last_month($format = 'F') {
    // returns key in format F
    return date($format, strtotime('last month'));
}

function get_goal_value($array, $id, $date) {
    if($array && $id && $date) {
        $row = get_goal_row($array,$id);
        return $row[$date];
    }
}

function get_metric_value($array, $id, $date) {
    if($array && $id && $date) {
        $row = get_metric_row($array,$id);
        return $row[$date];
    }
}

function is_win( $metric, $goal, $operator = '>') {

    if($metric >= $goal && $operator == '>') {
        // if the metric is greater than goal and the win operator is greater than output true
        return true;

    } elseif($metric <= $goal && $operator == '<') {
        // if metric is less than goal and the win operator is less than output true
        return true;
    } else {
        return false;
    }
}

function is_loss( $metric, $goal, $operator = '>') {

    if(!is_win($metric, $goal, $operator)) {
        return true;
    } else {
        return false;
    }

}

function get_week_of_month($date) {
    $firstOfMonth = strtotime(date("Y-m-01", strtotime($date)));

    return intval(strftime("%U", strtotime($date))) - intval(strftime("%U", $firstOfMonth));
}

function get_month_total($array, $month) {
    if($array && $month) {
        $key = array_search($month, $array['columns']['title']);

        return $array['rows'][$key]['total'];
    }
}

function get_month_avg($array, $month) {
    $total = get_month_total($array, $month);
    $week = get_week_of_month('last day of '.$month.' '.date('Y'));

    return $total / $week;
}

function get_this_month_avg($array) {
    $total = get_month_total($array, get_this_month());
    $week = get_week_of_month(get_this_week());

    return $total / $week;
}

function get_last_month_avg($array) {
    if($array) {
        $key = array_search(get_last_month(), $array['columns']['title']);

        return $array['rows'][$key]['avg'];
    }
}

function get_time_measurement($timeMeasure, $date = '', $dateMonthly = '') {
    $useDate = '';
    if($timeMeasure == 'Weekly') {
        if($date == 'This Week')        { $useDate = get_this_week();  }
        elseif($date == 'Last Week')    { $useDate = get_last_week();  }
        elseif($date == 'This Month')   { $useDate = get_this_month(); }
        elseif($date == 'Last Month')   { $useDate = get_last_month(); }
    } elseif($timeMeasure == 'Monthly') {
        if($dateMonthly == 'This Month')       { $useDate = strtolower(get_this_month()); }
        elseif($dateMonthly == 'Last Month')   { $useDate = strtolower(get_last_month()); }
    }
    return $useDate;
}



/**** Layout functions
--------------------------------------------------- */

function wig_single_card($type, $title, $val, $pre = '', $post = '', $classes, $win = '') {
    $classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';
    ?>
    <div class="single-metric-card text-center px-3 mb-4 <?= $classes; ?> <?= $type; ?>">
        <div class="p-3 bg-dark h-100">
            <h3 class="title small text-center text-uppercase text-muted"><?= $title; ?></h3>
            <h4 class="value <?= $win; ?> mb-0"><strong><?= $pre.$val.$post; ?></strong></h4>
        </div>
    </div>
    <?php
}


function wig_multi_card($type, $title, $val, $pre = '', $post = '', $classes, $win = '') {
    //$classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';
    ?>
    <div class="multi-metric-value <?= $type ?> col-6 p-2">
        <div class="title"><?= $title; ?></div>
        <div class="value <?= $win; ?>"><?= $pre.$val.$post; ?></div>
    </div>
    <?php
}


function wig_single_goal($type = 'single', $array, $title, $id, $date, $classes, $pre = '', $post = '') {

    // Defaults
    $title = $title ? $title : get_goal_desc($array, $id);
    $val = get_goal_value($array, $id, $date);
    $pre = $pre ? $pre : '';
    $post = $post ? $post : '';
    if($type == 'multi') {
        wig_multi_card('goal', $title, $val, $pre, $post, $classes);
    } else {
        wig_single_card('goal', $title, $val, $pre, $post, $classes);
    }


}



function wig_single_metric($type = 'single',$array, $title, $metric, $date, $classes, $pre = '', $post = '', $operator = '>', $checkWin = true ) {

    $title = $title ? $title : get_metric_desc($array, $metric);
    $val = get_metric_value($array, $metric, $date);
    $classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';

    $win = '';
    if($checkWin == true) {
        if(is_win($val, get_goal_value($array, $metric, $date), $operator)) {
            $win = 'win';
        } else {
            $win = 'loss';
        }
    }
    if($type == 'multi') {
        wig_multi_card('metric', $title, $val, $pre, $post, $classes, $win);
    } else {
        wig_single_card('metric', $title, $val, $pre, $post, $classes, $win);
    }

}



function wig_single_total($type = 'single', $array, $title, $date, $classes, $pre = '', $post = '') {

    $title = $title ? $title : $date;
    $val = get_month_total($array, $date);
    $classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';

    if($type == 'multi') {
        wig_multi_card('total', $title, $val, $pre, $post, $classes);
    } else {
        wig_single_card('total', $title, $val, $pre, $post, $classes);
    }

}


function wig_single_avg($type = 'single', $array, $title, $date, $classes, $pre = '', $post = '') {

    $title = $title ? $title : $date;
    $val = get_month_avg($array, $date);
    $classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';

    if($type == 'multi') {
        wig_multi_card('average', $title, $val, $pre, $post, $classes);
    } else {
        wig_single_card('average', $title, $val, $pre, $post, $classes);
    }

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

                $useDate = get_time_measurement($timeMeasure, $m['date'], $m['date_monthly']);

                if($m['type'] == 'Goal') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_goal('single', $data, $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                }


                if($m['type'] == 'Metric') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_metric('single', $data, $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append'], $m['win_operator'], $m['check_win']);
                }

                if($m['type'] == 'Total') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_total('single', $data, $m['title'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                }

                if($m['type'] == 'Average') {
                    //echo '<pre>'.print_r($m, true).'</pre>';

                    wig_single_avg('single', $data, $m['title'], $useDate, $m['classes'], $m['prepend'], $m['append']);
                }

            }
        echo '</div>';
    }
}
