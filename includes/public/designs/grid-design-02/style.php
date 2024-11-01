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

.team-section-<?php echo esc_attr($PostId); ?> .our-team{
    text-align: center;
    margin-bottom: 24px;
}
.team-section-<?php echo esc_attr($PostId); ?> .our-team .pic{
    position: relative;
    overflow: hidden;
}
.team-section-<?php echo esc_attr($PostId); ?> .our-team .pic img{
    width: 100%;
    height: auto;
    transition: all 0.2s ease 0s;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team:hover .pic img{
    transform: translateY(15px);
     transition: all 0.2s ease 0s;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .social_media_team{
    width: 90%;
    left: 0;
    right: 0;
    margin: auto;
    position: absolute;
    bottom: -500%;
    padding: 25px;
    background-color: rgba(<?php echo esc_attr($overlayR); ?>,<?php echo esc_attr($overlayG); ?>,<?php echo esc_attr($overlayB); ?>,<?php echo esc_attr($overlayA); ?>);
    transition: all 0.5s ease;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team:hover .social_media_team{
    bottom: 0px;
    transition: all 0.5s ease;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .social-link{
    list-style: none;
    padding: 0;
    margin: 0;
    height: 100%;
    text-align: center;
    position: relative;
    top:2%;
}
.team-section-<?php echo esc_attr($PostId); ?> .our-team .social-link li{
    display: inline-block;
    margin: 0 5px 8px 0;
}
.team-section-<?php echo esc_attr($PostId); ?> .our-team .social-link li a{
    width: 38px;
    height: 38px;
    line-height: 38px;
    display: block;
    color: <?php echo esc_attr($socialIconColor); ?>;
    background: <?php echo esc_attr($socialIconBgColor); ?>;
    font-size: <?php echo esc_attr($socialIconSize); ?>px;
    transition: all 1.3s ease 0s;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .social-link li a:hover{
    color: <?php echo esc_attr($socialIconHColor); ?>;
    background: <?php echo esc_attr($socialIconBgHColor); ?>;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .team-content{
    padding: 10px;
    background: <?php echo esc_attr($teamBgColor); ?>;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .team-content h3.post-title{
    color: <?php echo esc_attr($titleColor); ?>;
    font-size: <?php echo esc_attr($titleFontSize); ?>px;
    margin-bottom: 5px;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .team-designation{
    color: <?php echo esc_attr($designationColor); ?>;
    font-size: <?php echo esc_attr($designationFontSize); ?>px;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .team-description{
    margin-top: 10px;
    color: <?php echo esc_attr($captionColor); ?>;
    font-size: <?php echo esc_attr($captionFontSize); ?>px;
}

@media only screen and (max-width: 480px){
    .team-section-<?php echo esc_attr($PostId); ?> .our-team .team-content h3.post-title{
        font-size: <?php echo esc_attr($mobileTitleFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .our-team .team-designation{
        font-size: <?php echo esc_attr($mobileDesignationFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .our-team .team-description{
        font-size: <?php echo esc_attr($mobileCaptionFontSize); ?>px;
    }
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .post-title a{
    text-transform: capitalize;
    color:#424242;
    transition: all 0.2s ease 0s;
}

.team-section-<?php echo esc_attr($PostId); ?> .our-team .post-title a:hover{
    text-decoration: none;
    color:#3bcca1;
}

@media screen and (max-width: 990px){
   .team-section-<?php echo esc_attr($PostId); ?> .our-team{
        margin-bottom: 30px;
    }
}

/*Settings*/
.team-section-<?php echo esc_attr($PostId); ?>{
    font-family: <?php echo esc_attr($fontFamily); ?>;
}

<?php echo esc_attr($customCSS); ?>
</style>