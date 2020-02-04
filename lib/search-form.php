<?php

// search-form same behavior as genesis with additional classes
// for bootstrap styling

add_filter( 'genesis_search_form', 'bsg_search_form', 10, 4);

function bsg_search_form( $form, $search_text, $button_text, $label ) {

    $value_or_placeholder = ( get_search_query() == '' ) ? 'placeholder' : 'value';

$format = <<<EOT
<form method="get" class="search-form form-inline align-items-end" action="%s" role="search">
    <div class="form-group">
        <label class="sr-only sr-only-focusable" for="bsg-search-form">%s</label>
        <input type="search" class="form-control" type="text" id="bsg-search-form" name="s" %s="%s" placeholder="What are you looking for?">
    </div>
    <button type="submit" class="btn btn-outline-white"><i class="far fa-search"></i> <span class="sr-only">Search</span></button>
    <div class="toggleSearch d-flex justify-content-end align-self-stretch align-items-center"><i class="fas fa-times-circle fa-sm"></i></div>
</form>
EOT;

    return sprintf( $format, home_url( '/' ), esc_html( $label ), $value_or_placeholder, esc_attr( $search_text ), esc_attr( $button_text ) );
}


function bsg_search_button_text( $text ) {
    return ( 'Search everything...');
}
add_filter( 'genesis_search_text', 'bsg_search_button_text' );
