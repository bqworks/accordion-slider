<div class="panel">
	<div class="panel-image"> 
		<?php 
			if ( isset ( $panel_image ) && $panel_image !== '' ) {
				echo '<img src="' . $panel_image . '" />';
			} else {
				echo '<p class="no-image">Click to add image</p>';
			}
		?>
	</div>

	<div class="panel-controls">
		<a class="delete-panel" href="#">Delete</a>
		<a class="duplicate-panel" href="#">Duplicate</a>
		<a class="toggle-visibility" href="#">Visibility</a>
	</div>

	<div class="panel-buttons"> 
		<a class="button-secondary edit-background-image" href="#">Image</a>
		<a class="button-secondary edit-html-content" href="#">HTML</a>
		<a class="button-secondary edit-layers" href="#">Layers</a>
	</div>
</div>
