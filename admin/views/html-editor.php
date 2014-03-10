<div class="modal-overlay"></div>
<div class="modal-window-container html-editor">
	<div class="modal-window">
		<span class="close-x"></span>

		<textarea><?php echo isset( $html_content ) ? esc_textarea( stripslashes( $html_content ) ) : ''; ?></textarea>
	</div>
</div>