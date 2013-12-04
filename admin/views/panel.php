<div class="panel">
	<div class="panel-image"> 
		<?php 
			if ( isset ( $panel_image ) && $panel_image !== '' ) {
				echo '<img src="' . $panel_image . '" />';
			}
		?>
	</div>

	<div class="panel-buttons"> 
		<a class="button-secondary edit-background-image" href="#">Image</a>
		<a class="button-secondary edit-html-content" href="#">HTML</a>
		<a class="button-secondary edit-layers" href="#">Layers</a>
	</div>

	<div class="panel-controls"> 

	</div>
</div>
