<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! function_exists( 'team_builder_showcase_hex2rgb' ) ) {
    function team_builder_showcase_hex2rgb($hex, $alpha = false) {
       $hex      = str_replace('#', '', $hex);
       $length   = strlen($hex);
       $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
       $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
       $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
       if ( $alpha ) {
          $rgb['a'] = $alpha/100;
       }
       return $rgb;
    }
}

$overlayColor = team_builder_showcase_hex2rgb($teamOverlayColor);
  $overlayR = $overlayColor['r'];
  $overlayG = $overlayColor['g'];
  $overlayB = $overlayColor['b'];
  $overlayA = 1-($overlayTransparency/100);
?>

<style>

.team-section-<?php echo esc_attr($PostId); ?>{
  position: relative;
}

.team-section-<?php echo esc_attr($PostId); ?> .box-team {
    position: relative;
    overflow: hidden;
    padding: 20px;
    background: <?php echo esc_attr($teamBgColor); ?>;
    margin-bottom: 24px;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-img {
    position: relative;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-img img{
    -webkit-transition: all .5s ease-in .1s;
    -o-transition: all .5s ease-in .1s;
    transition: all .5s ease-in .1s;
}


.team-section-<?php echo esc_attr($PostId); ?> .box-team:hover img {
    transform: scale(0.95);
    -webkit-transition: all .5s ease-in .1s;
    -o-transition: all .5s ease-in .1s;
    transition: all .5s ease-in .1s;
}

.team-section-<?php echo esc_attr($PostId); ?> .box-team .overlay {
    background-color: rgba(<?php echo esc_attr($overlayR); ?>,<?php echo esc_attr($overlayG); ?>,<?php echo esc_attr($overlayB); ?>,<?php echo esc_attr($overlayA); ?>);
    border-radius: 50%;
    padding-bottom: 10px;
    padding-left: 15px;
    padding-right: 15px;
    padding-top: 10px;
    position: absolute;
    visibility: hidden;
    z-index: 98;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    -webkit-transition: all .5s ease-in .1s;
    -o-transition: all .5s ease-in .1s;
    transition: all .5s ease-in .1s;
}

.team-section-<?php echo esc_attr($PostId); ?> .center-block {
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -moz-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -moz-box-orient: vertical;
    -moz-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -moz-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}

.team-section-<?php echo esc_attr($PostId); ?> .box-team:hover .overlay {
    opacity: 1;
    visibility: visible;
    border-radius: 0;
    -webkit-transition: all .5s ease-in .1s;
    -o-transition: all .5s ease-in .1s;
    transition: all .5s ease-in .1s;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-social li {
    display: inline-block;
}

.team-section-<?php echo esc_attr($PostId); ?> .box-team .team-social{
    padding: 0;
    margin: 0;
}

/*.team-section-<?php // echo esc_attr($PostId); ?> .box-team ul li:nth-child(1) {
    -webkit-transition-delay: .2s;
    -o-transition-delay: .2s;
    transition-delay: .2s;
}*/

.team-section-<?php echo esc_attr($PostId); ?> .team-social li a {
    font-size: <?php echo esc_attr($socialIconSize); ?>px;
    color: <?php echo esc_attr($socialIconColor); ?>;
    background: <?php echo esc_attr($socialIconBgColor); ?>;
    height: 38px;
    width: 38px;
    line-height: 38px;
    display: inline-block;
    text-align: center;
    margin: 0;
    border: 1px solid transparent;
    border-radius: 50%;
    /*transition: all 1.3s ease 0s;*/
}

.team-section-<?php echo esc_attr($PostId); ?> .team-social li:hover a{
    color: <?php echo esc_attr($socialIconHColor); ?>;
    background: <?php echo esc_attr($socialIconBgHColor); ?>;
}

.team-section-<?php echo esc_attr($PostId); ?> .box-team .team-content h3{
    color: <?php echo esc_attr($titleColor); ?>;
    font-size: <?php echo esc_attr($titleFontSize); ?>px;
    margin-bottom: 5px;
}

.team-section-<?php echo esc_attr($PostId); ?> .box-team .team-content p.team-designation{
    color: <?php echo esc_attr($designationColor); ?>;
    font-size: <?php echo esc_attr($designationFontSize); ?>px;
    margin-bottom: 8px;
}

.team-section-<?php echo esc_attr($PostId); ?> .box-team .team-content .team-description{   
    color: <?php echo esc_attr($captionColor); ?>;
    font-size: <?php echo esc_attr($captionFontSize); ?>px;
    margin-bottom: 5px;
}

@media only screen and (max-width: 480px){
    .team-section-<?php echo esc_attr($PostId); ?> .box-team .team-content h3{
        font-size: <?php echo esc_attr($mobileTitleFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .box-team .team-content p.team-designation{
        font-size: <?php echo esc_attr($mobileDesignationFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .box-team .team-content .team-description{   
        font-size: <?php echo esc_attr($mobileCaptionFontSize); ?>px;
    }
}

/*Settings*/
.team-section-<?php echo esc_attr($PostId); ?>{
    font-family: <?php echo esc_attr($fontFamily); ?>;
}

<?php echo esc_attr($customCSS); ?>

</style>