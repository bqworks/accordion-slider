<?php

	class BQW_AS_Public_Div_Layer extends BQW_AS_Public_Layer {

		public function __construct() {
			parent::__construct();
		}

		public function render() {
			$content = isset( $this->data['text'] ) ? $this->data['text'] : '';

			return "\r\n" . '			' . '<div class="' .  $this->get_classes() . '"' . $this->get_attributes() . '>' . $content . '</div>';
		}
	}