<li id="layer-settings-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings" data-id="<?php echo esc_attr( $layer_id ); ?>">
	<ul>
		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="tab" id="content-tab-<?php echo esc_attr( $layer_id ); ?>" checked="checked">
			<label for="content-tab-<?php echo esc_attr( $layer_id ); ?>" class="tab-label"><?php _e( 'Content', 'accordion-slider' ); ?></label>
			<div class="setting-fields">
				<textarea class="content"><?php echo isset( $layer_content ) ? stripslashes( esc_textarea( $layer_content ) ) : __( 'New layer', 'accordion-slider' ); ?></textarea>
			</div>
		</li>
		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="tab" id="style-tab-<?php echo $layer_id; ?>">
			<label for="style-tab-<?php echo esc_attr( $layer_id ); ?>" class="tab-label"><?php _e( 'Style', 'accordion-slider' ); ?></label>
			<div class="setting-fields">
				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-width"><?php _e( 'Width', 'accordion-slider' ); ?></label>
				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-width" class="setting" type="text" name="width" value="<?php echo isset( $layer_settings['width'] ) ? esc_attr( $layer_settings['width'] ) : $layer_default_settings['width']['default_value']; ?>" />

				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-height"><?php _e( 'Height', 'accordion-slider' ); ?></label>
				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-height" class="setting" type="text" name="height" value="<?php echo isset( $layer_settings['height'] ) ? esc_attr( $layer_settings['height'] ) : $layer_default_settings['height']['default_value']; ?>" />

				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-position"><?php _e( 'Position', 'accordion-slider' ); ?></label>
				<select id="layer-<?php echo esc_attr( $layer_id ); ?>-position" class="setting" name="position">
					<?php
						foreach ( $layer_default_settings['position']['available_values'] as $value_name => $value_label ) {
							$selected = ( isset( $layer_settings['position'] ) && $value_name === $layer_settings['position'] ) || ( ! isset( $layer_settings['position'] ) && $value_name === $layer_default_settings['position']['default_value'] ) ? ' selected="selected"' : '';
							echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
                        }
					?>
				</select>

				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-horizontal"><?php _e( 'Horizontal', 'accordion-slider' ); ?></label>
				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-horizontal" class="setting" type="text" name="horizontal" value="<?php echo isset( $layer_settings['horizontal'] ) ? esc_attr( $layer_settings['horizontal'] ) : $layer_default_settings['horizontal']['default_value']; ?>" />

				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-vertical"><?php _e( 'Vertical', 'accordion-slider' ); ?></label>
				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-vertical" class="setting" type="text" name="vertical" value="<?php echo isset( $layer_settings['vertical'] ) ? esc_attr( $layer_settings['vertical'] ) : $layer_default_settings['vertical']['default_value']; ?>" />

				<br/><br/>

				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-black-background" class="setting" type="checkbox" name="black_background" <?php echo ( isset( $layer_settings['black_background'] ) && $layer_settings['black_background'] === true ) || ( ! isset( $layer_settings['black_background'] ) &&  $layer_default_settings['black_background']['default_value'] === true ) ? 'checked="checked"' : ''; ?>/>
				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-black-background"><?php _e( 'Black Background', 'accordion-slider' ); ?></label>

				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-white-background" class="setting" type="checkbox" name="white_background" <?php echo ( isset( $layer_settings['white_background'] ) && $layer_settings['white_background'] === true ) || ( ! isset( $layer_settings['white_background'] ) &&  $layer_default_settings['white_background']['default_value'] === true ) ? 'checked="checked"' : ''; ?>/>
				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-white-background"><?php _e( 'White Background', 'accordion-slider' ); ?></label>

				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-padding" class="setting" type="checkbox" name="padding" <?php echo ( isset( $layer_settings['padding'] ) && $layer_settings['padding'] === true ) || ( ! isset( $layer_settings['padding'] ) &&  $layer_default_settings['padding']['default_value'] === true ) ? 'checked="checked"' : ''; ?>/>
				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-padding"><?php _e( 'Padding', 'accordion-slider' ); ?></label>

				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-round-corners" class="setting" type="checkbox" name="round_corners" <?php echo ( isset( $layer_settings['round_corners'] ) && $layer_settings['round_corners'] === true ) || ( ! isset( $layer_settings['round_corners'] ) && $layer_default_settings['round_corners']['default_value'] === true ) ? 'checked="checked"' : ''; ?>/>
				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-round-corners"><?php _e( 'Round Corners', 'accordion-slider' ); ?></label>

				<label for="layer-<?php echo esc_attr( $layer_id ); ?>-custom-class"><?php _e( 'Custom Class', 'accordion-slider' ); ?></label>
				<input id="layer-<?php echo esc_attr( $layer_id ); ?>-custom-class" class="setting" type="text" name="custom_class" value="<?php echo isset( $layer_settings['custom_class'] ) ? esc_attr( $layer_settings['custom_class'] ) : $layer_default_settings['custom_class']['default_value']; ?>" />
			</div>
		</li>

		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="tab" id="effects-tab-<?php echo esc_attr( $layer_id ); ?>">
			<label for="effects-tab-<?php echo esc_attr( $layer_id ); ?>" class="tab-label"><?php _e( 'Effects', 'accordion-slider' ); ?></label>
			<div class="setting-fields">
				<table>
					<tbody>
						<tr>
							<td><?php _e( 'Display', 'accordion-slider' ); ?></td>
							<td>
								<?php
									foreach ( $layer_default_settings['display']['available_values'] as $value_name => $value_label ) {
										$checked = ( isset( $layer_settings['display'] ) && $value_name === $layer_settings['display'] ) || ( ! isset( $layer_settings['display'] ) && $value_name === $layer_default_settings['display']['default_value'] ) ? ' checked="checked"' : '' ;
										echo '<input class="setting" type="radio" name="display-' . esc_attr( $layer_id ) . '" id="display-' . $value_name . '-' . esc_attr( $layer_id ) . '" value="' . $value_name . '"' . $checked . ' /><label for="display-' . $value_name . '-' . esc_attr( $layer_id ) . '">' . $value_label . '</label>';
			                        }
								?>
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Show Effect', 'accordion-slider' ); ?></td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-transition"><?php _e( 'Transition', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-show-transition" class="setting" name="show_transition">
									<?php
										foreach ( $layer_default_settings['show_transition']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['show_transition'] ) && $value_name === $layer_settings['show_transition'] ) || ( ! isset( $layer_settings['show_transition'] ) && $value_name === $layer_default_settings['show_transition']['default_value'] ) ? ' selected="selected"' : '';
				                            echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
				                        }
									?>
								</select>

								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-offset"><?php _e( 'Offset', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-show-offset" class="setting" type="text" name="show_offset" value="<?php echo isset( $layer_settings['show_offset'] ) ? esc_attr( $layer_settings['show_offset'] ) : $layer_default_settings['show_offset']['default_value']; ?>" />

								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-delay"><?php _e( 'Delay', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-show-delay" class="setting" type="text" name="show_delay" value="<?php echo isset( $layer_settings['show_delay'] ) ? esc_attr( $layer_settings['show_delay'] ) : $layer_default_settings['show_delay']['default_value']; ?>" />

								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-duration"><?php _e( 'Duration', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-show-duration" class="setting" type="text" name="show_duration" value="<?php echo isset( $layer_settings['show_duration'] ) ? esc_attr( $layer_settings['show_duration'] ) : $layer_default_settings['show_duration']['default_value']; ?>" />
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Hide Effect', 'accordion-slider' ); ?></td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-transition"><?php _e( 'Transition', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-transition" class="setting" name="hide_transition">
									<?php
										foreach ( $layer_default_settings['hide_transition']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['hide_transition'] ) && $value_name === $layer_settings['hide_transition'] ) || ( ! isset( $layer_settings['hide_transition'] ) && $value_name === $layer_default_settings['hide_transition']['default_value'] ) ? ' selected="selected"' : '';
				                            echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
				                        }
									?>
								</select>

								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-offset"><?php _e( 'Offset', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-offset" class="setting" type="text" name="hide_offset" value="<?php echo isset( $layer_settings['hide_offset'] ) ? esc_attr( $layer_settings['hide_offset'] ) : $layer_default_settings['hide_offset']['default_value']; ?>" />

								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-delay"><?php _e( 'Delay', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-delay" class="setting" type="text" name="hide_delay" value="<?php echo isset( $layer_settings['hide_delay'] ) ? esc_attr( $layer_settings['hide_delay'] ) : $layer_default_settings['hide_delay']['default_value']; ?>" />

								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-duration"><?php _e( 'Duration', 'accordion-slider' ); ?></label>
								<input id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-duration" class="setting" type="text" name="hide_duration" value="<?php echo isset( $layer_settings['hide_duration'] ) ? esc_attr( $layer_settings['hide_duration'] ) : $layer_default_settings['hide_duration']['default_value']; ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</li>
	</ul>
</li>