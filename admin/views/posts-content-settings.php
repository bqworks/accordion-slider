<tr>
	<td class="label-cell">
		<label><?php _e( 'Post Type', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select multiple class="setting" name="post_type">
			<?php
				foreach ( $data as $key => $value ) {
					echo '<option value="' . $key . '">' . $value['label'] . '</option>';
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Taxonomy', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select multiple class="setting" name="taxonomy">
	
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Match', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select class="setting" name="match">
			<option name="or">At least one</option>
			<option name="and">All</option>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Width selected', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select class="setting" name="with_selected">
			<option name="in">Include</option>
			<option name="not_in">Exclude</option>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Order By', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select class="setting" name="order_by">
			<option name="date">Date</option>
			<option name="comments">Comments</option>
			<option name="title">Title</option>
			<option name="random">Random</option>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Order', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<select class="setting" name="order">
			<option name="desc">Descending</option>
			<option name="asc">Ascending</option>
		</select>
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Limit', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input class="setting" type="text" name="maximum" value="" />
	</td>
</tr>
<tr>
	<td class="label-cell">
		<label><?php _e( 'Start At', 'accordion-slider' ); ?>:</label>
	</td>
	<td class="setting-cell">
		<input class="setting" type="text" name="offset" value="" />
	</td>
</tr>