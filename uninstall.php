<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	global $wpdb;			
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	
	if ( $blog_ids !== false ) {
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			bqw_accordion_slider_delete_all_data();
		}

		restore_current_blog();
	}
} else {
	bqw_accordion_slider_delete_all_data();
}

function bqw_accordion_slider_delete_all_data() {
	global $wpdb;
	$prefix = $wpdb->prefix;

	$accordions_table = $prefix . 'accordionslider_accordions';
	$panels_table = $prefix . 'accordionslider_panels';
	$layers_table = $prefix . 'accordionslider_layers';

	$wpdb->query( "DROP TABLE $accordions_table, $panels_table, $layers_table" );

	delete_option( 'accordion_slider_custom_css' );
	delete_option( 'accordion_slider_custom_js' );
	delete_option( 'accordion_slider_is_custom_css' );
	delete_option( 'accordion_slider_is_custom_js' );
	delete_option( 'accordion_slider_load_stylesheets' );
	delete_option( 'accordion_slider_load_custom_css_js' );
	delete_option( 'accordion_slider_load_unminified_scripts' );
	delete_option( 'accordion_slider_cache_expiry_interval' );
	delete_option( 'accordion_slider_hide_inline_info' );
	delete_option( 'accordion_slider_hide_getting_started_info' );
	delete_option( 'accordion_slider_hide_custom_css_js_warning' );
	delete_option( 'accordion_slider_hide_image_size_warning' );
	delete_option( 'accordion_slider_version' );

	delete_transient( 'accordion_slider_post_names' );
	delete_transient( 'accordion_slider_posts_data' );
	delete_transient( 'accordion_slider_update_notification_message' );
	
	$wpdb->query( "DELETE FROM " . $prefix . "options WHERE option_name LIKE '%accordion_slider_cache%'" );
}