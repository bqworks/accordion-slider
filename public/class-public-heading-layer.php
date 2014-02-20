<?php

	class BQW_AS_Public_Heading_Layer extends BQW_AS_Public_Layer {

		public function __construct( $data ) {
			parent::__construct( $data );
		}

		public function render() {
			$content = isset( $this->data['text'] ) ? $this->data['text'] : '';
			$type = isset( $this->data['heading_type'] ) ? $this->data['heading_type'] : '';
			
			return "\r\n" . '			' . '<' . $type . ' class="' .  $this->get_classes() . '"' . $this->get_attributes() . '>' . $content . '</' . $type . '>';
		}
	}