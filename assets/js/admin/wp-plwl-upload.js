wp.TeamBuilderShowcase = 'undefined' === typeof( wp.TeamBuilderShowcase ) ? {} : wp.TeamBuilderShowcase;

(function( $, plwl ){

	var TeamBuilderShowcaseToolbar = wp.media.view.Toolbar.Select.extend({
		clickSelect: function() {

			var controller = this.controller,
				state = controller.state(),
				selection = state.get('selection');

			controller.close();
			state.trigger( 'insert', selection ).reset();

		}
	});

	var TeamBuilderShowcaseAttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
		createToolbar: function() {
			var LibraryViewSwitcher, Filters, toolbarOptions;

			wp.media.view.AttachmentsBrowser.prototype.createToolbar.call(this);


			this.toolbar.set( 'plwl-error', new plwl.upload['errorview']({
				controller: this.controller,
				priority: -80
			}) );

		},
	});

	var TeamBuilderShowcaseFrame = wp.media.view.MediaFrame.Select.extend({

		className: 'media-frame plwl-media-modal',

		createStates: function() {
			var options = this.options;
			options.library.type = 'image';
			if ( this.options.states ) {
				return;
			}

			// Add the default states.
			this.states.add([
				// Main states.
				new plwl.upload['library']({
					library:   wp.media.query( options.library ),
					multiple:  options.multiple,
					title:     options.title,
					priority:  20
				})
			]);
		},

		createSelectToolbar: function( toolbar, options ) {
			options = options || this.options.button || {};
			options.controller = this;

			toolbar.view = new plwl.upload['toolbar']( options );
		},

		browseContent: function( contentRegion ) {
			var state = this.state();

			// this.$el.removeClass('hide-toolbar');

			// Browse our library of attachments.
			contentRegion.view = new plwl.upload['attachmentsbrowser']({
			// contentRegion.view = new wp.media.view.AttachmentsBrowser({
				controller: this,
				collection: state.get('library'),
				selection:  state.get('selection'),
				model:      state,
				sortable:   state.get('sortable'),
				search:     state.get('searchable'),
				filters:    state.get('filterable'),
				date:       state.get('date'),
				display:    state.has('display') ? state.get('display') : state.get('displaySettings'),
				dragInfo:   state.get('dragInfo'),

				idealColumnWidth: state.get('idealColumnWidth'),
				suggestedWidth:   state.get('suggestedWidth'),
				suggestedHeight:  state.get('suggestedHeight'),

				AttachmentView: state.get('AttachmentView')
			});
		},

	});

	var TeamBuilderShowcaseSelection = wp.media.model.Selection.extend({

		add: function( models, options ) {
			var needed, differences;

			if ( ! this.multiple ) {
				this.remove( this.models );
			}

			if ( this.length >= 20 ) {
				models = [];
				wp.media.frames.plwl.trigger( 'plwl:show-error', {'message' : teamBuilderShowcaseHelper.strings.limitExceeded } );
			}else{

				needed = 20 - this.length;

				if ( Array.isArray( models ) && models.length > 1 ) {
					// Create an array with elements that we don't have in our selection
					differences = _.difference( _.pluck(models, 'cid'), _.pluck(this.models, 'cid') );
					// Check if we have mode elements that we need
					if ( differences.length > needed ) {
						// Filter our models, to have only that we don't have already
						models = _.filter( models, function( model ){
							return _.contains( differences, model.cid );
						});
						// Get only how many we need.
						models = models.slice( 0, needed );
						wp.media.frames.plwl.trigger( 'plwl:show-error', {'message' : teamBuilderShowcaseHelper.strings.limitExceeded } );
					}

				}

			}

			/**
			 * call 'add' directly on the parent class
			 */
			return wp.media.model.Attachments.prototype.add.call( this, models, options );
		},
		single: function( model ) {
			var previous = this._single;

			// If a `model` is provided, use it as the single model.
			if ( model ) {
				this._single = model;
			}
			// If the single model isn't in the selection, remove it.
			if ( this._single && ! this.get( this._single.cid ) ) {
				delete this._single;
			}

			this._single = this._single || this.last();

			// If single has changed, fire an event.
			if ( this._single !== previous ) {
				if ( previous ) {
					previous.trigger( 'selection:unsingle', previous, this );

					// If the model was already removed, trigger the collection
					// event manually.
					if ( ! this.get( previous.cid ) ) {
						this.trigger( 'selection:unsingle', previous, this );
					}
				}
				if ( this._single ) {
					this._single.trigger( 'selection:single', this._single, this );
				}
			}

			if(this.length < 20){
				wp.media.frames.plwl.trigger( 'plwl:hide-error', {'message' : teamBuilderShowcaseHelper.strings.limitExceeded } );
			}

			// Return the single model, or the last model as a fallback.
			return this._single;
		}
	});

	var TeamBuilderShowcaseLibrary = wp.media.controller.Library.extend({

		initialize: function() {
			var selection = this.get('selection'),
				props;

			if ( ! this.get('library') ) {
				this.set( 'library', wp.media.query() );
			}

			if ( ! selection ) {
				props = selection;

				if ( ! props ) {
					props = this.get('library').props.toJSON();
					props = _.omit( props, 'orderby', 'query' );
				}

				this.set( 'selection', new wp.media.model.Selection( null, {
					multiple: this.get('multiple'),
					props: props
				}) );
			}

			this.resetDisplays();
		},

	});

	var TeamBuilderShowcaseError = wp.media.View.extend({
		tagName:   'div',
		className: 'plwl-error-container hide',
		errorTimeout: false,
		delay: 400,
		message: '',

		initialize: function() {

			this.controller.on( 'plwl:show-error', this.show, this );
			this.controller.on( 'plwl:hide-error', this.hide, this );

			this.render();
		},

		show: function( e ) {

			if ( 'undefined' !== typeof e.message ) {
				this.message = e.message;
			}

			if ( '' != this.message ) {
				this.render();
				this.$el.removeClass( 'hide' );
			}

		},

		hide: function() {
			this.$el.addClass( 'hide' );
		},

		render: function() {
			var html = '<div class="plwl-error"><span>' + this.message + '</span></div>';
			this.$el.html( html );
		}
	});

	var uploadHandler = Backbone.Model.extend({
		uploaderOptions: {
			container: $( '#plwl-uploader-container' ),
			browser: $( '#plwl-uploader-browser' ),
			dropzone: $( '#plwl-uploader-container' ),
		},
		dropzone: $( '#plwl-dropzone-container' ),
		progressBar: $( '.plwl-progress-bar' ),
		containerUploader: $( '.plwl-upload-actions' ),
		errorContainer: $( '.plwl-error-container' ),
		galleryCotainer: $( '#plwl-uploader-container .plwl-uploader-inline-content' ),
		plwl_files_count: 0,
		limitExceeded: false,

		initialize: function(){
			var plwlGalleryObject = this,
				uploader,
				dropzone,
				attachments,
				limitExceeded = false,
				plwl_files_count = 0;

			uploader = new wp.Uploader( plwlGalleryObject.uploaderOptions );

			// Uploader events
			// Files Added for Uploading - show progress bar
			uploader.uploader.bind( 'FilesAdded', $.proxy( plwlGalleryObject.filesadded, plwlGalleryObject ) );

			// File Uploading - update progress bar
			uploader.uploader.bind( 'UploadProgress', $.proxy( plwlGalleryObject.fileuploading, plwlGalleryObject ) );

			// File Uploaded - add images to the screen
			uploader.uploader.bind( 'FileUploaded', $.proxy( plwlGalleryObject.fileupload, plwlGalleryObject ) );

			// Files Uploaded - hide progress bar
			uploader.uploader.bind( 'UploadComplete', $.proxy( plwlGalleryObject.filesuploaded, plwlGalleryObject ) );

			// File Upload Error - show errors
			uploader.uploader.bind( 'Error', function( up, err ) {

				// Show message
	            plwlGalleryObject.errorContainer.html( '<div class="error fade"><p>' + err.file.name + ': ' + err.message + '</p></div>' );
	            up.refresh();

			});

			// Dropzone events
			dropzone = uploader.dropzone;
			dropzone.on( 'dropzone:enter', plwlGalleryObject.show );
			dropzone.on( 'dropzone:leave', plwlGalleryObject.hide );

			// Single Image Actions ( Delete/Edit )
			plwlGalleryObject.galleryCotainer.on( 'click', '.plwl-delete-image', function( e ){
				e.preventDefault();
				$(this).parents( '.plwl-single-image' ).remove();
			});

			// TeamBuilderShowcase WordPress Media Library
	        wp.media.frames.plwl = new plwl.upload['frame']({
	            frame: 'select',
	            reset: false,
	            title:  wp.media.view.l10n.addToGalleryTitle,
	            button: {
	                text: wp.media.view.l10n.addToGallery,
	            },
	            multiple: 'add',
	        });

	        // Mark existing Gallery images as selected when the modal is opened
	        wp.media.frames.plwl.on( 'open', function() {

	        	// Get any previously selected images
	            var selection = wp.media.frames.plwl.state().get( 'selection' );
	            selection.reset();

	            // Get images that already exist in the gallery, and select each one in the modal
	            wp.TeamBuilderShowcase.Items.each( function( item ) {
	            	var image = wp.media.attachment( item.get( 'id' ) );
	                selection.add( image ? [ image ] : [] );
	            });

	            selection.single( selection.last() );

	        } );

			wp.media.frames.plwl.on( 'close', function() {

				wp.media.frames.plwl.trigger( 'plwl:hide-error', {'message' : teamBuilderShowcaseHelper.strings.limitExceeded } );

			} );
	        

	        // Insert into Gallery Button Clicked
	        wp.media.frames.plwl.on( 'insert', function( selection ) {

	            // Get state
	            var state = wp.media.frames.plwl.state();
	            var oldItemsCollection = wp.TeamBuilderShowcase.Items;

	            plwl.Items = new plwl.items['collection']();

	            // Iterate through selected images, building an images array
	            selection.each( function( attachment ) {
	            	var attachmentAtts = attachment.toJSON(),
	            		currentModel = oldItemsCollection.get( attachmentAtts['id'] );

	            	if ( currentModel ) {
	            		wp.TeamBuilderShowcase.Items.addItem( currentModel );
	            		oldItemsCollection.remove( currentModel );
	            	}else{
	            		plwlGalleryObject.generateSingleImage( attachmentAtts );
	            	}
	            }, this );

	            while ( model = oldItemsCollection.first() ) {
				  model.delete();
				}

	        } );

	        // Open WordPress Media Gallery
	        $( '#plwl-wp-gallery' ).click( function( e ){
	        	e.preventDefault();
	        	// set wp-settings-1 cookie to browse and not upload
				setUserSetting( 'libraryContent', 'browse' );
				// check if modal exists ( already introduced in page )
				var media_modal = $('body .media-modal');
				if(media_modal.length > 0 ){
					// click on item browse so we can activate it and look at item browsing
					media_modal.find('#menu-item-browse').click();
				}
				wp.media.frames.plwl.open();
	        });

		},

		// Uploader Events
		// Files Added for Uploading - show progress bar
		filesadded: function( up, files ){

			var plwlGalleryObject = this;

			// Hide any existing errors
            plwlGalleryObject.errorContainer.html( '' );

			// Get the number of files to be uploaded
            plwlGalleryObject.plwl_files_count = files.length;

            // Set the status text, to tell the user what's happening
            $( '.plwl-upload-numbers .plwl-current', plwlGalleryObject.containerUploader ).text( '1' );
            $( '.plwl-upload-numbers .plwl-total', plwlGalleryObject.containerUploader ).text( plwlGalleryObject.plwl_files_count );

            // Show progress bar
            plwlGalleryObject.containerUploader.addClass( 'show-progress' );

		},

		// File Uploading - update progress bar
		fileuploading: function( up, file ) {

			var plwlGalleryObject = this;

			// Update the status text
            $( '.plwl-upload-numbers .plwl-current', plwlGalleryObject.containerUploader ).text( ( plwlGalleryObject.plwl_files_count - up.total.queued ) + 1 );

            // Update the progress bar
            $( '.plwl-progress-bar-inner', plwlGalleryObject.progressBar ).css({ 'width': up.total.percent + '%' });

		},

		// File Uploaded - add images to the screen
		fileupload: function( up, file, info ){

			var plwlGalleryObject = this;
			var response = JSON.parse( info.response );
			plwlGalleryObject.generateSingleImage( response['data'] );

		},

		// Files Uploaded - hide progress bar
		filesuploaded: function() {

			var plwlGalleryObject = this;

			setTimeout( function() {
                plwlGalleryObject.containerUploader.removeClass( 'show-progress' );
            }, 1000 );

		},

		show: function() {
			var $el = $( '#plwl-dropzone-container' ).show();

			// Ensure that the animation is triggered by waiting until
			// the transparent element is painted into the DOM.
			_.defer( function() {
				$el.css({ opacity: 1 });
			});
		},

		hide: function() {
			var $el = $( '#plwl-dropzone-container' ).css({ opacity: 0 });

			wp.media.transition( $el ).done( function() {
				// Transition end events are subject to race conditions.
				// Make sure that the value is set as intended.
				if ( '0' === $el.css('opacity') ) {
					$el.hide();
				}
			});

			// https://core.trac.wordpress.org/ticket/27341
			_.delay( function() {
				if ( '0' === $el.css('opacity') && $el.is(':visible') ) {
					$el.hide();
				}
			}, 500 );
		},

		generateSingleImage: function( attachment ){
			var data = { halign: 'center', valign: 'middle', fburl: '', twturl: '', lnkdnurl: '', instanurl: '', emailurl: '', link: '', target: '', togglelightbox: ''}

			data['full']      = attachment['sizes']['full']['url'];
			if ( "undefined" != typeof attachment['sizes']['large'] ) {
				data['thumbnail'] = attachment['sizes']['large']['url'];
			}else{
				data['thumbnail'] = data['full'];
			}

			data['id']          = attachment['id'];
			data['alt']         = attachment['alt'];
			data['orientation'] = attachment['orientation'];
			data['title']       = attachment['title'];
			data['description'] = attachment['caption'];

			new plwl.items['model']( data );
		}

	});

    plwl.upload = {
        'toolbar' : TeamBuilderShowcaseToolbar,
        'attachmentsbrowser' : TeamBuilderShowcaseAttachmentsBrowser,
        'frame' : TeamBuilderShowcaseFrame,
        'selection' : TeamBuilderShowcaseSelection,
        'library' : TeamBuilderShowcaseLibrary,
        'errorview' : TeamBuilderShowcaseError,
        'uploadHandler' : uploadHandler
    };

}( jQuery, wp.TeamBuilderShowcase ))