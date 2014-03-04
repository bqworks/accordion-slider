<?php
/**
 * Renderer for heading layers.
 * 
 * @since 1.0.0
 */
class BQW_AS_Heading_Layer_Renderer extends BQW_AS_Layer_Renderer {

	/**
	 * Initialize the heading layer renderer.
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Return the layer's HTML markup.
	 * 
	 * @since 1.0.0
	 *
	 * @return string The layer HTML.
	 */
	public function render() {
		$content = isset( $this->data['text'] ) ? $this->data['text'] : '';
		$content = apply_filters( 'accordion_slider_layer_content', $content );
		
		$type = isset( $this->data['heading_type'] ) ? $this->data['heading_type'] : '';
		
		$html_output = "\r\n" . '			' . '<' . $type . ' class="' .  $this->get_classes() . '"' . $this->get_attributes() . '>' . $content . '</' . $type . '>';

		$html_output = apply_filters( 'accordion_slider_layer_markup', $html_output, $this->accordion_id, $this->panel_index );

		return $html_output;
	}
}