(function($) {

	var AccordionSliderAdmin = {

		panels: [],

		initPanels: function() {
			var that = this;

			$('#panels-container').find('.admin-panel').each(function() {
				that.createPanel($(this));
			});
		},

		createPanel: function(element) {
			var panel = new AdminPanel(element);
			this.panels.push(panel);
		}

	};

	var AdminPanel = function(element) {
		this.$element = element;

		this.init();
	};

	AdminPanel.prototype = {

		init: function() {
			this.$element.find('.edit-background-image').on('click', function(event) {
				event.preventDefault();

				BackgroundImageEditor.open();
			});
		}
	};

	var BackgroundImageEditor = {

		html: '',

		panelIndex: -1,
		
		open: function(index) {
			var that = this;

			this.panelIndex = index;

			$.ajax({
				url: as_js_vars.ajaxurl,
				type: 'get',
				dataType: 'html',
				data: {action: 'accordion_slider_get_background_image_editor', accordion_id: 0, panel_id: 0},
				complete: function(data) {
					that.html = data.responseText;
					that.create();
				}
			});
		},

		create: function() {
			$('body').append(this.html);

			var editor = $('.background-image-editor');
			editor.find('.close-editor').on('click', this.close);
		},

		close: function() {
			$('body').find('.modal-overlay, .background-image-editor').remove();
		}
	};

	$(document).ready(function() {
		AccordionSliderAdmin.initPanels();
	});

})(jQuery);