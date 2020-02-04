<?php

/**
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class wp_cutting_edge_navwalker extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role=\"menu\" class=\"sub-menu\">\n";

	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'pointer' ) == 0 && $depth === 0 ) {
			//$output .= $indent . '';
		} else {

			$class_names = $value = '';


			// li attributes
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );


				if($depth === 0 && $item->title !== 'PFM / Metal Crown & Bridge')
					$class_names .= ' col-12 col-sm-6 col-md-4 col-lg-3 mb-5';

				if($depth === 0 && $item->title === 'PFM / Metal Crown & Bridge')
					$class_names .= ' col-12 col-sm-6 mb-5';
				if ( $args->has_children )
					$class_names .= '';

				if ( $args->has_children && $depth == 1 )
					$class_names .= '';

				if ( $depth !== 0 )
					$class_names = '';

				if ( in_array( 'current-menu-item', $classes ) || in_array('current_page_ancestor', $classes) )
					$class_names .= ' selected';

				$class_names = $class_names ? ' class=" ' . esc_attr( $class_names ) . ' "' : '';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';


			// Add anchor attributes
			$atts = array();
				$atts['title']  	= ! empty( $item->title )	? $item->title	: '';
				$atts['target'] 	= ! empty( $item->target )	? $item->target	: '';
				$atts['rel']    	= ! empty( $item->xfn )		? $item->xfn	: '';
				$atts['class']  	= '';
				$atts['itemscope'] 	= 'name';
				$atts['id']			= 'dropdown'.$item->ID;
				if ( !$args->has_children )
					$atts['href'] 		= ! empty( $item->url ) ? $item->url : '';

				if($args->has_children)
					$atts['class']  	= ' split-line split-line-bottom';



			// Join all the anchor attributes
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
			$attributes = '';
			if($atts) {
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}
			}


			// Join the Item and Output it
			$item_output = $args->before;

				$item_output .= '<a'. $attributes .'><span>';

					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

				$item_output .= '</span></a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			echo $fb_output;
		}
	}
}


add_filter( 'wp_nav_menu_args', 'bsg_nav_menu_args_filter' );
function bsg_nav_menu_args_filter( $args ) {

    if (in_array($args['theme_location'], array('products', 'resources', 'company')) ) {
        $args['menu_class'] = 'row align-items-start';
        $args['fallback_cb'] = 'wp_cutting_edge_navwalker::fallback';
        $args['walker'] = new wp_cutting_edge_navwalker();
		$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';

    }

    return $args;
}


add_theme_support( 'genesis-menus' ,
	array(
		//'primary' => __( 'Main Navigation', 'bsg-burbank' ),
		'products' => __( 'Product Navigation', 'bsg-burbank' ),
		'resources' => __( 'Resource Navigation', 'bsg-burbank' ),
		'company' => __( 'Company Navigation', 'bsg-burbank' ),
		'footer' => __( 'Footer Navigation', 'bsg-burbank' ),
	)
);
