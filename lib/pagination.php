<?php

add_filter( 'genesis_prev_link_text', 'bsg_genesis_prev_link_text_numeric' );
add_filter( 'genesis_next_link_text', 'bsg_genesis_next_link_text_numeric' );

function bsg_genesis_prev_link_text_numeric( $text ) {
    if ( 'numeric' === genesis_get_option( 'posts_nav' ) ) {
        return '<span aria-hidden="true">&laquo;</span>'
            . '<span class="sr-only">' . __( 'Previous Page', 'genesis' ) . '</span>';
    }
    return $text;
}

function bsg_genesis_next_link_text_numeric( $text ) {
    if ( 'numeric' === genesis_get_option( 'posts_nav' ) ) {
        return '<span class="sr-only">' . __( 'Next Page', 'genesis' ) . '</span>'
            . '<span aria-hidden="true">&raquo;</span>';
    }
    return $text;
}


remove_filter( 'genesis_attr_archive-pagination', 'genesis_attributes_pagination' );

add_filter( 'bsg-add-class', 'bsg_prev_next_or_numeric_archive_pagination', 10, 2 );

function bsg_prev_next_or_numeric_archive_pagination( $classes_array, $context ) {
    if ( 'archive-pagination' !== $context ) {
        return $classes_array;
    }

    if ( 'numeric' === genesis_get_option( 'posts_nav' ) ) {
        $classes_array[] = 'bsg-pagination-numeric';
    } else {
        $classes_array[] = 'bsg-pagination-prev-next';
    }

    return $classes_array;

}
