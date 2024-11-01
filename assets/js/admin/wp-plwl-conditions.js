wp.TeamBuilderShowcase = 'undefined' === typeof( wp.TeamBuilderShowcase ) ? {} : wp.TeamBuilderShowcase;

jQuery.fn.setting_state = function( el, state) {

	if( state == 'off'){
		this.css('opacity', '0.5');
		this.find('input, textarea, select, button').attr('disabled', 'disabled');
	}
	if( state == 'on'){
		this.css('opacity', '1');
		this.find('input, textarea, select, button').removeAttr('disabled');
	}
}; 



var plwlTeamConditions = Backbone.Model.extend({

	initialize: function( args ){

		var rows = jQuery('.plwl-team-settings-container tr[data-container]');
		var tabs = jQuery('.plwl-tabs .plwl-tab');
		this.set( 'rows', rows );
		this.set( 'tabs', tabs );
		var imageSizesInfo = jQuery('.plwl-team-settings-container tr[data-container="grid_image_size"] .plwl-team-imagesizes-infos .plwl-team-imagesize-info');

		this.set( 'imagesizes', imageSizesInfo );
		this.initEvents();
		this.initValues();

	},

	initEvents: function(){

		this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:designName', this.changedType );
		
		this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:lightbox', this.changedLightbox );
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:enableSocial', this.enableSocial );
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:enableEmail', this.enableEmail);
		this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:enable_responsive', this.changedResponsiveness );
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:hide_title', this.hideTitle);
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:hide_designation', this.hideDesignation);
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:hide_description', this.hideCaption);
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:slider_autoplay', this.autoplaySlider);
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:hide_arrow', this.hideArrow);
		//this.listenTo( wp.TeamBuilderShowcase.Settings, 'change:hide_bullets', this.hideBullets);
		this.listenTo(wp.TeamBuilderShowcase.Settings, 'change:grid_type', this.changedGridType);
		this.listenTo(wp.TeamBuilderShowcase.Settings, 'change:grid_image_size', this.changedGridImageSize);

		//this.listenTo(wp.TeamBuilderShowcase.Settings, 'toggleAccordeon:enableSocial', this.toggleSocial);

	},

	initValues: function(){

		this.changedType( false, wp.TeamBuilderShowcase.Settings.get( 'designName' ) );

		this.changedLightbox( false, wp.TeamBuilderShowcase.Settings.get( 'lightbox' ) );
		//this.enableSocial (false, wp.TeamBuilderShowcase.Settings.get('enableSocial') );
		//this.enableEmail( false, wp.TeamBuilderShowcase.Settings.get( 'enableEmail' ) );
		this.changedResponsiveness ( false, wp.TeamBuilderShowcase.Settings.get('enable_responsive') );
		//this.hideTitle ( false, wp.TeamBuilderShowcase.Settings.get( 'hide_title' ) );
		//this.hideDesignation ( false, wp.TeamBuilderShowcase.Settings.get( 'hide_designation' ) );
		//this.hideCaption ( false, wp.TeamBuilderShowcase.Settings.get( 'hide_description') );
		//this.autoplaySlider ( false, wp.TeamBuilderShowcase.Settings.get( 'slider_autoplay' ) );
		//this.hideArrow ( false, wp.TeamBuilderShowcase.Settings.get( 'hide_arrow' ) );
		//this.hideBullets ( false, wp.TeamBuilderShowcase.Settings.get( 'hide_bullets' ) );
		this.changedGridType(false, wp.TeamBuilderShowcase.Settings.get('grid_type'));
		this.changedGridImageSize(false, wp.TeamBuilderShowcase.Settings.get('grid_image_size'));

	},

	changedType: function( settings, value ){
		var rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );

		if(value == 'grid-design-01' || value == 'grid-design-02' || value == 'grid-design-03' || value == 'grid-design-04'){
			tabs.filter( '[data-tab="plwl-slider"]' ).hide();
		}else{
			tabs.filter( '[data-tab="plwl-slider"]' ).show();
		}

		if(value == 'grid-design-03' || value == 'slider-design-03'){
			rows.filter( '[data-container="teamOverlayColor"], [data-container="overlayTransparency"]' ).hide();
		}else{
			rows.filter( '[data-container="teamOverlayColor"], [data-container="overlayTransparency"]' ).show();
		}
		/*if ( 'custom-grid' == value ) {

			// Show Responsive tab
			tabs.filter( '[data-tab="plwl-responsive"]' ).show();
			
			rows.filter( '[data-container="columns"], [data-container="gutter"]' ).setting_state( this, 'on');

			rows.filter( '[data-container="width"], [data-container="height"], [data-container="randomFactor"], [data-container="shuffle"]' ).setting_state( this, 'off');
			rows.filter( '[data-container="width"], [data-container="height"], [data-container="randomFactor"], [data-container="shuffle"]' ).hide();

			rows.filter('[data-container="maxImagesCount"]').setting_state( this, 'on');

			// Rows for grid type
			rows.filter('[data-container="grid_type"], [data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_row_height"], [data-container="grid_justify_last_row"]').hide();
			rows.filter('[data-container="grid_type"], [data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_row_height"], [data-container="grid_justify_last_row"]').setting_state( this, 'off');
			
		}else if ( 'creative-gallery' == value ) {

			// Hide Responsive tab
			tabs.filter( '[data-tab="plwl-responsive"]' ).hide();

			rows.filter( '[data-container="columns"]' ).setting_state( this, 'off');

			rows.filter( '[data-container="width"], [data-container="height"], [data-container="randomFactor"], [data-container="shuffle"]' ).setting_state( this, 'on');
			rows.filter( '[data-container="width"], [data-container="height"], [data-container="randomFactor"], [data-container="shuffle"]' ).show();

			rows.filter('[data-container="height"],  [data-container="gutter"], [data-container="shuffle"], [data-container="showAllOnLightbox"],[data-container="maxImagesCount"]').setting_state( this, 'on');


			// Rows for grid type
			//rows.filter('[data-container="grid_type"], [data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_row_height"], [data-container="grid_justify_last_row"]').hide();
			//rows.filter('[data-container="grid_type"], [data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_row_height"], [data-container="grid_justify_last_row"]').setting_state( this, 'off');

			
		} else if('grid' == value){

			rows.filter('[data-container="grid_type"], [data-container="width"],[data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_row_height"], [data-container="grid_justify_last_row"], [data-container="gutter"],[data-container="maxImagesCount"]').show();
			rows.filter('[data-container="grid_type"], [data-container="width"],[data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_row_height"], [data-container="grid_justify_last_row"], [data-container="gutter"],[data-container="maxImagesCount"]').setting_state( this, 'on');

			rows.filter('[data-container="height"], [data-container="randomFactor"]').setting_state( this, 'off');
			rows.filter('[data-container="height"], [data-container="randomFactor"]').hide();

			tabs.filter( '[data-tab="plwl-responsive"]' ).show();

			this.changedGridType(false, wp.TeamBuilderShowcase.Settings.get('grid_type'));


		} else {

			rows.filter('[data-container="grid_type"],  [data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_row_height"], [data-container="grid_justify_last_row"]').setting_state( this, 'off');

			rows.filter('[data-container="randomFactor"]').show();
		}*/

		// Check image sizes
		this.changedGridImageSize(false, wp.TeamBuilderShowcase.Settings.get('grid_image_size'));

	},

	/*changedCursor: function( settings, value ) {
		var cursorBox = jQuery( '.plwl-effects-preview > div' );
		cursorBox.css( 'cursor', value);
	},*/

	changedLightbox: function( settings, value ){
		var rows         = this.get('rows'),
			tabs         = this.get('tabs'),
			link_options = ['no-link', 'direct', 'attachment-page'];

		if ( 'fancybox' == value ) {

			rows.filter('[data-container="show_navigation"]').setting_state( this, 'on');
			tabs.filter('[data-tab="plwl-exif"],[data-tab="plwl-zoom"]').setting_state( this, 'on');

		} else {

			rows.filter('[data-container="show_navigation"]').setting_state( this, 'off');
			tabs.filter('[data-tab="plwl-exif"],[data-tab="plwl-zoom"]').setting_state( this, 'off');

		}

	},

	/*changedEffect: function( settings, value ){

		var hoverBoxes = jQuery( '.plwl-effects-preview > div' );

		hoverBoxes.setting_state( this, 'off');
		hoverBoxes.filter( '.panel-' + value ).setting_state( this, 'on');

	},*/

	/*enableSocial: function( settings, value){

		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="enableSocial"]'),
            children  = currentRow.data( 'children' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 0 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 0 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });

		if ( 0 != value ) {

			currentRow.addClass( 'plwl_accordion_open' );

		}

		this.enableEmail(false, wp.TeamBuilderShowcase.Settings.get('enableEmail'));
	},*/

	/*toggleSocial: function(){

		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="enableSocial"]'),
			emailRow = rows.filter('[data-container="enableEmail"]'),
			children  = emailRow.data( 'children' );

		if ( ! currentRow.hasClass( 'plwl_accordion_open' ) ) {
			jQuery.each(children, function(index, item) {
				var child = jQuery('[data-container="'+item+'"]');
				child.hide();
			});

			if ( emailRow.hasClass( 'plwl_accordion_open' ) ) {
				emailRow.removeClass( 'plwl_accordion_open' )
			}

		}

	},*/

	/*enableEmail: function( settings, value ) {

		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="enableEmail"]'),
			parentrow = rows.filter('[data-container="enableSocial"]'),
            children  = currentRow.data( 'children' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( (0 == value || 0 == wp.TeamBuilderShowcase.Settings.get( 'enableSocial')) && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 0 == value || 0 == wp.TeamBuilderShowcase.Settings.get( 'enableSocial')){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });

		if ( 1 == value && 1 == wp.TeamBuilderShowcase.Settings.get( 'enableSocial') ) {
			currentRow.addClass( 'plwl_accordion_open' );
		}
	},*/

	changedResponsiveness: function( settings, value){
		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="enable_responsive"]'),
            children  = currentRow.data( 'children' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 0 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 0 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });

		if( 1 == value ) {
			currentRow.addClass( 'plwl_accordion_open' );
		}
	},

	/*hideTitle: function( settings, value ) {
		
		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="hide_title"]'),
            children  = currentRow.data( 'children' );

			currentRow.addClass( 'plwl_accordion_reversed' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 1 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 1 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });


		if( 1 != value ) {

			currentRow.addClass( 'plwl_accordion_open' );
		}
	},

	hideDesignation: function( settings, value ) {
		
		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="hide_designation"]'),
            children  = currentRow.data( 'children' );

			currentRow.addClass( 'plwl_accordion_reversed' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 1 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 1 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });


		if( 1 != value ) {

			currentRow.addClass( 'plwl_accordion_open' );
		}
	},

	hideCaption: function( settings, value ) {
		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="hide_description"]'),
            children  = currentRow.data( 'children' );

			currentRow.addClass( 'plwl_accordion_reversed' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 1 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 1 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });

		if( 1 != value ) {
			
			currentRow.addClass( 'plwl_accordion_open' );
		}
	},*/

	/*autoplaySlider: function( settings, value ) {
		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="slider_autoplay"]'),
            children  = currentRow.data( 'children' );

			currentRow.addClass( 'plwl_accordion_reversed' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 0 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 0 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });

		if( 0 != value ) {
			
			currentRow.addClass( 'plwl_accordion_open' );
		}
	},

	hideArrow: function( settings, value ) {
		
		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="hide_arrow"]'),
            children  = currentRow.data( 'children' );

			currentRow.addClass( 'plwl_accordion_reversed' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 1 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 1 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });


		if( 1 != value ) {

			currentRow.addClass( 'plwl_accordion_open' );
		}
	},

	hideBullets: function( settings, value ) {
		
		var rows = this.get( 'rows' ),
			currentRow = rows.filter('[data-container="hide_bullets"]'),
            children  = currentRow.data( 'children' );

			currentRow.addClass( 'plwl_accordion_reversed' );

        jQuery.each(children, function(index, item) {

            var child = jQuery('[data-container="'+item+'"]');

            if ( 1 == value && currentRow.hasClass( 'plwl_accordion_open' )) {
            	child.setting_state( this, 'off');
				child.show();
            }else if( 1 == value ){
				child.hide();
            }else{
				child.css('opacity', '1');
                child.find('input, textarea, select, button').removeAttr('disabled');
            	child.show();
			}

        });


		if( 1 != value ) {

			currentRow.addClass( 'plwl_accordion_open' );
		}
	},*/

	changedGridType: function (settings, value) {
		var rows = this.get( 'rows' ),
			tabs = this.get( 'tabs' );


		if ( 'grid' != wp.TeamBuilderShowcase.Settings.get('type') ) {
			return;
		}

		if( 'automatic' == value || '' == value) {
			rows.filter(' [data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_justify_last_row"], [data-container="gutter"]').setting_state( this, 'on');
			tabs.filter( '[data-tab="plwl-responsive"]' ).hide();

			
		} else {
			rows.filter(' [data-container="grid_row_height"], [data-container="grid_max_row_height"], [data-container="grid_justify_last_row"]').setting_state( this, 'off');
			rows.filter('[data-container="grid_type"],[data-container="gutter"]').setting_state( this, 'on');
			tabs.filter( '[data-tab="plwl-responsive"]' ).show();

		}

	},

	changedGridImageSize: function( settings, value ) {

		let rows = this.get( 'rows' ),
			imagesizes = this.get( 'imagesizes' );


		if ( 'custom-grid' == wp.TeamBuilderShowcase.Settings.get( 'type' ) ) {
			if ( 'custom' == value ) {
				rows.filter( '[data-container="grid_image_dimensions"], [data-container="grid_image_crop"]').hide();
				rows.filter( '[data-container="img_size"], [data-container="img_crop"]').show();
				rows.filter( '[data-container="img_size"], [data-container="img_crop"]').setting_state( this, 'on');
			}else{
				rows.filter( '[data-container="grid_image_dimensions"], [data-container="grid_image_crop"]').hide();
				rows.filter( '[data-container="img_size"], [data-container="img_crop"]').show();
				rows.filter( '[data-container="img_size"], [data-container="img_crop"]').setting_state( this, 'off');
			}
		}else{
			if( 'custom' == wp.TeamBuilderShowcase.Settings.get( 'grid_image_size' ) ){
				rows.filter( '[data-container="img_size"], [data-container="img_crop"]').hide();
				rows.filter( '[data-container="grid_image_dimensions"], [data-container="grid_image_crop"]').show();
				rows.filter( '[data-container="grid_image_dimensions"], [data-container="grid_image_crop"]').setting_state( this, 'on');
			}else{
				rows.filter( '[data-container="img_size"], [data-container="img_crop"]').hide();
				rows.filter( '[data-container="grid_image_dimensions"], [data-container="grid_image_crop"]').show();
				rows.filter( '[data-container="grid_image_dimensions"], [data-container="grid_image_crop"]').setting_state( this, 'off');
			}

		}

		var currentInfo = imagesizes.filter( '[data-size="' + value + '"]' );
		imagesizes.hide();
		if ( currentInfo.length > 0 ) {
			currentInfo.show();
		}
	},
});