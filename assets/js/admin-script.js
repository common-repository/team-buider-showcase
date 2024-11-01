jQuery(document).ready(function () {
   
    // select template code
    jQuery("#pw_popupdiv div.pw-template-thumbnail .pw-popum-select a").on('click', function (e) {
        e.preventDefault();
        jQuery('#pw_popupdiv div.pw-template-thumbnail').removeClass('pw_selected_template');
        jQuery(this).parents('div.pw-template-thumbnail').addClass('pw_selected_template');
    });

    jQuery(".pw_select_template").on('click', function (e) {
        e.preventDefault();
        var template_name = jQuery('#template_name').val();
        jQuery("#pw_popupdiv").dialog({
            title: pwlite_js.choose_plwl_template,
            dialogClass: 'pw_template_model',
            width: jQuery(window).width() - 100,
            height: jQuery(window).height() - 100,
            modal: true,
            draggable: false,
            resizable: false,
            buttons: [{
                    text: pwlite_js.set_plwl_template,
                    id: "btnSetPWTemplate",
                    click: function () {
                        var template_name = jQuery('#pw_popupdiv div.pw-template-thumbnail.pw_selected_template .pw-template-thumbnail-inner').children('img').attr('src');
                        if (typeof template_name === 'undefined' || template_name === null) {
                            jQuery("#pw_popupdiv").dialog('close');
                            return;
                        }

                        var template_value = jQuery('#pw_popupdiv div.pw-template-thumbnail.pw_selected_template .pw-template-thumbnail-inner').children('img').attr('data-value');

                        let designName = template_value.split("-");

                        for (var i = 0; i < designName.length; i++) {
                            designName[i] = designName[i].charAt(0).toUpperCase() + designName[i].slice(1);
                        }

                        const str2 = designName.join(" ");

                        document.getElementById("plwl-id").innerHTML = str2;

                        jQuery(".pw_selected_template_image > div").empty();
                        jQuery('#template_name').val(template_value);
                        jQuery(".pw_selected_template_image > div").append('<img src="' + template_name + '" alt="' + template_value.replace('_', '-') + ' Template" /><label id="pw_template_select_name">' + template_value.replace('_', '-') + ' Template</label>');
                        jQuery('.pw-setting-handle li').each(function () {
                            var hide = jQuery(this).attr('data-show');
                            if (hide == 'pwpagination') {
                                jQuery(this).removeClass('clickDisable');
                            }
                        });


                        if(template_value == 'grid-design-01' || template_value == 'grid-design-02' || template_value == 'grid-design-03' || template_value == 'grid-design-04'){
                            jQuery(".plwl-slider").hide();
                        }else{
                            jQuery(".plwl-slider").show();
                        }

                        if(template_value == 'grid-design-03' || template_value == 'slider-design-03'){
                            jQuery(".teamOverlayColor").hide();
                            jQuery(".overlayTransparency").hide();
                        }else{
                            jQuery(".teamOverlayColor").show();
                            jQuery(".overlayTransparency").show();
                        }


                        jQuery('.pw_selected_template').val(template_value);

                        if (jQuery('#bp-apply-default-style').is(":checked")) {
                            default_data(template_value);
                        }
                        pwAltBackground();
                        jQuery("#pw_popupdiv").dialog('close');

                    }
                },
                {text: pwlite_js.close,class: 'pw_template_close',click: function () {jQuery(this).dialog("close");},}
            ],
            open: function (event, ui) {
                var template_name = jQuery('#designName').val();
                jQuery('#pw_popupdiv .pw-template-thumbnail').removeClass('pw_selected_template');
                jQuery('#pw_popupdiv .pw-template-thumbnail').each(function () {
                    if (jQuery(this).children('.pw-template-thumbnail-inner').children('img').attr('data-value') == template_name) {
                        jQuery(this).addClass('pw_selected_template');
                    }
                });
            }
        });
        return false;
    });

    jQuery('.pw_template_tab li a').click(function (e) {
        e.preventDefault();
        var all_template_hide = true;
        jQuery('.pw_template_tab li').removeClass('pw_current_tab');
        jQuery(this).parent('li').addClass('pw_current_tab');
        var href = jQuery(this).attr('href').replace('#', '');
        jQuery('.pw-template-thumbnail').hide();
        if (href == 'all') {
            jQuery('.pw-template-thumbnail').show();
        } else{
            jQuery('.' + href + '.pw-template-thumbnail').show();
        }
        jQuery('.pw-template-thumbnail').each(function () {
            if (jQuery(this).is(':visible')) {
                all_template_hide = false;
            }
        });
        if (all_template_hide) {jQuery('.no-template').show()} else{jQuery('.no-template').hide()}
    });
   

});

function pwAltBackground() {
    jQuery('.postbox').each(function() {
        jQuery(this).find('ul.pw-settings > li').removeClass('pw-gray');jQuery(this).find('ul.pw-settings > li:not(.pw-hidden):odd').addClass('pw-gray');
    });
}