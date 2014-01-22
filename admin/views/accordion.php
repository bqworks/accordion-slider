<div class="wrap accordion-slider-admin">
	<h2><?php echo isset( $_GET['action'] ) && $_GET['action'] === 'edit' ? 'Edit Accordion' : 'Add New Accordion'; ?></h2>

	<form action="" method="post">
    	<div class="metabox-holder has-right-sidebar">
            <div class="editor-wrapper">
                <div class="editor-body">
                    <div id="titlediv">
                    	<input name="name" id="title" type="text" value="<?php echo esc_attr( $accordion_name ); ?>" />
                    </div>
					
					<div class="panels-container">
                    	<?php
                    		if ( isset( $panels ) ) {
                    			if ( $panels !== false ) {
                    				foreach ( $panels as $panel ) {
                    					$this->create_panel( $panel );
                    				}
                    			}
                    		} else {
                    			$this->create_panel( false );
                    		}
	                    ?>
                    </div>

                    <div class="add-panel-group">
                        <a class="button add-panel" href="#"><?php _e( 'Add Panel', 'accordion-slider' ); ?> <span class="add-panel-arrow">&#9660</span></a>
                        <ul class="panel-type">
                            <li><a href="#" data-type="images"><?php _e( 'Image(s) Panel', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="dynamic"><?php _e( 'Dynamic Panel', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="empty"><?php _e( 'Empty Panel', 'accordion-slider' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="inner-sidebar meta-box-sortables ui-sortable">
				<div class="postbox action">
					<div class="inside">
						<input type="submit" name="submit" class="button-primary" value="Update" />
						<a class="button preview-accordion" href="#"><?php _e( 'Preview', 'accordion-slider' ); ?></a>
					</div>
				</div>
                
                <div class="sidebar-settings">
                    <?php 
                        $setting_groups = Accordion_Slider_Settings::getSettingGroups();

                        foreach ( $setting_groups as $group ) {
                            ?>
                            <div class="postbox">
                                <div class="handlediv"></div>
                                <h3 class="hndle"><?php echo $group['label']; ?></h3>
                                <div class="inside">
                                    <table>
                                        <tbody>
                                            <?php
                                                foreach ( $group['list'] as $setting_name ) {
                                                    $setting = Accordion_Slider_Settings::getSettings( $setting_name );
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <label><?php echo $setting['label']; ?></label>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $value = isset( $accordion_settings ) && isset( $accordion_settings[ $setting_name ] ) ? $accordion_settings[ $setting_name ] : $setting['default_value'];

                                                                if ( $setting['type'] === 'number' || $setting['type'] === 'mixed' ) {
                                                                    echo '<input class="setting" type="text" name="' . $setting_name . '" value="' . esc_attr( $value ) . '" />';
                                                                } else if ( $setting['type'] === 'boolean' ) {
                                                                    echo '<input class="setting" type="checkbox" name="' . $setting_name . '"' . ( $value === true ? ' checked="checked"' : '' ) . ' />';
                                                                } else if ( $setting['type'] === 'select' ) {
                                                                    echo'<select class="setting" name="' . $setting_name . '">';
                                                                    
                                                                    foreach ( $setting['available_values'] as $value_name => $value_label ) {
                                                                        echo '<option value="' . $value_name . '"' . ( $value === $value_name ? ' selected="selected"' : '' ) . '>' . $value_label . '</option>';
                                                                    }
                                                                    
                                                                    echo '</select>';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="postbox breakpoints-box">
                        <div class="handlediv"></div>
                        <h3 class="hndle"><?php _e( 'Breakpoints', 'accordion-slider' ); ?></h3>
                        <div class="inside">
                            <div class="breakpoints">
                                <?php
                                    if ( isset( $accordion_settings['breakpoints'] ) ) {
                                        $breakpoints = $accordion_settings['breakpoints'];

                                        foreach ( $breakpoints as $breakpoint_settings ) {
                                            include( 'breakpoint.php' );
                                        }
                                    }
                                ?>
                            </div>
                            <a class="button add-breakpoint" href="#"><?php _e( 'Add Breakpoint', 'accordion-slider' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</form>
    
</div>