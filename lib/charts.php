<?php

function wig_chart($postID = '') {
    if($postID) {
        $postID = $postID;
    } else {
        global $post;
        $postID = $post->id;
    }
    $metrics = get_field('metrics', $postID);
    $timeMeasure = get_field('time_measurement', $postID);
    $data = wig_scorebord_data($postID);

    if($data && $metrics) {
        //echo '<pre class="bg-dark p-3 border-radius border">';
            //echo '<h2>Full Data Array</h2>';
            //print_r($metrics);
            $dataOutput = array(
                'labels' => array(),
                'datasets' =>  array(
                    array(
                        'label' => '',
                        'data' => array(),
                        'backgroundColor' => array(),
                        'borderColor' => array(),
                        'borderWidth' => 1,
                        'pointBackgroundColor' => array(),
                        'fill' => false,
                    )
                )
            );

            $dataCount = 0;
            $metricCount = 0;
            foreach($metrics as $m) {
                if(($m['type'] == 'Goal' || $m['type'] == 'Metric') && $m['show_on_chart']) {
                    if($dataCount == 0) {
                        $dataOutput['labels'] = $labels = wig_parse_chart_data($data, $m['id'], $m['type'], 'key', $timeMeasure );
                    }

                    $operator = $m['win_operator'] ? $m['win_operator'] : '>';

                    // Dataset
                    $dataOutput['datasets'][$dataCount]['label'] = $m['title'];


                    $dataOutput['datasets'][$dataCount]['data'] = wig_parse_chart_data($data, $m['id'], $m['type'], 'value', $timeMeasure );


                    $dataOutput['datasets'][$dataCount]['fill'] = false;
                    if($m['type'] == 'Goal') {
                        $dataOutput['datasets'][$dataCount]['borderColor'] = ($m['line_color'] === '#979797') ? 'rgba(72, 147, 179, 30)' : $m['line_color'];
                        $dataOutput['datasets'][$dataCount]['pointRadius'] = 0;
                        $dataOutput['datasets'][$dataCount]['borderWidth'] = 5;
                    } elseif($m['type'] == 'Metric') {
                        $dataOutput['datasets'][$dataCount]['borderColor'] = $m['line_color'] ? $m['line_color'] : 'rgba(151, 151, 151, 151)';
                        $dataOutput['datasets'][$dataCount]['borderWidth'] = 2;


                        if($m['check_win']) {
                            $dataOutput['datasets'][$dataCount]['pointBackgroundColor'] = wig_parse_chart_data($data, $m['id'], $m['type'], 'color', $timeMeasure, $m['check_win'], $operator );
                            $dataOutput['datasets'][$dataCount]['pointBorderColor'] = wig_parse_chart_data($data, $m['id'], $m['type'], 'color', $timeMeasure, $m['check_win'], $operator );
                            $dataOutput['datasets'][$dataCount]['pointRadius'] = 5;
                        } else {
                            $dataOutput['datasets'][$dataCount]['pointBackgroundColor'] = $m['line_color'] ? $m['line_color'] : 'rgba(151, 151, 151, 151)';
                            $dataOutput['datasets'][$dataCount]['pointRadius'] = 3;
                        }

                    }
                    $dataCount++;
                }
            }

            //print_r($dataOutput);
            //print_r(json_encode($dataOutput));

        //echo '</pre>';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
    <script>
    jQuery(document).ready(function($) {
        var data, options;

        var ctx = $('#wigChart');
        var wigChart = new Chart(ctx, {
            type: 'line',
            data: <?= json_encode($dataOutput); ?>,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                }
            }
        });
    });
    </script>
    <?php
    if($timeMeasure == 'Weekly') {
        echo '<h2>Weekly Data Chart</h2>';
    } else {
        echo '<h2>Monthly Data Chart</h2>';
    }
    ?>
    <div class="chart-container bg-dark p-3 border border-radius" style="position: relative; width:100%">
        <canvas id="wigChart" height="10" width="25"></canvas>
    </div>
    <div class="clearfix mb-3">&nbsp;</div>
    <?php
    }
}
