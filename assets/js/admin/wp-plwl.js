wp.TeamBuilderShowcase = 'undefined' === typeof( wp.TeamBuilderShowcase ) ? {} : wp.TeamBuilderShowcase;
wp.TeamBuilderShowcase.modalChildViews = 'undefined' === typeof( wp.TeamBuilderShowcase.modalChildViews ) ? [] : wp.TeamBuilderShowcase.modalChildViews;
wp.TeamBuilderShowcase.previewer = 'undefined' === typeof( wp.TeamBuilderShowcase.previewer ) ? {} : wp.TeamBuilderShowcase.previewer;
wp.TeamBuilderShowcase.modal = 'undefined' === typeof( wp.TeamBuilderShowcase.modal ) ? {} : wp.TeamBuilderShowcase.modal;
wp.TeamBuilderShowcase.items = 'undefined' === typeof( wp.TeamBuilderShowcase.items ) ? {} : wp.TeamBuilderShowcase.items;
wp.TeamBuilderShowcase.upload = 'undefined' === typeof( wp.TeamBuilderShowcase.upload ) ? {} : wp.TeamBuilderShowcase.upload;

(function( $ ){

	// Here we will have all gallery's items.
	if(wp.TeamBuilderShowcase.items['collection']){
		wp.TeamBuilderShowcase.Items = new wp.TeamBuilderShowcase.items['collection']();
	}

	// Settings related objects.
	wp.TeamBuilderShowcase.Settings = new wp.TeamBuilderShowcase.settings['model']( teamBuilderShowcaseHelper.settings );

	// TeamBuilderShowcase conditions
	wp.TeamBuilderShowcase.Conditions = new plwlTeamConditions();

	// Initiate TeamBuilderShowcase Resizer
	if ( 'undefined' == typeof wp.TeamBuilderShowcase.Resizer &&  wp.TeamBuilderShowcase.previewer['resizer']) {
		wp.TeamBuilderShowcase.Resizer = new wp.TeamBuilderShowcase.previewer['resizer']();
	}
	
	// Initiate Gallery View
	if(wp.TeamBuilderShowcase.previewer['view']){
		wp.TeamBuilderShowcase.GalleryView = new wp.TeamBuilderShowcase.previewer['view']({
			'el' : $( '#plwl-uploader-container' ),
		});
	}

	// TeamBuilderShowcase edit item modal.
	wp.TeamBuilderShowcase.EditModal = new wp.TeamBuilderShowcase.modal['model']({
		'childViews' : wp.TeamBuilderShowcase.modalChildViews
	});

	// Here we will add items for the gallery to collection.
	if ( 'undefined' !== typeof teamBuilderShowcaseHelper.items ) {
		$.each( teamBuilderShowcaseHelper.items, function( index, image ){
			var imageModel = new wp.TeamBuilderShowcase.items['model']( image );
		});
	}

	// Initiate TeamBuilderShowcase Gallery Upload
	if(wp.TeamBuilderShowcase.upload['uploadHandler']){
		new wp.TeamBuilderShowcase.upload['uploadHandler']();
	}


	// Copy shortcode functionality
    $('.copy-plwl-shortcode').click(function (e) {
        e.preventDefault();
        var gallery_shortcode = $(this).parent().find('input');
        gallery_shortcode.focus();
        gallery_shortcode.select();
        document.execCommand("copy");
        $(this).next('span').text('Shortcode copied');
    });

	jQuery( document ).on( "keydown.autocomplete", '.plwl-link input[name="link"]', function() {

		var url = teamBuilderShowcaseHelper.ajax_url + "?action=plwl_autocomplete&nonce="+teamBuilderShowcaseHelper._wpnonce;
		jQuery(this).autocomplete({
			source: url,
			delay: 500,
			minLength: 3
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )  
				.data( "item.autocomplete", item )  
				.append( `
				<div class="plwl-autocomplete-results">
				<p> ${item.label} </p> <span> <code> ${item.type} </code> </span>
				<p style="color: #555; font-size: 11px;"> ${item.value} </p>
				</div>
				` )  
				.appendTo( ul );  
		};  
	} );


	/*$('#plwl-image-loaded-effects ').on('click','#test-scaling-preview',function (e) {
		e.preventDefault();
		var val     = $('input[data-setting="loadedScale"]').val();
		var targets = $('#plwl-image-loaded-effects .plwl-item');
		targets.css('transform', 'scale(' + val / 100 + ')');
		setTimeout(function () {
			targets.removeAttr('style')
		}, 600)
	});*/

	// Dismiss notice
	$('body').on('click','#plwl-lightbox-upgrade .notice-dismiss',function (e) {

		e.preventDefault();
		var notice = $(this).parent();

		var data = {
			'action': 'plwl_lbu_notice',
			'nonce' : teamBuilderShowcaseHelper._wpnonce
		};

		$.post(teamBuilderShowcaseHelper.ajax_url, data, function (response) {
			// Redirect to plugins page
			notice.remove();
		});
	});

	// Save on CTRL/Meta Key + S
	$( document ).keydown( function ( e ) {
		if ( ( e.keyCode === 115 || e.keyCode === 83 ) && ( e.ctrlKey || e.metaKey ) && !( e.altKey ) ) {
			e.preventDefault();
			$( '#publish' ).click();
			return false;
		}
	} );



	/*$( 'tr[data-container="emailMessage"] td .plwl-placeholders' ).on('click', 'span', function(){
		let input = $( 'textarea[data-setting="emailMessage"]');
		let placeholder = $(this).attr('data-placeholder') ;
		input.val( function( index, value ){
			value += placeholder;
			return value;
		})
	})*/

	/** Remember last tab on update */
	// search for plwl in hash so we won't do the function on every hash
	if( window.location.hash.length != 0 && window.location.hash.indexOf('plwl') ) {
		var plwlTabHash = window.location.hash.split( '#!' )[1];
		$( '.plwl-tabs,.plwl-tabs-content' ).find( '.active-tab' ).removeClass( 'active-tab' );
		$( '.plwl-tabs' ).find( '.' + plwlTabHash ).addClass( 'active-tab' );
		$( '#' + plwlTabHash ).addClass( 'active-tab').trigger('plwl-current-tab');
		var postAction = $( "#post" ).attr('action');
		if( postAction ) {
			postAction = postAction.split( '#' )[0];
			$( '#post' ).attr( 'action', postAction + window.location.hash );
		}
	}

	/*var inputs = $('#plwl-hover-effect .plwl-hover-effect-item input[type="radio"]');
	$( '#plwl-hover-effect .plwl-hover-effect-item' ).on( 'click',function () {
		let input = $( this ).find( 'input[type="radio"]' );

		if ( input.length > 0 ) {
			input.prop( "checked", false );
			input.prop( "checked", true );
		}
	} );*/

})(jQuery);