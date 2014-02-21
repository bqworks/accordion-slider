<?php
	class BQW_AS_Public_Gallery_Panel extends BQW_AS_Public_Dynamic_Panel {

		protected $input_html = null;

		public function __construct() {
			parent::__construct();

			$this->registered_tags = array(
				'image' => array( $this, 'render_image' ),
				'image_src' => array( $this, 'render_image_src' ),
				'image_alt' => array( $this, 'render_image_alt' ),
				'image_title' => array( $this, 'render_image_title' ),
				'image_description' => array( $this, 'render_image_description' )
			);

			$this->registered_tags = apply_filters( 'accordion_slider_gallery_tags', $this->registered_tags );
		}

		public function render() {
			$this->input_html = parent::render();
			$output_html = '';
			
			$result = $this->get_gallery_images();
			$output_html = $this->replace_tags( $result );

			return $output_html;
		}

		protected function get_gallery_images() {
			global $post;

			$pattern = get_shortcode_regex();

			preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches, PREG_SET_ORDER );

			$images = array();

			foreach ( $matches as $match ) {
				if ( $match[2] !== 'gallery' ) {
					continue;
				}

				$atts = shortcode_parse_atts( $match[3] );

				if ( ! isset( $atts[ 'ids' ] ) ) {
					continue;
				}

				$ids = explode( ',', $atts[ 'ids' ] );

				foreach ( $ids as $id ) {
					$image = get_post( $id );
					$image_alt = get_post_meta( $id, '_wp_attachment_image_alt' );
					$image->alt = ! empty( $image_alt ) ? $image_alt[0] : '';

					array_push( $images, $image );
				}
			}

			return $images;
		}

		protected function replace_tags( $images ) {
			$output_html = '';
			$tags = $this->get_panel_tags();

			foreach ( $images as $image ) {
				$content = $this->input_html;

				foreach ( $tags as $tag ) {
					$result = $this->render_tag( $tag['name'], $tag['arg'], $image );
					$content = str_replace( $tag['full'], $result, $content );
				}

				$output_html .= $content;
			}

			return $output_html;
		}

		protected function render_image( $tag_arg, $image ) {
			$image_size = $tag_arg !== false ? $tag_arg : 'full';
			$image_full = wp_get_attachment_image( $image->ID, $image_size );

			return $image_full;
		}

		protected function render_image_src( $tag_arg, $image ) {
			$image_size = $tag_arg !== false ? $tag_arg : 'full';
			$image_src = wp_get_attachment_image_src( $image->ID, $image_size );

			return $image_src[0];
		}

		protected function render_image_alt( $tag_arg, $image ) {
			return $image->alt;
		}

		protected function render_image_title( $tag_arg, $image ) {
			return $image->post_title;
		}

		protected function render_image_description( $tag_arg, $image ) {
			return $image->post_content;
		}
	}
?>