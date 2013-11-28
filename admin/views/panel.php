<div class="admin-panel">
	<div class="panel-image"> 
		<?php 
			if ( $panel_image !== '' ) {
				echo '<img src="' . $panel_image . '" />';
			} else {
				echo '<p>Add an image</p>';
			}
		?>
	</div>

	<div class="panel-buttons"> 
		<a class="button-secondary edit-background-image" href="#">Background Image</a>
		<a class="button-secondary edit-html-content" href="#">HTML Content</a>
		<a class="button-secondary edit-layers" href="#">Layers</a>
	</div>

	<div class="panel-controls"> 

	</div>
</div>