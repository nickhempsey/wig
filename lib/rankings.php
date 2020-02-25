<?php

function wig_rankings() {
    $args = array(
        'post_type'     => 'scoreboards',
        'post_per_page' => -1,
        'post__not_in'  => array(26),
    );

    $scoreboards = new WP_Query($args);

    if($scoreboards->have_posts()) {
        $start    = new DateTime('2020-01-01');
        $start->modify('first day of this month');
        $now      = date('Y-m-d');
        $end      = new DateTime($now);
        $end->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);


        ?>
        <div class="rankings">
            <div class="rankings--head text-center">
                <i class="fas fa-2x fa-trophy-alt"></i>
                <h2>Rankings</h2>
            </div>

            <div class="rankings--list">
            <?php

            $rankings = array(
                array(
                    'title'     => '',
                    'link'      => '',
                    'wins'      => '',
                    'losses'    => '',
                ),
            );
            $count = 0;
            while( $scoreboards->have_posts() ) {

                $scoreboards->the_post();
                if(get_the_title($scoreboards->ID) !== 'First Time Guests') {
                    $data = wig_scorebord_data($scoreboards->ID);
                    $winParam = get_field('monthly_win_metric');
                    $winGoal = '';
                    $winOperator = get_field('win_operator') ? get_field('win_operator') : '>';

                    if($winParam == 'Average') {
                        $winParam   = 'avg';
                        $winGoal    = 'avggoal';
                    } else {
                        $winParam   = 'total';
                        $winGoal    = 'totalgoal';
                    }

                    $winCount = 0;
                    $lossCount = 0;

                    foreach ($period as $dt) {
                        $date = $dt->format('F');
                        $goal = get_month_goal($data, $date, $winGoal);
                        if($winParam == 'avg') {
                            $val = get_month_avg($data, $date);
                        } else {
                            $val = get_month_total($data, $date);
                        }

                        if(is_win($val, $goal, $winOperator)) {
                            $winCount++;
                        } else {
                            $lossCount++;
                        }


                    }

                    $rankings[$count]['title']  = get_the_title();
                    $rankings[$count]['link']   = get_permalink();
                    $rankings[$count]['wins']   = $winCount;
                    $rankings[$count]['losses'] = $lossCount;

                    $count++;
                    $winCount = 0;
                    $lossCount = 0;

                }
            }

            // Sorth the based on wins.
            $rankings = val_sort($rankings, 'wins');

            foreach($rankings as $r) {
                ?>
                <div class="row align-items-center border-bottom pb-3 mb-3 px-3 mx-0">
                    <div class="col-8">
                        <small><a href="<?= $r['link']; ?>" class="text-white"><?= $r['title']; ?></a></small>
                    </div>

                    <div class="col-2">
                        <small class="win"><?= $r['wins']; ?></small>
                    </div>

                    <div class="col-2">
                        <small class="loss"><?= $r['losses']; ?></small>
                    </div>
                </div>
                <?php
            }
            // echo '<pre>';
            //
            //     print_r($rankings);
            //
            // echo '</pre>';

            ?>
            </div>
        </div>
        <?php
    }
}
