<?php

class BQW_Accordion_Slider {

	// the current version of the plugin
	const VERSION = '1.0.0';

	// unique identifier for the plugin
	protected $plugin_slug = 'accordion-slider';

	// holds a reference to the instance of the class
	protected static $instance = null;

	protected $accordion_id_counter = 1000;

	protected $scripts_to_load = [];

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

	public function output_accordion( $accordion_data ) {
		$accordion = new BQW_AS_Public_Accordion( $accordion_data );
		$html_output = $accordion->render();
		$this->js_output .= $accordion->render_js();

		$css_dependencies = $accordion->get_css_dependencies();
		$js_dependencies = $accordion->get_js_dependencies();

		foreach ( $css_dependencies as $css_dependency ) {
			wp_enqueue_style( $this->plugin_slug . '-' . $css_dependency . '-style' );
		}

		foreach ( $js_dependencies as $js_dependency ) {
			$this->add_script_to_load( $this->plugin_slug . '-' . $js_dependency . '-script' );
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