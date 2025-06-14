<?php
/**
 * Assets class.
 *
 * @package IFD
 */

namespace IFD\Inc;

use IFD\Inc\Traits\Singleton;

class Assets {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		/**
		 * Action
		 */
	}
}
