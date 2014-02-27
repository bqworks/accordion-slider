<div class="modal-overlay"></div>
<div class="modal-window-container background-image-editor <?php echo $content_class;?>">
	<div class="modal-window">
		<span class="close-x"></span>
		<div class="fieldset background-image">
			<h3 class="heading"><?php _e( 'Background Image', 'accordion-slider' ); ?><span class="clear-fieldset"><?php _e( 'Clear', 'accordion-slider' ); ?></span></h3>
			<div class="image-loader">
				<?php
					if ( isset( $data['background_source'] ) && $data['background_source'] !== '' ) {
						echo '<img src="' . $data['background_source'] . '" />';
					} else {
						echo '<p class="no-image">' . __( 'Click to add image', 'accordion-slider' ) . '</p>';
					}
				?>
			</div>
			<table>
				<tbody>
					<tr>
						<td><label for="background-source"><?php _e( 'Source:', 'accordion-slider' ); ?></label></td>
						<td><input id="background-source" class="field" type="text" name="background_source" value="<?php echo isset( $data['background_source'] ) ? esc_attr( $data['background_source'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="background-alt"><?php _e( 'Alt:', 'accordion-slider' ); ?></label></td>
						<td><input id="background-alt" class="field" type="text" name="background_alt" value="<?php echo isset( $data['background_alt'] ) ? esc_attr( $data['background_alt'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="background-title"><?php _e( 'Title:', 'accordion-slider' ); ?></label></td>
						<td><input id="background-title" class="field" type="text" name="background_title" value="<?php echo isset( $data['background_title'] ) ? esc_attr( $data['background_title'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="background-retina-source"><?php _e( 'Retina Source:', 'accordion-slider' ); ?></label></td>
						<td><input id="background-retina-source" class="field" type="text" name="background_retina_source" value="<?php echo isset( $data['background_retina_source'] ) ? esc_attr( $data['background_retina_source'] ) : ''; ?>" /><span class="retina-loader"></span></td>
					</tr>
				</tbody>
			</table>
			<input class="field" type="hidden" name="background_width" value="<?php echo isset( $data['background_width'] ) ? esc_attr( $data['background_width'] ) : ''; ?>" />
			<input class="field" type="hidden" name="background_height" value="<?php echo isset( $data['background_height'] ) ? esc_attr( $data['background_height'] ) : ''; ?>" />
		</div>

		<div class="fieldset opened-background-image">
			<h3 class="heading"><?php _e( 'Opened Background Image', 'accordion-slider' ); ?><span class="clear-fieldset"><?php _e( 'Clear', 'accordion-slider' ); ?></span></h3>
			<div class="image-loader">
				<?php
					if ( isset( $data['opened_background_source'] ) && $data['opened_background_source'] !== '' ) {
						echo '<img src="' . esc_url( $data['opened_background_source'] ) . '" />';
					} else {
						echo '<p class="no-image">' . __( 'Click to add image', 'accordion-slider' ) . '</p>';
					}
				?>
			</div>
			<table>
				<tbody>
					<tr>
						<td><label for="opened-background-source"><?php _e( 'Source:', 'accordion-slider' ); ?></label></td>
						<td><input id="opened-background-source" class="field" type="text" name="opened_background_source" value="<?php echo isset( $data['opened_background_source'] ) ? esc_attr( $data['opened_background_source'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="opened-background-alt"><?php _e( 'Alt:', 'accordion-slider' ); ?></label></td>
						<td><input id="opened-background-alt" class="field" type="text" name="opened_background_alt" value="<?php echo isset( $data['opened_background_alt'] ) ? esc_attr( $data['opened_background_alt'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="opened-background-title"><?php _e( 'Title:', 'accordion-slider' ); ?></label></td>
						<td><input id="opened-background-title" class="field" type="text" name="opened_background_title" value="<?php echo isset( $data['opened_background_title'] ) ? esc_attr( $data['opened_background_title'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="opened-background-retina-source"><?php _e( 'Retina Source:', 'accordion-slider' ); ?></label></td>
						<td><input id="opened-background-retina-source" class="field" type="text" name="opened_background_retina_source" value="<?php echo isset( $data['opened_background_retina_source'] ) ? esc_attr( $data['opened_background_retina_source'] ) : ''; ?>" /><span class="retina-loader"></span></td>
					</tr>
				</tbody>
			</table>
			<input class="field" type="hidden" name="opened_background_width" value="<?php echo isset( $data['opened_background_width'] ) ? esc_attr( $data['opened_background_width'] ) : ''; ?>" />
			<input class="field" type="hidden" name="opened_background_height" value="<?php echo isset( $data['opened_background_height'] ) ? esc_attr( $data['opened_background_height'] ) : ''; ?>" />
		</div>

		<div class="fieldset link">
			<h3 class="heading"><?php _e( 'Link', 'accordion-slider' ); ?><span class="clear-fieldset"><?php _e( 'Clear', 'accordion-slider' ); ?></span></h3>
			<table>
				<tbody>
					<tr>
						<td><label for="background-link"><?php _e( 'URL:', 'accordion-slider' ); ?></label></td>
						<td><input id="background-link" class="field" type="text" name="background_link" value="<?php echo isset( $data['background_link'] ) ?  esc_attr( $data['background_link'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="background-link-title"><?php _e( 'Title:', 'accordion-slider' ); ?></label></td>
						<td><input id="background-link-title" class="field" type="text" name="background_link_title" value="<?php echo isset( $data['background_link_title'] ) ? esc_attr( $data['background_link_title'] ) : ''; ?>" /></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>