<?php

class Accordion_Slider {

	// the current version of the plugin
	const VERSION = '1.0.0';

	// unique identifier for the plugin
	protected $plugin_slug = 'accordion-slider';

	// holds a reference to the instance of the class
	protected static $instance = null;

	/*
		Initialize the plugin
	*/
	private function __construct() {
		// load the plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// activate the plugin when a new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_blog' ) );

		// load the public CSS and JavaScript
		add_action( 'wp_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/*
		Return the plugin's slug
	*/
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
		$table_name = $prefix . 'accslider_accordions';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// when the slider is activated for the first time, the tables don't exist, so we need to create them
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			$create_accordions_table = "CREATE TABLE ". $prefix . "accslider_accordions (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				settings text NOT NULL,
				created varchar(11) NOT NULL,
				modified varchar(11) NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_panels_table = "CREATE TABLE ". $prefix . "accslider_panels (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				accordion_id mediumint(9) NOT NULL,
				label varchar(100) NOT NULL,
				position mediumint(9) NOT NULL,
				visibility varchar(20) NOT NULL,
				background text NOT NULL,
				background_retina text NOT NULL,
				background_alt text NOT NULL,
				background_title text NOT NULL,
				background_width mediumint(9) NOT NULL,
				background_height mediumint(9) NOT NULL,
				background_opened text NOT NULL,
				background_opened_retina text NOT NULL,
				background_opened_alt text NOT NULL,
				background_opened_title text NOT NULL,
				background_opened_width mediumint(9) NOT NULL,
				background_opened_height mediumint(9) NOT NULL,
				background_link text NOT NULL,
				background_link_title text NOT NULL,
				html_content text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_layers_table = "CREATE TABLE ". $prefix . "accslider_layers (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				panel_id mediumint(9) NOT NULL,
				content text NOT NULL,
				settings text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
																		   						
			dbDelta($create_accordions_table);
			dbDelta($create_panels_table);
			dbDelta($create_layers_table);
		}
	}

	/*
		Executed for a single blog when the plugin is deactivated
	*/
	private static function single_deactivate() {

	}

	/*
		Load the plugin translation file
	*/
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, $domain . '/languages/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path ( dirname ( __FILE__ ) ) ) . '/languages/' );
	}

	/*
		Load the public CSS
	*/
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-style', plugins_url( 'assets/css/accordion-slider.min.css', __FILE__ ), array(), self::VERSION );
	}

	/*
		Load the public JavaScript
	*/
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/jquery.accordionSlider.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	public function load_accordion( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accslider_accordions';
		$result = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $id", ARRAY_A );
		
		if ( $result ) {
			$result['settings'] = unserialize( $result['settings'] );

			return $result;
		} else {
			return false;	
		}
	}
}