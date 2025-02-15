wp.TeamBuilderShowcase = 'undefined' === typeof( wp.TeamBuilderShowcase ) ? {} : wp.TeamBuilderShowcase;

(function( $, plwl ){

    var plwlSettings = Backbone.Model.extend({
    	initialize: function( args ){
            var model = this;
            $.each( args, function( att, value ){
                model.set( att, value );
            });

      		var view = new plwl.settings['view']({
      			model: this,
      			el: $( '#plwl-team-settings' )
      		});

      		this.set( 'view', view );

        },
    });

    var plwlSettingsView = Backbone.View.extend({

    	events: {
    		// Tabs specific events
    		'click .plwl-tab':     'changeTab',
            'click .plwl-tab > *': 'changeTabFromChild',
            //'click .plwl_settings_accordion': 'changeAcordeon',

    		// Settings specific events
            'keyup input':         'updateModel',
            'keyup textarea':      'updateModel',
            'change input':        'updateModel',
            'change textarea':     'updateModel',
            'blur textarea':       'updateModel',
            'change select':       'updateModel',
        },

        initialize: function( args ) {
        	this.initializeLite();
        },

        initializeLite: function(){

            this.tabs          = this.$el.find( '.plwl-tabs .plwl-tab' );
            this.tabContainers = this.$el.find( '.plwl-tabs-content > div' );
            this.sliders       = this.$el.find( '.plwl-ui-slider' );
            this.colorPickers  = this.$el.find( '.plwl-color' );
            this.customEditors = this.$el.find( '.plwl-code-editor' );

            // initialize 3rd party scripts
            this.initSliders();
            this.initColorPickers();
            this.initCustomCSS();
            this.expandGalleryContainer();

        },

        updateModel: function( event ) {
        	var value, setting;

        	// Check if the target has a data-field. If not, it's not a model value we want to store
            if ( undefined === event.target.dataset.setting ) {
                return;
            }

            setting = event.target.dataset.setting;

            // Update the model's value, depending on the input type
            if ( event.target.type == 'checkbox' ) {
                value = ( event.target.checked ? event.target.value : 0 );
            } else {
                value = event.target.value;
            }

            // Update the model
            this.model.set( setting, value );

        },

        changeTab: function ( event ) {

        	var currentTab = jQuery( event.target ).data( 'tab' );

            if ( this.tabContainers.filter( '#' + currentTab ).length < 1 ) {
                return;
            }
    		this.tabs.removeClass( 'active-tab' );
    		this.tabContainers.removeClass( 'active-tab' );
    		jQuery( event.target ).addClass( 'active-tab' );
    		this.tabContainers.filter( '#' + currentTab ).addClass( 'active-tab' ).trigger( 'plwl-current-tab' );

            window.location.hash = '#!' + currentTab;

            var postAction = $( "#post" ).attr( 'action' );
            if( postAction ) {
                postAction = postAction.split( '#' )[0];
                $( '#post' ).attr( 'action', postAction + window.location.hash );
            }

            var opts = {
                url:      teamBuilderShowcaseHelper.ajax_url,
                type:     'post',
                async:    true,
                cache:    false,
                dataType: 'json',
                data:     {
                    action: 'plwl_remember_tab',
                    tab:     currentTab,
                    id: $('#post_ID').val(),
                    nonce:  teamBuilderShowcaseHelper._wpnonce,
                }
            };

            $.ajax(opts);

        },

        changeTabFromChild: function ( event ) {

            var currentTab = jQuery( event.target ).parent().data( 'tab' );

            if ( this.tabContainers.filter( '#' + currentTab ).length < 1 ) {
                return;
            }

            this.tabs.removeClass( 'active-tab' );
            this.tabContainers.removeClass( 'active-tab' );
            jQuery( event.target ).parent().addClass( 'active-tab' );
            this.tabContainers.filter( '#' + currentTab ).addClass( 'active-tab' );

        },

        changeAcordeon: function ( event ) {
            var row = jQuery( event.target ).parents( 'tr' ),
                settingID = row.data( 'container' ),
                children  = row.data( 'children' ),
                value = wp.TeamBuilderShowcase.Settings.get( settingID ),
                parentval = 1;

            row.toggleClass( 'plwl_accordion_open' );
           
            if( row.hasClass( 'plwl_accordion_reversed' ) ){
                if( 0 == value ){ value = 1; }else{ value = 0; }
            }

            if( row.data( 'parent' ) ){
                //recursively check for parents
                parentval = this.chechParents(row[0], parentval);
            }

            jQuery.each(children, function(index, item) {

                var child = jQuery('[data-container="'+item+'"]');
                    
                if ( 1 == value && 1 == parentval ) {
                    child.css('opacity', '1');
                    child.find('input, textarea, select, button').removeAttr('disabled');
                }else{
                    child.css('opacity', '0.5');
                    child.find('input, textarea, select, button').attr('disabled', 'disabled');
                    
                }

                if ( row.hasClass( 'plwl_accordion_open' ) ) {
                    child.show();
                }else{
                    child.hide();
                }

            });

            let customEvent = 'toggleAccordeon:'+settingID;
            this.model.trigger( 'toggleAccordeon' );
            this.model.trigger( customEvent );

            
        },

        chechParents: function ( parent, parentval ) {

            if( jQuery(parent).data( 'parent' ) && 1 == parentval ){

                if( 1 == wp.TeamBuilderShowcase.Settings.get( jQuery(parent).data( 'parent' ) ) ){
                    
                    parentval = this.chechParents( jQuery('[data-container="'+jQuery(parent).data( 'parent' ) +'"]'), parentval );
                }else{

                    parentval = 0;
                    return 0;
                   
                }
                
            }

            return parentval;

            
        },

        initSliders: function() {

        	if ( this.sliders.length > 0 ) {
    			this.sliders.each( function( $index, $slider ) {
                    var input = jQuery( $slider ).parent().find( '.plwl-ui-slider-input' ),
                        max = input.data( 'max' ),
                        min = input.data( 'min' ),
                        step = input.data( 'step' ),
                        value = parseInt( input.val(), 10 );

                    jQuery( $slider ).slider({
                        value: value,
                        min: min,
                        max: max,
                        step: step,
                        range: 'min',
                        slide: function( event, ui ) {
                            input.val( ui.value ).trigger( 'change' );
                        }
                    });


                    input.keyup(function() {
                        jQuery( $slider ).slider("value" , input.val())
                    });

                });
    		}

        },

        initColorPickers: function() {
        	if ( this.colorPickers.length > 0 ) {
                this.colorPickers.each( function( $index, colorPicker ) {
                	//@todo: we need to find a solution to trigger a change event on input.
                    jQuery( colorPicker ).wpColorPicker();
                });
            }
        },

        initCustomCSS: function() {

            // If codeEditor is undefined we should not try to recreate the Custom CSS editor
            // Will be left as a simple textarea
            if ( undefined !== wp.codeEditor ) {
                var editorSettings =  wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
                if ( this.customEditors.length > 0 ) {
                    this.customEditors.each( function( $index, customEditorContainer ) {
                        var syntax          = $( customEditorContainer ).data( 'syntax' ),
                            id              = '#' + $( customEditorContainer ).find( '.plwl-custom-editor-field' ).attr( 'id' ),
                            currentSettings = _.extend(
                                {},
                                editorSettings.codemirror,
                                {
                                    mode: syntax,
                                }
                            );

                        if( undefined !== wp.codeEditor ) {
                            var editor =  wp.codeEditor.initialize( $( id ), currentSettings );
                            $( customEditorContainer ).parents( '.plwl-tab-content' ).on( 'plwl-current-tab',function(){
                                editor.codemirror.refresh();
                            });
                        }

                    });
                }
            }
        },
        expandGalleryContainer: function () {
            $( '#plwl-preview-gallery' ).removeClass( 'closed' );
        }

    });

    plwl.settings = {
        'model' : plwlSettings,
        'view' : plwlSettingsView
    };

}( jQuery, wp.TeamBuilderShowcase ))

