(function($) {

	var AccordionSliderAdmin = {

		panels: [],

		init: function() {
			var that = this;

			if (as_js_vars.page === 'single') {
				this.initPanels();

				if (as_js_vars.id !== -1) {
					$.ajax({
						url: as_js_vars.ajaxurl,
						type: 'get',
						data: {action: 'accordion_slider_get_accordion', id: as_js_vars.id},
						complete: function(data) {
							var accordionData = JSON.parse(data.responseText);

							$.each(accordionData.panels, function(index, element) {
								that.getPanel(index).setData(element);
							});
						}
					});
				}

				$('form').on('submit', function(event) {
					event.preventDefault();

					var accordionData = {
						'id': as_js_vars.id,
						'name': $('input#title').val(),
						'settings': {},
						'panels': []
					};

					$('#panels-container').find('.admin-panel').each(function(index) {
						accordionData.panels[index] = that.getPanel(index).getData();
					});

					$('#sidebar-settings').find('.setting').each(function() {
						var setting = $(this);
						accordionData.settings[setting.attr('name')] = setting.attr('type') === 'checkbox' ? setting.is(':checked') : setting.val();
					});

					var accordionDataString = JSON.stringify(accordionData);

					$.ajax({
						url: as_js_vars.ajaxurl,
						type: 'post',
						data: {action: 'accordion_slider_update_accordion', data: accordionDataString},
						complete: function(data) {
							if (parseInt(as_js_vars.id, 10) === -1 && isNaN(data.responseText) === false) {
								window.location = as_js_vars.admin + '?page=accordion-slider&id=' + data.responseText + '&action=edit';
							}
						}
					});
				});
			}
		},

		initPanels: function() {
			var that = this;

			$('#panels-container').find('.admin-panel').each(function(index) {
				that.createPanel($(this), index);
			});
		},

		createPanel: function(element, index) {
			var panel = new AdminPanel(element, index);
			this.panels.push(panel);
		},

		getPanel: function(index) {
			return this.panels[index];
		}
	};

	var AdminPanel = function(element, index) {
		this.$element = element;
		this.index = index;
		this.panelData = {};

		this.init();
	};

	AdminPanel.prototype = {

		init: function() {
			var that = this;

			this.$element.find('.edit-background-image').on('click', function(event) {
				event.preventDefault();

				BackgroundImageEditor.open(that.index);
			});
		},

		getData: function() {
			return this.panelData;
		},

		setData: function(data) {
			this.panelData = data;
		},

		updateData: function(name, value) {
			this.panelData[name] = value;
		}
	};

	var BackgroundImageEditor = {

		editor: null,

		currentPanel: null,

		currentPanelData: null,

		open: function(index) {
			var that = this;

			this.currentPanel = AccordionSliderAdmin.getPanel(index);
			this.currentPanelData = this.currentPanel.getData();

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				dataType: 'html',
				data: {action: 'accordion_slider_get_background_image_editor'},
				complete: function(data) {
					$('body').append(data.responseText);
					that.init();
				}
			});
		},

		init: function() {
			editor = $('.background-image-editor');

			this.populateFields();

			editor.find('.close, .close-x').on('click', $.proxy(this.close, this));
			editor.find('.save').on('click', $.proxy(this.save, this));

			editor.find('.image-loader').on('click', $.proxy(this.openMediaLibrary, this));
		},

		populateFields: function() {
			var that = this;

			editor.find('.field').each(function() {
				var $this = $(this),
					name = $this.attr('name');

				if (typeof that.currentPanelData[name] !== 'undefined') {
					$this.val(that.currentPanelData[name]);
				}
			});
		},

		save: function() {
			event.preventDefault();

			var that = this;

			editor.find('.field').each(function() {
				var $this = $(this);

				if ($this.val().length !== 0) {
					that.currentPanel.updateData($this.attr('name'), $this.val());
				}
			});

			this.close();
		},

		close: function() {
			event.preventDefault();

			$('body').find('.modal-overlay, .background-image-editor').remove();
		},

		openMediaLibrary: function() {
			
		}
	};

	$(document).ready(function() {
		AccordionSliderAdmin.init();
	});

})(jQuery);