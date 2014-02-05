<?php

class Accordion_Slider {

	// the current version of the plugin
	const VERSION = '1.0.0';

	// unique identifier for the plugin
	protected $plugin_slug = 'accordion-slider';

	// holds a reference to the instance of the class
	protected static $instance = null;

	/*
		Initialize the plugin
	*/
	private function __construct() {
		// load the plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// register the widget
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// activate the plugin when a new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_blog' ) );

		// load the public CSS and JavaScript
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_shortcode( 'accordion_slider', array( $this, 'accordion_slider_shortcode' ) );
	}

	/*
		Return the plugin's slug
	*/
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
		Executed when the plugin is activated
	*/
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/*
		Executed when the plugin is deactivated
	*/
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}

				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	/*
		Executed when a new blog is activated within a WPMU environment
	*/
	public function activate_new_blog( $blog_id ) {
		if ( did_action( 'wpmu_new_blog' ) !== 1 ) {
			return 1;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/*
		Get all blog id's
	*/
	private static function get_blog_ids() {
		global $wpdb;

		$sql = "SELECT blog_id FROM $wpdb->blogs WHERE archived = '0' AND spam = '0' AND deleted = '0'";

		return $wpdb->get_col($sql);
	}

	/*
		Executed for a single blog when the plugin is activated
	*/
	private static function single_activate() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$table_name = $prefix . 'accordionslider_accordions';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// when the slider is activated for the first time, the tables don't exist, so we need to create them
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			$create_accordions_table = "CREATE TABLE ". $prefix . "accordionslider_accordions (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				settings text NOT NULL,
				created varchar(11) NOT NULL,
				modified varchar(11) NOT NULL,
				panels_state text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_panels_table = "CREATE TABLE ". $prefix . "accordionslider_panels (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				accordion_id mediumint(9) NOT NULL,
				label varchar(100) NOT NULL,
				position mediumint(9) NOT NULL,
				visibility varchar(20) NOT NULL,
				background_source text NOT NULL,
				background_retina_source text NOT NULL,
				background_alt text NOT NULL,
				background_title text NOT NULL,
				background_width mediumint(9) NOT NULL,
				background_height mediumint(9) NOT NULL,
				opened_background_source text NOT NULL,
				opened_background_retina_source text NOT NULL,
				opened_background_alt text NOT NULL,
				opened_background_title text NOT NULL,
				opened_background_width mediumint(9) NOT NULL,
				opened_background_height mediumint(9) NOT NULL,
				background_link text NOT NULL,
				background_link_title text NOT NULL,
				html text NOT NULL,
				settings text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
			
			$create_layers_table = "CREATE TABLE ". $prefix . "accordionslider_layers (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				panel_id mediumint(9) NOT NULL,
				position mediumint(9) NOT NULL,
				content text NOT NULL,
				settings text NOT NULL,
				PRIMARY KEY (id)
				) DEFAULT CHARSET=utf8;";
																		   						
			dbDelta( $create_accordions_table );
			dbDelta( $create_panels_table );
			dbDelta( $create_layers_table );
		}
	}

	/*
		Executed for a single blog when the plugin is deactivated
	*/
	private static function single_deactivate() {

	}

	/*
		Load the plugin translation file
	*/
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	}

	public function register_widget() {
		register_widget( 'Accordion_Slider_Widget' );
	}

	/*
		Load the public CSS
	*/
	public function enqueue_styles() {
		wp_register_style( $this->plugin_slug . '-plugin-style', plugins_url( 'assets/css/accordion-slider.min.css', __FILE__ ), array(), self::VERSION );
	}

	/*
		Load the public JavaScript
	*/
	public function enqueue_scripts() {
		wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/jquery.accordionSlider.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	public function load_accordion( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accordionslider_accordions';

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	public function load_panels( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accordionslider_panels';
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE accordion_id = %d", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	public function load_layers( $id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'accordionslider_layers';
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE panel_id = %d", $id ), ARRAY_A );

		if ( ! is_null( $result ) ) {
			return $result;
		} else {
			return false;	
		}
	}

	public function get_accordion( $id ) {
		$accordion = array();
		$accordion_raw = $this->load_accordion( $id );

		if ( $accordion_raw === false ) {
			return false;
		}

		$accordion['id'] = $accordion_raw['id'];
		$accordion['name'] = $accordion_raw['name'];
		$accordion['settings'] = json_decode( stripslashes( $accordion_raw['settings'] ), true );
		$accordion['panels_state'] = json_decode( stripslashes( $accordion_raw['panels_state'] ), true );
		
		$panels_raw = $this->load_panels( $id );

		if ( $panels_raw !== false ) {
			$accordion['panels'] = array();

			foreach ( $panels_raw as $panel_raw ) {
				$panel = $panel_raw;
				$panel['settings'] = json_decode( stripslashes( $panel_raw['settings'] ), true );
				$layers_raw = $this->load_layers( $panel_raw['id'] );

				if ( $layers_raw !== false ) {
					$panel['layers'] = array();

					foreach ( $layers_raw as $layer_raw ) {
						$layer = $layer_raw;
						$layer['settings'] = json_decode( stripslashes( $layer_raw['settings'] ), true );

						array_push( $panel['layers'], $layer );
					}
				}

				array_push( $accordion['panels'], $panel );
			}
		}

		return $accordion;
	}

	public function accordion_slider_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'id' => '-1'
		), $atts ) );

		return $this->get_accordion_slider( $id );
	}

	public function get_accordion_slider( $id ) {
		wp_enqueue_style( $this->plugin_slug . '-plugin-style' );
		wp_enqueue_script( $this->plugin_slug . '-plugin-script' );

		$accordion = $this->get_accordion( $id );
		
		return $this->output_accordion( $accordion );
	}

	public function output_accordion( $accordion ) {
		$accordion_id = $accordion['id'];
		$accordion_settings = $accordion['settings'];
		
		$default_settings = Accordion_Slider_Settings::getSettings();
 		$default_layer_settings = Accordion_Slider_Settings::getLayerSettings();
 		$default_panel_settings = Accordion_Slider_Settings::getPanelSettings();

		$content_html = "";
		$settings_js = "";

		$content_html .= "\r\n" . '<div id="accordion-slider-' . $accordion_id . '" class="accordion-slider">';
		$content_html .= "\r\n" . '	<div class="as-panels">';

		$is_lazy_loading = isset( $accordion_settings['lazy_loading'] ) ? $accordion_settings['lazy_loading'] : $default_settings['lazy_loading'];

		if ( isset( $accordion['panels'] ) ) {
			$panels = $accordion['panels'];

			foreach ( $panels as $panel ) {
				$panel_html = "\r\n" . '		<div class="as-panel">';

				$is_background_image = isset( $panel['background_source'] ) && $panel['background_source'] !== '' ? true : false;
				$is_opened_background_image = isset( $panel['opened_background_source'] ) && $panel['opened_background_source'] !== '' ? true : false;
				$is_background_link = isset( $panel['background_link'] ) && $panel['background_link'] !== '' ? true : false;

				$background_link = '';

				if ( $is_background_link ) {
					$background_link_href = ' href="' . esc_attr( $panel['background_link'] ) . '"';
					$background_link_title = isset( $panel['background_link_title'] ) && $panel['background_link_title'] !== '' ? ' title="' . esc_attr( $panel['background_link_title'] ) . '"' : '';

					$background_link = '<a' . $background_link_href . $background_link_title . '>';
				}

				if ( $is_background_image ) {
					$background_source = $is_lazy_loading === true ? ' src="' . plugins_url( 'assets/css/images/blank.gif', __FILE__ ) . '" data-src="' . esc_attr( $panel['background_source'] ) . '"' : ' src="' . esc_attr( $panel['background_source'] ) . '"';
					$background_alt = isset( $panel['background_alt'] ) && $panel['background_alt'] !== '' ? ' alt="' . esc_attr( $panel['background_alt'] ) . '"' : '';
					$background_title = isset( $panel['background_title'] ) && $panel['background_title'] !== '' ? ' title="' . esc_attr( $panel['background_title'] ) . '"' : '';
					$background_width = isset( $panel['background_width'] ) && $panel['background_width'] !== '' ? ' width="' . esc_attr( $panel['background_width'] ) . '"' : '';
					$background_height = isset( $panel['background_height'] ) && $panel['background_height'] !== '' ? ' height="' . esc_attr( $panel['background_height'] ) . '"' : '';
					$background_retina_source = isset( $panel['background_retina_source'] ) && $panel['background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $panel['background_retina_source'] ) . '"' : '';

					$background_image = '<img class="as-background"' . $background_source . $background_retina_source . $background_alt . $background_title . $background_width . $background_height . ' />';
				
					if ( $is_background_link === true &&  $is_opened_background_image === false ) {
						$panel_html .= "\r\n" . '			' . $background_link . "\r\n" . '				' . $background_image . "\r\n" . '			' . '</a>';
					} else {
						$panel_html .= "\r\n" . '			' . $background_image;
					}
				}

				if ( $is_opened_background_image ) {
					$opened_background_source = $is_lazy_loading === true ? ' src="' . plugins_url( 'assets/css/images/blank.gif', __FILE__ ) . '" data-src="' . esc_attr( $panel['opened_background_source'] ) . '"' : ' src="' . esc_attr( $panel['opened_background_source'] ) . '"';
					$opened_background_alt = isset( $panel['opened_background_alt'] ) && $panel['opened_background_alt'] !== '' ? ' alt="' . esc_attr( $panel['opened_background_alt'] ) . '"' : '';
					$opened_background_title = isset( $panel['opened_background_title'] ) && $panel['opened_background_title'] !== '' ? ' title="' . esc_attr( $panel['opened_background_title'] ) . '"' : '';
					$opened_background_width = isset( $panel['opened_background_width'] ) && $panel['opened_background_width'] !== '' ? ' width="' . esc_attr( $panel['opened_background_width'] ) . '"' : '';
					$opened_background_height = isset( $panel['opened_background_height'] ) && $panel['opened_background_height'] !== '' ? ' height="' . esc_attr( $panel['opened_background_height'] ) . '"' : '';
					$opened_background_retina_source = isset( $panel['opened_background_retina_source'] ) && $panel['opened_background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $panel['opened_background_retina_source'] ) . '"' : '';

					$opened_background_image = '<img class="as-background-opened"' . $opened_background_source . $opened_background_retina_source . $opened_background_alt . $opened_background_title . $opened_background_width . $opened_background_height . ' />';

					if ( $is_background_link === true ) {
						$panel_html .= "\r\n" . '			' . $background_link . "\r\n" . '				' . $opened_background_image . "\r\n" . '			' . '</a>';
					} else {
						$panel_html .= "\r\n" . '			' . $opened_background_image;
					}
				}

				if ( isset( $panel['html'] ) && $panel['html'] !== '' ) {
					$panel_html .= "\r\n" . '			' . $panel['html'];
				}

				if ( isset( $panel['layers'] ) ) {
					$layers = $panel['layers'];

					foreach ( $layers as $layer ) {
						$layer_html = '';
						$layer_classes = 'as-layer';
						$layer_attributes = '';
						$layer_content = $layer['content'];

						$layer_settings = $layer['settings'];

						if ( isset( $layer_settings['display'] ) ) {
							$layer_classes .= ' as-' . $layer_settings['display'];
							unset( $layer_settings['display'] );
						}

						if ( isset( $layer_settings['black_background'] ) && $layer_settings['black_background'] === true ) {
							$layer_classes .= ' as-black';
							unset( $layer_settings['black_background'] );
						}

						if ( isset( $layer_settings['white_background'] ) && $layer_settings['white_background'] === true ) {
							$layer_classes .= ' as-white';
							unset( $layer_settings['white_background'] );
						}

						if ( isset( $layer_settings['round_corners'] ) && $layer_settings['round_corners'] === true ) {
							$layer_classes .= ' as-rounded';
							unset( $layer_settings['round_corners'] );
						}

						if ( isset( $layer_settings['padding'] ) && $layer_settings['padding'] === true ) {
							$layer_classes .= ' as-padding';
							unset( $layer_settings['padding'] );
						}

						if ( isset( $layer_settings['custom_class'] ) && $layer_settings['custom_class'] !== '' ) {
							$layer_classes .= ' ' . sanitize_html_class( $layer_settings['custom_class'] );
						}

						foreach ( $layer_settings as $layer_settings_name => $layer_settings_value ) {
							if ( $default_layer_settings[ $layer_settings_name ]['default_value'] != $layer_settings_value ) {
								$layer_settings_name = str_replace('_', '-', $layer_settings_name);

								$layer_attributes .= ' data-' . $layer_settings_name . '="' . esc_attr( $layer_settings_value ) . '"';
							}
						}

						$panel_html .= "\r\n" . '			' . '<div class="' .  $layer_classes . '"' . $layer_attributes . '>' . esc_html( $layer_content ) . '</div>';
					}
				}

				$panel_html .= "\r\n" . '		</div>';

				$content_type = isset( $panel['settings']['content_type'] ) ? $panel['settings']['content_type'] : $default_panel_settings['content_type'];

				if ( $content_type === 'static' ) {
					$content_html .= $panel_html;
				} else if ( $content_type === 'posts' ) {
					$content_html .= $this->output_posts_panels( $panel_html, $panel['settings'] );
				} else if ( $content_type === 'gallery' ) {
					$content_html .= $this->output_gallery_panels( $panel_html, $panel['settings'] );
				}
			}
		}

		$content_html .= "\r\n" . '	</div>';
		$content_html .= "\r\n" . '</div>';

		foreach ( $default_settings as $setting_name => $setting ) {
			$setting_default_value = $setting['default_value'];
			$setting_value = isset( $accordion_settings[ $setting_name ] ) ? $accordion_settings[ $setting_name ] : $setting_default_value;

			if ( $setting_value != $setting_default_value ) {
				if ( $settings_js !== '' ) {
					$settings_js .= ',';
				}

				if ( is_bool( $setting_value ) ) {
					$setting_value = $setting_value === true ? 'true' : 'false';
				} else if ( is_numeric( $setting_value ) === false ) {
					$setting_value = "'" . $setting_value . "'";
				}

				$settings_js .= "\r\n" . '			' . $setting_name . ' : ' . $setting_value;
			}
		}

		if ( isset ( $accordion_settings['breakpoints'] ) ) {
			$breakpoints_js = "";

			foreach ( $accordion_settings['breakpoints'] as $breakpoint ) {
				if ( $breakpoint['breakpoint_width'] === '' ) {
					continue;
				}

				if ( $breakpoints_js !== '' ) {
					$breakpoints_js .= ',';
				}

				$breakpoints_js .= "\r\n" . '				' . $breakpoint['breakpoint_width'] . ': {';

				unset( $breakpoint['breakpoint_width'] );

				if ( ! empty( $breakpoint ) ) {
					$breakpoint_setting_js = '';

					foreach ( $breakpoint as $breakpoint_setting_name => $breakpoint_setting_value ) {
						if ( $breakpoint_setting_js !== '' ) {
							$breakpoint_setting_js .= ',';
						}

						if ( is_bool( $breakpoint_setting_value ) ) {
							$breakpoint_setting_value = $breakpoint_setting_value === true ? 'true' : 'false';
						} else if ( is_numeric( $breakpoint_setting_value ) === false ) {
							$breakpoint_setting_value = "'" . $breakpoint_setting_value . "'";
						}

						$breakpoint_setting_js .= "\r\n" . '					' . $breakpoint_setting_name . ' : ' . $breakpoint_setting_value;
					}

					$breakpoints_js .= $breakpoint_setting_js;
				}

				$breakpoints_js .= "\r\n" . '				}';
			}

			if ( $settings_js !== '' ) {
				$settings_js .= ',';
			}

			$settings_js .= "\r\n" . '			breakpoints: {' . $breakpoints_js . "\r\n" . '			}';
		}

		$accordion_js = "\r\n" . '<script type="text/javascript">' .
						"\r\n" . '	jQuery( document ).ready(function( $ ) {' .
						"\r\n" . '		$( "#accordion-slider-' . $accordion_id . '" ).accordionSlider({' .
											$settings_js .
						"\r\n" . '		});' .
						"\r\n" . '	});' .
						"\r\n" . '</script>';

		

		return $content_html . "\r\n" . $accordion_js;
	}

	public function output_posts_panels( $panel_html, $settings ) {
		$default_panel_settings = Accordion_Slider_Settings::getPanelSettings();

		$query_args = array();
			
		if ( isset( $settings['posts_post_types'] ) && ! empty( $settings['posts_post_types'] ) ) {
			$query_args['post_types'] = $settings['posts_post_types'];
		}
		
		if ( isset( $settings['posts_taxonomies'] ) && ! empty( $settings['posts_taxonomies'] ) ) {
			$taxonomy_terms = $settings['posts_taxonomies'];

			$tax_query = array();

			foreach ( $taxonomy_terms as $taxonomy_term_raw ) {
				$taxonomy_term = explode( '|', $taxonomy_term_raw );
				
				$tax_item['taxonomy'] = $taxonomy_term[0];
				$tax_item['terms'] = $taxonomy_term[1];
				$tax_item['field'] = 'slug';
				
				$tax_item['operator'] = isset( $settings['posts_operator'] ) ? $settings['posts_operator'] : $default_panel_settings['posts_operator'];
				
				array_push( $tax_query, $tax_item );
			}
			
			if ( count( $taxonomy_terms ) > 1 ) {
				$tax_query['relation'] = isset( $settings['posts_relation'] ) ? $settings['posts_relation'] : $default_panel_settings['posts_relation'];
			}

			$query_args['tax_query'] = $tax_query;
		}
		
		$query_args['posts_per_page'] = isset( $settings['posts_maximum'] ) ? $settings['posts_maximum'] : $default_panel_settings['posts_maximum'];
		$query_args['offset'] = isset( $settings['posts_offset'] ) ? $settings['posts_offset'] : $default_panel_settings['posts_offset'];
		$query_args['orderby'] = isset( $settings['posts_order_by'] ) ? $settings['posts_order_by'] : $default_panel_settings['posts_order_by'];
		$query_args['order'] = isset( $settings['posts_order'] ) ? $settings['posts_order'] : $default_panel_settings['posts_order'];
		
		$query = new WP_Query( $query_args );

		$is_image = false;

		if ( strpos( $panel_html, '[as_image' ) ) {
			$is_image = true;
		}

		preg_match_all( '/\[as_(.*?)\]/', $panel_html, $matches, PREG_SET_ORDER );

		$panels_html = '';

		while ( $query->have_posts() ) {
			$query->the_post();

			$panel_html_copy = $panel_html;
			$featured_image_data = null;

			if ( $is_image && has_post_thumbnail() ) {
				$featured_image_id = get_post_thumbnail_id();
				$featured_image_data = get_post( $featured_image_id, ARRAY_A );
				
				$featured_image_alt = get_post_meta( $featured_image_id, '_wp_attachment_image_alt' );
				$featured_image_data['alt'] = ! empty( $featured_image_alt ) ? $featured_image_alt[0] : '';
			}

			foreach ( $matches as $match ) {
				$shortcode = $match[0];

				$delimiter_position = strpos( $match[1], '.' );
				$shortcode_arg = $delimiter_position !== false ? substr( $match[1], $delimiter_position + 1 ) : false;
				$shortcode_name = $shortcode_arg !== false ? substr( $match[1], 0, $delimiter_position ) : $match[1];

				switch( $shortcode_name ) {
					case 'image':
						if ( is_null( $featured_image_data ) ) {
							return;
						}

						$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
						$image_full = get_the_post_thumbnail( get_the_ID(), $image_size, array( 'class' => '' ) );

						$panel_html_copy = str_replace( $shortcode, $image_full, $panel_html_copy );
						break;

					case 'image_src':
						if ( is_null( $featured_image_data ) ) {
							return;
						}

						$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
						$image_src = wp_get_attachment_image_src( $featured_image_id, $image_size );

						$panel_html_copy = str_replace( $shortcode, $image_src[0], $panel_html_copy );
						break;

					case 'image_alt':
						if ( is_null( $featured_image_data ) ) {
							return;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['alt'], $panel_html_copy );
						break;

					case 'image_title':
						if ( is_null( $featured_image_data ) ) {
							return;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['post_title'], $panel_html_copy );
						break;

					case 'image_description':
						if ( is_null( $featured_image_data ) ) {
							return;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['post_content'], $panel_html_copy );
						break;

					case 'image_caption':
						if ( is_null( $featured_image_data ) ) {
							return;
						}

						$panel_html_copy = str_replace( $shortcode, $featured_image_data['post_excerpt'], $panel_html_copy );
						break;

					case 'title':

						$panel_html_copy = str_replace( $shortcode, get_the_title(), $panel_html_copy );
						break;

					case 'link':
						$link = '<a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a>';

						$panel_html_copy = str_replace( $shortcode, $link, $panel_html_copy );
						break;

					case 'link_url':

						$panel_html_copy = str_replace( $shortcode, get_permalink( get_the_ID() ), $panel_html_copy );
						break;

					case 'date':

						$date_format = $shortcode_arg !== false ? $shortcode_arg : get_option( 'date_format' );
						$panel_html_copy = str_replace( $shortcode, get_the_date( $date_format ), $panel_html_copy );
						break;

					case 'excerpt':

						$panel_html_copy = str_replace( $shortcode, get_the_excerpt(), $panel_html_copy );
						break;

					case 'content':

						$panel_html_copy = str_replace( $shortcode, get_the_content(), $panel_html_copy );
						break;

					case 'category':

						$categories = get_the_category( get_the_ID() );
						$category = ! empty( $categories ) ? $categories[0] : '';

						$panel_html_copy = str_replace( $shortcode, $category, $panel_html_copy );
						break;

					case 'custom':

						$value = '';

						if ( $shortcode_arg !== false ) {
							$values = get_post_meta( get_the_ID(), $shortcode_arg );										
							$value = $values[0];
						}

						$panel_html_copy = str_replace( $shortcode, $value, $panel_html_copy );
						break;
				}
			}

			$panels_html .= $panel_html_copy;
		}

		wp_reset_postdata();

		return $panels_html;
	}

	public function output_gallery_panels( $panel_html, $settings ) {
		global $post;

		$post_content = $post->post_content;
		$pattern = get_shortcode_regex();

		preg_match_all( '/' . $pattern . '/s', $post_content, $gallery_matches, PREG_SET_ORDER );
		preg_match_all( '/\[as_(.*?)\]/', $panel_html, $matches, PREG_SET_ORDER );

		$panels_html = '';

		foreach ( $gallery_matches as $gallery_match ) {
			if ( $gallery_match[2] !== 'gallery' ) {
				continue;
			}

			$gallery_atts = shortcode_parse_atts( $gallery_match[3] );

			if ( ! isset( $gallery_atts[ 'ids' ] ) ) {
				continue;
			}

			$gallery_ids = explode( ',', $gallery_atts[ 'ids' ] );

			$gallery_images = array();

			foreach ( $gallery_ids as $gallery_id ) {
				$gallery_image = get_post( $gallery_id, ARRAY_A );
				$gallery_image_alt = get_post_meta( $gallery_id, '_wp_attachment_image_alt' );
				$gallery_image['alt'] = ! empty( $gallery_image_alt ) ? $gallery_image_alt[0] : '';

				array_push( $gallery_images, $gallery_image );
			}

			foreach ( $gallery_images as $gallery_image ) {
				$panel_html_copy = $panel_html;

				foreach ( $matches as $match ) {
					$shortcode = $match[0];

					$delimiter_position = strpos( $match[1], '.' );
					$shortcode_arg = $delimiter_position !== false ? substr( $match[1], $delimiter_position + 1 ) : false;
					$shortcode_name = $shortcode_arg !== false ? substr( $match[1], 0, $delimiter_position ) : $match[1];

					switch( $shortcode_name ) {
						case 'image':
							$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
							$image_full = wp_get_attachment_image( $gallery_image['ID'], $image_size );

							$panel_html_copy = str_replace( $shortcode, $image_full, $panel_html_copy );
							break;

						case 'image_src':

							$image_size = $shortcode_arg !== false ? $shortcode_arg : 'full';
							$image_src = wp_get_attachment_image_src( $gallery_image['ID'], $image_size );

							$panel_html_copy = str_replace( $shortcode, $image_src[0], $panel_html_copy );
							break;

						case 'image_alt':

							$panel_html_copy = str_replace( $shortcode, $gallery_image['alt'], $panel_html_copy );
							break;

						case 'image_title':

							$panel_html_copy = str_replace( $shortcode, $gallery_image['post_title'], $panel_html_copy );
							break;

						case 'image_description':

							$panel_html_copy = str_replace( $shortcode, $gallery_image['post_content'], $panel_html_copy );
							break;
					}
				}

				$panels_html .= $panel_html_copy;
			}
		}

		return $panels_html;
	}

}