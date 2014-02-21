<?php
	class BQW_AS_Public_Panel_Factory {

		protected static $registered_types = array(
			'custom' => 'BQW_AS_Public_Panel',
			'posts' => 'BQW_AS_Public_Posts_Panel',
			'gallery' => 'BQW_AS_Public_Gallery_Panel',
			'flickr' => 'BQW_AS_Public_Flickr_Panel'
		);

		protected static $default_type = null;

		public static function create_panel( $data, $accordion_id, $panel_index, $lazy_loaded ) {
			if ( is_null( self::$default_type ) ) {
				$default_settings = BQW_Accordion_Slider_Settings::getPanelSettings();
				self::$default_type = $default_settings['content_type']['default_value'];
			}

			$type = isset( $data['settings']['content_type'] ) ? $data['settings']['content_type'] : self::$default_type;

			foreach( self::$registered_types as $registered_type_name => $registered_type_class ) {
				if ( $type === $registered_type_name ) {
					return new $registered_type_class( $data, $accordion_id, $panel_index, $lazy_loaded );
				}
			}
		}

		public static function register_panel_type( $type_name, $type_class ) {
			self::$registered_types[ $type_name ] = $type_class;
		}

	}
?>