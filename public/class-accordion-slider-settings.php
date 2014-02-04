<?php

class Accordion_Slider_Settings {

	protected static $settings = array();

	protected static $setting_groups = array();

	protected static $layer_settings = array();

	protected static $panel_settings = array();

	protected static $breakpoint_settings = array(
		'width',
		'height',
		'responsive',
		'responsiveMode',
		'aspectRatio',
		'orientation',
		'panelDistance',
		'visiblePanels'
	);

	protected static $panels_state = array(
		'appearance' => '',
		'animations' => 'closed',
		'autoplay' => 'closed',
		'mouse_wheel' => 'closed',
		'keyboard' => 'closed',
		'swap_background' => 'closed',
		'touch_swipe' => 'closed',
		'video' => 'closed',
		'breakpoints'  => 'closed'
	);

	/*
		Return the settings
	*/
	public static function getSettings( $name = null ) {
		if ( empty( self::$settings ) ) {
			self::$settings = array(
				'width' => array(
					'label' => __( 'Width', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 800,
					'description' => ''
				),
				'height' => array(
					'label' => __( 'Height', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 400,
					'description' => ''
				),
				'responsive' => array(
					'label' => __( 'Responsive', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'responsiveMode' => array(
					'label' => __( 'Responsive Mode', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'auto',
					'available_values' => array(
						'auto' => __( 'Auto', 'accordion-slider' ),
						'custom' => __( 'Custom', 'accordion-slider' )
					),
					'description' => ''
				),
				'aspectRatio' => array(
					'label' => __( 'Aspect Ratio', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => -1,
					'description' => ''
				),
				'orientation' => array(
					'label' => __( 'Orientation', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'horizontal',
					'available_values' => array(
						'horizontal' => __( 'Horizontal', 'accordion-slider' ),
						'vertical' => __( 'Vertical', 'accordion-slider' )
					),
					'description' => ''
				),
				'shadow' => array(
					'label' => __( 'Shadow', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'panelDistance' => array(
					'label' => __( 'Panel Distance', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 0,
					'description' => ''
				),
				'panelOverlap' => array(
					'label' => __( 'Panel Overlap', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'visiblePanels' => array(
					'label' => __( 'Visible Panels', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => -1,
					'description' => ''
				),
				'startPanel' => array(
					'label' => __( 'Start Panel', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 0,
					'description' => ''
				),
				'startPage' => array(
					'label' => __( 'Start Page', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 0,
					'description' => ''
				),

				'openedPanelSize' => array(
					'label' => __( 'Opened Panel Size', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => 'max',
					'description' => ''
				),
				'maxOpenedPanelSize' => array(
					'label' => __( 'Max Opened Panel Size', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => '80%',
					'description' => ''
				),
				'openPanelOn' => array(
					'label' => __( 'Open Panel On', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'hover',
					'available_values' => array(
						'hover' => __( 'Hover', 'accordion-slider' ),
						'click' => __( 'Click', 'accordion-slider' )
					),
					'description' => ''
				),
				'closePanelsOnMouseOut' => array(
					'label' => __( 'Close Panels On Mouse Out', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'mouseDelay' => array(
					'label' => __( 'Mouse Delay', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 200,
					'description' => ''
				),
				'openPanelDuration' => array(
					'label' => __( 'Open Panel Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 700,
					'description' => ''
				),
				'closePanelDuration' => array(
					'label' => __( 'Close Panel Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 700,
					'description' => ''
				),
				'pageScrollDuration' => array(
					'label' => __( 'Page Scroll Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 500,
					'description' => ''
				),
				'pageScrollEasing' => array(
					'label' => __( 'Page Scroll Easing', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'swing',
					'available_values' => array(
						'swing' => 'Swing',
						'easeInQuad' => 'Quad In',
						'easeOutQuad' => 'Quad Out',
						'easeInOutQuad' => 'Quad In Out',
						'easeInCubic' => 'Cubic In',
						'easeOutCubic' => 'Cubic Out',
						'easeInOutCubic' => 'Cubic In Out',
						'easeInQuart' => 'Quart In',
						'easeOutQuart' => 'Quart Out',
						'easeInOutQuart' => 'Quart In Out',
						'easeInQuint' => 'Quint In',
						'easeOutQuint' => 'Quint Out',
						'easeInOutQuint' => 'Quint In Out',
						'easeInSine' => 'Sine In',
						'easeOutSine' => 'Sine Out',
						'easeInOutSine' => 'Sine In Out',
						'easeInExpo' => 'Expo In',
						'easeOutExpo' => 'Expo Out',
						'easeInOutExpo' => 'Expo In Out',
						'easeInCirc' => 'Circ In',
						'easeOutCirc' => 'Circ Out',
						'easeInOutCirc' => 'Circ In Out',
						'easeInElastic' => 'Elastic In',
						'easeOutElastic' => 'Elastic Out',
						'easeInOutElastic' => 'Elastic In Out',
						'easeInBack' => 'Back In',
						'easeOutBack' => 'Back Out',
						'easeInOutBack' => 'Back In Out',
						'easeInBounce' => 'Bounce In',
						'easeOutBounce' => 'Bounce Out',
						'easeInOutBounce' => 'Bounce In Out'
					),
					'description' => ''
				),

				'autoplay' => array(
					'label' => __( 'Autoplay', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'autoplayDelay' => array(
					'label' => __( 'Autoplay Delay', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 5000,
					'description' => ''
				),
				'autoplayDirection' => array(
					'label' => __( 'Autoplay Direction', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'normal',
					'available_values' => array(
						'normal' =>  __( 'Normal', 'accordion-slider' ),
						'backwards' =>  __( 'Backwards', 'accordion-slider' )
					),
					'description' => ''
				),
				'autoplayOnHover' => array(
					'label' => __( 'Autoplay On Hover', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'pause',
					'available_values' => array(
						'pause' => __( 'Pause', 'accordion-slider' ),
						'stop' => __( 'Stop', 'accordion-slider' ),
						'none' => __( 'None', 'accordion-slider' )
					),
					'description' => ''
				),

				'mouseWheel' => array(
					'label' => __( 'Mouse Wheel', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'mouseWheelSensitivity' => array(
					'label' => __( 'Mouse Wheel Sensitivity', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 50,
					'description' => ''
				),
				'mouseWheelTarget' => array(
					'label' => __( 'Mouse Wheel Target', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'panel',
					'available_values' => array(
						'panel' => __( 'Panel', 'accordion-slider' ),
						'page' => __( 'Page', 'accordion-slider' )
					),
					'description' => ''
				),

				'keyboard' => array(
					'label' => __( 'Keyboard', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'keyboardOnlyOnFocus' => array(
					'label' => __( 'Keyboard Only On Focus', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => false,
					'description' => ''
				),

				'swapBackgroundDuration' => array(
					'label' => __( 'Swap Background Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 700,
					'description' => ''
				),
				'fadeOutBackground' => array(
					'label' => __( 'Fade Out Background', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => false,
					'description' => ''
				),

				'touchSwipe' => array(
					'label' => __( 'Touch Swipe', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'touchSwipeThreshold' => array(
					'label' => __( 'Touch Swipe Threshold', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 50,
					'description' => ''
				),

				'openPanelVideoAction' => array(
					'label' => __( 'Open Panel Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'playVideo',
					'available_values' => array(
						'playVideo' => __( 'Play Video', 'accordion-slider' ),
						'none' => __( 'None', 'accordion-slider' )
					),
					'description' => ''
				),
				'closePanelVideoAction' => array(
					'label' => __( 'Close Panel Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'pauseVideo',
					'available_values' => array(
						'pauseVideo' => __( 'Pause Video', 'accordion-slider' ),
						'stopVideo' => __( 'Stop Video', 'accordion-slider' )
					),
					'description' => ''
				),
				'playVideoAction' => array(
					'label' => __( 'Play Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'stopAutoplay',
					'available_values' => array(
						'stopAutoplay' => __( 'Stop Autoplay', 'accordion-slider' ),
						'none' => __( 'None', 'accordion-slider' )
					),
					'description' => ''
				),
				'pauseVideoAction' => array(
					'label' => __( 'Pause Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'none',
					'available_values' => array(
						'startAutoplay' => __( 'Start Autoplay', 'accordion-slider' ),
						'none' => 'None'
					),
					'description' => ''
				),
				'endVideoAction' => array(
					'label' => __( 'End Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'none',
					'available_values' => array(
						'startAutoplay' => __( 'Start Autoplay', 'accordion-slider' ),
						'nextPanel' => __( 'Next Panel', 'accordion-slider' ),
						'replayVideo' => __( 'Replay Video', 'accordion-slider' ),
						'none' => 'None'
					),
					'description' => ''
				)
			);
		}

		if ( ! is_null( $name ) ) {
			return self::$settings[ $name ];
		}

		return self::$settings;
	}

	/*
		Return the setting groups
	*/
	public static function getSettingGroups() {
		if ( empty( self::$setting_groups ) ) {
			self::$setting_groups = array(
				'appearance' => array(
					'label' => __( 'Appearance', 'accordion-slider' ),
					'list' => array(
						'width',
						'height',
						'responsive',
						'responsiveMode',
						'aspectRatio',
						'orientation',
						'shadow',
						'panelDistance',
						'panelOverlap',
						'visiblePanels',
						'startPanel',
						'startPage'
					)
				),

				'animations' => array(
					'label' => __( 'Animations', 'accordion-slider' ),
					'list' => array(
						'openedPanelSize',
						'maxOpenedPanelSize',
						'openPanelOn',
						'closePanelsOnMouseOut',
						'mouseDelay',
						'openPanelDuration',
						'closePanelDuration',
						'pageScrollDuration',
						'pageScrollEasing'
					)
				),

				'autoplay' => array(
					'label' => __( 'Autoplay', 'accordion-slider' ),
					'list' => array(
						'autoplay',
						'autoplayDelay',
						'autoplayDirection',
						'autoplayOnHover'
					)
				),

				'mouse_wheel' => array(
					'label' => __( 'Mouse Wheel', 'accordion-slider' ),
					'list' => array(
						'mouseWheel',
						'mouseWheelSensitivity',
						'mouseWheelTarget'
					)
				),

				'keyboard' => array(
					'label' => __( 'Keyboard', 'accordion-slider' ),
					'list' => array(
						'keyboard',
						'keyboardOnlyOnFocus'
					)
				),

				'swap_background' => array(
					'label' => __( 'Swap Background', 'accordion-slider' ),
					'list' => array(
						'swapBackgroundDuration',
						'fadeOutBackground'
					)
				),

				'touch_swipe' => array(
					'label' => __( 'Touch Swipe', 'accordion-slider' ),
					'list' => array(
						'touchSwipe',
						'touchSwipeThreshold'
					)
				),

				'video' => array(
					'label' => __( 'Video', 'accordion-slider' ),
					'list' => array(
						'openPanelVideoAction',
						'closePanelVideoAction',
						'playVideoAction',
						'pauseVideoAction',
						'endVideoAction'
					)
				)
			);
		}

		return self::$setting_groups;
	}

	/*
		Return the breakpoint settings
	*/
	public static function getBreakpointSettings() {
		return self::$breakpoint_settings;
	}

	/*
		Return the default panels state
	*/
	public static function getPanelsState() {
		return self::$panels_state;
	}

	/*
		Return the layer settings
	*/
	public static function getLayerSettings() {
		if ( empty( self::$layer_settings ) ) {
			self::$layer_settings = array(
				'width' => array(
					'label' => __( 'Width', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => 'auto',
					'description' => ''
				),
				'height' => array(
					'label' => __( 'Height', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => 'auto',
					'description' => ''
				),
				'position' => array(
					'label' => __( 'Position', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'topLeft',
					'available_values' => array(
						'topLeft' => __( 'Top Left', 'accordion-slider' ),
						'topRight' => __( 'Top Right', 'accordion-slider' ),
						'bottomLeft' => __( 'Bottom Left', 'accordion-slider' ),
						'bottomRight' => __( 'Bottom Right', 'accordion-slider' )
					),
					'description' => ''
				),
				'horizontal' => array(
					'label' => __( 'Horizontal', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => '0',
					'description' => ''
				),
				'vertical' => array(
					'label' => __( 'Vertical', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => '0',
					'description' => ''
				),
				'black_background' => array(
					'label' => __( 'Black Background', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'white_background' => array(
					'label' => __( 'White Background', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => false,
					'description' => ''
				),
				'padding' => array(
					'label' => __( 'Padding', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'round_corners' => array(
					'label' => __( 'Round Corners', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => false,
					'description' => ''
				),
				'custom_class' => array(
					'label' => __( 'Custom Class', 'accordion-slider' ),
					'type' => 'text',
					'default_value' => '',
					'description' => ''
				),
				'display' => array(
					'label' => __( 'Display', 'accordion-slider' ),
					'type' => 'radio',
					'default_value' => 'always',
					'available_values' => array(
						'always' => __( 'Always', 'accordion-slider' ),
						'opened' => __( 'When panel is opened', 'accordion-slider' ),
						'closed' => __( 'When panel is closed', 'accordion-slider' )
					),
					'description' => ''
				),
				'show_transition' => array(
					'label' => __( 'Show Transition', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'left',
					'available_values' => array(
						'left' => __( 'Left', 'accordion-slider' ),
						'right' => __( 'Right', 'accordion-slider' ),
						'top' => __( 'Top', 'accordion-slider' ),
						'bottom' => __( 'Bottom', 'accordion-slider' )
					),
					'description' => ''
				),
				'show_offset' => array(
					'label' => __( 'Show Offset', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 50,
					'description' => ''
				),
				'show_delay' => array(
					'label' => __( 'Show Delay', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 10,
					'description' => ''
				),
				'show_duration' => array(
					'label' => __( 'Show Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 400,
					'description' => ''
				),
				'hide_transition' => array(
					'label' => __( 'Hide Transition', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'left',
					'available_values' => array(
						'left' => __( 'Left', 'accordion-slider' ),
						'right' => __( 'Right', 'accordion-slider' ),
						'top' => __( 'Top', 'accordion-slider' ),
						'bottom' => __( 'Bottom', 'accordion-slider' )
					),
					'description' => ''
				),
				'hide_offset' => array(
					'label' => __( 'Hide Offset', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 50,
					'description' => ''
				),
				'hide_delay' => array(
					'label' => __( 'Hide Delay', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 10,
					'description' => ''
				),
				'hide_duration' => array(
					'label' => __( 'Hide Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 400,
					'description' => ''
				)
			);
		}

		return self::$layer_settings;
	}

	/*
		Return the panel settings
	*/
	public static function getPanelSettings() {
		if ( empty( self::$panel_settings ) ) {
			self::$panel_settings = array(
				'content_type' => array(
					'label' => __( 'Content Type', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'static',
					'available_values' => array(
						'static' => __( 'Static Content', 'accordion-slider' ),
						'posts' => __( 'Content from posts', 'accordion-slider' ),
						'gallery' => __( 'Images from post\'s gallery', 'accordion-slider' ),
						'flickr' => __( 'Flickr images', 'accordion-slider' )
					),
					'description' => ''
				),
				'posts_post_type' => array(
					'label' => __( 'Posts', 'accordion-slider' ),
					'type' => 'multiselect',
					'default_value' => array( 'post' ),
					'description' => ''
				),
				'posts_taxonomy' => array(
					'label' => __( 'Taxonomy', 'accordion-slider' ),
					'type' => 'multiselect',
					'default_value' => '',
					'description' => ''
				),
				'posts_relation' => array(
					'label' => __( 'Match', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'OR',
					'available_values' => array(
						'OR' => __( 'At least one', 'accordion-slider' ),
						'AND' => __( 'All', 'accordion-slider' )
					),
					'description' => ''
				),
				'posts_operator' => array(
					'label' => __( 'With selected', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'IN',
					'available_values' => array(
						'IN' => __( 'Include', 'accordion-slider' ),
						'NOT IN' => __( 'Exclude', 'accordion-slider' )
					),
					'description' => ''
				),
				'posts_order_by' => array(
					'label' => __( 'Order By', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'date',
					'available_values' => array(
						'date' => __( 'Date', 'accordion-slider' ),
						'comment_count' => __( 'Comments', 'accordion-slider' ),
						'title' => __( 'Title', 'accordion-slider' ),
						'rand' => __( 'Random', 'accordion-slider' )
					),
					'description' => ''
				),
				'posts_order' => array(
					'label' => __( 'Order', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'DESC',
					'available_values' => array(
						'DESC' => __( 'Descending', 'accordion-slider' ),
						'ASC' => __( 'Ascending', 'accordion-slider' )
					),
					'description' => ''
				),
				'posts_maximum' => array(
					'label' => __( 'Limit', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 10,
					'description' => ''
				),
				'flickr_api_key' => array(
					'label' => __( 'API Key', 'accordion-slider' ),
					'type' => 'text',
					'default_value' => '',
					'description' => ''
				),
				'flickr_load_by' => array(
					'label' => __( 'Load By', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'set',
					'available_values' => array(
						'set' => __( 'Set', 'accordion-slider' ),
						'username' => __( 'Username', 'accordion-slider' )
					),
					'description' => ''
				),
				'flickr_id' => array(
					'label' => __( 'ID', 'accordion-slider' ),
					'type' => 'text',
					'default_value' => '',
					'description' => ''
				),
				'flickr_maximum' => array(
					'label' => __( 'Limit', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 10,
					'description' => ''
				)
			);
		}

		return self::$panel_settings;
	}
}