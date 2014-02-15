<?php

class Accordion_Slider {

	// the current version of the plugin
	const VERSION = '1.0.0';

	// unique identifier for the plugin
	protected $plugin_slug = 'accordion-slider';

	// holds a reference to the instance of the class
	protected static $instance = null;

	protected $accordion_id_counter = 1000;

	protected $scripts_to_load = [];

	protected $js_output = '';

	/*
		Initialize the plugin
	*/
	private function __construct() {
		// load the plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// register the widget
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// activate the plugin when a new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_blog' ) );

		// register public CSS and JavaScript
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

		// load public CSS and JavaScript
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
		add_action( 'wp_footer', array( $this, 'load_scripts' ) );

		add_shortcode( 'accordion_slider', array( $this, 'accordion_slider_shortcode' ) );
		add_shortcode( 'accordion_panel', array( $this, 'accordion_panel_shortcode' ) );
		add_shortcode( 'accordion_panel_element', array( $this, 'accordion_panel_element_shortcode' ) );
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

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	}

	public function register_widget() {
		register_widget( 'Accordion_Slider_Widget' );
	}

	/*
		Load the public CSS and JavaScript
	*/
	public function register_styles() {
		if ( get_option( 'accordion_slider_load_unminified_scripts' ) == true ) {
			wp_register_style( $this->plugin_slug . '-plugin-style', plugins_url( 'assets/css/accordion-slider.css', __FILE__ ), array(), self::VERSION );
		} else {
			wp_register_style( $this->plugin_slug . '-plugin-style', plugins_url( 'assets/css/accordion-slider.min.css', __FILE__ ), array(), self::VERSION );
		}

		wp_register_style( $this->plugin_slug . '-lightbox-style', plugins_url( 'assets/libs/fancybox/jquery.fancybox.css', __FILE__ ), array(), self::VERSION );
		wp_register_style( $this->plugin_slug . '-video-js-style', plugins_url( 'assets/libs/video-js/video-js.min.css', __FILE__ ), array(), self::VERSION );
	}

	public function register_scripts() {
		if ( get_option( 'accordion_slider_load_unminified_scripts' ) == true ) {
			wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/jquery.accordionSlider.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		} else {
			wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/jquery.accordionSlider.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		}
		
		wp_register_script( $this->plugin_slug . '-easing-script', plugins_url( 'assets/libs/easing/jquery.easing.1.3.min.js', __FILE__ ), false, self::VERSION );
		wp_register_script( $this->plugin_slug . '-lightbox-script', plugins_url( 'assets/libs/fancybox/jquery.fancybox.pack.js', __FILE__ ), false, self::VERSION );
		wp_register_script( $this->plugin_slug . '-video-js-script', plugins_url( 'assets/libs/video-js/video.js', __FILE__ ), false, self::VERSION );
	}

	public function add_script_to_load( $handle ) {
		if ( in_array( $handle, $this->scripts_to_load ) === false ) {
			$this->scripts_to_load[] = $handle;
		}
	}

	public function load_accordion( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accordionslider_accordions';

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	public function load_panels( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accordionslider_panels';
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE accordion_id = %d", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	public function load_layers( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accordionslider_layers';
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE panel_id = %d", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	public function get_accordion( $id ) {
		$accordion = array();
		$accordion_raw = $this->load_accordion( $id );

		if ( $accordion_raw === false ) {
			return false;
		}

		$accordion['id'] = $accordion_raw['id'];
		$accordion['name'] = $accordion_raw['name'];
		$accordion['settings'] = json_decode( stripslashes( $accordion_raw['settings'] ), true );
		$accordion['panels_state'] = json_decode( stripslashes( $accordion_raw['panels_state'] ), true );
		
		$panels_raw = $this->load_panels( $id );

		if ( $panels_raw !== false ) {
			$accordion['panels'] = array();

			foreach ( $panels_raw as $panel_raw ) {
				$panel = $panel_raw;
				$panel['settings'] = json_decode( stripslashes( $panel_raw['settings'] ), true );
				$layers_raw = $this->load_layers( $panel_raw['id'] );

				if ( $layers_raw !== false ) {
					$panel['layers'] = array();

					foreach ( $layers_raw as $layer_raw ) {
						$layer = $layer_raw;
						$layer['settings'] = json_decode( stripslashes( $layer_raw['settings'] ), true );

						array_push( $panel['layers'], $layer );
					}
				}

				array_push( $accordion['panels'], $panel );
			}
		}

		return $accordion;
	}

	public function accordion_slider_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'id' => '-1'
		), $atts ) );

		$content = do_shortcode( $content );
		$accordion = $this->get_accordion( $id );

		if ( $accordion === false ) {
			if ( empty( $content ) ) {
				return 'An accordion slider with the ID of ' . $id . ' doesn\'t exist.';
			}

			$accordion = array( 'settings' => array() );
		}

		foreach ( $atts as $key => $value ) {
			if ( $key === 'breakpoints' ) {
				$value = json_decode( stripslashes( $value ), true );
			} else if ( $value === 'true' ) {
				$value = true;
			} else if ( $value === 'false' ) {
				$value = false;
			}

			$accordion['settings'][ $key ] = $value;
		}

		// analyze the shortcode's content, if any
		if ( ! empty( $content ) ) {
			// create an array that will hold extra slides
			$panels_extra = array();
			
			// counter for the slides for which an index was not specified and will be added at the end of the other slides
			$end_counter = 1;
			
			// get all the added slides
			$panels_shortcode = do_shortcode( $content );
			$panels_shortcode = str_replace( '<br />', '', $panels_shortcode );		
			$panels_shortcode = explode( '%as_sep%', $panels_shortcode );
			
			
			// loop through all the slides added within the shortcode 
			// and add the slide to the panels_extra array
			foreach ( $panels_shortcode as $panel_shortcode ) {
				$panel_shortcode = json_decode( stripslashes( trim( $panel_shortcode ) ), true );

				if ( ! empty( $panel_shortcode ) ) {
					$index = $panel_shortcode['settings']['index'];
					
					if ( ! is_numeric( $index ) ) {
						$index .= '_' . $end_counter;
						$end_counter++;
					}
					
					$panels_extra[ $index ] = $panel_shortcode;
				}
			}
			
			// loop through all the existing slides and override the settings and/or the content
			// if it's the case
			if ( isset( $accordion['panels'] ) ) {
				foreach ( $accordion['panels'] as $index => &$panel ) {
					if ( isset( $panels_extra[ $index ] ) ) {
						$panel_extra = $panels_extra[ $index ];

						foreach ( $panel_extra as $key => $value ) {
							if ( $key === 'settings' || $key === 'layers' ) {
								$panel[ $key ] = array_merge( $panel[ $key ], $panel_extra[ $key ] );
							} else {
								$panel[ $key ] = $value;
							}
						}
						
						unset( $panels_extra[ $index ] );
					}
				}
			}

			if ( ! empty( $panels_extra ) ) {
				if ( ! isset( $accordion['panels'] ) ) {
					$accordion['panels'] = array();
				}

				foreach ( $panels_extra as $panel_end ) {
					array_push( $accordion['panels'], $panel_end );
				}
			}
		}

		if ( ! wp_style_is( $this->plugin_slug . '-plugin-style' ) ) {
			echo '<div style="width: 450px; background-color: #FFF; color: #F00; border: 1px solid #F00; padding: 10px; font-size: 14px;"><span style="font-weight: bold;">Warning: Stylesheets not loaded!</span> You are loading the accordion outside of a post or page, so you need to manually specify where to load the stylesheets (e.g., homepage, all pages). You can set that <a style="text-decoration: underline; color: #F00;" href="' . admin_url( 'admin.php?page=accordion-slider-plugin-settings' ) . '">here</a>.</div>';
		}

		return $this->output_accordion( $accordion );
	}

	public function accordion_panel_shortcode( $atts, $content = null ) {
		$panel = array( 'settings' => array( 'index' => 'end' ) );

		if ( ! empty( $atts ) ) {
			foreach ( $atts as $key => $value ) {
				if ( $key === 'posts_post_types' || $key === 'posts_taxonomies' ) {
					$value = explode( ',', $value );
				}

				$panel['settings'][ $key ] = $value;
			}
		}
		
		$panel_content = do_shortcode( $content );	
		$panel_content = str_replace( '<br />', '', $panel_content );	
		$panel_content_elements = explode( '%as_sep%', $panel_content );

		// get the content of the panel
		foreach ( $panel_content_elements as $element ) {
			$element = json_decode( stripslashes( trim( $element ) ), true );

			if ( ! empty( $element ) ) {
				foreach ( $element as $key => $value ) {
					// check if the element is a layer or a different type
					if ( $key === 'layer' ) {
						$layer = array( 'content' => $value );

						if ( isset( $element['layer_settings'] ) ) {
							$layer['settings'] = $element['layer_settings'];
						}

						if ( ! isset( $panel['layers'] ) ) {
							$panel['layers'] = array();
						}

						array_push( $panel['layers'], $layer );
					} else if ( $key !== 'layer_settings' ) {
						$panel[ $key ] = $value;
					}
				}
			}
		}

		return json_encode( $panel ) . '%as_sep%';
	}

	public function accordion_panel_element_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array( 'name' => 'none' ), $atts ) );
	
		$content = do_shortcode( $content );

		$attributes = array();

		foreach ( $atts as $key => $value ) {
			if ( $key === 'name' ) {
				$attributes[ $name ] = $content;
			} else if ( $name === 'layer' ) {
				if ( ! isset( $attributes['layer_settings'] ) ) {
					$attributes['layer_settings'] = array();
				}

				if ( $value === 'true' ) {
					$value = true;
				} else if ( $value === 'false' ) {
					$value = false;
				}

				$attributes['layer_settings'][ $key ] = $value;
			}
		}

		return json_encode( $attributes ) . '%as_sep%';
	}

	public function output_accordion( $accordion ) {
		$accordion_id = isset( $accordion['id'] ) ? $accordion['id'] : $this->accordion_id_counter--;
		$accordion_settings = $accordion['settings'];
		
		$default_settings = Accordion_Slider_Settings::getSettings();
 		$default_layer_settings = Accordion_Slider_Settings::getLayerSettings();
 		$default_panel_settings = Accordion_Slider_Settings::getPanelSettings();

		$html_output = "";
		$settings_js = "";

		$html_output .= "\r\n" . '<div id="accordion-slider-' . $accordion_id . '" class="accordion-slider">';
		$html_output .= "\r\n" . '	<div class="as-panels">';

		$is_lazy_loading = isset( $accordion_settings['lazy_loading'] ) ? $accordion_settings['lazy_loading'] : $default_settings['lazy_loading'];

		if ( isset( $accordion['panels'] ) ) {
			$panels = $accordion['panels'];

			foreach ( $panels as $panel ) {
				$panel_html = "\r\n" . '		<div class="as-panel">';

				$is_background_image = isset( $panel['background_source'] ) && $panel['background_source'] !== '' ? true : false;
				$is_opened_background_image = isset( $panel['opened_background_source'] ) && $panel['opened_background_source'] !== '' ? true : false;
				$is_background_link = isset( $panel['background_link'] ) && $panel['background_link'] !== '' ? true : false;

				$background_link = '';

				if ( $is_background_link ) {
					$background_link_href = ' href="' . esc_attr( $panel['background_link'] ) . '"';
					$background_link_title = isset( $panel['background_link_title'] ) && $panel['background_link_title'] !== '' ? ' title="' . esc_attr( $panel['background_link_title'] ) . '"' : '';

					$background_link = '<a' . $background_link_href . $background_link_title . '>';
				}

				if ( $is_background_image ) {
					$background_source = $is_lazy_loading === true ? ' src="' . plugins_url( 'assets/css/images/blank.gif', __FILE__ ) . '" data-src="' . esc_attr( $panel['background_source'] ) . '"' : ' src="' . esc_attr( $panel['background_source'] ) . '"';
					$background_alt = isset( $panel['background_alt'] ) && $panel['background_alt'] !== '' ? ' alt="' . esc_attr( $panel['background_alt'] ) . '"' : '';
					$background_title = isset( $panel['background_title'] ) && $panel['background_title'] !== '' ? ' title="' . esc_attr( $panel['background_title'] ) . '"' : '';
					$background_width = isset( $panel['background_width'] ) && $panel['background_width'] !== '' ? ' width="' . esc_attr( $panel['background_width'] ) . '"' : '';
					$background_height = isset( $panel['background_height'] ) && $panel['background_height'] !== '' ? ' height="' . esc_attr( $panel['background_height'] ) . '"' : '';
					$background_retina_source = isset( $panel['background_retina_source'] ) && $panel['background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $panel['background_retina_source'] ) . '"' : '';

					$background_image = '<img class="as-background"' . $background_source . $background_retina_source . $background_alt . $background_title . $background_width . $background_height . ' />';
				
					if ( $is_background_link === true &&  $is_opened_background_image === false ) {
						$panel_html .= "\r\n" . '			' . $background_link . "\r\n" . '				' . $background_image . "\r\n" . '			' . '</a>';
					} else {
						$panel_html .= "\r\n" . '			' . $background_image;
					}
				}

				if ( $is_opened_background_image ) {
					$opened_background_source = $is_lazy_loading === true ? ' src="' . plugins_url( 'assets/css/images/blank.gif', __FILE__ ) . '" data-src="' . esc_attr( $panel['opened_background_source'] ) . '"' : ' src="' . esc_attr( $panel['opened_background_source'] ) . '"';
					$opened_background_alt = isset( $panel['opened_background_alt'] ) && $panel['opened_background_alt'] !== '' ? ' alt="' . esc_attr( $panel['opened_background_alt'] ) . '"' : '';
					$opened_background_title = isset( $panel['opened_background_title'] ) && $panel['opened_background_title'] !== '' ? ' title="' . esc_attr( $panel['opened_background_title'] ) . '"' : '';
					$opened_background_width = isset( $panel['opened_background_width'] ) && $panel['opened_background_width'] !== '' ? ' width="' . esc_attr( $panel['opened_background_width'] ) . '"' : '';
					$opened_background_height = isset( $panel['opened_background_height'] ) && $panel['opened_background_height'] !== '' ? ' height="' . esc_attr( $panel['opened_background_height'] ) . '"' : '';
					$opened_background_retina_source = isset( $panel['opened_background_retina_source'] ) && $panel['opened_background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $panel['opened_background_retina_source'] ) . '"' : '';

					$opened_background_image = '<img class="as-background-opened"' . $opened_background_source . $opened_background_retina_source . $opened_background_alt . $opened_background_title . $opened_background_width . $opened_background_height . ' />';

					if ( $is_background_link === true ) {
						$panel_html .= "\r\n" . '			' . $background_link . "\r\n" . '				' . $opened_background_image . "\r\n" . '			' . '</a>';
					} else {
						$panel_html .= "\r\n" . '			' . $opened_background_image;
					}
				}

				if ( isset( $panel['html'] ) && $panel['html'] !== '' ) {
					$panel_html .= "\r\n" . '			' . $panel['html'];
				}

				if ( isset( $panel['layers'] ) ) {
					$layers = $panel['layers'];

					foreach ( $layers as $layer ) {
						$layer_html = '';
						$layer_classes = 'as-layer';
						$layer_attributes = '';
						$layer_content = $layer['content'];

						$layer_settings = $layer['settings'];

						if ( isset( $layer_settings['display'] ) ) {
							$layer_classes .= ' as-' . $layer_settings['display'];
							unset( $layer_settings['display'] );
						}

						if ( isset( $layer_settings['black_background'] ) && $layer_settings['black_background'] === true ) {
							$layer_classes .= ' as-black';
							unset( $layer_settings['black_background'] );
						}

						if ( isset( $layer_settings['white_background'] ) && $layer_settings['white_background'] === true ) {
							$layer_classes .= ' as-white';
							unset( $layer_settings['white_background'] );
						}

						if ( isset( $layer_settings['round_corners'] ) && $layer_settings['round_corners'] === true ) {
							$layer_classes .= ' as-rounded';
							unset( $layer_settings['round_corners'] );
						}

						if ( isset( $layer_settings['padding'] ) && $layer_settings['padding'] === true ) {
							$layer_classes .= ' as-padding';
							unset( $layer_settings['padding'] );
						}

						if ( isset( $layer_settings['custom_class'] ) && $layer_settings['custom_class'] !== '' ) {
							$layer_classes .= ' ' . sanitize_html_class( $layer_settings['custom_class'] );
						}

						foreach ( $layer_settings as $layer_settings_name => $layer_settings_value ) {
							if ( $default_layer_settings[ $layer_settings_name ]['default_value'] != $layer_settings_value ) {
								$layer_settings_name = str_replace('_', '-', $layer_settings_name);

								$layer_attributes .= ' data-' . $layer_settings_name . '="' . esc_attr( $layer_settings_value ) . '"';
							}
						}

						$panel_html .= "\r\n" . '			' . '<div class="' .  $layer_classes . '"' . $layer_attributes . '>' . esc_html( $layer_content ) . '</div>';
					}
				}

				$panel_html .= "\r\n" . '		</div>';

				$content_type = isset( $panel['settings']['content_type'] ) ? $panel['settings']['content_type'] : $default_panel_settings['content_type']['default_value'];

				if ( $content_type === 'custom' ) {
					$html_output .= $panel_html;
				} else if ( $content_type === 'posts' ) {
					$html_output .= $this->output_posts_panels( $panel_html, $panel['settings'] );
				} else if ( $content_type === 'gallery' ) {
					$html_output .= $this->output_gallery_panels( $panel_html, $panel['settings'] );
				}
			}
		}

		$html_output .= "\r\n" . '	</div>';
		$html_output .= "\r\n" . '</div>';

		foreach ( $default_settings as $setting_name => $setting ) {
			$setting_default_value = $setting['default_value'];
			$setting_value = isset( $accordion_settings[ $setting_name ] ) ? $accordion_settings[ $setting_name ] : $setting_default_value;

			if ( $setting_value != $setting_default_value ) {
				if ( $settings_js !== '' ) {
					$settings_js .= ',';
				}

				if ( is_bool( $setting_value ) ) {
					$setting_value = $setting_value === true ? 'true' : 'false';
				} else if ( is_numeric( $setting_value ) === false ) {
					$setting_value = "'" . $setting_value . "'";
				}

				$settings_js .= "\r\n" . '			' . $setting['js_name'] . ' : ' . $setting_value;
			}
		}

		if ( isset ( $accordion_settings['breakpoints'] ) ) {
			$breakpoints_js = "";

			foreach ( $accordion_settings['breakpoints'] as $breakpoint ) {
				if ( $breakpoint['breakpoint_width'] === '' ) {
					continue;
				}

				if ( $breakpoints_js !== '' ) {
					$breakpoints_js .= ',';
				}

				$breakpoints_js .= "\r\n" . '				' . $breakpoint['breakpoint_width'] . ': {';

				unset( $breakpoint['breakpoint_width'] );

				if ( ! empty( $breakpoint ) ) {
					$breakpoint_setting_js = '';

					foreach ( $breakpoint as $breakpoint_setting_name => $breakpoint_setting_value ) {
						if ( $breakpoint_setting_js !== '' ) {
							$breakpoint_setting_js .= ',';
						}

						if ( is_bool( $breakpoint_setting_value ) ) {
							$breakpoint_setting_value = $breakpoint_setting_value === true ? 'true' : 'false';
						} else if ( is_numeric( $breakpoint_setting_value ) === false ) {
							$breakpoint_setting_value = "'" . $breakpoint_setting_value . "'";
						}

						$breakpoint_setting_js .= "\r\n" . '					' . $breakpoint_setting_name . ' : ' . $breakpoint_setting_value;
					}

					$breakpoints_js .= $breakpoint_setting_js;
				}

				$breakpoints_js .= "\r\n" . '				}';
			}

			if ( $settings_js !== '' ) {
				$settings_js .= ',';
			}

			$settings_js .= "\r\n" . '			breakpoints: {' . $breakpoints_js . "\r\n" . '			}';
		}

		$this->add_script_to_load( $this->plugin_slug . '-plugin-script' );

		$this->js_output .= "\r\n" . '<script type="text/javascript">' .
							"\r\n" . '	jQuery( document ).ready(function( $ ) {' .
							"\r\n" . '		$( "#accordion-slider-' . $accordion_id . '" ).accordionSlider({' .
												$settings_js .
							"\r\n" . '		});';

		if ( isset ( $accordion['settings']['lightbox'] ) && $accordion['settings']['lightbox'] === true ) {
			$this->add_script_to_load( $this->plugin_slug . '-lightbox-script' );
			wp_enqueue_style( $this->plugin_slug . '-lightbox-style' );

			$this->js_output .= "\r\n" . '		$( \'#accordion-slider-' . $accordion_id . ' .as-panel > a\' ).on( \'click\', function( event ) {' .
								"\r\n" . '			event.preventDefault();' .
								"\r\n" . '			if ( $( this ).hasClass( \'as-swiping\' ) === false ) {' .
								"\r\n" . '				$.fancybox.open( $( \'#accordion-slider-' . $accordion_id . ' .as-panel > a\' ), { index: $( this ).parent().index() } );' .
								"\r\n" . '			}' .
								"\r\n" . '		});';
		}

		$this->js_output .= "\r\n" . '	});' .
							"\r\n" . '</script>';

		if ( isset ( $accordion['settings']['page_scroll_easing'] ) && $accordion['settings']['page_scroll_easing'] !== 'swing' ) {
			$this->add_script_to_load( $this->plugin_slug . '-easing-script' );
		}

		if ( strpos( $html_output, 'video-js' ) !== false ) {
			$this->add_script_to_load( $this->plugin_slug . '-video-js-script' );
			wp_enqueue_style( $this->plugin_slug . '-video-js-style' );
		}

		return $html_output . "\r\n";
	}

	public function output_posts_panels( $panel_html, $settings ) {
		$default_panel_settings = Accordion_Slider_Settings::getPanelSettings();

		$query_args = array();
			
		if ( isset( $settings['posts_post_types'] ) && ! empty( $settings['posts_post_types'] ) ) {
			$query_args['post_type'] = $settings['posts_post_types'];
		}
		
		if ( isset( $settings['posts_taxonomies'] ) && ! empty( $settings['posts_taxonomies'] ) ) {
			$taxonomy_terms = $settings['posts_taxonomies'];

			$tax_query = array();

			foreach ( $taxonomy_terms as $taxonomy_term_raw ) {
				$taxonomy_term = explode( '|', $taxonomy_term_raw );
				
				$tax_item['taxonomy'] = $taxonomy_term[0];
				$tax_item['terms'] = $taxonomy_term[1];
				$tax_item['field'] = 'slug';
				
				$tax_item['operator'] = isset( $settings['posts_operator'] ) ? $settings['posts_operator'] : $default_panel_settings['posts_operator']['default_value'];
				
				array_push( $tax_query, $tax_item );
			}
			
			if ( count( $taxonomy_terms ) > 1 ) {
				$tax_query['relation'] = isset( $settings['posts_relation'] ) ? $settings['posts_relation'] : $default_panel_settings['posts_relation']['default_value'];
			}

			$query_args['tax_query'] = $tax_query;
		}
		
		$query_args['posts_per_page'] = isset( $settings['posts_maximum'] ) ? $settings['posts_maximum'] : $default_panel_settings['posts_maximum']['default_value'];
		$query_args['orderby'] = isset( $settings['posts_order_by'] ) ? $settings['posts_order_by'] : $default_panel_settings['posts_order_by']['default_value'];
		$query_args['order'] = isset( $settings['posts_order'] ) ? $settings['posts_order'] : $default_panel_settings['posts_order']['default_value'];
		
		$query = new WP_Query( $query_args );

		$is_image = false;

		if ( strpos( $panel_html, '[as_image' ) ) {
			$is_image = true;
		}

		preg_match_all( '/\[as_(.*?)\]/', $panel_html, $matches, PREG_SET_ORDER );

		$panels_html = '';

		while ( $query->have_posts() ) {
			$query->the_post();

			$panel_html_copy = $panel_html;
			$featured_image_data = null;

			if ( $is_image && has_post_thumbnail() ) {
				$featured_image_id = get_post_thumbnail_id();
				$featured_image_data = get_post( $featured_image_id, ARRAY_A );
				
				$featured_image_alt = get_post_meta( $featured_image_id, '_wp_attachment_image_alt' );
				$featured_image_data['alt'] = ! empty( $featured_image_alt ) ? $featured_image_alt[0] : '';
			}

			foreach ( $matches as $match ) {
				$shortcode = $match[0];

				$delimiter_position = strpos( $match[1], '.' );
				$shortcode_arg = $delimiter_position !== false ? substr( $match[1], $delimiter_position + 1 ) : false;
				$shortcode_name = $shortcode_arg !== false ? substr( $match[1], 0, $delimiter_position ) : $match[1];

				switch( $shortcode_name ) {
					case 'image':
						if ( is_null( $featured_image_data ) ) {
							break;
						}

						$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
						$image_full = get_the_post_thumbnail( get_the_ID(), $image_size, array( 'class' => '' ) );

						$panel_html_copy = str_replace( $shortcode, $image_full, $panel_html_copy );
						break;

					case 'image_src':
						if ( is_null( $featured_image_data ) ) {
							break;
						}

						$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
						$image_src = wp_get_attachment_image_src( $featured_image_id, $image_size );

						$panel_html_copy = str_replace( $shortcode, $image_src[0], $panel_html_copy );
						break;

					case 'image_alt':
						if ( is_null( $featured_image_data ) ) {
							break;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['alt'], $panel_html_copy );
						break;

					case 'image_title':
						if ( is_null( $featured_image_data ) ) {
							break;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['post_title'], $panel_html_copy );
						break;

					case 'image_description':
						if ( is_null( $featured_image_data ) ) {
							break;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['post_content'], $panel_html_copy );
						break;

					case 'image_caption':
						if ( is_null( $featured_image_data ) ) {
							break;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['post_excerpt'], $panel_html_copy );
						break;

					case 'title':

						$panel_html_copy = str_replace( $shortcode, get_the_title(), $panel_html_copy );
						break;

					case 'link':
						$link = '<a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a>';

						$panel_html_copy = str_replace( $shortcode, $link, $panel_html_copy );
						break;

					case 'link_url':

						$panel_html_copy = str_replace( $shortcode, get_permalink( get_the_ID() ), $panel_html_copy );
						break;

					case 'date':

						$date_format = $shortcode_arg !== false ? $shortcode_arg : get_option( 'date_format' );
						$panel_html_copy = str_replace( $shortcode, get_the_date( $date_format ), $panel_html_copy );
						break;

					case 'excerpt':

						$panel_html_copy = str_replace( $shortcode, get_the_excerpt(), $panel_html_copy );
						break;

					case 'content':

						$panel_html_copy = str_replace( $shortcode, get_the_content(), $panel_html_copy );
						break;

					case 'category':

						$categories = get_the_category( get_the_ID() );
						$category = ! empty( $categories ) ? $categories[0] : '';

						$panel_html_copy = str_replace( $shortcode, $category, $panel_html_copy );
						break;

					case 'custom':

						$value = '';

						if ( $shortcode_arg !== false ) {
							$values = get_post_meta( get_the_ID(), $shortcode_arg );										
							$value = $values[0];
						}

						$panel_html_copy = str_replace( $shortcode, $value, $panel_html_copy );
						break;

					default:
						break;
				}
			}

			$panels_html .= $panel_html_copy;
		}

		wp_reset_postdata();

		return $panels_html;
	}

	public function output_gallery_panels( $panel_html, $settings ) {
		global $post;

		$post_content = $post->post_content;
		$pattern = get_shortcode_regex();

		preg_match_all( '/' . $pattern . '/s', $post_content, $gallery_matches, PREG_SET_ORDER );
		preg_match_all( '/\[as_(.*?)\]/', $panel_html, $matches, PREG_SET_ORDER );

		$panels_html = '';

		foreach ( $gallery_matches as $gallery_match ) {
			if ( $gallery_match[2] !== 'gallery' ) {
				continue;
			}

			$gallery_atts = shortcode_parse_atts( $gallery_match[3] );

			if ( ! isset( $gallery_atts[ 'ids' ] ) ) {
				continue;
			}

			$gallery_ids = explode( ',', $gallery_atts[ 'ids' ] );

			$gallery_images = array();

			foreach ( $gallery_ids as $gallery_id ) {
				$gallery_image = get_post( $gallery_id, ARRAY_A );
				$gallery_image_alt = get_post_meta( $gallery_id, '_wp_attachment_image_alt' );
				$gallery_image['alt'] = ! empty( $gallery_image_alt ) ? $gallery_image_alt[0] : '';

				array_push( $gallery_images, $gallery_image );
			}

			foreach ( $gallery_images as $gallery_image ) {
				$panel_html_copy = $panel_html;

				foreach ( $matches as $match ) {
					$shortcode = $match[0];

					$delimiter_position = strpos( $match[1], '.' );
					$shortcode_arg = $delimiter_position !== false ? substr( $match[1], $delimiter_position + 1 ) : false;
					$shortcode_name = $shortcode_arg !== false ? substr( $match[1], 0, $delimiter_position ) : $match[1];

					switch( $shortcode_name ) {
						case 'image':
							$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
							$image_full = wp_get_attachment_image( $gallery_image['ID'], $image_size );

							$panel_html_copy = str_replace( $shortcode, $image_full, $panel_html_copy );
							break;

						case 'image_src':

							$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
							$image_src = wp_get_attachment_image_src( $gallery_image['ID'], $image_size );

							$panel_html_copy = str_replace( $shortcode, $image_src[0], $panel_html_copy );
							break;

						case 'image_alt':

							$panel_html_copy = str_replace( $shortcode, $gallery_image['alt'], $panel_html_copy );
							break;

						case 'image_title':

							$panel_html_copy = str_replace( $shortcode, $gallery_image['post_title'], $panel_html_copy );
							break;

						case 'image_description':

							$panel_html_copy = str_replace( $shortcode, $gallery_image['post_content'], $panel_html_copy );
							break;
					}
				}

				$panels_html .= $panel_html_copy;
			}
		}

		return $panels_html;
	}

	public function load_styles() {
		if ( is_admin() ) {
			return;
		}

		global $posts;
		$load_styles = false;
		
		if ( ( $load_stylesheets = get_option( 'accordion_slider_load_stylesheets' ) ) !== false ) {
			if ( ( $load_stylesheets === 'all' ) || ( $load_stylesheets === 'homepage' && ( is_home() || is_front_page() ) ) ) {
				$load_styles = true;
			}
		}

		if ( $load_styles === false && isset( $posts ) && ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( strpos( $post->post_content, '[accordion_slider' ) !== false ) {
					$load_styles = true;
				}
			}
		}

		if ( $load_styles === false ) {
			$widget_accordions = get_option('widget_accordion-slider-widget');

			foreach ( ( array )$widget_accordions as $key => $value ) {
				if ( is_array( $value ) && isset( $value['accordion_id'] ) ) {
					$load_styles = true;
				}
			}
		}

		if ( $load_styles === true ) {
			wp_enqueue_style( $this->plugin_slug . '-plugin-style' );

			if ( get_option( 'accordion_slider_is_custom_css') == true ) {
				if ( get_option( 'accordion_slider_load_custom_css_js' ) === 'in_files' ) {
					$custom_css_path = plugins_url( 'accordion-slider-custom/custom.css' );
					$custom_css_dir_path = WP_PLUGIN_DIR . '/accordion-slider-custom/custom.css';

					if ( file_exists( $custom_css_dir_path ) ) {
						wp_enqueue_style( $this->plugin_slug . '-plugin-custom-style', $custom_css_path, array(), self::VERSION );
					}
				} else {
					wp_add_inline_style( $this->plugin_slug . '-plugin-style', stripslashes( get_option( 'accordion_slider_custom_css' ) ) );
				}
			}
		}
	}

	public function load_scripts() {
		if ( empty( $this->scripts_to_load ) ) {
			return;
		}

		foreach ( $this->scripts_to_load as $script_handle ) {
			wp_enqueue_script( $script_handle );
		}

		if ( get_option( 'accordion_slider_is_custom_js' ) == true && get_option( 'accordion_slider_load_custom_css_js' ) === 'in_files' ) {
			$custom_js_path = plugins_url( 'accordion-slider-custom/custom.js' );
			$custom_js_dir_path = WP_PLUGIN_DIR . '/accordion-slider-custom/custom.js';

			if ( file_exists( $custom_js_dir_path ) ) {
				wp_enqueue_script( $this->plugin_slug . '-plugin-custom-script', $custom_js_path, array(), self::VERSION );
			}
		}

		echo $this->get_inline_scripts();
	}

	public function get_inline_scripts() {
		$inline_js = '';

		$inline_js .= $this->js_output;

		if ( get_option( 'accordion_slider_is_custom_js' ) == true && get_option( 'accordion_slider_load_custom_css_js' ) !== 'in_files' ) {
			$custom_js = "\r\n" . '<script type="text/javascript">' .
						"\r\n" . '	' . stripslashes( get_option( 'accordion_slider_custom_js' ) ) .
						"\r\n" . '</script>' . "\r\n";

			$inline_js .= $custom_js;
		}

		return $inline_js;
	}
}