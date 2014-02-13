<div class="modal-overlay"></div>
<div class="modal-window-container layers-editor">
	<div class="modal-window">
		<span class="close-x"></span>

		<div class="viewport"></div>

		<div class="controls">
			<div class="left">
				<div class="buttons">
					<a class="button-secondary add-new-layer" href="#">+</a>
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
						$layer_content = $layer[ 'content' ];
						$layer_settings = $layer[ 'settings' ];

						include( 'layer-settings.php' );
					}
				?>
			</ul>
		</div>
		
	</div>
</div>