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
	const ACCORDION_SLIDER_API = 'http://api.bqworks.com/accordion-slider/';

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
	 * The plugin's purchase code received from envato.
	 * 
	 * @since 1.0.0
	 * 
	 * @var string
	 */
	protected $purchase_code = null;

	/**
	 * The status of the purchase code.
	 *
	 * Can be 0, if the purcahse code is empty, 1 if the purchase 
	 * code is valid, or 2 if the purchase code is not valid.
	 * 
	 * @since 1.0.0
	 * 
	 * @var string
	 */
	protected $purchase_code_status = null;

	/**
	 * Initialize the API handling
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->purchase_code = get_option( 'accordion_slider_purchase_code', '' );
		$this->purchase_code_status = get_option( 'accordion_slider_purchase_code_status', '0' );

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
			'action' => 'update-check',
			'purchase_code' => $this->purchase_code,
			'purchase_code_status' => $this->purchase_code_status
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
			'action' => 'plugin-info',
			'purchase_code' => $this->purchase_code,
			'purchase_code_status' => $this->purchase_code_status
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
	 * Also, if the purchase code was not entered or is not valid, 
	 * append a text to the update message that prompts the user
	 * to enter the purchase code.
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

		if ( $this->purchase_code_status !== '1' ) {
			$message = 
				__( ' To activate automatic updates, you need to enter your purchase code ', 'accordion-slider' ) . 
				'<a href="' . admin_url( 'admin.php?page=accordion-slider-settings' ) . '">' . 
					__( 'here', 'accordion-slider' ) . 
				'</a>.<br/> ' . 
				$message;
		}
		
		echo $message;
	}

	/**
	 * Verify the purchase code.
	 *
	 * Sends the purchase code to the remote API to verify
	 * if it's valid. This verification is merely done to 
	 * display useful information for the user, like the validity
	 * of the purchase code, or in order to decide if the update button
	 * should be displayed.
	 * 
	 * (In order to actually do an update, the purchase code is
	 * verified once again, server-side, and the new version
	 * is served only if the purchase code is found to be valid there.)
	 *
	 * Also, it deletes the transient that stores the plugin's 
	 * update notification message because changes in the purchase
	 * code status need to be reflected in the message. 
	 * 
	 * It also deletes the 'update_plugins' transient because
	 * the transient may include the download link for the plugin.
	 * If the purchase code is valid, the download link is included
	 * in the transient, but if the purchase code is not valid,
	 * the download link is not included. The addition, if it's needed,
	 * is done when the 'pre_set_site_transient_update_plugins' filter runs.
	 *
	 * @since 1.0.0
	 * 
	 * @param  string $purchase_code The entered purchase code.
	 * @return bool                  Whether or not the purchase code is valid.
	 */
	public function verify_purchase_code( $purchase_code ) {
		$args = array(
			'action' => 'verify-purchase',
			'purchase_code' => $purchase_code
		);

		$response = $this->api_request( $args );

		delete_site_transient( 'update_plugins' );
		delete_transient( 'accordion_slider_update_notification_message' );

		if ( $response !== false && isset( $response->is_valid ) ) {
			if ( $response->is_valid === 'yes' ) {
				return 'yes';
			} else {
				return 'no';
			}
		} else {
			return 'error';
		}
	}
}