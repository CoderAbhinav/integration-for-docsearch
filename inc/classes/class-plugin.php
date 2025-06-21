<?php
/**
 * Plugin manifest class.
 *
 * @package IFD
 */

namespace IFD\Inc;

use IFD\Inc\Traits\Singleton;

class Plugin {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		Assets::get_instance();
		Settings::get_instance();
		Page::get_instance();
	}
}
