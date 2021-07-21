<div class="modal-overlay"></div>
<div class="modal-window-container import-window">
	<div class="modal-window">
		<span class="close-x"></span>
		
		<textarea></textarea>

		<div class="buttons as-clearfix">
			<a class="button-secondary save" href="#"><?php _e( 'Import', 'accordion-slider' ); ?></a>
		</div>
		
		<?php
            $hide_info = get_option( 'accordion_slider_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
				<div class="inline-info import-info">
		            <input type="checkbox" id="show-hide-info" class="show-hide-info">
		            <label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'accordion-slider' ); ?></label>
		            <label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'accordion-slider' ); ?></label>
		            
		            <div class="info-content">
		                <p><?php _e( 'In the field above you need to copy the new accordion\'s data, as it was exported. Then, click the <i>Import</i> button.', 'accordion-slider' ); ?></p>
		            	<p><a href="http://bqworks.net/accordion-slider/screencasts/#import-export" target="_blank"><?php _e( 'See the video tutorial', 'accordion-slider' ); ?> &rarr;</a></p>
		            </div>
		        </div>
		<?php
            }
        ?>
	</div>
</div>