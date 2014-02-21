<?php
	class BQW_AS_Public_Panel {

		protected $data = null;

		protected $html_output = '';

		protected $lazy_loading = null;

		public function __construct( $data, $lazy_loading ) {
			$this->data = $data;
			$this->lazy_loading = $lazy_loading;
		}

		public function render() {
			$this->html_output = "\r\n" . '		<div class="as-panel">';

			if ( $this->has_background_image() ) {
				$this->html_output .= "\r\n" . '			' . ( $this->has_background_link() && ! $this->has_opened_background_image() ? $this->add_link_to_background_image( $this->create_background_image() ) : $this->create_background_image() );
			}

			if ( $this->has_opened_background_image() ) {
				$this->html_output .= "\r\n" . '			' . ( $this->has_background_link() ? $this->add_link_to_background_image( $this->create_opened_background_image() ) : $this->create_opened_background_image() );
			}

			if ( $this->has_html() ) {
				$this->html_output .= "\r\n" . '			' . $this->create_html();
			}

			if ( $this->has_layers() ) {
				$this->html_output .= "\r\n" . '			' . $this->create_layers();
			}

			$this->html_output .= "\r\n" . '		</div>';

			return $this->html_output;
		}

		private function has_background_image() {
			if ( isset( $this->data['background_source'] ) && $this->data['background_source'] !== '' ) {
				return true;
			}

			return false;
		}

		private function create_background_image() {
			$background_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'accordion-slider/public/assets/css/images/blank.gif' ) . '" data-src="' . esc_attr( $this->data['background_source'] ) . '"' : ' src="' . esc_attr( $this->data['background_source'] ) . '"';
			$background_alt = isset( $this->data['background_alt'] ) && $this->data['background_alt'] !== '' ? ' alt="' . esc_attr( $this->data['background_alt'] ) . '"' : '';
			$background_title = isset( $this->data['background_title'] ) && $this->data['background_title'] !== '' ? ' title="' . esc_attr( $this->data['background_title'] ) . '"' : '';
			$background_width = isset( $this->data['background_width'] ) && $this->data['background_width'] != 0 ? ' width="' . esc_attr( $this->data['background_width'] ) . '"' : '';
			$background_height = isset( $this->data['background_height'] ) && $this->data['background_height'] != 0 ? ' height="' . esc_attr( $this->data['background_height'] ) . '"' : '';
			$background_retina_source = isset( $this->data['background_retina_source'] ) && $this->data['background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['background_retina_source'] ) . '"' : '';
			$background_image = '<img class="as-background"' . $background_source . $background_retina_source . $background_alt . $background_title . $background_width . $background_height . ' />';

			return $background_image;
		}

		private function has_opened_background_image() {
			if ( isset( $this->data['opened_background_source'] ) && $this->data['opened_background_source'] !== '' ) {
				return true;
			}

			return false;
		}

		private function create_opened_background_image() {
			$opened_background_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'accordion-slider/public/assets/css/images/blank.gif' ) . '" data-src="' . esc_attr( $this->data['opened_background_source'] ) . '"' : ' src="' . esc_attr( $this->data['opened_background_source'] ) . '"';
			$opened_background_alt = isset( $this->data['opened_background_alt'] ) && $this->data['opened_background_alt'] !== '' ? ' alt="' . esc_attr( $this->data['opened_background_alt'] ) . '"' : '';
			$opened_background_title = isset( $this->data['opened_background_title'] ) && $this->data['opened_background_title'] !== '' ? ' title="' . esc_attr( $this->data['opened_background_title'] ) . '"' : '';
			$opened_background_width = isset( $this->data['opened_background_width'] ) && $this->data['opened_background_width'] != 0 ? ' width="' . esc_attr( $this->data['opened_background_width'] ) . '"' : '';
			$opened_background_height = isset( $this->data['opened_background_height'] ) && $this->data['opened_background_height'] != 0 ? ' height="' . esc_attr( $this->data['opened_background_height'] ) . '"' : '';
			$opened_background_retina_source = isset( $this->data['opened_background_retina_source'] ) && $this->data['opened_background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['opened_background_retina_source'] ) . '"' : '';
			$opened_background_image = '<img class="as-background-opened"' . $opened_background_source . $opened_background_retina_source . $opened_background_alt . $opened_background_title . $opened_background_width . $opened_background_height . ' />';
		
			return $opened_background_image;
		}

		private function has_background_link() {
			if ( isset( $this->data['background_link'] ) && $this->data['background_link'] !== '' ) {
				return true;
			} 

			return false;
		}

		private function add_link_to_background_image( $image ) {
			$background_link_href = $this->data['background_link'];
			$background_link_title = isset( $this->data['background_link_title'] ) && $this->data['background_link_title'] !== '' ? ' title="' . esc_attr( $this->data['background_link_title'] ) . '"' : '';
			$background_link = '<a href="' . $background_link_href . '"' . $background_link_title . '>' . $image . '</a>';
			
			return $background_link;
		}

		private function has_html() {
			if ( isset( $this->data['html'] ) && $this->data['html'] !== '' ) {
				return true;
			} 

			return false;
		}

		private function create_html() {
			return $this->data['html'];
		}

		private function has_layers() {
			if ( isset( $this->data['layers'] ) && ! empty( $this->data['layers'] ) ) {
				return true;
			}

			return false;
		}

		private function create_layers() {
			$layers_output = '';
			$layers = array_reverse( $this->data['layers'] );

			foreach ( $layers as $layer ) {
				$layers_output .= $this->create_layer( $layer );
			}

			return $layers_output;
		}

		private function create_layer( $data ) {
			$layer = BQW_AS_Public_Layer_Factory::create_layer( $data );
			return $layer->render();
		}
	}
?>