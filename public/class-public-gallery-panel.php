<?php
	class BQW_AS_Public_Gallery_Panel extends BQW_AS_Public_Dynamic_Panel {

		protected $input_html = null;

		public function __construct( $data, $accordion_id, $panel_index, $lazy_loading ) {
			parent::__construct( $data, $accordion_id, $panel_index, $lazy_loading );

			$this->registered_tags = array(
				'image' => array( $this, 'render_image' ),
				'image_src' => array( $this, 'render_image_src' ),
				'image_alt' => array( $this, 'render_image_alt' ),
				'image_title' => array( $this, 'render_image_title' ),
				'image_description' => array( $this, 'render_image_description' )
			);
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
					$image = get_post( $id, ARRAY_A );
					$image_alt = get_post_meta( $id, '_wp_attachment_image_alt' );
					$image['alt'] = ! empty( $image_alt ) ? $image_alt[0] : '';

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
					$content = $this->render_tag( $content, $tag['full'], $tag['name'], $tag['arg'], $image );
				}

				$output_html .= $content;
			}

			return $output_html;
		}

		protected function render_image( $content, $tag_full, $tag_arg, $image ) {
			$image_size = $tag_arg !== false ? $tag_arg : 'full';
			$image_full = wp_get_attachment_image( $image['ID'], $image_size );

			return str_replace( $tag_full, $image_full, $content );
		}

		protected function render_image_src( $content, $tag_full, $tag_arg, $image ) {
			$image_size = $tag_arg !== false ? $tag_arg : 'full';
			$image_src = wp_get_attachment_image_src( $image['ID'], $image_size );

			return str_replace( $tag_full, $image_src[0], $content );
		}

		protected function render_image_alt( $content, $tag_full, $tag_arg, $image ) {
			return str_replace( $tag_full, $image['alt'], $content );
		}

		protected function render_image_title( $content, $tag_full, $tag_arg, $image ) {
			return str_replace( $tag_full, $image['post_title'], $content );
		}

		protected function render_image_description( $content, $tag_full, $tag_arg, $image ) {
			return str_replace( $tag_full, $image['post_content'], $content );
		}
	}
?>