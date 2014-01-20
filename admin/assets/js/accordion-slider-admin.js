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

			if ( parseInt( as_js_vars.id, 10 ) !== -1 ) {
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

			$( '.breakpoints' ).lightSortable( {
				children: '.breakpoint',
				placeholder: ''
			} );
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

			$( '.accordions-list tbody' ).lightSortable( {
				children: '.accordion-row',
				placeholder: ''
			} );
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
				var panelData = that.getPanel( parseInt( $( element ).attr('data-index'), 10) ).getData( 'all' );
				panelData.position = parseInt( $( element ).attr( 'data-position' ), 10 );

				accordionData.panels[ index ] = panelData;
			});

			$( '.sidebar-settings' ).find( '.setting' ).each(function() {
				var setting = $( this );
				accordionData.settings[ setting.attr( 'name' ) ] = setting.attr( 'type' ) === 'checkbox' ? setting.is( ':checked' ) : setting.val();
			});

			var breakpoints = [];

			$( '.breakpoints' ).find( '.breakpoint' ).each(function() {
				var breakpointGroup = $( this ),
					breakpoint = { 'breakpoint_width': breakpointGroup.find( 'input[name="breakpoint_width"]' ).val() };

				breakpointGroup.find( '.breakpoint-setting' ).each(function() {
					var breakpointSetting = $( this );

					breakpoint[ breakpointSetting.attr( 'name' ) ] = breakpointSetting.attr( 'type' ) === 'checkbox' ? breakpointSetting.is( ':checked' ) : breakpointSetting.val();
				});

				breakpoints.push( breakpoint );
			});

			if ( breakpoints.length > 0 ) {
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

			$( '.panels-container' ).lightSortable( {
				children: '.panel',
				placeholder: 'panel panel-placeholder'
			} );
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

			element.attr( 'data-index', index );
			element.attr( 'data-position', index );
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
				background_source: panelData.background.background_source
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
					images.push( { background_source: element.url, background_alt: element.alt, background_title: element.title,  background_width: element.width,  background_height: element.height } );
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

					that.setData( 'background', { background_source: image.url, background_alt: image.alt, background_title: image.title, background_width: image.width, background_height: image.height } );
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
					editor.find( 'input[name="background_width"]' ).val( image.width );
					editor.find( 'input[name="background_height"]' ).val( image.height );
				} else if ( target === 'opened-background' ) {
					editor.find( 'input[name="opened_background_source"]' ).val( image.url );
					editor.find( 'input[name="opened_background_alt"]' ).val( image.alt );
					editor.find( 'input[name="opened_background_title"]' ).val( image.title );
					editor.find( 'input[name="opened_background_width"]' ).val( image.width );
					editor.find( 'input[name="opened_background_height"]' ).val( image.height );
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

		currentPanel: null,

		layersData: null,

		layers: [],

		counter: 0,

		open: function( index ) {
			var that = this;

			this.currentPanel = AccordionSliderAdmin.getPanel( index );
			this.layersData = this.currentPanel.getData( 'layers' );

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
			var that = this;

			this.counter = 0;

			editor = $( '.layers-editor' );

			editor.find( '.add-new-layer' ).on( 'click', $.proxy( this.addNewLayer, this ) );
			editor.find( '.delete-layer' ).on( 'click', $.proxy( this.deleteLayer, this ) );
			editor.find( '.duplicate-layer' ).on( 'click', $.proxy( this.duplicateLayer, this ) );

			editor.find( '.close' ).on( 'click', $.proxy( this.close, this ) );
			editor.find( '.save' ).on( 'click', $.proxy( this.save, this ) );

			this.initViewport();

			$.each( this.layersData, function( index, layerData ) {
				that.createLayer( layerData.id, layerData );

				that.counter = Math.max( that.counter, layerData.id );
			});

			$( '.layers-list' ).lightSortable( {
				children: '.layers-list-item',
				placeholder: 'layers-list-item-placeholder',
				sortEnd: function( event ) {
					var layer = that.layers[ event.startPosition ];
					that.layers.splice( event.startPosition, 1 );
					that.layers.splice( event.endPosition, 0, layer );

					$( '.layers-list' ).find( '.layers-list-item' ).each(function( index, element ) {
						$( element ).attr( 'data-position', index );
					});
				}
			} );

			$( '.layers-list' ).find( '.layers-list-item' ).each(function( index, element ) {
				$( element ).attr( 'data-position', index );
			});

			if ( this.layers.length !== 0 ) {
				this.layers[ 0 ].triggerSelect();
			}
		},

		initViewport: function() {
			var orientation = $( '.sidebar-settings' ).find( '.setting[name="orientation"]' ).val(),
				accordionWidth = parseInt( $( '.sidebar-settings' ).find( '.setting[name="width"]' ).val(), 10),
				accordionHeight = parseInt( $( '.sidebar-settings' ).find( '.setting[name="height"]' ).val(), 10),
				backgroundData = this.currentPanel.getData( 'background' ),
				imageWidth = backgroundData.background_width,
				imageHeight = backgroundData.background_height;

			if ( orientation === 'horizontal' ) {
				imageWidth = imageWidth * ( accordionHeight / imageHeight );
				imageHeight = accordionHeight;
			} else if ( orientation === 'vertical' ) {
				imageHeight = imageHeight * ( accordionWidth / imageWidth );
				imageWidth = accordionWidth;
			}

			var viewport = editor.find( '.viewport' ),
				viewportImage = $( '<img class="viewport-image" src="' + backgroundData.background_source + '" width="' + imageWidth + '" height="' + imageHeight + '" />' ),
				viewportLayers = $( '<div class="viewport-layers"></div>' );
			
			viewportImage.appendTo( viewport );
			viewportLayers.appendTo( viewport );

			editor.css( { 'width': Math.max( imageWidth, 960 ), 'height': imageHeight + 180 } );
			viewport.css( 'height', imageHeight );

			if ( imageWidth < 960 ) {
				viewportImage.css( 'left', ( 960 - imageWidth ) / 2 );
			}

			viewportLayers.css( {
				'width': imageWidth,
				'height': imageHeight,
				'left': viewportImage.position().left,
				'top': viewportImage.position().top
			});
		},

		createLayer: function( id, data ) {
			var that = this,
				layer = new Layer( id, data, editor );

			this.layers.push( layer );

			layer.on( 'select', function( event ) {
				$.each( that.layers, function( index, layer ) {
					if ( layer.isSelected() === true ) {
						layer.deselect();
					}

					if (layer.getID() === event.id) {
						layer.select();
					}
				});
			});

			layer.triggerSelect();
		},

		addNewLayer: function() {
			event.preventDefault();

			var that = this;

			this.counter++;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'accordion_slider_add_layer_settings', id: this.counter },
				complete: function( data ) {
					$( data.responseText ).appendTo( $( '.layers-settings' ) );
					$( '<li class="layers-list-item" data-id="' + that.counter + '" data-position="' + that.layers.length + '">Layer ' + that.counter + '</li>' ).appendTo( editor.find( '.layers-list' ) );

					that.createLayer( that.counter, false );
				}
			});
		},

		deleteLayer: function() {
			event.preventDefault();

			var that = this,
				removedIndex;

			$.each( this.layers, function( index, layer ) {
				if ( layer.isSelected() === true ) {
					layer.destroy();
					that.layers.splice( index, 1 );
					removedIndex = index;

					return false;
				}
			});

			if ( removedIndex === 0 ) {
				this.layers[ 0 ].triggerSelect();
			} else {
				this.layers[ removedIndex - 1 ].triggerSelect();
			}
		},

		duplicateLayer: function() {
			event.preventDefault();

			var that = this,
				layerData;

			this.counter++;

			$.each( this.layers, function( index, layer ) {
				if ( layer.isSelected() === true ) {
					layerData = layer.getData();
				}
			});

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'post',
				dataType: 'html',
				data: { action: 'accordion_slider_add_layer_settings', id: this.counter, content: layerData.content, settings: JSON.stringify( layerData.settings ) },
				complete: function( data ) {
					$( data.responseText ).appendTo( $( '.layers-settings' ) );
					$( '<li class="layers-list-item" data-id="' + that.counter + '">Layer ' + that.counter + '</li>' ).appendTo( editor.find( '.layers-list' ) );

					that.createLayer( that.counter, layerData );
				}
			});
		},

		save: function() {
			event.preventDefault();

			var data = [];

			$.each( this.layers, function( index, element ) {
				data.push( element.getData() );
			});

			this.currentPanel.setData( 'layers', data );

			this.close();
		},

		close: function() {
			event.preventDefault();

			this.layers.length = 0;

			editor.find( '.close' ).off( 'click' );
			editor.find( '.save' ).off( 'click' );

			$( '.layers-list' ).lightSortable( 'destroy' );

			$( 'body' ).find( '.modal-overlay, .layers-editor' ).remove();
		}
	};

	var Layer = function( id, data, editor ) {
		this.id = id;
		this.data = data;
		this.editor = editor;

		this.selected = false;
		this.events = $( {} );

		this.init();
	};

	Layer.prototype = {

		init: function() {
			this.$viewportLayers = this.editor.find( '.viewport-layers' );

			this.$viewportLayer = $( '<div class="viewport-layer as-layer"></div>' ).appendTo( this.$viewportLayers );
			this.$layerListItem = this.editor.find( '.layers-list-item[data-id="' + this.id + '"]' );
			this.$layerSettings = this.editor.find( '.layer-settings[data-id="' + this.id + '"]' );

			this.initViewportLayer();
			this.initLayerDragging();
			this.initLayerListItem();
			this.initLayerSettings();
		},

		getData: function() {
			var data = {};

			data.id = this.id;
			data.position = parseInt( this.$layerListItem.attr( 'data-position' ), 10);
			data.name = this.$layerListItem.text();
			data.content = this.$layerSettings.find( '.content' ).val();
			data.settings = {};

			this.$layerSettings.find( '.setting' ).each(function() {
				var settingField = $( this ),
					type = settingField.attr( 'type' );

				if ( type === 'radio' ) {
					if ( settingField.is( ':checked' ) ) {
						data.settings[ settingField.attr( 'name' ).split( '-' )[ 0 ] ] = settingField.val();
					}
				} else if (type === 'checkbox' ) {
					data.settings[ settingField.attr( 'name' ) ] = settingField.is( ':checked' );
				} else {
					data.settings[ settingField.attr( 'name' ) ] = settingField.val();
				}
			});

			return data;
		},

		getID: function() {
			return this.id;
		},

		select: function() {
			this.selected = true;

			this.$layerListItem.addClass( 'selected-layers-list-item' );
			this.$layerSettings.addClass( 'selected-layer-settings' );
		},

		deselect: function() {
			this.selected = false;

			this.$layerListItem.removeClass( 'selected-layers-list-item' );
			this.$layerSettings.removeClass( 'selected-layer-settings' );
		},

		triggerSelect: function() {
			this.trigger( { type: 'select', id: this.id } );
		},

		isSelected: function() {
			return this.selected;
		},

		destroy: function() {
			this.$viewportLayer.off( 'mousedown' );
			this.$viewportLayer.off( 'mouseup' );
			this.$viewportLayer.off( 'click' );

			this.$layerListItem.off( 'click' );
			this.$layerListItem.off( 'dblclick' );
			this.$layerListItem.off( 'selectstart' );

			this.editor.off( 'mousemove.layer' + this.id );
			this.editor.off( 'click.layer' + this.id );

			this.$viewportLayer.remove();
			this.$layerListItem.remove();
			this.$layerSettings.remove();
		},

		on: function( type, handler ) {
			this.events.on( type, handler );
		},

		off: function( type ) {
			this.events.off( type );
		},

		trigger: function( type ) {
			this.events.triggerHandler( type );
		},

		initViewportLayer: function() {
			var that = this,
				classes = '';

			if ( this.data === false ) {
				this.$viewportLayer.attr( 'data-id', this.id )
									.addClass( 'as-black as-padding' )
									.css( { 'width': 'auto', 'height': 'auto', 'left': 0, 'top': 0 } )
									.text( 'New layer' );
			} else {
				this.$viewportLayer.attr( 'data-id', this.id );
				this.$viewportLayer.html( this.data.content );

				if ( this.data.settings.black_background === true ) {
					classes += ' as-black';
				}

				if ( this.data.settings.white_background === true ) {
					classes += ' as-white';
				}

				if ( this.data.settings.padding === true ) {
					classes += ' as-padding';
				}

				if ( this.data.settings.round_corners === true ) {
					classes += ' as-rounded';
				}

				if ( this.data.settings.custom_class !== '' ) {
					classes += ' ' + this.data.settings.custom_class;
				}

				this.$viewportLayer.addClass( classes );

				this.$viewportLayer.css( { 'width': this.data.settings.width, 'height': this.data.settings.height } );

				var position = this.data.settings.position.toLowerCase(),
					horizontalPosition = position.indexOf( 'right' ) !== -1 ? 'right' : 'left',
					verticalPosition = position.indexOf( 'bottom' ) !== -1 ? 'bottom' : 'top';

				if ( this.data.settings.horizontal === 'center' ) {
					this.$viewportLayer.css( { 'width': this.$viewportLayer.outerWidth( true ), 'marginLeft': 'auto', 'marginRight': 'auto', 'left': 0, 'right': 0 } );
				} else {
					suffix = this.data.settings.horizontal.indexOf( 'px' ) === -1 && this.data.settings.horizontal.indexOf( '%' ) === -1 ? 'px' : '';
					this.$viewportLayer.css( horizontalPosition, this.data.settings.horizontal + suffix );
				}

				if ( this.data.settings.vertical === 'center' ) {
					this.$viewportLayer.css( { 'height': this.$viewportLayer.outerHeight( true ),  'marginTop': 'auto', 'marginBottom': 'auto', 'top': 0, 'bottom': 0 } );
				} else {
					suffix = this.data.settings.vertical.indexOf( 'px' ) === -1 && this.data.settings.vertical.indexOf( '%' ) === -1 ? 'px' : '';
					this.$viewportLayer.css( verticalPosition, this.data.settings.vertical + suffix );
				}
			}

			this.$viewportLayer.on( 'click', function() {
				that.triggerSelect();
			});
		},

		initLayerDragging: function() {
			var that = this,
				mouseX = 0,
				mouseY = 0,
				layerX = 0,
				layerY = 0,
				hasFocus = false,
				autoRightBottom = false;

			this.$viewportLayer.on( 'mousedown', function( event ) {
				mouseX = event.pageX;
				mouseY = event.pageY;
				layerX = that.$viewportLayer[ 0 ].offsetLeft;
				layerY = that.$viewportLayer[ 0 ].offsetTop;

				hasFocus = true;
			});

			this.editor.on( 'mousemove.layer' + this.id, function( event ) {
				if ( hasFocus === true ) {
					that.$viewportLayer.css( { 'left': layerX + event.pageX - mouseX, 'top': layerY + event.pageY - mouseY } );

					if ( autoRightBottom === false ) {
						autoRightBottom = true;
						that.$viewportLayer.css( { 'right': 'auto', 'bottom': 'auto' } );
					}
				}
			});

			this.$viewportLayer.on( 'mouseup', function( event ) {
				var position = that.$layerSettings.find( '.setting[name="position"]' ).val().toLowerCase(),
					horizontalPosition = position.indexOf( 'right' ) !== -1 ? 'right' : 'left',
					verticalPosition = position.indexOf( 'bottom' ) !== -1 ? 'bottom' : 'top';

				hasFocus = false;
				autoRightBottom = false;

				if ( horizontalPosition === 'left' ) {
					that.$layerSettings.find( '.setting[name="horizontal"]' ).val( that.$viewportLayer.position().left );
				} else if ( horizontalPosition === 'right' ) {
					var right = that.editor.find( '.viewport-layers' ).width() - that.$viewportLayer.position().left - that.$viewportLayer.outerWidth( true );

					that.$layerSettings.find( '.setting[name="horizontal"]' ).val( right );
					that.$viewportLayer.css( { 'left': 'auto', 'right': right } );
				}

				if ( verticalPosition === 'top' ) {
					that.$layerSettings.find( '.setting[name="vertical"]' ).val( that.$viewportLayer.position().top );
				} else if ( verticalPosition === 'bottom' ) {
					var bottom = that.editor.find( '.viewport-layers' ).height() - that.$viewportLayer.position().top - that.$viewportLayer.outerHeight( true );

					that.$layerSettings.find( '.setting[name="vertical"]' ).val( bottom );
					that.$viewportLayer.css( { 'top': 'auto', 'bottom': bottom } );
				}
			});
		},

		initLayerListItem: function() {
			var that = this,
				isEditingLayerName = false;

			this.$layerListItem.on( 'click', function( event ) {
				that.trigger( { type: 'select', id: that.id } );
			});

			this.$layerListItem.on( 'dblclick', function( event ) {
				if ( isEditingLayerName === true ) {
					return;
				}

				isEditingLayerName = true;

				var name = that.$layerListItem.text();

				var input = $( '<input type="text" value="' + name + '" />' ).appendTo( that.$layerListItem );
			});

			this.$layerListItem.on( 'selectstart', function( event ) {
				event.preventDefault();
			});

			this.editor.on( 'click.layer' + this.id, function( event ) {
				if ( ! $( event.target ).is( 'input' ) && isEditingLayerName === true ) {
					isEditingLayerName = false;

					var input = that.$layerListItem.find( 'input' );

					that.$layerListItem.text( input.val() );
					input.remove();
				}
			});
		},

		initLayerSettings: function() {
			var that = this,
				position = this.$layerSettings.find( '.setting[name="position"]' ).val().toLowerCase(),
				horizontalPosition = position.indexOf( 'right' ) !== -1 ? 'right' : 'left',
				verticalPosition = position.indexOf( 'bottom' ) !== -1 ? 'bottom' : 'top',
				customClass = this.$layerSettings.find( '.setting[name="custom_class"]' ).val();

			this.$layerSettings.find( '.setting[name="width"]' ).on( 'change', function() {
				that.$viewportLayer.css( 'width', $( this ).val() );
			});

			this.$layerSettings.find( '.setting[name="height"]' ).on( 'change', function() {
				that.$viewportLayer.css( 'height', $( this ).val() );
			});

			this.$layerSettings.find( '.setting[name="position"], .setting[name="horizontal"], .setting[name="vertical"]' ).on( 'change', function() {
				var horizontal = that.$layerSettings.find( '.setting[name="horizontal"]' ).val(),
					vertical = that.$layerSettings.find( '.setting[name="vertical"]' ).val();

				position = that.$layerSettings.find( '.setting[name="position"]' ).val().toLowerCase();
				horizontalPosition = position.indexOf( 'right' ) !== -1 ? 'right' : 'left';
				verticalPosition = position.indexOf( 'bottom' ) !== -1 ? 'bottom' : 'top';

				that.$viewportLayer.css( { 'top': 'auto', 'bottom': 'auto', 'left': 'auto', 'right': 'auto' } );

				if ( horizontal === 'center' ) {
					that.$viewportLayer.css( { 'width': that.$viewportLayer.outerWidth( true ), 'marginLeft': 'auto', 'marginRight': 'auto', 'left': 0, 'right': 0 } );
				} else {
					suffix = horizontal.indexOf( 'px' ) === -1 && horizontal.indexOf( '%' ) === -1 ? 'px' : '';
					that.$viewportLayer.css( horizontalPosition, horizontal + suffix );
				}

				if ( vertical === 'center' ) {
					that.$viewportLayer.css( { 'height': that.$viewportLayer.outerHeight( true ),  'marginTop': 'auto', 'marginBottom': 'auto', 'top': 0, 'bottom': 0 } );
				} else {
					suffix = vertical.indexOf( 'px' ) === -1 && vertical.indexOf( '%' ) === -1 ? 'px' : '';
					that.$viewportLayer.css( verticalPosition, vertical + suffix );
				}
			});

			this.$layerSettings.find( '.setting[name="black_background"]' ).on( 'change', function() {
				if ( $( this ).is( ':checked' ) ) {
					that.$viewportLayer.addClass( 'as-black' );
				} else {
					that.$viewportLayer.removeClass( 'as-black' );
				}
			});

			this.$layerSettings.find( '.setting[name="white_background"]' ).on( 'change', function() {
				if ( $( this ).is( ':checked' ) ) {
					that.$viewportLayer.addClass( 'as-white' );
				} else {
					that.$viewportLayer.removeClass( 'as-white' );
				}
			});

			this.$layerSettings.find( '.setting[name="padding"]' ).on( 'change', function() {
				if ( $( this ).is( ':checked' ) ) {
					that.$viewportLayer.addClass( 'as-padding' );
				} else {
					that.$viewportLayer.removeClass( 'as-padding' );
				}
			});

			this.$layerSettings.find( '.setting[name="round_corners"]' ).on( 'change', function() {
				if ( $( this ).is( ':checked' ) ) {
					that.$viewportLayer.addClass( 'as-rounded' );
				} else {
					that.$viewportLayer.removeClass( 'as-rounded' );
				}
			});

			this.$layerSettings.find( '.setting[name="custom_class"]' ).on( 'change', function(event) {
				that.$viewportLayer.removeClass( customClass );

				customClass = $( this ).val();

				that.$viewportLayer.addClass( customClass );
			});

			this.$layerSettings.find( '.content' ).on( 'input', function() {
				that.$viewportLayer.html( $( this ).val() );
			});
		}

	};

	var MediaLoader = {

		open: function( callback ) {
			var selection = [],
				insertReference = wp.media.editor.insert;
			
			wp.media.editor.send.attachment = function( props, attachment ) {
				var url = attachment.sizes[ props.size ].url,
					alt = attachment.alt,
					title = attachment.title,
					width = attachment.width,
					height = attachment.height;

				selection.push( { url: url, alt: alt, title: title, width: width, height: height } );
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



;(function( $, window, document ) {

	var LightSortable = function( instance, options ) {

		this.options = options;
		this.$container = $( instance );
		this.$selectedChild = null;
		this.$placeholder = null;

		this.currentMouseX = 0;
		this.currentMouseY = 0;
		this.panelInitialX = 0;
		this.panelInitialY = 0;
		this.initialMouseX = 0;
		this.initialMouseY = 0;
		this.isDragging = false;
		
		this.checkHover = 0;

		this.uid = new Date().valueOf();

		this.events = $( {} );
		this.startPosition = 0;
		this.endPosition = 0;

		this.init();
	};

	LightSortable.prototype = {

		init: function() {
			this.settings = $.extend( {}, this.defaults, this.options );

			this.$container.on( 'mousedown.lightSortable' + this.uid, $.proxy( this.onDragStart, this ) );
			$( 'body' ).on( 'mousemove.lightSortable.' + this.uid, $.proxy( this.onDragging, this ) );
			$( 'body' ).on( 'mouseup.lightSortable.' + this.uid, $.proxy( this.onDragEnd, this ) );
		},

		onDragStart: function( event ) {
			if ( event.which !== 1 || $( event.target ).is( 'select' ) || $( event.target ).is( 'input' ) ) {
				return;
			}

			this.$selectedChild = $( event.target ).is( this.settings.children ) ? $( event.target ) : $( event.target ).parents( this.settings.children );

			if ( this.$selectedChild.length === 1 ) {
				this.initialMouseX = event.pageX;
				this.initialMouseY = event.pageY;
				this.panelInitialX = this.$selectedChild.position().left;
				this.panelInitialY = this.$selectedChild.position().top;

				this.startPosition = this.$selectedChild.index();
			}
		},

		onDragging: function( event ) {
			if ( this.$selectedChild === null )
				return;

			this.currentMouseX = event.pageX;
			this.currentMouseY = event.pageY;

			if ( ! this.isDragging ) {
				this.isDragging = true;

				this.trigger( { type: 'sortStart' } );
				if ( $.isFunction( this.settings.sortStart ) ) {
					this.settings.sortStart.call( this, { type: 'sortStart' } );
				}

				var tag = this.$container.is( 'ul' ) || this.$container.is( 'ol' ) ? 'li' : 'div';

				this.$placeholder = $( '<' + tag + '>' ).addClass( 'ls-ignore ' + this.settings.placeholder )
					.insertAfter( this.$selectedChild );

				if ( this.$placeholder.width() === 0 ) {
					this.$placeholder.css( 'width', this.$selectedChild.outerWidth() );
				}

				if ( this.$placeholder.height() === 0 ) {
					this.$placeholder.css( 'height', this.$selectedChild.outerHeight() );
				}

				this.$selectedChild.css( {
						'pointer-events': 'none',
						'position': 'absolute',
						left: this.$selectedChild.position().left,
						top: this.$selectedChild.position().top,
						width: this.$selectedChild.width(),
						height: this.$selectedChild.height()
					} )
					.addClass( 'ls-ignore' );

				this.$container.append( this.$selectedChild );

				$( 'body' ).css( 'user-select', 'none' );

				var that = this;

				this.checkHover = setInterval( function() {

					that.$container.find( that.settings.children ).not( '.ls-ignore' ).each( function() {
						var $currentChild = $( this );

						if ( that.currentMouseX > $currentChild.offset().left &&
							that.currentMouseX < $currentChild.offset().left + $currentChild.width() &&
							that.currentMouseY > $currentChild.offset().top &&
							that.currentMouseY < $currentChild.offset().top + $currentChild.height() ) {

							if ( $currentChild.index() >= that.$placeholder.index() )
								that.$placeholder.insertAfter( $currentChild );
							else
								that.$placeholder.insertBefore( $currentChild );
						}
					});
				}, 200 );
			}

			this.$selectedChild.css( { 'left': this.currentMouseX - this.initialMouseX + this.panelInitialX, 'top': this.currentMouseY - this.initialMouseY + this.panelInitialY } );
		},

		onDragEnd: function() {
			if ( this.isDragging ) {
				this.isDragging = false;

				$( 'body' ).css( 'user-select', '');

				this.$selectedChild.css( { 'position': 'relative', left: '', top: '', width: '', height: '', 'pointer-events': 'auto' } )
									.removeClass( 'ls-ignore' )
									.insertAfter( this.$placeholder );

				this.$placeholder.remove();

				clearInterval( this.checkHover );

				this.endPosition = this.$selectedChild.index();

				this.trigger( { type: 'sortEnd' } );
				if ( $.isFunction( this.settings.sortEnd ) ) {
					this.settings.sortEnd.call( this, { type: 'sortEnd', startPosition: this.startPosition, endPosition: this.endPosition } );
				}
			}

			this.$selectedChild = null;
		},

		destroy: function() {
			if ( this.isDragging ) {
				this.onDragEnd();
			}

			this.$container.off( 'mousedown.lightSortable.' + this.uid );
			$( 'body' ).off( 'mousemove.lightSortable.' + this.uid );
			$( 'body' ).off( 'mouseup.lightSortable.' + this.uid );
		},

		on: function( type, callback ) {
			return this.events.on( type, callback );
		},
		
		off: function( type ) {
			return this.events.off( type );
		},

		trigger: function( data ) {
			return this.events.triggerHandler( data );
		},

		defaults: {
			placeholder: '',
			sortStart: function() {},
			sortEnd: function() {}
		}

	};

	$.fn.lightSortable = function( options ) {
		var args = Array.prototype.slice.call( arguments, 1 );

		return this.each(function() {
			// instantiate the video controller or call a function on the current instance
			if ( typeof $( this ).data( 'lightSortable' ) === 'undefined' ) {
				var newInstance = new LightSortable( this, options );

				// store a reference to the instance created
				$( this ).data( 'lightSortable', newInstance );
			} else if ( typeof options !== 'undefined' ) {
				var	currentInstance = $( this ).data( 'lightSortable' );

				// check the type of argument passed
				if ( typeof currentInstance[ options ] === 'function' ) {
					currentInstance[ options ].apply( currentInstance, args );
				} else {
					$.error( options + ' does not exist in lightSortable.' );
				}
			}
		});
	};

})(jQuery, window, document);