<?php
/**
 * Renderer for image layers.
 * 
 * @since 1.0.0
 */
class BQW_AS_Image_Layer_Renderer extends BQW_AS_Layer_Renderer {

	/**
	 * Initialize the image layer renderer.
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Return the layer's HTML markup.
	 *
	 * Get the image source, alt, link, and retina version
	 * and create the image's HTML markup from those.
	 * 
	 * @since 1.0.0
	 *
	 * @return string The layer HTML.
	 */
	public function render() {
		$image_source = isset( $this->data['image_source'] ) && $this->data['image_source'] !== '' ? $this->data['image_source'] : '';
		$image_alt = isset( $this->data['image_alt'] ) && $this->data['image_alt'] !== '' ? ' alt="' . esc_attr( $this->data['image_alt'] ) . '"' : '';
		$image_retina = isset( $this->data['image_retina'] ) && $this->data['image_retina'] !== '' ? ' data-retina="' . esc_attr( $this->data['image_retina'] ) . '"' : '';

		$image_content = '<img class="' .  esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . ' src="' . esc_attr( $image_source ) . '"' . $image_alt . $image_retina . ' />';
		
		$image_link = $this->data['image_link'];

		if ( isset( $image_link ) && $image_link !== '' ) {
			$image_link = apply_filters( 'accordion_slider_layer_image_link_url', $image_link );
			$image_content = '<a href="' . esc_url( $image_link ) . '">' . $image_content . '</a>';
		}
		
		$html_output = "\r\n" . '			' . $image_content;
		
		$html_output = apply_filters( 'accordion_slider_layer_markup', $html_output, $this->accordion_id, $this->panel_index );

		return $html_output;
	}
}