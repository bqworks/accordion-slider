<div class="wrap accordion-slider-admin">
	<h2><?php _e( 'Custom CSS and JavaScript', 'accordion-slider' ); ?></h2>

	<form action="" method="post">
        <?php wp_nonce_field( 'custom-css-js-update', 'custom-css-js-nonce' ); ?>

        <h3><?php _e( 'Custom CSS', 'accordion-slider' ); ?></h3>
        <textarea class="custom-css" name="custom_css" cols="80" rows="20"><?php echo isset( $custom_css ) ? stripslashes( esc_textarea( $custom_css ) ) : ''; ?></textarea>
        
        <input type="submit" name="custom_css_update" class="button-primary custom-css-js-update" value="Update CSS" />

        <h3><?php _e( 'Custom JavaScript', 'accordion-slider' ); ?></h3>
        <textarea class="custom-js" name="custom_js" cols="80" rows="20"><?php echo isset( $custom_js ) ? stripslashes( esc_textarea( $custom_js ) ) : ''; ?></textarea>

    	<input type="submit" name="custom_js_update" class="button-primary custom-css-js-update" value="Update JavaScript" />
	</form>
    
</div>