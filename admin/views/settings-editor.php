<div class="modal-overlay"></div>
<div class="settings-editor">
	<span class="close-x"></span>
	<h3 class="heading"><?php _e( 'Panel Settings', 'accordion-slider' ); ?></h3>
	
	<table>
		<thead>
			<tr>
				<th class="label-cell">
					<label>Content Type:</label>
				</th>
				<th class="setting-cell">
					<select class="setting" name="content_type">
						<option value="static">Static Content</option>
						<option value="posts">Content from posts</option>
						<option value="gallery">Images from post's gallery</option>
						<option value="flickr">Flickr images</option>
					</select>
				</th>
			</tr>
		</thead>
		<tbody class="content-type-settings">

		</tbody>
	</table>

	<div class="buttons">
		<a class="button-secondary save" href="#"><?php _e( 'Save', 'accordion-slider' ); ?></a>
		<a class="button-secondary close" href="#"><?php _e( 'Close', 'accordion-slider' ); ?></a>
	</div>
</div>