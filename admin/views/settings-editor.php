<div class="modal-overlay"></div>
<div class="modal-window-container settings-editor">
	<div class="modal-window">
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
									$selected = ( $content_type === $value_name ) ? ' selected="selected"' : '';
									echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
		                        }
							?>
						</select>
					</th>
				</tr>
			</thead>
			<tbody class="content-type-settings">
				<?php
					$this->load_content_type_settings( $content_type, $panel_settings );
				?>
			</tbody>
		</table>

		<div class="buttons">
			<a class="button-secondary save" href="#"><?php _e( 'Save', 'accordion-slider' ); ?></a>
			<a class="button-secondary close" href="#"><?php _e( 'Close', 'accordion-slider' ); ?></a>
		</div>
	</div>
</div>