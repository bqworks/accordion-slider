<?php

class Accordion_Slider_Admin {

	// holds a reference to the instance of the class
	protected static $instance = null;

	protected $plugin_screen_hook_suffixes = null;

	protected $plugin = null;

	/*
		Initialize the plugin
	*/
	private function __construct() {

		$this->plugin = Accordion_Slider::get_instance();
		$this->plugin_slug = $this->plugin->get_plugin_slug();

		// load the admin CSS and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'wp_ajax_accordion_slider_get_background_image_editor', array( $this, 'get_background_image_editor' ) );
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

			wp_localize_script( $this->plugin_slug . '-admin-script', 'as_js_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			));
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
			array( $this, 'display_all_accordions_page' )
		);

		$this->plugin_screen_hook_suffixes[] = add_submenu_page(
			$this->plugin_slug,
			__( 'Accordion Slider - All Accordions', $this->plugin_slug ),
			__( 'All Accordions', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_all_accordions_page' )
		);

		$this->plugin_screen_hook_suffixes[] = add_submenu_page(
			$this->plugin_slug,
			__( 'Accordion Slider - Add New', $this->plugin_slug ),
			__( 'Add New', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug . '-add-new',
			array( $this, 'display_add_new_page' )
		);
	}

	public function display_all_accordions_page() {
		
	}

	public function display_add_new_page() {
		include_once( 'views/add-new.php' );
	}

	public function create_panel() {
		include( 'views/panel.php' );
	}

	public function get_background_image_editor() {
		include( 'views/background-image-editor.php' );

		die();
	}
}