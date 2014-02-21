<?php

	class BQW_AS_Public_Video_Layer extends BQW_AS_Public_Layer {

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

			return "\r\n" . '			' . $content;
		}
	}