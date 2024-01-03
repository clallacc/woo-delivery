<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dtod.com
 * @since             1.0.0
 * @package           dtod_Woo_Delivery
 *
 * @wordpress-plugin
 * Plugin Name:       Delivery & Pickup Date Time for WooCommerce
 * Description:       Delivery & Pickup Date Time for WooCommerce is a WooCommerce plugin extension that gives the facility of selecting delivery/pickup date and time on order checkout page. Moreover, you don't need to worry about the styling because the plugin adjusts with your WordPress theme.
 * Version:           1.3.60
 * Author:            dtod
 * Author URI:        https://dtod.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-delivery
 * Domain Path:       /languages
 * WC tested up to:   7.8
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(!defined("dtod_WOO_DELIVERY_DIR"))
    define("dtod_WOO_DELIVERY_DIR",plugin_dir_path(__FILE__));
if(!defined("dtod_WOO_DELIVERY_URL"))
    define("dtod_WOO_DELIVERY_URL",plugin_dir_url(__FILE__));
if(!defined("dtod_WOO_DELIVERY"))
    define("dtod_WOO_DELIVERY",plugin_basename(__FILE__));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'dtod_WOO_DELIVERY_VERSION', '1.3.60' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dtod-woo-delivery-activator.php
 */
function activate_dtod_woo_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dtod-woo-delivery-activator.php';
	dtod_Woo_Delivery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dtod-woo-delivery-deactivator.php
 */
function deactivate_dtod_woo_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dtod-woo-delivery-deactivator.php';
	dtod_Woo_Delivery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dtod_woo_delivery' );
register_deactivation_hook( __FILE__, 'deactivate_dtod_woo_delivery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dtod-woo-delivery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dtod_woo_delivery() {

	$plugin = new dtod_Woo_Delivery();
	$plugin->run();

}
run_dtod_woo_delivery();

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

