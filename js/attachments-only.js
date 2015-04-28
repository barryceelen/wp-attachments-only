(function ($) {
	var attachmentsOnly = {
		buttonID: '#insert-media-button-attachments-only',
		init: function() {
			var _this = this;
			$(document.body).on( 'click.attachmentsOnly', this.buttonID, function() {
				_this.openMediaFrame();
			});
			$('#postimagediv').off( 'click', '#set-post-thumbnail' ).on( 'click.attachmentsOnly', '#set-post-thumbnail', function( event ) {
					event.preventDefault();
					event.stopPropagation();
					_this.openMediaFrame( 'featured-image' );
			});
		},
		openMediaFrame: function( state ) {

			state = state || 'library';

			if ( this.frame ) {
				this.frame.open();
				return;
			}

			var states = [
				new wp.media.controller.Library( {
					date: false,
					filterable: false,
					id: 'library',
					searchable: true,
					toolbar: 'generic',
					library:  wp.media.query( {
						uploadedTo: wp.media.view.settings.post.id,
						orderby: 'menuOrder',
						order: 'ASC'
					} ),
					priority: 10
				} ),
				new wp.media.controller.EditImage()
			];

			if ( wp.media.view.settings.post.featuredImageId ) {
				states.push( new wp.media.controller.FeaturedImage( {
					date: false,
					filterable: false,
					id: 'featured-image',
					searchable: true,
					library:  wp.media.query( {
						uploadedTo: wp.media.view.settings.post.id,
						type: 'image'
					} ),
					priority: 20
				} ) );
			}

			this.frame = wp.media.frames.attachmentsOnly = wp.media({
				states: states
			});

			this.frame.on( 'toolbar:create:generic', this.genericToolbar, this.frame );
			this.frame.on( 'toolbar:create:featured-image', this.featuredImageToolbar, this.frame );
			this.frame.on( 'content:render:edit-image', this.editImage, this.frame );

			this.frame.state( 'featured-image' ).on( 'select', function() {
				var selection = this.get( 'selection' ).first();
				wp.media.featuredImage.set( selection.id );
			});

			// Todo: how to change the state back and forth depending on which button triggered it?
			this.frame.options.state = state;

			this.frame.open();

		},
		editImage: function() {
			var selection = this.state('library').get('selection'),
				view = new wp.media.view.EditImage( { model: selection.single(), controller: this } ).render();

			this.content.set( view );

			// after bringing in the frame, load the actual editor via an ajax call
			view.loadEditor();
		},
		featuredImageToolbar: function( toolbar ) {
			this.createSelectToolbar( toolbar, {
				text: wp.media.view.l10n.setFeaturedImage
			});
		},
		/*
		Haven't reviewed the code to see if it is possible to get rid of the button.
		Short of defining my own view, I'll just use a generic 'Done' button.
		*/
		genericToolbar: function( toolbar ) {
			this.createSelectToolbar( toolbar, {
				text: wp.media.view.l10n.done
			});
		}
	};
	$(document).ready( function() {
		attachmentsOnly.init();
	});
}(jQuery));
