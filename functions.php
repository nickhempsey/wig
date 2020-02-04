<?php
/**
 * functions.php
 *
 */

/**
 * Include all php files in the /includes directory
 *
 * https://gist.github.com/theandystratton/5924570
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

function bsg_admin_error() {
    ?>
    <div class="error notice">
        <p><?php _e( 'This theme requires Advanced Custom Fields to operate properly. <a href="/wp-admin/plugins.php?s=advanced%20custom%20fields&plugin_status=all">Click here to activate it.</a>', 'bsg_template' ); ?></p>
    </div>
    <?php
}
add_action( 'genesis_setup', 'bsg_load_lib_files', 15 );

function bsg_load_lib_files() {
    if(is_plugin_active('advanced-custom-fields-pro/acf.php')) {
        foreach ( glob( dirname( __FILE__ ) . '/lib/*.php' ) as $file ) {
    	    include $file;
        }
    } else {
        add_action( 'admin_notices', 'bsg_admin_error' );
    }
}
