<?php

/**** Utility Functions
--------------------------------------------------- */

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

function wig_single_goal($array, $title, $id, $date, $classes, $pre = '', $post = '') {

    // Defaults
    $title = $title ? $title : get_goal_desc($array, $id);
    $val = get_goal_value($array, $id, $date);
    $pre = $pre ? $pre : '';
    $post = $post ? $post : '';

    wig_single_card('goal', $title, $val, $pre, $post, $classes);

}



function wig_single_metric($array, $title, $metric, $date, $classes, $pre = '', $post = '', $operator = '>', $checkWin = true ) {

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

    wig_single_card('metric', $title, $val, $pre, $post, $classes, $win);

}



function wig_single_total($array, $title, $date, $classes, $pre = '', $post = '') {

    $title = $title ? $title : $date;
    $val = get_month_total($array, $date);
    $classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';

    wig_single_card('total', $title, $val, $pre, $post, $classes);

}



function wig_single_avg($array, $title, $date, $classes, $pre = '', $post = '') {

    $title = $title ? $title : $date;
    $val = get_month_avg($array, $date);
    $classes = $classes ? $classes : 'col-12 col-sm-6 col-md-3';

    wig_single_card('average', $title, $val, $pre, $post, $classes);

}
