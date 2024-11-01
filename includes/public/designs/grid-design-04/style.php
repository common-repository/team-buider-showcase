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
.team-section-<?php echo esc_attr($PostId); ?> .social ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item {
  margin-bottom: 24px;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb {
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb::after {
  background: rgb(<?php echo esc_attr($overlayR); ?>,<?php echo esc_attr($overlayG); ?>,<?php echo esc_attr($overlayB); ?>);
  content: "";
  height: 100%;
  left: 0;
  opacity: 0;
  position: absolute;
  top: 0;
  transition: all 0.35s ease-in-out;
  -webkit-transition: all 0.35s ease-in-out;
  -moz-transition: all 0.35s ease-in-out;
  -ms-transition: all 0.35s ease-in-out;
  -o-transition: all 0.35s ease-in-out;
  width: 100%;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-items .single-item:hover .thumb::after {
  opacity: <?php echo esc_attr($overlayA); ?>;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb .overlay {
  top: -500%;
  left: 0;
  padding: 20px;
  position: absolute;
  text-align: center;
  -webkit-transition: all 0.35s ease-in-out;
  -moz-transition: all 0.35s ease-in-out;
  -ms-transition: all 0.35s ease-in-out;
  -o-transition: all 0.35s ease-in-out;
  transition: all 0.35s ease-in-out;
  width: 100%;
  z-index: 1;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item:hover .thumb .overlay {
  top: 50%;
  transform: translate(-50%, -50%);
  left: 50%;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb .overlay h3 {
  color: #ffffff;
  display: inline-block;
  position: relative;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb img {
  width: 100%;
  -webkit-transition: all 0.35s ease-in-out;
  -moz-transition: all 0.35s ease-in-out;
  -ms-transition: all 0.35s ease-in-out;
  -o-transition: all 0.35s ease-in-out;
  transition: all 0.35s ease-in-out;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item:hover .thumb img {
  opacity: .6;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb .social li {
  display: inline-block;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb .social li a {
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
  display: inline-block;
  height: 38px;
  line-height: 38px;
  margin: 0 2px;
  text-align: center;
  width: 38px;
  font-size: <?php echo esc_attr($socialIconSize); ?>px;
  color: <?php echo esc_attr($socialIconColor); ?>;
  background: <?php echo esc_attr($socialIconBgColor); ?>;
  transition: all 1.3s ease 0s;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb .social li:hover a{
  color: <?php echo esc_attr($socialIconHColor); ?>;
  background: <?php echo esc_attr($socialIconBgHColor); ?>;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-content {
  background-color: <?php echo esc_attr($teamBgColor); ?>;
  -moz-box-shadow: 0 0 10px #cccccc;
  -webkit-box-shadow: 0 0 10px #cccccc;
  -o-box-shadow: 0 0 10px #cccccc;
  box-shadow: 0 0 10px #cccccc;
  padding: 15px;
  position: relative;
  text-align: center;
  z-index: 9;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-content .message {
  height: 50px;
  line-height: 40px;
  margin-left: -25px;
  margin-top: -25px;
  position: absolute;
  text-align: center;
  top: 0;
  width: 50px;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-content .message a {
  background: #fff none repeat scroll 0 0;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
  -moz-box-shadow: 0 0 10px #cccccc;
  -webkit-box-shadow: 0 0 10px #cccccc;
  -o-box-shadow: 0 0 10px #cccccc;
  box-shadow: 0 0 10px #cccccc;
  box-sizing: border-box;
  color: #ff5a6e;
  display: inline-block;
  font-size: 20px;
  height: 50px;
  line-height: 50px;
  width: 50px;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-content .message a i {
  font-weight: 500;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-content h3 {
  font-weight: 600;
  margin-bottom: 5px;
  text-transform: capitalize;
  color: <?php echo esc_attr($titleColor); ?>;
  font-size: <?php echo esc_attr($titleFontSize); ?>px;
}

.team-section-<?php echo esc_attr($PostId); ?> .team-content span.team-designation {
  font-weight: 600;
  text-transform: capitalize;
  color: <?php echo esc_attr($designationColor); ?>;
  font-size: <?php echo esc_attr($designationFontSize); ?>px;
}

.team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb .overlay p.team-description {
  color: <?php echo esc_attr($captionColor); ?>;
  font-size: <?php echo esc_attr($captionFontSize); ?>px;
}

@media only screen and (max-width: 480px){
    .team-section-<?php echo esc_attr($PostId); ?> .team-content h3{
      font-size: <?php echo esc_attr($mobileTitleFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .team-content span.team-designation {
      font-size: <?php echo esc_attr($mobileDesignationFontSize); ?>px;
    }
    .team-section-<?php echo esc_attr($PostId); ?> .single-item .thumb .overlay p.team-description {
      font-size: <?php echo esc_attr($mobileCaptionFontSize); ?>px;
    }
}

/*Settings*/
.team-section-<?php echo esc_attr($PostId); ?>{
    font-family: <?php echo esc_attr($fontFamily); ?>;
}

<?php echo esc_attr($customCSS); ?>
</style>