<div class="modal-overlay"></div>
<div class="modal-window-container html-editor">
	<div class="modal-window">
		<span class="close-x"></span>
		<h3 class="heading"><?php _e( 'Edit HTML', 'accordion-slider' ); ?></h3>

		<textarea><?php echo isset( $html_content ) ? esc_textarea( stripslashes( $html_content ) ) : ''; ?></textarea>

		<div class="buttons">
			<a class="button-secondary save" href="#"><?php _e( 'Save', 'accordion-slider' ); ?></a>
			<a class="button-secondary close" href="#"><?php _e( 'Close', 'accordion-slider' ); ?></a>
		</div>
	</div>
</div>