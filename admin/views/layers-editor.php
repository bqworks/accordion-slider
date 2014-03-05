<div class="modal-overlay"></div>
<div class="modal-window-container layers-editor">
	<div class="modal-window">
		<span class="close-x"></span>

		<div class="layer-viewport"></div>

		<div class="layer-controls">
			<div class="layer-controls-left">
				<div class="layer-buttons">
					<div class="add-layer-group">
                        <a class="add-new-layer" href="#" title="<?php _e( 'Add Layer', 'accordion-slider' ); ?>"><?php _e( 'Add', 'accordion-slider' ); ?></a>
                        <ul class="layer-type">
                            <li><a href="#" data-type="video"><?php _e( 'Video Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="div"><?php _e( 'DIV Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="image"><?php _e( 'Image Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="heading"><?php _e( 'Heading Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="paragraph"><?php _e( 'Paragraph Layer', 'accordion-slider' ); ?></a></li>
                        </ul>
                    </div>
					
					<a class="delete-layer disabled" href="#" title="<?php _e( 'Delete Layer', 'accordion-slider' ); ?>"><?php _e( 'Delete', 'accordion-slider' ); ?></a>
					<a class="duplicate-layer disabled" href="#" title="<?php _e( 'Duplicate Layer', 'accordion-slider' ); ?>"><?php _e( 'Duplicate', 'accordion-slider' ); ?></a>
				</div>

				<ul class="list-layers">
					<?php
						foreach ( $layers as $layer ) {
							$layer_id = $layer[ 'id' ];
							$layer_name = $layer[ 'name' ];
							echo '<li class="list-layer" data-id="' . $layer_id . '">' . $layer_name . '</li>';
						}
					?>
				</ul>
			</div>

			<ul class="layer-controls-right layers-settings">
				<?php
					foreach ( $layers as $layer ) {
						$layer_id = $layer[ 'id' ];
						$layer_type = $layer[ 'type' ];
						$layer_settings = $layer[ 'settings' ];

						include( 'layer-settings.php' );
					}
				?>
			</ul>
		</div>
		
	</div>
</div>