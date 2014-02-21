<?php

	class BQW_AS_Public_Image_Layer extends BQW_AS_Public_Layer {

		public function __construct() {
			parent::__construct();
		}

		public function render() {
			$image_source = isset( $this->data['image_source'] ) && $this->data['image_source'] !== '' ? $this->data['image_source'] : 'placeholder.png';
			$image_alt = isset( $this->data['image_alt'] ) && $this->data['image_alt'] !== '' ? ' alt="' . esc_attr( $this->data['image_alt'] ) . '"' : '';
			$image_retina = isset( $this->data['image_retina'] ) && $this->data['image_retina'] !== '' ? ' data-retina="' . $this->data['image_retina'] . '"' : '';

			$image_content = '<img class="' .  $this->get_classes() . '"' . $this->get_attributes() . ' src="' . $image_source . '"' . $image_alt . $image_retina . ' />';

			if ( isset( $this->data['image_link'] ) && $this->data['image_link'] !== '' ) {
				$image_content = '<a href="' . esc_url( $this->data['image_link'] ) . '">' . $image_content . '</a>';
			}

			return "\r\n" . '			' . $image_content;
		}
	}