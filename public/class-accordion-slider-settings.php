<?php

class Accordion_Slider_Settings {

	protected static $settings = array(
		'appearance' => array(
			'label' => 'Appearance',
			'list' => array(
				'width' => array(
					'label' => 'Width',
					'type' => 'number',
					'default_value' => 800,
					'description' => ''
				),
				'height' => array(
					'label' => 'Height',
					'type' => 'number',
					'default_value' => 400,
					'description' => ''
				),
				'responsive' => array(
					'label' => 'Responsive',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'responsiveMode' => array(
					'label' => 'Responsive Mode',
					'type' => 'select',
					'default_value' => 'auto',
					'available_values' => array(
						'auto' => 'Auto',
						'custom' => 'Custom'
					),
					'description' => ''
				),
				'aspectRatio' => array(
					'label' => 'Aspect Ratio',
					'type' => 'number',
					'default_value' => -1,
					'description' => ''
				),
				'orientation' => array(
					'label' => 'Orientation',
					'type' => 'select',
					'default_value' => 'horizontal',
					'available_values' => array(
						'horizontal' => 'Horizontal',
						'vertical' => 'Vertical'
					),
					'description' => ''
				),
				'shadow' => array(
					'label' => 'Shadow',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'panelDistance' => array(
					'label' => 'Panel Distance',
					'type' => 'number',
					'default_value' => 0,
					'description' => ''
				),
				'panelOverlap' => array(
					'label' => 'Panel Overlap',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'visiblePanels' => array(
					'label' => 'Visible Panels',
					'type' => 'number',
					'default_value' => -1,
					'description' => ''
				),
				'startPanel' => array(
					'label' => 'Start Panel',
					'type' => 'number',
					'default_value' => 0,
					'description' => ''
				),
				'startPage' => array(
					'label' => 'Start Page',
					'type' => 'number',
					'default_value' => 0,
					'description' => ''
				)
			)
		),
		'animations' => array(
			'label' => 'Animations',
			'list' => array(
				'openedPanelSize' => array(
					'label' => 'Opened Panel Size',
					'type' => 'mixed',
					'default_value' => 'max',
					'description' => ''
				),
				'maxOpenedPanelSize' => array(
					'label' => 'Max Opened Panel Size',
					'type' => 'mixed',
					'default_value' => '80%',
					'description' => ''
				),
				'openPanelOn' => array(
					'label' => 'Open Panel On',
					'type' => 'select',
					'default_value' => 'hover',
					'available_values' => array(
						'hover' => 'Hover',
						'click' => 'Click'
					),
					'description' => ''
				),
				'closePanelsOnMouseOut' => array(
					'label' => 'Close Panels On Mouse Out',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'mouseDelay' => array(
					'label' => 'Mouse Delay',
					'type' => 'number',
					'default_value' => 200,
					'description' => ''
				),
				'openPanelDuration' => array(
					'label' => 'Open Panel Duration',
					'type' => 'number',
					'default_value' => 700,
					'description' => ''
				),
				'closePanelDuration' => array(
					'label' => 'Close Panel Duration',
					'type' => 'number',
					'default_value' => 700,
					'description' => ''
				),
				'pageScrollDuration' => array(
					'label' => 'Page Scroll Duration',
					'type' => 'number',
					'default_value' => 500,
					'description' => ''
				),
				'pageScrollEasing' => array(
					'label' => 'Page Scroll Easing',
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
			'label' => 'Autoplay',
			'list' => array(
				'autoplay' => array(
					'label' => 'Autoplay',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'autoplayDelay' => array(
					'label' => 'Autoplay Delay',
					'type' => 'number',
					'default_value' => 5000,
					'description' => ''
				),
				'autoplayDirection' => array(
					'label' => 'Autoplay Direction',
					'type' => 'select',
					'default_value' => 'normal',
					'available_values' => array(
						'normal' => 'Normal',
						'backwards' => 'Backwards'
					),
					'description' => ''
				),
				'autoplayOnHover' => array(
					'label' => 'Autoplay On Hover',
					'type' => 'select',
					'default_value' => 'pause',
					'available_values' => array(
						'pause' => 'Pause',
						'stop' => 'Stop',
						'none' => 'None'
					),
					'description' => ''
				)
			)
		),
		'mouse_wheel' => array(
			'label' => 'Mouse Wheel',
			'list' => array(
				'mouseWheel' => array(
					'label' => 'Mouse Wheel',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'mouseWheelSensitivity' => array(
					'label' => 'Mouse Wheel Sensitivity',
					'type' => 'number',
					'default_value' => 50,
					'description' => ''
				),
				'mouseWheelTarget' => array(
					'label' => 'Mouse Wheel Target',
					'type' => 'select',
					'default_value' => 'panel',
					'available_values' => array(
						'panel' => 'Panel',
						'page' => 'Page'
					),
					'description' => ''
				)
			)
		),
		'keyboard' => array(
			'label' => 'Keyboard',
			'list' => array(
				'keyboard' => array(
					'label' => 'Keyboard',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'keyboardOnlyOnFocus' => array(
					'label' => 'Keyboard Only On Focus',
					'type' => 'boolean',
					'default_value' => false,
					'description' => ''
				)
			)
		),
		'swap_background' => array(
			'label' => 'Swap Background',
			'list' => array(
				'swapBackgroundDuration' => array(
					'label' => 'Swap Background Duration',
					'type' => 'number',
					'default_value' => 700,
					'description' => ''
				),
				'fadeOutBackground' => array(
					'label' => 'Fade Out Background',
					'type' => 'boolean',
					'default_value' => false,
					'description' => ''
				)
			)
		),
		'touch_swipe' => array(
			'label' => 'Touch Swipe',
			'list' => array(
				'touchSwipe' => array(
					'label' => 'Touch Swipe',
					'type' => 'boolean',
					'default_value' => true,
					'description' => ''
				),
				'touchSwipeThreshold' => array(
					'label' => 'Touch Swipe Threshold',
					'type' => 'number',
					'default_value' => 50,
					'description' => ''
				)
			)
		),
		'video' => array(
			'label' => 'Video',
			'list' => array(
				'openPanelVideoAction' => array(
					'label' => 'Open Panel Video Action',
					'type' => 'select',
					'default_value' => 'playVideo',
					'available_values' => array(
						'playVideo' => 'Play Video',
						'none' => 'None'
					),
					'description' => ''
				),
				'closePanelVideoAction' => array(
					'label' => 'Close Panel Video Action',
					'type' => 'select',
					'default_value' => 'pauseVideo',
					'available_values' => array(
						'pauseVideo' => 'PauseV ideo',
						'stopVideo' => 'Stop Video'
					),
					'description' => ''
				),
				'playVideoAction' => array(
					'label' => 'Play Video Action',
					'type' => 'select',
					'default_value' => 'stopAutoplay',
					'available_values' => array(
						'stopAutoplay' => 'Stop Autoplay',
						'none' => 'None'
					),
					'description' => ''
				),
				'pauseVideoAction' => array(
					'label' => 'Pause Video Action',
					'type' => 'select',
					'default_value' => 'none',
					'available_values' => array(
						'startAutoplay' => 'Start Autoplay',
						'none' => 'None'
					),
					'description' => ''
				),
				'endVideoAction' => array(
					'label' => 'End Video Action',
					'type' => 'select',
					'default_value' => 'none',
					'available_values' => array(
						'startAutoplay' => 'Start Autoplay',
						'nextPanel' => 'Next Panel',
						'replayVideo' => 'Replay Video',
						'none' => 'None'
					),
					'description' => ''
				)
			)
		)
	);

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