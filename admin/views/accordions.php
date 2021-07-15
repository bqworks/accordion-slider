<div class="wrap accordion-slider-admin">
	<h2><?php _e( 'All Accordions' ); ?></h2>
	
	<?php
		$hide_info = get_option( 'accordion_slider_hide_getting_started_info' );

		if ( $hide_info != true ) {
	?>
	    <div class="inline-info getting-started-info">
			<h3><?php _e( 'Getting started', 'accordion-slider' ); ?></h3>
			<p><?php _e( 'If you want to reproduce one of the examples showcased online, you can easily import those examples into your own Accordion Slider installation.', 'accordion-slider' ); ?></p>
			<p><?php _e( 'The examples can be found in the <i>examples</i> folder, which is included in the plugin\'s folder, and can be imported using the <i>Import Accordion</i> button below.', 'accordion-slider' ); ?></p>
			<p><?php _e( 'For quick usage instructions, please see the video tutorials below. For more detailed instructions, please see the', 'accordion-slider' ); ?> <a href="<?php echo admin_url('admin.php?page=accordion-slider-documentation'); ?>"><?php _e( 'Documentation', 'accordion-slider' ); ?></a> <?php _e( 'page.', 'accordion-slider' ); ?></p>
			<ul class="video-tutorials-list">
				<li><a href="http://bqworks.net/accordion-slider/screencasts/#simple-accordion" target="_blank"><?php _e( '1. Create and publish accordions', 'accordion-slider' ); ?></a></li>
				<li><a href="http://bqworks.net/accordion-slider/screencasts/#accordion-from-posts" target="_blank"><?php _e( '2. Create accordions from posts', 'accordion-slider' ); ?></a></li>
				<li><a href="http://bqworks.net/accordion-slider/screencasts/#accordion-from-gallery" target="_blank"><?php _e( '3. Create accordions from galleries', 'accordion-slider' ); ?></a></li>
				<li><a href="http://bqworks.net/accordion-slider/screencasts/#adding-layers" target="_blank"><?php _e( '4. Working with layers', 'accordion-slider' ); ?></a></li>
				<li><a href="http://bqworks.net/accordion-slider/screencasts/#working-with-breakpoints" target="_blank"><?php _e( '5. Working with breakpoints', 'accordion-slider' ); ?></a></li>
				<li><a href="http://bqworks.net/accordion-slider/screencasts/#import-export" target="_blank"><?php _e( '6. Import and Export accordions', 'accordion-slider' ); ?></a></li>
			</ul>
			<a href="#" class="getting-started-close">Close</a>
		</div>
	<?php
		}

		if ( ( get_option( 'accordion_slider_is_custom_css') == true || get_option( 'accordion_slider_is_custom_js') == true ) && get_option( 'accordion_slider_hide_custom_css_js_warning' ) != true ) {
	?>
		<div class="custom-css-js-warning">
			<h3><?php _e( 'Custom CSS & JS', 'accordion-slider' ); ?></h3>
			<p><?php _e( 'Your accordion sliders contain custom CSS and/or JavaScript. Please move this code in the Wordpress\' CSS editor, in Customize, or a different place. Your current code will still work for now, but you won\'t be able to edit it. You can see your custom CSS/JS below.', 'accordion-slider' )?></p>
			<?php
			if ( get_option( 'accordion_slider_is_custom_css') == true ) {
			?>
			<div class="custom-css-js-warning-code">
				<h4> <?php _e( 'Custom CSS', 'accordion-slider' ); ?></h4>
				<textarea><?php echo stripslashes( get_option( 'accordion_slider_custom_css' ) ); ?></textarea>
			</div>
			<?php
			}

			if ( get_option( 'accordion_slider_is_custom_js') == true ) {
			?>
			<div class="custom-css-js-warning-code">
				<h4><?php _e( 'Custom JS', 'accordion-slider' ); ?></h4>
				<textarea><?php echo stripslashes( get_option( 'accordion_slider_custom_js' ) ); ?></textarea>
			</div>
			<?php
			}
			?>
			<a href="#" class="custom-css-js-warning-close"><?php _e( 'Don\'t show this again.', 'accordion-slider' ); ?></a>
		</div>
	<?php
		}
	?>

	<table class="widefat accordions-list">
	<thead>
	<tr>
		<th><?php _e( 'ID', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Name', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Shortcode', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Created', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Modified', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Actions', 'accordion-slider' ); ?></th>
	</tr>
	</thead>
	
	<tbody>
		
	<?php
		global $wpdb;
		$prefix = $wpdb->prefix;

		$accordions = $wpdb->get_results( "SELECT * FROM " . $prefix . "accordionslider_accordions ORDER BY id" );
		
		if ( count( $accordions ) === 0 ) {
			echo '<tr class="no-accordion-row">' .
					 '<td colspan="100%">' . __( 'You don\'t have saved accordions.', 'accordion-slider' ) . '</td>' .
				 '</tr>';
		} else {
			foreach ( $accordions as $accordion ) {
				$accordion_id = $accordion->id;
				$accordion_name = stripslashes( $accordion->name );
				$accordion_created = $accordion->created;
				$accordion_modified = $accordion->modified;

				include( 'accordions-row.php' );
			}
		}
	?>

	</tbody>
	
	<tfoot>
	<tr>
		<th><?php _e( 'ID', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Name', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Shortcode', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Created', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Modified', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Actions', 'accordion-slider' ); ?></th>
	</tr>
	</tfoot>
	</table>
    
    <div class="new-accordion-buttons">    
		<a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=accordion-slider-new' ); ?>"><?php _e( 'Create New Accordion', 'accordion-slider' ); ?></a>
        <a class="button-secondary import-accordion" href=""><?php _e( 'Import Accordion', 'accordion-slider' ); ?></a>
    </div>    
    
</div>