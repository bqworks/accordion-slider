<?php

	class BQW_AS_Public_Layer {

		protected $data = null;

		protected $settings = null;

		protected $default_settings = null;

		public function __construct( $data ) {
			$this->data = $data;
			$this->settings = $this->data['settings'];
			$this->default_settings = Accordion_Slider_Settings::getLayerSettings();
		}

		public function render() {
			
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
				$classes .= ' ' . sanitize_html_class( $this->settings['custom_class'] );
			}

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