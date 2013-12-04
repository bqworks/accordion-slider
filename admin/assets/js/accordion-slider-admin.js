(function($) {

	var AccordionSliderAdmin = {

		panels: [],

		init: function() {
			var that = this;

			if ( as_js_vars.page === 'single' ) {
				this.initPanels();

				if ( as_js_vars.id !== -1)  {
					this.loadAccordionData();
				}

				$( 'form' ).on( 'submit', function( event ) {
					event.preventDefault();
					that.updateAccordion();
				});

				$( '.add-panel, .panel-type a[data-type="empty"]' ).on( 'click', function( event ) {
					event.preventDefault();
					that.addEmptyPanel();
				});

				$( '.panel-type a[data-type="images"]' ).on( 'click', function( event ) {
					event.preventDefault();
					that.addImagesPanel();
				});

				$( '.panel-type a[data-type="dynamic"]' ).on( 'click', function( event ) {
					event.preventDefault();
					that.addDynamicPanel();
				});

				$( '.add-breakpoint' ).on( 'click', function( event ) {
					event.preventDefault();
					that.addBreakpoint();
				});

				$( '.breakpoints' ).on( 'click', '.add-setting', function( event ) {
					event.preventDefault();

					var name = $( this ).siblings( '.setting-selector' ).val(),
						context = $( this ).parents( '.breakpoint' ).find( '.breakpoint-settings' );

					that.addBreakpointSetting( name, context );
				});

				$( '.breakpoints' ).on( 'click', '.remove-breakpoint', function( event ) {
					$( this ).parents( '.breakpoint' ).remove();
				});

				$( '.breakpoints' ).on( 'click', '.remove-breakpoint-setting', function( event ) {
					$( this ).parents( 'tr' ).remove();
				});
			} else if ( as_js_vars.page === 'all' ) {
				
			}
		},

		loadAccordionData: function() {
			var that = this;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'accordion_slider_get_accordion_data', id: as_js_vars.id },
				complete: function( data ) {
					var accordionData = JSON.parse( data.responseText );

					$.each( accordionData.panels, function( index, element ) {
						that.getPanel( index ).setData( element );
					});
				}
			});
		},

		updateAccordion: function() {
			var that = this;

			var accordionData = {
				'id': as_js_vars.id,
				'name': $( 'input#title' ).val(),
				'settings': {},
				'panels': []
			};

			$( '.panels-container' ).find( '.panel' ).each(function( index ) {
				accordionData.panels[ index ] = that.getPanel( index ).getData();
			});

			$( '.sidebar-settings' ).find( '.setting' ).each(function() {
				var setting = $( this );
				accordionData.settings[ setting.attr( 'name' ) ] = setting.attr( 'type' ) === 'checkbox' ? setting.is( ':checked' ) : setting.val();
			});

			var breakpoints = {};

			$( '.breakpoints' ).find( '.breakpoint' ).each(function() {
				var breakpointGroup = $( this ),
					breakpoint = breakpoints[ breakpointGroup.find( 'input[name="breakpoint_width"]' ).val() ] = {};

				breakpointGroup.find( '.breakpoint-setting' ).each(function() {
					var breakpointSetting = $( this );

					breakpoint[ breakpointSetting.attr( 'name' ) ] = breakpointSetting.attr( 'type' ) === 'checkbox' ? breakpointSetting.is( ':checked' ) : breakpointSetting.val();
				});
			});

			if ( ! $.isEmptyObject( breakpoints ) ) {
				accordionData.settings.breakpoints = breakpoints;
			}

			var accordionDataString = JSON.stringify( accordionData );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_update_accordion', data: accordionDataString },
				complete: function( data ) {
					if ( parseInt( as_js_vars.id, 10 ) === -1 && isNaN( data.responseText ) === false ) {
						window.location = as_js_vars.admin + '?page=accordion-slider&id=' + data.responseText + '&action=edit';
					}
				}
			});
		},

		initPanels: function() {
			var that = this;

			$( '.panels-container' ).find( '.panel' ).each(function( index ) {
				that.initPanel( $( this ), index );
			});
		},

		initPanel: function( element, index ) {
			var panel = new AdminPanel( element, index );
			this.panels.push( panel );
		},

		getPanel: function( index ) {
			return this.panels[index];
		},

		addEmptyPanel: function() {
			var that = this;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_add_panels' },
				complete: function( data ) {
					var panel = $( data.responseText ).appendTo( $( '.panels-container' ) );

					that.initPanel( panel, panel.index() );
				}
			});
		},

		addImagesPanel: function() {
			var that = this,
				selectedImages = [];

			if ( typeof wp != 'undefined' && wp.media && wp.media.editor ) {
				var insertReference = wp.media.editor.insert;

				wp.media.editor.send.attachment = function( props, attachment ) {
					var url = attachment.sizes[ props.size ].url,
						alt = attachment.alt,
						title = attachment.title;

					selectedImages.push( { background_source: url, background_alt: alt, background_title: title } );
				};

				wp.media.editor.insert = function( prop ) {
					$.ajax({
						url: as_js_vars.ajaxurl,
						type: 'post',
						data: { action: 'accordion_slider_add_panels', data: JSON.stringify( selectedImages ) },
						complete: function( data ) {
							var lastIndex = $( '.panels-container' ).find( '.panel' ).length - 1,
								panels = $( '.panels-container' ).append( data.responseText );

							panels.find( '.panel:gt(' + lastIndex + ')' ).each(function( index ) {
								var panel = $( this ),
									panelIndex = lastIndex + 1 + index;

								that.initPanel( panel, panelIndex );
								that.getPanel( panelIndex ).setData( selectedImages[ index ] );
							});
						}
					});

					wp.media.editor.insert = insertReference;
				};

				wp.media.editor.open( 'media-loader' );
			}
		},

		addDynamicPanel: function() {
			var that = this;

			
		},

		addBreakpoint: function() {
			var that = this;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'accordion_slider_add_breakpoint' },
				complete: function( data ) {
					$( data.responseText ).appendTo( $( '.breakpoints' ) );
				}
			});
		},

		addBreakpointSetting: function( name, context) {
			var that = this;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'accordion_slider_add_breakpoint_setting', data: name },
				complete: function( data ) {
					$( data.responseText ).appendTo( context );
				}
			});
		}
	};

	var AdminPanel = function( element, index ) {
		this.$element = element;
		this.index = index;
		this.panelData = {};

		this.init();
	};

	AdminPanel.prototype = {

		init: function() {
			var that = this;

			this.$element.find( '.edit-background-image' ).on( 'click', function( event ) {
				event.preventDefault();

				BackgroundImageEditor.open( that.index );
			});
		},

		getData: function() {
			return this.panelData;
		},

		setData: function(data) {
			this.panelData = data;
		}
	};

	var BackgroundImageEditor = {

		editor: null,

		currentPanel: null,

		currentPanelIndex: 0,

		currentPanelData: null,

		open: function( index ) {
			var that = this;

			this.currentPanel = AccordionSliderAdmin.getPanel( index );
			this.currentPanelIndex = index;
			this.currentPanelData = this.currentPanel.getData();

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'accordion_slider_load_background_image_editor', data: JSON.stringify( this.currentPanelData ) },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();
				}
			});
		},

		init: function() {
			editor = $( '.background-image-editor' );

			editor.find( '.close, .close-x' ).on( 'click', $.proxy( this.close, this ) );
			editor.find( '.save' ).on( 'click', $.proxy( this.save, this ));
			editor.find( '.image-loader' ).on( 'click', $.proxy( this.openMediaLibrary, this ) );
			editor.find( '.clear-fieldset' ).on( 'click', $.proxy( this.clearFieldset, this ) );
		},

		save: function() {
			event.preventDefault();

			var that = this;

			editor.find( '.field' ).each(function() {
				var field = $( this );
				that.currentPanelData[ field.attr('name') ] = field.val();
			});

			this.currentPanel.setData( this.currentPanelData );

			var panelImage = $( '.panels-container' ).find( '.panel' ).eq( this.currentPanelIndex ).find( '.panel-image' );

			if ( this.currentPanelData[ 'background_source' ] !== '' ) {
				if ( panelImage.find( 'img' ).length ) {
					panelImage.find( 'img' ).attr( 'src', this.currentPanelData[ 'background_source' ] );
				} else {
					$( '<img src="' + this.currentPanelData[ 'background_source' ] + '" />' ).appendTo( panelImage );
				}
			} else if ( panelImage.find( 'img' ).length ) {
				panelImage.find( 'img' ).remove();
			}

			this.close();
		},

		close: function() {
			event.preventDefault();

			$( 'body' ).find( '.modal-overlay, .background-image-editor' ).remove();
		},

		openMediaLibrary: function( event ) {
			var target = $( event.target ).parents( '.fieldset' ).hasClass( 'opened-background-image' ) === true ? 'opened-background-image' : 'background-image';

			if ( typeof wp != 'undefined' && wp.media && wp.media.editor ) {
				wp.media.editor.send.attachment = function( props, attachment ) {
					var url = attachment.sizes[ props.size ].url,
						alt = attachment.alt,
						title = attachment.title,
						imageLoader;

					if ( target === 'background-image' ) {
						imageLoader = editor.find( '.background-image .image-loader' );

						if ( imageLoader.find( 'img' ).length !== 0 ) {
							imageLoader.find( 'img' ).attr( 'src', attachment.sizes.medium.url );
						} else {
							imageLoader.find( '.no-image' ).remove();
							$( '<img src="' + attachment.sizes.medium.url + '" />' ).appendTo( imageLoader );
						}

						editor.find( 'input[name="background_source"]' ).val( url );
						editor.find( 'input[name="background_alt"]' ).val( alt );
						editor.find( 'input[name="background_title"]' ).val( title );
					} else if ( target === 'opened-background-image' ) {
						imageLoader = editor.find( '.opened-background-image .image-loader' );

						if ( imageLoader.find( 'img' ).length !== 0 ) {
							imageLoader.find( 'img' ).attr( 'src', attachment.sizes.medium.url );
						} else {
							imageLoader.find( '.no-image' ).remove();
							$( '<img src="' + attachment.sizes.medium.url + '" />' ).appendTo( imageLoader );
						}

						editor.find( 'input[name="opened_background_source"]' ).val( url );
						editor.find( 'input[name="opened_background_alt"]' ).val( alt );
						editor.find( 'input[name="opened_background_title"]' ).val( title );
					}
				};

				wp.media.editor.open( 'media-loader' );
			}
		},

		clearFieldset: function( event ) {
			var target = $( event.target ).parents( '.fieldset' ),
				imageLoader = target.find( '.image-loader' );

			target.find( 'input' ).val( '' );

			if ( imageLoader.find( 'img' ).length !== 0 ) {
				imageLoader.find( 'img' ).remove();
				$( '<p class="no-image">Click to add image</p>' ).appendTo( imageLoader );
			}
		}
	};

	$( document ).ready(function() {
		AccordionSliderAdmin.init();
	});

})( jQuery );