<?php

class Accordion_Slider_Settings {

	protected static $settings = array();

	protected static $settings_map = array(
		'width' => 'appearance',
		'height' => 'appearance',
		'responsive' => 'appearance',
		'responsiveMode' => 'appearance',
		'aspectRatio' => 'appearance',
		'orientation' => 'appearance',
		'shadow' => 'appearance',
		'panelDistance' => 'appearance',
		'panelOverlap' => 'appearance',
		'visiblePanels' => 'appearance',
		'startPanel' => 'appearance',
		'startPage' => 'appearance',
		'openedPanelSize' => 'animations',
		'maxOpenedPanelSize' => 'animations',
		'openPanelOn' => 'animations',
		'closePanelsOnMouseOut' => 'animations',
		'mouseDelay' => 'animations',
		'openPanelDuration' => 'animations',
		'closePanelDuration' => 'animations',
		'pageScrollDuration' => 'animations',
		'pageScrollEasing' => 'animations',
		'autoplay' => 'autoplay',
		'autoplayDelay' => 'autoplay',
		'autoplayDirection' => 'autoplay',
		'autoplayOnHover' => 'autoplay',
		'mouseWheel' => 'mouse_wheel',
		'mouseWheelSensitivity' => 'mouse_wheel',
		'mouseWheelTarget' => 'mouse_wheel',
		'keyboard' => 'keyboard',
		'keyboardOnlyOnFocus' => 'keyboard',
		'swapBackgroundDuration' => 'swap_background',
		'fadeOutBackground' => 'swap_background',
		'touchSwipe' => 'touch_swipe',
		'touchSwipeThreshold' => 'touch_swipe',
		'openPanelVideoAction' => 'video',
		'closePanelVideoAction' => 'video',
		'playVideoAction' => 'video',
		'pauseVideoAction' => 'video',
		'endVideoAction' => 'video'
	);

	protected static $breakpoint_settings = array('width', 'height', 'responsive', 'responsiveMode', 'aspectRatio', 'orientation', 'panelDistance', 'visiblePanels');
	

	/*
		Return the settings
	*/
	public static function getSettings() {
		if ( empty( self::$settings ) ) {
			self::$settings = array(
				'appearance' => array(
					'label' => __( 'Appearance', 'accordion-slider' ),
					'list' => array(
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
						)
					)
				),
				'animations' => array(
					'label' =>  __( 'Animations', 'accordion-slider' ),
					'list' => array(
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
						)
					)
				),
				'autoplay' => array(
					'label' => __( 'Autoplay', 'accordion-slider' ),
					'list' => array(
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
						)
					)
				),
				'mouse_wheel' => array(
					'label' => __( 'Mouse Wheel', 'accordion-slider' ),
					'list' => array(
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
						)
					)
				),
				'keyboard' => array(
					'label' => __( 'Keyboard', 'accordion-slider' ),
					'list' => array(
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
						)
					)
				),
				'swap_background' => array(
					'label' => __( 'Swap Background', 'accordion-slider' ),
					'list' => array(
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
						)
					)
				),
				'touch_swipe' => array(
					'label' => __( 'Touch Swipe', 'accordion-slider' ),
					'list' => array(
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
						)
					)
				),
				'video' => array(
					'label' => __( 'Video', 'accordion-slider' ),
					'list' => array(
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
					)
				)
			);
		}

		return self::$settings;
	}

	/*
		Return the breakpoint settings
	*/
	public static function getBreakpointSettings() {
		return self::$breakpoint_settings;
	}

	/*
		Return the information of a setting
	*/
	public static function getSettingInfo( $setting_name ) {
		return self::$settings[self::$settings_map[$setting_name]]['list'][$setting_name];
	}
}