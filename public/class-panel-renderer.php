<?php
	class BQW_AS_Panel_Renderer {

		protected $data = null;

		protected $accordion_id = null;

		protected $panel_index = null;

		protected $lazy_loading = null;

		protected $html_output = '';

		public function __construct() {
			
		}

		public function set_data( $data, $accordion_id, $panel_index, $lazy_loading ) {
			$this->data = $data;
			$this->accordion_id = $accordion_id;
			$this->panel_index = $panel_index;
			$this->lazy_loading = $lazy_loading;
		}

		public function render() {
			$classes = 'as-panel';
			$classes = apply_filters( 'accordion_slider_panel_classes' , $classes, $this->accordion_id, $this->panel_index );

			$this->html_output = "\r\n" . '		<div class="' . $classes . '">';

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

			$this->html_output = apply_filters( 'accordion_slider_panel_markup', $this->html_output, $this->accordion_id, $this->panel_index );

			return $this->html_output;
		}

		protected function has_background_image() {
			if ( isset( $this->data['background_source'] ) && $this->data['background_source'] !== '' ) {
				return true;
			}

			return false;
		}

		protected function create_background_image() {
			$background_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'accordion-slider/public/assets/css/images/blank.gif' ) . '" data-src="' . esc_attr( $this->data['background_source'] ) . '"' : ' src="' . esc_attr( $this->data['background_source'] ) . '"';
			$background_alt = isset( $this->data['background_alt'] ) && $this->data['background_alt'] !== '' ? ' alt="' . esc_attr( $this->data['background_alt'] ) . '"' : '';
			$background_title = isset( $this->data['background_title'] ) && $this->data['background_title'] !== '' ? ' title="' . esc_attr( $this->data['background_title'] ) . '"' : '';
			$background_width = isset( $this->data['background_width'] ) && $this->data['background_width'] != 0 ? ' width="' . esc_attr( $this->data['background_width'] ) . '"' : '';
			$background_height = isset( $this->data['background_height'] ) && $this->data['background_height'] != 0 ? ' height="' . esc_attr( $this->data['background_height'] ) . '"' : '';
			$background_retina_source = isset( $this->data['background_retina_source'] ) && $this->data['background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['background_retina_source'] ) . '"' : '';
			$background_image = '<img class="as-background"' . $background_source . $background_retina_source . $background_alt . $background_title . $background_width . $background_height . ' />';

			return $background_image;
		}

		protected function has_opened_background_image() {
			if ( isset( $this->data['opened_background_source'] ) && $this->data['opened_background_source'] !== '' ) {
				return true;
			}

			return false;
		}

		protected function create_opened_background_image() {
			$opened_background_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'accordion-slider/public/assets/css/images/blank.gif' ) . '" data-src="' . esc_attr( $this->data['opened_background_source'] ) . '"' : ' src="' . esc_attr( $this->data['opened_background_source'] ) . '"';
			$opened_background_alt = isset( $this->data['opened_background_alt'] ) && $this->data['opened_background_alt'] !== '' ? ' alt="' . esc_attr( $this->data['opened_background_alt'] ) . '"' : '';
			$opened_background_title = isset( $this->data['opened_background_title'] ) && $this->data['opened_background_title'] !== '' ? ' title="' . esc_attr( $this->data['opened_background_title'] ) . '"' : '';
			$opened_background_width = isset( $this->data['opened_background_width'] ) && $this->data['opened_background_width'] != 0 ? ' width="' . esc_attr( $this->data['opened_background_width'] ) . '"' : '';
			$opened_background_height = isset( $this->data['opened_background_height'] ) && $this->data['opened_background_height'] != 0 ? ' height="' . esc_attr( $this->data['opened_background_height'] ) . '"' : '';
			$opened_background_retina_source = isset( $this->data['opened_background_retina_source'] ) && $this->data['opened_background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['opened_background_retina_source'] ) . '"' : '';
			$opened_background_image = '<img class="as-background-opened"' . $opened_background_source . $opened_background_retina_source . $opened_background_alt . $opened_background_title . $opened_background_width . $opened_background_height . ' />';
		
			return $opened_background_image;
		}

		protected function has_background_link() {
			if ( isset( $this->data['background_link'] ) && $this->data['background_link'] !== '' ) {
				return true;
			} 

			return false;
		}

		protected function add_link_to_background_image( $image ) {
			$background_link_href = $this->data['background_link'];
			$background_link_href = apply_filters( 'accordion_slider_slide_link_url', $background_link_href );

			$background_link_title = isset( $this->data['background_link_title'] ) && $this->data['background_link_title'] !== '' ? ' title="' . esc_attr( $this->data['background_link_title'] ) . '"' : '';
			$background_link = '<a href="' . $background_link_href . '"' . $background_link_title . '>' . $image . '</a>';
			
			return $background_link;
		}

		protected function has_html() {
			if ( isset( $this->data['html'] ) && $this->data['html'] !== '' ) {
				return true;
			} 

			return false;
		}

		protected function create_html() {
			$html = $this->data['html'];
			$html = apply_filters( 'accordion_slider_slide_html', $html );

			return $html;
		}

		protected function has_layers() {
			if ( isset( $this->data['layers'] ) && ! empty( $this->data['layers'] ) ) {
				return true;
			}

			return false;
		}

		protected function create_layers() {
			$layers_output = '';
			$layers = array_reverse( $this->data['layers'] );

			foreach ( $layers as $layer ) {
				$layers_output .= $this->create_layer( $layer );
			}

			return $layers_output;
		}

		protected function create_layer( $data ) {
			$layer = BQW_AS_Layer_Renderer_Factory::create_layer( $data );
			$layer->set_data( $data, $this->accordion_id, $this->panel_index );
			return $layer->render();
		}
	}
?>