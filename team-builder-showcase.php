<?php
/**
* Plugin Name: 				Team Builder Showcase
* Plugin URI: 				#
* Description: 				The Team WordPress plugin is your go-to solution for effortlessly showcasing your team members' profiles. With easy-to-use shortcodes, you can create stylish grids or sliders to highlight your team in no time.
* Version: 					1.0.5
* Author: 					Plugin Wale
* Author URI: 				https://profiles.wordpress.org/pluginwale/
* License: 					GPLv3 or later
* License URI:         		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 	    	5.6
* Text Domain: 				team-builder-showcase
* Domain Path: 				/languages
*
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define Constants
 */

define( 'PLWL_TEAM_CURRENT_VERSION' , '1.0.5' );
define( 'PLWL_TEAM_PATH' , plugin_dir_path( __FILE__ ) );
define( 'PLWL_TEAM_URL' , plugin_dir_url( __FILE__ ) );
define( 'PLWL_TEAM_FILE' , plugin_basename( __FILE__ ) );
define( 'PLWL_TEAM_ASSETS_PATH'    , PLWL_TEAM_URL . 'assets/' );
define( 'PLWL_TEAM_INCLUDES_PATH'  , PLWL_TEAM_PATH  . 'includes' . DIRECTORY_SEPARATOR );

define ( 'PLWL_TEAM_LITE_TRANSLATE', dirname( plugin_basename( __FILE__ ) ) . '/languages' );


/**
 * The code that runs during plugin activation.
 */
function plwl_team_activate() {
	
}

register_activation_hook( __FILE__, 'plwl_team_activate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plwl.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function plwl_team_run() {

	// Our core class
	$plugin = new PlWl();

}

plwl_team_run();