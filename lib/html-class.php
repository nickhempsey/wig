<?php
add_filter( 'language_attributes', 'add_no_js_class_to_html_tag', 10, 2 );
/**
 * Add 'no-js' class to <html> tag in order to work with Modernizr.
 *
 * (Modernizr will change class to 'js' if JavaScript is supported).
 *
 * @since 1.0.0
 *
 * @param string $output A space-separated list of language attributes.
 * @param string $doctype The type of html document (xhtml|html).
 *
 * @return string $output A space-separated list of language attributes.
 */
function add_no_js_class_to_html_tag( $output, $doctype ) {
	if ( 'html' !== $doctype ) {
		return $output;
	}
	$output .= ' class="wig-2020"';
	return $output;
}
