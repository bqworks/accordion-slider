<tr>
	<td class="label-cell">
		<label><?php _e( 'Post ID', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input class="panel-setting" type="text" name="gallery_post_id" value="<?php echo isset( $panel_settings['gallery_post_id'] ) ? esc_attr( $panel_settings['gallery_post_id'] ) : $panel_default_settings['gallery_post_id']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Limit', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input class="panel-setting" type="text" name="gallery_maximum" value="<?php echo isset( $panel_settings['gallery_maximum'] ) ? esc_attr( $panel_settings['gallery_maximum'] ) : $panel_default_settings['gallery_maximum']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Start At', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input class="panel-setting" type="text" name="gallery_offset" value="<?php echo isset( $panel_settings['gallery_offset'] ) ? esc_attr( $panel_settings['gallery_offset'] ) : $panel_default_settings['gallery_offset']['default_value']; ?>" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Hide Gallery', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input class="panel-setting" type="checkbox" name="gallery_hide_gallery" <?php echo ( isset( $panel_settings['gallery_hide_gallery'] ) && $panel_settings['gallery_hide_gallery'] === true ) || ( ! isset( $panel_settings['gallery_hide_gallery'] ) &&  $panel_default_settings['gallery_hide_gallery']['default_value'] === true ) ? 'checked="checked"' : ''; ?>/>
	</td>
</tr>