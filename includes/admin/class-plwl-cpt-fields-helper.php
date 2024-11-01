<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 *
 */
class PlWl_CPT_Fields_Helper {

	public static function get_tabs() {

		$general_description = '<p>' . esc_html__( 'Choose between creative or custom grid (build your own) and easily design your gallery.', 'team-builder-showcase' ) . '</p>';
		$caption_description = '<p>' . esc_html__( 'The settings below will adjust the image title/description that will appear on the front-end.', 'team-builder-showcase' ) . '</p>';
		$social_description = '<p>' . esc_html__( 'Here you can add social sharing buttons to your the images in your gallery.', 'team-builder-showcase' ) . '</p>';
		$slider_description = '<p>' . esc_html__( 'Here you can customize the style of your slider.', 'team-builder-showcase' ) . '</p>';
		$customizations_description = '<p>' . esc_html__( 'Use this section to add custom CSS to your gallery for advanced modifications.', 'team-builder-showcase' ) . '</p>';

		return apply_filters( 'plwl_gallery_tabs', array(
			'general' => array(
				'label'       => esc_html__( 'General', 'team-builder-showcase' ),
				'title'       => esc_html__( 'General Settings', 'team-builder-showcase' ),
				'description' => $general_description,
				"icon"        => "dashicons dashicons-admin-generic",
				'priority'    => 10,
			),
			'design' => array(
				'label'       => esc_html__( 'Design', 'team-builder-showcase' ),
				'title'       => esc_html__( 'Design Settings', 'team-builder-showcase' ),
				'description' => 'Select One of the Team design',
				"icon"        => "dashicons dashicons-admin-customizer",
				'priority'    => 10,
			),
			'captions' => array(
				'label'       => esc_html__( 'Captions', 'team-builder-showcase' ),
				'title'       => esc_html__( 'Caption Settings', 'team-builder-showcase' ),
				'description' => $caption_description,
				"icon"        => "dashicons dashicons-text",
				'priority'    => 40,
			),
			'social' => array(
				'label'       => esc_html__( 'Social', 'team-builder-showcase' ),
				'title'       => esc_html__( 'Social Settings', 'team-builder-showcase' ),
				'description' => $social_description,
				"icon"        => "dashicons dashicons-admin-links",
				'priority'    => 50,
			),
			'slider' => array(
				'label'       => esc_html__( 'Slider', 'team-builder-showcase' ),
				'title'       => esc_html__( 'Slider Settings', 'team-builder-showcase' ),
				'description' => $slider_description,
				"icon"        => "dashicons dashicons-cover-image",
				'priority'    => 60,
			),
			'customizations' => array(
				'label'       => esc_html__( 'Custom CSS', 'team-builder-showcase' ),
				'title'       => esc_html__( 'Custom CSS', 'team-builder-showcase' ),
				'description' => $customizations_description,
				"icon"        => "dashicons dashicons-admin-tools",
				'priority'    => 140,
			),

		) );

	}

	public static function get_fields( $tab ) {

		$fields = apply_filters( 'plwl_gallery_fields', array(
			'general' => array(
				"grid_column" => array(
					"name"        => esc_html__( 'Column Layout', 'team-builder-showcase' ),
					"type"        => "select",
					"description" => esc_html__( 'Select the grid type. it will automatically fill each row to the fullest.', 'team-builder-showcase' ),
					'values'      => array(
						'6'         => esc_html__( 'Two Columns', 'team-builder-showcase' ),
						'4'         => esc_html__( 'Three Columns', 'team-builder-showcase' ),
						'3'         => esc_html__( 'Four Columns', 'team-builder-showcase' ),
					),
					'default'     => '4',
					'priority'    => 26,
				),

				'font_family' => array(
					"name"        => esc_html__( 'Font Family', 'team-builder-showcase' ),
					"type"        => "select",
					"description" => esc_html__( 'Select the font family you want to use', 'team-builder-showcase' ),
					"values"      => array(
						'' 				  => esc_html__( 'Default', 'team-builder-showcase' ),
						'Arial' 		  => esc_html__( 'Arial', 'team-builder-showcase' ),
						'Arial Black'     => esc_html__( 'Arial Black', 'team-builder-showcase' ),
						'Arial Narrow'    => esc_html__( 'Arial Narrow', 'team-builder-showcase' ),
						'Calibri'	 	  => esc_html__( 'Calibri', 'team-builder-showcase' ),
						'Cambria'	 	  => esc_html__( 'Cambria', 'team-builder-showcase' ),
						'Candara'	 	  => esc_html__( 'Candara', 'team-builder-showcase' ),
						'Courier'	      => esc_html__( 'Courier', 'team-builder-showcase' ),
						'Courier New'	  => esc_html__( 'Courier New', 'team-builder-showcase' ),
						'Geneva'		  => esc_html__( 'Geneva', 'team-builder-showcase' ),
						'Georgia'		  => esc_html__( 'Georgia', 'team-builder-showcase' ),
						'Grande'		  => esc_html__( 'Grande', 'team-builder-showcase' ),
						'Helvetica'		  => esc_html__( 'Helvetica', 'team-builder-showcase' ),
						'Impact' 		  => esc_html__( 'Impact', 'team-builder-showcase' ),
						'Lucida' 		  => esc_html__( 'Lucida', 'team-builder-showcase' ),
						'Lucida Grande'   => esc_html__( 'Lucida Grande', 'team-builder-showcase' ),
						'Open Sans'       => esc_html__( 'Open Sans', 'team-builder-showcase' ),
						'OpenSansBold'    => esc_html__( 'OpenSansBold', 'team-builder-showcase' ),
						'Optima'  		  => esc_html__( 'Optima', 'team-builder-showcase' ),
						'Palatino Linotype' => esc_html__( 'Palatino', 'team-builder-showcase' ),
						'Sans' 			  => esc_html__( 'Sans', 'team-builder-showcase' ),
						'sans-serif'	  => esc_html__( 'Sans-serif', 'team-builder-showcase' ),
						'Tahom'           => esc_html__( 'Tahom', 'team-builder-showcase' ),
						'Tahoma'          => esc_html__( 'Tahoma', 'team-builder-showcase' ),
						'Tahoma'          => esc_html__( 'Tahoma', 'team-builder-showcase' ),
						'Times New Roman' => esc_html__( 'Times New Roman', 'team-builder-showcase' ),
						'Verdana' 		  => esc_html__( 'Verdana', 'team-builder-showcase' ),
					),
					'default'     => 'Arial',
					'priority' => 30,
				),
				"teamBgColor"     => array(
					"name"        => esc_html__( 'Team Background Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the team background color.', 'team-builder-showcase' ),
					"default"     => "#efefef",
					'priority'    => 45,
				),
				"teamOverlayColor"     => array(
					"name"        => esc_html__( 'Team Overlay Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the overlay color of team.', 'team-builder-showcase' ),
					"default"     => "#04acc1",
					'priority'    => 50,
				),
				"overlayTransparency" => array(
					"name"        => esc_html__( 'Overlay Transparency', 'team-builder-showcase' ),
					"type"        => "ui-slider",
					"min"         => 0,
                    "max"         => 100,
					"description" => esc_html__( 'The Overlay Transparency in Percent (set to 70 to use the defaults).', 'team-builder-showcase' ),
					"default"     => 40,
					'priority'    => 60,
				),
			),

			/*--------------design settings-------------*/
			'design' => array(
				"designName"     => array(
					"name"        => esc_html__( '', 'team-builder-showcase' ),
					"type"        => "custom_text",
					"default"     => "grid-design-01",
					'priority'    => 5,
				),
			),
			/*--------------End of design settings-------------*/

			'captions' => array(
				"hide_title"        => array(
					"name"        => esc_html__( 'Hide Title', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide image titles from your gallery.', 'team-builder-showcase' ),
					'priority'    => 10,
				),
				"titleColor"     => array(
					"name"        => esc_html__( 'Title Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the color of title.', 'team-builder-showcase' ),
					"default"     => "#ffffff",
					'is_child'    => true,
					'priority'    => 20,
				),
				"titleFontSize"    => array(
					"name"        => esc_html__( 'Title Font Size', 'team-builder-showcase' ),
					"type"        => "number",
					"after"       => 'px',
					"min"         => 0,
                    "max"         => 30,
					"default"     => 18,
					"description" => esc_html__( 'The title font size in pixels (set to 0 to use the theme defaults).', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 30,
				),
                "mobileTitleFontSize"    => array(
                    "name"        => esc_html__( 'Mobile Title Font Size', 'team-builder-showcase' ),
                    "type"        => "number",
                    "after"       => 'px',
                    "min"         => 0,
                    "max"         => 24,
                    "default"     => 12,
                    "description" => esc_html__( 'The title font size in pixels (set to 0 to use the theme defaults) for mobile view.', 'team-builder-showcase' ),
                    'is_child'    => true,
                    'priority'    => 40,
                ),
                
                "hide_designation" => array(
					"name"        => esc_html__( 'Hide Designation', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide image titles from your gallery.', 'team-builder-showcase' ),
					'priority'    => 50,
				),
				"designationColor" => array(
					"name"        => esc_html__( 'Designation Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the color of title.', 'team-builder-showcase' ),
					"default"     => "#ffffff",
					'is_child'    => true,
					'priority'    => 60,
				),
				"designationFontSize"    => array(
					"name"        => esc_html__( 'Designation Font Size', 'team-builder-showcase' ),
					"type"        => "number",
					"min"         => 0,
                    "max"         => 24,
					"after"       => 'px',
					"default"     => 14,
					"description" => esc_html__( 'The title font size in pixels (set to 0 to use the theme defaults).', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 70,
				),
                "mobileDesignationFontSize"    => array(
                    "name"        => esc_html__( 'Mobile Designation Font Size', 'team-builder-showcase' ),
                    "type"        => "number",
                    "min"         => 0,
                    "max"         => 20,
                    "after"       => 'px',
                    "default"     => 12,
                    "description" => esc_html__( 'The title font size in pixels (set to 0 to use the theme defaults) for mobile view.', 'team-builder-showcase' ),
                    'is_child'    => true,
                    'priority'    => 80,
                ),

				"hide_description"        => array(
					"name"        => esc_html__( 'Hide Caption', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide image captions from your gallery.', 'team-builder-showcase' ),
					'priority'    => 90,
				),
				"captionColor"     => array(
					"name"        => esc_html__( 'Caption Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the color of captions.', 'team-builder-showcase' ),
					"default"     => "#ffffff",
					'is_child'    => true,
					'priority'    => 100,
				),
				"captionFontSize"  => array(
					"name"        => esc_html__( 'Caption Font Size', 'team-builder-showcase' ),
					"type"        => "number",
					"min"         => 0,
                    "max"         => 24,
					"after"       => 'px',
					"default"     => 14,
					"description" => esc_html__( 'The caption font size in pixels (set to 0 to use theme defaults).', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 110,
				),
                "mobileCaptionFontSize"  => array(
                    "name"        => esc_html__( 'Mobile Caption Font Size', 'team-builder-showcase' ),
                    "type"        => "number",
                    "min"         => 0,
                    "max"         => 20,
					"after"       => 'px',
                    "default"     => 10,
                    "description" => esc_html__( 'The caption font size in pixels (set to 0 to use theme defaults) for mobile view.', 'team-builder-showcase' ),
                    'is_child'    => true,
                    'priority'    => 120,
                ),
			),
			'social' => array(
				"enableSocial"   => array(
					"name"        => esc_html__( 'Enable Social Bar', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => "Enable social sharing on hovering the gallery thumbnail. Off by default.",
					'priority'    => 10,
				),
				"enableFacebook"  => array(
					"name"        => esc_html__( 'Facebook', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Show Facebook Share Icon when hovering the gallery thumbnail', 'team-builder-showcase'),
					'is_child'    => true,
					'priority'    => 20,
				),
				"enableTwitter"   => array(
					"name"        => esc_html__( 'Twitter', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Show Twitter Share Icon when hovering the gallery thumbnail.', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 30,
				),
				"enableLinkedin"  => array(
					"name"        => esc_html__( 'LinkedIn', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Show LinkedIn Share Icon when hovering the gallery thumbnail', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 40,
				),
				"enableInstagram"  => array(
					"name"        => esc_html__( 'Instagram', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Show Instagram Share Icon when hovering the gallery thumbnail', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 50,
				),
				"enableEmail" => array(
					"name"        => esc_html__( 'Email', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Show Email Share Icon when hovering the gallery thumbnail', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 60,
				),
				"socialIconSize" => array(
					"name"        => esc_html__( 'Size', 'team-builder-showcase' ),
					"type"        => "number",
					"min"         => 0,
                    "max"         => 20,
					"after"       => "px",
					"description" => esc_html__( '16 will be the default value.','team-builder-showcase'),
					"default"     => 16,
					'is_child'    => true,
					'priority'    => 70,
				),
				"socialIconColor" => array(
					"name"        => esc_html__( 'Icon Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Select the color of the Social icon.', 'team-builder-showcase' ),
					"default"     => "#000000",
					'is_child'    => true,
					'priority'    => 80,
				),
				"socialIconBgColor" => array(
					"name"        => esc_html__( 'Icon Background Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Select the Background Color of the Social icon.', 'team-builder-showcase' ),
					"default"     => "#ffffff",
					'is_child'    => true,
					'priority'    => 90,
				),		
				"socialIconHColor" => array(
					"name"        => esc_html__( 'Icon Hover Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Select the hover color of the Social icon.', 'team-builder-showcase' ),
					"default"     => "#ffffff",
					'is_child'    => true,
					'priority'    => 100,
				),
				"socialIconBgHColor" => array(
					"name"        => esc_html__( 'Icon Background Hover Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Select the Background Hover Color of the Social icon.', 'team-builder-showcase' ),
					"default"     => "#000000",
					'is_child'    => true,
					'priority'    => 110,
				),
			),
			'slider' => array(
				"slider_autoplay" => array(
					"name"        => esc_html__( 'Autoplay Slider', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 1,
					"description" => esc_html__( 'Set the setting of autoplay the slider.', 'team-builder-showcase' ),
					'priority'    => 10,
				),
				"autoplay_timing" => array(
					"name"        => esc_html__( 'Autoplay Time', 'team-builder-showcase' ),
					"type"        => "number",
					"min"         => 500,
                    "max"         => 30000,
					"after"       => 'ms',
					"default"     => 5000,
					"description" => esc_html__( 'Set the autoplay timing in the slider.', 'team-builder-showcase' ),
					'is_child'    => true,
					'priority'    => 20,
				),

				"hide_arrow"        => array(
					"name"        => esc_html__( 'Hide Navigation Arrows', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide navigation arrow from your slider.', 'team-builder-showcase' ),
					'priority'    => 30,
				),
				"arrowBgColor"  => array(
					"name"        => esc_html__( 'Arrow Background Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the background color of arrow in the slider.', 'team-builder-showcase' ),
					"default"     => "#00bcd4",
					'is_child'    => true,
					'priority'    => 40,
				),
				"navArrowColor"  => array(
					"name"        => esc_html__( 'Navigation Arrow Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the color of arrow in the slider.', 'team-builder-showcase' ),
					"default"     => "#ffffff",
					'is_child'    => true,
					'priority'    => 50,
				),
				"hide_bullets"        => array(
					"name"        => esc_html__( 'Hide Navigation Bullets', 'team-builder-showcase' ),
					"type"        => "toggle",
					"default"     => 0,
					"description" => esc_html__( 'Hide navigation bullets from your slider.', 'team-builder-showcase' ),
					'priority'    => 60,
				),
				"habulletsBgColor"  => array(
					"name"        => esc_html__( 'Hover/Active Bullets Background Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the background color of bullets in the slider.', 'team-builder-showcase' ),
					"default"     => "#869791",
					'is_child'    => true,
					'priority'    => 70,
				),
				"bulletsBgColor"  => array(
					"name"        => esc_html__( 'Bullets Background Color', 'team-builder-showcase' ),
					"type"        => "color",
					"description" => esc_html__( 'Set the color of bullets in the slider.', 'team-builder-showcase' ),
					"default"     => "#D6D6D6",
					'is_child'    => true,
					'priority'    => 80,
				),
			),
			'customizations' => array(
				"style"  => array(
					"name"        => '',
					"type"        => "custom_code",
					"syntax"      => 'css',
					"description" => '',
					'priority' => 20,
				),
			),
			'hidden' => array(
				'last_visited_tab' => array(
					"name"        => '',
					"type" => 'hidden',
					"description" => '',
					'priority' => 20,
				)
			)
		) );

		if ( 'all' == $tab ) {
			return $fields;
		}

		if ( isset( $fields[ $tab ] ) ) {
			return $fields[ $tab ];
		} else {
			return array();
		}

	}



	public static function get_defaults() {
		return apply_filters( 'plwl_lite_default_settings', array(
			'type'                  => 'creative-gallery',
			'teamBgColor'           => '#efefef',
			'teamOverlayColor'      => '#07c5dd',
			'overlayTransparency'   => 40,
			'titleColor'            => '#ffffff',
			'captionColor'          => '#ffffff',
			'hide_title'            => 0,
			'hide_designation'      => 0,
			'hide_description'      => 0,
			'titleFontSize'         => '18',
			'captionFontSize'       => '14',
			'mobileTitleFontSize'   => '12',
			'mobileCaptionFontSize' => '10',
			'enableSocial'          => 1,
			'enableFacebook'        => 1,
			'enableLinkedin'        => 1,
			'enableTwitter'         => 1,
			'enableInstagram'        => 0,
			'enableEmail'           => 0,
			'socialIconSize'        => 16,
			'socialIconColor'       => '#000000',
			'socialIconBgColor'     => '#ffffff',
			'socialIconHColor'      => '#ffffff',
			'socialIconBgHColor'    => '#000000',
			'borderColor'           => '#ffffff',
			'borderRadius'          => '0',
			'slider_autoplay' 		=> 1,
			'autoplay_timing' 		=> 5000,
			'hide_arrow' 			=> 0,
			'arrowBgColor' 			=> '#00bcd4',
			'navArrowColor' 	    => '#ffffff',
			'hide_bullets' 	    	=> 0,
			'habulletsBgColor' 	    => '#869791',
			'bulletsBgColor' 	    => '#D6D6D6',
			'borderSize'            => '0',
			'style'                 => '',
			'grid_column'           => '4',
			'last_visited_tab'      => 'plwl-general'
		) );
	}
}