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

	/**
	 * Returns the fields used in the options page.
	 *
	 * @return array
	 */
	public static function fields() {

		return [
			'appId' => [
				'label'       => __( 'Algolia Application ID', 'ifd' ),
				'description' => __( 'Your Algolia application ID.', 'ifd' ),
				'constant'    => 'IFD_APP_ID',
				'type'        => 'text',
				'default'     => 'R2IYF7ETH7'
			],
			'apiKey' => [
				'label'       => __( 'Algolia Search API Key', 'ifd' ),
				'description' => __( 'Your Algolia Search API Key.', 'ifd' ),
				'constant'    => 'IFD_API_KEY',
				'type'        => 'text',
				'default'     => '599cec31baffa4868cae4e79f180729b'
			],
			'indexName' => [
				'label'       => __( 'Algolia Index Name', 'ifd' ),
				'description' => __( 'Your Algolia index name.', 'ifd' ),
				'constant'    => 'IFD_INDEX_NAME',
				'type'        => 'text',
				'default'     => 'docsearch'
			],
			'placeholder' => [
				'label'       => __( 'Placeholder', 'ifd' ),
				'description' => __( 'Your placeholder.', 'ifd' ),
				'constant'    => 'IFD_PLACEHOLDER',
				'type'        => 'text',
			],
			'initialQuery' => [
				'label'       => __( 'Initial Query', 'ifd' ),
				'description' => __( 'The search input initial query.', 'ifd' ),
				'constant'    => 'IFD_INITIAL_QUERY',
				'type'        => 'text',
			],
			'disableUserPersonalization' => [
				'label'       => __( 'Disable User Personalization', 'ifd' ),
				'description' => __( 'The search input initial query.', 'ifd' ),
				'constant'    => 'IFD_DISABLE_USER_PERSONALIZATION',
				'type'        => 'checkbox',
			],
			'maxResultsPerGroup' => [
				'label'       => __( 'Max Results Per Group', 'ifd' ),
				'description' => __( 'The maximum number of results to display per search group. Default is 5.', 'ifd' ),
				'constant'    => 'IFD_MAX_RESULTS_PER_GROUP',
				'type'        => 'number',
				'default'     => 5,
			]
		];
	}
}
