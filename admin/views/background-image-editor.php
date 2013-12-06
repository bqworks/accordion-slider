<div class="modal-overlay"></div>
<div class="background-image-editor">
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
		<table class="data-fields">
			<tbody>
				<tr>
					<td><label><?php _e( 'Source:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="background_source" value="<?php echo isset( $data['background_source'] ) ? $data['background_source'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label><?php _e( 'Alt:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="background_alt" value="<?php echo isset( $data['background_alt'] ) ? $data['background_alt'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label><?php _e( 'Title:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="background_title" value="<?php echo isset( $data['background_title'] ) ? $data['background_title'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label><?php _e( 'Retina Source:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="background_retina_source" value="<?php echo isset( $data['background_retina_source'] ) ? $data['background_retina_source'] : ''; ?>" /></td>
				</tr>
			</tbody>
		</table>
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
		<table class="data-fields">
			<tbody>
				<tr>
					<td><label><?php _e( 'Source:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="opened_background_source" value="<?php echo isset( $data['opened_background_source'] ) ? esc_attr( $data['opened_background_source'] ) : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label><?php _e( 'Alt:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="opened_background_alt" value="<?php echo isset( $data['opened_background_alt'] ) ? esc_attr( $data['opened_background_alt'] ) : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label><?php _e( 'Title:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="opened_background_title" value="<?php echo isset( $data['opened_background_title'] ) ? esc_attr( $data['opened_background_title'] ) : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label><?php _e( 'Retina Source:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="opened_background_retina_source" value="<?php echo isset( $data['opened_background_retina_source'] ) ? esc_attr( $data['opened_background_retina_source'] ) : ''; ?>" /></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="fieldset link">
		<h3 class="heading"><?php _e( 'Link', 'accordion-slider' ); ?><span class="clear-fieldset"><?php _e( 'Clear', 'accordion-slider' ); ?></span></h3>
		<table class="data-fields">
			<tbody>
				<tr>
					<td><label><?php _e( 'URL:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="background_link" value="<?php echo isset( $data['background_link'] ) ?  esc_attr( $data['background_link'] ) : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label><?php _e( 'Title:', 'accordion-slider' ); ?></label></td>
					<td><input class="field" type="text" name="background_link_title" value="<?php echo isset( $data['background_link_title'] ) ? esc_attr( $data['background_link_title'] ) : ''; ?>" /></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="buttons">
		<a class="button-secondary add-opened-background-image" href="#"><?php _e( 'Add Opened Background Image', 'accordion-slider' ); ?></a>
		<a class="button-secondary add-link" href="#"><?php _e( 'Add Link', 'accordion-slider' ); ?></a>
		<a class="button-secondary save" href="#"><?php _e( 'Save', 'accordion-slider' ); ?></a>
		<a class="button-secondary close" href="#"><?php _e( 'Close', 'accordion-slider' ); ?></a>
	</div>
</div>