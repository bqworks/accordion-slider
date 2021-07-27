<div class="wrap accordion-slider-admin plugin-settings">
	<h2><?php _e( 'Plugin Settings', 'accordion-slider' ); ?></h2>

	<form action="" method="post">
        <?php wp_nonce_field( 'plugin-settings-update', 'plugin-settings-nonce' ); ?>
        
        <table>
            <tr>
                <td>
                    <label for="load-stylesheets"><?php echo esc_html( $plugin_settings['load_stylesheets']['label'] ); ?></label>
                </td>
                <td>
                    <select id="load-stylesheets" name="load_stylesheets">
                        <?php
                            foreach ( $plugin_settings['load_stylesheets']['available_values'] as $value_name => $value_label ) {
                                $selected = $value_name === $load_stylesheets ? ' selected="selected"' : '';
                                echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
                            }
                        ?>
                    </select>
                 </td>
                <td>
                    <?php echo wp_kses_post( $plugin_settings['load_stylesheets']['description'] ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="load-unminified-scripts"><?php echo esc_html( $plugin_settings['load_unminified_scripts']['label'] ); ?></label>
                </td>
                <td>
                    <input type="checkbox" id="load-unminified-scripts" name="load_unminified_scripts" <?php echo $load_unminified_scripts == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <?php echo wp_kses_post( $plugin_settings['load_unminified_scripts']['description'] ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cache-expiry-interval"><?php echo esc_html( $plugin_settings['cache_expiry_interval']['label'] ); ?></label>
                </td>
                <td>
                    <input type="text" id="cache-expiry-interval" name="cache_expiry_interval" value="<?php echo esc_attr( $cache_expiry_interval ); ?>"><span>hours</span>
                </td>
                <td>
                    <?php echo wp_kses_post( $plugin_settings['cache_expiry_interval']['description'] ); ?>
                    <a class="button-secondary clear-all-cache" data-nonce="<?php echo wp_create_nonce( 'clear-all-cache' ); ?>"><?php _e( 'Clear all cache now', 'accordion-slider' ); ?></a>
                    <span class="spinner clear-cache-spinner"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="hide-inline-info"><?php echo esc_html( $plugin_settings['hide_inline_info']['label'] ); ?></label>
                </td>
                <td>
                    <input type="checkbox" id="hide-inline-info" name="hide_inline_info" <?php echo $hide_inline_info == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <?php echo wp_kses_post( $plugin_settings['hide_inline_info']['description'] ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="hide-getting-started-info"><?php echo wp_kses_post( $plugin_settings['hide_getting_started_info']['label'] ); ?></label>
                </td>
                <td>
                    <input type="checkbox" id="hide-getting-started-info" name="hide_getting_started_info" <?php echo $hide_getting_started_info == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <?php echo wp_kses_post( $plugin_settings['hide_getting_started_info']['description'] ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="hide-image-size-warning"><?php echo esc_html( $plugin_settings['hide_image_size_warning']['label'] ); ?></label>
                </td>
                <td>
                    <input type="checkbox" id="hide-image-size-warning" name="hide_image_size_warning" <?php echo $hide_image_size_warning == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <?php echo wp_kses_post( $plugin_settings['hide_image_size_warning']['description'] ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="access"><?php echo esc_html( $plugin_settings['access']['label'] ); ?></label>
                </td>
                <td>
                    <select id="access" name="access">
                        <?php
                            foreach ( $plugin_settings['access']['available_values'] as $value_name => $value_label ) {
                                $selected = $value_name === $access ? ' selected="selected"' : '';
                                echo '<option value="' . esc_attr( $value_name ) . '"' . $selected . '>' . esc_html( $value_label ) . '</option>';
                            }
                        ?>
                    </select>
                 </td>
                <td>
                    <?php echo wp_kses_post( $plugin_settings['access']['description'] ); ?>
                </td>
            </tr>
        </table>

    	<input type="submit" name="plugin_settings_update" class="button-primary" value="Update Settings" />
	</form>

</div>