<div class="wrap accordion-slider-admin">
	<h2><?php _e( 'All Accordions' ); ?></h2>
	
	<?php
		$hide_info = get_option( 'accordion_slider_hide_getting_started_info' );

		if ( $hide_info != true ) {
	?>
	    <div class="inline-info getting-started-info">
			<h3><?php _e( '1. Getting started', 'accordion-slider' ); ?></h3>
			<p><?php _e( 'If you want to reproduce one of the examples showcased online, you can easily import those examples into your own Accordion Slider installation.', 'accordion-slider' ); ?></p>
			<p><?php _e( 'The examples can be found in the <i>examples</i> folder, which is included in the plugin\'s folder, and can be imported using the <i>Import Accordion</i> button below.', 'accordion-slider' ); ?></p>
			<p><?php _e( 'For quick usage instructions, please see the video tutorials below. For more detailed instructions, please see the', 'accordion-slider' ); ?> <a href="<?php echo admin_url('admin.php?page=accordion-slider-documentation'); ?>"><?php _e( 'Documentation', 'accordion-slider' ); ?></a> <?php _e( 'page.', 'accordion-slider' ); ?></p>
			<ul class="video-tutorials-list">
				<li><a href="https://www.youtube.com/watch?v=qmjgtmeLrzs&list=PLh-6IaZNuPo58lYbFPNV_nOzKYoOwHBKG" target="_blank"><?php _e( '1. Create and publish accordions', 'accordion-slider' ); ?></a></li>
				<li><a href="https://www.youtube.com/watch?v=sGToyxSDZLk&list=PLh-6IaZNuPo58lYbFPNV_nOzKYoOwHBKG" target="_blank"><?php _e( '2. Create accordions from posts', 'accordion-slider' ); ?></a></li>
				<li><a href="https://www.youtube.com/watch?v=oF3tRiuJq_k&list=PLh-6IaZNuPo58lYbFPNV_nOzKYoOwHBKG" target="_blank"><?php _e( '3. Create accordions from galleries', 'accordion-slider' ); ?></a></li>
				<li><a href="https://www.youtube.com/watch?v=-6X1FAbJyYw&list=PLh-6IaZNuPo58lYbFPNV_nOzKYoOwHBKG" target="_blank"><?php _e( '4. Working with layers', 'accordion-slider' ); ?></a></li>
				<li><a href="https://www.youtube.com/watch?v=kqU0FxrN6L4&list=PLh-6IaZNuPo58lYbFPNV_nOzKYoOwHBKG" target="_blank"><?php _e( '5. Adding custom CSS', 'accordion-slider' ); ?></a></li>
				<li><a href="https://www.youtube.com/watch?v=AatLP0QGYv8&list=PLh-6IaZNuPo58lYbFPNV_nOzKYoOwHBKG" target="_blank"><?php _e( '6. Working with breakpoints', 'accordion-slider' ); ?></a></li>
				<li><a href="https://www.youtube.com/watch?v=UVk_2r3f1xc&list=PLh-6IaZNuPo58lYbFPNV_nOzKYoOwHBKG" target="_blank"><?php _e( '7. Import and Export accordions', 'accordion-slider' ); ?></a></li>
			</ul>

			<h3><?php _e( '2. Support', 'accordion-slider' ); ?></h3>
			<p><?php _e( 'When you need support, please contact us at our support center:', 'accordion-slider' ); ?> <a href="http://support.bqworks.com">support.bqworks.com</a>.</p>
			
			<?php
				$purchase_code_status = get_option( 'accordion_slider_purchase_code_status', '0' );

				if ( $purchase_code_status !== '1' ) {
			?>
					<h3><?php _e( '3. Updates', 'accordion-slider' ); ?></h3>
					<p><?php _e( 'In order to have access to automatic updates, please enter your purchase code', 'accordion-slider' ); ?> <a href="<?php echo admin_url('admin.php?page=accordion-slider-settings'); ?>"><?php _e( 'here', 'accordion-slider' ); ?></a>.</p>
			<?php
				}
			?>

			<a href="#" class="getting-started-close">Close</a>
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