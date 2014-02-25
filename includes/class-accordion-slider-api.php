<?php

class BQW_Accordion_Slider_API {

	const ACCORDION_SLIDER_API = 'http://bqworks.com/api/accordion-slider/';

	protected static $instance = null;

	private function __construct() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_check' ) );
		add_filter( 'plugins_api', array( $this, 'update_info' ), 10, 3 );
		add_action( 'in_plugin_update_message-accordion-slider/accordion-slider.php', array( $this, 'update_notification_message' ) );
	}

	/*
		Return the instance of the class
	*/
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/*
		Makes requests to the server's update API
	*/
	public function api_request( $args ) {
		$request = wp_remote_post( self::ACCORDION_SLIDER_API, array( 'body' => $args ) );

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			return false;
		}

		$response = unserialize( wp_remote_retrieve_body( $request ) );
		
		if ( is_object( $response ) ) {
			return $response;
		} else {
			return false;
		}
	}

	/*
		When the update cycle runs, if there is any Accordion Slider update available append its information
	*/
	public function update_check( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$slug = 'accordion-slider/accordion-slider.php';
		$current_version = $transient->checked[ $slug ];
		
		$args = array(
			'action' => 'update-check',
			'slug' => $slug
		);

		$response = $this->api_request( $args );
		
		if ( $response !== false && version_compare( $current_version, $response->new_version, '<' ) )	{	
			$transient->response[ $slug ] = $response;
		}

		return $transient;
	}

	
	public function update_info( $false, $action, $args ) {
		$slug = 'accordion-slider/accordion-slider.php';
		
		// return if the Accordion Slider plugin info is not requested
		if ( ! isset( $args->slug ) || $args->slug !== $slug ) {
			return $false;
		}

		$args = array(
			'action' => 'plugin-info',
			'slug' => $slug
		);

		$response = $this->api_request( $args );
		
		if ( $response !== false ) {	
			return $response;
		} else {
			return $false;
		}
	}

	/*
		Display the update notification message
		Appends a custom message, if any, to the default message
	*/
	public function update_notification_message() {
		$message = get_transient( 'accordion_slider_update_notification_message' );
		
		// if the message has expired, interrogate the server
		if ( $message === false ) {
			$args = array(
				'action' => 'notification-message',
				'slug' => 'accordion-slider/accordion-slider.php'
			);
			
			$response = $this->api_request( $args );
			
			if ( $response !== false ) {
				$message = $response->notification_message;
				
				// store the message in a transient for 12 hours
				set_transient( 'accordion_slider_update_notification_message', $message, 60 * 60 * 12 );
			}
		}
		
		echo $message;
	}

	public function verify_purchase_code( $purchase_code ) {
		$args = array(
			'action' => 'verify-purchase',
			'slug' => 'accordion-slider/accordion-slider.php',
			'purchase_code' => $purchase_code
		);

		$response = $this->api_request( $args );
		
		if ( $response !== false && isset( $response->is_valid ) && $response->is_valid === 'yes' ) {
			return true;
		} else {
			return false;
		}
	}
}