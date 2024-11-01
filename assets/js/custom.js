jQuery(document).ready(function(){

    jQuery('.hide_title').on('change', function() {
        var radioValue = jQuery("input[name='plwl-team-settings[hide_title]']:checked").val();
        if(radioValue==1){
            jQuery('.titleColor').hide('slow');
            jQuery('.titleFontSize').hide('slow');
            jQuery('.mobileTitleFontSize').hide('slow');
        }
        else{
            jQuery('.titleColor').show('slow');
            jQuery('.titleFontSize').show('slow');
            jQuery('.mobileTitleFontSize').show('slow');
        }
    });

    /*designation*/
    jQuery('.hide_designation').on('change', function() {
        var radioValue = jQuery("input[name='plwl-team-settings[hide_designation]']:checked").val();
        if(radioValue==1){
            jQuery('.designationColor').hide('slow');
            jQuery('.designationFontSize').hide('slow');
            jQuery('.mobileDesignationFontSize').hide('slow');
        }
        else{
            jQuery('.designationColor').show('slow');
            jQuery('.designationFontSize').show('slow');
            jQuery('.mobileDesignationFontSize').show('slow');
        }
    });

    /*description*/
    jQuery('.hide_description').on('change', function() {
        var radioValue = jQuery("input[name='plwl-team-settings[hide_description]']:checked").val();
        if(radioValue==1){
            jQuery('.captionColor').hide('slow');
            jQuery('.captionFontSize').hide('slow');
            jQuery('.mobileCaptionFontSize').hide('slow');
        }
        else{
            jQuery('.captionColor').show('slow');
            jQuery('.captionFontSize').show('slow');
            jQuery('.mobileCaptionFontSize').show('slow');
        }
    });

    /*social*/
    jQuery('.enableSocial').on('change', function() {
        var radioValue = jQuery("input[name='plwl-team-settings[enableSocial]']:checked").val();
        if(radioValue==1){
            jQuery('.enableFacebook').show('slow');
            jQuery('.enableTwitter').show('slow');
            jQuery('.enableLinkedin').show('slow');
            jQuery('.enableInstagram').show('slow');
            jQuery('.enableEmail').show('slow');
            jQuery('.socialIconSize').show('slow');           
            jQuery('.socialIconColor').show('slow');
            jQuery('.socialIconBgColor').show('slow');
            jQuery('.socialIconHColor').show('slow');
            jQuery('.socialIconBgHColor').show('slow');
        }
        else{
            jQuery('.enableFacebook').hide('slow');
            jQuery('.enableTwitter').hide('slow');
            jQuery('.enableLinkedin').hide('slow');
            jQuery('.enableInstagram').hide('slow');
            jQuery('.enableEmail').hide('slow');
            jQuery('.socialIconSize').hide('slow');
            jQuery('.socialIconColor').hide('slow');
            jQuery('.socialIconBgColor').hide('slow');
            jQuery('.socialIconHColor').hide('slow');
            jQuery('.socialIconBgHColor').hide('slow');
        }
    });

    /*slider*/
    jQuery('.slider_autoplay').on('change', function() {
        var radioValue = jQuery("input[name='plwl-team-settings[slider_autoplay]']:checked").val();
        if(radioValue==1){
            jQuery('.autoplay_timing').show('slow');
        }
        else{
            jQuery('.autoplay_timing').hide('slow');
        }
    });

    jQuery('.hide_arrow').on('change', function() {
        var radioValue = jQuery("input[name='plwl-team-settings[hide_arrow]']:checked").val();
        if(radioValue==1){
            jQuery('.arrowBgColor').hide('slow');
            jQuery('.navArrowColor').hide('slow');
        }
        else{
            jQuery('.arrowBgColor').show('slow');
            jQuery('.navArrowColor').show('slow');
        }
    });

    jQuery('.hide_bullets').on('change', function() {
        var radioValue = jQuery("input[name='plwl-team-settings[hide_bullets]']:checked").val();
        if(radioValue==1){
            jQuery('.habulletsBgColor').hide('slow');
            jQuery('.bulletsBgColor').hide('slow');
        }
        else{
            jQuery('.habulletsBgColor').show('slow');
            jQuery('.bulletsBgColor').show('slow');
        }
    });
});