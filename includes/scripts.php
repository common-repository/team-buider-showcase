<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_enqueue_scripts', 'plwl_enqueue_color_picker', 9);

/**
 * Enqueue colorpicket and chosen
 */
function plwl_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page.
   
        global $wp_version;
        wp_enqueue_style( array( 'wp-color-picker', 'wp-jquery-ui-dialog' ) );
        if ( function_exists( 'wp_enqueue_code_editor' ) ) {
            wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
        }
        wp_enqueue_script( 'pw-script-handle', PLWL_TEAM_URL . 'assets/js/admin-script.js', array( 'wp-color-picker', 'jquery-ui-core', 'jquery-ui-dialog' ), '1.0', true );
        wp_localize_script(
            'pw-script-handle',
            'pwlite_js',
            array(
                'wp_version'             => $wp_version,
                'nothing_found'          => esc_html__( 'Oops, nothing found!', 'team-builder-showcase' ),
                'reset_data'             => esc_html__( 'Do you want to reset data?', 'team-builder-showcase' ),
                'choose_plwl_template'   => esc_html__( 'Choose the Team Design you love', 'team-builder-showcase' ),
                'close'                  => esc_html__( 'Close', 'team-builder-showcase' ),
                'set_plwl_template'      => esc_html__( 'Select Team Design', 'team-builder-showcase' ),
                'no_template_exist'      => esc_html__( 'No template exist for selection', 'team-builder-showcase' ),
                'nonce'                  => wp_create_nonce( 'ajax-nonce' ),
            )
        );
}