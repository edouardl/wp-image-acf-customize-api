<?php
/**
 * Plugin Name: ACF - Image options - Customize API
 * Version: 1.0
 * Plugin URI: https://github.com/edouardl/wp-image-acf-customize-api
 * Description: A simple developer plugin to synchronize options values between ACF and the WP Customize API.
 * Author: Edouard Labre
 * Author URI: http://www.edouardlabre.com/
 * License: GPL v3
 */

// Include core class
include('wiaca-sync-customize-acf.php');

/**
 * Include class only during customize register to avoid php fatal error
 *
 * @return void
 */
function wiaca_initAcfImageCustomizeApi() {
    // Our specific customize API class
    include( 'customize-image-control-acf-synch.php' );
}
add_action('customize_register', 'wiaca_initAcfImageCustomizeApi', 1 );


/**
 * Function to synchronize two options in WordPress Database to synchronize image options
 * from ACF options (which store image id) to Customize API options (wiich store image url)
 * Should be executed at init
 *
 *
 * @param  string $acf_option_name           : name of the ACF option declared (without the acf previx)
 * @param  string $customize_api_option_name : name of the image option from the WordPress Customize API
 * @return void
 */
function wiaca_synchronize_image_customize_acf( $acf_option_name, $customize_api_option_name ) {
    new wiaca_SyncAcfCustomizeImageOption( $acf_option_name, $customize_api_option_name );
}
