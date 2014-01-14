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

			$( '.accordions-list' ).on( 'click', '.delete-accordion', function( event ) {
				event.preventDefault();
				that.deleteAccordion( $( this ) );
			});

			$( '.accordions-list' ).on( 'click', '.duplicate-accordion', function( event ) {
				event.preventDefault();
				that.duplicateAccordion( $( this ) );
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
						that.getPanel( index ).setData( 'all', element );
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

			$( '.panels-container' ).find( '.panel' ).each(function( index, element ) {
				accordionData.panels[ index ] = that.getPanel( parseInt( $( element ).attr('data-index'), 10) ).getData( 'all' );
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

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
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
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
		},

		duplicateAccordion: function( target ) {
			var url = target.attr( 'href' ),
				urlArray = url.split( '&' ).splice( 1 ),
				id,
				nonce;

			$.each( urlArray, function( index, element ) {
				var elementArray = element.split( '=' );

				if ( elementArray[ 0 ] === 'id' ) {
					id = parseInt( elementArray[ 1 ], 10 );
				} else if ( elementArray[ 0 ] === 'dua_nonce' ) {
					nonce = elementArray[ 1 ];
				}
			});

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_duplicate_accordion', id: id, nonce: nonce },
				complete: function( data ) {
					var row = $( data.responseText ).appendTo( $( '.accordions-list tbody' ) );
					
					row.hide().fadeIn();
				}
			});
		},

		initPanels: function() {
			var that = this;

			$( '.panels-container' ).find( '.panel' ).each(function( index ) {
				that.initPanel( $( this ), index );
			});

			var target,
				placeholde;

			$( '.panel' ).on( 'dragstart', function( event ) {
				target = $( this );
				
				placeholder = $( '<div class="panel placeholder" style="height: ' + $( this ).height() + 'px"></div>' );

				placeholder.on( 'dragover', function( event ) {
					event.preventDefault();
				});

				event.originalEvent.dataTransfer.setData( 'text/html', target.html() );
			}).on( 'dragend', function( event ) {
				target.insertAfter( placeholder );
				placeholder.remove();
			}).on( 'dragenter', function( event ) {
				if ( $( this ).index() >= placeholder.index() )
					placeholder.insertAfter( $( this ) );
				else
					placeholder.insertBefore( $( this ) );
			}).on( 'dragover', function( event ) {
				event.preventDefault();
				target.detach();
			});
		},

		initPanel: function( element, index ) {
			var that = this;

			var panel = new Panel( element, index );
			this.panels.push( panel );

			panel.on( 'duplicatePanel', function( event ) {
				that.duplicatePanel( event.panelData );
			});

			panel.on( 'deletePanel', function( event ) {
				that.deletePanel( event.index );
			});

			element.attr( 'data-index', index);
		},

		getPanel: function( index ) {
			var that = this,
				panel;

			$.each( that.panels, function( elementIndex, element ) {
				if ( element.index === index ) {
					panel = element;
					return false;
				}
			});

			return panel;
		},

		removePanel: function( index ) {
			var panel = this.getPanel( index );
			panel.off( 'duplicatePanel' );
			panel.off( 'deletePanel' );

			this.panels.splice( index, 1 );
		},

		duplicatePanel: function( panelData ) {
			var that = this;

			var images = [ {
				background_source: panelData.background.background_source,
				background_alt: panelData.background.background_alt,
				background_title: panelData.background.background_title
			} ];

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				data: { action: 'accordion_slider_add_panels', data: JSON.stringify( images ) },
				complete: function( data ) {
					var panel = $( data.responseText ).appendTo( $( '.panels-container' ) ),
						index = panel.index();

					that.initPanel( panel, index );
					that.getPanel( index ).setData( 'all', panelData );
				}
			});
		},

		deletePanel: function( index ) {
			var that = this;

			var panel = that.getPanel( index ),
				dialog = $(
				'<div class="modal-overlay"></div>' +
				'<div class="delete-panel-dialog">' +
				'	<p class="dialog-question">' + as_js_vars.panel_delete + '</p>' +
				'	<div class="dialog-buttons">' +
				'		<a class="button dialog-ok" href="#">' + as_js_vars.yes + '</a>' +
				'		<a class="button dialog-cancel" href="#">' + as_js_vars.cancel + '</a>' +
				'	</div>' +
				'</div>'
			).appendTo( 'body' );

			dialog.find( '.dialog-ok' ).one( 'click', function( event ) {
				event.preventDefault();

				that.removePanel( index );
				dialog.remove();
				panel.remove();
			});

			dialog.find( '.dialog-cancel' ).one( 'click', function( event ) {
				event.preventDefault();
				dialog.remove();
			});

			dialog.find( '.modal-overlay' ).one( 'click', function( event ) {
				dialog.remove();
			});
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
				});

				$.ajax({
					url: as_js_vars.ajaxurl,
					type: 'post',
					data: { action: 'accordion_slider_add_panels', data: JSON.stringify( images ) },
					complete: function( data ) {
						var lastIndex = $( '.panels-container' ).find( '.panel' ).length - 1,
							panels = $( '.panels-container' ).append( data.responseText ),
							indexes = lastIndex === -1 ? '' : ':gt(' + lastIndex + ')';

						panels.find( '.panel' + indexes ).each(function( index ) {
							var panel = $( this ),
								panelIndex = lastIndex + 1 + index;

							that.initPanel( panel, panelIndex );
							that.getPanel( panelIndex ).setData( 'background', images[ index ] );
						});
					}
				});
			});
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

	var Panel = function( element, index ) {
		this.$element = element;
		this.index = index;
		this.panelData = { background: {}, layers: {}, html: {} };
		this.events = $( {} );

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

					that.setData( 'background', { background_source: image.url, background_alt: image.alt, background_title: image.title } );
					that.updateBackgroundImage();
				});
			});

			this.$element.find( '.edit-layers' ).on( 'click', function( event ) {
				event.preventDefault();

				LayersEditor.open( that.index );
			});

			this.$element.find( '.delete-panel' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'deletePanel', index: that.index } );
			});

			this.$element.find( '.duplicate-panel' ).on( 'click', function( event ) {
				event.preventDefault();
				that.trigger( { type: 'duplicatePanel', panelData: that.panelData } );
			});
		},

		getIndex: function() {
			return this.index;
		},

		setIndex: function( index ) {
			this.index = index;
		},

		getData: function( target ) {
			if ( target === 'all' ) {
				return this.panelData;
			} else if ( target === 'background' ) {
				return this.panelData.background;
			} else if ( target === 'layers' ) {
				return this.panelData.layers;
			} else if ( target === 'html' ) {
				return this.panelData.html;
			}
		},

		setData: function( target, data ) {
			if ( target === 'all' ) {
				this.panelData = data;
			} else if ( target === 'background' ) {
				this.panelData.background = data;
			} else if ( target === 'layers' ) {
				this.panelData.layers = data;
			} else if ( target === 'html' ) {
				this.panelData.html = data;
			}
		},

		remove: function() {
			this.$element.find( '.edit-background-image' ).off( 'click' );
			this.$element.find( '.panel-image' ).off( 'click' );
			this.$element.find( '.delete-panel' ).off( 'click' );
			this.$element.find( '.duplicate-panel' ).off( 'click' );

			this.$element.fadeOut( 500, function() {
				$( this ).remove();
			});
		},

		updateBackgroundImage: function() {
			var panelImage = this.$element.find( '.panel-image' );

			if ( this.panelData.background[ 'background_source' ] !== '' ) {
				if ( panelImage.find( 'img' ).length ) {
					panelImage.find( 'img' ).attr( 'src', this.panelData.background[ 'background_source' ] );
				} else {
					panelImage.find( '.no-image' ).remove();
					$( '<img src="' + this.panelData.background[ 'background_source' ] + '" />' ).appendTo( panelImage );
				}
			} else if ( panelImage.find( 'img' ).length ) {
				panelImage.find( 'img' ).remove();
				$( '<p class="no-image">' + as_js_vars.no_image + '</p>' ).appendTo( panelImage );
			}
		},

		on: function( type, handler ) {
			this.events.on( type, handler );
		},

		off: function( type ) {
			this.events.off( type );
		},

		trigger: function( type ) {
			this.events.triggerHandler( type );
		}
	};

	var BackgroundImageEditor = {

		editor: null,

		currentPanel: null,

		backgroundData: null,

		open: function( index ) {
			var that = this;

			this.currentPanel = AccordionSliderAdmin.getPanel( index );
			this.backgroundData = this.currentPanel.getData( 'background' );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'accordion_slider_load_background_image_editor', data: JSON.stringify( this.backgroundData ) },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();
				}
			});
		},

		init: function() {
			editor = $( '.background-image-editor' );

			editor.find( '.close, .close-x' ).on( 'click', $.proxy( this.close, this ) );
			editor.find( '.save' ).on( 'click', $.proxy( this.save, this ) );
			editor.find( '.image-loader' ).on( 'click', $.proxy( this.openMediaLibrary, this ) );
			editor.find( '.clear-fieldset' ).on( 'click', $.proxy( this.clearFieldset, this ) );
		},

		save: function() {
			event.preventDefault();

			var that = this;

			editor.find( '.field' ).each(function() {
				var field = $( this );
				that.backgroundData[ field.attr('name') ] = field.val();
			});

			this.currentPanel.setData( 'background', this.backgroundData );
			this.currentPanel.updateBackgroundImage();

			this.close();
		},

		close: function() {
			event.preventDefault();

			editor.find( '.close, .close-x' ).off( 'click' );
			editor.find( '.save' ).off( 'click' );
			editor.find( '.image-loader' ).off( 'click' );
			editor.find( '.clear-fieldset' ).off( 'click' );

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

	var LayersEditor = {

		editor: null,

		currentPanel:null,

		layersData: null,

		counter: 0,

		open: function( index ) {
			var that = this;

			this.currentPanel = AccordionSliderAdmin.getPanel( index );
			this.layersData = AccordionSliderAdmin.getPanel( index ).getData( 'layers' );

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'accordion_slider_load_layers_editor', data: JSON.stringify( this.layersData ) },
				complete: function( data ) {
					$( 'body' ).append( data.responseText );
					that.init();
				}
			});
		},

		init: function() {
			var that = this,
				isEditingLayerName = false;

			this.counter = 0;

			editor = $( '.layers-editor' );

			editor.find( '.add-new-layer' ).on( 'click', $.proxy( this.addNewLayer, this ) );
			editor.find( '.delete-layer' ).on( 'click', $.proxy( this.deleteLayer, this ) );
			editor.find( '.duplicate-layer' ).on( 'click', $.proxy( this.duplicateLayer, this ) );

			editor.find( '.close' ).on( 'click', $.proxy( this.close, this ) );
			editor.find( '.save' ).on( 'click', $.proxy( this.save, this ) );

			var layersList = editor.find( '.layers-list' );

			layersList.find( '.layers-list-item' ).each(function( index, element ) {
				that.counter = Math.max( parseInt( $( element ).attr( 'data-id' ), 10 ),  that.counter );
			});

			layersList.on( 'click', '.layers-list-item', function( event ) {
				var layerID = $( this ).attr( 'data-id' );

				layersList.find( '.selected-layers-list-item' ).removeClass( 'selected-layers-list-item' );
				$( this ).addClass( 'selected-layers-list-item' );

				editor.find( '.selected-layer-settings' ).removeClass( 'selected-layer-settings' );
				editor.find( '#layer-settings-' + layerID ).addClass( 'selected-layer-settings' );
			});

			layersList.find( '.layers-list-item' ).first().trigger( 'click' );

			layersList.on( 'dblclick', '.layers-list-item', function( event ) {
				if ( isEditingLayerName === true ) {
					return;
				}

				isEditingLayerName = true;

				var item = $( this ),
					name = item.text();

				var input = $( '<input type="text" value="' + name + '" />' ).appendTo( item );
			});

			layersList.on( 'selectstart', '.layers-list-item', function( event ) {
				event.preventDefault();
			});

			editor.on( 'click', function( event ) {
				if ( ! $( event.target ).is( 'input' ) && isEditingLayerName === true ) {
					isEditingLayerName = false;

					var input = layersList.find( 'input' ),
						name = input.val();

					input.parent( '.layers-list-item' ).text( name );
					input.remove();
				}
			});
		},

		save: function() {
			event.preventDefault();

			var that = this,
				layers = [];

			editor.find( '.layer-settings' ).each( function( index, element ) {
				var counter = $( element ).attr( 'data-id' ),
					layer = {};

				layer.id = counter;
				layer.name = editor.find( '.layers-list-item[data-id="' + counter + '"]' ).text();
				layer.settings = {};

				$( element ).find( '.field' ).each(function() {
					var field = $( this ),
						type = field.attr( 'type' );

					if ( type === 'radio' ) {
						if ( field.is( ':checked' ) ) {
							layer.settings[ field.attr( 'name' ).split( '-' )[ 0 ] ] = field.val();
						}
					} else if (type === 'checkbox' ) {
						layer.settings[ field.attr( 'name' ) ] = field.is( ':checked' );
					} else {
						layer.settings[ field.attr( 'name' ) ] = field.val();
					}
				});

				layers.push( layer) ;
			});

			this.currentPanel.setData( 'layers', layers );

			this.close();
		},

		close: function() {
			event.preventDefault();

			editor.find( '.close' ).off( 'click' );
			editor.find( '.save' ).off( 'click' );

			$( 'body' ).find( '.modal-overlay, .layers-editor' ).remove();
		},

		addNewLayer: function() {
			event.preventDefault();

			var that = this;

			this.counter++;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'accordion_slider_add_layer_settings', data: this.counter },
				complete: function( data ) {
					$( '.layers-settings' ).append( data.responseText );

					var layersList = editor.find( '.layers-list' ),
						layerListItem = $( '<li class="layers-list-item" data-id="' + that.counter + '">Layer ' + that.counter + '</li>' ).appendTo( layersList );

					layerListItem.trigger( 'click' );
				}
			});
		},

		deleteLayer: function() {
			event.preventDefault();

			var selectedLayer = editor.find( '.selected-layers-list-item' ),
				selectedLayerID = parseInt( selectedLayer.attr( 'data-id' ), 10 );

			selectedLayer.remove();
			editor.find( '.layer-settings[data-id="' + selectedLayerID + '"]' ).remove();
		},

		duplicateLayer: function() {
			event.preventDefault();

			
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
				callback.call( this, selection );

				wp.media.editor.insert = insertReference;
			};

			wp.media.editor.open( 'media-loader' );
		}

	};

	$( document ).ready(function() {
		AccordionSliderAdmin.init();
	});

})( jQuery );