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

require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider-activation.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider-shortcode.php' );
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
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flickr.php' );

register_activation_hook( __FILE__, array( 'BQW_Accordion_Slider_Base', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BQW_Accordion_Slider_Base', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider_Activation', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider_Shortcode', 'get_instance' ) );

// register the widget
add_action( 'widgets_init', 'bqw_as_register_widget' );

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-accordion-slider-admin.php' );
	add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider_Admin', 'get_instance' ) );
}