<div class="modal-overlay"></div>
<div class="modal-window-container background-image-editor <?php echo $content_class;?>">
	<div class="modal-window">
		<span class="close-x"></span>
		<div class="fieldset background-image">
			<h3 class="heading"><?php _e( 'Background Image', 'accordion-slider' ); ?><span class="clear-fieldset"><?php _e( 'Clear', 'accordion-slider' ); ?></span></h3>
			<div class="image-loader">
				<?php
					if ( isset( $data['background_source'] ) && $data['background_source'] !== '' ) {
						echo '<img src="' . esc_url( $data['background_source'] ) . '" />';
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

		<?php
            $hide_info = get_option( 'accordion_slider_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
            <div class="inline-info background-editor-info">
                <input type="checkbox" id="show-hide-info" class="show-hide-info">
				<label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'accordion-slider' ); ?></label>
				<label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'accordion-slider' ); ?></label>
				
				<div class="info-content">
	                <p><?php _e( 'The <i>Background Image</i> represents the main image that will be visible when the accordion loads.', 'accordion-slider' ); ?></p>
	                <p><?php _e( 'The <i>Opened Background Image</i> is optional and represents the image that will fade in over the main image when the panel is opened.', 'accordion-slider' ); ?></p>
	                <p><?php _e( 'The <i>Link</i> is optional and will be added either for the <i>Background Image</i>, or for the <i>Opened Background Image</i> if one was added.', 'accordion-slider' ); ?></p>
					
					<?php
						if ( $content_type === 'posts' || $content_type === 'gallery' || $content_type === 'flickr' ) {
					?>
						<input type="checkbox" id="show-hide-dynamic-tags" class="show-hide-dynamic-tags">
						<label for="show-hide-dynamic-tags" class="show-dynamic-tags"><?php _e( 'Show dynamic tags', 'accordion-slider' ); ?></label>
						<label for="show-hide-dynamic-tags" class="hide-dynamic-tags"><?php _e( 'Hide dynamic tags', 'accordion-slider' ); ?></label>
					<?php
						}

						if ( $content_type === 'posts' ) {
					?>
							<table class="dynamic-tags">
								<tbody>
									<tr>
										<td><b>[as_image_src]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The URL of the post\'s featured image. It accepts an optional parameter to specify the size of the image: [as_image_src.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_alt]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The <i>alt</i> text of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_title]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The title of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_description]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The description of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_caption]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The caption of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_title]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s title.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_link_url]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s link.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_custom.<i>name</i>]</b></td>
										<td> - </td>
										<td><p><?php _e( 'Returns the value from a custom field. The <i>name</i> parameter indicates the name of the custom field.', 'accordion-slider' ); ?></p></td>
									</tr>
								</tbody>
							</table>
	            	<?php
	            		} else if ( $content_type === 'gallery' ) {
	            	?>
	            			<table class="dynamic-tags">
								<tbody>
									<tr>
										<td><b>[as_image_src]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The URL of the gallery image. It accepts an optional parameter to specify the size of the image: [as_image_src.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_alt]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The <i>alt</i> text of the gallery image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_title]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The title of the gallery image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_description]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The description of the gallery image.', 'accordion-slider' ); ?></p></td>
									</tr>
								</tbody>
							</table>
	            	<?php
	            		} else if ( $content_type === 'flickr' ) {
	            	?>
	            			<table class="dynamic-tags">
								<tbody>
									<tr>
										<td><b>[as_image_src]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The URL of the Flickr image. It accepts an optional parameter to specify the size of the image: [as_image_src.thumbnail]. Accepted sizes are: <i>square</i>, <i>thumbnail</i>, <i>small</i>, <i>medium</i>, <i>medium_640</i>, <i>large</i>. The default value is <i>medium</i>.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_description]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The description of the Flickr image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_image_link]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The link of the Flickr image.', 'accordion-slider' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[as_user_link]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The link to the profile of the image\'s owner.', 'accordion-slider' ); ?></p></td>
									</tr>
								</tbody>
							</table>
	            	<?php
	            		}
	            	?>
	            </div>
            </div>
        <?php
            }
        ?>
	</div>
</div>