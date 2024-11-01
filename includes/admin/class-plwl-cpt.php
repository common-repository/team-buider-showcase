<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The cpt plugin class.
 *
 * This is used to define the custom post type that will be used for galleries
 *
 * @since      1.0.0
 */
class PlWl_CPT {

	private $labels    = array();
	private $args      = array();
	private $metaboxes = array();
	private $cpt_name;
	private $builder;
	private $resizer;

	public function __construct() {

		$this->cpt_name = apply_filters( 'plwl_cpt_name', 'plwl-team' );

		add_action( 'init', array( $this, 'register_cpt' ) );
		//Bring the settings in Rest
		add_action( 'rest_api_init', array( $this, 'register_post_meta_rest') );

		/* Fire our meta box setup function on the post editor screen. */
		add_action( 'load-post.php', array( $this, 'meta_boxes_setup' ) );
		add_action( 'load-post-new.php', array( $this, 'meta_boxes_setup' ) );

		// Post Table Columns
		add_filter( "manage_{$this->cpt_name}_posts_columns", array( $this, 'add_columns' ) );
		add_action( "manage_{$this->cpt_name}_posts_custom_column", array( $this, 'outpu_column' ), 10, 2 );


		// Add the last visited tab to link
		add_filter( 'get_edit_post_link', array( $this, 'plwl_remember_tab' ), 2, 99 );
		add_action( 'wp_ajax_plwl_remember_tab', array( $this, 'plwl_remember_tab_save' ) );

		/* Load Fields Helper */
		require_once PLWL_TEAM_PATH . 'includes/admin/class-plwl-cpt-fields-helper.php';

		/* Load Builder */
		require_once PLWL_TEAM_PATH . 'includes/admin/class-plwl-field-builder.php';
		$this->builder = PlWl_Field_Builder::get_instance();

		/* Initiate Image Resizer */
		$this->resizer = new PlWl_Image();

		// Ajax for removing notices
		add_action( 'wp_ajax_plwl-edit-notice', array( $this, 'dismiss_edit_notice' ) );

	}

	public function register_cpt() {

		$this->labels = apply_filters( 'plwl_cpt_labels', array(
			'name'                  => esc_html__( 'All Teams', 'team-builder-showcase' ),
			'singular_name'         => esc_html__( 'Team Showcase', 'team-builder-showcase' ),
			'menu_name'             => esc_html__( 'Team Showcase', 'team-builder-showcase' ),
			'name_admin_bar'        => esc_html__( 'Team Showcase', 'team-builder-showcase' ),
			'archives'              => esc_html__( 'Item Archives', 'team-builder-showcase' ),
			'attributes'            => esc_html__( 'Item Attributes', 'team-builder-showcase' ),
			'parent_item_colon'     => esc_html__( 'Parent Item:', 'team-builder-showcase' ),
			'all_items'             => esc_html__( 'All Teams', 'team-builder-showcase' ),
			'add_new_item'          => esc_html__( 'Add New Team', 'team-builder-showcase' ),
			'add_new'               => esc_html__( 'Add New Team', 'team-builder-showcase' ),
			'new_item'              => esc_html__( 'New Team', 'team-builder-showcase' ),
			'edit_item'             => esc_html__( 'Edit Team', 'team-builder-showcase' ),
			'update_item'           => esc_html__( 'Update Team', 'team-builder-showcase' ),
			'view_item'             => esc_html__( 'View Team', 'team-builder-showcase' ),
			'view_items'            => esc_html__( 'View Teams', 'team-builder-showcase' ),
			'search_items'          => esc_html__( 'Search Team', 'team-builder-showcase' ),
			'not_found'             => '<a href="'.admin_url('post-new.php?post_type=plwl-team').'">'.esc_html__( 'Add Your First Team ','team-builder-showcase').'</a>',
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'team-builder-showcase' ),
			'featured_image'        => esc_html__( 'Featured Image', 'team-builder-showcase' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'team-builder-showcase' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'team-builder-showcase' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'team-builder-showcase' ),
			'insert_into_item'      => esc_html__( 'Insert into item', 'team-builder-showcase' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'team-builder-showcase' ),
			'items_list'            => esc_html__( 'Items list', 'team-builder-showcase' ),
			'items_list_navigation' => esc_html__( 'Items list navigation', 'team-builder-showcase' ),
			'filter_items_list'     => esc_html__( 'Filter items list', 'team-builder-showcase' ),
		), $this->labels );

		$this->args = apply_filters( 'plwl_cpt_args', array(
			'label'               => esc_html__( 'Team Builder Showcase', 'team-builder-showcase' ),
			'description'         => esc_html__( 'Description', 'team-builder-showcase' ),
			'supports'            => array( 'title' ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-insert',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'rewrite'             => false,
			'show_in_rest'        => true,
		) );

		$this->metaboxes = array(
			'plwl-preview-gallery' => array(
				'title'    => esc_html__( 'Gallery', 'team-builder-showcase' ),
				'callback' => 'output_gallery_images',
				'context'  => 'normal',
				'priority' => 10,
			),
			'plwl-team-settings'        => array(
				'title'    => esc_html__( 'Settings', 'team-builder-showcase' ),
				'callback' => 'output_gallery_settings',
				'context'  => 'normal',
				'priority' => 20,
			),
			'plwl-shortcode'       => array(
				'title'    => esc_html__( 'Shortcode', 'team-builder-showcase' ),
				'callback' => 'output_gallery_shortcode',
				'context'  => 'side',
				'priority' => 10,
			),
		);

		$args           = $this->args;
		$args['labels'] = $this->labels;

		register_post_type( $this->cpt_name, $args );

	}

	/**
	 * Rest field for plwl settings
	 *
	 * @since 2.5.0
	 */
	public function register_post_meta_rest() {
		register_rest_field( 'plwl-team-builder-showcase', 'plwlSettings', array(
			'get_callback' => function( $post_arr ) {
				return get_post_meta( $post_arr['id'], 'plwl-team-settings', true );
			},
		) );
	}

	public function meta_boxes_setup() {

		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );
	}

	public function add_meta_boxes() {

		global $post;
		$this->metaboxes = apply_filters( 'plwl_cpt_metaboxes', $this->metaboxes );

		// Sort tabs based on priority.
		uasort( $this->metaboxes, array( 'PlWl_Helper', 'sort_data_by_priority' ) );

		foreach ( $this->metaboxes as $metabox_id => $metabox ) {

			if ( 'plwl-shortcode' == $metabox_id && 'auto-draft' == $post->post_status ) {
				continue;
			}

			add_meta_box(
				$metabox_id,      // Unique ID
				$metabox['title'],    // Title
				array( $this, $metabox['callback'] ),   // Callback function
				'plwl-team',         // Admin page (or post type)
				$metabox['context'],         // Context
				'high'         // Priority
			);
		}

	}

	public function output_gallery_images() {
		$this->builder->render( 'gallery' );
	}

	public function output_gallery_settings() {
		$this->builder->render( 'settings' );
	}

	public function output_gallery_shortcode( $post ) {
		$this->builder->render( 'shortcode', $post );
	}

	public function save_meta_boxes( $post_id, $post ) {

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) || 'plwl-team' != $post_type->name ) {
			return $post_id;
		}

		if ( isset( $_POST['plwl-team-settings'] ) ) {
			$nonce = isset( $_POST['plwl-team-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['plwl-team-nonce'] ) ) : '';
			if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
			    die();
			}
			$fields_with_tabs = PlWl_CPT_Fields_Helper::get_fields( 'all' );

			// Here we will save all our settings
			$plwl_settings = array();

			// We will save only our settings.
			foreach ( $fields_with_tabs as $tab => $fields ) {

				// We will iterate through all fields of current tab
				foreach ( $fields as $field_id => $field ) {

					if ( isset( $_POST['plwl-team-settings'][$field_id] ) ) {
						if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
						    die();
						}
						// Values for selects
						$lightbox_values = apply_filters( 'plwl_lightbox_values', array( 'no-link', 'direct', 'fancybox', 'attachment-page' ) );

						switch ( $field_id ) {
							case 'description':
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								$plwl_settings[$field_id] = sanitize_text_field( $_POST['plwl-team-settings'][$field_id] );
								break;
							case 'randomFactor':
							case 'captionFontSize':
							case 'titleFontSize':
							case 'borderSize':
							case 'borderRadius':
							case 'shadowSize':
							case 'socialIconSize':
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								$plwl_settings[$field_id] = absint( $_POST['plwl-team-settings'][$field_id] );
								break;
							case 'lightbox' :
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								if ( in_array( $_POST['plwl-team-settings'][$field_id], $lightbox_values ) ) {
									$plwl_settings[$field_id] = sanitize_text_field( wp_unslash( $_POST['plwl-team-settings'][$field_id] ) );
								} else {
									$plwl_settings[$field_id] = 'fancybox';
								}
								break;
							case 'enableSocial':
							case 'enableTwitter' :
							case 'enableWhatsapp':
							case 'enableFacebook' :
							case 'enablePinterest' :
							case 'enableEmail':
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								$plwl_settings[$field_id] = sanitize_text_field( wp_unslash( $_POST['plwl-team-settings'][$field_id] ) );
								break;
							case 'imageMessage':
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								$plwl_settings[$field_id] = sanitize_text_field( wp_unslash( $_POST['plwl-team-settings'][$field_id] ) );
								break;
							case 'galleryMessage':
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								$plwl_settings[$field_id] = sanitize_text_field( wp_unslash( $_POST['plwl-team-settings'][$field_id] ) );
								break;
							case 'shuffle' :
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								if ( isset( $_POST['plwl-team-settings'][$field_id] ) ) {
									$plwl_settings[$field_id] = '1';
								} else {
									$plwl_settings[$field_id] = '0';
								}
								break;
							case 'captionColor':
							case 'socialIconColor':
							case 'borderColor':
							case 'shadowColor':
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}

								$raw_color = isset( $_POST['plwl-team-settings'][$field_id] ) ? sanitize_text_field(wp_unslash( $_POST['plwl-team-settings'][$field_id] )) : ''; // Unslash the raw input
								$sanitized_color = PlWl_Helper::sanitize_rgba_colour( $raw_color ); // Sanitize RGBA color
								$rgba_color = esc_attr( $sanitized_color );

								$sanitized_rgba_color = sanitize_hex_color( $rgba_color );
								$plwl_settings[$field_id] = $sanitized_rgba_color;
								break;
							case 'gutterInput' :
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								$plwl_settings[$field_id] = absint( $_POST['plwl-team-settings'][$field_id] );
								break;
							case 'height' :
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								$plwl_settings[$field_id] = array_map( 'absint', $_POST['plwl-team-settings'][$field_id] );
								break;
							default:
								if ( !wp_verify_nonce( $nonce, 'plwl_team_nonce_save_settings' ) ) {
								    die();
								}
								if ( is_array( $_POST['plwl-team-settings'][$field_id] ) ) {
									$sanitized = array_map( 'sanitize_text_field', wp_unslash( $_POST['plwl-team-settings'][$field_id] ) );
									$input_value = isset( $_POST['plwl-team-settings'][$field_id] ) ? sanitize_text_field( wp_unslash( $_POST['plwl-team-settings'][$field_id] ) ) : '';
									$sanitized_value = apply_filters('plwl_settings_field_sanitization', $sanitized, $input_value, $field_id, $field);
									$plwl_settings[$field_id] = $sanitized_value;
								} else {
									$input_value = isset( $_POST['plwl-team-settings'][$field_id] ) ? sanitize_text_field( wp_unslash( $_POST['plwl-team-settings'][$field_id] ) ) : '';
									$plwl_settings[$field_id] = apply_filters( 'plwl_settings_field_sanitization', sanitize_text_field( wp_unslash( $_POST['plwl-team-settings'][$field_id] ) ), $input_value, sanitize_key($field_id), sanitize_text_field($field) );
								}

								break;
						}

					} else {
						if ( 'toggle' == $field['type'] ) {
							$plwl_settings[$field_id] = '0';
						} else if ( 'hidden' == $field['type'] ) {

							$hidden_set = get_post_meta( $post_id, 'plwl-team-settings', true );
							if ( isset( $hidden_set['last_visited_tab'] ) && '' != $hidden_set['last_visited_tab'] ) {
								$plwl_settings[$field_id] = $hidden_set['last_visited_tab'];
							} else {
								$plwl_settings[$field_id] = 'plwl-general';
							}

						} else {
							$plwl_settings[$field_id] = '';
						}
					}

				}

			}			

			// Add settings to gallery meta
			update_post_meta( $post_id, 'plwl-team-settings', $plwl_settings );

		}

	}

	public function add_columns( $columns ) {

		$date = $columns['date'];
		unset( $columns['date'] );
		$columns['shortcode'] = esc_html__( 'Shortcode', 'team-builder-showcase' );
		$columns['date']      = $date;
		return $columns;

	}

	public function outpu_column( $column, $post_id ) {

		if ( 'shortcode' == $column ) {
			$shortcode = '[plwl-team id="' . $post_id . '"]';
			echo '<div class="duplicate-copy-shortcode">';
			echo '<input type="text" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
			echo '<a href="#" title="' . esc_attr__( 'Copy shortcode', 'team-builder-showcase' ) . '" class="copy-plwl-shortcode button button-primary dashicons dashicons-format-gallery" style="width:40px;"></a><span></span>';
			echo '</div>';
		}

	}

	public function dismiss_edit_notice() {

		$plwl_options                = get_option( 'plwl-checks', array() );
		$plwl_options['edit-notice'] = 1;
		update_option( 'plwl-checks', $plwl_options );
		die( '1' );

	}

	/**
	 * Add the last visited settings tab to edit link
	 *
	 * @param $link
	 * @param $id
	 *
	 * @return string
	 * @since 2.4.0
	 */
	public function plwl_remember_tab( $link, $id ) {

		if ( 'plwl-team' != get_post_type( $id ) ) {
			return $link;
		}

		$settings = get_post_meta( $id, 'plwl-team-settings', true );

		if ( isset( $settings['last_visited_tab'] ) && '' != $settings['last_visited_tab'] ) {
			$link = $link . '#!' . $settings['last_visited_tab'];
		} else {
			$link = $link . '#!plwl-general';
		}

		return $link;
	}

	/**
	 * Save the tab
	 */
	public function plwl_remember_tab_save() {

		$nonce = sanitize_text_field( $_POST['nonce'] );
		if ( !wp_verify_nonce( $nonce, 'plwl-ajax-save' ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		// Check if post exists and is plwl-team-builder-showcase CPT
		if ( ! get_post_type( $id ) || 'plwl-team' !== get_post_type( $id ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$settings                     = wp_parse_args( get_post_meta( $id, 'plwl-team-settings', true ), PlWl_CPT_Fields_Helper::get_defaults() );
		$settings['last_visited_tab'] = isset( $_POST['tab'] ) ? sanitize_text_field( wp_unslash( $_POST['tab'] ) ) : '';

		update_post_meta( $id, 'plwl-team-settings', $settings );
		die();

	}
}