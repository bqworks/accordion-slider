<?php

	class BQW_AS_Public_Heading_Layer extends BQW_AS_Public_Layer {

		public function __construct() {
			parent::__construct();
		}

		public function render() {
			$content = isset( $this->data['text'] ) ? $this->data['text'] : '';
			$type = isset( $this->data['heading_type'] ) ? $this->data['heading_type'] : '';
			
			$html_output = "\r\n" . '			' . '<' . $type . ' class="' .  $this->get_classes() . '"' . $this->get_attributes() . '>' . $content . '</' . $type . '>';

			$html_output = apply_filters( 'accordion_slider_layer_markup', $html_output, $this->accordion_id, $this->panel_index );

			return $html_output;
		}
	}