<?php

	class BQW_AS_Video_Layer_Renderer extends BQW_AS_Layer_Renderer {

		public function __construct() {
			parent::__construct();
		}

		public function render() {
			$content = isset( $this->data['text'] ) && $this->data['text'] !== '' ? $this->data['text'] : '';
			$content = str_replace( 'as-video' , 'as-video ' . $this->get_classes() , $content );
			$insert_pos = strpos( $content, ' ' );

			if ( $insert_pos !== false ) {
				$content = substr_replace( $content, $this->get_attributes(), $insert_pos, 1 );
			}

			$html_output = "\r\n" . '			' . $content;

			$html_output = apply_filters( 'accordion_slider_layer_markup', $html_output, $this->accordion_id, $this->panel_index );

			return $html_output;
		}
	}