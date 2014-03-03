<?php

	class BQW_AS_Image_Layer_Renderer extends BQW_AS_Layer_Renderer {

		public function __construct() {
			parent::__construct();
		}

		public function render() {
			$image_source = isset( $this->data['image_source'] ) && $this->data['image_source'] !== '' ? $this->data['image_source'] : '';
			$image_alt = isset( $this->data['image_alt'] ) && $this->data['image_alt'] !== '' ? ' alt="' . esc_attr( $this->data['image_alt'] ) . '"' : '';
			$image_retina = isset( $this->data['image_retina'] ) && $this->data['image_retina'] !== '' ? ' data-retina="' . $this->data['image_retina'] . '"' : '';

			$image_content = '<img class="' .  $this->get_classes() . '"' . $this->get_attributes() . ' src="' . $image_source . '"' . $image_alt . $image_retina . ' />';

			$image_link = $this->data['image_link'];

			if ( isset( $image_link ) && $$image_link !== '' ) {
				$image_link = apply_filters( 'accordion_slider_layer_image_link_url', $image_link );
				$image_content = '<a href="' . esc_url( $image_link ) . '">' . $image_content . '</a>';
			}
			
			$html_output = "\r\n" . '			' . $image_content;
			
			$html_output = apply_filters( 'accordion_slider_layer_markup', $html_output, $this->accordion_id, $this->panel_index );

			return $html_output;
		}
	}