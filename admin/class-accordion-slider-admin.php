<?php

class Accordion_Slider_Admin {

	// holds a reference to the instance of the class
	protected static $instance = null;

	protected $plugin_screen_hook_suffixes = null;

	/*
		Initialize the plugin
	*/
	private function __construct() {

		$plugin = Accordion_Slider::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// load the admin CSS and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/*
		Return the instance of the class
	*/
	public static function get_instance() {
		if (self::$instance == null) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/*
		Load the admin CSS
	*/
	public function enqueue_admin_styles() {
		if ( ! isset( $this->plugin_screen_hook_suffixes ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffixes ) ) {
			wp_enqueue_style( $this->plugin_slug . '-admin-style', plugins_url( 'assets/css/accordion-slider-admin.css', __FILE__ ), array(), Accordion_Slider::VERSION );
		}
	}

	/*
		Load the admin JavaScript
	*/
	public function enqueue_admin_scripts() {
		if ( ! isset( $this->plugin_screen_hook_suffixes ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffixes ) ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/accordion-slider-admin.js', __FILE__ ), array( 'jquery' ), Accordion_Slider::VERSION );
		}
	}

	/*
		Create the admin menu
	*/
	public function add_admin_menu() {
		add_menu_page( 
			'Accordion Slider', 
			'Accordion Slider', 
			'manage_options', 
			$this->plugin_slug, 
			array( $this, 'display_page_all_accordions' )
		);

		$this->plugin_screen_hook_suffixes[] = add_submenu_page( 
			$this->plugin_slug, 
			__( 'Accordion Slider - All Accordions', $this->plugin_slug ), 
			__( 'All Accordions', $this->plugin_slug ), 
			'manage_options', 
			$this->plugin_slug, 
			array( $this, 'display_page_all_accordions' )
		);

		$this->plugin_screen_hook_suffixes[] = add_submenu_page( 
			$this->plugin_slug,
			__( 'Accordion Slider - Add New', $this->plugin_slug ), 
			__( 'Add New', $this->plugin_slug ), 
			'manage_options', 
			$this->plugin_slug . '-add-new', 
			array( $this, 'display_page_add_new' )
		);
	}

	public function display_page_all_accordions() {

	}

	public function display_page_add_new() {
		
	}
}