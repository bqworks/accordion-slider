<div class="modal-overlay"></div>
<div class="modal-window-container layers-editor no-layers">
	<div class="modal-window">
		<span class="close-x"></span>

		<div class="layer-viewport"></div>

		<div class="layer-controls as-clearfix">
			<div class="layer-controls-left">
				<div class="layer-buttons">
					<div class="add-layer-group">
                        <a class="add-new-layer" href="#" title="<?php _e( 'Add Layer', 'accordion-slider' ); ?>"><?php _e( 'Add', 'accordion-slider' ); ?></a>
                        <ul class="layer-type">
                            <li><a href="#" data-type="video"><?php _e( 'Video Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="div"><?php _e( 'DIV Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="image"><?php _e( 'Image Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="heading"><?php _e( 'Heading Layer', 'accordion-slider' ); ?></a></li>
                            <li><a href="#" data-type="paragraph"><?php _e( 'Paragraph Layer', 'accordion-slider' ); ?></a></li>
                        </ul>
                    </div>
					
					<a class="delete-layer disabled" href="#" title="<?php _e( 'Delete Layer', 'accordion-slider' ); ?>"><?php _e( 'Delete', 'accordion-slider' ); ?></a>
					<a class="duplicate-layer disabled" href="#" title="<?php _e( 'Duplicate Layer', 'accordion-slider' ); ?>"><?php _e( 'Duplicate', 'accordion-slider' ); ?></a>
				</div>

				<ul class="list-layers">
					<?php
						foreach ( $layers as $layer ) {
							$layer_id = $layer[ 'id' ];
							$layer_name = $layer[ 'name' ];
							echo '<li class="list-layer" data-id="' . esc_attr( $layer_id ) . '">' . esc_html( $layer_name ) . '</li>';
						}
					?>
				</ul>
			</div>

			<ul class="layer-controls-right layers-settings">
				<?php
					foreach ( $layers as $layer ) {
						$layer_id = $layer[ 'id' ];
						$layer_type = $layer[ 'type' ];
						$layer_settings = $layer[ 'settings' ];

						include( 'layer-settings.php' );
					}
				?>
			</ul>
		</div>

		<?php
            $hide_info = get_option( 'accordion_slider_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
            <div class="inline-info layers-editor-info">
            	<input type="checkbox" id="show-hide-info" class="show-hide-info">
				<label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'accordion-slider' ); ?></label>
				<label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'accordion-slider' ); ?></label>
				
				<div class="info-content">
					<div class="no-layers-info">
						<p><?php _e( 'To add a new layer, hover on the plus sign above and select a layer type.', 'accordion-slider' ); ?></p>
						<p><a href="http://bqworks.net/accordion-slider/screencasts/#adding-layers" target="_blank"><?php _e( 'See the video tutorial', 'accordion-slider' ); ?> &rarr;</a></p>
					</div>

        			<div class="has-layers-info">
		                <p><?php _e( 'The <i>Display</i> setting from the <i>Appearance</i> tab allows you to specify if the layer will be permanently visible inside the panel, if it will appear only when the panel is opened, or if it will appear when the panel is closed.', 'accordion-slider' ); ?></p>
		                <p><?php _e( 'The <i>Position</i> setting allows you to set the reference point for the layer\'s horizontal and vertical position. The <i>Horizontal</i> and <i>Vertical</i> settings can be set to fixed or percentage values. Also, in order to center a layer, you can set the <i>Horizontal</i> and/or <i>Vertical</i> settings to <i>center</i>. The <i>Width</i> and <i>Height</i> settings can be set to fixed or percentage values, or to <i>auto</i>.', 'accordion-slider' ); ?></p>
						<p><?php _e( 'To style the layer, you can select one or more of the <i>Preset Styles</i>, or assign it a <i>Custom Class</i> and add some', 'accordion-slider' ); ?> <a target="_blank" href="<?php echo admin_url( 'admin.php?page=accordion-slider-custom' ); ?>"><?php _e( 'custom CSS', 'accordion-slider' ); ?></a> <?php _e( 'for it.', 'accordion-slider' ); ?></p>
						<p><?php _e( '<strong>Note:</strong> The style of <i>Paragraph</i>, <i>Heading</i> or <i>Image</i> layers might be affected by the theme\'s CSS code when you publish the accordion in a page. More details in the', 'accordion-slider' ); ?> <a target="_blank" href="<?php echo admin_url( 'admin.php?page=accordion-slider-documentation' ); ?>"><?php _e( 'Troubleshooting chapter', 'accordion-slider' ); ?></a>.</p>
						<p><a href="http://bqworks.net/accordion-slider/screencasts/#adding-layers" target="_blank"><?php _e( 'See the video tutorial', 'accordion-slider' ); ?> &rarr;</a></p>
						
						<?php
							if ( $content_type === 'posts' || $content_type === 'gallery' || $content_type === 'flickr' ) {
						?>
							<input type="checkbox" id="show-hide-dynamic-tags" class="show-hide-dynamic-tags">
							<label for="show-hide-dynamic-tags" class="show-dynamic-tags"><?php _e( 'Show dynamic tags', 'accordion-slider' ); ?></label>
							<label for="show-hide-dynamic-tags" class="hide-dynamic-tags"><?php _e( 'Hide dynamic tags', 'accordion-slider' ); ?></label>
						<?php
							}

							if ( $content_type === 'posts' ) {
						?>
								<table class="dynamic-tags">
									<tbody>
										<tr>
											<td><b>[as_image]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The post\'s featured image, as an <i>img</i> HTML element. It accepts an optional parameter to specify the size of the image: [as_image.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_src]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The URL of the post\'s featured image. It accepts an optional parameter to specify the size of the image: [as_image_src.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_alt]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The <i>alt</i> text of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_title]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The title of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_description]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The description of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_caption]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The caption of the post\'s featured image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_title]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The post\'s title.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_link]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The post\'s link, as an <i>anchor</i> HTML element, with the post\'s title as the text of the link.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_link_url]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The post\'s link.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_date]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The post\'s date.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_excerpt]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The post\'s excerpt.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_content]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The post\'s content.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_category]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The first category that the post is assigned to.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_custom.<i>name</i>]</b></td>
											<td> - </td>
											<td><p><?php _e( 'Returns the value from a custom field. The <i>name</i> parameter indicates the name of the custom field.', 'accordion-slider' ); ?></p></td>
										</tr>
									</tbody>
								</table>
		            	<?php
		            		} else if ( $content_type === 'gallery' ) {
		            	?>
		            			<table class="dynamic-tags">
									<tbody>
										<tr>
											<td><b>[as_image]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The gallery image, as an <i>img</i> HTML element. It accepts an optional parameter to specify the size of the image: [as_image.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_src]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The URL of the gallery image. It accepts an optional parameter to specify the size of the image: [as_image_src.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_alt]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The <i>alt</i> text of the gallery image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_title]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The title of the gallery image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_description]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The description of the gallery image.', 'accordion-slider' ); ?></p></td>
										</tr>
									</tbody>
								</table>
		            	<?php
		            		} else if ( $content_type === 'flickr' ) {
		            	?>
		            			<table class="dynamic-tags">
									<tbody>
										<tr>
											<td><b>[as_image]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The Flickr image, as an <i>img</i> HTML element. It accepts an optional parameter to specify the size of the image: [as_image.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_src]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The URL of the Flickr image. It accepts an optional parameter to specify the size of the image: [as_image_src.thumbnail]. Accepted sizes are: <i>square</i>, <i>thumbnail</i>, <i>small</i>, <i>medium</i>, <i>medium_640</i>, <i>large</i>. The default value is <i>medium</i>.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_description]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The description of the Flickr image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_image_link]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The link of the Flickr image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_date]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The date of the Flickr image.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_username]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The username of the image\'s owner.', 'accordion-slider' ); ?></p></td>
										</tr>
										<tr>
											<td><b>[as_user_link]</b></td>
											<td> - </td>
											<td><p><?php _e( 'The link to the profile of the image\'s owner.', 'accordion-slider' ); ?></p></td>
										</tr>
									</tbody>
								</table>
		            	<?php
		            		}
		            	?>
		            </div>
				</div>
            </div>
        <?php
            }
        ?>
		
	</div>
</div>