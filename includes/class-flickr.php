<?php

if ( class_exists( 'BQW_Flickr' ) === false ) {
	class BQW_Flickr {

		protected $rest = 'https://api.flickr.com/services/rest/';

		protected $api_key = '';

		protected $photo_sizes = array(
			'square' => '_s',
			'thumbnail' => '_t',
			'small' => '_m',
			'medium' => '',
			'medium_640' => '_z',
			'large' => '_b'
		);

		public function __construct( $api_key ) {
			$this->api_key = $api_key;
		}

		public function get_photos_by_user_id( $user_id, $extras = NULL, $per_page = NULL ) {
			return $this->request( 'flickr.people.getPublicPhotos', array( 'user_id' => $user_id, 'extras' => $extras, 'per_page' => $per_page ) );
		}

		public function get_photos_by_set_id( $set_id, $extras = NULL, $per_page = NULL ) {
			return $this->request( 'flickr.photosets.getPhotos', array( 'photoset_id' => $set_id, 'extras' => $extras, 'per_page' => $per_page ) );
		}

		public function get_photo_url( $photo, $size = 'medium' ) {
			return 'http://farm' . $photo['farm'] . '.staticflickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'] . $this->photo_sizes[ $size ] . '.jpg';
		}

		protected function request( $method, $args = array() ) {
			$data = array_merge( array( 'method' => $method, 'api_key' => $this->api_key, 'format' => 'php_serial' ), $args );
			$response = $this->post( $data );
			$parsed_response = unserialize( $response );

			if ( $parsed_response['stat'] === 'ok' )
				return $parsed_response;
			else
				return false;
		}

		protected function post( $data ) {
			$curl = curl_init( $this->rest );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_TIMEOUT, 5 );
			$response = curl_exec( $curl );
			curl_close( $curl ); 

			return $response;
		}
	}
}