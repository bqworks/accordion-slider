<?php
/**
 * Handles options and database changes in updates
 * 
 * @since 1.0.5
 */
class BQW_Accordion_Slider_Updates {

	/**
	 * Current version number in the database
	 * 
	 * @since 1.0.5
	 * 
	 * @var string
	 */
	protected $db_version; 

	/**
	 * @since 1.0.5
	 */
	public function __construct() {
		
	}

	/**
	 * Do database modifications if necessary.
	 *
	 * @since 1.0.5
	 */
	public function init() {
		$this->db_version = get_option( 'accordion_slider_version', '1.0.0' );

		if ( version_compare( $this->db_version, '1.0.5', '<' ) ) {
			$this->update_to_105();
		}
	}

	/**
	 * Update to version 1.0.5.
	 *
	 * @since 1.0.5
	 */
	public function update_to_105() {
		global $wpdb;
		$prefix = $wpdb->prefix;

		$accordions = $wpdb->get_results( "SELECT * FROM " . $prefix . "accordionslider_accordions" );
		
		if ( count( $accordions ) > 0 ) {
			foreach ( $accordions as $accordion ) {
				$accordion_id = $accordion->id;
				$accordion_settings = json_decode( stripslashes( $accordion->settings ) );

				if ( $accordion_settings->start_panel == 0 ) {
					$accordion_settings->start_panel = -1;

					$wpdb->update($wpdb->prefix . 'accordionslider_accordions', array( 'settings' => json_encode( $accordion_settings ) ), 
																				array( 'id' => $accordion_id ),
																				array( '%s' ),
																				array( '%d' ) );
				}
			}
		}
	}
}