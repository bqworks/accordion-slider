<div class="wrap accordion-slider-admin">
	<h2><?php echo isset( $_GET['action'] ) && $_GET['action'] === 'edit' ? __( 'Edit Accordion', 'accordion-slider' ) : __( 'Add New Accordion', 'accordion-slider' ); ?></h2>

	<form action="" method="post">
    	<div class="metabox-holder has-right-sidebar">
            <div class="editor-wrapper">
                <div class="editor-body">
                    <div id="titlediv">
                    	<input name="name" id="title" type="text" value="<?php echo esc_attr( $accordion_name ); ?>" />
                    </div>

                    <?php
                        if ( get_option( 'accordion_slider_hide_image_size_warning' ) != true ) {
                    ?>
                            <div class="image-size-warning">
                                <p><?php _e( 'Some of the background images are smaller than the size of the panel, so they might appear blurred when viewed in the accordion.', 'accordion-slider' ); ?></p>
                                <p><?php _e( 'When you select images to insert them into the panels, you can set their size from the right column of the Media Library window, as you can see in <a href="http://bqworks.net/accordion-slider/screencasts/#simple-accordion" target="_blank">this video</a> at 0:05.', 'accordion-slider' ); ?></p>
                                <a href="#" class="image-size-warning-close"><?php _e( 'Don\'t show this again.', 'accordion-slider' ); ?></a>
                            </div>
                    <?php
                        }
                    ?>
					
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
                        <a class="button add-panel" href="#"><?php _e( 'Add Panels', 'accordion-slider' ); ?> <span class="add-panel-arrow">&#9660</span></a>
                        <ul class="panel-type">
                            <li><a href="#" data-type="image"><?php _e( 'Image Panels', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="posts"><?php _e( 'Posts Panels', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="gallery"><?php _e( 'Gallery Panels', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="flickr"><?php _e( 'Flickr Panels', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="empty"><?php _e( 'Empty Panel', 'accordion-slider' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="inner-sidebar meta-box-sortables ui-sortable">
				<div class="postbox action">
					<div class="inside">
						<input type="submit" name="submit" class="button-primary" value="<?php echo isset( $_GET['action'] ) && $_GET['action'] === 'edit' ? __( 'Update', 'accordion-slider' ) : __( 'Create', 'accordion-slider' ); ?>" />
                        <span class="spinner update-spinner"></span>
						<a class="button preview-accordion" href="#"><?php _e( 'Preview', 'accordion-slider' ); ?></a>
                        <span class="spinner preview-spinner"></span>
					</div>
				</div>
                
                <div class="sidebar-settings">
                    <?php 
                        $setting_groups = BQW_Accordion_Slider_Settings::getSettingGroups();
                        $panels_state = BQW_Accordion_Slider_Settings::getPanelsState();

                        foreach ( $setting_groups as $group_name => $group ) {
                            $panel_state_class = isset( $accordion_panels_state ) && isset( $accordion_panels_state[ $group_name ] ) ? $accordion_panels_state[ $group_name ] : $panels_state[ $group_name ];
                            $panel_name_class = $group_name . '-panel';
                            ?>
                            <div class="postbox <?php echo esc_attr( $panel_name_class ) . ' ' . esc_attr( $panel_state_class ); ?>" data-name="<?php echo esc_attr( $group_name ); ?>">
                                <div class="handlediv"></div>
                                <h3 class="hndle"><?php echo esc_html( $group['label'] ); ?></h3>
                                <div class="inside">
                                    <table>
                                        <tbody>
                                            <?php
                                                foreach ( $group['list'] as $setting_name ) {
                                                    $setting = BQW_Accordion_Slider_Settings::getSettings( $setting_name );
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <label data-info="<?php echo wp_kses_post( $setting['description'] ); ?>" for="<?php echo esc_attr( $setting_name ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $value = isset( $accordion_settings ) && isset( $accordion_settings[ $setting_name ] ) ? $accordion_settings[ $setting_name ] : $setting['default_value'];

                                                                if ( $setting['type'] === 'number' || $setting['type'] === 'text' || $setting['type'] === 'mixed' ) {
                                                                    echo '<input id="' . esc_attr( $setting_name ) . '" class="setting" type="text" name="' . esc_attr( $setting_name ) . '" value="' . esc_attr( $value ) . '" />';
                                                                } else if ( $setting['type'] === 'boolean' ) {
                                                                    echo '<input id="' . esc_attr( $setting_name ) . '" class="setting" type="checkbox" name="' . esc_attr( $setting_name ) . '"' . ( $value === true ? ' checked="checked"' : '' ) . ' />';
                                                                } else if ( $setting['type'] === 'select' ) {
                                                                    echo'<select id="' . esc_attr( $setting_name ) . '" class="setting" name="' . esc_attr( $setting_name ) . '">';
                                                                    
                                                                    foreach ( $setting['available_values'] as $value_name => $value_label ) {
                                                                        echo '<option value="' . esc_attr( $value_name ) . '"' . ( $value === $value_name ? ' selected="selected"' : '' ) . '>' . esc_html( $value_label ) . '</option>';
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
                                    
                                    <?php
                                        $hide_info = get_option( 'accordion_slider_hide_inline_info' );
                                
                                        if ( $hide_info != true && isset( $group['inline_info'] ) ) {
                                    ?>
                                            <div class="inline-info sidebar-panel-info">
                                                <input type="checkbox" id="show-hide-<?php echo esc_attr( $group_name ); ?>-info" class="show-hide-info">
                                                <label for="show-hide-<?php echo esc_attr( $group_name ); ?>-info" class="show-info"><?php _e( 'Show info', 'accordion-slider' ); ?></label>
                                                <label for="show-hide-<?php echo esc_attr( $group_name ); ?>-info" class="hide-info"><?php _e( 'Hide info', 'accordion-slider' ); ?></label>
                                                
                                                <div class="info-content">
                                                    <?php 
                                                        foreach( $group['inline_info'] as $inline_info_paragraph ) {
                                                            echo '<p>' . wp_kses_post( $inline_info_paragraph ) . '</p>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <?php $panel_class = isset( $accordion_panels_state ) && isset( $accordion_panels_state['breakpoints'] ) ? $accordion_panels_state['breakpoints'] : $panels_state[ $group_name ]; ?>
                    <div class="postbox breakpoints-box <?php echo esc_attr( $panel_class ); ?>" data-name="breakpoints">
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
                                        <p><a href="http://bqworks.net/accordion-slider/screencasts/#working-with-breakpoints" target="_blank"><?php _e( 'See the video tutorial', 'accordion-slider' ); ?> &rarr;</a></p>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</form>
</div>