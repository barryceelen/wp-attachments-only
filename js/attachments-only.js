(function ($) {
	var AttachmentsOnly = {
		init: function() {
			this.setupEventHandlers();
		},
		openMediaFrame: function() {
			var mediaFrame;
			if ( mediaFrame ) {
				mediaFrame.open();
				return;
			}

			var states = [
				new wp.media.controller.Library( {
					date: false,
					// Attachments display settings (Alignment, size etc.), false by default but leaving this for future reference:
					displaySettings: false,
					filterable: false,
					searchable: true,
					title: attachmentsOnlyVars.media_library_title,
					toolbar: '', // Todo: Do something useful with the toolbar.
					library:  wp.media.query( {
						uploadedTo: wp.media.view.settings.post.id,
						orderby : 'menuOrder',
						order: 'ASC',
					} ),
					priority: 10
				} )
			];

			mediaFrame = wp.media.frames.mediaFrame = wp.media({
				states: states
			});

			mediaFrame.open();
		},
		setupEventHandlers: function() {
			var _this = this;
			$(document.body).on( 'click', '#insert-media-button-attachments-only', function(e){
				e.preventDefault();
				_this.openMediaFrame();
			});
		}
	}
	AttachmentsOnly.init();
}(jQuery));