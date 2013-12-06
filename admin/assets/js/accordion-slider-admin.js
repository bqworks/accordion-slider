(function($) {

	var AccordionSliderAdmin = {

		panels: [],

		init: function() {
			if ( as_js_vars.page === 'single' ) {
				this.initSingleAccordionPage();
			} else if ( as_js_vars.page === 'all' ) {
				this.initAllAccordionsPage();
			}
		},

		initSingleAccordionPage: function() {
			var that = this;

			this.initPanels();

			if ( parseInt( as_js_vars.id, 10 ) !== -1) {
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
		},

		initAllAccordionsPage: function() {
			var that = this;

			$( '.delete-accordion' ).on( 'click', function( event ) {
				event.preventDefault();
				that.deleteAccordion( $( this ) );
			});
		},

		loadAccordionData: function() {
			var that = this;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				data: { action: 'accordion_slider_get_accordion_data', id: as_js_vars.id, nonce: as_js_vars.lad_nonce },
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
				'panels': [],
				'nonce': as_js_vars.ua_nonce
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

		deleteAccordion: function( target ) {
			var url = target.attr( 'href' ),
				urlArray = url.split( '&' ).splice( 1 ),
				id,
				action,
				nonce,
				row = target.parents( 'tr' );

			$.each( urlArray, function( index, element ) {
				var elementArray = element.split( '=' );

				if ( elementArray[ 0 ] === 'id' ) {
					id = parseInt( elementArray[ 1 ], 10 );
				} else if ( elementArray[ 0 ] === 'da_nonce' ) {
					nonce = elementArray[ 1 ];
				}

			});

			var dialog = $(
				'<div class="modal-overlay"></div>' +
				'<div class="delete-accordion-dialog">' +
				'	<p class="dialog-question">' + as_js_vars.accordion_delete + '</p>' +
				'	<div class="dialog-buttons">' +
				'		<a class="button dialog-ok" href="#">' + as_js_vars.yes + '</a>' +
				'		<a class="button dialog-cancel" href="#">' + as_js_vars.cancel + '</a>' +
				'	</div>' +
				'</div>'
			).appendTo( 'body' );

			dialog.find( '.dialog-ok' ).on( 'click', function( event ) {
				event.preventDefault();

				$.ajax({
					url: as_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'accordion_slider_delete_accordion', id: id, nonce: nonce },
					complete: function( data ) {
						if ( id === parseInt( data.responseText, 10 ) ) {
							row.fadeOut( 300, function() {
								row.remove();
							});
						}
					}
				});

				dialog.remove();
			} );

			dialog.find( '.dialog-cancel' ).on( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).on( 'click', function( event ) {
				dialog.remove();
			});
		},

		initPanels: function() {
			var that = this;

			$( '.panels-container' ).find( '.panel' ).each(function( index ) {
				that.initPanel( $( this ), index );
			});
		},

		initPanel: function( element, index ) {
			var panel = new Panel( element, index );
			this.panels.push( panel );
		},

		getPanel: function( index ) {
			return this.panels[ index ];
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
			var that = this;
			
			MediaLoader.open(function( selection ) {
				var images = [];

				$.each( selection, function( index, element ) {
					images.push( { background_source: element.url, background_alt: element.alt, background_title: element.title } );
				} );

				$.ajax({
					url: as_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'accordion_slider_add_panels', data: JSON.stringify( images ) },
					complete: function( data ) {
						var lastIndex = $( '.panels-container' ).find( '.panel' ).length - 1,
							panels = $( '.panels-container' ).append( data.responseText );

						panels.find( '.panel:gt(' + lastIndex + ')' ).each(function( index ) {
							var panel = $( this ),
								panelIndex = lastIndex + 1 + index;

							that.initPanel( panel, panelIndex );
							that.getPanel( panelIndex ).setData( images[ index ] );
						});
					}
				});
			});
		},

		addDynamicPanel: function() {
			var that = this;
		},

		removePanel: function( index ) {
			this.panels.splice( index, 1 );

			$.each( this.panels, function( index, element ) {
				element.setIndex( index );
			});
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

	var Panel = function( element, index ) {
		this.$element = element;
		this.index = index;
		this.panelData = {};

		this.init();
	};

	Panel.prototype = {

		init: function() {
			var that = this;

			this.$element.find( '.edit-background-image' ).on( 'click', function( event ) {
				event.preventDefault();

				BackgroundImageEditor.open( that.index );
			});

			this.$element.find( '.panel-image' ).on( 'click', function( event ) {
				MediaLoader.open(function( selection ) {
					var image = selection[ 0 ];

					that.setData( { background_source: image.url, background_alt: image.alt, background_title: image.title } );
					that.updateBackgroundImage();
				});
			});

			this.$element.find( '.delete-panel' ).on( 'click', function( event ) {
				event.preventDefault();

				var dialog = $(
					'<div class="modal-overlay"></div>' +
					'<div class="delete-panel-dialog">' +
					'	<p class="dialog-question">' + as_js_vars.panel_delete + '</p>' +
					'	<div class="dialog-buttons">' +
					'		<a class="button dialog-ok" href="#">' + as_js_vars.yes + '</a>' +
					'		<a class="button dialog-cancel" href="#">' + as_js_vars.cancel + '</a>' +
					'	</div>' +
					'</div>'
				).appendTo( 'body' );

				dialog.find( '.dialog-ok' ).on( 'click', function( event ) {
					event.preventDefault();

					AccordionSliderAdmin.removePanel( that.index );
					dialog.remove();

					that.$element.fadeOut( 500, function() {
						that.$element.remove();
					});
				});

				dialog.find( '.dialog-cancel' ).on( 'click', function( event ) {
					event.preventDefault();
					dialog.remove();
				});

				dialog.find( '.modal-overlay' ).on( 'click', function( event ) {
					dialog.remove();
				});
			});
		},

		getIndex: function() {
			return this.index;
		},

		setIndex: function( index ) {
			this.index = index;
		},

		getData: function() {
			return this.panelData;
		},

		setData: function( data ) {
			this.panelData = data;
		},

		updateBackgroundImage: function() {
			var panelImage = this.$element.find( '.panel-image' );

			if ( this.panelData[ 'background_source' ] !== '' ) {
				if ( panelImage.find( 'img' ).length ) {
					panelImage.find( 'img' ).attr( 'src', this.panelData[ 'background_source' ] );
				} else {
					panelImage.find( '.no-image' ).remove();
					$( '<img src="' + this.panelData[ 'background_source' ] + '" />' ).appendTo( panelImage );
				}
			} else if ( panelImage.find( 'img' ).length ) {
				panelImage.find( 'img' ).remove();
				$( '<p class="no-image">' + as_js_vars.no_image + '</p>' ).appendTo( panelImage );
			}
		}
	};

	var BackgroundImageEditor = {

		editor: null,

		currentPanel: null,

		currentPanelData: null,

		open: function( index ) {
			var that = this;

			this.currentPanel = AccordionSliderAdmin.getPanel( index );
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
			this.currentPanel.updateBackgroundImage();

			this.close();
		},

		close: function() {
			event.preventDefault();

			$( 'body' ).find( '.modal-overlay, .background-image-editor' ).remove();
		},

		openMediaLibrary: function( event ) {
			var target = $( event.target ).parents( '.fieldset' ).hasClass( 'opened-background-image' ) === true ? 'opened-background' : 'background',
				imageLoader = editor.find( '.' + target + '-image .image-loader' );

			MediaLoader.open(function( selection ) {
				var image = selection[ 0 ];

				if ( imageLoader.find( 'img' ).length !== 0 ) {
					imageLoader.find( 'img' ).attr( 'src', image.url );
				} else {
					imageLoader.find( '.no-image' ).remove();
					$( '<img src="' + image.url + '" />' ).appendTo( imageLoader );
				}

				if ( target === 'background' ) {
					editor.find( 'input[name="background_source"]' ).val( image.url );
					editor.find( 'input[name="background_alt"]' ).val( image.alt );
					editor.find( 'input[name="background_title"]' ).val( image.title );
				} else if ( target === 'opened-background' ) {
					editor.find( 'input[name="opened_background_source"]' ).val( image.url );
					editor.find( 'input[name="opened_background_alt"]' ).val( image.alt );
					editor.find( 'input[name="opened_background_title"]' ).val( image.title );
				}
			});
		},

		clearFieldset: function( event ) {
			var target = $( event.target ).parents( '.fieldset' ),
				imageLoader = target.find( '.image-loader' );

			target.find( 'input' ).val( '' );

			if ( imageLoader.find( 'img' ).length !== 0 ) {
				imageLoader.find( 'img' ).remove();
				$( '<p class="no-image">' + as_js_vars.no_image + '</p>' ).appendTo( imageLoader );
			}
		}
	};

	var MediaLoader = {

		open: function( callback ) {
			var selection = [],
				insertReference = wp.media.editor.insert;
			
			wp.media.editor.send.attachment = function( props, attachment ) {
				var url = attachment.sizes[ props.size ].url,
					alt = attachment.alt,
					title = attachment.title;

				selection.push( { url: url, alt: alt, title: title } );
			};

			wp.media.editor.insert = function( prop ) {
				callback.call(this, selection);

				wp.media.editor.insert = insertReference;
			};

			wp.media.editor.open( 'media-loader' );
		}

	};

	$( document ).ready(function() {
		AccordionSliderAdmin.init();
	});

})( jQuery );