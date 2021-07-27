<?php
/**
 * Contains the validation functions for the accordion slider settings, panels, layers etc.
 * 
 * @since 1.8.0
 */
class BQW_Accordion_Slider_Validation {

	/**
	 * Validate the accordion slider's data.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted accordion slider data.
	 * @return array       Validated accordion slider data.
	 */
	public static function validate_accordion_slider_data( $data ) {
		$accordion = array(
			'id' => intval( $data['id'] ),
			'name' => sanitize_text_field( $data['name'] ),
			'panels_state' => self::validate_accordion_slider_panels_state( $data['panels_state'] ),
			'settings' => self::validate_accordion_slider_settings( $data['settings'] ),
			'panels' => self::validate_accordion_slider_panels( $data['panels'] )
		);

		return $accordion;
	}

	/**
	 * Validate the accordion slider's panels state.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted accordion slider panels state.
	 * @return array       Validated accordion slider panels state.
	 */
	public static function validate_accordion_slider_panels_state( $data ) {
		$accordion_slider_panels_state = array();
		$default_panels_state = BQW_Accordion_Slider_Settings::getPanelsState();

		foreach ( $data as $panel_name => $panel_state) {
			if ( array_key_exists( $panel_name, $default_panels_state ) ) {
				$accordion_slider_panels_state[ $panel_name ] = ( $panel_state === 'closed' || $panel_state === '' ) ? $panel_state : 'closed';
			}
		}

		return $accordion_slider_panels_state;
	}

	/**
	 * Validate the accordion slider's settings.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted accordion slider settings.
	 * @return array       Validated accordion slider settings.
	 */
	public static function validate_accordion_slider_settings( $data ) {
		$accordion_slider_settings = array();
		$default_accordion_slider_settings = BQW_Accordion_Slider_Settings::getSettings();
		
		foreach ( $default_accordion_slider_settings as $name => $value ) {
			if ( isset( $data[ $name ] ) ) {
				$setting_value = $data[ $name ];
				$type = $default_accordion_slider_settings[ $name ][ 'type' ];

				if ( $type === 'boolean' ) {
					$accordion_slider_settings[ $name ] = is_bool( $setting_value ) ? $setting_value : $default_accordion_slider_settings[ $name ]['default_value'];
				} else if ( $type === 'number' ) {
					$accordion_slider_settings[ $name ] = floatval( $setting_value );
				} else if ( $type === 'mixed' || $type === 'text' ) {
					$accordion_slider_settings[ $name ] = sanitize_text_field( $setting_value );
				} else if ( $type === 'select' ) {
					if ( $name === 'thumbnail_image_size' ) {
						$accordion_slider_settings[ $name ] = sanitize_text_field( $setting_value );
					} else {
						$accordion_slider_settings[ $name ] = array_key_exists( $setting_value, $default_accordion_slider_settings[ $name ]['available_values'] ) ? $setting_value : $default_accordion_slider_settings[ $name ]['default_value'];
					}
				}
			}
		}

		if ( isset( $data['breakpoints'] ) ) {
			$accordion_slider_settings['breakpoints'] = self::validate_accordion_slider_breakpoint_settings( $data['breakpoints'] );
		}
		
		return $accordion_slider_settings;
	}

	/**
	 * Validate the accordion slider's breakpoint settings.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted breakpoint settings.
	 * @return array       Validated breakpoint settings.
	 */
	public static function validate_accordion_slider_breakpoint_settings( $breakpoints_data ) {
		$default_accordion_slider_settings = BQW_Accordion_Slider_Settings::getSettings();
		$default_breakpoint_settings = BQW_Accordion_Slider_Settings::getBreakpointSettings();
		$breakpoints = array();

		foreach ( $breakpoints_data as $breakpoint_data ) {
			$breakpoint = array(
				'breakpoint_width' => floatval( $breakpoint_data['breakpoint_width'] )
			);

			foreach ( $breakpoint_data as $name => $value ) {
				if ( in_array( $name, $default_breakpoint_settings ) ) {
					$type = $default_accordion_slider_settings[ $name ][ 'type' ];

					if ( $type === 'boolean' ) {
						$breakpoint[ $name ] = is_bool( $value ) ? $value : $default_accordion_slider_settings[ $name ]['default_value'];
					} else if ( $type === 'number' ) {
						$breakpoint[ $name ] = floatval( $value );
					} else if ( $type === 'mixed' ) {
						$breakpoint[ $name ] = sanitize_text_field( $value );
					} else if ( $type === 'select' ) {
						$breakpoint[ $name ] = array_key_exists( $value, $default_accordion_slider_settings[ $name ]['available_values'] ) ? $value : $default_accordion_slider_settings[ $name ]['default_value'];
					}
				}
			}

			array_push( $breakpoints, $breakpoint );
		}

		return $breakpoints;
	}

	/**
	 * Validate the accordion slider's panels data.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted accordion slider panels data.
	 * @return array       Validated accordion slider panels.
	 */
	public static function validate_accordion_slider_panels( $panels_data ) {
		$panels = array();
		
		foreach ( $panels_data as $panel_data ) {
			$panel = array();

			foreach ( $panel_data as $name => $value ) {
				if ( $name === 'position' ) {
					$panel['position'] = intval( $value );
				} else if ( $name === 'settings' ) {
					$panel['settings'] = self::validate_panel_settings( $value );
				} else if ( $name === 'layers' ) {
					$panel['layers'] = self::validate_panel_layers( $value );
				} else if ( $name === 'html' ) {
					$panel[ $name ] = $value;
				} else {
					$panel[ $name ] = sanitize_text_field( $value );
				}
			}

			array_push( $panels, $panel );
		}

		return $panels;
	}

	/**
	 * Validate the panel settings.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted panel settings.
	 * @return array       Validated panel settings.
	 */
	public static function validate_panel_settings( $panel_settings_data ) {
		$panel_settings = array();
		$default_panel_settings = BQW_Accordion_Slider_Settings::getPanelSettings();

		if ( ! empty( $panel_settings_data ) ) {
			$panel_settings['content_type'] = array_key_exists( $panel_settings_data['content_type'], $default_panel_settings['content_type']['available_values'] ) ? $panel_settings_data['content_type'] : $default_panel_settings['content_type']['default_value'];
			
			foreach ( $panel_settings_data as $panel_setting_name => $panel_setting_value ) {
				if ( isset( $default_panel_settings[ $panel_setting_name ] ) ) {
					$type = $default_panel_settings[ $panel_setting_name ]['type'];

					if ( $type === 'number' ) {
						$panel_settings[ $panel_setting_name ] = floatval( $panel_setting_value );
					} else if ( $type === 'text' ) {
						$panel_settings[ $panel_setting_name ] = sanitize_text_field( $panel_setting_value );
					} else if ( $type === 'select' ) {
						$panel_settings[ $panel_setting_name ] = array_key_exists( $panel_setting_value, $default_panel_settings[ $panel_setting_name ]['available_values'] ) ? $panel_setting_value : $default_panel_settings[ $panel_setting_name ]['default_value'];
					} else if ( $type === 'multiselect' ) {
						$panel_settings[ $panel_setting_name ] = array();

						foreach ( $panel_setting_value as $option ) {
							array_push( $panel_settings[ $panel_setting_name ], wp_kses_post( $option ) );
						}
					}
				}
			}
		}

		return $panel_settings;
	}

	/**
	 * Validate the panel layers.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted panel layers.
	 * @return array       Validated panel layers.
	 */
	public static function validate_panel_layers( $layers_data ) {
		$layers = array();
		global $allowedposttags;

		foreach ( $layers_data as $layer_data ) {
			$layer = array();

			foreach ( $layer_data as $name => $value ) {
				if ( in_array( $name, array( 'id', 'accordion_id', 'panel_id', 'position' ) ) ) {
					$layer[ $name ] = intval( $value );
				} else if ( $name === 'settings' ) {
					$layer['settings'] = self::validate_layer_settings( $value );
				} else {

					// for other layer fields, like name, text, image source etc.
					$allowed_html = array_merge(
						$allowedposttags,
						array(
							'iframe' => array(
								'src' => true,
								'width' => true,
								'height' => true,
								'allow' => true,
								'allowfullscreen' => true,
								'class' => true,
								'id' => true,
								'data-*' => true
							),
							'source' => array(
								'src' => true,
								'type' => true
							)
						)
					);

					$layer[ $name ] = wp_kses( $value, $allowed_html );
				}
			}

			array_push( $layers, $layer );
		}

		return $layers;
	}

	/**
	 * Validate the layer settings.
	 *
	 * @since 1.8.0
	 * 
	 * @param array  $data Posted layer settings.
	 * @return array       Validated panel layers.
	 */
	public static function validate_layer_settings( $layer_settings_data ) {
		$layer_settings = array();
		$default_layer_settings = BQW_Accordion_Slider_Settings::getLayerSettings();

		foreach ( $layer_settings_data as $layer_setting_name => $layer_setting_value ) {
			if ( isset( $default_layer_settings[ $layer_setting_name ] ) ) {
				$type = $default_layer_settings[ $layer_setting_name ]['type'];

				if ( $type === 'number' ) {
					$layer_settings[ $layer_setting_name ] = floatval( $layer_setting_value );
				} else if ( $type === 'text' || $type === 'mixed' ) {
					$layer_settings[ $layer_setting_name ] = sanitize_text_field( $layer_setting_value );
				} else if ( $type === 'select' ) {
					$layer_settings[ $layer_setting_name ] = array_key_exists( $layer_setting_value, $default_layer_settings[ $layer_setting_name ]['available_values'] ) ? $layer_setting_value : $default_layer_settings[ $layer_setting_name ]['default_value'];
				} else if ( $type === 'multiselect' ) {
					$layer_settings[ $layer_setting_name ] = array();

					foreach ( $layer_setting_value as $option ) {
						array_push( $layer_settings[ $layer_setting_name ], wp_kses_post( $option ) );
					}
				}
			}
		}

		return $layer_settings;
	}
}