<?php

/*
	Plugin Name: Accordion Slider
	Plugin URI: http://bqworks.net/accordion-slider/
	Description: Responsive and Touch-enabled WordPress accordion
	Version: 1.0
	Author: bqworks
	Author URI: http://bqworks.com
*/

include('ChromePhp.php');

// if the file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider-widget.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider.php' );

register_activation_hook( __FILE__, array( 'Accordion_Slider', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Accordion_Slider', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Accordion_Slider', 'get_instance' ) );

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-accordion-slider-admin.php' );
	add_action( 'plugins_loaded', array( 'Accordion_Slider_Admin', 'get_instance' ) );
}