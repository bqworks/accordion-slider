<?php
/**
 * Calls the plugin's remote API for getting update information.
 * 
 * @since 1.0.0
 */
class BQW_Accordion_Slider_API {

	/**
	 * The address of the remote API.
	 * 
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const ACCORDION_SLIDER_API = 'http://api.bqworks.net/accordion-slider/';

	/**
	 * Current class instance.
	 * 
	 * @since 1.0.0
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Plugin slug.
	 * 
	 * @since 1.0.0
	 * 
	 * @var string
	 */
	protected $slug = 'accordion-slider';

	/**
	 * Plugin reference.
	 * 
	 * @since 1.4.0
	 * 
	 * @var string
	 */
	protected $plugin_reference = 'accordion-slider/accordion-slider.php';

	/**
	 * Initialize the API handling
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_check' ) );
		add_filter( 'plugins_api', array( $this, 'update_info' ), 10, 3 );
		add_action( 'in_plugin_update_message-' . $this->slug, array( $this, 'update_notification_message' ) );
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 1.0.0
	 * 
	 * @return object The instance of the current class.
	 */
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Makes requests to the remote API
	 *
	 * @since 1.0.0
	 *
	 * @param  $args       The data posted with the server request.
	 * @return object|bool The server response, or false if there was an error or no data is sent.
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

	/**
	 * Append the plugin's information, if there's a new update available
	 *
	 * Sends a request to the server to get the plugin's latest information.
	 * If the version received from the API call is superior to the one 
	 * stored in the transient, mark the plugin as updateable by storing
	 * the data received from the server in the transient.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object $transient Object containing the list of all checked plugins
	 *                           and the list of updateable plugins with their information.
	 * 
	 * @return object            Same as above.
	 */
	public function update_check( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		
		$args = array(
			'action' => 'update-check'
		);

		$response = $this->api_request( $args );
		
		if ( $response !== false && version_compare( BQW_Accordion_Slider::VERSION, $response->new_version, '<' ) )	{
			$transient->response[ $this->plugin_reference ] = $response;
		}

		return $transient;
	}

	/**
	 * Return server information about the latest available plugin
	 * version.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object $false  empty
	 * @param  string $action 'plugin_information'
	 * @param  object $args   The slug and other data about the current plugin
	 * @return object         Data from the server about the plugin
	 */
	public function update_info( $false, $action, $args ) {
		$slug = $this->slug;

		// return if the Accordion Slider plugin info is not requested
		if ( ! isset( $args->slug ) || $args->slug !== $slug ) {
			return $false;
		}

		$args = array(
			'action' => 'plugin-info'
		);

		$response = $this->api_request( $args );
		
		if ( $response !== false ) {	
			return $response;
		} else {
			return $false;
		}
	}

	/**
	 * Return the update notification message.
	 *
	 * Checks the transient for a cached message and sends a new
	 * request to the server if no cached message is found.
	 *
	 * @since 1.0.0
	 * 
	 * @return string The message to be displayed.
	 */
	public function update_notification_message() {
		$message = get_transient( 'accordion_slider_update_notification_message' );
		
		if ( $message === false ) {
			$args = array(
				'action' => 'notification-message'
			);
			
			$response = $this->api_request( $args );
			
			if ( $response !== false ) {
				$message = $response->notification_message;
				
				set_transient( 'accordion_slider_update_notification_message', $message, 60 * 60 * 12 );
			}
		}
		
		echo $message;
	}
}