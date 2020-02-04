<?php

add_image_size( 'review-thumb', 77, 77, true );

function bsg_reviews_avatar($postID = '', $size = 'review-thumb', $classes = '') {
    if($postID) {
        $postid = $postID;
    } else {
        global $post;
        $postid = $post->ID;
    }

    $output = '';
    if(has_post_thumbnail( $postid )) {
        $output = get_the_post_thumbnail( $postid, $size, array('class' => $classes) );
    } else {
        $output = '<img src="'.get_stylesheet_directory_uri().'/images/review-placeholder.png" class="'.$classes.'" alt="Phillps Kaiser Review Logo" />';
    }
    return $output;

}

function bsg_reviews_star($postID = '') {
    if($postID) {
        $postid = $postID;
    } else {
        global $post;
        $postid = $post->ID;
    }

	$stars = get_field('rating', $postid);

	$output = '';

	if($stars) {
		$output .= '<div class="card__rate">';

			for($i =1.0; $i< ($stars); $i++) {
				$output .= '<i class="star full-star"></i>';
			}
			//output half start for half ratings otherwise full star
			if(fmod($stars, 1.0) !== 0.0) {
				$output .= '<i class="star half-star"></i>';
			} else {
				$output .= '<i class="star full-star"></i>';
			}

		$output .= '</div>';

	}

	return $output;
}


function bsg_reviews_card($postID = '', $aosDelay = '', $aosOnce = 'false', $classes = 'item review col-12 col-sm-6 col-md-4 py-4 py-md-0') {
    if($postID) {
        $postid = $postID;
    } else {
        global $post;
        $postid = $post->ID;
    }

    $output = '';


    // Open review container
    $output .= '<div itemprop="review" itemscope itemtype="http://schema.org/Review"  class="'.$classes.'">';

    //data-aos="fade-left" data-aos-delay="'.$aosDelay.'" data-aos-once="false"

        // Open Cloud Body
        $output .= '<div class="cloud-box px-3 py-4">';

            $output .= '<p class="py-0 m-0">'.wp_trim_words( get_content_by_id($postid), 30, '...' ).'<br><br><a href="#" data-toggle="modal" data-target="#reviewModal" data-body="'.strip_tags(htmlspecialchars(get_content_by_id($postid))).'" data-title="'.get_the_title( $postid ).'">Read Full Review</a></p>';

        // Close Cloud Body
        $output .= '</div>';

        // Grab the terms, if they exist grab the first one and return it's name.
        $terms = get_the_terms($postid, 'reviews-categories');
        $reviewCategory = ($terms && !is_wp_error( $terms )) ? $terms[0]->name : 'Website';

        $output .= '<a class="profiletag w-100" href="#">';

            // Avatar
            $output .= '<div class="prt_img">'.bsg_reviews_avatar($postid).'</div>';

            // User info
            $output .= '<div class="prt_info">';
                $output .= '<h3>'.get_the_title( $postid ).'</h3>';
                $output .= '<span>'.get_the_date( 'm/d/Y' ).' - '.$reviewCategory.'</span>';
                $output .= bsg_reviews_star($postid);
            $output .= '</div>';

        $output .= '</a>';

    $output .= '</div>';

    return $output;
}

function bsg_reviews_archive_card() {
    echo '<div class="col-12 col-md-6 col-lg-4 mb-5">';
        echo bsg_reviews_card();
    echo '</div>';
}

function bsg_reviews_archive_loop() {
    wp_reset_query();
    wp_reset_postdata();
  if ( have_posts() ) :
		do_action( 'genesis_before_while' );

        echo '<div class="row align-items-stretch" style="clear:both;">';

        $count  = 1;

    	while ( have_posts() ) : the_post();

            $i = $count++;

    		do_action( 'genesis_before_entry' );

                bsg_reviews_archive_card();

  			do_action( 'genesis_after_entry' );

		endwhile; //* end of one post

        echo '</div>';


        echo do_shortcode('[ajax_load_more container_type="div" css_classes="row align-items-stretch" theme_repeater="reviews.php" post_type="reviews" posts_per_page="12" transition_container_classes="row align-items-stretch" button_label="Load More Reviews" button_loading_label="Loading Reviews"]');

		//do_action( 'genesis_after_endwhile' );
	else : //* if no posts exist
		do_action( 'genesis_loop_else' );
	endif; //* end loop
    wp_reset_query();
    wp_reset_postdata();
}


function bsg_review_carousel() {
    $args = array(
        'post_type'         => 'reviews',
        'posts_per_page'    => -1,
        'orderby'           => 'date',
        'order'             => 'DESC',
    );
    $reviews = new WP_Query($args);
    if($reviews->have_posts()) {
    ?>
        <div class="block bl_gray py-4 py-sm-3">
            <div class="container px-4 pb-2 pb-sm-0 px-sm-0 my-md-5">
                <div class="block__title row mb-md-5 px-4 px-sm-0">
                    <h2>What Our Clients Say</h2>
                </div>

                <div class="swiper-container mb-5 pb-5">
                    <div class="swiper-wrapper mb-5">
                    <?php
                        $count = 0;
                        while($reviews->have_posts()) {
                            $reviews->the_post();
                            $delay = ($count++ * 100 + 50);
                            echo bsg_reviews_card($reviews->ID, $delay, '', 'swiper-slide review');
                        }
                    ?>
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>

        <div class="review-modal">
            <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reviewTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="reviewBody"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    wp_reset_query();
    wp_reset_postdata();
    }
}
