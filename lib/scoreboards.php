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

function wig_data_test() {
    $data = get_sheet_data(1);
    if($data) {
        return true;
    } else {
        return false;
    }
}

function wig_error_output($code, $desc, $class) {
    return '<div class="alert alert-'.$class.'" role="alert">Error '.$code.': '.$desc.'.</div>';
}

add_action('genesis_before_loop', 'wig_data_error');
function wig_data_error() {
    if(wig_data_test() == false) {
        echo wig_error_output('808', 'Failure to get dope beats from Google Sheets.', 'danger');
    }
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
    if($metric > 0) {
        if($metric >= $goal && $operator == '>') {
            // if the metric is greater than goal and the win operator is greater than output true
            return true;

        } elseif($metric <= $goal && $operator == '<') {
            // if metric is less than goal and the win operator is less than output true
            return true;
        } else {
            return false;
        }
    }  else {
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



/**** Time Functions
--------------------------------------------------- */
function wig_convert_date($date) {
    $year = date('Y');
    preg_match_all('![a-z]+!', $date, $mon);
    preg_match_all('!\d+!', $date, $day);
    $date = strtotime($day[0][0].' '.$mon[0][0].', '.$year);

    return date('M j', $date);
}

function is_before_today($date) {
    $today = strtotime('today');
    $year = date('Y');
    preg_match_all('![a-z]+!', $date, $mon);
    preg_match_all('!\d+!', $date, $day);
    $date = strtotime($day[0][0].' '.$mon[0][0].', '.$year);
    //echo $day[0][0].' '.$mon[0][0].' <br>';
    //print_r($day);
    if($today > $date) {
        return true;
    }
}

function is_before_this_month($date) {
    $today = strtotime('today');
    $year = date('Y');
    $date = strtotime($date.' 01, '.$year);
    if($today > $date) {
        return true;
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

function get_month_goal($array, $month, $goalType = 'totalgoal') {
    // Type = totalgoal or avggoal
    if($array && $month && $goalType) {
        $key = array_search($month, $array['columns']['title']);

        return $array['rows'][$key][$goalType];

        //echo $goalType;
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

function get_time_measurement($metricType, $date = '', $dateMonthly = '') {
    $useDate = '';

    if(in_array($metricType, array('Total', 'Total Goal', 'Average', 'Average Goal'))) {
        if($dateMonthly == 'This Month')       { $useDate = get_this_month(); }
        elseif($dateMonthly == 'Last Month')   { $useDate = get_last_month(); }
    } else {
        if($date == 'This Week')        { $useDate = get_this_week();  }
        elseif($date == 'Last Week')    { $useDate = get_last_week();  }
        elseif($date == 'This Month')   { $useDate = get_this_month(); }
        elseif($date == 'Last Month')   { $useDate = get_last_month(); }
    }

    return $useDate;
}





/**** Array Compliling for Chart.js
--------------------------------------------------- */

function wig_parse_chart_data($array, $id, $type = 'Goal', $return = 'value', $dateType = 'Weekly', $checkWin = true, $operator = '>' ) {
    // $type = goal || metric
    // $return =  value || key || color || border
    // $dateType = Weekly || Monthly
    $row = '';
    if($type == 'Goal') {
        $row = get_goal_row($array, $id);
    } elseif($type == 'Metric') {
        $row = get_metric_row($array, $id);
    }
    //echo $dateType;
    if($row && ($type == 'Goal' || $type == 'Metric')) {
        $keys = array();
        $values = array();
        $colors = array();
        $borders = array();
        $exceptions = array('totalgoal', 'total', 'avg', 'avggoal');
        $arrayCount = 0;
        foreach($row as $key => $value) {
            if($arrayCount > 1 && !in_array($key, $exceptions)) {

                // Weekly Key/Values
                if($dateType === 'Weekly') {

                    if(is_before_today($key)) {
                        if($return == 'key' || $return == 'value') {
                            $keys[] = wig_convert_date($key);
                            $values[] = $value;
                        }
                    }
                }

                // Monthly Key/Values
                if($dateType === 'Monthly') {
                    if(is_before_this_month($key)) {
                        if($return == 'key' || $return == 'value') {
                            $keys[] = ucfirst($key);
                            $values[] = $value;
                        }
                     }
                }

                if(($return == 'color' || $return == 'border') && $type == 'Metric') {
                    if($checkWin) {
                        if(is_win($value, get_goal_value($array, $id, $key), $operator)) {
                            $colors[] = 'rgba(120,192, 118, 30)';
                            $borders[] = 'rgba(120,192, 118, 50)';
                        } else {
                            $colors[] = 'rgba(174, 63, 63, 30)';
                            $borders[] = 'rgba(174, 63, 63, 50)';
                        }
                    }
                }
            }
            $arrayCount++;
        }

        // Return keys or values
        if($return == 'value') {
            return $values;
        } elseif($return == 'key') {
            return $keys;
        } elseif($return == 'color') {
            return $colors;
        } elseif($return == 'border') {
            return $borders;
        }
    }

}

/**** Layout functions
--------------------------------------------------- */

function wig_single_card($type, $title, $val, $pre = '', $post = '', $classes, $win = '') {
    $classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';
    ?>
    <div class="single-metric-card text-center px-2 mb-4 <?= $classes; ?> <?= $type; ?>">
        <div class="p-3 bg-dark h-100 border card">
            <h3 class="title small text-center text-uppercase text-muted"><?= $title; ?></h3>
            <h4 class="value <?= $win; ?> mb-0"><strong><?= $pre.$val.$post; ?></strong></h4>
        </div>
    </div>
    <?php
}


function wig_multi_card($type, $title, $val, $pre = '', $post = '', $classes, $win = '') {
    //$classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';
    ?>
    <div class="multi-metric-value <?= $type ?> col-6 p-2 d-flex align-items-center justify-content-center">
        <div>
            <h3 class="title small text-center text-uppercase text-muted"><?= $title; ?></h3>
            <h4 class="value <?= $win; ?>"><strong><?= $pre.$val.$post; ?></strong></h4>
        </div>
    </div>
    <?php
}


function wig_value_output($type = 'single', $array, $metricType = '', $title = '', $id = '', $date = '', $classes = '', $pre = '', $post = '', $operator = '>', $checkWin = false) {

    // Construct Array
    $output = array(
        'type'          => $metricType,
        'title'         => '',
        'value'         => '',
        'pre'           => $pre,
        'post'          => $post,
        'classes'       => $classes,
        'goal_val'      => '',
        'win_loss'      => '',
    );


    // Weely Goal
    if($metricType == 'Goal') {
        $output['title'] = $title ? $title : get_goal_desc($array, $id);
        $output['value'] = get_goal_value($array, $id, $date);
        $output['win_loss'] = 'default';
    }


    // Weekly Metric
    if($metricType == 'Metric') {
        $output['title'] = $title ? $title : get_metric_desc($array, $metric);
        $output['value'] = get_metric_value($array, $id, $date);
        $output['goal_val'] = get_goal_value($array, $id, $date);
    }


    // Monthly Average Goal
    if($metricType == 'Average Goal') {
        $output['title'] = $title ? $title : $date;
        $output['value'] = get_month_goal($array, $date, 'avggoal');
    }


    // Monthly Average Metric
    if($metricType == 'Average') {
        $output['title'] = $title ? $title : $date;
        $output['value'] = get_month_avg($array, $date);
        $output['goal_val'] = get_month_goal($array, $date, 'avggoal');
    }


    // Monthly Total Goal
    if($metricType == 'Total Goal') {
        $output['title'] = $title ? $title : $date;
        $output['value'] = get_month_goal($array, $date, 'totalgoal');
    }


    // Monthly Total Metric
    if($metricType == 'Total') {
        $output['title'] = $title ? $title : $date;
        $output['value'] = get_month_total($array, $date);
        $output['goal_val'] = get_month_goal($array, $date, 'totalgoal');
    }


    // Check Wins
    if($checkWin == true && $output['win_loss'] !== 'default') {
        if(is_win($output['value'], $output['goal_val'], $operator)) {
            $output['win_loss'] = 'win';
        } else {
            $output['win_loss'] = 'loss';
        }
    }

    // echo '<pre>';
    //     print_r($output);
    // echo '</pre>';


    // Output Functions
    if($type == 'multi') {
        wig_multi_card(strtolower($metricType), $output['title'], $output['value'], $output['pre'], $output['post'], $output['classes'], $output['win_loss']);
    } elseif($type == 'single') {
        wig_single_card(strtolower($metricType), $output['title'], $output['value'], $output['pre'], $output['post'], $output['classes'], $output['win_loss']);
    } elseif($type == 'raw') {
        return $output;
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

function wig_single_metrics_board($postID = '') {
    if($postID) {
        $postID = $postID;
    } else {
        global $post;
        $postID = $post->id;
    }
    $metrics = get_field('metrics', $postID);
    //$timeMeasure = get_field('time_measurement');
    $data = wig_scorebord_data($postID);
    $title = get_field('metric_board_title', $postID);

    if($metrics) {

        echo '<div class="row justify-content-between align-items-stretch px-0 mt-5">';
            if($title) {
                echo '<h2 class="col-12">'.$title.'</h2>';
            }
            foreach($metrics as $m) {

                $useDate = get_time_measurement($m['type'], $m['date'], $m['date_monthly']);

                wig_value_output('single', $data, $m['type'], $m['title'], $m['id'], $useDate, $m['classes'], $m['prepend'], $m['append'], $m['win_operator'], $m['check_win']);

            }
        echo '</div>';
    }
}
