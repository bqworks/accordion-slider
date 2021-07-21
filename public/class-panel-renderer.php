<?php
/**
 * Renderer class for custom panels and base class for dynamic panel renderers.
 *
 * @since  1.0.0
 */
class BQW_AS_Panel_Renderer {

	/**
	 * Data of the panel.
	 *
	 * @since 1.0.0
	 * 
	 * @var array
	 */
	protected $data = null;

	/**
	 * ID of the accordion to which the panel belongs.
	 *
	 * @since 1.0.0
	 * 
	 * @var int
	 */
	protected $accordion_id = null;

	/**
	 * index of the panel.
	 *
	 * @since 1.0.0
	 * 
	 * @var int
	 */
	protected $panel_index = null;

	/**
	 * Indicates whether or not the panel's images will be lazy loaded.
	 *
	 * @since 1.0.0
	 * 
	 * @var bool
	 */
	protected $lazy_loading = null;

	/**
	 * Indicates whether or not the panel's image or link can be opened in a lightbox.
	 *
	 * @since 1.0.0
	 * 
	 * @var bool
	 */
	protected $lightbox = null;

	/**
	 * Indicates the target of the panel links.
	 *
	 * @since 1.2.0
	 * 
	 * @var bool
	 */
	protected $link_target = null;

	/**
	 * HTML markup of the panel.
	 *
	 * @since 1.0.0
	 * 
	 * @var string
	 */
	protected $html_output = '';

	/**
	 * No implementation yet
	 * .
	 * @since 1.0.0
	 */
	public function __construct() {
		
	}

	/**
	 * Set the data of the panel.
	 *
	 * @since 1.0.0
	 * 
	 * @param array $data         The data of the panel.
	 * @param int   $accordion_id The id of the accordion.
	 * @param int   $panel_index  The index of the panel.
	 * @param bool  $extra_data   Extra settings data for the slider.
	 */
	public function set_data( $data, $accordion_id, $panel_index, $extra_data ) {
		$this->data = $data;
		$this->accordion_id = $accordion_id;
		$this->panel_index = $panel_index;
		$this->lazy_loading = $extra_data->lazy_loading;
		$this->lightbox = $extra_data->lightbox;
		$this->hide_image_title = $extra_data->hide_image_title;
		$this->link_target = $extra_data->link_target;
	}

	/**
	 * Create the background image(s), link, inline HTML and layers, and return the HTML markup of the panel.
	 *
	 * @since  1.0.0
	 * 
	 * @return string the HTML markup of the panel.
	 */
	public function render() {
		$classes = 'as-panel';
		$classes = apply_filters( 'accordion_slider_panel_classes' , $classes, $this->accordion_id, $this->panel_index );

		$this->html_output = "\r\n" . '		<div class="' . esc_attr( $classes ) . '">';

		if ( $this->has_background_image() ) {
			$this->html_output .= "\r\n" . '			' . ( $this->has_background_link() && ! $this->has_opened_background_image() ? $this->add_link_to_background_image( $this->create_background_image() ) : $this->create_background_image() );
		}

		if ( $this->has_opened_background_image() ) {
			$this->html_output .= "\r\n" . '			' . ( $this->has_background_link() ? $this->add_link_to_background_image( $this->create_opened_background_image() ) : $this->create_opened_background_image() );
		}

		if ( $this->has_html() ) {
			$this->html_output .= "\r\n" . '			' . $this->create_html();
		}

		if ( $this->has_layers() ) {
			$this->html_output .= "\r\n" . '			' . $this->create_layers();
		}

		$this->html_output .= "\r\n" . '		</div>';

		$this->html_output = apply_filters( 'accordion_slider_panel_markup', $this->html_output, $this->accordion_id, $this->panel_index );

		return $this->html_output;
	}

	/**
	 * Check if the panel has a background image.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_background_image() {
		if ( isset( $this->data['background_source'] ) && $this->data['background_source'] !== '' ) {
			return true;
		}

		return false;
	}

	/**
	 * Create the HTML markup for the background image.
	 *
	 * @since  1.0.0
	 * 
	 * @return string HTML markup
	 */
	protected function create_background_image() {
		$background_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'public/assets/css/images/blank.gif', dirname( __FILE__ ) ) . '" data-src="' . esc_attr( $this->data['background_source'] ) . '"' : ' src="' . esc_attr( $this->data['background_source'] ) . '"';
		$background_alt = isset( $this->data['background_alt'] ) && $this->data['background_alt'] !== '' ? ' alt="' . esc_attr( $this->data['background_alt'] ) . '"' : '';
		$background_title = isset( $this->data['background_title'] ) && $this->data['background_title'] !== '' && $this->hide_image_title === false ? ' title="' . esc_attr( $this->data['background_title'] ) . '"' : '';
		$background_width = isset( $this->data['background_width'] ) && $this->data['background_width'] != 0 ? ' width="' . esc_attr( $this->data['background_width'] ) . '"' : '';
		$background_height = isset( $this->data['background_height'] ) && $this->data['background_height'] != 0 ? ' height="' . esc_attr( $this->data['background_height'] ) . '"' : '';
		$background_retina_source = isset( $this->data['background_retina_source'] ) && $this->data['background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['background_retina_source'] ) . '"' : '';
		
		$classes = "as-background";

		$classes = apply_filters( 'accordion_slider_background_image_classes', $classes, $this->accordion_id, $this->panel_index );
		$background_image = '<img class="' . esc_attr( $classes ) . '"' . $background_source . $background_retina_source . $background_alt . $background_title . $background_width . $background_height . ' />';

		return $background_image;
	}

	/**
	 * Check if the panel has an opened background image.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_opened_background_image() {
		if ( isset( $this->data['opened_background_source'] ) && $this->data['opened_background_source'] !== '' ) {
			return true;
		}

		return false;
	}

	/**
	 * Create the HTML markup for the opened background image.
	 *
	 * @since  1.0.0
	 * 
	 * @return string HTML markup
	 */
	protected function create_opened_background_image() {
		$opened_background_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'public/assets/css/images/blank.gif', dirname( __FILE__ ) ) . '" data-src="' . esc_attr( $this->data['opened_background_source'] ) . '"' : ' src="' . esc_attr( $this->data['opened_background_source'] ) . '"';
		$opened_background_alt = isset( $this->data['opened_background_alt'] ) && $this->data['opened_background_alt'] !== '' ? ' alt="' . esc_attr( $this->data['opened_background_alt'] ) . '"' : '';
		$opened_background_title = isset( $this->data['opened_background_title'] ) && $this->data['opened_background_title'] !== '' && $this->hide_image_title === false ? ' title="' . esc_attr( $this->data['opened_background_title'] ) . '"' : '';
		$opened_background_width = isset( $this->data['opened_background_width'] ) && $this->data['opened_background_width'] != 0 ? ' width="' . esc_attr( $this->data['opened_background_width'] ) . '"' : '';
		$opened_background_height = isset( $this->data['opened_background_height'] ) && $this->data['opened_background_height'] != 0 ? ' height="' . esc_attr( $this->data['opened_background_height'] ) . '"' : '';
		$opened_background_retina_source = isset( $this->data['opened_background_retina_source'] ) && $this->data['opened_background_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['opened_background_retina_source'] ) . '"' : '';
		
		$classes = "as-background-opened";
		$classes = apply_filters( 'accordion_slider_opened_background_image_classes', $classes, $this->accordion_id, $this->panel_index );

		$opened_background_image = '<img class="' . esc_attr( $classes ) . '"' . $opened_background_source . $opened_background_retina_source . $opened_background_alt . $opened_background_title . $opened_background_width . $opened_background_height . ' />';
	
		return $opened_background_image;
	}

	/**
	 * Check if the panel has a link for the background image(s).
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_background_link() {
		if ( ( isset( $this->data['background_link'] ) && $this->data['background_link'] !== '' ) || $this->lightbox === true ) {
			return true;
		} 

		return false;
	}

	/**
	 * Create a link for the background image(s).
	 *
	 * If the lightbox is enabled and a link was not specified,
	 * add the background image URL as a link.
	 *
	 * @since 1.0.0
	 * 
	 * @param  string  $image The image markup.
	 * @return string         The link markup.
	 */
	protected function add_link_to_background_image( $image ) {
		$background_link_href = '';

		if ( isset( $this->data['background_link'] ) && $this->data['background_link'] !== '' ) {
			$background_link_href = $this->data['background_link'];
		} else if ( $this->lightbox === true ) {
			if ( $this->has_opened_background_image() ) {
				$background_link_href = $this->data['opened_background_source'];
			} else if ( $this->has_background_image() ) {
				$background_link_href = $this->data['background_source'];
			}
		}

		$background_link_href = apply_filters( 'accordion_slider_panel_link_url', $background_link_href, $this->accordion_id, $this->panel_index );

		$classes = "";
		$classes = apply_filters( 'accordion_slider_panel_link_classes', $classes, $this->accordion_id, $this->panel_index );

		$background_link_title = isset( $this->data['background_link_title'] ) && $this->data['background_link_title'] !== '' ? ' title="' . esc_attr( $this->data['background_link_title'] ) . '"' : '';
		$background_link = 
			'<a class="' . esc_attr( $classes ) . '" href="' . esc_attr( $background_link_href ) . '"' . $background_link_title . ' target="' . esc_attr( $this->link_target ) . '">' .
				"\r\n" . '				' . $image . 
			"\r\n" . '			' . '</a>';
		
		return $background_link;
	}

	/**
	 * Check if the panel has inline HTML.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_html() {
		if ( isset( $this->data['html'] ) && $this->data['html'] !== '' ) {
			return true;
		} 

		return false;
	}

	/**
	 * Create inline HTML for the panel.
	 *
	 * @since 1.0.0
	 * 
	 * @return string The inline HTML.
	 */
	protected function create_html() {
		$html = $this->data['html'];
		$html = do_shortcode( $html );
		$html = apply_filters( 'accordion_slider_panel_html', $html, $this->accordion_id, $this->panel_index );

		return $html;
	}

	/**
	 * Check if the panel has layers.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_layers() {
		if ( isset( $this->data['layers'] ) && ! empty( $this->data['layers'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Create layers for the panel and return the HTML markup.
	 *
	 * @since 1.0.0
	 * 
	 * @return string The HTML output for the layers.
	 */
	protected function create_layers() {
		$layers_output = '';
		$layers = array_reverse( $this->data['layers'] );

		foreach ( $layers as $layer ) {
			$layers_output .= $this->create_layer( $layer );
		}

		return $layers_output;
	}

	/**
	 * Create a layer.
	 *
	 * @since  1.0.0
	 * 
	 * @param  array  $data The data of the layer.
	 * @return string       The HTML output of the layer.
	 */
	protected function create_layer( $data ) {
		$layer = BQW_AS_Layer_Renderer_Factory::create_layer( $data );
		$layer->set_data( $data, $this->accordion_id, $this->panel_index );
		
		return $layer->render();
	}
}