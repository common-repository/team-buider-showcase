<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 *
 */
class PlWl_Field_Builder {

	private $settings = array();

	function __construct() {

		/* Add templates for our plugin */
		add_action( 'admin_footer', array( $this, 'print_plwl_templates' ) );

	}


	/**
	 * Get an instance of the field builder
	 */
	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new PlWl_Field_Builder();
		}
		return $inst;
	}

	public function get_id(){
		global $id, $post;

        $post_id = isset( $post->ID ) ? $post->ID : (int) $id;

        return $post_id;
	}

	/**
     * Helper method for retrieving settings values.
     *
     * @since 1.0.0
     *
     * @global int $id        The current post ID.
     * @global object $post   The current post object.
     * @param string $key     The setting key to retrieve.
     * @param string $default A default value to use.
     * @return string         Key value on success, empty string on failure.
     */
    public function get_setting( $key, $default = false ) {

        // Get config
        if ( empty( $this->settings ) ) {
        	$this->settings = get_post_meta( $this->get_id(), 'plwl-team-settings', true );
        }

        // Check config key exists
        if ( isset( $this->settings[ $key ] ) ) {
            $value = $this->settings[ $key ];
        } else {
            $value = $default ? $default : '';
        }

        return apply_filters( 'plwl_admin_field_value', $value, $key, $this->settings );

    }

	public function render( $metabox, $post = false ) {

		switch ( $metabox ) {
			case 'gallery':
				$this->_render_gallery_metabox();
				break;
			case 'settings':
				$this->_render_settings_metabox();
				break;
			case 'shortcode':
				$this->_render_shortcode_metabox( $post );
				break;
			default:
				do_action( "plwl_metabox_fields_{$metabox}" );
				break;
		}

	}

	/* Create HMTL for gallery metabox */
	private function _render_gallery_metabox() {

		$images = get_post_meta( $this->get_id(), 'plwl-team-images', true );
		$max_upload_size = wp_max_upload_size();
                
                if ( ! $max_upload_size ) {
                    $max_upload_size = 0;
                }
                
		echo '<div class="plwl-uploader-container">';
		
		echo '<div id="plwl-uploader-container" class="plwl-uploader-inline">';
			echo '<div class="plwl-error-container"></div>';
			echo '<div class="plwl-uploader-inline-content">';
				echo '<h2 class="plwl-upload-message"><span class="dashicons dashicons-upload"></span>' . esc_html__( 'Drag & Drop files here!', 'team-builder-showcase' ) . '</h2>';
				echo '<div id="plwl-grid" style="display:none"></div>';
			echo '</div>';
			echo '<div id="plwl-dropzone-container"><div class="plwl-uploader-window-content"><h1>' . esc_html__( 'Drop files to upload', 'team-builder-showcase' ) . '</h1></div></div>';
		echo '</div>';

		echo '<div class="plwl-upload-actions">';
		echo '<div class="upload-info-container">';
		echo '<div class="upload-info">';
                if (current_user_can('upload_files')) {
                    echo sprintf( wp_kses_post( __( '<b>Drag and drop</b> files here (max %s per file), or <b>drag images around to change their order</b>', 'team-builder-showcase' ) ), esc_html( size_format( $max_upload_size ) ) );
                    echo '</div>';
                    echo '<div class="upload-progress">';
                    echo '<p class="plwl-upload-numbers">' . esc_html__( 'Uploading image', 'team-builder-showcase' ) . ' <span class="plwl-current"></span> ' . esc_html__( 'of', 'team-builder-showcase' ) . ' <span class="plwl-total"></span>';
                    echo '<div class="plwl-progress-bar"><div class="plwl-progress-bar-inner"></div></div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="buttons">';
                    echo '<a href="#" id="plwl-uploader-browser" class="button">' . esc_html__( 'Upload image files', 'team-builder-showcase' ) . '</a><a href="#" id="plwl-wp-gallery" class="button button-primary">' . esc_html__( 'Select from Library', 'team-builder-showcase' ) . '</a>';
                    do_action( 'plwl_gallery_media_button');
                } else {
                    echo '<b>' . esc_html__( 'Drag images around to change their order', 'team-builder-showcase' ) . '</b>';
                    echo '</div>';
                }
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	/* Create HMTL for settings metabox */
	private function _render_settings_metabox() {
		$tabs = PlWl_CPT_Fields_Helper::get_tabs();

		// Sort tabs based on priority.
		uasort( $tabs, array( 'PlWl_Helper', 'sort_data_by_priority' ) );

		$tabs_html = '';
		$tabs_content_html = '';
		$first = true;

		// Generate HTML for each tab.
		foreach ( $tabs as $tab_id => $tab ) {
			$tab['id'] = $tab_id;
			$tabs_html .= $this->_render_tab( $tab, $first );

			$fields = PlWl_CPT_Fields_Helper::get_fields( $tab_id );
			// Sort fields based on priority.
			uasort( $fields, array( 'PlWl_Helper', 'sort_data_by_priority' ) );

			$current_tab_content = '<div id="plwl-' . esc_attr( $tab['id'] ) . '" class="plwl-tab-content' . ( $first ? ' active-tab' : '' ) . '">';

			// Check if our tab have title & description
			if ( isset( $tab['title'] ) || isset( $tab['description'] ) ) {
				$current_tab_content .= '<div class="tab-content-header">';
				$current_tab_content .= '<div class="tab-content-header-title">';
				if ( isset( $tab['title'] ) && '' != $tab['title'] ) {
					$current_tab_content .= '<h2>' . esc_html( $tab['title'] ) . '</h2>';
				}
				if ( isset( $tab['description'] ) && '' != $tab['description'] ) {
					$current_tab_content .= '<div class="tab-header-tooltip-container plwl-tooltip"><span class="dashicons dashicons-lightbulb"></span>';
					$current_tab_content .= '<div class="tab-header-description plwl-tooltip-content">' . wp_kses_post( $tab['description'] ) . '</div>';
					$current_tab_content .= '</div>';
				}
				$current_tab_content .= '</div>';

				$current_tab_content .= '</div>';

			}

			// Generate all fields for current tab
			$current_tab_content .= '<div class="form-table-wrapper">';
			$current_tab_content .= '<table class="form-table"><tbody>';
			foreach ( $fields as $field_id => $field ) {
				$field['id'] = $field_id;
				$current_tab_content .= $this->_render_row( $field );
			}
			$current_tab_content .= '</tbody></table>';
			// Filter to add extra content to a specific tab
			$current_tab_content .= apply_filters( 'plwl_' . $tab_id . '_tab_content', '' );
			$current_tab_content .= '</div>';
			$current_tab_content .= '</div>';
			$tabs_content_html .= $current_tab_content;

			if ( $first ) {
				$first = false;
			}

		}

		$html = '<div class="plwl-team-settings-container"><div class="plwl-tabs">%s</div><div class="plwl-tabs-content">%s</div>';
	
		printf( wp_kses_post( $html ), wp_kses_post( $tabs_html ), $tabs_content_html );

		global $id, $post;
		$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

		if( !empty(  get_post_meta( $post_id, 'plwl-team-settings' )  ) ) {
		    $settings = get_post_meta( $post_id, 'plwl-team-settings', true );

		    $hide_title=$settings['hide_title'];
		    $hide_designation=$settings['hide_designation'];
		    $hide_description=$settings['hide_description'];
		    $slider_autoplay=$settings['slider_autoplay'];
		    $hide_arrow=$settings['hide_arrow'];
		    $hide_bullets=$settings['hide_bullets'];
		    $enableSocial=$settings['enableSocial'];
		} else {  
		    $hide_title=0;
		    $hide_designation=0;
		    $hide_description=0;
		    $slider_autoplay=1;
		    $hide_arrow=0;
		    $hide_bullets=0;
		    $enableSocial=1;
		}

		if($hide_title==1){
			?>
				<style>.titleColor,.titleFontSize,.mobileTitleFontSize{ display:none; }</style>
			<?php
		}
		if($hide_designation==1){
			?>
				<style>.designationColor,.designationFontSize,.mobileDesignationFontSize{ display:none; }</style>
			<?php
		}
		if($hide_description==1){
			?>
				<style>.captionColor,.captionFontSize,.mobileCaptionFontSize{ display:none; }</style>
			<?php
		}
		if($slider_autoplay==0){
			?>
				<style>.autoplay_timing{ display:none; }</style>
			<?php
		}
		if($hide_arrow==1){
			?>
				<style>.arrowBgColor,.navArrowColor{ display:none; }</style>
			<?php
		}
		if($hide_bullets==1){
			?>
				<style>.habulletsBgColor,.bulletsBgColor{ display:none; }</style>
			<?php
		}
		if($enableSocial==0){
			?>
				<style>.enableFacebook,.enableTwitter,.enableLinkedin,.enableInstagram,.enableEmail,.socialIconSize,.socialIconColor,.socialIconBgColor,.socialIconHColor,.socialIconBgHColor{ display:none; }</style>
			<?php
		}
	}

	/* Create HMTL for shortcode metabox */
	private function _render_shortcode_metabox( $post ) {
		$shortcode = '[plwl-team id="' . $post->ID . '"]';

		do_action( 'plwl_admin_before_shortcode_metabox', $post );

		echo '<div class="duplicate-copy-shortcode">';
        echo '<input type="text" value="' . esc_attr($shortcode) . '"  onclick="select()" readonly>';
		echo '<a href="#" title="' . esc_attr__('Copy shortcode','team-builder-showcase') . '" class="copy-plwl-shortcode button button-primary dashicons dashicons-format-gallery" style="width:40px;"></a><span></span>';
		echo '<p class="shortcode-description">' . sprintf( esc_html__( 'You can use this to display your newly created gallery inside a %s post or a page %s', 'team-builder-showcase'), '<u>', '</u>' ) .  '</p>';
        echo '</div>';

        do_action( 'plwl_admin_after_shortcode_metabox', $post );
	}

	/* Create HMTL for a tab */
	private function _render_tab( $tab, $first = false ) {
		$icon = '';
		$badge = '';

		if ( isset( $tab['icon'] ) ) {
			$icon = '<i class="' . esc_attr( $tab['icon'] ) . '"></i>';
		}

		if ( isset( $tab['badge'] ) ) {
			$badge = '<sup>' . esc_html( $tab['badge'] ) . '</sup>';
		}
		return '<div class="plwl-tab' . ( $first ? ' active-tab' : '' ) . ' plwl-' . esc_attr( $tab['id'] ) . '" data-tab="plwl-' . esc_attr( $tab['id'] ) . '">' . $icon . wp_kses_post( $tab['label'] ) . $badge . '</div>';
	}

	/* Create HMTL for a row */
	private function _render_row( $field ) {

		$child      = '';
		$field_name = wp_kses_post( $field[ 'name' ] );

		// Generate tooltip
		$tooltip = '';
		if ( isset( $field[ 'description' ] ) && '' != $field[ 'description' ] ) {
			$tooltip .= '<div class="plwl-tooltip"><span class="dashicons dashicons-lightbulb"></span>';
			$tooltip .= '<div class="plwl-tooltip-content">' . wp_kses_post( $field[ 'description' ] ) . '</div>';
			$tooltip .= '</div>';
		}


		if(isset($field['is_child']) && $field['is_child'] && is_bool($field['is_child'])){
			$child = 'child_setting';
		}

		if(isset($field['is_child']) && $field['is_child'] && is_string($field['is_child'])){
			$child = $field['is_child'].'_child_setting';
		}

		$format = '<tr class="' . esc_attr( $field['id'] ) . '" data-container="' . esc_attr( $field['id'] ) . '"><th scope="row" class="' . esc_attr( $child ) . '"><label>%s</label>%s</th><td>%s</td></tr>';

		if( isset( $field['children'] ) && is_array( $field['children'] ) && 0 < count( $field['children'] ) ){

			$children = htmlspecialchars(wp_json_encode( $field['children'] ), ENT_QUOTES, 'UTF-8');

			$parent = '';
			if( isset( $field['parent'] ) && '' != $field['parent']  ){
				$parent = 'data-parent="' . $field['parent'] . '"';
			}
			$format = '<tr data-container="' . esc_attr( $field['id'] ) . '" data-children=\'' . $children . '\' ' . $parent . '><th scope="row" class="' . esc_attr( $child ) . '"><label>%s</label>%s</th><td>%s</td></tr>';
		}

		// Formats for General Gutter
		if ( 'gutterInput' == $field['type'] ) {

			if ( 'desktop' == $field['media'] ) {
				$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><th scope="row" class="' . esc_attr( $child ) . '"><label>%s</label>%s</th><td><span class="dashicons dashicons-desktop"></span>%s<span class="plwl_input_suffix">px</span></td>';
			}

			if ( 'tablet' == $field['media'] ) {
				$field_name = '<span class="dashicons dashicons-tablet"></span>';
				$tooltip    = '';
				$format     = '<td>%s%s%s<span class="plwl_input_suffix">px</span></td>';
			}

			if ( 'mobile' == $field['media'] ) {
				$field_name = '<span class="dashicons dashicons-smartphone"></span>';
				$tooltip    = '';
				$format     = '<td>%s%s%s<span class="plwl_input_suffix">px</span></td></tr>';
			}
		}
		// End formats for General Gutter

		if ( 'textarea' == $field['type'] || 'custom_code' == $field['type'] || 'hover-effect' == $field['type'] ) {
			$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><td colspan="2" class="' . esc_attr( $child ) . '"><label class="th-label">%s</label>%s<div>%s</div></td></tr>';
		}

		$format = apply_filters( "plwl_field_type_{$field['type']}_format", $format, $field );

		$default = '';

		// Check if our field have a default value
		if ( isset( $field['default'] ) ) {
			$default = $field['default'];
		}

		// Get the current value of the field
		$value = $this->get_setting( $field['id'], $default );

		return sprintf( $format, $tooltip, $field_name, $this->_render_field( $field, $value ) );
	}

	/* Create HMTL for a field */
	private function _render_field( $field, $value = '' ) {
		$html = '';
$plwl_nonce = wp_create_nonce( 'plwl_team_nonce_save_settings' );
		switch ( $field['type'] ) {

			case 'image-size':
				$html .= '<div class="plwl-image-size">';
				$html .= '<input type="text" name="plwl-team-settings[' . esc_attr( $field['id'] ) . '][width]" data-setting="' . esc_attr( $field['id'] ) . '" value="' . ((is_array($value) && isset($value['width'])) ? esc_attr( $value['width'] ) : '') . '">';
				$html .= '<span class="modila-image-size-spacer">x</span>';
				$html .= '<input type="text" name="plwl-team-settings[' . esc_attr( $field['id'] ) . '][height]" data-setting="' . esc_attr( $field['id'] ) . '" value="' . ((is_array($value) && isset($value['height'])) ? esc_attr( $value['height'] ) : '') . '">';
				$html .= '<span class="modila-image-size-spacer">px</span>';
				$html .= '</div>';
				break;
			case 'text':
				$html = '<input type="text" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'. wp_kses_post( $field['afterrow'] ) .'</p>';
				}
				break;
			case 'number':
				$min  = isset( $field['min'] ) ? $field['min'] : 0;
				$max  = isset( $field['max'] ) ? $field['max'] : 100;

				$attributes = 'min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" ';

				$html = '<input type="number" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '"' . esc_attr( $attributes ) . '/>';


				if ( isset( $field['after'] ) ) {
					$html .= '<span class="plwl-after-input">' . esc_html( $field['after'] ) . '</span>';
				}

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'. wp_kses_post( $field['afterrow'] ) .'</p>';
				}
				break;
			case 'gutterInput':
				$html = '<input type="number" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" class="plwl-gutter-input" value="' . esc_attr( $value ) . '">';
				if ( isset( $field['after'] ) ) {
					$html .= '<span class="plwl-after-input">' . esc_html( $field['after'] ) . '</span>';
				}

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'. wp_kses_post( $field['afterrow'] ) .'</p>';
				}
				break;

			case 'responsiveInput':
				$html = '<span class="dashicons dashicons-desktop"></span><input type="number"  name="plwl-team-settings[' . esc_attr( $field['id'] ) . '][]" data-setting="' . esc_attr( $field['id'] ) . '" class="plwl-gutter-input" value="' . esc_attr( $value[0] ) . '"><span class="plwl_input_suffix">px</span></td>';
				$html .= '<td><span class="dashicons dashicons-tablet"></span><input type="number"  name="plwl-team-settings[' . esc_attr( $field['id'] ) . '][]" data-setting="' . esc_attr( $field['id'] ) . '" class="plwl-gutter-input" value="' . esc_attr( $value[1] ) . '"><span class="plwl_input_suffix">px</span></td>';
				$html .= '<td><span class="dashicons dashicons-smartphone"></span><input type="number"  name="plwl-team-settings[' . esc_attr( $field['id'] ) . '][]" data-setting="' . esc_attr( $field['id'] ) . '" class="plwl-gutter-input" value="' . esc_attr( $value[2] ) . '"><span class="plwl_input_suffix">px</span>';
				if ( isset( $field['after'] ) ) {
					$html .= '<span class="plwl-after-input">' . esc_html( $field['after'] ) . '</span>';
				}

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'. wp_kses_post( $field['afterrow'] ) .'</p>';
				}
				break;
			case 'select' :
				$html = '<select name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '">';

				foreach ( $field['values'] as $key => $option ) {

				    // Fix for single lightbox after Plwl update
				    if('lightbox' == $field['id']){
				    	
				        if(is_array($option) && !isset($option[$value])){

				            $value = 'fancybox';
                        }
                    }

					if ( is_array( $option )  && ! empty( $option ) ) {
						$html .= '<optgroup label="' . esc_attr( $key ) . '">';
						foreach ( $option as $key_subvalue => $subvalue ) {
                            $html .= '<option value="' . esc_attr( $key_subvalue ) . '" ' .  selected( $key_subvalue, $value, false )  . '>' . esc_html( $subvalue ) . '</option>';
						}
						$html .= '</optgroup>';
					}else{
						$html .= '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value, false ) . '>' . esc_html( $option ) . '</option>';
					}
				}

				if ( isset( $field['disabled'] ) && is_array( $field['disabled'] ) && ! empty( $field['disabled']['values'] ) ) {
					$html .= '<optgroup label="' . esc_attr( $field['disabled']['title'] ) . '">';
					foreach ( $field['disabled']['values'] as $key => $disabled ) {
						$html .= '<option value="' . esc_attr( $key ) . '" disabled >' . esc_html( $disabled ) . '</option>';
					}
					$html .= '</optgroup>';
				}
				$html .= '</select>';

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'.esc_html($field['afterrow']).'</p>';
				}
				break;
			case 'ui-slider':
				$min  = isset( $field['min'] ) ? $field['min'] : 0;
				$max  = isset( $field['max'] ) ? $field['max'] : 100;
				$step = isset( $field['step'] ) ? $field['step'] : 1;
				if ( '' === $value ) {
					if ( isset( $field['default'] ) ) {
						$value = $field['default'];
					}else{
						$value = $min;
					}
				}
				$attributes = 'data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-step="' . esc_attr( $step ) . '"';
				$html .= '<div class="slider-container plwl-ui-slider-container">';
					$html .= '<div id="slider_' . esc_attr( $field['id'] ) . '" class="ss-slider plwl-ui-slider"></div>';
					$html .= '<input contenteditable="true" data-setting="' . esc_attr( $field['id'] ) . '"  name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" type="text" class="rl-slider plwl-ui-slider-input" id="input_' . esc_attr( $field['id'] ) . '" value="' . sanitize_text_field( $value ) . '" ' . $attributes . '/>';
				$html .= '</div>';
				break;
			case 'color' :
				$html .= '<div class="plwl-colorpickers">';
				if ( isset( $field['alpha'] ) && $field['alpha'] ) {
					$html .= '<input id="' . esc_attr( $field['id'] ) . '" class="plwl-color" data-alpha="true" data-setting="' . esc_attr( $field['id'] ) . '" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" value="' . esc_attr( $value ) . '">';
				}else{
					$html .= '<input id="' . esc_attr( $field['id'] ) . '" class="plwl-color" data-setting="' . esc_attr( $field['id'] ) . '" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" value="' . esc_attr( $value ) . '">';
				}

				$html .= '</div>';

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'.esc_html($field['afterrow']).'</p>';
				}
				break;
			case "toggle":
				$html .= '<div class="plwl-toggle">';
					$html .= '<input class="duplicate-toggle__input" type="checkbox" data-setting="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" value="1" ' . checked( 1, $value, false ) . '>';
					$html .= '<div class="duplicate-toggle__items">';
						$html .= '<span class="duplicate-toggle__track"></span>';
						$html .= '<span class="duplicate-toggle__thumb"></span>';
						$html .= '<svg class="duplicate-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>';
						$html .= '<svg class="duplicate-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>';
					$html .= '</div>';
				$html .= '</div>';

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'. wp_kses_post($field['afterrow'], array( 'a' => array( 'href' => array() ), 'target' => array() )).'</p>';
				}
				break;
			case "custom_code":
				$html = '<div class="plwl-code-editor" data-syntax="' . esc_attr( $field['syntax'] ) . '">';
				$html .= '<textarea data-setting="' . esc_attr( $field['id'] ) . '" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" id="plwl-' . esc_attr( $field['id'] ) . '-customcode" class="large-text code plwl-custom-editor-field"  rows="10" cols="50">' . wp_kses_post($value) . '</textarea>';
				$html .= '</div>';

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'.esc_html($field['afterrow']).'</p>';
				}
				break;
			
			case 'dimensions-select' :
				$sizes = PlWl_Helper::get_image_sizes();
				$html = '<select name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" class="regular-text">';
				$infos = '';

				foreach ( $sizes as $key => $size ) {

					$html .= '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value, false ) . '>' . esc_html( ucfirst(str_replace('-', ' ', $key) )) . '</option>';

					$infos .= '<div class="plwl-team-imagesize-info" data-size="' . esc_attr( $key ) . '"><span>' . esc_html__( 'Image Size', 'team-builder-showcase' ) . '</span>: ' . $size['width'] . 'x' . $size['height'];
					$infos .= '. <span>'.esc_html__('Crop','team-builder-showcase').'</span>: ' . ( boolval( $size['crop'] ) ? 'true' : 'false' ) . '</div>';
				}

				$html .= '<option value="full" '. selected( 'full', $value, false ).'>' . __( 'Full', 'team-builder-showcase' ) . '</option>';
				$html .= '<option value="custom" '.selected( 'custom', $value, false ).'>' . __( 'Custom', 'team-builder-showcase' ) . '</option>';
				$html .= '</select>';
				if ( '' != $infos ) {
					$html .= '<div class="plwl-team-imagesizes-infos">' . $infos . '</div>';
				}
				break;

			case "custom_text":
				
				$this->pw_reg_function();

				$html = '<div name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '"  class="">';


				$html .= '<div id="pwgeneral" class="postbox postbox-with-fw-options">';
				$html .= '<ul class="plwl-team-settings">';
				$html .= ' <li>';
				$html .= '<h3 class="plwl-table-title" style="margin-bottom:20px;">Select Team Layout</h3>';
				
				$html .= '<div class="plwl-left">';
				$html .= '<p class="plwl-margin-bottom-50" style="margin-bottom:20px; font-size:16px;">Select your favorite Team design from 4 free Grid and Slider designs. </p>';

				
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;
				$settings = get_post_meta( $post_id, 'plwl-team-settings', true );
				
				ob_start(); ?>
                
				<p class="plwl-margin-bottom-30" style="margin-bottom:20px;font-size:16px;"><b><?php esc_html_e('Selected Design:', 'team-builder-showcase'); ?></b> &nbsp;&nbsp;
                <span class="plwl-template-name" id="plwl-id">
                    <?php
                    if (isset($settings['designName'])) {
                        $designName = str_replace('-', ' ', $settings['designName']) . ' ';
                        printf(
						    esc_html__( '%s', 'team-builder-showcase' ),
						    esc_html( ucwords( $designName, ' ' ) )
						);
                    }
                    ?>
                </span></p>


				<?php $html .= ob_get_clean();

                $html .=   '</span> 
						 </p>';
				

				$html .= '<div class="pw_select_template_button_div">
							<input type="button" class="pw_select_template" value="Select Other Designs">
						</div>';

				$html .= '<input type="hidden" id="' . esc_attr( $field['id'] ) . '" class="col-sm-4 regular-text pw_selected_template" name="plwl-team-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';			

				$html .= '</div>';

				$html .= '</li>';
				$html .= '</ul>';
				$html .= '</div>';

				$html .= '</div>';



			default:
				/* Filter for render custom field types */
				$html = apply_filters( "plwl_render_{$field['type']}_field_type", $html, $field, $value );

				if(isset($field['afterrow'])){
					$html .= '<p class="description '.esc_attr($field['id']).'-afterrow">'.esc_html($field['afterrow']).'</p>';
				}
				break;
		}
		$html .= '<input type="hidden" name="plwl-team-nonce" value="'. esc_attr( $plwl_nonce ) .'">';

		return apply_filters( "plwl_render_field_type", $html, $field, $value );

	}

	public function print_plwl_templates() {
		include 'plwl-js-templates.php';
	}

		public function pw_reg_function( ) { ?>
		<!-- layout selector -->
					<div id="pw_popupdiv" class="pw-template-popupdiv" style="display: none;">
				<?php
				$tempate_list = $this->pw_template_list();
				foreach ( $tempate_list as $key => $value ) {
					$classes = explode( ' ', $value['class'] );
					foreach ( $classes as $class ) {
						$all_class[] = $class;
					}
				}
				$count = array_count_values( $all_class );
				?>
				<ul class="pw_template_tab">
					<li class="pw_current_tab">
						<a href="#all"><?php esc_html_e( 'All', 'team-builder-showcase' ); ?></a>
					</li>
					<li>
						<a href="#free"><?php echo esc_html__( 'Free', 'team-builder-showcase' ) . ' (' . esc_attr( $count['free'] ) . ')'; ?></a>
					</li>
					<li>
						<a href="#grid"><?php echo esc_html__( 'Grid', 'team-builder-showcase' ) . ' (' . esc_attr( $count['grid'] ) . ')'; ?></a>
					</li>
				</ul>
				<?php
				echo '<div class="pw-template-cover">';
				foreach ( $tempate_list as $key => $value ) {
					if ( 'grid-design-01' === $key || 'grid-design-02' === $key || 'grid-design-03' === $key || 'grid-design-04' === $key || 'slider-design-01' === $key || 'slider-design-02' === $key || 'slider-design-03' === $key || 'slider-design-04' === $key) {
						$class = 'pw-lite';
					} else {
						$class = 'pw-pro';
					}
					?>
					<div class="pw-template-thumbnail <?php echo esc_attr( $value['class'] . ' ' . $class ); ?>">
						<div class="pw-template-thumbnail-inner">
							<img src="<?php echo esc_url( PLWL_TEAM_URL ) . 'assets/images/layouts/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
							<?php if ( 'pw-lite' === $class ) { ?>
								<div class="pw-hover_overlay">
									<div class="pw-popup-template-name">
										<div class="pw-popum-select"><a href="#"><?php esc_html_e( 'Select Template', 'team-builder-showcase' ); ?></a></div>
										<div class="pw-popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'team-builder-showcase' ); ?></a></div>
									</div>
								</div>
							<?php } else { ?>
								<div class="pw_overlay"></div>
								<div class="pw-img-hover_overlay">
									<img src="<?php echo esc_url( PLWL_TEAM_URL ) . 'assets/images/pro-tag.png'; ?>" alt="Available in Pro" />
								</div>
								<div class="pw-hover_overlay">
									<div class="pw-popup-template-name">
										<div class="pw-popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'team-builder-showcase' ); ?></a></div>
									</div>
								</div>
							<?php } ?>
						</div>
						<span class="pw-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
					</div>
					<?php
				}
				echo '</div>';
				echo '<h3 class="no-template" style="display: none;">' . esc_html__( 'No template found. Please try again', 'team-builder-showcase' ) . '</h3>';
				?>
			</div>
		<?php 
	}

	public static function pw_template_list() {
		$tempate_list = array(
			'grid-design-01'               => array(
				'template_name' => esc_html__( 'Grid Design 1', 'team-builder-showcase' ),
				'class'         => 'grid free',
				'image_name'    => 'design-1.jpg',
				'demo_link'     => esc_url( '#' ),
			),
			'grid-design-02'               => array(
				'template_name' => esc_html__( 'Grid Design 2', 'team-builder-showcase' ),
				'class'         => 'grid free',
				'image_name'    => 'design-2.jpg',
				'demo_link'     => esc_url( '#' ),
			),
			'grid-design-03'               => array(
				'template_name' => esc_html__( 'Grid Design 3', 'team-builder-showcase' ),
				'class'         => 'grid free',
				'image_name'    => 'design-3.jpg',
				'demo_link'     => esc_url( '#' ),
			),
			'grid-design-04'               => array(
				'template_name' => esc_html__( 'Grid Design 4', 'team-builder-showcase' ),
				'class'         => 'grid free',
				'image_name'    => 'design-4.jpg',
				'demo_link'     => esc_url( '#' ),
			),
		);
		return $tempate_list;
	}
}