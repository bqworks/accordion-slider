<?php

/*
	Plugin Name: Accordion Slider
	Plugin URI: http://bqworks.net/accordion-slider/
	Description: Responsive and Touch-enabled WordPress accordion
	Version: 1.0
	Author: bqworks
	Author URI: http://bqworks.com
*/

require_once('/Users/david/webdev/FirePHPCore/FirePHP.class.php');
require_once('/Users/david/webdev/FirePHPCore/fb.php');
ob_start();

// if the file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flickr.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider-widget.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-accordion.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-panel.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-panel-factory.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-dynamic-panel.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-posts-panel.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-gallery-panel.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-flickr-panel.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-layer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-layer-factory.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-paragraph-layer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-heading-layer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-image-layer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-div-layer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-public-video-layer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider.php' );

register_activation_hook( __FILE__, array( 'Accordion_Slider', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Accordion_Slider', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Accordion_Slider', 'get_instance' ) );

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-accordion-slider-admin.php' );
	add_action( 'plugins_loaded', array( 'Accordion_Slider_Admin', 'get_instance' ) );
}