<?php

class BQW_Accordion_Slider_Activation {

	protected static $instance = null;

	private function __construct() {
		// activate the plugin when a new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_blog' ) );
	}

	/*
		Return the instance of the class
	*/
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/*
		Executed when the plugin is activated
	*/
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/*
		Executed when the plugin is deactivated
	*/
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}

				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	/*
		Executed when a new blog is activated within a WPMU environment
	*/
	public function activate_new_blog( $blog_id ) {
		if ( did_action( 'wpmu_new_blog' ) !== 1 ) {
			return 1;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/*
		Get all blog id's
	*/
	private static function get_blog_ids() {
		global $wpdb;

		$sql = "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'";

		return $wpdb->get_col($sql);
	}

	/*
		Executed for a single blog when the plugin is activated
	*/
	private static function single_activate() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table_name = $prefix . 'accordionslider_accordions';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// when the slider is activated for the first time, the tables don't exist, so we need to create them
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			$create_accordions_table = "CREATE TABLE ". $prefix . "accordionslider_accordions (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				settings text NOT NULL,
				created varchar(11) NOT NULL,
				modified varchar(11) NOT NULL,
				panels_state text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_panels_table = "CREATE TABLE ". $prefix . "accordionslider_panels (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				accordion_id mediumint(9) NOT NULL,
				label varchar(100) NOT NULL,
				position mediumint(9) NOT NULL,
				visibility varchar(20) NOT NULL,
				background_source text NOT NULL,
				background_retina_source text NOT NULL,
				background_alt text NOT NULL,
				background_title text NOT NULL,
				background_width mediumint(9) NOT NULL,
				background_height mediumint(9) NOT NULL,
				opened_background_source text NOT NULL,
				opened_background_retina_source text NOT NULL,
				opened_background_alt text NOT NULL,
				opened_background_title text NOT NULL,
				opened_background_width mediumint(9) NOT NULL,
				opened_background_height mediumint(9) NOT NULL,
				background_link text NOT NULL,
				background_link_title text NOT NULL,
				html text NOT NULL,
				settings text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_layers_table = "CREATE TABLE ". $prefix . "accordionslider_layers (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				accordion_id mediumint(9) NOT NULL,
				panel_id mediumint(9) NOT NULL,
				position mediumint(9) NOT NULL,
				name text NOT NULL,
				type text NOT NULL,
				text text NOT NULL,
				heading_type varchar(100) NOT NULL,
				image_source text NOT NULL,
				image_alt text NOT NULL,
				image_link text NOT NULL,
				image_retina text NOT NULL,
				settings text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
																		   						
			dbDelta( $create_accordions_table );
			dbDelta( $create_panels_table );
			dbDelta( $create_layers_table );
		}

		$wpdb->query( "DELETE FROM " . $prefix . "options WHERE option_name LIKE '%accordion_slider_cache%'" );
	}

	/*
		Executed for a single blog when the plugin is deactivated
	*/
	private static function single_deactivate() {

	}
}