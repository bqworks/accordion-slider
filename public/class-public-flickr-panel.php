<?php
	class BQW_AS_Public_Flickr_Panel extends BQW_AS_Public_Dynamic_Panel {

		protected $input_html = null;

		protected $flickr_instance = null;

		public function __construct( $data, $lazy_loading ) {
			parent::__construct( $data, $lazy_loading );

			$this->registered_tags = array(
				'image' => array( $this, 'render_image' ),
				'image_src' => array( $this, 'render_image_src' ),
				'image_description' => array( $this, 'render_image_description' ),
				'image_link' => array( $this, 'render_image_link' ),
				'date' => array( $this, 'render_date' ),
				'username' => array( $this, 'render_username' ),
				'user_link' => array( $this, 'render_user_link' )
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
			$loaded_photos = null;
			$collection_name = null;

			$api_key = $this->get_setting_value('flickr_api_key');

			if ( $api_key !== '' ) {
				$this->flickr_instance = new bqworks_Flickr( $api_key );
			} else {
				return false;
			}

			$data_type = $this->get_setting_value( 'flickr_load_by' );
			$data_id = $this->get_setting_value( 'flickr_id' );
			$limit = $this->get_setting_value( 'flickr_per_page' );

			if ( $data_type === 'set_id' ) {
				$loaded_photos = $this->flickr_instance->get_photos_by_set_id( $data_id, 'description,date_upload,owner_name', $limit );
				$collection_name = 'photoset';
			} else if ( $data_type === 'user_id' ) {
				$loaded_photos = $this->flickr_instance->get_photos_by_user_id( $data_id, 'description,date_upload,owner_name', $limit );
				$collection_name = 'photos';
			}

			$photos = $loaded_photos[ $collection_name ]['photo'];

			foreach ( $photos as &$photo ) {
				$photo['owner'] = $collection_name === 'photoset' ? $loaded_photos['photoset']['owner'] : $photo['owner'];
			}

			return $photos;
		}

		protected function replace_tags( $photos ) {
			$output_html = '';
			$tags = $this->get_panel_tags();

			foreach ( $photos as $photo ) {
				$content = $this->input_html;

				foreach ( $tags as $tag ) {
					$content = $this->render_tag( $content, $tag['full'], $tag['name'], $tag['arg'], $photo );
				}

				$output_html .= $content;
			}

			return $output_html;
		}

		protected function render_image( $content, $tag_full, $tag_arg, $photo ) {
			$image_size = $tag_arg !== false ? $tag_arg : 'medium';
			$image_src = $this->flickr_instance->get_photo_url( $photo, $image_size );
			$image_full = '<img src="' . $image_src . '" />';

			return str_replace( $tag_full, $image_full, $content );
		}

		protected function render_image_src( $content, $tag_full, $tag_arg, $photo ) {
			$image_size = $tag_arg !== false ? $tag_arg : 'medium';
			$image_src = $this->flickr_instance->get_photo_url( $photo, $image_size );

			return str_replace( $tag_full, $image_src, $content );
		}

		protected function render_image_description( $content, $tag_full, $tag_arg, $photo ) {
			return str_replace( $tag_full, $photo['description']['_content'], $content );
		}

		protected function render_image_link( $content, $tag_full, $tag_arg, $photo ) {
			return str_replace( $tag_full, 'http://www.flickr.com/photos/' . $photo['owner'] . '/' . $photo['id'] . '/', $content );
		}

		protected function render_date( $content, $tag_full, $tag_arg, $photo ) {
			return str_replace( $tag_full, date( 'F j Y', $photo['dateupload'] ), $content );
		}

		protected function render_username( $content, $tag_full, $tag_arg, $photo ) {
			return str_replace( $tag_full, $photo['ownername'], $content );
		}

		protected function render_user_link( $content, $tag_full, $tag_arg, $photo ) {
			return str_replace( $tag_full, 'http://www.flickr.com/people/' . $photo['owner'] . '/', $content );
		}
	}
?>