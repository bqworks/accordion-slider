<div class="wrap accordion-slider-admin">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form action="" method="post">
    	<div class="metabox-holder has-right-sidebar">
            <div class="editor-wrapper meta-box-sortables">
                <div class="editor-body">
                    <div id="titlediv">
                    	<input name="name" id="title" type="text" value="My accordion"/>
                    </div>
					
					<div id="panels-container">
                    	<?php
	                    	$this->create_panel();
	                    	$this->create_panel();
	                    	$this->create_panel();
	                    	$this->create_panel();
	                    	$this->create_panel();
	                    ?>
                    </div>
                </div>
            </div>

            <div class="inner-sidebar meta-box-sortables ui-sortable">
				<div class="postbox action">
					<div class="inside">
						
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