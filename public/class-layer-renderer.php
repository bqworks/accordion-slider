<?php
/**
 * Base class for layer renderers.
 *
 * @since  1.0.0
 */
class BQW_AS_Layer_Renderer {

	/**
	 * Data of the layer.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $data = null;

	/**
	 * Settings of the layer.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $settings = null;

	/**
	 * ID of the accordion to which the layer belongs.
	 *
	 * @since 1.0.0
	 * 
	 * @var int
	 */
	protected $accordion_id = null;

	/**
	 * index of the panel to which the layer belongs.
	 *
	 * @since 1.0.0
	 * 
	 * @var int
	 */
	protected $panel_index = null;

	/**
	 * Default layer settings.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $default_settings = null;

	/**
	 * Initialize the layer renderer.
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->default_settings = BQW_Accordion_Slider_Settings::getLayerSettings();
	}

	/**
	 * No implementation yet.
	 * 
	 * @since 1.0.0
	 */
	public function render() {
		
	}

	/**
	 * Set the data of the layer.
	 *
	 * @since 1.0.0
	 * 
	 * @param array $data         The data of the layer.
	 * @param int   $accordion_id The id of the accordion.
	 * @param int   $panel_index  The index of the panel.
	 */
	public function set_data( $data, $accordion_id, $panel_index ) {
		$this->data = $data;
		$this->accordion_id = $accordion_id;
		$this->panel_index = $panel_index;
		$this->settings = $this->data['settings'];
	}

	/**
	 * Return the classes of the layer.
	 *
	 * Gets the class associated with the display method,
	 * then the preset classes and then the custom class.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The classes.
	 */
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

	/**
	 * Return the attributes of the layer.
	 *
	 * Gets the attribute data and creates a string of attributes
	 * by suffixing each attribute name with 'data-'.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The attributes.
	 */
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