<?php
/**
 * Settings class.
 *
 * @package IFD
 */

namespace IFD\Inc;

use IFD\Inc\Traits\Singleton;

class Settings {

	use Singleton;

	/**
	 * Options group name.
	 */
	const OPTION_GROUP = 'ifd_option_group';

	/**
	 * Options name.
	 */
	const OPTION_NAME   = 'ifd_options';



	/**
	 * Construct method.
	 */
	public function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	public function setup_hooks() {
		/**
		 * Actions.
		 */
		add_action( 'admin_init', [ $this, 'add_settings' ] );
	}

	/**
	 * Registers the settings.
	 *
	 * @return void
	 */
	public function add_settings() {
		register_setting(
			self::OPTION_GROUP,
			self::OPTION_NAME
		);
	}
}
