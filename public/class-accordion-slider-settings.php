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
		'lightbox' => 'closed',
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
					'js_name' => 'width',
					'label' => __( 'Width', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 800,
					'description' => __( 'Sets the width of the accordion. Can be set to a fixed value, like 900 (indicating 900 pixels), or to a percentage value, like 100%. In order to make the accordion responsive, it\'s not necessary to use percentage values. More about this in the description of the Responsive option.', 'accordion-slider' )
				),
				'height' => array(
					'js_name' => 'height',
					'label' => __( 'Height', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 400,
					'description' => __( 'Sets the height of the accordion. Can be set to a fixed value, like 400 (indicating 400 pixels). It\s not recommended to set this to a percentage value, and it\s not usually needed, as the accordion will be responsive even with a fixed value set for the height.', 'accordion-slider' )
				),
				'responsive' => array(
					'js_name' => 'responsive',
					'label' => __( 'Responsive', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Makes the accordion responsive. The accordion can be responsive even if the Width and/or Height options are set to fixed values. In this situation, the Width and Height will act as the maximum width and height of the accordion.', 'accordion-slider' )
				),
				'responsive_mode' => array(
					'js_name' => 'responsiveMode',
					'label' => __( 'Responsive Mode', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'auto',
					'available_values' => array(
						'auto' => __( 'Auto', 'accordion-slider' ),
						'custom' => __( 'Custom', 'accordion-slider' )
					),
					'description' => __( '\'Auto\' resizes the accordion and all of its elements (e.g., layers, videos) automatically, while \'Custom\' only resizes the accordion container and panels, and you are given flexibility over the way inner elements (e.g., layers, videos) will respond to smaller sizes. For example, you could use CSS media queries to define different text sizes or to hide certain elements when the accordion becomes smaller, ensuring that all content remains readable without having to zoom in. It\'s important to note that, if \'Auto\' responsiveness is used, the \'Width\' and \'Height\' need to be set to fixed values, so that the accordion can calculate correctly how much it needs to scale.', 'accordion-slider' )
				),
				'aspect_ratio' => array(
					'js_name' => 'aspectRatio',
					'label' => __( 'Aspect Ratio', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => -1,
					'description' => __( 'Sets the aspect ratio of the accordion. The accordion will set its height depending on what value its width has, so that this ratio is maintained. For this reason, the set \'Height\' might be overridden. This property can be used only when \'Responsive Mode\' is set to \'Custom\'. When it\'s set to \'Auto\', the \'Aspect Ratio\' needs to remain -1.', 'accordion-slider' )
				),
				'orientation' => array(
					'js_name' => 'orientation',
					'label' => __( 'Orientation', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'horizontal',
					'available_values' => array(
						'horizontal' => __( 'Horizontal', 'accordion-slider' ),
						'vertical' => __( 'Vertical', 'accordion-slider' )
					),
					'description' => __( 'Sets the orientation of the panels.', 'accordion-slider' )
				),
				'shadow' => array(
					'js_name' => 'shadow',
					'label' => __( 'Shadow', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Indicates if the panels will have a drop shadow effect.', 'accordion-slider' )
				),
				'panel_distance' => array(
					'js_name' => 'panelDistance',
					'label' => __( 'Panel Distance', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 0,
					'description' => __( 'Sets the distance between consecutive panels. Can be set to a percentage or fixed value.', 'accordion-slider' )
				),
				'panel_overlap' => array(
					'js_name' => 'panelOverlap',
					'label' => __( 'Panel Overlap', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Indicates if the panels will be overlapped. If set to false, the panels will have their width or height set so that they are next to each other, but not overlapped.', 'accordion-slider' )
				),
				'visible_panels' => array(
					'js_name' => 'visiblePanels',
					'label' => __( 'Visible Panels', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => -1,
					'description' => __( 'Indicates the number of panels visible per page. If set to -1, all the panels will be displayed on one page.', 'accordion-slider' )
				),
				'start_panel' => array(
					'js_name' => 'startPanel',
					'label' => __( 'Start Panel', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 0,
					'description' => __( 'Indicates which panel will be opened when the accordion loads (0 for the first panel, 1 for the second panel, etc.). If set to -1, no panel will be opened.', 'accordion-slider' )
				),
				'start_page' => array(
					'js_name' => 'startPage',
					'label' => __( 'Start Page', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 0,
					'description' => __( 'Indicates which page will be opened when the accordion loads, if the panels are displayed on more than one page.', 'accordion-slider' )
				),
				'lazy_loading' => array(
					'js_name' => 'lazyLoading',
					'label' => __( 'Lazy Loading', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Indicates if the background images will be loaded only when they are visible. Images from accordion pages that are not visible, will not be loaded.', 'accordion-slider' )
				),

				'opened_panel_size' => array(
					'js_name' => 'openedPanelSize',
					'label' => __( 'Opened Panel Size', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => 'max',
					'description' => __( 'Sets the size (width if the accordion\'s Orientation is Horizontal; height if the accordion\'s Orientation is Vertical) of the opened panel. Possible values are: \'max\', which will open the panel to its maximum size, so that all the inner content is visible, a percentage value, like \'50%\', which indicates the percentage of the total size (width or height, depending on the Rrientation) of the accordion, or a fixed value.', 'accordion-slider' )
				),
				'max_opened_panel_size' => array(
					'js_name' => 'maxOpenedPanelSize',
					'label' => __( 'Max Opened Panel Size', 'accordion-slider' ),
					'type' => 'mixed',
					'default_value' => '80%',
					'description' => __( 'Sets the maximum allowed size of the opened panel. This should be used when the \'Opened Panel Size\' is set to \'max\', because sometimes the maximum size of the panel might be too big and we want to set a limit. The property can be set to a percentage (of the total size of the accordion) or to a fixed value.', 'accordion-slider' )
				),
				'open_panel_on' => array(
					'js_name' => 'openPanelOn',
					'label' => __( 'Open Panel On', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'hover',
					'available_values' => array(
						'hover' => __( 'Hover', 'accordion-slider' ),
						'click' => __( 'Click', 'accordion-slider' )
					),
					'description' => __( 'If set to \'Hover\', the panels will be opened by moving the mouse pointer over them; if set to \'Click\', the panels will only open when clicked.', 'accordion-slider' )
				),
				'close_panels_on_mouse_out' => array(
					'js_name' => 'closePanelsOnMouseOut',
					'label' => __( 'Close Panels On Mouse Out', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Determines whether the opened panel closes or remains open when the mouse pointer is moved away.', 'accordion-slider' )
				),
				'mouse_delay' => array(
					'js_name' => 'mouseDelay',
					'label' => __( 'Mouse Delay', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 200,
					'description' => __( 'Sets the delay in milliseconds between the movement of the mouse pointer and the opening of the panel. Setting a delay ensures that panels are not opened if the mouse pointer only moves over them without an intent to open the panel.', 'accordion-slider' )
				),
				'open_panel_duration' => array(
					'js_name' => 'openPanelDuration',
					'label' => __( 'Open Panel Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 700,
					'description' => __( 'Determines the duration in milliseconds for the opening animation of the panel.', 'accordion-slider' )
				),
				'close_panel_duration' => array(
					'js_name' => 'closePanelDuration',
					'label' => __( 'Close Panel Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 700,
					'description' => __( 'Determines the duration in milliseconds for the closing animation of the panel.', 'accordion-slider' )
				),
				'page_scroll_duration' => array(
					'js_name' => 'pageScrollDuration',
					'label' => __( 'Page Scroll Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 500,
					'description' => __( 'Indicates the duration of the page scrolling animation.', 'accordion-slider' )
				),
				'page_scroll_easing' => array(
					'js_name' => 'pageScrollEasing',
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
					'description' => __( 'Indicates the easing type of the page scrolling animation.', 'accordion-slider' )
				),

				'autoplay' => array(
					'js_name' => 'autoplay',
					'label' => __( 'Autoplay', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Indicates if the autoplay will be enabled.', 'accordion-slider' )
				),
				'autoplay_delay' => array(
					'js_name' => 'autoplayDelay',
					'label' => __( 'Autoplay Delay', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 5000,
					'description' => __( 'Sets the delay, in milliseconds, of the autoplay cycle.', 'accordion-slider' )
				),
				'autoplay_direction' => array(
					'js_name' => 'autoplayDirection',
					'label' => __( 'Autoplay Direction', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'normal',
					'available_values' => array(
						'normal' =>  __( 'Normal', 'accordion-slider' ),
						'backwards' =>  __( 'Backwards', 'accordion-slider' )
					),
					'description' => __( 'Sets the direction in which the panels will be opened. Can be set to \'Normal\' (ascending order) or \'Backwards\' (descending order).', 'accordion-slider' )
				),
				'autoplay_on_hover' => array(
					'js_name' => 'autoplayOnHover',
					'label' => __( 'Autoplay On Hover', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'pause',
					'available_values' => array(
						'pause' => __( 'Pause', 'accordion-slider' ),
						'stop' => __( 'Stop', 'accordion-slider' ),
						'none' => __( 'None', 'accordion-slider' )
					),
					'description' => __( 'Indicates if the autoplay will be paused when the accordion is hovered.', 'accordion-slider' )
				),

				'mouse_wheel' => array(
					'js_name' => 'mouseWheel',
					'label' => __( 'Mouse Wheel', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Indicates if the accordion will respond to mouse wheel input.', 'accordion-slider' )
				),
				'mouse_wheel_sensitivity' => array(
					'js_name' => 'mouseWheelSensitivity',
					'label' => __( 'Mouse Wheel Sensitivity', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 50,
					'description' => __( 'Sets how sensitive the accordion will be to mouse wheel input. Lower values indicate stronger sensitivity.', 'accordion-slider' )
				),
				'mouse_wheel_target' => array(
					'js_name' => 'mouseWheelTarget',
					'label' => __( 'Mouse Wheel Target', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'panel',
					'available_values' => array(
						'panel' => __( 'Panel', 'accordion-slider' ),
						'page' => __( 'Page', 'accordion-slider' )
					),
					'description' => __( 'Sets what elements will be targeted by the mouse wheel input. Can be set to \'Panel\' or \'Page\'. Setting it to \'Panel\' will indicate that the panels will be scrolled, while setting it to \'Page\' indicate that the pages will be scrolled.', 'accordion-slider' )
				),

				'keyboard' => array(
					'js_name' => 'keyboard',
					'label' => __( 'Keyboard', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Indicates if the accordion will respond to keyboard input.', 'accordion-slider' )
				),

				'keyboard_only_on_focus' => array(
					'js_name' => 'keyboardOnlyOnFocus',
					'label' => __( 'Keyboard Only On Focus', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => false,
					'description' => __( 'Indicates if the accordion will respond to keyboard input only if the accordion has focus.', 'accordion-slider' )
				),

				'keyboard_target' => array(
					'js_name' => 'keyboardTarget',
					'label' => __( 'Keyboard Target', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'panel',
					'available_values' => array(
						'panel' => __( 'Panel', 'accordion-slider' ),
						'page' => __( 'Page', 'accordion-slider' )
					),
					'description' => __( 'Sets what elements will be targeted by the keyboard input. Can be set to \'Panel\' or \'Page\'. Setting it to \'Panel\' will indicate that the panels will be scrolled, while setting it to \'Page\' indicate that the pages will be scrolled.', 'accordion-slider' )
				),

				'swap_background_duration' => array(
					'js_name' => 'swapBackgroundDuration',
					'label' => __( 'Swap Background Duration', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 700,
					'description' => __( 'Sets the duration, in milliseconds, of the transition effect.', 'accordion-slider' )
				),
				'fade_out_background' => array(
					'js_name' => 'fadeOutBackground',
					'label' => __( 'Fade Out Background', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => false,
					'description' => __( 'Indicates if the main image background will be faded out when the opened/alternative background fades in.', 'accordion-slider' )
				),

				'touch_swipe' => array(
					'js_name' => 'touchSwipe',
					'label' => __( 'Touch Swipe', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => true,
					'description' => __( 'Indicates if the touch swipe functionality will be enabled.', 'accordion-slider' )
				),
				'touch_swipe_threshold' => array(
					'js_name' => 'touchSwipeThreshold',
					'label' => __( 'Touch Swipe Threshold', 'accordion-slider' ),
					'type' => 'number',
					'default_value' => 50,
					'description' => __( 'Sets how many pixels the distance of the swipe gesture needs to be in order to trigger a page change.', 'accordion-slider' )
				),

				'lightbox' => array(
					'js_name' => 'lightbox',
					'label' => __( 'Lightbox', 'accordion-slider' ),
					'type' => 'boolean',
					'default_value' => false,
					'description' => __( 'Indicates if the links specified to the background images will be opened in a lightbox.', 'accordion-slider' )
				),

				'open_panel_video_action' => array(
					'js_name' => 'openPanelVideoAction',
					'label' => __( 'Open Panel Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'playVideo',
					'available_values' => array(
						'playVideo' => __( 'Play Video', 'accordion-slider' ),
						'none' => __( 'None', 'accordion-slider' )
					),
					'description' => __( 'Sets what the video will do when the panel is opened. Can be set to \'Play Video\' or \'None\'.', 'accordion-slider' )
				),
				'close_panel_video_action' => array(
					'js_name' => 'closePanelVideoAction',
					'label' => __( 'Close Panel Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'pauseVideo',
					'available_values' => array(
						'pauseVideo' => __( 'Pause Video', 'accordion-slider' ),
						'stopVideo' => __( 'Stop Video', 'accordion-slider' )
					),
					'description' => __( 'Sets what the video will do when the panel is closed. Can be set to \'Pause Video\' or \'Stop Video\'.', 'accordion-slider' )
				),
				'play_video_action' => array(
					'js_name' => 'playVideoAction',
					'label' => __( 'Play Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'stopAutoplay',
					'available_values' => array(
						'stopAutoplay' => __( 'Stop Autoplay', 'accordion-slider' ),
						'none' => __( 'None', 'accordion-slider' )
					),
					'description' => __( 'Sets what the accordion will do when a video starts playing. Can be set to \'Stop Autoplay\' or \'None\'.', 'accordion-slider' )
				),
				'pause_video_action' => array(
					'js_name' => 'pauseVideoAction',
					'label' => __( 'Pause Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'none',
					'available_values' => array(
						'startAutoplay' => __( 'Start Autoplay', 'accordion-slider' ),
						'none' => 'None'
					),
					'description' => __( 'Sets what the accordion will do when a video is paused. Can be set to \'Start Autoplay\' or \'None\'.', 'accordion-slider' )
				),
				'end_video_action' => array(
					'js_name' => 'endVideoAction',
					'label' => __( 'End Video Action', 'accordion-slider' ),
					'type' => 'select',
					'default_value' => 'none',
					'available_values' => array(
						'startAutoplay' => __( 'Start Autoplay', 'accordion-slider' ),
						'nextPanel' => __( 'Next Panel', 'accordion-slider' ),
						'replayVideo' => __( 'Replay Video', 'accordion-slider' ),
						'none' => 'None'
					),
					'description' => __( 'Sets what the accordion will do when a video ends. Can be set to \'Start Autoplay\', \'Next Panel\', \'Replay Video\' or \'None\'.', 'accordion-slider' )
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
						'responsive_mode',
						'aspect_ratio',
						'orientation',
						'shadow',
						'panel_distance',
						'panel_overlap',
						'visible_panels',
						'start_panel',
						'start_page',
						'lazy_loading'
					)
				),

				'animations' => array(
					'label' => __( 'Animations', 'accordion-slider' ),
					'list' => array(
						'opened_panel_size',
						'max_opened_panel_size',
						'open_panel_on',
						'close_panels_on_mouse_out',
						'mouse_delay',
						'open_panel_duration',
						'close_panel_duration',
						'page_scroll_duration',
						'page_scroll_easing'
					)
				),

				'autoplay' => array(
					'label' => __( 'Autoplay', 'accordion-slider' ),
					'list' => array(
						'autoplay',
						'autoplay_delay',
						'autoplay_direction',
						'autoplay_on_hover'
					)
				),

				'mouse_wheel' => array(
					'label' => __( 'Mouse Wheel', 'accordion-slider' ),
					'list' => array(
						'mouse_wheel',
						'mouse_wheel_sensitivity',
						'mouse_wheel_target'
					)
				),

				'keyboard' => array(
					'label' => __( 'Keyboard', 'accordion-slider' ),
					'list' => array(
						'keyboard',
						'keyboard_only_on_focus'
					)
				),

				'swap_background' => array(
					'label' => __( 'Swap Background', 'accordion-slider' ),
					'list' => array(
						'swap_background_duration',
						'fade_out_background'
					)
				),

				'touch_swipe' => array(
					'label' => __( 'Touch Swipe', 'accordion-slider' ),
					'list' => array(
						'touch_swipe',
						'touch_swipe_threshold'
					)
				),

				'lightbox' => array(
					'label' => __( 'Lightbox', 'accordion-slider' ),
					'list' => array(
						'lightbox'
					)
				),

				'video' => array(
					'label' => __( 'Video', 'accordion-slider' ),
					'list' => array(
						'open_panel_video_action',
						'close_panel_video_action',
						'play_video_action',
						'pause_video_action',
						'end_video_action'
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
				'posts_post_types' => array(
					'label' => __( 'Post Types', 'accordion-slider' ),
					'type' => 'multiselect',
					'default_value' => array( 'post' ),
					'description' => ''
				),
				'posts_taxonomies' => array(
					'label' => __( 'Taxonomies', 'accordion-slider' ),
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