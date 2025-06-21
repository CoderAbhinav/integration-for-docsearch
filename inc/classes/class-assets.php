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
		$this->register_scripts();
		$this->register_styles();
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
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Registers the scripts.
	 *
	 * @return void
	 */
	public function register_scripts() {
		$frontend_script_asset_file = include IFD_PLUGIN_DIR . '/assets/build/scripts/frontend.min.asset.php';

		wp_register_script(
			'ifd-frontend-script',
			IFD_PLUGIN_URL . '/assets/build/scripts/frontend.min.js',
			array( 'ifd-docsearch-js', ...$frontend_script_asset_file[ 'dependencies' ] ),
			$frontend_script_asset_file[ 'version' ]
		);

		wp_register_script(
			'ifd-docsearch-js',
			'https://cdn.jsdelivr.net/npm/@docsearch/js@3',
			array(),
		);
	}

	/**
	 * Registers the styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		$frontend_script_asset_file = include IFD_PLUGIN_DIR . '/assets/build/scripts/frontend.min.asset.php';

		wp_register_style(
			'ifd-frontend-style',
			IFD_PLUGIN_URL . '/assets/build/scripts/style-frontend.css',
			array(),
			$frontend_script_asset_file[ 'version' ]
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'ifd-frontend-script' );
		wp_enqueue_style( 'ifd-frontend-style' );
	}
}
