<?php
/**
 * Renders the accordion slider.
 * 
 * @since 1.0.0
 */
class BQW_AS_Accordion_Renderer {

	/**
	 * Data of the accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $data = null;

	/**
	 * ID of the accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @var int
	 */
	protected $id = null;

	/**
	 * Settings of the accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $settings = null;

	/**
	 * Default accordion settings data.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $default_settings = null;

	/**
	 * HTML markup of the accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @var string
	 */
	protected $html_output = '';

	/**
	 * List of id's for the CSS files that need to be loaded for the accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $css_dependencies = array();

	/**
	 * List of id's for the JS files that need to be loaded for the accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $js_dependencies = array();

	/**
	 * Initialize the accordion renderer by retrieving the id and settings from the passed data.
	 * 
	 * @since 1.0.0
	 *
	 * @param array $data The data of the accordion.
	 */
	public function __construct( $data ) {
		$this->data = $data;
		$this->id = $this->data['id'];
		$this->settings = $this->data['settings'];
		$this->default_settings = BQW_Accordion_Slider_Settings::getSettings();
	}

	/**
	 * Return the accordion's HTML markup.
	 *
	 * @since 1.0.0
	 * 
	 * @return string The HTML markup of the accordion.
	 */
	public function render() {
		$classes = 'accordion-slider as-no-js';
		$classes .= isset( $this->settings['custom_class'] ) && $this->settings['custom_class'] !== '' ? ' ' . $this->settings['custom_class'] : '';
		$classes = apply_filters( 'accordion_slider_classes' , $classes, $this->id );

		$width = isset( $this->settings['width'] ) ? $this->settings['width'] : $this->default_settings['width']['default_value'];
		$height = isset( $this->settings['height'] ) ? $this->settings['height'] : $this->default_settings['height']['default_value'];

		$this->html_output .= "\r\n" . '<div id="accordion-slider-' . esc_attr( $this->id ) . '" class="' . esc_attr( $classes ) . '" style="width: ' . floatval( $width ) . 'px; height: ' . floatval( $height ) . 'px;">';

		if ( $this->has_panels() ) {
			$this->html_output .= "\r\n" . '	<div class="as-panels">';
			$this->html_output .= "\r\n" . '		' . $this->create_panels();
			$this->html_output .= "\r\n" . '	</div>';
		}

		$this->html_output .= "\r\n" . '</div>';
		
		$this->html_output = apply_filters( 'accordion_slider_markup', $this->html_output, $this->id );

		return $this->html_output;
	}

	/**
	 * Check if the accordion has panels.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean Whether or not the accordion has panels.
	 */
	protected function has_panels() {
		if ( isset( $this->data['panels'] ) && ! empty( $this->data['panels'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Create the accordion's panels and get their HTML markup.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The HTML markup of the panels.
	 */
	protected function create_panels() {
		$panels_output = '';
		$panels = $this->data['panels'];
		$panel_counter = 0;

		foreach ( $panels as $panel ) {
			$panels_output .= $this->create_panel( $panel, $panel_counter );
			$panel_counter++;
		}

		return $panels_output;
	}

	/**
	 * Create a panel.
	 * 
	 * @since 1.0.0
	 *
	 * @param  array  $data          The data of the panel.
	 * @param  int    $panel_counter The index of the panel.
	 * @return string                The HTML markup of the panel.
	 */
	protected function create_panel( $data, $panel_counter ) {
		$panel = BQW_AS_Panel_Renderer_Factory::create_panel( $data );

		$lazy_loading = isset( $this->settings['lazy_loading'] ) ? $this->settings['lazy_loading'] : $this->default_settings['lazy_loading']['default_value'];
		$lightbox = isset( $this->settings['lightbox'] ) ? $this->settings['lightbox'] : $this->default_settings['lightbox']['default_value'];
		$hide_image_title = isset( $this->settings['hide_image_title'] ) ? $this->settings['hide_image_title'] : $this->default_settings['hide_image_title']['default_value'];
		$link_target = isset( $this->settings['link_target'] ) ? $this->settings['link_target'] : $this->default_settings['link_target']['default_value'];

		$extra_data = new stdClass();
		$extra_data->lazy_loading = $lazy_loading;
		$extra_data->lightbox = $lightbox;
		$extra_data->hide_image_title = $hide_image_title;
		$extra_data->link_target = $link_target;

		$panel->set_data( $data, $this->id, $panel_counter, $extra_data );
		
		return $panel->render();
	}

	/**
	 * Return the inline JavaScript code of the accordion and identify all CSS and JS
	 * files that need to be loaded for the current accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @return string The inline JavaScript code of the accordion.
	 */
	public function render_js() {
		$js_output = '';
		$settings_js = '';

		foreach ( $this->default_settings as $name => $setting ) {
			if ( ! isset( $setting['js_name'] ) ) {
				continue;
			}

			$setting_default_value = $setting['default_value'];
			$setting_value = isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : $setting_default_value;

			if ( $setting_value != $setting_default_value ) {
				if ( $settings_js !== '' ) {
					$settings_js .= ',';
				}

				if ( is_bool( $setting_value ) ) {
					$setting_value = $setting_value === true ? 'true' : 'false';
				} else if ( is_numeric( $setting_value ) ) {
					$setting_value = floatval( $setting_value );
				} else {
					$setting_value = json_encode( $setting_value );
				}

				$settings_js .= "\r\n" . '			' . $setting['js_name'] . ': ' . $setting_value;
			}
		}

		if ( isset ( $this->settings['breakpoints'] ) ) {
			$breakpoints_js = "";

			foreach ( $this->settings['breakpoints'] as $breakpoint ) {
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

					foreach ( $breakpoint as $name => $value ) {
						if ( $breakpoint_setting_js !== '' ) {
							$breakpoint_setting_js .= ',';
						}

						if ( is_bool( $value ) ) {
							$value = $value === true ? 'true' : 'false';
						} else if ( is_numeric( $value ) ) {
							$value = floatval( $value );
						} else {
							$value = json_encode( $value );
						}

						$breakpoint_setting_js .= "\r\n" . '					' . $this->default_settings[ $name ]['js_name'] . ': ' . $value;
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

		$this->add_js_dependency( 'plugin' );

		$js_output .= "\r\n" . '		$( "#accordion-slider-' . $this->id . '" ).accordionSlider({' .
											$settings_js .
						"\r\n" . '		});' . "\r\n";

		if ( isset ( $this->settings['lightbox'] ) && $this->settings['lightbox'] === true ) {
			$this->add_js_dependency( 'lightbox' );
			$this->add_css_dependency( 'lightbox' );
			$accordionIdAttribute = '#accordion-slider-' . $this->id;

			$lightbox_options = array();
			$lightbox_options = apply_filters( 'accordion_slider_lightbox_options', $lightbox_options, $this->id );
			$lightbox_options_string = '';

			if ( is_null( $lightbox_options ) === false && empty( $lightbox_options ) === false ) {
				foreach ( $lightbox_options as $key => $value) {
					$lightbox_option_value = $value;

					if ( is_bool( $lightbox_option_value ) ) {
						$lightbox_option_value = $lightbox_option_value === true ? 'true' : 'false';
					} else if ( is_numeric( $lightbox_option_value ) ) {
						$lightbox_option_value = floatval( $lightbox_option_value );
					} else {
						$lightbox_option_value = json_encode( $lightbox_option_value );
					}

					$lightbox_options_string .= ', ' . $key . ': ' . $lightbox_option_value;
				}
			}

			$js_output .= "\r\n" . '		$( "' . $accordionIdAttribute . ' .as-panel > a" ).on( "click", function( event ) {' .
							"\r\n" . '			event.preventDefault();' .
							"\r\n" . '			if ( $( "' . $accordionIdAttribute . '" ).hasClass( "as-swiping" ) === false ) {' .
							"\r\n" . '				var sliderInstance = $( "' . $accordionIdAttribute . '" ).data( "accordionSlider" ),' .
							"\r\n" . '					isAutoplay = sliderInstance.settings.autoplay,' .
							"\r\n" . '					index = $( "' . $accordionIdAttribute . ' .as-panel > a" ).index( $( this ) );' .
							"\r\n" .
							"\r\n" . '				$.fancybox.open( $( "' . $accordionIdAttribute . ' .as-panel > a" ), {' .
							"\r\n" . '					index: index,' .
							"\r\n" . '					afterShow: function() {' .
							"\r\n" . '						if ( isAutoplay === true ) {' .
							"\r\n" . '							sliderInstance.settings.autoplay = false;' .
							"\r\n" . '							sliderInstance.stopAutoplay();' .
							"\r\n" . '						}' .
							"\r\n" . '					},' .
							"\r\n" . '					afterClose: function() {' .
							"\r\n" . '						if ( isAutoplay === true ) {' .
							"\r\n" . '							sliderInstance.settings.autoplay = true;' .
							"\r\n" . '							sliderInstance.startAutoplay();' .
							"\r\n" . '						}' .
							"\r\n" . '					}' .
							"\r\n" . '					' . $lightbox_options_string . 
							"\r\n" . '				});' .
							"\r\n" . '			}' .
							"\r\n" . '		});' . "\r\n";
		}

		if ( isset ( $this->settings['page_scroll_easing'] ) && $this->settings['page_scroll_easing'] !== 'swing' ) {
			$this->add_js_dependency( 'easing' );
		}

		if ( strpos( $this->html_output, 'video-js' ) !== false ) {
			$this->add_js_dependency( 'video-js' );
			$this->add_css_dependency( 'video-js' );
		}

		return $js_output;
	}

	/**
	 * Add the id of a CSS file that needs to be loaded for the current accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @param string $id The id of the file.
	 */
	protected function add_css_dependency( $id ) {
		$this->css_dependencies[] = $id;
	}

	/**
	 * Add the id of a JS file that needs to be loaded for the current accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @param string $id The id of the file.
	 */
	protected function add_js_dependency( $id ) {
		$this->js_dependencies[] = $id;
	}

	/**
	 * Return the list of id's for CSS files that need to be loaded for the current accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @return array The list of id's for CSS files.
	 */
	public function get_css_dependencies() {
		return $this->css_dependencies;
	}

	/**
	 * Return the list of id's for JS files that need to be loaded for the current accordion.
	 *
	 * @since 1.0.0
	 * 
	 * @return array The list of id's for JS files.
	 */
	public function get_js_dependencies() {
		return $this->js_dependencies;
	}
}