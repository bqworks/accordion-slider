<div class="breakpoint">
	<div class="breakpoint-header">
		<?php _e( 'Window maximum width:', 'accordion-slider' ); ?><input type="text" name="breakpoint_width" value="<?php echo isset( $breakpoint_settings['breakpoint_width'] ) ? esc_attr( $breakpoint_settings['breakpoint_width'] ) : ( isset( $width ) ? $width : '' ); ?>" />
		<span class="remove-breakpoint"></span>
	</div>
	<table>
		<tbody class="breakpoint-settings">
			<?php
				if ( isset( $breakpoint_settings ) && ! empty( $breakpoint_settings ) ) {
					foreach ( $breakpoint_settings as $setting_name => $setting_value ) {
						if ( $setting_name !== 'breakpoint_width' ) {
							echo $this->create_breakpoint_setting( $setting_name, $setting_value );
						}
                    }
				}
			?>
		</tbody>
	</table>
	
	<div class="add-setting-group">
		<select class="setting-selector">
			<?php
				$breakpoint_settings = Accordion_Slider_Settings::getBreakpointSettings();

				foreach ( $breakpoint_settings as $setting_name ) {
					if ( $setting_name !== 'breakpoint_width' ) {
						$setting = Accordion_Slider_Settings::getSettings( $setting_name );
						echo '<option value="' . $setting_name . '">' . $setting['label'] . '</option>';
					}
				}
			?>
		</select>
		<a class="button add-setting" href="#"><?php _e( 'Add Option', 'accordion-slider' ); ?></a>
	</div>
</div>