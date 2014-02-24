<?php

	class BQW_AS_Public_Layer {

		protected $data = null;

		protected $settings = null;

		protected $accordion_id = null;

		protected $panel_index = null;

		protected $default_settings = null;

		public function __construct() {
			$this->default_settings = BQW_Accordion_Slider_Settings::getLayerSettings();
		}

		public function render() {
			
		}

		public function set_data( $data, $accordion_id, $panel_index ) {
			$this->data = $data;
			$this->accordion_id = $accordion_id;
			$this->panel_index = $panel_index;
			$this->settings = $this->data['settings'];
		}

		protected function get_classes() {
			$classes = 'as-layer';

			if ( isset( $this->settings['display'] ) ) {
				$classes .= ' as-' . $this->settings['display'];
				unset( $this->settings['display'] );
			}

			$styles = isset( $this->settings['preset_styles'] ) ? $this->settings['preset_styles'] : $this->default_settings['preset_styles']['default_value'];

			foreach ( $styles as $style ) {
				$classes .= ' ' . $style;
			}

			unset( $this->settings['preset_styles'] );

			if ( isset( $this->settings['custom_class'] ) && $this->settings['custom_class'] !== '' ) {
				$classes .= ' ' . $this->settings['custom_class'];
			}

			unset( $this->settings['custom_class'] );

			$classes = apply_filters( 'accordion_slider_layer_classes', $classes, $this->accordion_id, $this->panel_index );

			return $classes;
		}

		protected function get_attributes() {
			$attributes = '';

			foreach ( $this->settings as $name => $value ) {
				if ( $this->default_settings[ $name ]['default_value'] != $value ) {
					$name = str_replace('_', '-', $name);

					$attributes .= ' data-' . $name . '="' . esc_attr( $value ) . '"';
				}
			}

			return $attributes;
		}

	}