<tr>
	<td class="label-cell">
		<label for="posts-post-type"><?php _e( 'Post Type', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="posts-post-type" multiple class="panel-setting" name="posts_post_type">
			<?php
				foreach ( $post_names as $value ) {
					$selected = ( isset( $panel_settings['posts_post_type'] ) && in_array( $value['name'], $panel_settings['posts_post_type'] ) ) || ( ! isset( $panel_settings['posts_post_type'] ) && in_array( $value['name'], $panel_default_settings['posts_post_type']['default_value'] ) ) ? ' selected="selected"' : '';
					echo '<option value="' . $value['name'] . '"' . $selected . '>' . $value['label'] . '</option>';
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="posts-taxonomy"><?php _e( 'Taxonomy', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="posts-taxonomy" multiple class="panel-setting" name="posts_taxonomy">
			<?php
				if ( isset( $panel_settings['posts_post_type'] ) && ! empty( $panel_settings['posts_post_type'] ) ) {
					$post_types = $panel_settings['posts_post_type'];
					$all_taxonomies = $this->get_taxonomies_for_posts( $panel_settings['posts_post_type'] );

					foreach ( $post_types as $post_type ) {
						$taxonomies = $all_taxonomies[ $post_type ];

						foreach ( $taxonomies as $taxonomy ) {
							echo '<optgroup label="' . $taxonomy['label'] . '">';

							foreach ( $taxonomy['terms'] as $term ) {
								$selected = isset( $panel_settings['posts_taxonomy'] ) && in_array( $term[ 'full' ], $panel_settings['posts_taxonomy'] ) ? ' selected="selected"' : '';
								echo '<option value="' . $term[ 'full' ] . '"' . $selected . '>' . $term[ 'name' ] . '</option>';
							}

							echo '</optgroup>';
						}
					}
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="posts-relation"><?php _e( 'Match', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="posts-relation" class="panel-setting" name="posts_relation">
			<?php
				foreach ( $panel_default_settings['posts_relation']['available_values'] as $value_name => $value_label ) {
					$selected = ( isset( $panel_settings['posts_relation'] ) && $value_name === $panel_settings['posts_relation'] ) || ( ! isset( $panel_settings['posts_relation'] ) && $value_name === $panel_default_settings['posts_relation']['default_value'] ) ? ' selected="selected"' : '';
					echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
	            }
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="posts-operator"><?php _e( 'With selected', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="posts-operator" class="panel-setting" name="posts_operator">
			<?php
				foreach ( $panel_default_settings['posts_operator']['available_values'] as $value_name => $value_label ) {
					$selected = ( isset( $panel_settings['posts_operator'] ) && $value_name === $panel_settings['posts_operator'] ) || ( ! isset( $panel_settings['posts_operator'] ) && $value_name === $panel_default_settings['posts_operator']['default_value'] ) ? ' selected="selected"' : '';
					echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
	            }
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="posts-order-by"><?php _e( 'Order By', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="posts-order-by" class="panel-setting" name="posts_order_by">
			<?php
				foreach ( $panel_default_settings['posts_order_by']['available_values'] as $value_name => $value_label ) {
					$selected = ( isset( $panel_settings['posts_order_by'] ) && $value_name === $panel_settings['posts_order_by'] ) || ( ! isset( $panel_settings['posts_order_by'] ) && $value_name === $panel_default_settings['posts_order_by']['default_value'] ) ? ' selected="selected"' : '';
					echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
	            }
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="posts-order"><?php _e( 'Order', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select id="posts-order" class="panel-setting" name="posts_order">
			<?php
				foreach ( $panel_default_settings['posts_order']['available_values'] as $value_name => $value_label ) {
					$selected = ( isset( $panel_settings['posts_order'] ) && $value_name === $panel_settings['posts_order'] ) || ( ! isset( $panel_settings['posts_order'] ) && $value_name === $panel_default_settings['posts_order']['default_value'] ) ? ' selected="selected"' : '';
					echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
	            }
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label for="posts-maximum"><?php _e( 'Limit', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input id="posts-maximum" class="panel-setting" type="text" name="posts_maximum" value="<?php echo isset( $panel_settings['posts_maximum'] ) ? esc_attr( $panel_settings['posts_maximum'] ) : $panel_default_settings['posts_maximum']['default_value']; ?>" />
	</td>
</tr>