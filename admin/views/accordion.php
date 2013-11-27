<div class="wrap accordion-slider-admin">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form action="" method="post">
    	<div class="metabox-holder has-right-sidebar">
            <div class="editor-wrapper meta-box-sortables">
                <div class="editor-body">
                    <div id="titlediv">
                    	<input name="name" id="title" type="text" value="<?php echo $accordion_name; ?>" />
                    </div>
					
					<div id="panels-container">
                    	<?php
                    		if ( isset( $panels ) ) {
                    			if ( ! empty( $panels ) ) {
                    				foreach ( $panels as $panel ) {

                    					$this->create_panel( $panel );
                    				}
                    			}
                    		} else {
                    			$this->create_panel( false );
                    			$this->create_panel( false );
                    		}
	                    ?>
                    </div>
                </div>
            </div>

            <div class="inner-sidebar meta-box-sortables ui-sortable">
				<div class="postbox action">
					<div class="inside">
						<input type="submit" name="submit" class="button-primary" value="Update" />
						<a class="button preview-slider" href="">Preview</a>
					</div>
				</div>
				
				<div class="postbox">
					<div class="handlediv"></div>
					<h3 class="hndle">General</h3>
					<div class="inside">
					
					</div>
				</div>			   
            </div>
        </div>
	</form>
</div>