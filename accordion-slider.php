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
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-activation.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-accordion-slider-widget.php' );
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

register_activation_hook( __FILE__, array( 'BQW_Accordion_Slider_Activation', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BQW_Accordion_Slider_Activation', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider_Activation', 'get_instance' ) );

// register the widget
add_action( 'widgets_init', 'bqw_as_register_widget' );

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-accordion-slider-admin.php' );
	add_action( 'plugins_loaded', array( 'BQW_Accordion_Slider_Admin', 'get_instance' ) );
}

// add_action( 'accordion_slider_enqueue_scripts', 'add_my_scripts' );
// add_action( 'accordion_slider_enqueue_styles', 'add_my_style' );

// add_filter( 'accordion_slider_default_settings', 'default_settings' );
// add_filter( 'accordion_slider_default_panel_settings', 'default_panel_settings' );
// add_filter( 'accordion_slider_default_layer_settings', 'default_layer_settings' );
// add_filter( 'accordion_slider_javascript', 'javascript' );
// add_filter( 'accordion_slider_posts_query_args', 'posts_query_args', 10, 3 );
// add_filter( 'accordion_slider_classes', 'classes', 10, 2 );
// add_filter( 'accordion_slider_panel_classes', 'panel_classes', 10, 3 );
// add_filter( 'accordion_slider_layer_classes', 'layer_classes', 10, 3 );
// add_filter( 'accordion_slider_data', 'settings', 10, 2 );
// add_filter( 'accordion_slider_markup', 'markup', 10, 2 );
// add_filter( 'accordion_slider_panel_markup', 'panel_markup', 10, 3 );
// add_filter( 'accordion_slider_layer_markup', 'panel_markup', 10, 3 );
// add_filter( 'accordion_slider_posts_tags', 'posts_tags' );
// add_filter( 'accordion_slider_gallery_tags', 'gallery_tags' );
// add_filter( 'accordion_slider_flickr_tags', 'flickr_tags' );

function add_my_scripts() {
	wp_enqueue_script('test-script', 'my/test/script.js');
}

function add_my_style() {
	wp_enqueue_style('test-style', 'my/test/style.css');
}

function default_settings( $settings ) {
	return $settings;
}

function default_panel_settings( $settings ) {
	return $settings;
}

function default_layer_settings( $settings ) {
	return $settings;
}

function javascript( $code ) {
	return $code;
}

function posts_query_args( $query_args, $accordion_id, $panel_index ) {
	return $query_args;
}

function classes( $classes, $id ) {
	return $classes;
}

function panel_classes( $classes, $accordion_id, $panel_index ) {
	return $classes;
}

function layer_classes( $classes, $accordion_id, $panel_index ) {
	return $classes;
}

function settings( $settings, $id ) {
	return $settings;
}

function markup( $markup, $id ) {
	return $markup;
}

function panel_markup( $markup, $accordion_id, $panel_index ) {
	return $markup;
}

function layer_markup( $markup, $accordion_id, $panel_index ) {
	return $markup;
}

function posts_tags( $tags ) {
	$tags['my_custom'] = 'render_my_custom';
	return $tags;
}

function gallery_tags( $tags ) {
	$tags['my_custom'] = 'render_my_custom';
	return $tags;
}

function flickr_tags( $tags ) {
	$tags['my_custom'] = 'render_my_custom';
	return $tags;
}

function render_my_custom( $tag_arg, $photo ) {
	return $photo['id'];
}