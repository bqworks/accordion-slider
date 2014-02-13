<li id="layer-settings-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings" data-id="<?php echo esc_attr( $layer_id ); ?>">
	<ul>
		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="tab" id="content-tab-<?php echo esc_attr( $layer_id ); ?>" checked="checked">
			<label for="content-tab-<?php echo esc_attr( $layer_id ); ?>" class="tab-label"><?php _e( 'Content', 'accordion-slider' ); ?></label>
			<div class="setting-fields">
				<textarea><?php echo isset( $layer_content ) ? stripslashes( esc_textarea( $layer_content ) ) : __( 'New layer', 'accordion-slider' ); ?></textarea>
			</div>
		</li>
		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="tab" id="appearance-tab-<?php echo $layer_id; ?>">
			<label for="appearance-tab-<?php echo esc_attr( $layer_id ); ?>" class="tab-label"><?php _e( 'Appearance', 'accordion-slider' ); ?></label>
			<div class="setting-fields">
				<table>
					<tbody>
						<tr>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-display"><?php _e( 'Display', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-display" class="setting" name="display">
									<?php
										foreach ( $layer_default_settings['display']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['display'] ) && $value_name === $layer_settings['display'] ) || ( ! isset( $layer_settings['display'] ) && $value_name === $layer_default_settings['display']['default_value'] ) ? ' selected="selected"' : '';
											echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-width"><?php _e( 'Width', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-width" class="setting" type="text" name="width" value="<?php echo isset( $layer_settings['width'] ) ? esc_attr( $layer_settings['width'] ) : $layer_default_settings['width']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-horizontal"><?php _e( 'Horizontal', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-horizontal" class="setting" type="text" name="horizontal" value="<?php echo isset( $layer_settings['horizontal'] ) ? esc_attr( $layer_settings['horizontal'] ) : $layer_default_settings['horizontal']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-preset-styles"><?php _e( 'Preset styles', 'accordion-slider' ); ?></label>
								<select multiple id="layer-<?php echo esc_attr( $layer_id ); ?>-preset-styles" class="setting" name="preset_styles">
									<?php
										foreach ( $layer_default_settings['preset_styles']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['preset_styles'] ) && in_array( $value_name, $layer_settings['preset_styles'] ) ) || ( ! isset( $layer_settings['preset_styles'] ) && in_array( $value_name === $layer_default_settings['preset_styles']['default_value'] ) ) ? ' selected="selected"' : '';
											echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
				                        }
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-position"><?php _e( 'Position', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-position" class="setting" name="position">
									<?php
										foreach ( $layer_default_settings['position']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['position'] ) && $value_name === $layer_settings['position'] ) || ( ! isset( $layer_settings['position'] ) && $value_name === $layer_default_settings['position']['default_value'] ) ? ' selected="selected"' : '';
											echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-height"><?php _e( 'Height', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-height" class="setting" type="text" name="height" value="<?php echo isset( $layer_settings['height'] ) ? esc_attr( $layer_settings['height'] ) : $layer_default_settings['height']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-vertical"><?php _e( 'Vertical', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-vertical" class="setting" type="text" name="vertical" value="<?php echo isset( $layer_settings['vertical'] ) ? esc_attr( $layer_settings['vertical'] ) : $layer_default_settings['vertical']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-custom-class"><?php _e( 'Custom Class', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-custom-class" class="setting" type="text" name="custom_class" value="<?php echo isset( $layer_settings['custom_class'] ) ? esc_attr( $layer_settings['custom_class'] ) : $layer_default_settings['custom_class']['default_value']; ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</li>

		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="tab" id="animation-tab-<?php echo esc_attr( $layer_id ); ?>">
			<label for="animation-tab-<?php echo esc_attr( $layer_id ); ?>" class="tab-label"><?php _e( 'Animation', 'accordion-slider' ); ?></label>
			<div class="setting-fields">
				<table>
					<tbody>
						<tr>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-transition"><?php _e( 'Show Transition', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-show-transition" class="setting" name="show_transition">
									<?php
										foreach ( $layer_default_settings['show_transition']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['show_transition'] ) && $value_name === $layer_settings['show_transition'] ) || ( ! isset( $layer_settings['show_transition'] ) && $value_name === $layer_default_settings['show_transition']['default_value'] ) ? ' selected="selected"' : '';
				                            echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-offset"><?php _e( 'Show Offset', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-show-offset" class="setting" type="text" name="show_offset" value="<?php echo isset( $layer_settings['show_offset'] ) ? esc_attr( $layer_settings['show_offset'] ) : $layer_default_settings['show_offset']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-delay"><?php _e( 'Show Delay', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-show-delay" class="setting" type="text" name="show_delay" value="<?php echo isset( $layer_settings['show_delay'] ) ? esc_attr( $layer_settings['show_delay'] ) : $layer_default_settings['show_delay']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-duration"><?php _e( 'Show Duration', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-show-duration" class="setting" type="text" name="show_duration" value="<?php echo isset( $layer_settings['show_duration'] ) ? esc_attr( $layer_settings['show_duration'] ) : $layer_default_settings['show_duration']['default_value']; ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-transition"><?php _e( 'Hide Transition', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-transition" class="setting" name="hide_transition">
									<?php
										foreach ( $layer_default_settings['hide_transition']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['hide_transition'] ) && $value_name === $layer_settings['hide_transition'] ) || ( ! isset( $layer_settings['hide_transition'] ) && $value_name === $layer_default_settings['hide_transition']['default_value'] ) ? ' selected="selected"' : '';
				                            echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-offset"><?php _e( 'Hide Offset', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-offset" class="setting" type="text" name="hide_offset" value="<?php echo isset( $layer_settings['hide_offset'] ) ? esc_attr( $layer_settings['hide_offset'] ) : $layer_default_settings['hide_offset']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-delay"><?php _e( 'Hide Delay', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-delay" class="setting" type="text" name="hide_delay" value="<?php echo isset( $layer_settings['hide_delay'] ) ? esc_attr( $layer_settings['hide_delay'] ) : $layer_default_settings['hide_delay']['default_value']; ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-duration"><?php _e( 'Hide Duration', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-duration" class="setting" type="text" name="hide_duration" value="<?php echo isset( $layer_settings['hide_duration'] ) ? esc_attr( $layer_settings['hide_duration'] ) : $layer_default_settings['hide_duration']['default_value']; ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</li>
	</ul>
</li>