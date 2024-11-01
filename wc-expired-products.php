<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              nicogaldo.com.ar
 * @since             1.0.0
 * @package           Wc_Expired_Products
 *
 * @wordpress-plugin
 * Plugin Name:       WC Expired Products
 * Plugin URI:        devacid.com/plugins
 * Description:       Defines a expiration time to products and send an email notifying the author.
 * Version:           1.0.0
 * Author:            Nicolas Galdo
 * Author URI:        nicogaldo.com.ar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-expired-products
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-expired-products-activator.php
 */
function activate_wc_expired_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-expired-products-activator.php';
	Wc_Expired_Products_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-expired-products-deactivator.php
 */
function deactivate_wc_expired_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-expired-products-deactivator.php';
	Wc_Expired_Products_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_expired_products' );
register_deactivation_hook( __FILE__, 'deactivate_wc_expired_products' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-expired-products.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_expired_products() {

	$plugin = new Wc_Expired_Products();
	$plugin->run();

}
run_wc_expired_products();
