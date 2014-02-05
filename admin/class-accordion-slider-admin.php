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

		add_action( 'wp_ajax_accordion_slider_get_accordion_data', array( $this, 'ajax_get_accordion_data' ) );
		add_action( 'wp_ajax_accordion_slider_update_accordion', array( $this, 'ajax_update_accordion' ) );
		add_action( 'wp_ajax_accordion_slider_preview_accordion', array( $this, 'ajax_preview_accordion' ) );
		add_action( 'wp_ajax_accordion_slider_delete_accordion', array( $this, 'ajax_delete_accordion' ) );
		add_action( 'wp_ajax_accordion_slider_duplicate_accordion', array( $this, 'ajax_duplicate_accordion' ) );
		add_action( 'wp_ajax_accordion_slider_add_panels', array( $this, 'ajax_add_panels' ) );
		add_action( 'wp_ajax_accordion_slider_load_background_image_editor', array( $this, 'ajax_load_background_image_editor' ) );
		add_action( 'wp_ajax_accordion_slider_load_html_editor', array( $this, 'ajax_load_html_editor' ) );
		add_action( 'wp_ajax_accordion_slider_load_layers_editor', array( $this, 'ajax_load_layers_editor' ) );
		add_action( 'wp_ajax_accordion_slider_add_layer_settings', array( $this, 'ajax_add_layer_settings' ) );
		add_action( 'wp_ajax_accordion_slider_load_settings_editor', array( $this, 'ajax_load_settings_editor' ) );
		add_action( 'wp_ajax_accordion_slider_load_content_type_settings', array( $this, 'ajax_load_content_type_settings' ) );
		add_action( 'wp_ajax_accordion_slider_add_breakpoint', array( $this, 'ajax_add_breakpoint' ) );
		add_action( 'wp_ajax_accordion_slider_add_breakpoint_setting', array( $this, 'ajax_add_breakpoint_setting' ) );
		add_action( 'wp_ajax_accordion_slider_get_taxonomies', array( $this, 'ajax_get_taxonomies' ) );
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
			wp_enqueue_style( $this->plugin_slug . '-plugin-style', plugins_url( 'accordion-slider/public/assets/css/accordion-slider.min.css' ), array(), Accordion_Slider::VERSION );
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

			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'accordion-slider/public/assets/js/jquery.accordionSlider.min.js' ), array( 'jquery' ), Accordion_Slider::VERSION );
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
			$accordion = $this->plugin->get_accordion( $_GET['id'] );

			if ( $accordion !== false ) {
				$accordion_id = $accordion['id'];
				$accordion_name = $accordion['name'];
				$accordion_settings = $accordion['settings'];
				$accordion_panels_state = $accordion['panels_state'];

				$panels = isset( $accordion['panels'] ) ? $accordion['panels'] : false;

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

	public function ajax_get_accordion_data() {
		$nonce = $_GET['nonce'];
		$id = $_GET['id'];

		if ( ! wp_verify_nonce( $nonce, 'load-accordion-data' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$accordion = $this->get_accordion_data( $_GET['id'] );

		echo json_encode( $accordion );

		die();
	}

	public function get_accordion_data( $id ) {
		return $this->plugin->get_accordion( $id );
	}

	public function ajax_update_accordion() {
		$accordion_data = json_decode( stripslashes( $_POST['data'] ), true );
		$nonce = $accordion_data['nonce'];
		$id = intval( $accordion_data['id'] );

		if ( ! wp_verify_nonce( $nonce, 'update-accordion' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		echo $this->update_accordion( $accordion_data );

		die();
	}

	public function update_accordion( $accordion_data ) {
		global $wpdb;

		$id = intval( $accordion_data['id'] );
		$panels_data = $accordion_data['panels'];

		if ( $id === -1 ) {
			$wpdb->insert($wpdb->prefix . 'accordionslider_accordions', array( 'name' => $accordion_data['name'],
																				'settings' => json_encode( $accordion_data['settings'] ),
																				'created' => date( 'm-d-Y' ),
																				'modified' => date( 'm-d-Y' ),
																				'panels_state' => json_encode( $accordion_data['panels_state'] ) ), 
																		array( '%s', '%s', '%s', '%s', '%s' ) );
			
			$id = $wpdb->insert_id;
		} else {
			$wpdb->update( $wpdb->prefix . 'accordionslider_accordions', array( 'name' => $accordion_data['name'], 
																			 	'settings' => json_encode( $accordion_data['settings'] ),
																			 	'modified' => date( 'm-d-Y' ),
																				'panels_state' => json_encode( $accordion_data['panels_state'] ) ), 
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
							'html' => isset( $panel_data['html'] ) ? $panel_data['html'] : '',
							'settings' => isset( $panel_data['settings'] ) ? json_encode( $panel_data['settings'] ) : '');

			$wpdb->insert( $wpdb->prefix . 'accordionslider_panels', $panel, array( '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s' ) );

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

		return $id;
	}

	public function ajax_preview_accordion() {
		$accordion = json_decode( stripslashes( $_POST['data'] ), true );
		$accordion_name = $accordion['name'];
		$accordion_output = $this->plugin->output_accordion( $accordion );

		include( 'views/preview-window.php' );

		die();	
	}

	public function ajax_duplicate_accordion() {
		$nonce = $_POST['nonce'];
		$original_accordion_id = $_POST['id'];

		if ( ! wp_verify_nonce( $nonce, 'duplicate-accordion' . $original_accordion_id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		if ( ( $accordion = $this->duplicate_accordion( $original_accordion_id ) ) !== false ) {
			$accordion_id = $accordion['id'];
			$accordion_name = $accordion['name'];
			$accordion_created = $accordion['created'];
			$accordion_modified = $accordion['modified'];

			include( 'views/accordions_row.php' );
		}

		die();
	}

	public function duplicate_accordion( $accordion_id ) {
		global $wpdb;

		$accordion = $this->plugin->get_accordion( $accordion_id );

		if ( $accordion !== false ) {
			$new_accordion = array();

			$new_accordion['name'] = $accordion['name'];
			$new_accordion['settings'] = json_encode( $accordion['settings'] );
			$new_accordion['created'] = date( 'm-d-Y' );
			$new_accordion['modified'] = $new_accordion['created'];

			$wpdb->insert( $wpdb->prefix . 'accordionslider_accordions', $new_accordion, array( '%s', '%s', '%s', '%s', '%s' ) );
			
			$new_accordion_id = $wpdb->insert_id;

			$new_accordion['id'] = $new_accordion_id;

			if ( isset( $accordion['panels'] ) ) {
				$panels = $accordion['panels'];
				$new_accordion['panels'] = array();

				foreach ( $panels as $panel ) {
					$new_panel = $panel;
					$new_panel['accordion_id'] = $new_accordion_id;
					$new_panel['settings'] = json_encode( $panel['settings'] );
					unset( $new_panel['id'] );
					unset( $new_panel['layers'] );

					$wpdb->insert( $wpdb->prefix . 'accordionslider_panels', $new_panel, array( '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s' ) );

					$new_panel_id = $wpdb->insert_id;

					if ( isset( $panel['layers'] ) ) {
						$layers = $panel['layers'];
						$new_panel['layers'] = array();

						foreach ( $layers as $layer ) {
							$new_layer = $layer;
							$new_layer['accordion_id'] = $new_accordion_id;
							$new_layer['panel_id'] = $new_panel_id;
							$new_layer['settings'] = json_encode( $layer['settings'] );
							unset( $new_layer['id'] );

							$wpdb->insert( $wpdb->prefix . 'accordionslider_layers', $new_layer, array( '%d', '%d', '%d', '%s', '%s', '%s' ) );

							array_push( $new_panel['layers'], $new_layer );
						}
					}

					array_push( $new_accordion['panels'], $new_panel );
				}
			}

			return $new_accordion;
		}

		return false;
	}

	public function ajax_delete_accordion() {
		$nonce = $_POST['nonce'];
		$id = intval( $_POST['id'] );

		if ( ! wp_verify_nonce( $nonce, 'delete-accordion' . $id ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		echo $this->delete_accordion( $id ); 

		die();
	}

	public function delete_accordion( $id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "accordionslider_panels WHERE accordion_id = %d", $id ) );

		$wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "accordionslider_accordions WHERE id = %d", $id ) );

		return $id;
	}

	public function create_panel( $data ) {
		$panel_image = '';

		if ( $data !== false && $data['background_source'] !== '' ) {
			$panel_image = $data['background_source'];
		}

		include( 'views/panel.php' );
	}
	
	public function ajax_add_panels() {
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

	public function ajax_load_background_image_editor() {
		$data = json_decode( stripslashes( $_POST['data'] ), true );

		include( 'views/background-image-editor.php' );

		die();
	}

	public function ajax_load_html_editor() {
		$html_content = $_POST['data'];

		include( 'views/html-editor.php' );

		die();
	}

	public function ajax_load_layers_editor() {
		$layers = json_decode( stripslashes( $_POST['data'] ), true );

		$layer_default_settings = Accordion_Slider_Settings::getLayerSettings();

		include( 'views/layers-editor.php' );

		die();
	}

	public function ajax_add_layer_settings() {
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

	public function ajax_load_settings_editor() {
		$panel_settings = json_decode( stripslashes( $_POST['data'] ), true );

		$panel_default_settings = Accordion_Slider_Settings::getPanelSettings();

		$content_type = isset( $panel_settings['content_type'] ) ? $panel_settings['content_type'] : $panel_default_settings['content_type']['default_value'];

		include( 'views/settings-editor.php' );

		die();
	}

	public function ajax_load_content_type_settings() {
		$type = $_POST['type'];

		echo $this->load_content_type_settings( $type );

		die();
	}

	public function load_content_type_settings( $type, $panel_settings = NULL ) {
		$panel_default_settings = Accordion_Slider_Settings::getPanelSettings();

		if ( $type === 'posts' ) {
			$post_names = $this->get_post_names();

			include( 'views/posts-content-settings.php' );
		} else if ( $type === 'gallery' ) {
			include( 'views/gallery-images-settings.php' );
		} else if ( $type === 'flickr' ) {
			include( 'views/flickr-settings.php' );
		}
	}

	public function get_post_names() {
		$result = array();

		$post_names_transient = get_transient( 'accordion_slider_post_names' );

		if ( $post_names_transient === false ) {
			$post_types = get_post_types( '', 'objects' );

			unset( $post_types['attachment'] );
			unset( $post_types['revision'] );
			unset( $post_types['nav_menu_item'] );

			foreach ( $post_types as $post_type ) {
				$result[ $post_type->name ] = array( 'name' => $post_type->name , 'label' => $post_type->label );
			}

			set_transient( 'accordion_slider_post_names', $result, 5 * 60 );
		} else {
			$result = $post_names_transient;
		}

		return $result;
	}

	public function ajax_get_taxonomies() {
		$post_names = json_decode( stripslashes( $_GET['post_names'] ), true );

		echo json_encode( $this->get_taxonomies_for_posts( $post_names ) );

		die();
	}

	public function get_taxonomies_for_posts( $post_names ) {
		$result = array();
		$posts_to_load = array();

		$posts_data_transient = get_transient( 'accordion_slider_posts_data' );

		if ( $posts_data_transient === false || empty( $posts_data_transient ) === true ) {
			$posts_to_load = $post_names;
			$posts_data_transient = array();
		} else {
			foreach ( $post_names as $post_name ) {
				if ( array_key_exists( $post_name, $posts_data_transient ) === true ) {
					$result[ $post_name ] = $posts_data_transient[ $post_name ];
				} else {
					array_push( $posts_to_load, $post_name );
				}
			}
		}

		foreach ( $posts_to_load as $post_name ) {
			$taxonomies = get_object_taxonomies( $post_name, 'objects' );

			$result[ $post_name ] = array();

			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_terms( $taxonomy->name, 'objects' );

				if ( ! empty( $terms ) ) {
					$result[ $post_name ][ $taxonomy->name ] = array(
						'name' => $taxonomy->name,
						'label' => $taxonomy->label,
						'terms' => array()
					);

					foreach ( $terms as $term ) {
						$result[ $post_name ][ $taxonomy->name ]['terms'][ $term->name ] = array(
							'name' => $term->name,
							'slug' => $term->slug,
							'full' => $taxonomy->name . '|' . $term->name
						);
					}
				}
			}

			$posts_data_transient[ $post_name ] = $result[ $post_name ];
		}

		set_transient( 'accordion_slider_posts_data', $posts_data_transient, 5 * 60 );
		
		return $result;
	}

	public function ajax_add_breakpoint() {
		include( 'views/breakpoint.php' );

		die();
	}

	public function ajax_add_breakpoint_setting() {
		$setting_name = $_GET['data'];

		echo $this->create_breakpoint_setting( $setting_name, false );

		die();
	}

	public function create_breakpoint_setting( $name, $value ) {
		$setting = Accordion_Slider_Settings::getSettings( $name );
		$setting_value = $value !== false ? $value : $setting['default_value'];
		$setting_html = '';
		$uid = mt_rand();

		if ( $setting['type'] === 'number' || $setting['type'] === 'mixed' ) {
            $setting_html = '<tr><td><label for="breakpoint-' . $name . '-' . $uid . '">' . $setting['label'] . '</label></td><td class="setting-cell"><input id="breakpoint-' . $name . '-' . $uid . '" class="breakpoint-setting" type="text" name="' . $name . '" value="' . esc_attr( $setting_value ) . '" /><span class="remove-breakpoint-setting"></span></td></tr>';
        } else if ( $setting['type'] === 'boolean' ) {
            $setting_html = '<tr><td><label for="breakpoint-' . $name . '-' . $uid . '">' . $setting['label'] . '</label></td><td class="setting-cell"><input id="breakpoint-' . $name . '-' . $uid . '" class="breakpoint-setting" type="checkbox" name="' . $name . '"' . ( $setting_value === true ? ' checked="checked"' : '' ) . ' /><span class="remove-breakpoint-setting"></span></td></tr>';
        } else if ( $setting['type'] === 'select' ) {
            $setting_html ='<tr><td><label for="breakpoint-' . $name . '-' . $uid . '">' . $setting['label'] . '</label></td><td class="setting-cell"><select id="breakpoint-' . $name . '-' . $uid . '" class="breakpoint-setting" name="' . $name . '">';
            
            foreach ( $setting['available_values'] as $value_name => $value_label ) {
                $setting_html .= '<option value="' . $value_name . '"' . ( $setting_value == $value_name ? ' selected="selected"' : '' ) . '>' . $value_label . '</option>';
            }
            
            $setting_html .= '</select><span class="remove-breakpoint-setting"></span></td></tr>';
        }

        return $setting_html;
	}
}