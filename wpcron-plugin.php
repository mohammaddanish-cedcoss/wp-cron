<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Wpcron_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       wpcron plugin
 * Plugin URI:        https://makewebbetter.com/product/wpcron-plugin/
 * Description:       Your Basic Plugin
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       wpcron-plugin
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      4.9.5
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Define plugin constants.
 *
 * @since             1.0.0
 */
function define_wpcron_plugin_constants() {

	wpcron_plugin_constants( 'WPCRON_PLUGIN_VERSION', '1.0.0' );
	wpcron_plugin_constants( 'WPCRON_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
	wpcron_plugin_constants( 'WPCRON_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
	wpcron_plugin_constants( 'WPCRON_PLUGIN_SERVER_URL', 'https://makewebbetter.com' );
	wpcron_plugin_constants( 'WPCRON_PLUGIN_ITEM_REFERENCE', 'wpcron plugin' );
}


/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since             1.0.0
 */
function wpcron_plugin_constants( $key, $value ) {

	if ( ! defined( $key ) ) {

		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpcron-plugin-activator.php
 */
function activate_wpcron_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcron-plugin-activator.php';
	Wpcron_Plugin_Activator::wpcron_plugin_activate();
	$mwb_wp_active_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_wp_active_plugin ) && ! empty( $mwb_wp_active_plugin ) ) {
		$mwb_wp_active_plugin['wpcron-plugin'] = array(
			'plugin_name' => __( 'wpcron plugin', 'wpcron-plugin' ),
			'active' => '1',
		);
	} else {
		$mwb_wp_active_plugin = array();
		$mwb_wp_active_plugin['wpcron-plugin'] = array(
			'plugin_name' => __( 'wpcron plugin', 'wpcron-plugin' ),
			'active' => '1',
		);
	}
	update_option( 'mwb_all_plugins_active', $mwb_wp_active_plugin );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpcron-plugin-deactivator.php
 */
function deactivate_wpcron_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcron-plugin-deactivator.php';
	Wpcron_Plugin_Deactivator::wpcron_plugin_deactivate();
	$mwb_wp_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_wp_deactive_plugin ) && ! empty( $mwb_wp_deactive_plugin ) ) {
		foreach ( $mwb_wp_deactive_plugin as $mwb_wp_deactive_key => $mwb_wp_deactive ) {
			if ( 'wpcron-plugin' === $mwb_wp_deactive_key ) {
				$mwb_wp_deactive_plugin[ $mwb_wp_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'mwb_all_plugins_active', $mwb_wp_deactive_plugin );
}

register_activation_hook( __FILE__, 'activate_wpcron_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_wpcron_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpcron-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpcron_plugin() {
	define_wpcron_plugin_constants();

	$wp_plugin_standard = new Wpcron_Plugin();
	$wp_plugin_standard->wp_run();
	$GLOBALS['wp_mwb_wp_obj'] = $wp_plugin_standard;

}
run_wpcron_plugin();

// Add rest api endpoint for plugin.
add_action( 'rest_api_init', 'wp_add_default_endpoint' );

/**
 * Callback function for endpoints.
 *
 * @since    1.0.0
 */
function wp_add_default_endpoint() {
	register_rest_route(
		'wp-route',
		'/wp-dummy-data/',
		array(
			'methods'  => 'POST',
			'callback' => 'mwb_wp_default_callback',
			'permission_callback' => 'mwb_wp_default_permission_check',
		)
	);
}

/**
 * API validation
 * @param 	Array 	$request 	All information related with the api request containing in this array.
 * @since    1.0.0
 */
function mwb_wp_default_permission_check($request) {

	// Add rest api validation for each request.
	$result = true;
	return $result;
}

/**
 * Begins execution of api endpoint.
 *
 * @param   Array $request    All information related with the api request containing in this array.
 * @return  Array   $mwb_wp_response   return rest response to server from where the endpoint hits.
 * @since    1.0.0
 */
function mwb_wp_default_callback( $request ) {
	require_once WPCRON_PLUGIN_DIR_PATH . 'includes/class-wpcron-plugin-api-process.php';
	$mwb_wp_api_obj = new Wpcron_Plugin_Api_Process();
	$mwb_wp_resultsdata = $mwb_wp_api_obj->mwb_wp_default_process( $request );
	if ( is_array( $mwb_wp_resultsdata ) && isset( $mwb_wp_resultsdata['status'] ) && 200 == $mwb_wp_resultsdata['status'] ) {
		unset( $mwb_wp_resultsdata['status'] );
		$mwb_wp_response = new WP_REST_Response( $mwb_wp_resultsdata, 200 );
	} else {
		$mwb_wp_response = new WP_Error( $mwb_wp_resultsdata );
	}
	return $mwb_wp_response;
}


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpcron_plugin_settings_link' );

/**
 * Settings link.
 *
 * @since    1.0.0
 * @param   Array $links    Settings link array.
 */
function wpcron_plugin_settings_link( $links ) {

	$my_link = array(
		'<a href="' . admin_url( 'admin.php?page=wpcron_plugin_menu' ) . '">' . __( 'Settings', 'wpcron-plugin' ) . '</a>',
	);
	return array_merge( $my_link, $links );
}
