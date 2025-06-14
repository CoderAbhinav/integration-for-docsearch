<?php
/**
 * Page class.
 *
 * @package IFD
 */

namespace IFD\Inc;

use IFD\Inc\Traits\Singleton;

class Page {

	use Singleton;

	/**
	 * Constant for menu slug.
	 */
	const MENU_SLUG = 'ifd-settings';

	/**
	 * Variable stores options.
	 */
	private $options;

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
		add_action( 'admin_menu', [ $this, 'add_options_page' ] );
	}

	/**
	 * Add options page.
	 *
	 * @return void
	 */
	public function add_options_page() {
		add_options_page(
			__( 'DocSearch Settings', 'ifd' ),
			__( 'DocSearch Configuration', 'ifd' ),
			'manage_options',
			self::MENU_SLUG,
			[ $this, 'render_options_page' ]
		);
	}

	/**
	 * Renders options page.
	 *
	 * @return void
	 */
	public function render_options_page() {
		$this->options = get_option( Settings::OPTION_NAME );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Search Integration for Algolia DocSearch', 'ifd' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( Settings::OPTION_GROUP );
				do_settings_sections( self::MENU_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
