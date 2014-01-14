<div class="modal-overlay"></div>
<div class="layers-editor">
	<div class="viewport">
		
	</div>

	<div class="controls">
		<ul class="left layers-list">
			<?php
				foreach ( $layers as $layer ) {
					$layer_id = $layer[ 'id' ];
					$layer_name = $layer[ 'name' ];
					echo '<li class="layers-list-item" data-id="' . $layer_id . '">' . $layer_name . '</li>';
				}
			?>
		</ul>

		<ul class="right layers-settings">
			<?php
				foreach ( $layers as $layer ) {
					$layer_id = $layer[ 'id' ];
					$layer_settings = $layer[ 'settings' ];
					include( 'layer-settings.php' );
				}
			?>
		</ul>
	</div>
	
	<div class="buttons">
		<a class="button-secondary add-new-layer" href="#"><?php _e( 'Add New Layer', 'accordion-slider' ); ?></a>
		<a class="button-secondary save" href="#"><?php _e( 'Save', 'accordion-slider' ); ?></a>
		<a class="button-secondary close" href="#"><?php _e( 'Close', 'accordion-slider' ); ?></a>
	</div>
</div>