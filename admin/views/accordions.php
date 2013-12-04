<div class="wrap accordion-slider-admin">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
       
	<table class="widefat accordion-list">
	<thead>
	<tr>
		<th width="5%"><?php _e( 'ID', 'accordion-slider' ); ?></th>
		<th width="39%"><?php _e( 'Name', 'accordion-slider' ); ?></th>
		<th width="13%"><?php _e( 'Created', 'accordion-slider' ); ?></th>
		<th width="13%"><?php _e( 'Modified', 'accordion-slider' ); ?></th>
		<th width="35%"><?php _e( 'Actions', 'accordion-slider' ); ?></th>
	</tr>
	</thead>
	
	<tbody>
		
	<?php
		global $wpdb;
		$prefix = $wpdb->prefix;

		$accordions = $wpdb->get_results( "SELECT * FROM " . $prefix . "accordionslider_accordions ORDER BY id" );
		
		if ( count( $accordions ) === 0 ) {
			echo '<tr>' .
					 '<td colspan="100%">' . __( 'No accordion created yet.', 'accordion-slider' ) . '</td>' .
				 '</tr>';
		} else {
			foreach ( $accordions as $accordion ) {
				$accordion_id = $accordion->id;
				$accordion_name = stripslashes( $accordion->name );
				
				echo '<tr>'.
						'<td>' . $accordion_id . '</td>' .
						'<td>' . $accordion_name . '</td>' .
						'<td>' . $accordion->created . '</td>' .
						'<td>' . $accordion->modified . '</td>' .
						'<td>' .
							  '<a href="'. admin_url('admin.php?page=accordion-slider&id=' . $accordion_id) . '&action=edit">' . __('Edit', 'accordion-slider') . '</a> | ' .
							  '<a class="preview-accordion" href="">' . __('Preview', 'accordion-slider') . '</a> | ' .
							  '<a class="delete-accordion" href="'. admin_url('admin.php?page=accordion-slider&id=' . $accordion_id) . '&action=delete">' . __('Delete', 'accordion-slider') . '</a> | ' .
							  '<a class="duplicate-accordion" href="">' . __('Duplicate', 'accordion-slider') . '</a>';
							  
				echo	'</td>' .
					'</tr>';
			}
		}
	?>

	</tbody>
	
	<tfoot>
	<tr>
		<th><?php _e( 'ID', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Name', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Created', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Modified', 'accordion-slider' ); ?></th>
		<th><?php _e( 'Actions', 'accordion-slider' ); ?></th>
	</tr>
	</tfoot>
	</table>
    
    <div class="new-accordion-buttons">    
		<a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=accordion-slider-new' ); ?>"><?php _e( 'Create New Accordion', 'accordion-slider' ); ?></a>
        <a class="button-secondary" class="import-accordion" href=""><?php _e( 'Import Accordion', 'accordion-slider' ); ?></a>
    </div>    
    
</div>