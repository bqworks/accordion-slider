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

		add_action( 'wp_ajax_accordion_slider_get_accordion_data', array( $this, 'get_accordion_data' ) );
		add_action( 'wp_ajax_accordion_slider_update_accordion', array( $this, 'update_accordion' ) );
		add_action( 'wp_ajax_accordion_slider_delete_accordion', array( $this, 'delete_accordion' ) );
		add_action( 'wp_ajax_accordion_slider_add_panels', array( $this, 'add_panels' ) );
		add_action( 'wp_ajax_accordion_slider_load_background_image_editor', array( $this, 'load_background_image_editor' ) );
		add_action( 'wp_ajax_accordion_slider_add_breakpoint', array( $this, 'add_breakpoint' ) );
		add_action( 'wp_ajax_accordion_slider_add_breakpoint_setting', array( $this, 'add_breakpoint_setting' ) );
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
			if ( function_exists( 'wp_enqueue_media' ) ) {
		    	wp_enqueue_media();
			}

			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/accordion-slider-admin.js', __FILE__ ), array( 'jquery' ), Accordion_Slider::VERSION );

			wp_localize_script( $this->plugin_slug . '-admin-script', 'as_js_vars', array(
				'admin' => admin_url( 'admin.php' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'page' => isset( $_GET['page'] ) && ( $_GET['page'] === 'accordion-slider-new' || ( isset( $_GET['id'] ) && isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) ) ? 'single' : 'all',
				'id' => isset( $_GET['id'] ) ? $_GET['id'] : -1
			) );
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
			array( $this, 'display_accordion_page' )
		);

		$this->plugin_screen_hook_suffixes[] = add_submenu_page(
			$this->plugin_slug,
			__( 'All Accordions', $this->plugin_slug ),
			__( 'All Accordions', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_accordion_page' )
		);

		$this->plugin_screen_hook_suffixes[] = add_submenu_page(
			$this->plugin_slug,
			__( 'Add New Accordion', $this->plugin_slug ),
			__( 'Add New', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug . '-new',
			array( $this, 'display_new_accordion_page' )
		);
	}

	public function display_accordion_page() {
		if ( isset( $_GET['id'] ) && isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) {
			$accordion = $this->plugin->load_accordion( $_GET['id'] );

			if ( $accordion !== false ) {
				$accordion_id = $accordion['id'];
				$accordion_name = $accordion['name'];
				$accordion_settings = json_decode( stripslashes( $accordion['settings'] ), true );

				$panels = $this->plugin->load_panels( $accordion_id );

				include_once( 'views/accordion.php' );
			} else {
				include_once( 'views/accordions.php' );
			}
		} else {
			include_once( 'views/accordions.php' );
		}
	}

	public function display_new_accordion_page() {
		$accordion_name = 'My Accordion';

		include_once( 'views/accordion.php' );
	}

	public function create_panel( $data ) {
		$panel_image = '';

		if ( $data !== false && $data['background_source'] !== '' ) {
			$panel_image = $data['background_source'];
		}

		include( 'views/panel.php' );
	}

	public function get_accordion_data() {
		$id = $_GET['id'];

		$accordion = $this->plugin->load_accordion( $_GET['id'] );
		$panels = $this->plugin->load_panels( $_GET['id'] );

		$data = array( 'settings' => $accordion['settings'], 'panels' => $panels );

		echo json_encode( $data );

		die();
	}

	public function update_accordion() {
		global $wpdb;

		$accordion_data = json_decode( stripslashes( $_POST['data'] ), true );

		$id = intval( $accordion_data['id'] );
		$name = $accordion_data['name'];
		$settings = $accordion_data['settings'];
		$panels_data = $accordion_data['panels'];

		if ( $id === -1 ) {
			$wpdb->insert($wpdb->prefix . 'accordionslider_accordions', array( 'name' => $name, 
																				'settings' => json_encode( $settings ),
																				'created' => date( 'm-d-Y' ), 
																				'modified' => date( 'm-d-Y' ) ), 
																		array( '%s', '%s', '%s', '%s' ) );
			
			$id = $wpdb->insert_id;
		} else {
			$wpdb->update($wpdb->prefix . 'accordionslider_accordions', array('name' => $name, 
																			 'settings' => json_encode( $settings ),
																			 'modified' => date('m-d-Y')),
																	   array('id' => $id), 
																	   array('%s', '%s', '%s'), 
																	   array('%d'));
				
			$wpdb->query("DELETE FROM " . $wpdb->prefix . "accordionslider_panels WHERE accordion_id = $id");
		}

		foreach ( $panels_data as $panel_data ) {
			$panel = array('accordion_id' => $id,
							'label' => isset( $panel_data['label'] ) ? $panel_data['label'] : '',
							'position' => isset( $panel_data['position'] ) ? $panel_data['position'] : '',
							'visibility' => isset( $panel_data['visibility'] ) ? $panel_data['visibility'] : '',
							'background_source' => isset( $panel_data['background_source'] ) ? $panel_data['background_source'] : '',
							'background_retina_source' => isset( $panel_data['background_retina_source'] ) ? $panel_data['background_retina_source'] : '',
							'background_alt' => isset( $panel_data['background_alt'] ) ? $panel_data['background_alt'] : '',
							'background_title' => isset( $panel_data['background_title'] ) ? $panel_data['background_title'] : '',
							'background_width' => isset( $panel_data['background_width'] ) ? $panel_data['background_width'] : '',
							'background_height' => isset( $panel_data['background_height'] ) ? $panel_data['background_height'] : '',
							'opened_background_source' => isset( $panel_data['opened_background_source'] ) ? $panel_data['opened_background_source'] : '',
							'opened_background_retina_source' => isset( $panel_data['opened_background_retina_source'] ) ? $panel_data['opened_background_retina_source'] : '',
							'opened_background_alt' => isset( $panel_data['opened_background_alt'] ) ? $panel_data['opened_background_alt'] : '',
							'opened_background_title' => isset( $panel_data['opened_background_title'] ) ? $panel_data['opened_background_title'] : '',
							'opened_background_width' => isset( $panel_data['opened_background_width'] ) ? $panel_data['opened_background_width'] : '',
							'opened_background_height' => isset( $panel_data['opened_background_height'] ) ? $panel_data['opened_background_height'] : '',
							'background_link' => isset( $panel_data['background_link'] ) ? $panel_data['background_link'] : '',
							'background_link_title' => isset( $panel_data['background_link_title'] ) ? $panel_data['background_link_title'] : '',
							'html_content' => isset( $panel_data['content'] ) ? $panel_data['content'] : '');

			$panel_data_types = array( '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s' );

			$wpdb->insert( $wpdb->prefix . 'accordionslider_panels', $panel, $panel_data_types );
		}

		echo $id; 

		die();
	}

	public function delete_accordion() {
		global $wpdb;

		$id = intval( $_POST['id'] );

		$wpdb->query("DELETE FROM " . $wpdb->prefix . "accordionslider_panels WHERE accordion_id = $id");
		$wpdb->query("DELETE FROM " . $wpdb->prefix . "accordionslider_accordions WHERE id =$id");

		echo $id; 

		die();
	}

	public function add_panels() {
		if ( isset( $_POST['data'] ) ) {
			$data = json_decode( stripslashes( $_POST['data'] ), true );

			foreach ( $data as $element ) {
				$this->create_panel( $element );
			}
		} else {
			$this->create_panel( false );
		}

		die();
	}

	public function load_background_image_editor() {
		$data = json_decode( stripslashes( $_POST['data'] ), true );

		include( 'views/background-image-editor.php' );

		die();
	}

	public function add_breakpoint() {
		include( 'views/breakpoint.php' );

		die();
	}

	public function add_breakpoint_setting() {
		$setting_name = $_GET['data'];

		echo $this->create_breakpoint_setting( $setting_name, false );

		die();
	}

	public function create_breakpoint_setting( $name, $value ) {
		$setting = Accordion_Slider_Settings::getSettingInfo( $name );
		$setting_value = $value !== false ? $value : $setting['default_value'];
		$setting_html = '';

		if ( $setting['type'] === 'number' || $setting['type'] === 'mixed' ) {
            $setting_html = '<tr><td><label>' . $setting['label'] . '</label></td><td class="setting-cell"><input class="breakpoint-setting" type="text" name="' . $name . '" value="' . $setting_value . '" /><span class="remove-breakpoint-setting"></span></td></tr>';
        } else if ( $setting['type'] === 'boolean' ) {
            $setting_html = '<tr><td><label>' . $setting['label'] . '</label></td><td class="setting-cell"><input class="breakpoint-setting" type="checkbox" name="' . $name . '"' . ( $setting_value === true ? ' checked="checked"' : '' ) . ' /><span class="remove-breakpoint-setting"></span></td></tr>';
        } else if ( $setting['type'] === 'select' ) {
            $setting_html ='<tr><td><label>' . $setting['label'] . '</label></td><td class="setting-cell"><select class="breakpoint-setting" name="' . $name . '">';
            
            foreach ( $setting['available_values'] as $value_name => $value_label ) {
                $setting_html .= '<option value="' . $value_name . '"' . ( $setting_value == $value_name ? ' selected="selected"' : '' ) . '>' . $value_label . '</option>';
            }
            
            $setting_html .= '</select><span class="remove-breakpoint-setting"></span></td></tr>';
        }

        return $setting_html;
	}
}