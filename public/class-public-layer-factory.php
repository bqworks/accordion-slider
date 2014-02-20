<?php
	class BQW_AS_Public_Layer_Factory {

		protected static $registered_types = array(
			'paragraph' => 'BQW_AS_Public_Paragraph_Layer',
			'heading' => 'BQW_AS_Public_Heading_Layer',
			'image' => 'BQW_AS_Public_Image_Layer',
			'div' => 'BQW_AS_Public_Div_Layer',
			'video' => 'BQW_AS_Public_Video_Layer'
		);

		protected static $default_type = null;

		public static function create_layer( $data ) {
			if ( is_null( self::$default_type ) ) {
				$default_settings = Accordion_Slider_Settings::getLayerSettings();
				self::$default_type = $default_settings['type']['default_value'];
			}

			$type = isset( $data['type'] ) ? $data['type'] : self::$default_type;

			foreach( self::$registered_types as $registered_type_name => $registered_type_class ) {
				if ( $type === $registered_type_name ) {
					return new $registered_type_class( $data );
				}
			}
		}

		public static function register_layer_type( $type_name, $type_class ) {
			self::$registered_types[ $type_name ] = $type_class;
		}

	}
?>