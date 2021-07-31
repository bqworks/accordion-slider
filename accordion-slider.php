<?php

/*
	Plugin Name: Accordion Slider
	Plugin URI:  http://bqworks.net/accordion-slider/
	Description: Responsive and touch-enabled accordion slider.
	Version:     1.8.2
	Author:      bqworks
	Author URI:  http://bqworks.net
*/

// if the file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-slider.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-accordion-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-panel-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-panel-renderer-factory.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-dynamic-panel-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-posts-panel-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-gallery-panel-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-flickr-panel-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-layer-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-layer-renderer-factory.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-paragraph-layer-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-heading-layer-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-image-layer-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-div-layer-renderer.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-video-layer-renderer.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-activation.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-widget.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-validation.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-flickr.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-hideable-gallery.php' );

register_activation_hook( __FILE__, array( 'BQW_Accordion_Slider_Activation', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BQW_Accordion_Slider_Activation', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider_Activation', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_Hideable_Gallery', 'get_instance' ) );

// register the widget
add_action( 'widgets_init', 'bqw_as_register_widget' );

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-accordion-slider-admin.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-accordion-slider-updates.php' );
	add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider_Admin', 'get_instance' ) );
	add_action( 'admin_init', array( 'BQW_Accordion_Slider_Updates', 'get_instance' ) );
}