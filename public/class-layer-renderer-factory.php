<?php
	class BQW_AS_Layer_Renderer_Factory {

		protected static $registered_types = array(
			'paragraph' => 'BQW_AS_Paragraph_Layer_Renderer',
			'heading' => 'BQW_AS_Heading_Layer_Renderer',
			'image' => 'BQW_AS_Image_Layer_Renderer',
			'div' => 'BQW_AS_Div_Layer_Renderer',
			'video' => 'BQW_AS_Video_Layer_Renderer'
		);

		protected static $default_type = null;

		public static function create_layer( $data ) {
			if ( is_null( self::$default_type ) ) {
				$default_settings = BQW_Accordion_Slider_Settings::getLayerSettings();
				self::$default_type = $default_settings['type']['default_value'];
			}

			$type = isset( $data['type'] ) ? $data['type'] : self::$default_type;

			foreach( self::$registered_types as $registered_type_name => $registered_type_class ) {
				if ( $type === $registered_type_name ) {
					return new $registered_type_class();
				}
			}
		}

		public static function register_layer_type( $type_name, $type_class ) {
			self::$registered_types[ $type_name ] = $type_class;
		}

	}
?>