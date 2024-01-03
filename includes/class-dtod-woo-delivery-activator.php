<?php

/**
 * Fired during plugin activation
 *
 * @link       https://dtod.com
 * @since      1.0.0
 *
 * @package    dtod_Woo_Delivery
 * @subpackage dtod_Woo_Delivery/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    dtod_Woo_Delivery
 * @subpackage dtod_Woo_Delivery/includes
 * @author     dtod <admin@dtod.com>
 */
class dtod_Woo_Delivery_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option('dtod-woo-delivery-activation-time',time());
	}

}
