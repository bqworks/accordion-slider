<?php
	class BQW_AS_Panel_Renderer_Factory {

		protected static $registered_types = array(
			'custom' => 'BQW_AS_Panel_Renderer',
			'posts' => 'BQW_AS_Posts_Panel_Renderer',
			'gallery' => 'BQW_AS_Gallery_Panel_Renderer',
			'flickr' => 'BQW_AS_Flickr_Panel_Renderer'
		);

		protected static $default_type = null;

		public static function create_panel( $data ) {
			if ( is_null( self::$default_type ) ) {
				$default_settings = BQW_Accordion_Slider_Settings::getPanelSettings();
				self::$default_type = $default_settings['content_type']['default_value'];
			}

			$type = isset( $data['settings']['content_type'] ) ? $data['settings']['content_type'] : self::$default_type;

			foreach( self::$registered_types as $registered_type_name => $registered_type_class ) {
				if ( $type === $registered_type_name ) {
					return new $registered_type_class();

				}
			}
		}

		public static function register_panel_type( $type_name, $type_class ) {
			self::$registered_types[ $type_name ] = $type_class;
		}

	}
?>