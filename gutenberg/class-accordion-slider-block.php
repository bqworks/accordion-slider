<?php
/**
 * Handles the server side functionality of the Accordion Slider Gutenberg block.
 * 
 * @since 1.9.3
 */
class BQW_Accordion_Slider_Block {

	/**
	 * Current class instance.
	 * 
	 * @since 1.9.3
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Add initialization logic for the block.
	 *
	 * @since 1.9.3
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 1.9.3
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
	 * Register the block using the block.json file.
	 * Register a route that will allow the fetching of some slider data (name and id).
	 *
	 * @since 1.9.3
	 */
	public function init() {
		if ( ! function_exists( 'register_block_type' ) || ! function_exists( 'register_rest_route' ) ) {
			return;
		}

		register_block_type( __DIR__ . '/build' );

		add_action( 'rest_api_init', function() {
			register_rest_route( 'accordion-slider/v1', '/accordions', array(
				'method' => 'GET',
				'callback' => array( $this, 'get_accordions' ),
				'permission_callback' => '__return_true'
			));
		} );

		wp_localize_script( 'bqworks-accordion-slider-editor-script', 'as_gutenberg_js_vars', array(
			'admin_url' => admin_url( 'admin.php' )
		));
	}

	/**
	 * Endpoint for the 'accordion-slider/v1/accordions' route that returns
	 * the id and name of the sliders.
	 *
	 * @since 1.9.3
	 */
	public function get_accordions( $request ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$response = array();

		$accordions = $wpdb->get_results( "SELECT * FROM " . $prefix . "accordionslider_accordions ORDER BY id" );

		foreach ( $accordions as $accordion ) {
			$accordion_id = $accordion->id;
			$accordion_name = stripslashes( $accordion->name );
			
			$response[ $accordion_id ] = $accordion_name;
		}
		
		return rest_ensure_response( $response );
	}
}