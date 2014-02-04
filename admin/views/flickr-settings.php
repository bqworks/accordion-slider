<tr>
	<td class="label-cell">
		<label for="flickr-api-key"><?php _e( 'API Key', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input id="flickr-api-key" class="panel-setting" type="text" name="flickr_api_key" value="<?php echo isset( $panel_settings['flickr_api_key'] ) ? esc_attr( $panel_settings['flickr_api_key'] ) : $panel_default_settings['flickr_api_key']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="flickr-load-by"><?php _e( 'Load By', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="flickr-load-by" class="panel-setting" name="flickr_load_by">
			<?php
				foreach ( $panel_default_settings['flickr_load_by']['available_values'] as $value_name => $value_label ) {
					$selected = ( isset( $panel_settings['flickr_load_by'] ) && $value_name === $panel_settings['flickr_load_by'] ) || ( ! isset( $panel_settings['flickr_load_by'] ) && $value_name === $panel_default_settings['flickr_load_by']['default_value'] ) ? ' selected="selected"' : '';
					echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
	            }
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="flickr-id"><?php _e( 'ID', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input id="flickr-id" class="panel-setting" type="text" name="flickr_id" value="<?php echo isset( $panel_settings['flickr_id'] ) ? esc_attr( $panel_settings['flickr_id'] ) : $panel_default_settings['flickr_id']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="flickr-maximum"><?php _e( 'Limit', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input id="flickr-maximum" class="panel-setting" type="text" name="flickr_maximum" value="<?php echo isset( $panel_settings['flickr_maximum'] ) ? esc_attr( $panel_settings['flickr_maximum'] ) : $panel_default_settings['flickr_maximum']['default_value']; ?>" />
	</td>
</tr>