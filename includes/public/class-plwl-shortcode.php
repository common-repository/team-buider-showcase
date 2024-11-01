<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 *
 */
class PlWl_Shortcode {

	function __construct() {
		add_shortcode( 'plwl-team', array( $this, 'plwl_team_shortcode_handler' ) );
		add_shortcode( 'PLWL-TEAM', array( $this, 'plwl_team_shortcode_handler' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'plwl_team_builder_showcase_scripts' ) );

	}

	public function plwl_team_builder_showcase_scripts() {

		global $post;
		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'plwl-team') ) {

			wp_enqueue_style( 'plwl-team-bootstrap-css', PLWL_TEAM_ASSETS_PATH . 'css/bootstrap.css', null, PLWL_TEAM_CURRENT_VERSION );
			
			wp_enqueue_style( 'plwl-team-fontawesome-css', PLWL_TEAM_ASSETS_PATH . 'css/all.css', null, PLWL_TEAM_CURRENT_VERSION );

			wp_enqueue_script( 'plwl-team-bootstrap-js', PLWL_TEAM_ASSETS_PATH . 'js/bootstrap.bundle.js', array('jquery'), PLWL_TEAM_CURRENT_VERSION, true );
			
			/*slider*/
			wp_register_style( 'plwl-team-owl-carousel-css', PLWL_TEAM_ASSETS_PATH . 'css/owl.carousel.min.css', null, PLWL_TEAM_CURRENT_VERSION );
			
			wp_register_style( 'plwl-team-owl-theme-default-css', PLWL_TEAM_ASSETS_PATH . 'css/owl.theme.default.min.css', null, PLWL_TEAM_CURRENT_VERSION );
			
			wp_register_script( 'plwl-team-owl-carousel-js', PLWL_TEAM_ASSETS_PATH . 'js/owl.carousel.min.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
		}

	}


	public function plwl_team_shortcode_handler( $Id ) {
		// Id return id

		ob_start();	
		if(!isset($Id['id'])) 
		 {
			$PLWL_Team_ID = "";
		 } 
		else 
		{
			$PLWL_Team_ID = $Id['id'];
		}

		$post_type = "plwl-team";
		$AllTeams = array(  'p' => $PLWL_Team_ID, 'post_type' => $post_type, 'orderby' => 'ASC');
	    $loop = new WP_Query( $AllTeams );
		
		while ( $loop->have_posts() ) : $loop->the_post();
			
			$PostId = get_the_ID();
			$settings = get_post_meta( $PostId, 'plwl-team-settings', true );
			//print_r($settings);
			$default  = PlWl_CPT_Fields_Helper::get_defaults();
			$settings = wp_parse_args( $settings, $default );

			$images = apply_filters( 'plwl_team_before_shuffle_images', get_post_meta( $PostId, 'plwl-team-images', true ), $settings );

			/*general*/
			$column = $settings['grid_column'];
			$fontFamily = $settings['font_family'];
			$teamBgColor = $settings['teamBgColor'];
			$teamOverlayColor = $settings['teamOverlayColor'];
			$overlayTransparency = $settings['overlayTransparency'];

			/*title*/
			$hideTitle = $settings['hide_title'];
			$titleColor = $settings['titleColor'];
			$titleFontSize = $settings['titleFontSize'];
			$mobileTitleFontSize = $settings['mobileTitleFontSize'];

			/*designation*/
			$hideDesignation = $settings['hide_designation'];
			$designationColor = $settings['designationColor'];
			$designationFontSize = $settings['designationFontSize'];
			$mobileDesignationFontSize = $settings['mobileDesignationFontSize'];

			$hideDescription = $settings['hide_description'];
			$captionColor = $settings['captionColor'];
			$captionFontSize = $settings['captionFontSize'];
			$mobileCaptionFontSize = $settings['mobileCaptionFontSize'];

			/*social*/
			$enableSocial = $settings['enableSocial'];
			$enableFacebook = $settings['enableFacebook'];
			$enableTwitter = $settings['enableTwitter'];
			$enableLinkedin = $settings['enableLinkedin'];
			$enableInstagram = $settings['enableInstagram'];
			$enableEmail = $settings['enableEmail'];
			$socialIconSize = $settings['socialIconSize'];
			$socialIconColor = $settings['socialIconColor'];
			$socialIconBgColor = $settings['socialIconBgColor'];
			$socialIconHColor = $settings['socialIconHColor'];
			$socialIconBgHColor = $settings['socialIconBgHColor'];

			/*slider*/
			$sliderAutoplay = $settings['slider_autoplay'];
			$autoplayTiming = $settings['autoplay_timing'];

			$hideArrow = $settings['hide_arrow'];
			$arrowBgColor = $settings['arrowBgColor'];
			$navArrowColor = $settings['navArrowColor'];

			$hideBullets = $settings['hide_bullets'];
			$habulletsBgColor = $settings['habulletsBgColor'];
			$bulletsBgColor = $settings['bulletsBgColor'];

			switch($column){
				case(6):
					$sliderColumn=2;
					break;
				case(4):
					$sliderColumn=3;
					break;
				case(3):
					$sliderColumn=4;
				break;
			}

			/*custom css*/
			$customCSS = $settings['style'];

			$design = $settings['designName'];

			require "designs/$design/index.php";

		endwhile;

		wp_reset_query();
    return ob_get_clean();
	  
	}
	
}

new PlWl_Shortcode();