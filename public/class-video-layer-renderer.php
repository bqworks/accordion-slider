<?php
/**
 * Renderer for video layers.
 * 
 * @since 1.0.0
 */
class BQW_AS_Video_Layer_Renderer extends BQW_AS_Layer_Renderer {

	/**
	 * Initialize the video layer renderer.
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
		$content = isset( $this->data['text'] ) && $this->data['text'] !== '' ? $this->data['text'] : '';
		$content = str_replace( 'as-video' , 'as-video ' . $this->get_classes() , $content );
		$insert_pos = strpos( $content, ' ' );

		if ( $insert_pos !== false ) {
			$content = substr_replace( $content, ' ' . $this->get_attributes(), $insert_pos, 1 );
		}

		$html_output = "\r\n" . '			' . $content;

		$html_output = apply_filters( 'accordion_slider_layer_markup', $html_output, $this->accordion_id, $this->panel_index );

		return $html_output;
	}
}