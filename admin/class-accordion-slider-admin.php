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
		add_action( 'wp_ajax_accordion_slider_duplicate_accordion', array( $this, 'duplicate_accordion' ) );
		add_action( 'wp_ajax_accordion_slider_add_panels', array( $this, 'add_panels' ) );
		add_action( 'wp_ajax_accordion_slider_load_background_image_editor', array( $this, 'load_background_image_editor' ) );
		add_action( 'wp_ajax_accordion_slider_load_layers_editor', array( $this, 'load_layers_editor' ) );
		add_action( 'wp_ajax_accordion_slider_add_layer_settings', array( $this, 'add_layer_settings' ) );
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

			$id = isset( $_GET['id'] ) ? $_GET['id'] : -1;

			wp_localize_script( $this->plugin_slug . '-admin-script', 'as_js_vars', array(
				'admin' => admin_url( 'admin.php' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'page' => isset( $_GET['page'] ) && ( $_GET['page'] === 'accordion-slider-new' || ( isset( $_GET['id'] ) && isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) ) ? 'single' : 'all',
				'id' => $id,
				'lad_nonce' => wp_create_nonce( 'load-accordion-data' . $id ),
				'ua_nonce' => wp_create_nonce( 'update-accordion' . $id ),
				'no_image' => __( 'Click to add image', 'accordion-slider' ),
				'accordion_delete' => __( 'Are you sure you want to delete this accordion?', 'accordion-slider' ),
				'panel_delete' => __( 'Are you sure you want to delete this panel?', 'accordion-slider' ),
				'yes' => __( 'Yes', 'accordion-slider' ),
				'cancel' => __( 'Cancel', 'accordion-slider' )
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

	public function get_accordion_data() {
		$nonce = $_GET['nonce'];
		$id = $_GET['id'];

		if ( ! wp_verify_nonce( $nonce, 'load-accordion-data' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$accordion = $this->plugin->load_accordion( $_GET['id'] );
		$panels_raw = $this->plugin->load_panels( $_GET['id'] );

		$panels = array();

		if ( ! empty( $panels_raw ) ) {
			foreach ( $panels_raw as $panel_raw ) {
				$panel = array(
					'id' => $panel_raw['id'],
					'label' => $panel_raw['label'],
					'position' => $panel_raw['position'],
					'visibility' => $panel_raw['visibility'],
				);

				$panel['background'] = array();

				foreach ( $panel_raw as $key => $value ) {
					if ( strpos( $key, 'background' ) !== false ) {
						$panel['background'][$key] = $value;
					}
				}

				$layers = $this->plugin->load_layers( $panel_raw['id'] );

				$panel['layers'] = array();

				foreach ( $layers as $layer_raw ) {
					$layer = array();

					$layer['id'] = $layer_raw['id'];
					$layer['name'] = $layer_raw['name'];
					$layer['content'] = $layer_raw['content'];
					$layer['settings'] = json_decode( stripslashes( $layer_raw['settings'] ), true );

					array_push( $panel['layers'], $layer );
				}

				array_push( $panels, $panel );
			}
		}

		$data = array( 'settings' => $accordion['settings'], 'panels' => $panels );

		echo json_encode( $data );

		die();
	}

	public function update_accordion() {
		global $wpdb;

		$accordion_data = json_decode( stripslashes( $_POST['data'] ), true );

		$nonce = $accordion_data['nonce'];
		$id = intval( $accordion_data['id'] );
		$name = $accordion_data['name'];
		$settings = $accordion_data['settings'];
		$panels_data = $accordion_data['panels'];

		if ( ! wp_verify_nonce( $nonce, 'update-accordion' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		if ( $id === -1 ) {
			$wpdb->insert($wpdb->prefix . 'accordionslider_accordions', array( 'name' => $name, 
																				'settings' => json_encode( $settings ),
																				'created' => date( 'm-d-Y' ), 
																				'modified' => date( 'm-d-Y' ) ), 
																		array( '%s', '%s', '%s', '%s' ) );
			
			$id = $wpdb->insert_id;
		} else {
			$wpdb->update( $wpdb->prefix . 'accordionslider_accordions', array( 'name' => $name, 
																			 	'settings' => json_encode( $settings ),
																			 	'modified' => date( 'm-d-Y' ) ),
																	   	array( 'id' => $id ), 
																	   	array( '%s', '%s', '%s' ), 
																	   	array( '%d' ) );
				
			$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "accordionslider_panels WHERE accordion_id = %d", $id ) );

			$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "accordionslider_layers WHERE accordion_id = %d", $id ) );
		}

		foreach ( $panels_data as $panel_data ) {
			$panel = array('accordion_id' => $id,
							'label' => isset( $panel_data['label'] ) ? $panel_data['label'] : '',
							'position' => isset( $panel_data['position'] ) ? $panel_data['position'] : '',
							'visibility' => isset( $panel_data['visibility'] ) ? $panel_data['visibility'] : '',
							'background_source' => isset( $panel_data['background']['background_source'] ) ? $panel_data['background']['background_source'] : '',
							'background_retina_source' => isset( $panel_data['background']['background_retina_source'] ) ? $panel_data['background']['background_retina_source'] : '',
							'background_alt' => isset( $panel_data['background']['background_alt'] ) ? $panel_data['background']['background_alt'] : '',
							'background_title' => isset( $panel_data['background']['background_title'] ) ? $panel_data['background']['background_title'] : '',
							'background_width' => isset( $panel_data['background']['background_width'] ) ? $panel_data['background']['background_width'] : '',
							'background_height' => isset( $panel_data['background']['background_height'] ) ? $panel_data['background']['background_height'] : '',
							'opened_background_source' => isset( $panel_data['background']['opened_background_source'] ) ? $panel_data['background']['opened_background_source'] : '',
							'opened_background_retina_source' => isset( $panel_data['background']['opened_background_retina_source'] ) ? $panel_data['background']['opened_background_retina_source'] : '',
							'opened_background_alt' => isset( $panel_data['background']['opened_background_alt'] ) ? $panel_data['background']['opened_background_alt'] : '',
							'opened_background_title' => isset( $panel_data['background']['opened_background_title'] ) ? $panel_data['background']['opened_background_title'] : '',
							'opened_background_width' => isset( $panel_data['background']['opened_background_width'] ) ? $panel_data['background']['opened_background_width'] : '',
							'opened_background_height' => isset( $panel_data['background']['opened_background_height'] ) ? $panel_data['background']['opened_background_height'] : '',
							'background_link' => isset( $panel_data['background']['background_link'] ) ? $panel_data['background']['background_link'] : '',
							'background_link_title' => isset( $panel_data['background']['background_link_title'] ) ? $panel_data['background']['background_link_title'] : '',
							'html_content' => isset( $panel_data['background']['html_content'] ) ? $panel_data['background']['html_content'] : '');

			$panel_data_types = array( '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s' );

			$wpdb->insert( $wpdb->prefix . 'accordionslider_panels', $panel, $panel_data_types );

			if ( ! empty( $panel_data[ 'layers' ] ) ) {
				$panel_id = $wpdb->insert_id;
				$layers_data = $panel_data[ 'layers' ];

				foreach ( $layers_data as $layer_data ) {
					$layer = array('accordion_id' => $id,
									'panel_id' => $panel_id,
									'position' => isset( $layer_data['position'] ) ? $layer_data['position'] : 0,
									'name' => isset( $layer_data['name'] ) ? $layer_data['name'] : '',
									'content' => isset( $layer_data['content'] ) ? $layer_data['content'] : '',
									'settings' =>  isset( $layer_data['settings'] ) ? json_encode( $layer_data['settings'] ) : ''
									);

					$wpdb->insert( $wpdb->prefix . 'accordionslider_layers', $layer, array( '%d', '%d', '%d', '%s', '%s', '%s' ) );
				}
			}
		}

		echo $id;

		die();
	}

	public function duplicate_accordion() {
		global $wpdb;

		$nonce = $_POST['nonce'];
		$original_accordion_id = $_POST['id'];

		if ( ! wp_verify_nonce( $nonce, 'duplicate-accordion' . $original_accordion_id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$accordion = $this->plugin->load_accordion( $original_accordion_id );

		if ( $accordion !== false ) {
			$accordion_name = $accordion['name'];
			$accordion_created = date( 'm-d-Y' );
			$accordion_modified = $accordion_created;

			$wpdb->insert( $wpdb->prefix . 'accordionslider_accordions', array( 'name' => $accordion_name, 
																				'settings' => $accordion['settings'],
																				'created' => $accordion_created, 
																				'modified' => $accordion_modified ), 
																		array( '%s', '%s', '%s', '%s' ) );
			
			$accordion_id = $wpdb->insert_id;

			$panels = $this->plugin->load_panels( $original_accordion_id );

			if ( $panels !== false ) {
				foreach ( $panels as $panel ) {
					$new_panel = array( 'accordion_id' => $accordion_id,
										'label' => $panel['label'],
										'position' => $panel['position'],
										'visibility' => $panel['visibility'],
										'background_source' => $panel['background_source'],
										'background_retina_source' => $panel['background_retina_source'],
										'background_alt' => $panel['background_alt'],
										'background_title' => $panel['background_title'],
										'background_width' => $panel['background_width'],
										'background_height' => $panel['background_height'],
										'opened_background_source' => $panel['opened_background_source'],
										'opened_background_retina_source' => $panel['opened_background_retina_source'],
										'opened_background_alt' => $panel['opened_background_alt'],
										'opened_background_title' => $panel['opened_background_title'],
										'opened_background_width' => $panel['opened_background_width'],
										'opened_background_height' => $panel['opened_background_height'],
										'background_link' => $panel['background_link'],
										'background_link_title' => $panel['background_link_title'],
										'html_content' => $panel['html_content']);

					$new_panel_data_types = array( '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s' );

					$wpdb->insert( $wpdb->prefix . 'accordionslider_panels', $new_panel, $new_panel_data_types );

					$panel_id = $panel['id'];
					$new_panel_id = $wpdb->insert_id;

					$layers = $this->plugin->load_layers( $panel_id );

					if ( $layers !== false ) {
						foreach ( $layers as $layer ) {
							$layer = array('accordion_id' => $accordion_id,
											'panel_id' => $new_panel_id,
											'position' => $layer['position'],
											'name' => $layer['name'],
											'content' => $layer['content'],
											'settings' =>  $layer['settings']
											);

							$wpdb->insert( $wpdb->prefix . 'accordionslider_layers', $layer, array( '%d', '%d', '%d', '%s', '%s', '%s' ) );
						}
					}
				}
			}

			echo include( 'views/accordions_row.php' );
		}

		die();
	}

	public function delete_accordion() {
		global $wpdb;

		$nonce = $_POST['nonce'];
		$id = intval( $_POST['id'] );

		if ( ! wp_verify_nonce( $nonce, 'delete-accordion' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "accordionslider_panels WHERE accordion_id = %d", $id ) );

		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "accordionslider_accordions WHERE id = %d", $id ) );

		echo $id; 

		die();
	}

	public function create_panel( $data ) {
		$panel_image = '';

		if ( $data !== false && $data['background_source'] !== '' ) {
			$panel_image = $data['background_source'];
		}

		include( 'views/panel.php' );
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

	public function load_layers_editor() {
		$layers = json_decode( stripslashes( $_POST['data'] ), true );

		$layer_default_settings = Accordion_Slider_Settings::getLayerSettings();

		include( 'views/layers-editor.php' );

		die();
	}

	public function add_layer_settings() {
		$layer_id = $_POST['id'];
		$layer_settings;
		$layer_content;

		if ( isset( $_POST['settings'] ) ) {
			$layer_settings = json_decode( stripslashes( $_POST['settings'] ), true );
		}

		if ( isset( $_POST['content'] ) ) {
			$layer_content = $_POST['content'];
		}

		$layer_default_settings = Accordion_Slider_Settings::getLayerSettings();

		include( 'views/layer-settings.php' );

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
            $setting_html = '<tr><td><label>' . $setting['label'] . '</label></td><td class="setting-cell"><input class="breakpoint-setting" type="text" name="' . $name . '" value="' . esc_attr( $setting_value ) . '" /><span class="remove-breakpoint-setting"></span></td></tr>';
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