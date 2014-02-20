<?php
	class BQW_AS_Public_Posts_Panel extends BQW_AS_Public_Dynamic_Panel {

		protected $input_html = null;

		public function __construct( $data, $lazy_loading ) {
			parent::__construct( $data, $lazy_loading );

			$this->registered_tags = array(
				'image' => array( $this, 'render_image' ),
				'image_src' => array( $this, 'render_image_src' ),
				'image_alt' => array( $this, 'render_image_alt' ),
				'image_title' => array( $this, 'render_image_title' ),
				'image_description' => array( $this, 'render_image_description' ),
				'image_caption' => array( $this, 'render_image_caption' ),
				'title' => array( $this, 'render_title' ),
				'link' => array( $this, 'render_link' ),
				'link_url' => array( $this, 'render_link_url' ),
				'date' => array( $this, 'render_date' ),
				'excerpt' => array( $this, 'render_excerpt' ),
				'content' => array( $this, 'render_content' ),
				'category' => array( $this, 'render_category' ),
				'custom' => array( $this, 'render_custom' )
			);
		}

		public function render() {
			$this->input_html = parent::render();
			$output_html = '';
			
			$result = $this->query();
			$output_html = $this->replace_tags( $result );

			return $output_html;
		}

		protected function query() {
			$query_args = array();

			$post_types = $this->get_setting_value( 'posts_post_types' );

			if ( ! empty( $post_types ) ) {
				$query_args['post_type'] = $post_types;
			}

			$taxonomies = $this->get_setting_value( 'posts_taxonomies' );

			if ( ! empty( $taxonomies ) ) {
				$tax_query = array();

				foreach ( $taxonomies as $taxonomy_term_raw ) {
					$taxonomy_term = explode( '|', $taxonomy_term_raw );
					
					$tax_item['taxonomy'] = $taxonomy_term[0];
					$tax_item['terms'] = $taxonomy_term[1];
					$tax_item['field'] = 'slug';
					
					$tax_item['operator'] = $this->get_setting_value( 'posts_operator' );
					
					array_push( $tax_query, $tax_item );
				}
				
				if ( count( $taxonomies ) > 1 ) {
					$tax_query['relation'] = $this->get_setting_value( 'posts_relation' );
				}

				$query_args['tax_query'] = $tax_query;
			}
			
			$query_args['posts_per_page'] = $this->get_setting_value( 'posts_maximum' );
			$query_args['orderby'] = $this->get_setting_value( 'posts_order_by' );
			$query_args['order'] = $this->get_setting_value( 'posts_order' );
			
			$query = new WP_Query( $query_args );

			return $query;
		}

		protected function replace_tags( $query ) {
			$output_html = '';
			$tags = $this->get_panel_tags();

			while ( $query->have_posts() ) {
				$query->the_post();

				global $post;

				$content = $this->input_html;

				foreach ( $tags as $tag ) {
					$content = $this->render_tag( $content, $tag['full'], $tag['name'], $tag['arg'], $post );
				}

				$output_html .= $content;
			}

			wp_reset_postdata();

			return $output_html;
		}

		protected function render_image( $content, $tag_full, $tag_arg, $post ) {
			fb('test');
			if ( ! has_post_thumbnail( $post->ID ) ) {
				return;
			}

			$image_size = $tag_arg !== false ? $tag_arg : 'full';
			$image_full = get_the_post_thumbnail( $post->ID, $image_size, array( 'class' => '' ) );

			return str_replace( $tag_full, $image_full, $content );
		}

		protected function render_image_src( $content, $tag_full, $tag_arg, $post ) {
			if ( ! has_post_thumbnail( $post->ID ) ) {
				return;
			}

			$image_size = $tag_arg !== false ? $tag_arg : 'full';
			$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $image_size );

			return str_replace( $tag_full, $image_src[0], $content );
		}

		protected function render_image_alt( $content, $tag_full, $tag_arg, $post ) {
			if ( ! has_post_thumbnail( $post->ID ) ) {
				return;
			}

			$image_alt = get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt' );
			$image_alt_value = ! empty ( $image_alt ) ? $image_alt[0] : '';

			return str_replace( $tag_full, $image_alt_value, $content );
		}

		protected function render_image_title( $content, $tag_full, $tag_arg, $post ) {
			if ( ! has_post_thumbnail( $post->ID ) ) {
				return;
			}

			$image_data = get_post( get_post_thumbnail_id(), ARRAY_A );

			return str_replace( $tag_full, $image_data['post_title'], $content );
		}

		protected function render_image_description( $content, $tag_full, $tag_arg, $post ) {
			if ( ! has_post_thumbnail( $post->ID ) ) {
				return;
			}

			$image_data = get_post( get_post_thumbnail_id(), ARRAY_A );

			return str_replace( $tag_full, $image_data['post_content'], $content );
		}

		protected function render_image_caption( $content, $tag_full, $tag_arg, $post ) {
			if ( ! has_post_thumbnail( $post->ID ) ) {
				return;
			}

			$image_data = get_post( get_post_thumbnail_id(), ARRAY_A );

			return str_replace( $tag_full, $image_data['post_excerpt'], $content );
		}

		protected function render_title( $content, $tag_full, $tag_arg, $post ) {
			return str_replace( $tag_full, get_the_title(), $content );
		}

		protected function render_link( $content, $tag_full, $tag_arg, $post ) {
			$link = '<a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a>';

			return str_replace( $tag_full, $link, $content );
		}

		protected function render_link_url( $content, $tag_full, $tag_arg, $post ) {
			return str_replace( $tag_full, get_permalink( $post->ID ), $content );
		}

		protected function render_date( $content, $tag_full, $tag_arg, $post ) {
			$date_format = $tag_arg !== false ? $tag_arg : get_option( 'date_format' );
			
			return str_replace( $tag_full, get_post_time( $date_format, false, $post->ID ), $content );
		}

		protected function render_excerpt( $content, $tag_full, $tag_arg, $post ) {
			return str_replace( $tag_full, $post->post_excerpt, $content );
		}

		protected function render_content( $content, $tag_full, $tag_arg, $post ) {
			return str_replace( $tag_full, $post->post_content, $content );
		}

		protected function render_category( $content, $tag_full, $tag_arg, $post ) {
			$categories = get_the_category( $post->ID );
			$category = ! empty( $categories ) ? $categories[0]->name : '';

			return str_replace( $tag_full, $category, $content );
		}

		protected function render_custom( $content, $tag_full, $tag_arg, $post ) {
			$value = '';

			if ( $tag_arg !== false ) {
				$values = get_post_meta( $post->ID, $tag_arg );
				$value = ! empty( $values ) ? $values[0] : '';
			}

			return str_replace( $tag_full, $value, $content );
		}
	}
?>