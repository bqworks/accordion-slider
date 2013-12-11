<div class="panel" draggable="true">
	<div class="panel-image"> 
		<?php 
			if ( isset ( $panel_image ) && $panel_image !== '' ) {
				echo '<img draggable="false" src="' . esc_url( $panel_image ) . '" />';
			} else {
				echo '<p class="no-image">' . __( 'Click to add image', 'accordion-slider' ) . '</p>';
			}
		?>
	</div>

	<div class="panel-controls">
		<a class="delete-panel" draggable="false" href="#"><?php _e( 'Delete', 'accordion-slider' ); ?></a>
		<a class="duplicate-panel" draggable="false" href="#"><?php _e( 'Duplicate', 'accordion-slider' ); ?></a>
		<a class="toggle-visibility" draggable="false" href="#"><?php _e( 'Visibility', 'accordion-slider' ); ?></a>
	</div>

	<div class="panel-buttons"> 
		<a class="button-secondary edit-background-image" draggable="false" href="#"><?php _e( 'Image', 'accordion-slider' ); ?></a>
		<a class="button-secondary edit-html-content" draggable="false" href="#"><?php _e( 'HTML', 'accordion-slider' ); ?></a>
		<a class="button-secondary edit-layers" draggable="false" href="#"><?php _e( 'Layers', 'accordion-slider' ); ?></a>
	</div>
</div>
