<?php
	class BQW_AS_Public_Dynamic_Panel extends BQW_AS_Public_Panel {

		protected $settings = null;

		protected $default_settings = null;

		protected $registered_tags = null;

		public function __construct( $data, $accordion_id, $panel_index, $lazy_loading ) {
			parent::__construct( $data, $accordion_id, $panel_index, $lazy_loading );

			$this->settings = $data['settings'];
			$this->default_settings = BQW_Accordion_Slider_Settings::getPanelSettings();
		}

		public function render() {
			return parent::render();
		}

		protected function get_panel_tags() {
			$tags = array();

			preg_match_all( '/\[as_(.*?)\]/', $this->input_html, $matches, PREG_SET_ORDER );

			foreach ( $matches as $match ) {
				$tag = $match[0];

				$delimiter_position = strpos( $match[1], '.' );
				$tag_arg = $delimiter_position !== false ? substr( $match[1], $delimiter_position + 1 ) : false;
				$tag_name = $tag_arg !== false ? substr( $match[1], 0, $delimiter_position ) : $match[1];

				$tags[] = array(
					'full' => $tag,
					'name' => $tag_name,
					'arg' => $tag_arg
				);
			}

			return $tags;
		}

		protected function render_tag( $content, $tag_full, $tag_name, $tag_arg, $query_entry ) {
			foreach ( $this->registered_tags as $name => $method ) {
				if ( $name === $tag_name ) {
					return call_user_func( $method, $content, $tag_full, $tag_arg, $query_entry );
				}
			}
		}

		protected function get_setting_value( $setting_name ) {
			return isset( $this->settings[ $setting_name ] ) ? $this->settings[ $setting_name ] : $this->default_settings[ $setting_name ]['default_value'];
		}
	}
?>