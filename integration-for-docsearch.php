<?php
/*
 * Plugin Name:       Integration for DocSearch
 * Plugin URI:        https://github.com/coderabhinav/integration-for-docsearch
 * Description:       Easily integrate Algolia DocSearch with your WordPress site using a block, shortcode, or automatic search form replacement.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abhinav Belhekar
 * Author URI:        https://abhinavbelhekar.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       integration-for-docsearch
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! defined( 'IFD_VERSION' ) ) {
	/**
	 * Currently plugin version.
	 *
	 * @see https://semver.org
	 */
	define( 'IFD_VERSION', '1.0.0' );
}

if ( ! defined( 'IFD_PLUGIN_DIR' ) ) {
	/**
	 * Plugin directory path.
	 *
	 * @see https://developer.wordpress.org/reference/functions/plugin_dir_path/
	 */
	define( 'IFD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'IFD_PLUGIN_URL' ) ) {
	/**
	 * Plugin directory URL.
	 *
	 * @see https://developer.wordpress.org/reference/functions/plugin_dir_url/
	 */
	define( 'IFD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Loading autoloader, and custom functions.
 */
require_once IFD_PLUGIN_DIR . 'inc/helpers/autoloader.php';
require_once IFD_PLUGIN_DIR . 'inc/helpers/custom-functions.php';

/**
 * Initialize the plugin.
 *
 * @return void
 */
function ifd_init() {
	\IFD\Inc\Plugin::get_instance();
}

ifd_init();
