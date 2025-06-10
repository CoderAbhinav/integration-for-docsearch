<?php

namespace IFD\Inc;

class Ifd_options {

	const PAGE_SLUG = 'integration-for-docsearch';
	
	/**
	 * Option group name for settings API.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_setting/#parameters
	 * @var string
	 */
	const OPTIONS_GROUP = 'ifd_docsearch_options';

	/**
	 * The primary option name in the wp_options table. All settings are stored in this single array.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_setting/#parameters
	 * @var string
	 */
	const OPTION_NAME = 'ifd_docsearch_settings';

	/**
	 * Constructor to initialize the options page class.
	 */
	public function __construct() {
		// Setup hooks for the options page.
		$this->setup_hooks();
	}

	private function setup_hooks() {
		// Actions.
		add_action( 'admin_menu', [ $this, 'add_options_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}


	/**
	 * Registers the options page in the WordPress admin menu.
	 *
	 * @return void
	 */
	public function add_options_page() {
		add_options_page(
			__( 'DocSearch Settings', 'integration-for-docsearch' ),
			__( 'DocSearch', 'integration-for-docsearch' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_options_page' ]
		);
	}

	/**
	 * Renders the options page content.
	 *
	 * This method outputs the HTML for the options page, including the form
	 * for saving settings.
	 *
	 * @return void
	 */
	public function render_options_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'DocSearch Settings', 'integration-for-docsearch' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTIONS_GROUP );
				do_settings_sections( 'integration-for-docsearch' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Registers the settings, section, and fields using the WordPress Settings API.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			self::OPTIONS_GROUP,
			self::OPTION_NAME,
			[ $this, 'sanitize_settings' ]
		);

		add_settings_section(
			'ifd_main_section',
			'API Credentials & Configuration',
			[ $this, 'render_section_description' ],
			self::PAGE_SLUG
		);

		$fields = [
			'app_id'                       => 'Application ID',
			'api_key'                      => 'Search-Only API Key',
			'index_name'                   => 'Index Name',
			'placeholder'                  => 'Placeholder Text',
			'initial_query'                => 'Initial Query',
			'max_results_per_group'        => 'Max Results Per Group',
			'disable_user_personalization' => 'Disable User Personalization',
		];

		foreach ( $fields as $id => $title ) {
			add_settings_field(
				$id,
				$title,
				[ $this, "render_{$id}_field" ],
				self::PAGE_SLUG,
				'ifd_main_section',
				[ 'label_for' => self::OPTION_NAME . '[' . $id . ']' ]
			);
		}
	}

	/**
	 * Renders the descriptive text for the main settings section.
	 *
	 * @return void
	 */
	public function render_section_description() {
		echo '<p>Enter your Algolia credentials below. Fields defined as constants in <code>wp-config.php</code> are not editable.</p>';
	}

	/**
	 * Retrieves the value for a specific setting.
	 *
	 * It prioritizes a PHP constant (e.g., IFD_DOCSEARCH_APP_ID) over the
	 * value stored in the database.
	 *
	 * @param  string $key The option key (e.g., 'app_id').
	 * @return array       An array containing the 'value', whether it 'is_defined'
	 * by a constant, and the 'constant' name.
	 */
	private function get_setting( $key ) {
		$constant_name = strtoupper( 'IFD_DOCSEARCH' . '_' . $key );

		if ( defined( $constant_name ) ) {
			return [
				'value'      => constant( $constant_name ),
				'is_defined' => true,
				'constant'   => $constant_name,
			];
		}

		$options = get_option( self::OPTION_NAME );
		return [
			'value'      => isset( $options[ $key ] ) ? $options[ $key ] : '',
			'is_defined' => false,
			'constant'   => $constant_name,
		];
	}

	/**
	 * Renders a standard input field (text, password, number).
	 *
	 * @param string $key         The option key.
	 * @param string $type        The input type (e.g., 'text', 'password', 'number').
	 * @param string $description A helpful description for the field.
	 */
	private function render_input( $key, $type, $description ) {
		$setting  = $this->get_setting( $key );
		$disabled = $setting['is_defined'] ? 'disabled' : '';

		printf(
			'<input type="%s" id="%s" name="%s[%s]" value="%s" class="regular-text" %s />',
			esc_attr( $type ),
			esc_attr( $key ),
			esc_attr( self::OPTION_NAME ),
			esc_attr( $key ),
			esc_attr( $setting['value'] ),
			esc_attr( $disabled )
		);

		printf( '<p class="description">%s</p>', esc_html( $description ) );

		if ( $setting['is_defined'] ) {
			printf(
				'<p class="description" style="color: #007cba;">Defined by the constant <code>%s</code> in <code>wp-config.php</code>.</p>',
				esc_html( $setting['constant'] )
			);
		}
	}

	/**
	 * Renders the Application ID input field.
	 *
	 * @return void
	 */
	public function render_app_id_field() {
		$this->render_input( 'app_id', 'text', 'Your Algolia Application ID.' );
	}


	/**
	 * Renders the API key input field.
	 *
	 * @return void
	 */
	public function render_api_key_field() {
		$this->render_input( 'api_key', 'password', 'Your Algolia Search-Only API Key.' );
	}

	/**
	 * Renders the index name input field.
	 *
	 * @return void
	 */
	public function render_index_name_field() {
		$this->render_input( 'index_name', 'text', 'The name of the Algolia index to search.' );
	}

	/**
	 * Renders the placeholder input field.
	 *
	 * @return void
	 */
	public function render_placeholder_field() {
		$this->render_input( 'placeholder', 'text', 'The placeholder text for the search input modal.' );
	}

	/**
	 * Renders the initial query input field.
	 *
	 * @return void
	 */
	public function render_initial_query_field() {
		$this->render_input( 'initial_query', 'text', 'An initial query to populate the search input.' );
	}

	/**
	 * Renders the max results per group input field.
	 *
	 * @return void
	 */
	public function render_max_results_per_group_field() {
		$this->render_input( 'max_results_per_group', 'number', 'Maximum results to display per group. Default: 5' );
	}

	/**
	 * Renders the disable user personalization input field.
	 *
	 * @return void
	 */
	public function render_disable_user_personalization_field() {
		$setting  = $this->get_setting( 'disable_user_personalization' );
		$disabled = $setting['is_defined'] ? 'disabled' : '';

		printf(
			'<input type="checkbox" id="disable_user_personalization" name="%s[disable_user_personalization]" value="1" %s %s />',
			esc_attr( self::OPTION_NAME ),
			checked( '1', $setting['value'], false ),
			esc_attr( $disabled )
		);

		echo '<label for="disable_user_personalization"> Turn off saving recent searches and favorites to local storage.</label>';
		
		if ( $setting['is_defined'] ) {
			printf(
				'<p class="description" style="color: #007cba;">Defined by the constant <code>%s</code> in <code>wp-config.php</code>.</p>',
				esc_html( $setting['constant'] )
			);
		}
	}

	/**
	 * Sanitizes the settings array before saving to the database.
	 *
	 * @param  array $input The raw input from the form submission.
	 * @return array        The sanitized input.
	 */
	public function sanitize_settings( $input ) {
		$sanitized_input = [];
		$options = get_option( self::OPTION_NAME );
		
		$text_fields = ['app_id', 'api_key', 'index_name', 'placeholder', 'initial_query'];
		foreach($text_fields as $field) {
			$sanitized_input[$field] = isset($input[$field]) ? sanitize_text_field($input[$field]) : '';
		}

		$sanitized_input['max_results_per_group'] = isset( $input['max_results_per_group'] ) ? absint( $input['max_results_per_group'] ) : 5;
		$sanitized_input['disable_user_personalization'] = ( isset( $input['disable_user_personalization'] ) && '1' === $input['disable_user_personalization'] ) ? '1' : '0';

		return $sanitized_input;
	}
}
