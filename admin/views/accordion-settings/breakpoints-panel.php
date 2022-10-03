<div class="breakpoints">
    <?php
        if ( isset( $accordion_settings['breakpoints'] ) ) {
            $breakpoints = $accordion_settings['breakpoints'];

            foreach ( $breakpoints as $breakpoint_settings ) {
                include( ACCORDION_SLIDER_DIR_PATH . 'admin/views/accordion/breakpoint.php' );
            }
        }
    ?>
</div>
<a class="button add-breakpoint" href="#"><?php _e( 'Add Breakpoint', 'accordion-slider' ); ?></a>
<?php
    $hide_info = get_option( 'accordion_slider_hide_inline_info' );

    if ( $hide_info != true ) {
?>
    <div class="inline-info breakpoints-info">
        <input type="checkbox" id="show-hide-breakpoint-info" class="show-hide-info">
        <label for="show-hide-breakpoint-info" class="show-info"><?php _e( 'Show info', 'accordion-slider' ); ?></label>
        <label for="show-hide-breakpoint-info" class="hide-info"><?php _e( 'Hide info', 'accordion-slider' ); ?></label>
        
        <div class="info-content">
            <p><?php _e( 'Breakpoints allow you to modify the look of the accordion for different window sizes.', 'accordion-slider' ); ?></p>
            <p><?php _e( 'Each breakpoint allows you to set the width of the window for which the breakpoint will apply, and then add several settings which will override the global settings.', 'accordion-slider' ); ?></p>
            <p><a href="https://bqworks.net/accordion-slider/screencasts/#working-with-breakpoints" target="_blank"><?php _e( 'See the video tutorial', 'accordion-slider' ); ?> &rarr;</a></p>
        </div>
    </div>
<?php
    }
?>