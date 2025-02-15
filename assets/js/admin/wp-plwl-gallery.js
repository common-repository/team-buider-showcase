wp.TeamBuilderShowcase = 'undefined' === typeof( wp.TeamBuilderShowcase ) ? {} : wp.TeamBuilderShowcase;

(function( $, plwl ){

    var plwlGalleryResizer = Backbone.Model.extend({
    	defaults: {
            'columns': 12,
            'gutter': 10,
            'containerSize': false,
            'size': false,
        },

        initialize: function( args ){
            var resizer = this;

            this.set( 'containerSize', jQuery( '#plwl-uploader-container .plwl-uploader-inline-content' ).width() );

            // Get options
            this.set( 'gutter', parseInt( wp.TeamBuilderShowcase.Settings.get('gutter') ) );

        	// calculate block size.
        	this.generateSize();

            // Listen to column and gutter change
            // this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:columns', this.changeColumns );
            this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:gutter', this.changeGutter );

            // Listen to window resize
            jQuery( window ).on( 'resize', $.proxy( this.windowResize, this ) );
           /* new TeamBuilderShowcaseResizeSensor( jQuery( '#plwl-uploader-container .plwl-uploader-inline-content' ), function() {
                resizer.windowResize();
            });*/
        },
        
        generateSize: function(){
        	var columns = this.get( 'columns' ),
        		gutter = this.get( 'gutter' ),
        		containerWidth = this.get( 'containerSize' ),
        		size;

        	/* 
        	   We will calculate the size ( width and height, because every item is a square ) of an item.
    		   The formula is : from the container size we will subtract gutter * number of columns and then we will dived by number of columns
        	 */
        	size = Math.floor( ( containerWidth - ( gutter * ( columns - 1 ) ) ) / columns );
        	this.set( 'size', size );
        },
        /* 
           Here we will calculate the new size of the item.
           This will be called after resize event, that means the item is resized and we need to check it.
           currentSize is the new size of the item after we resized it.
         */
        calculateSize: function( currentSize ){
        	var size = this.get( 'size' ),
        		columns = Math.round( currentSize / size ),
        		gutter = this.get( 'gutter' ),
                containerColumns = this.get( 'columns' ),
        		correctSize;

            if ( columns > containerColumns ) {
                columns = containerColumns;
            }

        	correctSize = size * columns + ( gutter * ( columns - 1 ) );
        	return correctSize;
        },

        // Get columns from width/height
        getSizeColumns: function( currentSize ){
            var size = this.get( 'size' );
            return Math.round( currentSize / size );
        },

        windowResize: function() {
            var currentSize = this.get( 'containerSize' ),
                newSize = jQuery( '#plwl-uploader-container .plwl-uploader-inline-content' ).width();

            // Check if container size have been changed.
            if ( currentSize == newSize ) {
                return;
            }

            // Change Container Width
            this.set( 'containerSize', newSize );

            // Resize Items
            this.resizeItems();

        },

        resizeItems: function(){

            // Generate new sizes.
            this.generateSize();

            if ( 'undefined' != typeof wp.TeamBuilderShowcase.Items && wp.TeamBuilderShowcase.Items.length > 0 ) {

                // Resize all items when gutter or columns have changed.
                wp.TeamBuilderShowcase.Items.each( function( item ){
                    item.resize();
                });

            }

            if ( 'custom-grid' == plwl.Settings.get( 'type' ) ) {
                // Change packary columnWidth & columnHeight
                wp.TeamBuilderShowcase.GalleryView.setPackaryOption( 'columnWidth', this.get( 'size' ) );
                wp.TeamBuilderShowcase.GalleryView.setPackaryOption( 'rowHeight', this.get( 'size' ) );

                // Update Grid
                wp.TeamBuilderShowcase.GalleryView.setPackaryOption( 'gutter', parseInt( this.get( 'gutter' ) ) );

                // Reset Packary
                wp.TeamBuilderShowcase.GalleryView.resetPackary();
            }

        },

        changeColumns: function( model, value ){
            this.set( 'columns', value );

            // Resize all gallery items
            this.resizeItems();

        },

        changeGutter: function( model, value ){
            this.set( 'gutter', parseInt( value ) );

            // Resize all gallery items
            this.resizeItems();

        }

    });

    var plwlGalleryView = Backbone.View.extend({

    	isSortable : false,
    	isResizeble: false,
        refreshTimeout: false,
        updateIndexTimeout: false,
        backboneInstance : this,

    	initialize: function( args ) {

    		// This is the container where the gallery items are.
    		this.container = this.$el.find( '.plwl-uploader-inline-content' );

            // Helper Grid container
            this.helperGridContainer = this.$el.parent().find( '.plwl-helper-guidelines-container' );
            this.helperGrid = this.$el.find( '#plwl-grid' );

            // Listen to grid toggle
            this.helperGridContainer.on( 'change', 'input', $.proxy( this.updateSettings, this ) );

    		// Listent when gallery type is changing.
        	this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:type', this.checkSettingsType );

        	// Enable current gallery type
        	this.checkGalleryType( wp.TeamBuilderShowcase.Settings.get( 'type' ) );

        },

        updateSettings: function( event ) {
            var value,
                setting = event.target.dataset.setting;

            value = event.target.checked ? 1 : 0;

            if ( value ) {
                this.helperGrid.hide();
            }else{
                this.helperGrid.show();
            }

        },

        checkSettingsType: function( model, value ) {
        	this.checkGalleryType( value );
        },

        checkGalleryType: function( type ) {

            if ( 'creative-gallery' == type || 'grid' == type) {

            	// If resizeble is enable we will destroy it
            	if ( this.isResizeble ) {
            		this.disableResizeble();
            	}

            	// If sortable is not enabled, we will initialize it.
            	if ( ! this.isSortable ) {
            		this.enableSortable();
            	}

                this.helperGridContainer.addClass('plwl-guidelines-display');
                this.helperGridContainer.find( '.plwl-helper-guidelines-wrapper' ).hide();
                this.container.removeClass( 'plwl-custom-grid' ).addClass( 'plwl-creative-gallery' );

            }else if ( 'custom-grid' == type ) {

            	// If sortable is enable we will destroy it
            	if ( this.isSortable ) {
            		this.disableSortable();
            	}

            	// If resizeble is not enabled, we will initialize it.
            	if ( ! this.isResizeble ) {
            		this.enableResizeble();
            	}

                this.helperGridContainer.find( '.plwl-helper-guidelines-wrapper' ).show();
                this.helperGridContainer.removeClass('plwl-guidelines-display');

                this.container.removeClass( 'plwl-creative-gallery' ).addClass( 'plwl-custom-grid' );

            } else {
                jQuery( document ).trigger( 'plwl_gallery_type_check', [ this, this.backboneInstance, type ] );
            }
        },

        enableSortable: function() {
            var galleryView = this;

        	this.isSortable = true;
        	this.container.sortable( {
    	        items: '.plwl-single-image',
    	        cursor: 'move',
    	        forcePlaceholderSize: true,
    	        placeholder: 'plwl-single-image-placeholder',
                stop: function( event, ui ) {
                    var itemsIDs = galleryView.container.sortable( 'toArray' );
                    itemsIDs.forEach( function( itemID, i ) {
                        var id = "#" + itemID;
                        $( id ).trigger( 'plwl:updateIndex', { 'index': i } );
                    });
                }
    	    } );
        },

        disableSortable: function() {
        	this.isSortable = false;
        	this.container.sortable( 'destroy' );
        },

        enableResizeble: function() {

        	this.isResizeble = true;
            this.$el.addClass( 'plwl-resizer-enabled' );

            if ( 'undefined' == typeof wp.TeamBuilderShowcase.Resizer ) {
                wp.TeamBuilderShowcase.Resizer = new wp.TeamBuilderShowcase.previewer['resizer']({ 'galleryView': this });
            }

        	this.container.packery({
        		itemSelector: '.plwl-single-image',
                gutter: parseInt( wp.TeamBuilderShowcase.Resizer.get( 'gutter' ) ),
                columnWidth: wp.TeamBuilderShowcase.Resizer.get( 'size' ),
                rowHeight: wp.TeamBuilderShowcase.Resizer.get( 'size' ),
    		});

            this.container.on( 'layoutComplete', this.updateItemsIndex );
            this.container.on( 'dragItemPositioned', this.updateItemsIndex );
        },

        disableResizeble: function() {
    		this.isResizeble = false;
            this.$el.removeClass( 'plwl-resizer-enabled' );
            this.container.packery( 'destroy' );
        },

        bindDraggabillyEvents: function( item ){
        	if ( this.isResizeble ) {
        		this.container.packery( 'bindUIDraggableEvents', item );
        	}
        },

        resetPackary: function() {
            var view = this;

            if ( this.refreshTimeout ) {
                clearTimeout( this.refreshTimeout );
            }

            this.refreshTimeout = setTimeout(function () {        
                view.container.packery();
            }, 200);

        },

        updateItemsIndex: function(){

            var container = this;

            if ( this.updateIndexTimeout ) {
                clearTimeout( this.updateIndexTimeout );
            }
            
            this.updateIndexTimeout = setTimeout( function() {
                var items = $(container).packery('getItemElements');
                $( items ).each( function( i, itemElem ) {
                    $( itemElem ).trigger( 'plwl:updateIndex', { 'index': i } );
                });
            }, 200);

        },

        setPackaryOption: function( option, value ){

            var packaryOptions = this.container.data('packery');
            if ( 'undefined' != typeof packaryOptions ) {
                packaryOptions.options[ option ] = value;
            }

        },

    });

    var plwlGalleryGrid = Backbone.View.extend({

        containerHeight: 0,
        currentRows: 0,
        updateGridTimeout: false,

        initialize: function( args ) {
            var view = this;

            this.galleryView = args.galleryView;
            if ( 'undefined' == typeof wp.TeamBuilderShowcase.Resizer ) {
                wp.TeamBuilderShowcase.Resizer = new wp.TeamBuilderShowcase.previewer['resizer']({ 'galleryView': this.galleryView });
            }
            
            this.containerHeight = this.galleryView.container.height();

            // Listent when gallery type is changing.
            this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:type', this.checkSettingsType );

            // Listent when gallery gutter is changing.
            this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:gutter', this.changeGutter );

            // Listen when column width is changing
            this.listenTo( wp.TeamBuilderShowcase.Resizer, 'change:size', this.updateGrid );

            // On layout complete
            this.galleryView.container.on( 'layoutComplete', function( event ){
                view.updateGrid();
            });

            // Enable current gallery type
            this.checkGalleryType( wp.TeamBuilderShowcase.Settings.get( 'type' ) );

        },

        checkSettingsType: function( model, value ) {
            this.checkGalleryType( value );
        },

        checkGalleryType: function( type ) {
            if ( 'creative-gallery' == type || 'grid' == type ) {
                this.$el.hide();
            }else if ( 'custom-grid' == type ) {
                
                // Generate grid
                this.generateGrid();
            }
        },

        generateGrid: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.TeamBuilderShowcase.Resizer.get( 'size' ),
                gutter = wp.TeamBuilderShowcase.Resizer.get( 'gutter' ),
                neededItems = 0,
                neededContainerHeight = 0,
                containerHeight = 0,
                minContainerHeight = 0,
                parentHeight = this.$el.parent().height();

            containerHeight = view.$el.height();
            minContainerHeight = ( columnWidth + gutter ) * 3 - gutter;

            if ( containerHeight < minContainerHeight ) {
                containerHeight = minContainerHeight;
            }

            neededRows = Math.round( ( containerHeight + gutter ) / ( columnWidth + gutter ) ) + 1;
            neededContainerHeight = ( neededRows ) * ( columnWidth + gutter ) - gutter;

            while( containerHeight < neededContainerHeight ) {
                neededContainerHeight = neededContainerHeight - ( columnWidth + gutter );
            }

            this.$el.height( neededContainerHeight );
            $( '#plwl-uploader-container' ).css( 'min-height', minContainerHeight + 'px' );
            if ( neededContainerHeight > parentHeight ) {
                this.$el.parent().height( neededContainerHeight );
            }

            if ( neededRows > this.currentRows ) {

                neededItems = ( neededRows - this.currentRows ) * 12;
                this.currentRows = neededRows;

                for ( var i = 1; i <= neededItems; i++ ) {
                    this.$el.append( '<div class="plwl-grid-item"></div>' );
                }

                this.$el.find( '.plwl-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

            }

        },

        updateGrid: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.TeamBuilderShowcase.Resizer.get( 'size' ),
                gutter = wp.TeamBuilderShowcase.Resizer.get( 'gutter' ),
                neededItems = 0,
                neededContainerHeight = 0,
                containerHeight = 0,
                packery = view.galleryView.container.data('packery'),
                parentHeight = this.$el.parent().height();

            if ( 'undefined' == typeof packery ) {
                return;
            }

            containerHeight = packery.maxY - packery.gutter;
            minContainerHeight = ( columnWidth + gutter ) * 3 - gutter;

            if ( containerHeight < minContainerHeight ) {
                containerHeight = minContainerHeight;
            }

            neededRows = Math.round( ( containerHeight + gutter ) / ( columnWidth + gutter ) ) + 1;
            neededContainerHeight = ( neededRows ) * ( columnWidth + gutter ) - gutter;

            while( containerHeight < neededContainerHeight ) {
                neededContainerHeight = neededContainerHeight - ( columnWidth + gutter );
            }

            this.$el.height( neededContainerHeight );
            $( '#plwl-uploader-container' ).css( 'min-height', minContainerHeight + 'px' );
            if ( neededContainerHeight > parentHeight ) {
                this.$el.parent().height( neededContainerHeight );
            }

            neededItems = ( neededRows - this.currentRows ) * 12;
            this.currentRows = neededRows;

            for ( var i = 1; i <= neededItems; i++ ) {
                this.$el.append( '<div class="plwl-grid-item"></div>' );
            }

            this.$el.find( '.plwl-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

        },

        changeGutter: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.TeamBuilderShowcase.Resizer.get( 'size' ),
                gutter = wp.TeamBuilderShowcase.Resizer.get( 'gutter' ),
                neededItems = 0,
                neededContainerHeight = 0,
                containerHeight = 0,
                packery = view.galleryView.container.data('packery');

            if ( 'undefined' == typeof packery ) {
                return;
            }

            containerHeight = packery.maxY - packery.gutter;

            if ( containerHeight < 300 ) {
                containerHeight = 300;
            }

            neededRows = Math.round( ( containerHeight + gutter ) / ( columnWidth + gutter ) ) + 1;
            neededContainerHeight = ( neededRows ) * ( columnWidth + gutter ) - gutter;

            while( containerHeight < neededContainerHeight ) {
                neededContainerHeight = neededContainerHeight - ( columnWidth + gutter );
            }

            this.$el.height( neededContainerHeight );

            this.$el.find( '.plwl-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

        }

    });

    plwl.previewer = {
        'resizer' : plwlGalleryResizer,
        'view' : plwlGalleryView
    }

}( jQuery, wp.TeamBuilderShowcase ))

