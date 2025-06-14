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
	 * Constant for settings section id.
	 */
	const SETTINGS_SECTION_ID = 'ifd-settings-section';

	/**
	 * Variable stores options.
	 */
	private $options;

	/**
	 * Fields to show in the options page.
	 */
	private $fields;

	/**
	 * Construct method.
	 */
	public function __construct() {
		$this->fields = Settings::fields();
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
		add_action( 'admin_init', [ $this, 'add_settings_section' ] );
		add_action( 'admin_init', [ $this, 'add_settings_fields' ] );
		add_action( 'admin_menu', [ $this, 'add_options_page' ] );
	}

	public function add_settings_section() {
		add_settings_section(
			self::SETTINGS_SECTION_ID,
			__( 'DocSearch Configuration', 'ifd' ),
			null,
			self::MENU_SLUG
		);
	}

	public function add_settings_fields() {

		foreach( $this->fields as $key => $field ) {
			add_settings_field(
				$key,
				$field[ 'label' ],
				[ $this, 'render_field' ],
				self::MENU_SLUG,
				self::SETTINGS_SECTION_ID,
				[ 'key' => $key, 'field' => $field ],
			);
		}
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

	public function render_field( $args ) {
		$key      = $args['key'];
		$field    = $args['field'];
		$constant = $field['constant'];
		$type     = $field['type'];

		if ( defined( $constant ) ) {
			?>
			<input type="<?php echo esc_attr( $type ); ?>" disabled value="<?php echo esc_attr( constant( $constant ) ); ?>" class="regular-text" />
			<p class="description">
				<?php
				echo sprintf(
					/* translators: %s: The name of the file (e.g., wp-config.php). */
					__( 'Defined in %s: %s', 'your-text-domain' ),
					'<code>wp-config.php</code>',
					$field['description']
				);
				?>
			</p>
			<?php
		} else {
			$default = isset( $field['default'] ) ? $field['default'] : '';
			$value   = isset( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
			$input_name = Settings::OPTION_NAME . '[' . $key . ']';

			if ( $type === 'checkbox' ) {
				?>
				<input type="checkbox" name="<?php echo esc_attr( $input_name ); ?>" value="1" <?php echo checked( $value, 1, false ); ?>/>
				<?php
			} else {
				?>
				<input type="<?php echo esc_attr( $type ) ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text"/>
				<?php
			}
			?>
			<p class="description"><?php echo esc_html( $field['description'] ) ?></p>
			<?php
		}

	}
}
