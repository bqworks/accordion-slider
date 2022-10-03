<table>
    <tbody>
        <?php
            foreach ( $panel['list'] as $setting_name ) {
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