<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/BobvD
 * @since      1.0.0
 *
 * @package    Wp_Ali_Info
 * @subpackage Wp_Ali_Info/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Ali_Info
 * @subpackage Wp_Ali_Info/includes
 * @author     Bob van Donselaar <b.vandonselaar@student.fontys.nl>
 */
class Wp_Ali_Info_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-ali-info',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
