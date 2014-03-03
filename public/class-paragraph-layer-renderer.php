<?php

	class BQW_AS_Paragraph_Layer_Renderer extends BQW_AS_Layer_Renderer {

		public function __construct() {
			parent::__construct();
		}

		public function render() {
			$content = isset( $this->data['text'] ) ? $this->data['text'] : '';
			$content = apply_filters( 'accordion_slider_layer_content', $content );
			
			$html_output = "\r\n" . '			' . '<p class="' .  $this->get_classes() . '"' . $this->get_attributes() . '>' . $content . '</p>';

			$html_output = apply_filters( 'accordion_slider_layer_markup', $html_output, $this->accordion_id, $this->panel_index );

			return $html_output;
		}
	}