<li id="layer-settings-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings" data-id="<?php echo esc_attr( $layer_id ); ?>">
	<ul>
		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings-tab" id="content-tab-<?php echo esc_attr( $layer_id ); ?>" checked="checked">
			<label for="content-tab-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings-tab-label"><?php _e( 'Content', 'accordion-slider' ); ?></label>
			<div class="layer-setting-fields layer-content-fields">

				<?php 
					if ( $layer_type === 'paragraph' ) {
				?>
						<textarea name="text"><?php echo isset( $layer[ 'text' ] ) ? stripslashes( esc_textarea( $layer[ 'text' ] ) ) : 'New layer'; ?></textarea>
				<?php
					} else if ( $layer_type === 'heading' ) {
				?>
						<table>
							<tbody>
								<tr>
									<td>
										<label for="layer-<?php echo esc_attr( $layer_id ); ?>-heading-type" class="heading-type-label"><?php _e( 'Heading Type', 'accordion-slider' ); ?></label>
										<select id="layer-<?php echo esc_attr( $layer_id ); ?>-heading-type" name="heading_type">
											<?php
												foreach ( $layer_default_settings['heading_type']['available_values'] as $value_name => $value_label ) {
													$selected = ( isset( $layer['heading_type'] ) && $value_name === $layer['heading_type'] ) || ( ! isset( $layer['heading_type'] ) && $value_name === $layer_default_settings['heading_type']['default_value'] ) ? ' selected="selected"' : '';
													echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
						                        }
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<textarea name="text" class="heading-text"><?php echo isset( $layer[ 'text' ] ) ? stripslashes( esc_textarea( $layer[ 'text' ] ) ) : 'New layer'; ?></textarea>
									</td>
								</tr>
							</tbody>
						</table>
				<?php
					} else if ( $layer_type === 'image' ) {
				?>
						<table>
							<tbody>
								<tr>
									<td>
										<label for="layer-<?php echo esc_attr( $layer_id ); ?>-image-source"><?php _e( 'Source', 'accordion-slider' ); ?></label>
										<div class="layer-image-input">
											<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-image-source" name="image_source" value="<?php echo isset( $layer['image_source'] ) ? esc_attr( $layer['image_source'] ) : ''; ?>" />
											<span class="layer-image-loader"></span>
										</div>
									</td>
									<td>
										<label for="layer-<?php echo esc_attr( $layer_id ); ?>-image-alt"><?php _e( 'Alt', 'accordion-slider' ); ?></label>
										<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-image-alt" name="image_alt" value="<?php echo isset( $layer['image_alt'] ) ? esc_attr( $layer['image_alt'] ) : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td>
										<label for="layer-<?php echo esc_attr( $layer_id ); ?>-image-link"><?php _e( 'Link', 'accordion-slider' ); ?></label>
										<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-image-link" name="image_link" value="<?php echo isset( $layer['image_link'] ) ? esc_attr( $layer['image_link'] ) : ''; ?>" />
									</td>
									<td>
										<label for="layer-<?php echo esc_attr( $layer_id ); ?>-image-retina"><?php _e( 'Retina', 'accordion-slider' ); ?></label>
										<div class="layer-image-input">
											<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-image-retina" name="image_retina" value="<?php echo isset( $layer['image_retina'] ) ? esc_attr( $layer['image_retina'] ) : ''; ?>" />
											<span class="layer-image-loader"></span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
				<?php
					} else if ( $layer_type === 'div' ) {
				?>
						<textarea name="text"><?php echo isset( $layer[ 'text' ] ) ? stripslashes( esc_textarea( $layer[ 'text' ] ) ) : '<h3>New layer title</h3><p>New layer description</p>'; ?></textarea>
				<?php
					} else if ( $layer_type === 'video' ) {
				?>
						<label><?php _e( 'Video Embed/Code', 'accordion-slider' ); ?></label>
						<textarea name="text" class="video-text"><?php echo isset( $layer[ 'text' ] ) ? stripslashes( esc_textarea( $layer[ 'text' ] ) ) : ''; ?></textarea>
				<?php
					}
				?>
			</div>
		</li>
		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings-tab" id="appearance-tab-<?php echo esc_attr( $layer_id ); ?>">
			<label for="appearance-tab-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings-tab-label"><?php _e( 'Appearance', 'accordion-slider' ); ?></label>
			<div class="layer-setting-fields">
				<table>
					<tbody>
						<tr>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-display"><?php _e( 'Display', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-display" class="setting" name="display">
									<?php
										foreach ( $layer_default_settings['display']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['display'] ) && $value_name === $layer_settings['display'] ) || ( ! isset( $layer_settings['display'] ) && $value_name === $layer_default_settings['display']['default_value'] ) ? ' selected="selected"' : '';
											echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-horizontal"><?php _e( 'Horizontal', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-horizontal" class="setting" name="horizontal" value="<?php echo isset( $layer_settings['horizontal'] ) ? esc_attr( $layer_settings['horizontal'] ) : esc_attr( $layer_default_settings['horizontal']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-width"><?php _e( 'Width', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-width" class="setting" name="width" value="<?php echo isset( $layer_settings['width'] ) ? esc_attr( $layer_settings['width'] ) : esc_attr( $layer_default_settings['width']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-preset-styles"><?php _e( 'Preset styles', 'accordion-slider' ); ?></label>
								<select multiple id="layer-<?php echo esc_attr( $layer_id ); ?>-preset-styles" class="setting" name="preset_styles">
									<?php
										foreach ( $layer_default_settings['preset_styles']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['preset_styles'] ) && in_array( $value_name, $layer_settings['preset_styles'] ) ) || ( ! isset( $layer_settings ) && in_array( $value_name, $layer_default_settings['preset_styles']['default_value'] ) ) ? ' selected="selected"' : '';
											echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
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
											echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-vertical"><?php _e( 'Vertical', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-vertical" class="setting" name="vertical" value="<?php echo isset( $layer_settings['vertical'] ) ? esc_attr( $layer_settings['vertical'] ) : esc_attr( $layer_default_settings['vertical']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-height"><?php _e( 'Height', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-height" class="setting" name="height" value="<?php echo isset( $layer_settings['height'] ) ? esc_attr( $layer_settings['height'] ) : esc_attr( $layer_default_settings['height']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-custom-class"><?php _e( 'Custom Class', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-custom-class" class="setting" name="custom_class" value="<?php echo isset( $layer_settings['custom_class'] ) ? esc_attr( $layer_settings['custom_class'] ) : esc_attr( $layer_default_settings['custom_class']['default_value'] ); ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</li>

		<li>
			<input type="radio" name="tab-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings-tab" id="animation-tab-<?php echo esc_attr( $layer_id ); ?>">
			<label for="animation-tab-<?php echo esc_attr( $layer_id ); ?>" class="layer-settings-tab-label"><?php _e( 'Animation', 'accordion-slider' ); ?></label>
			<div class="layer-setting-fields">
				<table>
					<tbody>
						<tr>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-transition"><?php _e( 'Show Transition', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-show-transition" class="setting" name="show_transition">
									<?php
										foreach ( $layer_default_settings['show_transition']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['show_transition'] ) && $value_name === $layer_settings['show_transition'] ) || ( ! isset( $layer_settings['show_transition'] ) && $value_name === $layer_default_settings['show_transition']['default_value'] ) ? ' selected="selected"' : '';
				                            echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-offset"><?php _e( 'Show Offset', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-show-offset" class="setting" name="show_offset" value="<?php echo isset( $layer_settings['show_offset'] ) ? esc_attr( $layer_settings['show_offset'] ) : esc_attr( $layer_default_settings['show_offset']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-delay"><?php _e( 'Show Delay', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-show-delay" class="setting" name="show_delay" value="<?php echo isset( $layer_settings['show_delay'] ) ? esc_attr( $layer_settings['show_delay'] ) : esc_attr( $layer_default_settings['show_delay']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-show-duration"><?php _e( 'Show Duration', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-show-duration" class="setting" name="show_duration" value="<?php echo isset( $layer_settings['show_duration'] ) ? esc_attr( $layer_settings['show_duration'] ) : esc_attr( $layer_default_settings['show_duration']['default_value'] ); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-transition"><?php _e( 'Hide Transition', 'accordion-slider' ); ?></label>
								<select id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-transition" class="setting" name="hide_transition">
									<?php
										foreach ( $layer_default_settings['hide_transition']['available_values'] as $value_name => $value_label ) {
											$selected = ( isset( $layer_settings['hide_transition'] ) && $value_name === $layer_settings['hide_transition'] ) || ( ! isset( $layer_settings['hide_transition'] ) && $value_name === $layer_default_settings['hide_transition']['default_value'] ) ? ' selected="selected"' : '';
				                            echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
				                        }
									?>
								</select>
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-offset"><?php _e( 'Hide Offset', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-offset" class="setting" name="hide_offset" value="<?php echo isset( $layer_settings['hide_offset'] ) ? esc_attr( $layer_settings['hide_offset'] ) : esc_attr( $layer_default_settings['hide_offset']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-delay"><?php _e( 'Hide Delay', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-delay" class="setting" name="hide_delay" value="<?php echo isset( $layer_settings['hide_delay'] ) ? esc_attr( $layer_settings['hide_delay'] ) : esc_attr( $layer_default_settings['hide_delay']['default_value'] ); ?>" />
							</td>
							<td>
								<label for="layer-<?php echo esc_attr( $layer_id ); ?>-hide-duration"><?php _e( 'Hide Duration', 'accordion-slider' ); ?></label>
								<input type="text" id="layer-<?php echo esc_attr( $layer_id ); ?>-hide-duration" class="setting" name="hide_duration" value="<?php echo isset( $layer_settings['hide_duration'] ) ? esc_attr( $layer_settings['hide_duration'] ) : esc_attr( $layer_default_settings['hide_duration']['default_value'] ); ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</li>
	</ul>
</li>