<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       nicogaldo.com.ar
 * @since      1.0.0
 *
 * @package    Wc_Expired_Products
 * @subpackage Wc_Expired_Products/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Expired_Products
 * @subpackage Wc_Expired_Products/includes
 * @author     Nicolas Galdo <soporte@devacid.com>
 */
class Wc_Expired_Products_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-expired-products',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
