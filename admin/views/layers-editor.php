<div class="modal-overlay"></div>
<div class="modal-window-container layers-editor">
	<div class="modal-window">
		<span class="close-x"></span>

		<div class="viewport"></div>

		<div class="controls">
			<div class="left">
				<div class="buttons">
					<div class="add-layer-group">
                        <a class="button-secondary add-new-layer" href="#">+</a>
                        <ul class="layer-type">
                            <li><a href="#" data-type="video"><?php _e( 'Video Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="div"><?php _e( 'DIV Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="image"><?php _e( 'Image Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="heading"><?php _e( 'Heading Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="paragraph"><?php _e( 'Paragraph Layer', 'accordion-slider' ); ?></a></li>
                        </ul>
                    </div>
					
					<a class="button-secondary delete-layer" href="#">-</a>
					<a class="button-secondary duplicate-layer" href="#">=</a>
				</div>

				<ul class="layers-list">
					<?php
						foreach ( $layers as $layer ) {
							$layer_id = $layer[ 'id' ];
							$layer_name = $layer[ 'name' ];
							echo '<li class="layers-list-item" data-id="' . $layer_id . '">' . $layer_name . '</li>';
						}
					?>
				</ul>
			</div>

			<ul class="right layers-settings">
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