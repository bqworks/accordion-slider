<?php

class BQW_Accordion_Slider {

	// the current version of the plugin
	const VERSION = '1.0';

	// unique identifier for the plugin
	protected $plugin_slug = 'accordion-slider';

	// holds a reference to the instance of the class
	protected static $instance = null;

	protected $accordion_id_counter = 1000;

	protected $scripts_to_load = array();

	protected $js_output = '';

	protected $flickr_instance = null;

	/*
		Initialize the plugin
	*/
	private function __construct() {
		// load the plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

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
		Load the plugin translation file
	*/
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
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

	public function output_accordion( $accordion_data, $cache = true ) {
		$accordion_data = apply_filters( 'accordion_slider_data', $accordion_data, $accordion_data['id'] );

		$accordion = new BQW_AS_Public_Accordion( $accordion_data );
		$html_output = $accordion->render();
		$js_output = $accordion->render_js();
		$this->js_output .= $js_output;

		$css_dependencies = $accordion->get_css_dependencies();
		$js_dependencies = $accordion->get_js_dependencies();

		foreach ( $css_dependencies as $css_dependency ) {
			wp_enqueue_style( $this->plugin_slug . '-' . $css_dependency . '-style' );
		}

		foreach ( $js_dependencies as $js_dependency ) {
			$this->add_script_to_load( $this->plugin_slug . '-' . $js_dependency . '-script' );
		}

		if ( $cache === true ) {
			$accordion_cache = array(
				'html_output' => $html_output,
				'js_output' => $js_output,
				'css_dependencies' => $css_dependencies,
				'js_dependencies' => $js_dependencies
			);

			$plugin_settings = BQW_Accordion_Slider_Settings::getPluginSettings();
			$cache_time = 60 * 60 * floatval( get_option( 'accordion_slider_cache_expiry_interval', $plugin_settings['cache_expiry_interval']['default_value'] ) );
			
			set_transient( 'accordion_slider_cache_' . $accordion_data['id'], $accordion_cache, $cache_time );
		}

		return $html_output;
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

		if ( $load_styles === false && is_active_widget( false, false, 'bqw-accordion-slider-widget' ) ) {
			$load_styles = true;
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

			do_action( 'accordion_slider_enqueue_styles' );
		}
	}

	public function load_scripts() {
		if ( empty( $this->scripts_to_load ) ) {
			return;
		}

		if ( get_option( 'accordion_slider_is_custom_js' ) == true && get_option( 'accordion_slider_load_custom_css_js' ) === 'in_files' ) {
			$custom_js_path = plugins_url( 'accordion-slider-custom/custom.js' );
			$custom_js_dir_path = WP_PLUGIN_DIR . '/accordion-slider-custom/custom.js';

			if ( file_exists( $custom_js_dir_path ) ) {
				$this->add_script_to_load( $this->plugin_slug . '-plugin-custom-script', $custom_js_path );
			}
		}

		foreach ( $this->scripts_to_load as $key => $value ) {
			if ( is_numeric( $key ) ) {
				wp_enqueue_script( $value );
			} else if ( is_string( $key ) ) {
				wp_enqueue_script( $key, $value );
			}
		}

		do_action( 'accordion_slider_enqueue_scripts' );

		echo $this->get_inline_scripts();
	}

	public function get_inline_scripts() {
		$inline_js = "\r\n" . '<script type="text/javascript">' .
					"\r\n" . '	jQuery( document ).ready(function( $ ) {' .
					$this->js_output;

		if ( get_option( 'accordion_slider_is_custom_js' ) == true && get_option( 'accordion_slider_load_custom_css_js' ) !== 'in_files' ) {
			$custom_js = "\r\n" . '	' . stripslashes( get_option( 'accordion_slider_custom_js' ) );

			$inline_js .= $custom_js;
		}

		$inline_js .= "\r\n" . '	});' .
					"\r\n" . '</script>';

		$inline_js = apply_filters( 'accordion_slider_javascript', $inline_js );

		return $inline_js;
	}

	public function accordion_slider_shortcode( $atts, $content = null ) {
		$id = isset( $atts['id'] ) ? $atts['id'] : -1;
		$cache = ( isset( $atts['cache'] ) && $atts['cache'] === 'false' ) ? false : true;

		$cache_transient_name = 'accordion_slider_cache_' . $id;

		if ( ( $accordion_cache = get_transient( $cache_transient_name ) ) !== false && $cache !== false ) {
			$css_dependencies = $accordion_cache['css_dependencies'];
			$js_dependencies = $accordion_cache['js_dependencies'];

			foreach ( $css_dependencies as $css_dependency ) {
				wp_enqueue_style( $this->plugin_slug . '-' . $css_dependency . '-style' );
			}

			foreach ( $js_dependencies as $js_dependency ) {
				$this->add_script_to_load( $this->plugin_slug . '-' . $js_dependency . '-script' );
			}

			$this->js_output .= $accordion_cache['js_output'];
			
			return $accordion_cache['html_output'];
		}

		$content = do_shortcode( $content );
		$accordion = $this->get_accordion( $id );

		if ( $accordion === false ) {
			if ( empty( $content ) ) {
				return 'An accordion slider with the ID of ' . $id . ' doesn\'t exist.';
			}

			$accordion = array( 'settings' => array() );
		}

		if ( ! isset( $accordion['id'] ) ) {
			$accordion['id'] = $id;
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
						$layer = array( 'text' => $value );

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
				$attributes[ $atts['name'] ] = $content;
			} else if ( isset( $atts['name'] ) && $atts['name'] === 'layer' ) {
				if ( ! isset( $attributes['layer_settings'] ) ) {
					$attributes['layer_settings'] = array();
				}

				if ( $value === 'true' ) {
					$value = true;
				} else if ( $value === 'false' ) {
					$value = false;
				} else if ( $key === 'preset_styles' ) {
					$value = explode( ',', $value );
				}

				$attributes['layer_settings'][ $key ] = $value;
			}
		}

		return json_encode( $attributes ) . '%as_sep%';
	}
}