<?php

class BQW_Accordion_Slider_Shortcode {

	protected static $instance = null;

	protected $plugin = null;

	protected $plugin_slug = null;
	
	private function __construct() {
		$this->plugin = BQW_Accordion_Slider::get_instance();
		$this->plugin_slug = $this->plugin->get_plugin_slug();

		add_shortcode( 'accordion_slider', array( $this, 'accordion_slider_shortcode' ) );
		add_shortcode( 'accordion_panel', array( $this, 'accordion_panel_shortcode' ) );
		add_shortcode( 'accordion_panel_element', array( $this, 'accordion_panel_element_shortcode' ) );
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

	public function accordion_slider_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'id' => '-1'
		), $atts ) );

		$content = do_shortcode( $content );
		$accordion = $this->plugin->get_accordion( $id );

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

		return $this->plugin->output_accordion( $accordion );
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
}