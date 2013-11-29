<div class="modal-overlay"></div>
<div class="background-image-editor">
	<span class="close-x"></span>
	<div class="fieldset background-image">
		<h3 class="heading">Background Image<span class="clear-fieldset">Clear</span></h3>
		<div class="image-loader">
			<?php
				if ( isset( $data['background_source'] ) && $data['background_source'] !== '' ) {
					echo '<img src="' . $data['background_source'] . '" />';
				} else {
					echo '<p class="no-image">Click to add image</p>';
				}
			?>
		</div>
		<table class="data-fields">
			<tbody>
				<tr>
					<td><label>Source:</label></td>
					<td><input class="field" type="text" name="background_source" value="<?php echo isset( $data['background_source'] ) ? $data['background_source'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label>Alt:</label></td>
					<td><input class="field" type="text" name="background_alt" value="<?php echo isset( $data['background_alt'] ) ? $data['background_alt'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label>Title:</label></td>
					<td><input class="field" type="text" name="background_title" value="<?php echo isset( $data['background_title'] ) ? $data['background_title'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label>Retina Source:</label></td>
					<td><input class="field" type="text" name="background_retina_source" value="<?php echo isset( $data['background_retina_source'] ) ? $data['background_retina_source'] : ''; ?>" /></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="fieldset opened-background-image">
		<h3 class="heading">Opened Background Image<span class="clear-fieldset">Clear</span></h3>
		<div class="image-loader">
			<?php
				if ( isset( $data['opened_background_source'] ) && $data['opened_background_source'] !== '' ) {
					echo '<img src="' . $data['opened_background_source'] . '" />';
				} else {
					echo '<p class="no-image">Click to add image</p>';
				}
			?>
		</div>
		<table class="data-fields">
			<tbody>
				<tr>
					<td><label>Source:</label></td>
					<td><input class="field" type="text" name="opened_background_source" value="<?php echo isset( $data['opened_background_source'] ) ? $data['opened_background_source'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label>Alt:</label></td>
					<td><input class="field" type="text" name="opened_background_alt" value="<?php echo isset( $data['opened_background_alt'] ) ? $data['opened_background_alt'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label>Title:</label></td>
					<td><input class="field" type="text" name="opened_background_title" value="<?php echo isset( $data['opened_background_title'] ) ? $data['opened_background_title'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label>Retina Source:</label></td>
					<td><input class="field" type="text" name="opened_background_retina_source" value="<?php echo isset( $data['opened_background_retina_source'] ) ? $data['opened_background_retina_source'] : ''; ?>" /></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="fieldset link">
		<h3 class="heading">Link<span class="clear-fieldset">Clear</span></h3>
		<table class="data-fields">
			<tbody>
				<tr>
					<td><label>URL:</label></td>
					<td><input class="field" type="text" name="background_link" value="<?php echo isset( $data['background_link'] ) ? $data['background_link'] : ''; ?>" /></td>
				</tr>
				<tr>
					<td><label>Title:</label></td>
					<td><input class="field" type="text" name="background_link_title" value="<?php echo isset( $data['background_link_title'] ) ? $data['background_link_title'] : ''; ?>" /></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="buttons">
		<a class="button-secondary add-opened-background-image" href="#">Add Opened Background Image</a>
		<a class="button-secondary add-link" href="#">Add Link</a>
		<a class="button-secondary save" href="#">Save</a>
		<a class="button-secondary close" href="#">Close</a>
	</div>
</div>