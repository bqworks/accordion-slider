<div class="modal-overlay"></div>
<div class="settings-editor<?php echo isset( $panel_settings['content_type'] ) ? ' ' . $panel_settings['content_type'] : ''; ?>">
	<span class="close-x"></span>
	<h3 class="heading"><?php _e( 'Panel Settings', 'accordion-slider' ); ?></h3>
	
	<table>
		<thead>
			<tr>
				<th class="label-cell">
					<label>Content Type:</label>
				</th>
				<th class="setting-cell">
					<select class="panel-setting" name="content_type">
						<?php
							foreach ( $panel_default_settings['content_type']['available_values'] as $value_name => $value_label ) {
								$selected = ( isset( $panel_settings['content_type'] ) && $value_name === $panel_settings['content_type'] ) || ( ! isset( $panel_settings['content_type'] ) && $value_name === $panel_default_settings['content_type']['default_value'] ) ? ' selected="selected"' : '';
								echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
	                        }
						?>
					</select>
				</th>
			</tr>
		</thead>
		<tbody class="content-type-settings">
			<?php
				$content_type = isset( $panel_settings['content_type'] ) ? $panel_settings['content_type'] : '';

				if ( $content_type === 'posts' ) {
					$post_names = $this->get_post_names();

					include( 'posts-content-settings.php' );
				} else if ( $content_type === 'gallery' ) {
					include( 'gallery-images-settings.php' );
				} else if ( $content_type === 'flickr' ) {
					include( 'flickr-settings.php' );
				}
			?>
		</tbody>
	</table>

	<div class="buttons">
		<a class="button-secondary save" href="#"><?php _e( 'Save', 'accordion-slider' ); ?></a>
		<a class="button-secondary close" href="#"><?php _e( 'Close', 'accordion-slider' ); ?></a>
	</div>
</div>