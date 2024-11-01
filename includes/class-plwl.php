<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 */
class PlWl {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {

		require_once PLWL_TEAM_PATH . 'includes/helper/class-plwl-helper.php';
		require_once PLWL_TEAM_PATH . 'includes/admin/class-plwl-image.php';

		require_once PLWL_TEAM_PATH . 'includes/admin/class-plwl-cpt.php';
		
		require_once PLWL_TEAM_PATH . 'includes/admin/class-plwl-admin.php';

		require_once PLWL_TEAM_PATH . 'includes/public/class-plwl-shortcode.php';

		require_once PLWL_TEAM_PATH . 'includes/scripts.php';

	}

	public function set_locale() {
		load_plugin_textdomain( 'team-builder-showcase', false, dirname( PLWL_TEAM_FILE ) . '/languages'  );
	}

	private function define_admin_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
		add_action( 'init', array( $this, 'admin_init' ), 20 );

		add_action( 'init', array( $this, 'set_locale' ) );

		// Classic editor button for Plwl Gallery
		add_filter( 'mce_buttons', array( $this, 'editor_button' ) );
		add_filter( 'mce_external_plugins', array( $this, 'register_editor_plugin' ) );
		add_action( 'wp_ajax_plwl_shortcode_editor', array( $this, 'plwl_shortcode_editor' ) );

		// Allow other mime types to be uploaded
		add_filter( 'upload_mimes', array( $this, 'plwl_upload_mime_types' ) );
		add_filter( 'file_is_displayable_image', array( $this, 'plwl_webp_display' ), 10, 2 );

		// Initiate plwl cpts
		new PlWl_CPT();

	}

	public function admin_init() {

		if ( ! is_admin() ) {
			return;
		}

		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		   return;
		}

	}

	private function define_public_hooks() {

	}

	/* Enqueue Admin Scripts */
	public function admin_scripts( $hook ) {

		global $id, $post;

		// Get current screen.
		$screen = get_current_screen();

		// Set the post_id
		$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

		$plwl_helper = array(
			'items'    => array(),
			'settings' => array(),
			'strings'  => array(
				'limitExceeded' => '',
			),
			'id'       => $post_id,
			'_wpnonce' => wp_create_nonce( 'plwl-ajax-save' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		);

		if ( 'post-new.php' == $hook || 'post.php' == $hook ) {

			 // Check if is plwl custom post type
			if ( 'plwl-team' !== $screen->post_type ) {
				return;
			}

			if ( apply_filters( 'plwl_disable_drag_cpt_box', true ) ) {

				//returns plwl CPT metaboxes to the default position.
				add_filter('get_user_option_meta-box-order_plwl-team-builder-showcase', '__return_empty_string');
				add_filter('get_user_option_closedpostboxes_plwl-team-builder-showcase', '__return_empty_string');
				add_filter( 'admin_body_class', array( $this, 'no_drag_classes' ), 15, 1 );
			}

			/*
			 CPT Styles & Scripts */
			// Media Scripts
			wp_enqueue_media(
				array(
					'post' => $post_id,
				)
			);

			// Get all items from current gallery.
			$images = get_post_meta( $post_id, 'plwl-team-images', true );

			if ( is_array( $images ) && ! empty( $images ) ) {
				foreach ( $images as $image ) {
					if ( ! is_numeric( $image['id'] ) || 'attachment' !== get_post_type( $image['id'] ) ) {
						continue;
					}

					$attachment = wp_prepare_attachment_for_js( $image['id'] );
					$image_url  = wp_get_attachment_image_src( $image['id'], 'large' );
					$image_full = wp_get_attachment_image_src( $image['id'], 'full' );

					$image['full']        = $image_full[0];
					$image['thumbnail']   = $image_url[0];
					$image['orientation'] = $attachment['orientation'];

					$plwl_helper['items'][] = apply_filters( 'plwl_image_properties', $image );

				}
			}

			// Get current gallery settings.
			$settings = get_post_meta( $post_id, 'plwl-team-settings', true );
			$settings = apply_filters( 'plwl_backbone_settings', $settings );

			if ( is_array( $settings ) ) {
				$plwl_helper['settings'] = wp_parse_args( $settings, PlWl_CPT_Fields_Helper::get_defaults() );
			} else {
				$plwl_helper['settings'] = PlWl_CPT_Fields_Helper::get_defaults();
			}

			wp_enqueue_style( 'wp-color-picker' );
			// Enqueue Code Editor for Custom CSS
			wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
			wp_enqueue_style( 'plwl-cpt-style', PLWL_TEAM_URL . 'assets/css/admin/plwl-cpt.css', null, PLWL_TEAM_CURRENT_VERSION );

			wp_enqueue_script( 'plwl-resize-senzor', PLWL_TEAM_URL . 'assets/js/admin/resizesensor.js', array( 'jquery' ), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-packery', PLWL_TEAM_URL . 'assets/js/admin/packery.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-draggable' ), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-team-settings', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl-settings.js', array( 'jquery', 'jquery-ui-slider', 'wp-color-picker', 'jquery-ui-sortable' ), PLWL_TEAM_CURRENT_VERSION, true );

			wp_enqueue_script( 'plwl-save', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl-save.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-items', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl-items.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-modal', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl-modal.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-upload', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl-upload.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-team-builder-showcase', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl-gallery.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-conditions', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl-conditions.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
			wp_enqueue_script( 'plwl-custom-js', PLWL_TEAM_URL . 'assets/js/custom.js', array(), PLWL_TEAM_CURRENT_VERSION, true ); 

			do_action( 'plwl_scripts_before_wp' );

			wp_enqueue_script( 'plwl', PLWL_TEAM_URL . 'assets/js/admin/wp-plwl.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
			$plwl_helper = apply_filters( 'plwl_helper_properties', $plwl_helper );
			wp_localize_script( 'plwl', 'teamBuilderShowcaseHelper', $plwl_helper );

			do_action( 'plwl_scripts_after_wp' );

		} 

		wp_enqueue_script( 'plwl-edit-screen', PLWL_TEAM_URL . 'assets/js/admin/plwl-edit.js', array(), PLWL_TEAM_CURRENT_VERSION, true );
		wp_localize_script( 'plwl-edit-screen', 'teamBuilderShowcaseHelper', $plwl_helper );
		wp_enqueue_style( 'plwl-edit-style', PLWL_TEAM_URL . 'assets/css/admin/edit.css', null, PLWL_TEAM_CURRENT_VERSION );

	}	


	/**
	 * @param $buttons
	 * @return mixed
	 *
	 * Add tinymce button
	 */
	public function editor_button( $buttons ) {
		array_push( $buttons, 'separator', 'plwl_shortcode_editor' );
		return $buttons;
	}

	/**
	 * @param $plugin_array
	 * @return mixed
	 *
	 * Add plugin editor script
	 */
	public function register_editor_plugin( $plugin_array ) {
		$plugin_array['plwl_shortcode_editor'] = PLWL_TEAM_URL . 'assets/js/admin/editor-plugin.js';
		return $plugin_array;
	}

	/**
	 * Display galleries selection
	 */
	public function plwl_shortcode_editor() {
		$css_path  = PLWL_TEAM_URL . 'assets/css/admin/edit.css';
		$admin_url = admin_url();
		$galleries = PlWl_Helper::get_galleries();
		wp_die();

	}

	/**
	 * @param $mimes
	 *
	 * @return mixed
	 *
	 * @since 2.2.4
	 * Allow WebP image type to be uploaded
	 */
	public function plwl_upload_mime_types( $mimes ) {

		$mimes['webp'] = 'image/webp';

		return $mimes;
	}

	/**
	 * @param $result
	 * @param $path
	 *
	 * @return bool
	 *
	 * @since 2.2.4
	 * Enable thumbnail/preview for WebP image types.
	 */
	function plwl_webp_display( $result, $path ) {
		if ( $result === false && IMAGETYPE_WEBP) {
			$displayable_image_types = array( IMAGETYPE_WEBP );
			$info                    = @getimagesize( $path );

			if ( empty( $info ) ) {
				$result = false;
			} elseif ( ! in_array( $info[2], $displayable_image_types ) ) {
				$result = false;
			} else {
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * Add the `plwl-no-drag` class to body so we know when to hide the arrows
	 * @param String $classes The classes of the body, a space-separated string of class names instead of an array
	 * @return String
	 * 
	 * @since 2.6.3
	 */
	public function no_drag_classes( $classes ) {

		$classes .= ' plwl-no-drag';

		return $classes;
	}

}