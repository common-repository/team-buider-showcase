<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 *
 */
class PlWl_Admin {

	private $tabs;
	private $menu_links;
	private $version     = '1.0.0';
	private $current_tab = 'general';

	function __construct() {

		// Show general tab
		add_action( 'plwl_admin_tab_general', array( $this, 'show_general_tab' ) );

		add_action( 'wp_ajax_plwl_save_images', array( $this, 'save_images' ) );
		add_action( 'wp_ajax_plwl_save_image', array( $this, 'save_image' ) );
		add_action( 'plwl_scripts_before_wp', array( $this, 'add_autosuggest_scripts' ) );
		add_action( 'wp_ajax_plwl_autocomplete', array( $this, 'autocomplete_url' ) );
		add_action( 'delete_attachment', array( $this, 'delete_resized_image' ) );

		add_action( 'wp_ajax_plwl_lbu_notice', array( $this, 'plwl_lbu_notice' ) );
		add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );

	}

	public function delete_resized_image( $post_id ) {

		$post = get_post( $post_id );

		if ( 'attachment' !== $post->post_type ) {
			return false;
		}

		// Get the metadata
		$metadata = wp_get_attachment_metadata( $post_id );
		if ( ! $metadata ) {
			return;
		}
		$info     = pathinfo( $metadata['file'] );
		$uploads  = wp_upload_dir();
		$filename = $info['filename'];
		$file_dir = $uploads['basedir'] . '/' . $info['dirname'];
		$ext      = $info['extension'];

		if ( ! isset( $metadata['image_meta']['resized_images'] ) ) {
			return;
		}

		if ( count( $metadata['image_meta']['resized_images'] ) > 0 ) {

			foreach ( $metadata['image_meta']['resized_images'] as $value ) {
				$size = '-' . $value;

				// Format the files in the appropriate format
				$file = $file_dir . '/' . $filename . $size . '.' . $ext;
				// Delete found files
				wp_delete_file_from_directory( $file, $file_dir );

			}
		}

	}

	public function show_general_tab() {
		include 'tabs/general.php';
	}

	private function sanitize_image( $image ) {

		$new_image = array();

		// This list will not contain id because we save our images based on image id.
		$image_attributes = apply_filters(
			'plwl_gallery_image_attributes',
			array(
				'id',
				'alt',
				'title',
				'description',
				'halign',
				'valign',
				'fburl',
				'twturl',
				'lnkdnurl',
				'instanurl',
				'emailurl',
				'link',
				'target',
				'width',
				'height',
				'togglelightbox',
			)
		);

		foreach ( $image_attributes as $attribute ) {
			if ( isset( $image[ $attribute ] ) ) {

				switch ( $attribute ) {
					case 'alt':
						$new_image[ $attribute ] = sanitize_text_field( $image[ $attribute ] );
						break;
					case 'width':
					case 'height':
						$new_image[ $attribute ] = absint( $image[ $attribute ] );
						break;
					case 'title':
					case 'description':
						$new_image[ $attribute ] = wp_filter_post_kses( $image[ $attribute ] );
						break;
					case 'link':
						$new_image[ $attribute ] = esc_url_raw( $image[ $attribute ] );
						break;
					case 'target':
						if ( isset( $image[ $attribute ] ) ) {
							$new_image[ $attribute ] = absint( $image[ $attribute ] );
						} else {
							$new_image[ $attribute ] = 0;
						}
						break;
					case 'togglelightbox':
						if ( isset( $image[ $attribute ] ) ) {
							$new_image[ $attribute ] = absint( $image[ $attribute ] );
						} else {
							$new_image[ $attribute ] = 0;
						}
						break;
					case 'halign':
						if ( in_array( $image[ $attribute ], array( 'left', 'right', 'center' ) ) ) {
							$new_image[ $attribute ] = $image[ $attribute ];
						} else {
							$new_image[ $attribute ] = 'center';
						}
						break;
					case 'valign':
						if ( in_array( $image[ $attribute ], array( 'top', 'bottom', 'middle' ) ) ) {
							$new_image[ $attribute ] = $image[ $attribute ];
						} else {
							$new_image[ $attribute ] = 'middle';
						}
						break;
					default:
						$new_image[ $attribute ] = apply_filters( 'plwl_image_field_sanitization', sanitize_text_field( $image[ $attribute ] ), $image[ $attribute ], $attribute );
						break;
				}
			} else {
				$new_image[ $attribute ] = '';
			}
		}

		return $new_image;

	}

	public function save_images() {

		$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'plwl-ajax-save' ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
			die();
		}

		if ( ! isset( $_POST['gallery'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$gallery_id = absint( $_POST['gallery'] );

		if ( 'plwl-team' != get_post_type( $gallery_id ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		if ( ! isset( $_POST['images'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$old_images = get_post_meta( $gallery_id, 'plwl-team-images', true );
		$raw_images = isset($_POST['images']) ? sanitize_text_field(stripslashes($_POST['images'])) : '';
		$images = json_decode($raw_images, true);
		$new_images = array();

		if ( is_array( $images ) ) {
			foreach ( $images as $image ) {
				$new_images[] = $this->sanitize_image( $image );
			}
		}

		update_post_meta( $gallery_id, 'plwl-team-images', $new_images );
		wp_send_json( array( 'status' => 'succes' ) );

	}

	public function save_image() {
		$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( $_POST['_wpnonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'plwl-ajax-save' ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
			die();
		}

		if ( ! isset( $_POST['gallery'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$gallery_id = absint( $_POST['gallery'] );

		if ( 'plwl-team' != get_post_type( $gallery_id ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		if ( ! isset( $_POST['image'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$image = json_decode( stripslashes( sanitize_text_field( wp_unslash( $_POST['image'] ) ) ), true );
		$old_images = get_post_meta( $gallery_id, 'plwl-team-images', true );

		foreach ( $old_images as $key => $old_image ) {
			if ( $old_image['id'] == $image['id'] ) {
				$old_images[ $key ] = $this->sanitize_image( $image );
			}
		}

		update_post_meta( $gallery_id, 'plwl-team-images', $old_images );
		wp_send_json( array( 'status' => 'succes' ) );

	}

	/**
	 * Update plwl-checks option for lightbox upgrade notice 1
	 *
	 * @since 2.3.0
	 */
	public function plwl_lbu_notice() {

		$nonce = '';
		
		if( isset( $_POST['nonce'] ) ){
			$nonce = sanitize_text_field( $_POST['nonce'] );
		}


		if ( ! wp_verify_nonce( $nonce, 'plwl-ajax-save' ) ) {
			wp_send_json_error();
			die();
		}

		$plwl_checks               = get_option( 'plwl-checks', array() );
		$plwl_checks['lbu_notice'] = '1';

		update_option( 'plwl-checks', $plwl_checks );
		wp_die();

	}

	/**
	 * Enqueue jQuery autocomplete script
	 *
	 * /@since 2.3.2
	 */
	public function add_autosuggest_scripts() {

		wp_enqueue_script( 'jquery-ui-autocomplete' );

	}

	public function autocomplete_url() {

		$nonce = sanitize_text_field( $_GET['nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'plwl-ajax-save' ) ) {
			die();
		}

		$suggestions = array();
		$term        = sanitize_text_field( $_GET['term'] );

		$loop = new WP_Query( 's=' . $term );
		while ( $loop->have_posts() ) {
			$loop->the_post();
			$suggestion['label'] = get_the_title();
			$suggestion['type']  = get_post_type();
			$suggestion['value'] = get_permalink();
			$suggestions[]       = $suggestion;
		}

		echo esc_html( wp_json_encode( $suggestions ) );
		exit();
	}

	public function add_body_class( $classes ){
		$screen = get_current_screen();

		if ( 'plwl-team' != $screen->post_type ) {
			return $classes;
		}

		if ( 'post' != $screen->base ) {
			return $classes;
		}

		$classes .= ' single-plwl-team-builder-showcase';
		return $classes;

	}

}

new PlWl_Admin();