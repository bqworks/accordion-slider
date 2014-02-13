<div class="modal-overlay"></div>
<div class="modal-window-container html-editor">
	<div class="modal-window">
		<span class="close-x"></span>
		<h3 class="heading"><?php _e( 'Edit HTML', 'accordion-slider' ); ?></h3>

		<textarea><?php echo isset( $html_content ) ? esc_textarea( stripslashes( $html_content ) ) : ''; ?></textarea>
	</div>
</div>