<?php

	class BQW_AS_Public_Paragraph_Layer extends BQW_AS_Public_Layer {

		public function __construct() {
			parent::__construct();
		}

		public function render() {
			$content = isset( $this->data['text'] ) ? $this->data['text'] : '';
			
			return "\r\n" . '			' . '<p class="' .  $this->get_classes() . '"' . $this->get_attributes() . '>' . $content . '</p>';
		}
	}