<?php
/**
 * Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation;

use Exception;

use GFAddOn;
use GFAPI;
use GFCommon;
use GFFeedAddOn;
use GFForms;
use GFFormsModel;
use GFExport;
use Gravity_Forms\Gravity_Forms\Settings\Fields;
use WP_Error;

GFForms::include_feed_addon_framework();

/**
 * Entry Automation for Gravity Forms.
 *
 * @since     1.0
 * @author    ForGravity
 * @copyright Copyright (c) 2017, Travis Lopes
 */
class Entry_Automation extends GFFeedAddOn {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0
	 * @access private
	 * @var    Entry_Automation $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Defines the version of Gravity Forms Entry Automation.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_version Contains the version, defined from entryautomation.php
	 */
	protected $_version = FG_ENTRYAUTOMATION_VERSION;

	/**
	 * Defines the minimum Gravity Forms version required.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_min_gravityforms_version The minimum version required.
	 */
	protected $_min_gravityforms_version = '1.9.13';

	/**
	 * Defines the plugin slug.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_slug The slug used for this plugin.
	 */
	protected $_slug = 'entryautomation';

	/**
	 * Defines the main plugin file.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_path The path to the main plugin file, relative to the plugins folder.
	 */
	protected $_path = 'forgravity-entryautomation/entryautomation.php';

	/**
	 * Defines the full path to this class file.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_full_path The full path.
	 */
	protected $_full_path = __FILE__;

	/**
	 * Defines the URL where this Add-On can be found.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string The URL of the Add-On.
	 */
	protected $_url = 'http://forgravity.com/entry-automation';

	/**
	 * Defines the title of this Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_title The title of the Add-On.
	 */
	protected $_title = 'Entry Automation for Gravity Forms';

	/**
	 * Defines the short title of the Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_short_title The short title.
	 */
	protected $_short_title = 'Entry Automation';

	/**
	 * Allows configuration of what order feeds are executed in from the feed list page.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    bool $_supports_feed_ordering
	 */
	protected $_supports_feed_ordering = true;

	/**
	 * Defines the capability needed to access the Add-On settings page.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_settings_page The capability needed to access the Add-On settings page.
	 */
	protected $_capabilities_settings_page = 'forgravity_entryautomation';

	/**
	 * Defines the capability needed to access the Add-On form settings page.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_form_settings The capability needed to access the Add-On form settings page.
	 */
	protected $_capabilities_form_settings = 'forgravity_entryautomation';

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'forgravity_entryautomation_uninstall';

	/**
	 * Defines the capabilities needed for Entry Automation.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = [ 'forgravity_entryautomation', 'forgravity_entryautomation_uninstall' ];

	/**
	 * Get instance of this class.
	 *
	 * @since  1.0
	 * @access public
	 * @static
	 *
	 * @return Entry_Automation
	 */
	public static function get_instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	/**
	 * Register needed pre-initialization hooks.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses   Entry_Automation::strtotime()
	 */
	public function pre_init() {

		parent::pre_init();

		add_action( FG_ENTRYAUTOMATION_EVENT, [ $this, 'run_automation' ] );
		add_action( 'update_site_option_auto_update_plugins', [ $this, 'action_update_site_option_auto_update_plugins' ], 10, 3 );

		Scheduler::get_cron_array();

	}

	/**
	 * Register needed hooks.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init() {

		parent::init();

		remove_filter( 'gform_entry_post_save', [ $this, 'maybe_process_feed' ] );
		add_action( 'gform_after_submission', [ $this, 'maybe_process_feed' ], 99, 2 );

		add_action( 'gform_after_delete_form', [ $this, 'action_gform_after_delete_form' ], 10, 1 );

		add_filter( 'auto_update_plugin', [ $this, 'maybe_auto_update' ], 10, 2 );
		add_filter( $this->_slug . '_feed_actions', [ $this, 'feed_list_actions' ], 10, 3 );

		add_filter( 'gform_disable_notification', [ '\ForGravity\Entry_Automation\Action\Notification', 'filter_gform_disable_notification' ], 10, 5 );

		// Polls Add-On support.
		if ( function_exists( 'gf_polls' ) ) {
			add_filter( 'fg_entryautomation_export_field_value', function( $field_value, $form, $field_id, $entry ) {
				return gf_polls()->display_entries_field_value( $field_value, $form['id'], $field_id, $entry );
			}, 10, 4 );
		}

		// Quiz Add-On support.
		if ( function_exists( 'gf_quiz' ) ) {
			add_filter( 'fg_entryautomation_export_field_value', function( $field_value, $form, $field_id, $entry ) {
				return gf_quiz()->display_entries_field_value( $field_value, $form['id'], $field_id, $entry );
			}, 10, 4 );
		}

		// Survey Add-On support.
		if ( function_exists( 'gf_survey' ) ) {
			add_filter( 'fg_entryautomation_export_field_value', function( $field_value, $form, $field_id, $entry ) {
				return gf_survey()->export_field_value( $field_value, $form['id'], $field_id, $entry );
			}, 10, 4 );
		}

	}

	/**
	 * Register needed hooks.
	 *
	 * @since  1.1.5
	 * @access public
	 */
	public function init_admin() {

		parent::init_admin();

		add_action( 'admin_init', [ $this, 'maybe_serve_export_file' ] );

		remove_action( 'after_plugin_row_' . $this->get_path(), array( $this, 'plugin_row' ), 10, 2 );

		// Add plugin row message.
		if ( isset( $this->_min_gravityforms_version ) && RG_CURRENT_PAGE == 'plugins.php' && false === $this->_enable_rg_autoupgrade ) {
			add_action( 'after_plugin_row_' . $this->_path, [ $this, 'action_after_plugin_row' ], 10 );
		}

		// Members 2.0+ integration.
		if ( function_exists( 'members_register_cap_group' ) ) {
			remove_filter( 'members_get_capabilities', [ $this, 'members_get_capabilities' ] );
			add_action( 'members_register_cap_groups', [ $this, 'members_register_cap_group' ] );
			add_action( 'members_register_caps', [ $this, 'members_register_caps' ] );
		}

		add_filter( 'gform_settings_header_buttons', [ $this, 'filter_gform_settings_header_button' ] );

		add_filter( 'gform_system_report', [ '\ForGravity\Entry_Automation\System_Report', 'filter_gform_system_report' ], 1 );

	}

	/**
	 * Register needed AJAX actions.
	 *
	 * @since  1.0.6
	 * @access public
	 */
	public function init_ajax() {

		parent::init_ajax();

		add_action( 'wp_ajax_' . $this->_slug . '_run_task', [ $this, 'ajax_run_task' ] );

		add_action( 'wp_ajax_fg_entryautomation_extension_action', [ $this, 'ajax_handle_extension_action' ] );
		add_action( 'wp_ajax_fg_entryautomation_export_entries_task', [ $this, 'ajax_export_entries_task' ] );

	}

	/**
	 * Define minimum requirements needed to run Entry Automation.
	 *
	 * @since  1.0.7
	 * @access public
	 *
	 * @return array
	 */
	public function minimum_requirements() {

		return [ 'php' => [ 'version' => '5.6' ] ];

	}

	/**
	 * Returns the physical path of the plugins root folder.
	 *
	 * @since 3.0
	 *
	 * @param string $full_path Optional. The full path the the plugin file.
	 *
	 * @return string
	 */
	public function get_base_path( $full_path = '' ) {

		// The base path should be changed when Unit Tests are running.
		return ! defined( 'WP_TESTS_DOMAIN' ) ? WP_PLUGIN_DIR . '/' . dirname( FG_ENTRYAUTOMATION_PLUGIN_BASENAME ) : dirname( dirname( __FILE__ ) );

	}

	/**
	 * Returns the url of the root folder of the current Add-On.
	 *
	 * @since 3.0
	 *
	 * @param string $full_path Optional. The full path the the plugin file.
	 *
	 * @return string
	 */
	public function get_base_url( $full_path = '' ) {

		return plugins_url( null, FG_ENTRYAUTOMATION_PLUGIN_BASENAME );

	}

	/**
	 * Enqueue needed scripts.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function scripts() {

		// Get minification string.
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

		$scripts = [
			[
				'handle'  => 'forgravity_entryautomation_feed_settings',
				'src'     => $this->get_base_url() . "/js/feed_settings{$min}.js",
				'version' => $min ? $this->_version : filemtime( $this->get_base_path() . '/js/feed_settings.js' ),
				'deps'    => [ 'jquery', 'wp-element', 'wp-date', 'wp-components', 'wp-i18n', 'moment' ],
				'enqueue' => [
					[
						'admin_page' => [ 'form_settings' ],
						'tab'        => $this->get_slug(),
					],
				],
				'strings' => [
					'formId'      => rgget( 'id' ),
					'nonce'       => wp_create_nonce( $this->get_slug() ),
					'runTask'     => wp_strip_all_tags( __( 'Run Task Now', 'forgravity_entryautomation' ) ),
					'runningTask' => '<i class="fa fa-spinner fa-pulse fa-fw"></i>&nbsp;' . wp_strip_all_tags( __( 'Running Task...', 'forgravity_entryautomation' ) ),
				],
			],
			[
				'handle'  => 'forgravity_entryautomation_plugin_settings',
				'src'     => $this->get_base_url() . '/js/plugin_settings.js',
				'version' => $this->_version,
				'deps'    => [ 'jquery' ],
				'enqueue' => [
					[
						'admin_page' => [ 'plugin_settings' ],
						'tab'        => $this->get_slug(),
					],
				],
				'strings' => [
					'nonce'      => wp_create_nonce( $this->get_slug() ),
					'processing' => [
						'activate'   => '<i class="fa fa-spinner fa-pulse fa-fw"></i>&nbsp;' . wp_strip_all_tags( __( 'Activating...', 'forgravity_entryautomation' ) ),
						'deactivate' => '<i class="fa fa-spinner fa-pulse fa-fw"></i>&nbsp;' . wp_strip_all_tags( __( 'Deactivating...', 'forgravity_entryautomation' ) ),
						'install'    => '<i class="fa fa-spinner fa-pulse fa-fw"></i>&nbsp;' . wp_strip_all_tags( __( 'Installing...', 'forgravity_entryautomation' ) ),
					],
				],
			],
			[
				'handle'  => 'forgravity_entryautomation_export_entries',
				'src'     => $this->get_base_url() . "/js/export-entries{$min}.js",
				'version' => $min ? $this->_version : filemtime( $this->get_base_path() . '/js/export-entries.js' ),
				'deps'    => [ 'wp-i18n' ],
				'enqueue' => [ [ $this, 'enqueue_export_entries_script' ] ],
			],
		];

		$scripts = array_merge( parent::scripts(), $scripts );

		return apply_filters( 'fg_entryautomation_scripts', $scripts );

	}

	/**
	 * Enqueue needed stylesheets.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function styles() {

		// Get minification string.
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

		$styles = [
			[
				'handle'  => $this->get_slug() . '_feed_settings',
				'src'     => $this->get_base_url() . "/css/feed_settings{$min}.css",
				'version' => $min ? $this->get_version() : filemtime( $this->get_base_path() . '/css/feed_settings.css' ),
				'enqueue' => [
					[
						'admin_page' => [ 'form_settings' ],
						'tab'        => $this->get_slug(),
					],
				],
			],
			[
				'handle'  => $this->get_slug() . '_plugin_settings',
				'src'     => $this->get_base_url() . "/css/plugin_settings{$min}.css",
				'version' => $min ? $this->get_version() : filemtime( $this->get_base_path() . '/css/plugin_settings.css' ),
				'enqueue' => [
					[
						'admin_page' => [ 'plugin_settings' ],
						'tab'        => $this->get_slug(),
					],
				],
			],
			[
				'handle'  => $this->get_slug() . '_export_entries',
				'src'     => $this->get_base_url() . "/css/export-entries{$min}.css",
				'version' => $min ? $this->get_version() : filemtime( $this->get_base_path() . '/css/export-entries.css' ),
				'enqueue' => [ [ $this, 'enqueue_export_entries_script' ] ],
			],
			[
				'handle'  => 'forgravity_dashicons',
				'src'     => $this->get_base_url() . '/css/dashicons.css',
				'version' => $this->get_version(),
				'enqueue' => [
					[ 'query' => 'page=roles&action=edit' ],
				],
			],
		];

		return array_merge( parent::styles(), $styles );

	}

	/**
	 * Determine if Export Entries script should be enqueued.
	 *
	 * @since 4.0
	 *
	 * @return bool
	 */
	public function enqueue_export_entries_script() {

		if ( ! $this->current_user_can_any( $this->_capabilities_form_settings ) ) {
			return false;
		}

		if ( rgget( 'page' ) !== 'gf_export' ) {
			return false;
		}

		if ( rgget( 'subview' ) && rgget( 'subview' ) !== 'export_entry' ) {
			return false;
		}

		return true;

	}





	// # UNINSTALL -----------------------------------------------------------------------------------------------------

	/**
	 * Remove cron event.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function uninstall() {

		global $wpdb;

		// Get export file options.
		$export_file_options = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '" . $this->_slug . "_file_%'" );

		// If options were found, remove them.
		if ( ! empty( $export_file_options ) ) {
			array_map( 'delete_option', $export_file_options );
		}

		// Get all feeds.
		$feeds = $this->get_feeds();

		// Loop through feeds.
		foreach ( $feeds as $feed ) {

			// Delete feed.
			$this->delete_feed( $feed['id'] );

		}

	}





	// # FEED SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Display a warning when task does not have a scheduled event.
	 *
	 * @since 3.0
	 *
	 * @param array $form    Current Form object.
	 * @param int   $feed_id Current feed ID.
	 */
	public function feed_edit_page( $form, $feed_id ) {

		if ( $feed_id ) {

			$feed = $this->get_feed( $feed_id );

			if ( rgars( $feed, 'meta/type' ) === 'scheduled' && ! Scheduler::get_task_event( $feed_id, false ) ) {
				printf(
					'<div class="alert %2$s" role="alert">%1$s</div>',
					esc_html__( 'Your Task is not properly scheduled with WordPress. Please confirm the next run time below and save the task again.', 'forgravity_entryautomation' ),
					$this->is_gravityforms_supported( '2.5-beta-1' ) ? 'gforms_note_error' : 'error'
				);
			}

		}

		parent::feed_edit_page( $form, $feed_id );

	}

	/**
	 * Setup fields for feed settings.
	 *
	 * @since  1.0
	 *
	 * @return array
	 */
	public function feed_settings_fields() {

		require_once GFCommon::get_base_path() . '/includes/settings/class-fields.php';

		$settings = [
			[
				'id'       => 'general',
				'title'    => esc_html__( 'General', 'forgravity_entryautomation' ),
				'sections' => [
					[
						'id'     => 'task',
						'title'  => esc_html__( 'Task Settings', 'forgravity_entryautomation' ),
						'fields' => [
							[
								'name'          => 'feedName',
								'label'         => esc_html__( 'Task Name', 'forgravity_entryautomation' ),
								'type'          => 'text',
								'class'         => 'medium',
								'required'      => true,
								'default_value' => $this->get_default_feed_name(),
							],
							[
								'name'          => 'type',
								'label'         => esc_html__( 'Task Type', 'forgravity_entryautomation' ),
								'type'          => 'radio',
								'required'      => true,
								'default_value' => 'scheduled',
								'choices'       => [
									[
										'value' => 'manual',
										'icon'  => file_get_contents( $this->get_base_path() . '/images/type/manual.svg' ),
										'label' => esc_html__( 'Manually', 'forgravity_entryautomation' ),
									],
									[
										'value' => 'scheduled',
										'icon'  => file_get_contents( $this->get_base_path() . '/images/type/scheduled.svg' ),
										'label' => esc_html__( 'Scheduled', 'forgravity_entryautomation' ),
									],
									[
										'value' => 'submission',
										'icon'  => file_get_contents( $this->get_base_path() . '/images/type/submission.svg' ),
										'label' => esc_html__( 'On Form Submission', 'forgravity_entryautomation' ),
									],
								],
							],
							[
								'name'          => 'entryType',
								'label'         => esc_html__( 'Entry Type', 'forgravity_entryautomation' ),
								'type'          => 'radio',
								'required'      => true,
								'default_value' => 'entry',
								'choices'       => [
									[
										'value' => 'entry',
										'icon'  => file_get_contents( $this->get_base_path() . '/images/entry-type/entry.svg' ),
										'label' => esc_html__( 'Entries', 'forgravity_entryautomation' ),
									],
									[
										'value' => 'draft_submission',
										'icon'  => file_get_contents( $this->get_base_path() . '/images/entry-type/draft-submission.svg' ),
										'label' => esc_html__( 'Draft Submissions', 'forgravity_entryautomation' ),
									],
								],
								'dependency'    => [ __CLASS__, 'is_save_continue_enabled' ],
							],
							[
								'type'       => 'save',
								'name'       => 'save',
								'dependency' => [
									'live'   => true,
									'fields' => [
										[
											'field' => 'action',
										],
									],
								],
								'messages'   => [
									'error'   => esc_html__( 'There was an error while saving the Entry Automation task. Please review the errors below and try again.', 'forgravity_entryautomation' ),
									'success' => esc_html__( 'Entry Automation task updated.', 'forgravity_entryautomation' ),
								],
							],
						],
					],
					[
						'id'     => 'automation-action',
						'title'  => esc_html__( 'Automation Action Settings', 'forgravity_entryautomation' ),
						'fields' => [
							[
								'name'     => 'action',
								'label'    => esc_html__( 'Automation Action', 'forgravity_entryautomation' ),
								'type'     => 'radio',
								'required' => true,
								'choices'  => $this->get_actions_as_choices(),
							],
						],
					],
					[
						'id'          => 'schedule',
						'title'       => esc_html__( 'Schedule Settings', 'forgravity_entryautomation' ),
						'dependency'  => [
							'live'   => true,
							'fields' => [
								[
									'field' => 'action',
								],
								[
									'field'  => 'type',
									'values' => [ 'scheduled' ],
								],
							],
						],
						'fields'      => $this->get_schedule_fields(),
						'description' => sprintf(
							'<div class="entryautomation-section-summary">
								<div class="entryautomation-section-summary__icon">
									<img src="%2$s/images/summary-schedule.svg" alt="%1$s" width="22" />
								</div>
								<div class="entryautomation-section-summary__text">
									<h4>%1$s</h4>
									<p id="entryautomation-schedule-summary"></p>
								</div>
							</div>',
							esc_html__( 'Summary of Schedule', 'forgravity_entryautomation' ),
							$this->get_base_url()
						),
					],
					[
						'id'          => 'target_entries',
						'title'       => esc_html__( 'Target Entries Settings', 'forgravity_entryautomation' ),
						'dependency'  => [
							'live'   => true,
							'fields' => [
								[
									'field' => 'action',
								],
								[
									'field'  => 'type',
									'values' => [ 'manual', 'scheduled' ],
								],
							],
						],
						'fields'      => $this->get_target_entries_fields(),
						'description' => sprintf(
							'<div class="entryautomation-section-summary">
								<div class="entryautomation-section-summary__icon">
									<img src="%2$s/images/summary-target.svg" alt="%1$s" width="20" />
								</div>
								<div class="entryautomation-section-summary__text">
									<h4>%1$s</h4>
									<p id="entryautomation-target-summary"></p>
								</div>
							</div>',
							esc_html__( 'Summary of Selected Entries', 'forgravity_entryautomation' ),
							$this->get_base_url()
						),
					],
					[
						'id'         => 'conditional-logic',
						'title'      => esc_html__( 'Conditional Logic Settings', 'forgravity_entryautomation' ),
						'dependency' => [
							'live'   => true,
							'fields' => [
								[
									'field' => 'action',
								],
							],
						],
						'fields'     => [
							[
								'name'           => 'condition',
								'type'           => 'conditional_logic',
								'object_type'    => 'feed_condition',
								'label'          => esc_html__( 'Conditional Logic', 'forgravity_entryautomation' ),
								'checkbox_label' => esc_html__( 'Enable', 'forgravity_entryautomation' ),
								'instructions'   => esc_html__( 'Include entries if', 'forgravity_entryautomation' ),
								'tooltip'        => sprintf(
									'<h6>%s</h6>%s',
									esc_html__( 'Conditional Logic', 'forgravity_entryautomation' ),
									esc_html__( 'Filter the entries by adding conditions.', 'forgravity_entryautomation' )
								),
								'callback'       => [ $this, 'callback_feed_condition' ],
							],
						],
					],
				],
			],
		];

		// Loop through registered actions.
		foreach ( Action::get_registered_actions() as $action ) {

			// Get settings fields for action.
			$action_settings = $action->get_settings_fields();

			// If no settings fields are defined, skip.
			if ( empty( $action_settings ) ) {
				continue;
			}

			// Get keys for action settings.
			$settings_keys = array_keys( $action_settings );

			// If the first settings key is numeric, add each section separately.
			if ( is_numeric( $settings_keys[0] ) ) {

				// Loop through settings sections and add.
				foreach ( $action_settings as $settings_section ) {
					$settings[] = $settings_section;
				}
			} else {

				$settings[] = $action_settings;

			}
		}

		return $settings;

	}

	/**
	 * If Save and Continue is enabled in the form.
	 *
	 * @since 5.0
	 *
	 * @param int|Gravity_Forms\Gravity_Forms\Settings\Settings $form_id The form ID or the Settings object.
	 *
	 * @return bool
	 */
	public static function is_save_continue_enabled( $form_id ) {

		if ( ! is_integer( $form_id ) ) {
			$form_id = rgget( 'id' );
		}

		$form = GFFormsModel::get_form_meta( $form_id );

		return (bool) rgars( $form, 'save/enabled' );

	}

	/**
	 * Get registered actions as choices.
	 *
	 * @since  3.0
	 *
	 * @return array
	 */
	public function get_actions_as_choices() {

		// Initialize choices array.
		$choices = [];

		// Get registered actions.
		$actions = Action::get_registered_actions();

		// If no actions are registered, return.
		if ( empty( $actions ) ) {
			return $choices;
		}

		// Loop through actions.
		foreach ( $actions as $action ) {

			// Add as choice.
			$choices[] = [
				'value' => $action->get_name(),
				'icon'  => $action->get_icon(),
				'label' => $action->get_label(),
			];

		}

		return $choices;

	}

	/**
	 * Validates a text and select settings field.
	 *
	 * @since  3.0
	 *
	 * @param array $field    Field settings.
	 * @param array $settings Submitted settings values.
	 */
	public static function validate_text_select( $field, $settings ) {

		// Convert text field name.
		$text_field_name = explode( '/', str_replace( [ '[', ']' ], [ '/', '' ], $field['inputs']['text']['name'] ) );

		// Get text field value.
		$text_field_value = rgars( $settings, end( $text_field_name ) );

		// If text field is empty and field is required, set error.
		if ( rgblank( $text_field_value ) && rgar( $field, 'required' ) ) {
			fg_entryautomation()->set_field_error( $field, esc_html__( 'This field is required.', 'forgravity_entryautomation' ) );
			return;
		}

		// If text field is not numeric, set error.
		if ( ! rgblank( $text_field_value ) && ! ctype_digit( $text_field_value ) ) {
			fg_entryautomation()->set_field_error( $field, esc_html__( 'You must use a whole number.', 'forgravity_entryautomation' ) );
			return;
		}

	}

	/**
	 * Get the entry statuses.
	 *
	 * @since 3.0
	 *
	 * @return array[]
	 */
	public function get_entry_statuses() {

		return [
			[
				'label' => esc_html__( 'Active', 'forgravity_entryautomation' ),
				'value' => 'active',
			],
			[
				'label' => esc_html__( 'Spam', 'forgravity_entryautomation' ),
				'value' => 'spam',
			],
			[
				'label' => esc_html__( 'Trash', 'forgravity_entryautomation' ),
				'value' => 'trash',
			],
			[
				'label' => esc_html__( 'Unread', 'forgravity_entryautomation' ),
				'value' => 'unread',
			],
			[
				'label' => esc_html__( 'Read', 'forgravity_entryautomation' ),
				'value' => 'read',
			],
			[
				'label' => esc_html__( 'Starred', 'forgravity_entryautomation' ),
				'value' => 'starred',
			],
		];

	}

	/**
	 * Determine if a section is the one currently displayed.
	 *
	 * @since      1.2
	 * @deprecated 2.1
	 *
	 * @param string $section_id   Section ID.
	 * @param bool   $is_first     Is this the first section.
	 * @param bool   $return_class Return CSS class instead of boolean.
	 *
	 * @return string|bool
	 */
	public function is_current_section( $section_id, $is_first = false, $return_class = false ) {

		// Get current section.
		$current_section = rgpost( 'entryautomation_tab' );

		// Get first errored section.
		$section_error = $this->get_first_field_error();

		// Initialize current section flag.
		$is_current_section = false;

		// Determine if this is the current section.
		if ( ! empty( $section_error ) && $section_error['section'] == $section_id ) {
			$is_current_section = true;
		} elseif ( empty( $section_error ) && $current_section && $section_id === $current_section ) {
			$is_current_section = true;
		} elseif ( empty( $section_error ) && rgblank( $current_section ) && $is_first ) {
			$is_current_section = true;
		}

		if ( $return_class && $is_current_section ) {
			return 'gaddon-current-section';
		}

		return ! $return_class ? $is_current_section : '';

	}

	/**
	 * Get first settings field with an error and its section.
	 *
	 * @since      1.3
	 * @deprecated 2.1
	 *
	 * @return array|string
	 */
	public function get_first_field_error() {

		// Get field errors.
		$errors = $this->get_field_errors();

		// If no field errors were found, return.
		if ( empty( $errors ) ) {
			return '';
		}

		// Get first invalid field.
		$field_name = array_keys( $errors )[0];

		// Get feed settings fields.
		$sections = $this->get_feed_settings_fields();

		// Loop through sections.
		foreach ( $sections as $section ) {

			// Loop through section fields.
			foreach ( $section['fields'] as $field ) {

				// If this is not the invalid field, skip it.
				if ( $field_name !== $field['name'] ) {
					continue;
				}

				return [
					'section' => $section['id'],
					'field'   => $field,
				];

			}

		}

		return '';

	}

	/**
	 * Define the title for the feed settings page.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return string
	 */
	public function feed_settings_title() {

		return esc_html__( 'Entry Automation Task Settings', 'forgravity_entryautomation' );

	}

	/**
	 * Schedule the initial automation event upon saving feed.
	 *
	 * In Gravity Forms 2.5, maybe_save_feed_settings() is not triggered anymore. So we switch to use the method.
	 *
	 * @since 2.5
	 *
	 * @param int   $feed_id  The ID of the feed being saved.
	 * @param int   $form_id  The ID of the form the feed belongs to.
	 * @param array $settings The feed settings.
	 *
	 * @return int
	 */
	public function save_feed_settings( $feed_id, $form_id, $settings ) {

		// Get previous settings.
		$previous = $this->get_previous_settings();

		// If this is not a scheduled task, unschedule it.
		if ( 'scheduled' !== rgar( $settings, 'type' ) ) {
			if ( ! empty( $previous ) ) {
				Scheduler::unschedule_task( $feed_id );
			}

			return parent::save_feed_settings( $feed_id, $form_id, $settings );
		}

		// Get the new scheduled start time.
		$new_start = $this->strtotime( $settings['nextRun'], 'timestamp', true );

		// Remove change run time.
		unset( $settings['nextRun'] );

		// Initialize schedule flag.
		$schedule_task = false;

		// If this is a new feed, set schedule flag.
		if ( empty( $previous ) ) {

			// Set schedule flag.
			$schedule_task = true;

		} else {

			// Prepare times.
			$previous_start = $this->get_next_run_time( $feed_id );

			// If times are not the same, set schedule flag.
			if ( $previous_start != $new_start ) {
				$schedule_task = true;
			}

		}

		$feed_id = parent::save_feed_settings( $feed_id, $form_id, $settings );

		if ( $schedule_task ) {
			// Schedule next run.
			Scheduler::schedule_task( $feed_id, $form_id, $new_start );
		}

		return $feed_id;

	}

	/**
	 * Get default feed name.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses   GFFeedAddOn::get_feeds_by_slug()
	 *
	 * @return string
	 */
	public function get_default_feed_name() {

		/**
		 * Query db to look for two formats that the feed name could have been auto-generated with
		 * format from migration to add-on framework: 'Feed ' . $counter
		 * new auto-generated format when adding new feed: $short_title . ' Feed ' . $counter
		 */

		// Set to zero unless a new number is found while checking existing feed names (will be incremented by 1 at the end).
		$counter_to_use = 0;

		// Get Add-On feeds.
		$feeds_to_filter = $this->get_feeds_by_slug( $this->_slug );

		// If feeds were found, loop through and increase counter.
		if ( $feeds_to_filter ) {

			// Loop through feeds and look for name pattern to find what to make default feed name.
			foreach ( $feeds_to_filter as $check ) {

				// Get feed name and trim.
				$name = rgars( $check, 'meta/feed_name' ) ? rgars( $check, 'meta/feed_name' ) : rgars( $check, 'meta/feedName' );
				$name = trim( $name );

				// Prepare feed name pattern.
				$pattern = '/(^Task|^' . $this->_short_title . ' Task)\s\d+/';

				// Search for feed name pattern.
				preg_match( $pattern, $name, $matches );

				// If matches were found, increase counter.
				if ( $matches ) {

					// Number should be characters at the end after a space.
					$last_space = strrpos( $matches[0], ' ' );

					$digit = substr( $matches[0], $last_space );

					// Counter in existing feed name greater, use it instead.
					if ( $digit >= $counter_to_use ) {
						$counter_to_use = $digit;
					}

				}

			}

		}

		// Set default feed name.
		$value = $this->_short_title . ' Task ' . ( $counter_to_use + 1 );

		return $value;

	}

	/**
	 * Display Run Task Now button in page header.
	 *
	 * @since 3.0
	 *
	 * @param string $buttons Existing header buttons.
	 *
	 * @return string
	 */
	public function filter_gform_settings_header_button( $buttons ) {

		if ( ! $this->is_feed_edit_page() ) {
			return $buttons;
		}

		$feed        = $this->get_current_feed();
		$show_button = $feed && rgars( $feed, 'meta/action' ) && rgars( $feed, 'meta/type' ) !== 'submission';

		return sprintf(
			'<button class="button" style="%s" id="entryautomation-run-task">%s</button>',
			$show_button ? '' : 'display:none',
			esc_html__( 'Run Task Now', 'forgravity_entryautomation' )
		);

	}




	// # FEED LIST -----------------------------------------------------------------------------------------------------

	/**
	 * Define the title for the feed list page.
	 *
	 * @since  1.0
	 * @since  3.0    Update for Gravity Forms 2.5.
	 * @access public
	 *
	 * @uses   GFAddOn::get_short_title()
	 * @uses   GFFeedAddOn::can_create_feed()
	 *
	 * @return string
	 */
	public function feed_list_title() {

		return sprintf(
			esc_html__( '%s Tasks', 'forgravity_entryautomation' ),
			$this->get_short_title()
		);

	}

	/**
	 * Enable feed duplication.
	 *
	 * @since  1.1.5
	 * @access public
	 *
	 * @param string $id Feed ID requesting duplication.
	 *
	 * @return bool
	 */
	public function can_duplicate_feed( $id ) {

		return true;

	}

	/**
	 * Setup columns for feed list table.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function feed_list_columns() {

		return [
			'feedName'    => esc_html__( 'Name', 'forgravity_entryautomation' ),
			'type'        => esc_html__( 'Type', 'forgravity_entryautomation' ),
			'action'      => esc_html__( 'Action', 'forgravity_entryautomation' ),
			'lastRunTime' => esc_html__( 'Last Run Time', 'forgravity_entryautomation' ),
		];

	}

	/**
	 * Add export file URL to feed list actions.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array  $links  Action links to be filtered.
	 * @param array  $feed   The feed item being filtered.
	 * @param string $column The column ID.
	 *
	 * @return array
	 */
	public function feed_list_actions( $links, $feed, $column ) {

		// Get registered actions.
		$actions = Action::get_registered_actions();

		// If no actions are registered, return.
		if ( empty( $actions ) ) {
			return $links;
		}

		// Copy meta out to task.
		$task = [
			'task_id' => $feed['id'],
			'form_id' => $feed['form_id'],
		];
		$task = array_merge( $task, $feed['meta'] );

		// Loop through the actions.
		foreach ( $actions as $action ) {

			// Update links.
			$links = call_user_func( [ $action, 'feed_list_actions' ], $links, $task, $column );

		}

		return $links;

	}

	/**
	 * Prepare Task Type column value for feed list table.
	 *
	 * @since  2.0
	 *
	 * @param array $feed Current feed.
	 *
	 * @return string
	 */
	public function get_column_value_type( $feed ) {

		switch ( rgars( $feed, 'meta/type' ) ) {

			case 'manual':
				return esc_html__( 'Manually', 'forgravity_entryautomation' );

			case 'scheduled':
			default:
				return esc_html__( 'Scheduled', 'forgravity_entryautomation' );

			case 'submission':
				return esc_html__( 'On Form Submission', 'forgravity_entryautomation' );

		}

	}

	/**
	 * Prepare Automation Action column value for feed list table.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $feed Current feed.
	 *
	 * @uses   Action::get_action_by_name()
	 *
	 * @return string
	 */
	public function get_column_value_action( $feed ) {

		// Get action.
		$action = Action::get_action_by_name( rgars( $feed, 'meta/action' ) );

		// If action exists, return name.
		if ( is_object( $action ) ) {
			return $action->get_short_label();
		}

		return esc_html__( 'Unable to get action.', 'forgravity_entryautomation' );

	}

	/**
	 * Prepare Last Run Time column value for feed list table.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $feed Current feed.
	 *
	 * @uses   Entry_Automation::strtotime()
	 *
	 * @return string
	 */
	public function get_column_value_lastRunTime( $feed ) {

		// Get last run time.
		$last_run_time = get_option( fg_entryautomation()->get_slug() . '_last_run_time_' . $feed['id'] );

		return $last_run_time ? $this->strtotime( $last_run_time, 'Y-m-d g:i A', true, true ) : 'Never';

	}

	/**
	 * Delete feed.
	 *
	 * @since  1.2.5
	 * @access public
	 *
	 * @param int $id Feed ID.
	 *
	 * @uses   Action::get_action_by_name()
	 * @uses   GFAddOn::get_slug()
	 * @uses   GFFeedAddOn::get_feed()
	 */
	public function delete_feed( $id ) {

		// Get feed.
		$feed = $this->get_feed( $id );

		// Delete last run time.
		delete_option( $this->get_slug() . '_last_run_time_' . $id );

		// Delete scheduled event.
		Scheduler::unschedule_task( $id );

		// Get feed action.
		$action = Action::get_action_by_name( rgars( $feed, 'meta/action' ) );

		// Run delete action.
		if ( $action && method_exists( $action, 'delete_task' ) ) {
			call_user_func( [ $action, 'delete_task' ], $id );
		}

		parent::delete_feed( $id );

	}

	/**
	 * Duplicate feed.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param int|array $id          The ID of the feed to be duplicated or the feed object when duplicating a form.
	 * @param mixed     $new_form_id False when using feed actions or the ID of the new form when duplicating a form.
	 *
	 * @return int
	 */
	public function duplicate_feed( $id, $new_form_id = false ) {

		// Get new feed ID.
		$feed_id = parent::duplicate_feed( $id, $new_form_id );

		// If a feed ID was not returned, exit.
		if ( ! $feed_id ) {
			return $feed_id;
		}

		// Get new feed.
		$new_feed = $this->get_feed( $feed_id );

		// Deactivate new feed.
		$this->update_feed_active( $feed_id, false );

		// Schedule new run time.
		if ( 'scheduled' === rgars( $new_feed, 'meta/type' ) ) {

			// Get next scheduled run time of original feed.
			$next_run_time = Scheduler::get_task_event( $id );

			// Schedule first run time for new task.
			Scheduler::schedule_task( $feed_id, $new_feed['form_id'], $next_run_time['timestamp'] );

		}

		return $feed_id;

	}

	/**
	 * Save order of feeds.
	 * (Forked to regenerate events.)
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param array $feed_order Array of feed IDs in desired order.
	 *
	 * @uses   Scheduler::get_task_event()
	 * @uses   Scheduler::schedule_task()
	 */
	public function save_feed_order( $feed_order ) {

		// Save feed order.
		parent::save_feed_order( $feed_order );

		// Loop through feed IDs.
		foreach ( $feed_order as $feed_id ) {

			// Get current feed event.
			$event = Scheduler::get_task_event( $feed_id );

			// Reschedule feed.
			if ( $event ) {
				Scheduler::schedule_task( $feed_id, $event['args'][1], $event['timestamp'] );
			}

		}

	}





	// # ENTRY AUTOMATION ----------------------------------------------------------------------------------------------

	/**
	 * Determines what feeds need to be processed for the provided entry.
	 * (Forked from GFFeedAddOn::maybe_process_feed() to remove marking feed as processed.)
	 *
	 * @since 2.0
	 *
	 * @param array $entry The Entry Object currently being processed.
	 * @param array $form  The Form Object currently being processed.
	 *
	 * @return array
	 */
	public function maybe_process_feed( $entry, $form ) {

		if ( 'spam' === $entry['status'] ) {
			$this->log_debug( "GFFeedAddOn::maybe_process_feed(): Entry #{$entry['id']} is marked as spam; not processing feeds for {$this->_slug}." );
			return $entry;
		}

		$this->log_debug( __METHOD__ . "(): Checking for feeds to process for entry #{$entry['id']} for {$this->_slug}." );

		$feeds = false;

		// If this is a single submission feed, get the first feed. Otherwise, get all feeds.
		if ( $this->_single_feed_submission ) {
			$feed = $this->get_single_submission_feed( $entry, $form );
			if ( $feed ) {
				$feeds = array( $feed );
			}
		} else {
			$feeds = $this->get_feeds( $form['id'] );
		}

		// Run filters before processing feeds.
		$feeds = $this->pre_process_feeds( $feeds, $entry, $form );

		// If there are no feeds to process, return.
		if ( empty( $feeds ) ) {
			$this->log_debug( __METHOD__ . "(): No feeds to process for entry #{$entry['id']}." );
			return $entry;
		}

		// Determine if feed processing needs to be delayed.
		$is_delayed = $this->maybe_delay_feed( $entry, $form );

		// Initialize array of feeds that have been processed.
		$processed_feeds = array();

		// Loop through feeds.
		foreach ( $feeds as $feed ) {

			// Get the feed name.
			$feed_name = rgempty( 'feed_name', $feed['meta'] ) ? rgar( $feed['meta'], 'feedName' ) : rgar( $feed['meta'], 'feed_name' );

			// If this feed is inactive, log that it's not being processed and skip it.
			if ( ! $feed['is_active'] ) {
				$this->log_debug( "GFFeedAddOn::maybe_process_feed(): Feed is inactive, not processing feed (#{$feed['id']} - {$feed_name}) for entry #{$entry['id']}." );
				continue;
			}

			// If this feed's condition is not met, log that it's not being processed and skip it.
			if ( ! $this->is_feed_condition_met( $feed, $form, $entry ) ) {
				$this->log_debug( "GFFeedAddOn::maybe_process_feed(): Feed condition not met, not processing feed (#{$feed['id']} - {$feed_name}) for entry #{$entry['id']}." );
				continue;
			}

			// process feed if not delayed.
			if ( ! $is_delayed ) {

				// All requirements are met; process feed.
				$this->log_debug( "GFFeedAddOn::maybe_process_feed(): Starting to process feed (#{$feed['id']} - {$feed_name}) for entry #{$entry['id']} for {$this->_slug}" );
				$returned_entry = $this->process_feed( $feed, $entry, $form );

				// If returned value from the process feed call is an array containing an id, set the entry to its value.
				if ( is_array( $returned_entry ) && rgar( $returned_entry, 'id' ) ) {
					$entry = $returned_entry;
				}

				/**
				 * Perform a custom action when a feed has been processed.
				 *
				 * @param array $feed The feed which was processed.
				 * @param array $entry The current entry object, which may have been modified by the processed feed.
				 * @param array $form The current form object.
				 * @param GFAddOn $addon The current instance of the GFAddOn object which extends GFFeedAddOn or GFPaymentAddOn (i.e. GFCoupons, GF_User_Registration, GFStripe).
				 *
				 * @since 2.0
				 */
				do_action( 'gform_post_process_feed', $feed, $entry, $form, $this );
				do_action( "gform_{$this->_slug}_post_process_feed", $feed, $entry, $form, $this );

				// If feed is a deletion feed, do not mark as fulfilled.
				if ( 'delete' !== rgars( $feed, 'meta/action' ) || ( 'delete' === rgars( $feed, 'meta/action' ) && 'field' === rgars( $feed, 'meta/deleteType' ) ) ) {

					// Log that Add-On has been fulfilled.
					$this->log_debug( 'GFFeedAddOn::maybe_process_feed(): Marking entry #' . $entry['id'] . ' as fulfilled for ' . $this->_slug );
					gform_update_meta( $entry['id'], "{$this->_slug}_is_fulfilled", true );

					// Adding this feed to the list of processed feeds.
					$processed_feeds[] = $feed['id'];

				}

			} else {

				// Log that feed processing is being delayed.
				$this->log_debug( 'GFFeedAddOn::maybe_process_feed(): Feed processing is delayed, not processing feed for entry #' . $entry['id'] . ' for ' . $this->_slug );

				// Delay feed.
				$this->delay_feed( $feed, $entry, $form );

			}

		}

		// If any feeds were processed, save the processed feed IDs.
		if ( ! empty( $processed_feeds ) ) {

			// Get current processed feeds.
			$meta = gform_get_meta( $entry['id'], 'processed_feeds' );

			// If no feeds have been processed for this entry, initialize the meta array.
			if ( empty( $meta ) ) {
				$meta = array();
			}

			// Add this Add-On's processed feeds to the entry meta.
			$meta[ $this->_slug ] = $processed_feeds;

			// Update the entry meta.
			gform_update_meta( $entry['id'], 'processed_feeds', $meta );

		}

		// Return the entry object.
		return $entry;

	}

	/**
	 * Remove non-On Form Submission tasks from feed processing.
	 *
	 * @since 2.0
	 *
	 * @param array $feeds An array of $feed objects.
	 * @param array $entry Current entry for which feeds will be processed.
	 * @param array $form  Current form object.
	 *
	 * @return array
	 */
	public function pre_process_feeds( $feeds, $entry, $form ) {

		// Loop through feeds, remove non-On Form Submission tasks.
		foreach ( $feeds as $i => $feed ) {

			// If feed is an On Form Submission task, skip.
			if ( 'submission' === rgars( $feed, 'meta/type' ) ) {
				continue;
			}

			// Remove feed.
			unset( $feeds[ $i ] );

		}

		return parent::pre_process_feeds( $feeds, $entry, $form );

	}

	/**
	 * Run On Form Submission tasks.
	 *
	 * @since 2.0
	 *
	 * @param array $feed  The current Feed being processed.
	 * @param array $entry The current Entry being submitted.
	 * @param array $form  The current Form object.
	 *
	 * @return array
	 */
	public function process_feed( $feed, $entry, $form ) {

		try {

			// Get task.
			$task = Task::get( $feed['id'] );

		} catch ( Exception $e ) {

			// Log that task could not be run.
			$this->log_error( __METHOD__ . '(): Unable to run task #' . $feed['id'] . ' because task could not be found.' );

			return $entry;

		}

		// If this is not an On Form Submission task, do not run.
		if ( 'submission' !== rgars( $feed, 'meta/type' ) ) {

			// Log why task could not be run.
			$this->add_feed_error( 'Unable to run task #' . $task->id . ' on form #' . $task->form_id . ' because it is not an "On Form Submission" task.', $feed, $entry, $form );

			return $entry;

		}

		// If action could not be found, skip it.
		if ( ! is_object( $task->action ) ) {

			// Log why task could not be run.
			$this->add_feed_error( 'Unable to run task #' . $task->id . ' on form #' . $task->form_id . ' because action could not be found.', $feed, $entry, $form );

			return $entry;

		}

		// Set entry ID for task.
		$task->entry_id = (int) $entry['id'];

		// Run task.
		$task->run( true, false );

		return $entry;

	}

	/**
	 * Run Entry Automation on forms that pass automation conditions.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array|int $task_ids IDs of the Task being run.
	 * @param int       $form_id  Form ID.
	 *
	 * @uses   Action::get_action_by_name()
	 * @uses   Action::maybe_run_task()
	 * @uses   GFAddOn::log_error()
	 * @uses   GFFeedAddOn::get_feed()
	 */
	public function run_automation( $task_ids = [], $form_id = 0 ) {

		// If only a singular task ID is provided, convert to array.
		if ( ! is_array( $task_ids ) ) {
			$task_ids = [ $task_ids ];
		}

		// Loop through task IDs.
		foreach ( $task_ids as $task_id ) {

			try {

				// Get task.
				$task = Task::get( $task_id );

			} catch ( Exception $e ) {

				// Log that task could not be run.
				$this->log_error( __METHOD__ . '(): Unable to run task #' . $task_id . ' because task could not be found.' );

				return;

			}

			// If task is meant to run manually or on submission, skip it.
			if ( 'scheduled' !== $task->type ) {
				$this->log_error( __METHOD__ . '(): Unable to run task #' . $task->id . ' on form #' . $task->form_id . ' because it is not a scheduled task.' );
				return;
			}

			// If action could not be found, skip it.
			if ( ! is_object( $task->action ) ) {
				$this->log_error( __METHOD__ . '(): Unable to run task #' . $task->id . ' on form #' . $task->form_id . ' because action could not be found.' );
				return;
			}

			// Run task.
			$task->run( true, false );

		}

	}

	/**
	 * Run a single task from the task settings page.
	 *
	 * @since  1.2
	 */
	public function ajax_run_task() {

		// Verify nonce.
		if ( ! wp_verify_nonce( rgpost( 'nonce' ), $this->get_slug() ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'forgravity_entryautomation' ) ] );
		}

		// Verify capabilities.
		if ( ! GFCommon::current_user_can_any( $this->_capabilities_settings_page ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'forgravity_entryautomation' ) ] );
		}

		// Initial feed settings.
		$this->feed_settings_init();

		// Get settings.
		$settings = $this->get_settings_renderer()->get_posted_values();

		// If settings are invalid, return.
		if ( ! $this->get_settings_renderer()->validate( $settings ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'There is an error with your Entry Automation task. Please review your settings and try again.', 'forgravity_entryautomation' ) ] );
		}

		// Get task.
		$task = new Task(
			[
				'id'      => intval( rgpost( 'gf_feed_id' ) ),
				'form_id' => intval( rgpost( 'form_id' ) ),
				'meta'    => $settings,
			]
		);

		// If action could not be found, return.
		if ( ! is_object( $task->action ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unable to run task; selected action could not be found.', 'forgravity_entryautomation' ) ] );
		}

		// Run task.
		$response = $task->run( false, true );

		// Store file name to a transient.
		$file_parts = explode( '/', $response );
		set_transient( $this->get_slug() . '_file_' . $task->id, end( $file_parts ), HOUR_IN_SECONDS );

		// Prepare URL.
		$url = admin_url( 'admin.php?action=fg_entryautomation_export_file&tid=' . $task->id . '&run_task_now=1' );
		$url = wp_nonce_url( $url, 'fg_entryautomation_export_file' );

		// Prepare message.
		$message  = esc_html__( 'Task has successfully run.', 'forgravity_entryautomation' );
		$message .= 'export' === $task->meta['action'] && file_exists( $response ) ? sprintf( esc_html__( '%1$sDownload export file.%2$s', 'forgravity_entryautomation' ), ' <a href="' . $url . '" target="_blank">', '</a>' ) : '';

		// Send result.
		wp_send_json_success( [ 'message' => $message ] );

	}





	// # CONDITIONAL LOGIC ---------------------------------------------------------------------------------------------

	/**
	 * Display or return the markup for the feed_condition field type.
	 *
	 * @since 1.2 Added support for logic based on the entry meta.
	 *
	 * @param array $field The field properties.
	 * @param bool  $echo  Should the setting markup be echoed.
	 *
	 * @uses  Entry_Automation::get_feed_condition_entry_meta()
	 * @uses  Entry_Automation::get_feed_condition_entry_properties()
	 * @uses  GFFeedAddOn::settings_feed_condition()
	 *
	 * @return string
	 */
	public function settings_feed_condition( $field, $echo = true ) {

		$entry_meta  = array_merge( $this->get_feed_condition_entry_meta(), $this->get_feed_condition_entry_properties() );
		$find        = 'var feedCondition';
		$replacement = sprintf( 'var entry_meta = %s; %s', json_encode( $entry_meta ), $find );
		$html        = str_replace( $find, $replacement, parent::settings_feed_condition( $field, false ) );

		if ( $echo ) {
			echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $html;

	}

	/**
	 * Display or return the markup for the feed_condition object type.
	 *
	 * @since 3.2 Added support for logic based on the entry meta.
	 *
	 * @param Fields\Conditional_Logic $field The field properties.
	 * @param bool                     $echo  Should the setting markup be echoed.
	 *
	 * @return string
	 */
	public function callback_feed_condition( $field, $echo ) {

		$entry_meta  = array_merge( $this->get_feed_condition_entry_meta(), $this->get_feed_condition_entry_properties() );
		$find        = 'var feedCondition';
		$replacement = sprintf( 'var entry_meta = %s; %s', json_encode( $entry_meta ), $find );
		$html        = str_replace( $find, $replacement, $field->markup() );

		if ( $echo ) {
			echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $html;

	}

	/**
	 * Get the entry meta for use with the feed_condition setting.
	 *
	 * @since 1.2
	 *
	 * @uses  GFFormsModel::get_entry_meta()
	 *
	 * @return array
	 */
	public function get_feed_condition_entry_meta() {

		$form_id = absint( rgget( 'id' ) );

		return GFFormsModel::get_entry_meta( $form_id );

	}

	/**
	 * Get the entry properties for use with the feed_condition setting.
	 *
	 * @since 1.2
	 *
	 * @return array
	 */
	public function get_feed_condition_entry_properties() {

		$user_choices = [];

		if ( $this->is_form_settings() ) {
			$args = apply_filters( 'gform_filters_get_users', [
				'number' => 200,
				'fields' => [ 'ID', 'user_login' ],
			] );

			$users = get_users( $args );
			foreach ( $users as $user ) {
				$user_choices[] = [
					'text'  => $user->user_login,
					'value' => $user->ID,
				];
			}
		}

		return [
			'is_starred'     => [
				'label'  => esc_html__( 'Starred', 'gravityforms' ),
				'filter' => [
					'operators' => [ 'is', 'isnot' ],
					'choices'   => [
						[
							'text'  => 'Yes',
							'value' => '1',
						],
						[
							'text'  => 'No',
							'value' => '0',
						],
					],
				],
			],
			'is_read'        => [
				'label'  => esc_html__( 'Unread', 'gravityforms' ),
				'filter' => [
					'operators' => [ 'is' ],
					'choices'   => [
						[
							'text'  => 'Yes',
							'value' => '0',
						],
						[
							'text'  => 'No',
							'value' => '1',
						],
					],
				],
			],
			'ip'             => [
				'label'  => esc_html__( 'User IP', 'forgravity_entryautomation' ),
				'filter' => [
					'operators' => [ 'is', 'isnot', '>', '<', 'contains' ],
				],
			],
			'source_url'     => [
				'label'  => esc_html__( 'Source URL', 'forgravity_entryautomation' ),
				'filter' => [
					'operators' => [ 'is', 'isnot', '>', '<', 'contains' ],
				],
			],
			'payment_status' => [
				'label'  => esc_html__( 'Payment Status', 'forgravity_entryautomation' ),
				'filter' => [
					'operators' => [ 'is', 'isnot' ],
					'choices'   => [
						[
							'text'  => esc_html__( 'Paid', 'forgravity_entryautomation' ),
							'value' => 'Paid',
						],
						[
							'text'  => esc_html__( 'Processing', 'forgravity_entryautomation' ),
							'value' => 'Processing',
						],
						[
							'text'  => esc_html__( 'Failed', 'forgravity_entryautomation' ),
							'value' => 'Failed',
						],
						[
							'text'  => esc_html__( 'Active', 'forgravity_entryautomation' ),
							'value' => 'Active',
						],
						[
							'text'  => esc_html__( 'Cancelled', 'forgravity_entryautomation' ),
							'value' => 'Cancelled',
						],
						[
							'text'  => esc_html__( 'Pending', 'forgravity_entryautomation' ),
							'value' => 'Pending',
						],
						[
							'text'  => esc_html__( 'Refunded', 'forgravity_entryautomation' ),
							'value' => 'Refunded',
						],
						[
							'text'  => esc_html__( 'Voided', 'forgravity_entryautomation' ),
							'value' => 'Voided',
						],
					],
				],
			],
			'payment_amount' => [
				'label'  => esc_html__( 'Payment Amount', 'forgravity_entryautomation' ),
				'filter' => [
					'operators' => [ 'is', 'isnot', '>', '<', 'contains' ],
				],
			],
			'transaction_id' => [
				'label'  => esc_html__( 'Transaction ID', 'forgravity_entryautomation' ),
				'filter' => [
					'operators' => [ 'is', 'isnot', '>', '<', 'contains' ],
				],
			],
			'created_by'     => [
				'label'  => esc_html__( 'Created By', 'forgravity_entryautomation' ),
				'filter' => [
					'operators' => [ 'is', 'isnot' ],
					'choices'   => $user_choices,
				],
			],
		];

	}

	/**
	 * Fork of GFCommon::evaluate_conditional_logic which supports evaluating logic based on entry properties.
	 *
	 * @since 1.2
	 *
	 * @param array $logic The conditional logic to be evaluated.
	 * @param array $form  The Form object.
	 * @param array $entry The Entry object.
	 *
	 * @uses  Entry_Automation::get_feed_condition_entry_meta()
	 * @uses  Entry_Automation::get_feed_condition_entry_properties()
	 * @uses  GFFormsModel::get_field()
	 * @uses  GFFormsModel::get_field_value()
	 * @uses  GFFormsModel::is_value_match()
	 *
	 * @return bool
	 */
	public function evaluate_conditional_logic( $logic, $form, $entry ) {

		if ( ! $logic || ! is_array( rgar( $logic, 'rules' ) ) ) {
			return true;
		}

		$entry_meta      = array_merge( $this->get_feed_condition_entry_meta(), $this->get_feed_condition_entry_properties() );
		$entry_meta_keys = array_keys( $entry_meta );
		$match_count     = 0;

		if ( is_array( $logic['rules'] ) ) {
			foreach ( $logic['rules'] as $rule ) {

				if ( in_array( $rule['fieldId'], $entry_meta_keys ) ) {
					$is_value_match = GFFormsModel::is_value_match( rgar( $entry, $rule['fieldId'] ), $rule['value'], $rule['operator'], null, $rule, $form );
				} else {
					$source_field   = GFFormsModel::get_field( $form, $rule['fieldId'] );
					$field_value    = empty( $entry ) ? GFFormsModel::get_field_value( $source_field, [] ) : GFFormsModel::get_lead_field_value( $entry, $source_field );
					$is_value_match = GFFormsModel::is_value_match( $field_value, $rule['value'], $rule['operator'], $source_field, $rule, $form );
				}

				if ( $is_value_match ) {
					$match_count++;
				}
			}
		}

		$do_action = ( $logic['logicType'] == 'all' && $match_count == count( $logic['rules'] ) ) || ( $logic['logicType'] == 'any' && $match_count > 0 );

		return $do_action;

	}





	// # EXPORT ENTRIES ------------------------------------------------------------------------------------------------

	/**
	 * Serve export file.
	 *
	 * @since  1.1.5
	 * @access public
	 */
	public function maybe_serve_export_file() {

		// If export file action is not set, exit.
		if ( 'fg_entryautomation_export_file' !== rgget( 'action' ) ) {
			return;
		}

		// Verify nonce.
		check_admin_referer( 'fg_entryautomation_export_file' );

		// Get export file path.
		$task_id = rgget( 'tid' );
		if ( ! empty( $task_id ) ) {
			try {

				$task        = Task::get( $task_id );
				$form        = GFAPI::get_form( $task->form_id );
				$export_file = Action\Export::get_export_folder( $task, $form );

			} catch ( Exception $e ) {

				$this->log_error( __METHOD__ . '(): Task #' . $task_id . ' could not be found.' );

			}
		} else {
			// For a task that hasn't been saved yet, the feed id (task id) will be 0.
			// So we can only save it to the default upload root.
			$export_file = Action\Export::get_upload_root();
		}

		if ( rgget( 'run_task_now' ) == '1' ) {
			$export_file .= get_transient( $this->get_slug() . '_file_' . $task_id );
		} else {
			$export_file .= get_option( $this->get_slug() . '_file_' . $task_id );
		}

		// If export file not found, exit.
		if ( ! $export_file || ! file_exists( $export_file ) ) {
			wp_die( esc_html__( 'Export file not found.', 'forgravity_entryautomation' ) );
		}

		// Set headers.
		header( 'X-Robots-Tag: noindex, nofollow', true );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Cache-Control: public, must-revalidate, max-age=0' );
		header( 'Pragma: public' );
		header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Content-Type: application/force-download' );
		header( 'Content-Type: application/octet-stream', false );
		header( 'Content-Type: application/download', false );
		header( 'Content-Type: ' . mime_content_type( $export_file ), false );
		if ( ! isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) || empty( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) {
			// Do not use length if server is using compression.
			header( 'Content-Length: ' . strlen( $export_file ) );
		}
		header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( basename( $export_file ) ) . '"' );

		// Serve export file.
		readfile( $export_file );

		die();

	}

	/**
	 * Create a Task from the Export Entries page.
	 *
	 * @since 4.0
	 */
	public function ajax_export_entries_task() {

		check_admin_referer( 'rg_start_export', 'rg_start_export_nonce' );

		if ( ! $this->current_user_can_any( $this->_capabilities_settings_page ) ) {
			wp_send_json_error( esc_html__( 'You do not have permissions to create a new task.', 'forgravity_entryautomation' ) );
		}

		// Require export class.
		if ( ! class_exists( 'GFExport' ) ) {
			require_once GFCommon::get_base_path() . '/export.php';
		}

		// Sanitize form ID, set to $_POST for when getting default export fields.
		$form_id          = absint( rgpost( 'export_form' ) );
		$form             = GFAPI::get_form( $form_id );
		$_POST['form_id'] = $form_id;

		if ( ! $form ) {
			wp_send_json_error( esc_html__( 'You must select a form.', 'forgravity_entryautomation' ) );
		}

		// Initialize task meta.
		$task_meta = [
			'feedName'                         => $this->get_default_feed_name(),
			'action'                           => 'export',
			'type'                             => 'manual',
			'target'                           => 'all',
			'exportFileType'                   => 'csv',
			'exportFileName'                   => '{form_title}.csv',
			'exportWriteType'                  => 'overwrite',
			'exportFields'                     => Action\Export::get_default_export_fields(),
			'feed_condition_conditional_logic' => rgpost( 'mode' ) && count( rgpost( 'f' ) ) > 0 ? '1' : '0',
		];

		// Define date range.
		if ( rgpost( 'export_date_start' ) || rgpost( 'export_date_end' ) ) {

			$task_meta['target']    = 'custom';
			$task_meta['dateRange'] = [
				'start' => sanitize_text_field( rgpost( 'export_date_start' ) ),
				'end'   => sanitize_text_field( rgpost( 'export_date_end' ) ),
			];

		}

		// Enable export fields.
		foreach ( $task_meta['exportFields'] as &$export_field ) {
			if ( in_array( $export_field['id'], rgpost( 'export_field' ) ) ) {
				$export_field['enabled'] = true;
			}
		}

		// Conditional logic.
		if ( $task_meta['feed_condition_conditional_logic'] ) {

			// Determine conditional logic type.
			$logic_type = GFCommon::whitelist( strtolower( rgpost( 'mode' ) ), [ 'all', 'any' ] );

			// Parse rules from request, remove logic type.
			$rules = GFCommon::get_field_filters_from_post( $form );
			unset( $rules['mode'] );

			// Adjust rule keys, remove "Any form field" rules.
			foreach ( $rules as $i => &$rule ) {

				if ( $rule['key'] === '0' ) {
					unset( $rules[ $i ] );
					continue;
				}

				$rule['fieldId'] = $rule['key'];
				unset( $rule['key'] );

			}

			$task_meta['feed_condition_conditional_logic_object'] = [
				'conditionalLogic' => [
					'actionType' => 'show',
					'logicType'  => $logic_type,
					'rules'      => $rules,
				],
			];

		}

		if ( ( $task_id = $this->insert_feed( $form_id, false, $task_meta ) ) ) {

			$task_url_params = [
				'page'    => 'gf_edit_forms',
				'view'    => 'settings',
				'subview' => $this->_slug,
				'id'      => $form_id,
				'fid'     => $task_id,
			];
			$task_url        = add_query_arg( $task_url_params, admin_url( 'admin.php' ) );

			wp_send_json_success(
				sprintf(
					esc_html__( 'Your Entry Automation Task has been created. %1$sClick here to edit it.%2$s', 'forgravity_entryautomation' ),
					'<a href="' . esc_url( $task_url ) . '">',
					'</a>'
				)
			);

		} else {

			wp_send_json_error( esc_html__( 'Unable to create Entry Automation Task.', 'forgravity_entryautomation' ) );

		}

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Delete Entry Automation feeds upon form deletion.
	 *
	 * @since  1.3.4
	 * @access public
	 *
	 * @param int $form_id Form ID.
	 *
	 * @uses   Entry_Automation::delete_feeds()
	 * @uses   GFFeedAddOn::get_feeds()
	 */
	public function action_gform_after_delete_form( $form_id ) {

		// Get feeds for form.
		$feeds = $this->get_feeds( $form_id );

		// If no feeds were found, return.
		if ( ! $feeds ) {
			return;
		}

		// Loop through feeds.
		foreach ( $feeds as $feed_id ) {

			// Delete feed.
			$this->delete_feed( $feed_id );

		}

	}

	/**
	 * Convert a string to time.
	 *
	 * @since  1.0.6
	 * @access public
	 *
	 * @deprecated 3.0 Use Date class.
	 *
	 * @param string $string        A date/time string.
	 * @param string $format        Format to convert to. Defaults to UNIX timestamp.
	 * @param bool   $bypass_minus  Bypass prepending minus to string.
	 * @param bool   $set_timestamp Set time via timestamp.
	 *
	 * @return int|string
	 */
	public function strtotime( $string = null, $format = 'timestamp', $bypass_minus = false, $set_timestamp = false ) {

		$date = new Date( $format, $string );  // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		return $date->format();

	}

	/**
	 * Get Entry Automation search criteria for form.
	 *
	 * @since  1.0
	 * @since  3.0 Deprecated the $form parameter.
	 *
	 * @param array $task       Entry Automation Task meta.
	 * @param array $deprecated Deprecated. It was the Form object.
	 *
	 * @return array
	 */
	public function get_search_criteria( $task, $deprecated = null ) {

		try {

			// Get task.
			$task = Task::get( $task['id'] );

		} catch ( Exception $e ) {

			return [];

		}

		return $task->get_search_criteria();

	}

	/**
	 * Get next run time for Entry Automation task.
	 *
	 * @since  1.0.6
	 * @access public
	 *
	 * @param int    $task_id Entry Automation Task ID.
	 * @param string $format  Format to return next run time in.
	 *
	 * @return string|bool
	 */
	public function get_next_run_time( $task_id, $format = 'timestamp' ) {

		// Get next run time.
		$next_event = Scheduler::get_task_event( $task_id );

		// If task has not run yet, return.
		if ( ! $next_event ) {
			return false;
		}

		return $format === 'timestamp' ? $next_event['timestamp'] : $this->strtotime( $next_event['timestamp'], $format, true, true );

	}

	/**
	 * Returns the current form object based on the id query var. Otherwise returns false.
	 *
	 * @since 3.0
	 *
	 * @return array|null If ID is found and is valid form, then the populated Form array is returned.
	 *                    If the form ID is invalid, null is returned by GFFormsModel::get_form_meta.
	 *                    If the form ID is not found, a minimal form array with id (set to 0) and empty title is returned.
	 */
	public function get_current_form() {

		$form_id = null;

		if ( rgpost( 'form_id' ) ) {
			$form_id = (int) rgpost( 'form_id' );
		} elseif ( ! rgempty( 'id', $_GET ) ) {
			$form_id = (int) rgget( 'id' );
		}

		if ( $form_id ) {
			return GFAPI::get_form( $form_id );
		}

		return [
			'id'    => 0,
			'title' => '',
		];

	}

	/**
	 * Prevent the GFAddOn::update_path() method from running.
	 *
	 * @since 3.1
	 */
	public function update_path() {
	}





	// # UPGRADE ROUTINES ----------------------------------------------------------------------------------------------

	/**
	 * Upgrade routines.
	 *
	 * @since  1.0.6
	 *
	 * @param string $previous_version Previously installed version number.
	 */
	public function upgrade( $previous_version ) {

		if ( empty( $previous_version ) ) {
			return;
		}

		// Run first run time upgrade.
		if ( version_compare( $previous_version, '1.0.6', '<' ) ) {
			$this->upgrade_first_run();
		}

		// Run export fields upgrade.
		if ( version_compare( $previous_version, '1.2', '<' ) ) {
			$this->upgrade_export_fields();
		}

		// Run cron upgrade.
		if ( version_compare( $previous_version, '1.3', '<' ) ) {
			$this->upgrade_to_single_events();
		}

		// Run Entry Automation 2.0 upgrade.
		if ( version_compare( $previous_version, '2.0', '<' ) ) {
			$this->upgrade_20();
		}

		// Run Entry Automation 2.0.6 upgrade.
		if ( version_compare( $previous_version, '2.0.6', '<' ) ) {
			$this->upgrade_export_folder();
		}

		// Run Entry Automation 3.0 upgrade.
		if ( version_compare( $previous_version, '3.0', '<' ) ) {
			$this->upgrade_30();
		}

		// Run Entry Automation 3.0.1 upgrade.
		if ( version_compare( $previous_version, '3.0.1', '<' ) ) {
			$this->upgrade_301();

			// Run auto update upgrade.
			$settings = $this->get_plugin_settings();
			if ( $settings['background_updates'] ) {
				$this->update_wp_auto_updates( true );
			}
		}

		// Run Entry Automation 3.2 upgrade.
		if ( version_compare( $previous_version, '3.2', '<' ) ) {
			$this->upgrade_32();
		}

	}

	/**
	 * Upgrade 1.0 feeds to new format.
	 *
	 * @since  1.0.6
	 */
	public function upgrade_first_run() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// Loop through feeds.
		foreach ( $feeds as $feed ) {

			// Convert first run time.
			if ( 'defined' === $feed['meta']['firstRun']['type'] ) {

				// Define firt run time.
				$feed['meta']['firstRun'] = [
					'date'   => $this->strtotime( $feed['meta']['firstRun']['when'], Date::FORMAT_DATE, true ),
					'hour'   => $this->strtotime( $feed['meta']['firstRun']['when'], 'g', true ),
					'minute' => $this->strtotime( $feed['meta']['firstRun']['when'], 'i', true ),
					'period' => $this->strtotime( $feed['meta']['firstRun']['when'], 'A', true ),
				];

			} else {

				// Define firt run time.
				$feed['meta']['firstRun'] = [
					'date'   => $this->strtotime( '+1 hour', Date::FORMAT_DATE, true ),
					'hour'   => $this->strtotime( '+1 hour', 'g', true ),
					'minute' => '00',
					'period' => $this->strtotime( '+1 hour', 'A', true ),
				];

			}

			// Convert date range.
			foreach ( [ 'start', 'end' ] as $range_type ) {

				// If range type is not an array, skip it.
				if ( ! is_array( $feed['meta']['dateRange'][ $range_type ] ) ) {
					continue;
				}

				// Prepare new date range.
				$feed['meta']['dateRange'][ $range_type ] = rgblank( $feed['meta']['dateRange'][ $range_type ]['number'] ) ? null : $feed['meta']['dateRange'][ $range_type ]['number'] . ' ' . $feed['meta']['dateRange'][ $range_type ]['unit'];

			}

			// Save feed.
			$this->update_feed_meta( $feed['id'], $feed['meta'] );

		}

	}

	/**
	 * Upgrade 1.0 feeds to new format.
	 *
	 * @since  1.2
	 */
	public function upgrade_export_fields() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// Loop through feeds.
		foreach ( $feeds as $feed ) {

			// If this is not an export feed, skip.
			if ( 'export' !== rgars( $feed, 'meta/action' ) ) {
				continue;
			}

			// Get form.
			$form = GFAPI::get_form( $feed['form_id'] );

			// Get export fields.
			$export_fields = rgars( $feed, 'meta/exportFields' );

			// Initialize array for new export fields.
			$new_export_fields = [];

			// Loop through export fields.
			foreach ( $export_fields as $field_id => $enabled ) {

				// Get field.
				$field = GFFormsModel::get_field( $form, $field_id );

				// Get field label.
				$field_label = is_float( $field_id ) ? GFCommon::get_label( $field, $field_id ) : GFCommon::get_label( $field );

				// Add new export field.
				$new_export_fields[] = [
					'id'            => $field_id,
					'enabled'       => '1' == $enabled || true == $enabled ? true : false,
					'label'         => '',
					'default_label' => $field_label,
				];

			}

			// Add new export fields to meta.
			$feed['meta']['exportFields'] = $new_export_fields;

			// Save feed.
			$this->update_feed_meta( $feed['id'], $feed['meta'] );

		}

	}

	/**
	 * Upgrade 1.2 feeds to single scheduled events.
	 *
	 * @since  1.2
	 */
	public function upgrade_to_single_events() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// Loop through feeds.
		foreach ( $feeds as $feed ) {

			// Get next run time.
			$run_time = get_option( '_transient_timeout_' . $this->get_slug() . '_timeout_' . $feed['id'], false );

			// If action was supposed to run already, get next run time.
			if ( ! $run_time ) {

				// Get first run time array.
				$run_time = $feed['meta']['firstRun'];

				// Convert legacy first run times.
				$run_time = is_array( $run_time ) ? sprintf(
					'%s %d:%s %s',
					rgar( $run_time, 'date' ),
					rgar( $run_time, 'hour' ),
					rgar( $run_time, 'minute' ),
					rgar( $run_time, 'period' )
				) : $run_time;

				$run_time = $this->strtotime( $run_time, 'timestamp' );

			} else {

				// Get next run time without seconds.
				$run_time += 5;
				$run_time  = $this->strtotime( $run_time, Date::FORMAT_DATETIME_NO_SECONDS, true, true );

				// Convert to timestamp.
				$run_time = $this->strtotime( $run_time, 'timestamp', true );

			}

			// Delete timeout transient.
			delete_transient( $this->get_slug() . '_timeout_' . $feed['id'] );

			// Schedule single event for feed.
			Scheduler::schedule_task( $feed['id'], $feed['form_id'], $run_time );

			// Update first run time.
			if ( is_array( $feed['meta']['firstRun'] ) ) {

				// Convert first run time to string.
				$feed['meta']['firstRun'] = sprintf( '%s %s:%s %s', $feed['meta']['firstRun']['date'], $feed['meta']['firstRun']['hour'], $feed['meta']['firstRun']['minute'], $feed['meta']['firstRun']['period'] );

				// Update feed.
				$this->update_feed_meta( $feed['id'], $feed['meta'] );

			}

		}

		// Remove old cron event.
		wp_clear_scheduled_hook( 'fg_entryautomation_maybe_automate' );

	}

	/**
	 * Upgrade feeds for Version 2.0.
	 *
	 * @since 2.0
	 */
	public function upgrade_20() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// If no feeds were found, exit.
		if ( empty( $feeds ) ) {
			return;
		}

		// Loop through feeds, update settings.
		foreach ( $feeds as $feed ) {

			// Set task type.
			$feed['meta']['type'] = 'scheduled';

			// Set deletion type.
			if ( 'delete' === rgars( $feed, 'meta/action' ) ) {
				$feed['meta']['deleteType'] = 'entry';
			}

			// Set export write type.
			if ( 'export' === rgars( $feed, 'meta/action' ) ) {
				$feed['meta']['exportWriteType'] = '1' == rgars( $feed, 'meta/exportOverwriteExisting' ) ? 'overwrite' : 'new';
				unset( $feed['meta']['exportOverwriteExisting'] );
			}

			// Update feed.
			$this->update_feed_meta( $feed['id'], $feed['meta'] );

		}

	}

	/**
	 * Upgrade the export folder for current exported files.
	 *
	 * @since 2.0.6
	 */
	public function upgrade_export_folder() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// Loop through feeds.
		foreach ( $feeds as $feed ) {

			// If this is not an export feed, skip.
			if ( 'export' !== rgars( $feed, 'meta/action' ) ) {
				continue;
			}

			// Get the form.
			$form = GFAPI::get_form( $feed['form_id'] );

			// Get the task.
			$task = Task::get( $feed['id'] );

			// Get exported files for this form.
			$option_name = fg_entryautomation()->get_slug() . '_file_' . $feed['id'];
			$export_file = get_option( $option_name );

			if ( $export_file && file_exists( $export_file ) ) {

				// Move the file to the new folder.
				$_export_file = explode( '/', $export_file );
				$file_name    = end( $_export_file );
				$new_file     = Action\Export::get_export_folder( $task, $form ) . $file_name;
				rename( $export_file, $new_file );

				// Update the option to store only the file name.
				update_option( $option_name, $file_name );

			}
		}

	}

	/**
	 * Upgrade feeds for Version 3.0.
	 *
	 * @since 3.0
	 */
	public function upgrade_30() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// If no feeds were found, exit.
		if ( empty( $feeds ) ) {
			return;
		}

		// Loop through feeds, update settings.
		foreach ( $feeds as $feed ) {

			// Set the export delete file setting.
			if ( 'export' === rgars( $feed, 'meta/action' ) ) {

				if ( rgars( $feed, 'meta/exportEmailEnable' ) == '1' && rgars( $feed, 'meta/exportEmailDelete' ) == '1' ) {

					$feed['meta']['exportDeleteFile'] = '1';
					unset( $feed['meta']['exportEmailDelete'] );

				}

			}

			// Update feed.
			$this->update_feed_meta( $feed['id'], $feed['meta'] );

		}

	}

	/**
	 * Upgrade the export folder on the per form basis for current exported files.
	 *
	 * @since 3.0.1
	 */
	public function upgrade_301() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// Get the upload root.
		$upload_root = Action\Export::get_upload_root();

		// Loop through feeds.
		foreach ( $feeds as $feed ) {

			// If this is not an export feed, skip.
			if ( 'export' !== rgars( $feed, 'meta/action' ) ) {
				continue;
			}

			// Get the form.
			$form = GFAPI::get_form( $feed['form_id'] );

			// Get the task.
			try {

				$task = Task::get( $feed['id'] );

				// Get exported files for this form.
				$option_name = fg_entryautomation()->get_slug() . '_file_' . $feed['id'];
				$export_file = get_option( $option_name );

				// Current file path.
				$current_file = $upload_root . $export_file;

				if ( $export_file && file_exists( $current_file ) ) {

					// Move the file to the new folder.
					$new_file = Action\Export::get_export_folder( $task, $form ) . $export_file;
					rename( $current_file, $new_file );

				}

			} catch ( Exception $e ) {

				$this->log_error( 'Task #' . $feed['id'] . ' does not exist.' );

				continue;

			}
		}

		// Get the orphaned files.
		$orphaned_files = glob( $upload_root . '*.{csv,json,pdf,xlsx}', GLOB_BRACE );
		if ( ! $orphaned_files ) {
			return;
		}

		// Get the archived folder name.
		$archived_folder = trailingslashit( $upload_root . 'archived-' . wp_hash( 'archived' ) );

		// Create it if not exist.
		if ( ! is_dir( $archived_folder ) ) {
			mkdir( $archived_folder );

			// Add index file to the archived folder.
			GFExport::maybe_create_index_file( $archived_folder );
		}

		// Move the rest old files to the archived folder.
		foreach ( $orphaned_files as $file_path ) {
			$path_arr  = explode( DIRECTORY_SEPARATOR, $file_path );
			$file_name = end( $path_arr );
			$new_path  = $archived_folder . $file_name;
			rename( $file_path, $new_path );
		}

	}

	/**
	 * Fix the conditional logic feed settings.
	 *
	 * @since 3.2
	 */
	public function upgrade_32() {

		// Get Entry Automation feeds.
		$feeds = $this->get_feeds();

		// If no feeds were found, exit.
		if ( empty( $feeds ) ) {
			return;
		}

		// Loop through feeds, update settings.
		foreach ( $feeds as $feed ) {

			// Check if the conditional logic stored is incorrect.
			if ( rgars( $feed, 'meta/feed_condition_conditional_logic_object/actionType' ) ) {

				// Adding back the missing `conditionalLogic` key.
				$feed['meta']['feed_condition_conditional_logic_object'] = array( 'conditionalLogic' => $feed['meta']['feed_condition_conditional_logic_object'] );

				// Update feed.
				$this->update_feed_meta( $feed['id'], $feed['meta'] );

			}

		}

	}





	// # PLUGIN SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Prepare plugin settings fields.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses   Entry_Automation::license_feedback()
	 * @uses   Entry_Automation::license_key_description()
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {

		$settings = [
			[
				'fields' => [
					[
						'name'                => 'license_key',
						'label'               => esc_html__( 'License Key', 'forgravity_entryautomation' ),
						'type'                => 'text',
						'class'               => 'medium',
						'default_value'       => '',
						'input_type'          => $this->license_feedback() ? 'password' : 'text',
						'error_message'       => esc_html__( 'Invalid License', 'forgravity_entryautomation' ),
						'feedback_callback'   => [ $this, 'license_feedback' ],
						'validation_callback' => [ $this, 'license_validation' ],
						'description'         => $this->license_key_description(),
					],
					[
						'name'          => 'background_updates',
						'label'         => esc_html__( 'Background Updates', 'forgravity_entryautomation' ),
						'type'          => 'radio',
						'horizontal'    => true,
						'default_value' => false,
						'tooltip'       => esc_html__( 'Set this to ON to allow Entry Automation to download and install bug fixes and security updates automatically in the background. Requires a valid license key.', 'forgravity_easypassthrough' ),
						'choices'       => [
							[
								'label' => esc_html__( 'On', 'forgravity_entryautomation' ),
								'value' => true,
							],
							[
								'label' => esc_html__( 'Off', 'forgravity_entryautomation' ),
								'value' => false,
							],
						],
					],
					[
						'name'  => 'extensions',
						'label' => esc_html__( 'Extensions', 'forgravity_entryautomation' ),
						'type'  => 'extensions',
					],
				],
			],
		];

		if ( defined( 'FG_ENTRYAUTOMATION_LICENSE_KEY' ) || ( is_multisite() && ! is_main_site() ) ) {
			$settings[0]['fields'][0]['disabled'] = true;
		}

		return apply_filters( 'fg_entryautomation_plugin_settings', $settings );

	}

	/**
	 * Updates the plugin settings with the provided settings.
	 *
	 * @since 3.1
	 *
	 * @param array $settings The settings to be saved.
	 */
	public function update_plugin_settings( $settings ) {

		if ( $this->is_save_postback() ) {

			$previous_settings = $this->get_previous_settings();

			if ( $settings['background_updates'] != $previous_settings['background_updates'] ) {
				$this->update_wp_auto_updates( $settings['background_updates'] );
			}

		}

		parent::update_plugin_settings( $settings );

	}

	/**
	 * Return the plugin's icon for the plugin/form settings menu.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_menu_icon() {

		return file_get_contents( $this->get_base_path() . '/images/menu-icon.svg' );

	}

	/**
	 * Get license validity for plugin settings field.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $value Plugin setting value.
	 * @param array  $field Plugin setting field.
	 *
	 * @uses   Entry_Automation::check_license()
	 *
	 * @return null|bool
	 */
	public function license_feedback( $value = '', $field = [] ) {

		// If no license key is provided, check the setting.
		if ( empty( $value ) ) {
			$value = $this->get_setting( 'license_key' );
		}

		// If no license key is provided, return.
		if ( empty( $value ) ) {
			return null;
		}

		// Get license data.
		$license_data = $this->check_license( $value );

		// If no license data was returned or license is invalid, return false.
		if ( empty( $license_data ) || 'invalid' === $license_data->license ) {
			return false;
		} elseif ( 'valid' === $license_data->license ) {
			return true;
		}

		return false;

	}

	/**
	 * Activate license on plugin settings save.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array  $field         Plugin setting field.
	 * @param string $field_setting Plugin setting value.
	 *
	 * @uses   GFAddOn::get_plugin_setting()
	 * @uses   GFAddOn::log_debug()
	 * @uses   Entry_Automation::activate_license()
	 * @uses   Entry_Automation::process_license_request()
	 */
	public function license_validation( $field, $field_setting ) {

		// Get old license.
		$old_license = $this->get_plugin_setting( 'license_key' );

		// If an old license key exists and a new license is being saved, deactivate old license.
		if ( $old_license && $field_setting != $old_license ) {

			// Deactivate license.
			$deactivate_license = $this->process_license_request( 'deactivate_license', $old_license );

			// Log response.
			$this->log_debug( __METHOD__ . '(): Deactivate license: ' . print_r( $deactivate_license, true ) );

		}

		// If field setting is empty, return.
		if ( empty( $field_setting ) ) {
			return;
		}

		// Activate license.
		$this->activate_license( $field_setting );

	}

	/**
	 * Prepare description for License Key plugin settings field.
	 *
	 * @since  1.2.3
	 * @access public
	 *
	 * @uses   Entry_Automation::check_license()
	 * @uses   GFAddOn::get_setting()
	 *
	 * @return string
	 */
	public function license_key_description() {

		// Get license key.
		$license_key = defined( 'FG_ENTRYAUTOMATION_LICENSE_KEY' ) ? FG_ENTRYAUTOMATION_LICENSE_KEY : $this->get_setting( 'license_key' );

		// If no license key is entered, display warning.
		if ( rgblank( $license_key ) ) {
			return esc_html__( 'The license key is used for access to extensions, automatic upgrades and support.', 'forgravity_entryautomation' );
		}

		// Get license data.
		$license_data = $this->check_license( $license_key );

		// If no expiration date is provided, return.
		if ( ! rgobj( $license_data, 'expires' ) ) {
			return '';
		}

		if ( 'lifetime' === $license_data->expires ) {

			return sprintf(
				'<em>%s</em>',
				esc_html__( 'Your license is valid forever.', 'forgravity_entryautomation' )
			);

		} else {

			return sprintf(
				'<em>%s</em>',
				sprintf(
					esc_html__( 'Your license is valid through %s.', 'forgravity_entryautomation' ),
					date( Date::FORMAT_DATE, strtotime( $license_data->expires ) )  // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				)
			);

		}

	}





	// # EXTENSION MANAGEMENT ------------------------------------------------------------------------------------------

	/**
	 * Displays available Entry Automation extensions.
	 *
	 * @since  3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public function settings_extensions( $field, $echo = true ) {

		// Get license key.
		$license_key = $this->get_license_key();

		// If no license key is available, return.
		if ( rgblank( $license_key ) ) {

			// Prepare return message.
			$html = esc_html__( 'To see available extensions, please enter a valid license key.', 'forgravity_entryautomation' );

			if ( $echo ) {
				echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			return $html;

		}

		// Get license data.
		$license_data = $this->check_license( $license_key );

		// If license is not valid, return.
		if ( 'valid' !== rgobj( $license_data, 'license' ) ) {

			// Prepare return message.
			$html = esc_html__( 'To see available extensions, please enter a valid license key.', 'forgravity_entryautomation' );

			if ( $echo ) {
				echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			return $html;

		}

		// If no extensions could be found, return.
		if ( ! rgobj( $license_data, 'extensions' ) ) {

			if ( $echo ) {
				echo esc_html__( 'No extensions could be found.', 'forgravity_entryautomation' );
			}

			return esc_html__( 'No extensions could be found.', 'forgravity_entryautomation' );

		}

		$html = '';
		foreach ( $license_data->extensions as $extension ) {

			// Initialize button text and link variables.
			$button_text   = '';
			$button_link   = '#';
			$button_action = '';

			// If extension is active, offer deactivate link.
			if ( Extension::is_activated( $extension->plugin_file ) ) {
				$button_text   = esc_html__( 'Deactivate', 'forgravity_entryautomation' );
				$button_action = 'deactivate';
			} elseif ( Extension::is_installed( $extension->plugin_file ) ) {
				$button_text   = esc_html__( 'Activate', 'forgravity_entryautomation' );
				$button_action = 'activate';
			} elseif ( ! Extension::is_installed( $extension->plugin_file ) ) {
				if ( $extension->has_access ) {
					$button_text   = esc_html__( 'Install', 'forgravity_entryautomation' );
					$button_action = 'install';
				} else {
					$button_text   = esc_html__( 'Upgrade License', 'forgravity_entryautomation' );
					$button_action = 'upgrade';
					$button_link   = $extension->upgrade_url;
				}
			}

			$button = sprintf(
				'<a data-action="%s" data-plugin="%s" href="%s" class="entryautomation-extension-button">%s</a>',
				$button_action,
				$extension->plugin_file,
				$button_link,
				$button_text
			);

			$html .= sprintf(
				'<div class="entryautomation-extension %5$s">
					<div class="entryautomation-extension-container">
						<img src="%1$s" alt="%2$s" width="80" height="80" />
						<div class="entryautomation-extension-meta">
							<div class="entryautomation-extension-meta__label">%2$s</div>
							<p class="entryautomation-extension-meta__description">%3$s</p>
						</div>
						%6$s
					</div>
					<div class="entryautomation-extension-footer">Version %4$s</div>
				</div>',
				$extension->icon,
				str_replace( ' Extension', '', $extension->name ),
				$extension->description,
				$extension->version,
				! Extension::is_installed( $extension->plugin_file ) && ! $extension->has_access ? 'entryautomation-extension--locked' : 'entryautomation-extension--unlocked',
				$button
			);

		}

		if ( $echo ) {
			echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $html;

	}

	/**
	 * Process extension management actions.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @uses   Extension::install_extension()
	 */
	public function ajax_handle_extension_action() {

		// Verify nonce.
		if ( ! wp_verify_nonce( rgpost( 'nonce' ), $this->get_slug() ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'forgravity_entryautomation' ) ] );
		}

		// Verify capabilities.
		if ( ! GFCommon::current_user_can_any( $this->_capabilities_settings_page ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'forgravity_entryautomation' ) ] );
		}

		// Get plugin and action.
		$plugin = sanitize_text_field( rgars( $_POST, 'extension/plugin' ) );
		$action = sanitize_text_field( rgars( $_POST, 'extension/action' ) );

		switch ( $action ) {

			case 'activate':
				// Activate plugin.
				$activated = activate_plugin( $plugin );

				// Respond based on activation.
				if ( is_wp_error( $activated ) ) {
					wp_send_json_error( [
						'error'     => sprintf(
							'%s: %s',
							esc_html__( 'Unable to activate extension', 'forgravity_entryautomation' ),
							$activated->get_error_message()
						),
						'newAction' => 'activate',
						'newText'   => esc_html__( 'Activate', 'forgravity_entryautomation' ),
					] );
				} else {
					wp_send_json_success( [
						'newAction' => 'deactivate',
						'newText'   => esc_html__( 'Deactivate', 'forgravity_entryautomation' ),
					] );
				}

				break;

			case 'deactivate':
				// Deactivate plugin.
				deactivate_plugins( $plugin );

				wp_send_json_success( [
					'newAction' => 'activate',
					'newText'   => esc_html__( 'Activate', 'forgravity_entryautomation' ),
				] );

				break;

			case 'install':
				// Install plugin.
				$installed = Extension::install_extension( $plugin );

				// Response based on installation.
				if ( is_wp_error( $installed ) ) {
					wp_send_json_error( [
						'error'     => sprintf(
							'%s: %s',
							esc_html__( 'Unable to install extension', 'forgravity_entryautomation' ),
							$installed->get_error_message()
						),
						'newAction' => 'install',
						'newText'   => esc_html__( 'Install', 'forgravity_entryautomation' ),
					] );
				} else {
					wp_send_json_success( [
						'newAction' => 'activate',
						'newText'   => esc_html__( 'Activate', 'forgravity_entryautomation' ),
					] );
				}

				break;

		}

	}





	// # LICENSE METHODS -----------------------------------------------------------------------------------------------

	/**
	 * Activate a license key.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $license_key The license key.
	 *
	 * @uses   Entry_Automation::process_license_request()
	 *
	 * @return array
	 */
	public function activate_license( $license_key ) {

		// Activate license.
		$license = $this->process_license_request( 'activate_license', $license_key );

		// Clear update plugins transient.
		set_site_transient( 'update_plugins', null );

		// Delete plugin version info cache.
		$cache_key = md5( 'edd_plugin_' . sanitize_key( $this->_path ) . '_version_info' );
		delete_transient( $cache_key );

		return json_decode( wp_remote_retrieve_body( $license ) );

	}

	/**
	 * Check the status of a license key.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $license_key The license key.
	 *
	 * @uses   GFAddOn::get_plugin_setting()
	 * @uses   Entry_Automation::process_license_request()
	 *
	 * @return object
	 */
	public function check_license( $license_key = '' ) {

		// If license key is empty, get the plugin setting.
		if ( empty( $license_key ) ) {
			$license_key = $this->get_plugin_setting( 'license_key' );
		}

		// Perform a license check request.
		$license = $this->process_license_request( 'check_license', $license_key );

		return json_decode( wp_remote_retrieve_body( $license ) );

	}

	/**
	 * Get license key.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @uses   GFAddOn::get_plugin_setting()
	 *
	 * @return string
	 */
	public function get_license_key() {

		return defined( 'FG_ENTRYAUTOMATION_LICENSE_KEY' ) ? FG_ENTRYAUTOMATION_LICENSE_KEY : $this->get_plugin_setting( 'license_key' );

	}

	/**
	 * Process a request to the ForGravity store.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $action  The action to process.
	 * @param string $license The license key.
	 * @param int    $item_id The EDD item ID.
	 *
	 * @return array|WP_Error
	 */
	public function process_license_request( $action, $license, $item_id = FG_ENTRYAUTOMATION_EDD_ITEM_ID ) {

		// Prepare the request arguments.
		$args = [
			'method'    => 'POST',
			'timeout'   => 10,
			'sslverify' => false,
			'body'      => [
				'edd_action' => $action,
				'license'    => trim( $license ),
				'item_id'    => urlencode( $item_id ),
				'url'        => home_url(),
			],
		];

		return wp_remote_request( FG_EDD_STORE_URL, $args );

	}





	// # BACKGROUND UPDATES --------------------------------------------------------------------------------------------

	/**
	 * Display activate license message on Plugins list page.
	 *
	 * @since 2.0.1
	 *
	 * @param string $plugin_name The plugin filename.
	 */
	public function action_after_plugin_row( $plugin_name ) {

		// Get license key.
		$license_key = $this->get_license_key();

		// If no license key is installed, display message.
		if ( rgblank( $license_key ) ) {

			// Prepare message.
			$message = sprintf(
				esc_html__( '%1$sRegister your copy%2$s of Entry Automation to receive access to automatic upgrades and support. Need a license key? %3$sPurchase one now.%4$s', 'forgravity_entryautomation' ),
				'<a href="' . admin_url( 'admin.php?page=gf_settings&subview=' . $this->_slug ) . '">',
				'</a>',
				'<a href="' . esc_url( $this->_url ) . '" target="_blank">',
				'</a>'
			);

		} else {

			// Get license data.
			$license_data = $this->check_license( $license_key );

			// If license key is invalid, display message.
			if ( empty( $license_data ) || 'valid' !== $license_data->license ) {

				// Prepare message.
				$message = sprintf(
					esc_html__( 'Your license is invalid or expired. %1$sEnter a valid license key%2$s or %3$spurchase a new one.%4$s', 'forgravity_entryautomation' ),
					'<a href="' . admin_url( 'admin.php?page=gf_settings&subview=' . $this->_slug ) . '">',
					'</a>',
					'<a href="' . esc_url( $this->_url ) . '" target="_blank">',
					'</a>'
				);

			}

		}

		// If there is no message to display, exit.
		if ( ! isset( $message ) ) {
			return;
		}

		// Get active class.
		$active_class = ( is_network_admin() && is_plugin_active_for_network( $plugin_name ) ) || ( ! is_network_admin() && is_plugin_active( $plugin_name ) ) ? ' active' : '';

		// Display plugin message.
		printf(
			'<tr class="plugin-update-tr%3$s" id="%2$s-update" data-slug="%2$s" data-plugin="%1$s">
				<td colspan="3" class="plugin-update colspanchange">
					<div class="update-message notice inline notice-warning notice-alt">
						<p>%4$s</p>
					</div>
				</td>
			</tr>',
			esc_attr( $plugin_name ),
			esc_attr( $this->get_slug() ),
			esc_attr( $active_class ),
			$message // phpcs:ignore
		);

		// Hide border for plugin row.
		printf( '<script type="text/javascript">document.querySelector( \'tr[data-plugin="%s"]\' ).classList.add( \'update\' );</script>', esc_attr( $plugin_name ) );

	}

	/**
	 * Determines if automatic updating should be processed.
	 *
	 * @since  Unknown
	 *
	 * @param bool   $update Whether or not to update.
	 * @param object $item   The update offer object.
	 *
	 * @return bool
	 */
	public function maybe_auto_update( $update, $item ) {

		// If this is not the Entry Automation Add-On, exit.
		if ( ! isset( $item->slug ) || 'entryautomation' !== $item->slug || is_null( $update ) ) {
			return $update;
		}

		if ( $this->is_auto_update_disabled( $update ) ) {
			$this->log_debug( __METHOD__ . '() - Aborting; auto updates disabled.' );
			return false;
		}

		$current_major = implode( '.', array_slice( preg_split( '/[.-]/', $this->_version ), 0, 1 ) );
		$new_major     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 1 ) );

		$current_branch = implode( '.', array_slice( preg_split( '/[.-]/', $this->_version ), 0, 2 ) );
		$new_branch     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 2 ) );

		if ( $current_major == $new_major && $current_branch == $new_branch ) {
			$this->log_debug( __METHOD__ . '(): OK to update.' );
			return true;
		}

		$this->log_debug( __METHOD__ . '(): Skipping - not current branch.' );

		return false;

	}

	/**
	 * Determine if automatic updates are disabled.
	 *
	 * @since  1.0
	 *
	 * @param bool|null $enabled Indicates if auto updates are enabled.
	 *
	 * @return bool
	 */
	public function is_auto_update_disabled( $enabled = null ) {

		global $wp_version;

		if ( is_null( $enabled ) || version_compare( $wp_version, '5.5', '<' ) ) {
			$enabled = $this->get_plugin_setting( 'background_updates' );
		}

		$this->log_debug( __METHOD__ . ' - $enabled: ' . var_export( $enabled, true ) );

		return ! $enabled;

	}

	/**
	 * Updates the WordPress auto_update_plugins option to enable or disable automatic updates so the correct state is displayed on the plugins page.
	 *
	 * @since 3.1
	 *
	 * @param bool $is_enabled Indicates if background updates are enabled for Entry Automation in the plugin settings.
	 */
	public function update_wp_auto_updates( $is_enabled ) {

		$option       = 'auto_update_plugins';
		$auto_updates = (array) get_site_option( $option, [] );

		if ( $is_enabled ) {
			$auto_updates[] = FG_ENTRYAUTOMATION_PLUGIN_BASENAME;
			$auto_updates   = array_unique( $auto_updates );
		} else {
			$auto_updates = array_diff( $auto_updates, [ FG_ENTRYAUTOMATION_PLUGIN_BASENAME ] );
		}

		$callback = [ $this, 'action_update_site_option_auto_update_plugins' ];
		remove_action( 'update_site_option_auto_update_plugins', $callback );
		update_site_option( $option, $auto_updates );
		add_action( 'update_site_option_auto_update_plugins', $callback, 10, 3 );

	}

	/**
	 * Updates the background updates app setting when the WordPress auto_update_plugins option is changed.
	 *
	 * @since 3.1
	 *
	 * @param string $option    The name of the option.
	 * @param array  $value     The current value of the option.
	 * @param array  $old_value The previous value of the option.
	 */
	public function action_update_site_option_auto_update_plugins( $option, $value, $old_value ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && ! empty( rgpost( 'asset' ) ) && ! empty( rgpost( state ) ) ) {
			// Option is being updated by the ajax request performed when using the enable/disable auto-updates links on the plugins page.
			$asset = sanitize_text_field( urldecode( rgpost( 'asset' ) ) );

			if ( $asset !== FG_ENTRYAUTOMATION_PLUGIN_BASENAME ) {
				return;
			}

			$is_enabled = rgpost( 'state' ) === 'enable';
		} else {
			// Option is being updated by some other means.
			$is_enabled  = in_array( FG_ENTRYAUTOMATION_PLUGIN_BASENAME, $value );
			$was_enabled = in_array( FG_ENTRYAUTOMATION_PLUGIN_BASENAME, $old_value );

			if ( $is_enabled === $was_enabled ) {
				return;
			}
		}

		$settings = $this->get_plugin_settings();

		if ( $settings['background_updates'] != $is_enabled ) {
			$settings['background_updates'] = $is_enabled;
			$this->update_plugin_settings( $settings );
		}

	}





	// # TASK SETTINGS FIELDS ------------------------------------------------------------------------------------------

	/**
	 * Get schedule section fields.
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public function get_schedule_fields() {

		Fields::register( 'fg_entryautomation_next_run_time', '\ForGravity\Entry_Automation\Settings\Fields\Next_Run_Time' );

		// Has the task already run?
		$has_task_run = fg_entryautomation()->get_current_feed_id() && get_option( fg_entryautomation()->get_slug() . '_last_run_time_' . fg_entryautomation()->get_current_feed_id() );

		// Prepare Next Run Time field.
		$next_run_field = [
			'name'     => 'nextRun',
			'type'     => 'fg_entryautomation_next_run_time',
			'required' => true,
			'hasRun'   => $has_task_run,
		];

		// Set label, tooltip based on task previously running.
		if ( $has_task_run ) {
			$next_run_field['label']   = esc_html__( 'Next Run Time', 'forgravity_entryautomation' );
			$next_run_field['tooltip'] = sprintf(
				'<h6>%s</h6>%s',
				esc_html__( 'Next Run Time', 'forgravity_entryautomation' ),
				esc_html__( 'The next time this action will run.', 'forgravity_entryautomation' )
			);
		} else {
			$next_run_field['label']   = esc_html__( 'Start Running Task', 'forgravity_entryautomation' );
			$next_run_field['tooltip'] = sprintf(
				'<h6>%s</h6>%s',
				esc_html__( 'Start Running Task Time', 'forgravity_entryautomation' ),
				esc_html__( 'Select a time for when the task should be run for the first time.', 'forgravity_entryautomation' )
			);
		}

		return [
			$next_run_field,
			[
				'name'          => 'frequency',
				'label'         => esc_html__( 'Frequency', 'forgravity_entryautomation' ),
				'type'          => 'select',
				'required'      => true,
				'default_value' => 'interval',
				'choices'       => [
					[
						'value' => 'interval',
						'label' => esc_html__( 'Run Task Every...', 'forgravity_entryautomation' ),
					],
					[
						'value' => 'days_of_week',
						'label' => esc_html__( 'Run Task on Day(s) of the Week', 'forgravity_entryautomation' ),
					],
					[
						'value' => 'days_of_month',
						'label' => esc_html__( 'Run Task on Day(s) of the Month', 'forgravity_entryautomation' ),
					],
				],
			],
			[
				'name'          => 'days_of_week',
				'label'         => esc_html__( 'Day of Week', 'forgravity_entryautomation' ),
				'type'          => 'checkbox',
				'required'      => true,
				'error_message' => esc_html__( 'You must select at least one day.', 'forgravity_entryautomation' ),
				'dependency'    => [
					'live'   => true,
					'fields' => [
						[
							'field'  => 'frequency',
							'values' => [ 'days_of_week' ],
						],
					],
				],
				'choices'       => $this->get_days_of_options( 'days_of_week' ),
			],
			[
				'name'          => 'days_of_month',
				'label'         => esc_html__( 'Day of Month', 'forgravity_entryautomation' ),
				'type'          => 'checkbox',
				'required'      => true,
				'error_message' => esc_html__( 'You must select at least one day.', 'forgravity_entryautomation' ),
				'dependency'    => [
					'live'   => true,
					'fields' => [
						[
							'field'  => 'frequency',
							'values' => [ 'days_of_month' ],
						],
					],
				],
				'choices'       => $this->get_days_of_options( 'days_of_month' ),
			],
			[
				'name'                => 'runTime',
				'label'               => esc_html__( 'Run Task Every', 'forgravity_entryautomation' ),
				'type'                => 'text_and_select',
				'dependency'          => [
					'live'   => true,
					'fields' => [
						[
							'field'  => 'frequency',
							'values' => [ 'interval' ],
						],
					],
				],
				'validation_callback' => [ __CLASS__, 'validate_text_select' ],
				'required'            => true,
				'inputs'              => [
					'text'   => [
						'name'          => 'runTime[number]',
						'class'         => 'small',
						'input_type'    => 'number',
						'after_input'   => ' ',
						'min'           => 1,
						'default_value' => 1,
					],
					'select' => [
						'name'          => 'runTime[unit]',
						'default_value' => 'hours',
						'choices'       => [
							[
								'value' => 'minutes',
								'label' => esc_html__( 'Minute(s)', 'forgravity_entryautomation' ),
							],
							[
								'value' => 'hours',
								'label' => esc_html__( 'Hour(s)', 'forgravity_entryautomation' ),
							],
							[
								'value' => 'days',
								'label' => esc_html__( 'Day(s)', 'forgravity_entryautomation' ),
							],
							[
								'value' => 'weeks',
								'label' => esc_html__( 'Week(s)', 'forgravity_entryautomation' ),
							],
							[
								'value' => 'months',
								'label' => esc_html__( 'Month(s)', 'forgravity_entryautomation' ),
							],
						],
					],
				],
				'tooltip'             => sprintf(
					'<h6>%s</h6>%s',
					esc_html__( 'Run Task Time', 'forgravity_entryautomation' ),
					esc_html__( 'Select how often to run the Entry Automation task. By default, Entry Automation runs tasks every 15 minutes.', 'forgravity_entryautomation' )
				),
			],
		];
	}

	/**
	 * Get the days of options.
	 *
	 * @since 3.0
	 *
	 * @param string $field The field name.
	 *
	 * @return array[]
	 */
	public function get_days_of_options( $field ) {

		if ( $field === 'days_of_week' ) {

			$start_of_week = (int) get_option( 'start_of_week' );
			$days          = [
				[
					'name'  => $field . '_sunday',
					'label' => esc_html__( 'Sun', 'forgravity_entryautomation' ),
				],
				[
					'name'          => $field . '_monday',
					'label'         => esc_html__( 'Mon', 'forgravity_entryautomation' ),
					'default_value' => 1,
				],
				[
					'name'          => $field . '_tuesday',
					'label'         => esc_html__( 'Tue', 'forgravity_entryautomation' ),
					'default_value' => 1,
				],
				[
					'name'          => $field . '_wednesday',
					'label'         => esc_html__( 'Wed', 'forgravity_entryautomation' ),
					'default_value' => 1,
				],
				[
					'name'          => $field . '_thursday',
					'label'         => esc_html__( 'Thu', 'forgravity_entryautomation' ),
					'default_value' => 1,
				],
				[
					'name'          => $field . '_friday',
					'label'         => esc_html__( 'Fri', 'forgravity_entryautomation' ),
					'default_value' => 1,
				],
				[
					'name'  => $field . '_saturday',
					'label' => esc_html__( 'Sat', 'forgravity_entryautomation' ),
				],
			];

			return array_merge(
				array_slice( $days, $start_of_week ),
				array_slice( $days, 0, $start_of_week )
			);

		} else {

			$days  = range( 1, 31 );
			$_days = [];

			foreach ( $days as $day ) {

				$_days[] = [
					'default_value' => $day === 1 ? '1' : '',
					'name'          => $field . '_' . $day,
					'label'         => $day,
				];

			}

			return $_days;

		}

	}

	/**
	 * Get target entries section fields.
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public function get_target_entries_fields() {

		Fields::register( 'fg_entryautomation_date_range', '\ForGravity\Entry_Automation\Settings\Fields\Date_Range' );

		return [
			[
				'name'    => 'target',
				'label'   => esc_html__( 'Target Type', 'forgravity_entryautomation' ),
				'type'    => 'radio',
				'choices' => [
					[
						'value' => 'all',
						'label' => esc_html__( 'All Entries', 'forgravity_entryautomation' ),
						'icon'  => file_get_contents( $this->get_base_path() . '/images/target/all.svg' ),
					],
					[
						'value'    => 'since_last_run',
						'label'    => esc_html__( 'All Entries Since Last Task Run', 'forgravity_entryautomation' ),
						'icon'     => file_get_contents( $this->get_base_path() . '/images/target/since_last_run.svg' ),
						'disabled' => $this->get_settings_renderer()->get_value( 'type' ) === 'manual',
					],
					[
						'value' => 'custom',
						'label' => esc_html__( 'Custom Date Range', 'forgravity_entryautomation' ),
						'icon'  => file_get_contents( $this->get_base_path() . '/images/target/custom.svg' ),
					],
				],
				'value'   => 'custom',
			],
			[
				'name'       => 'dateRange',
				'label'      => esc_html__( 'Entry Date Range', 'forgravity_entryautomation' ),
				'type'       => 'fg_entryautomation_date_range',
				'start_date' => true,
				'end_date'   => true,
				'tooltip'    => sprintf(
					'<h6>%s</h6>%s<br /><br />%s',
					esc_html__( 'Deletion Date Range', 'forgravity_entryautomation' ),
					esc_html__( 'Select a date range. Date range is relative to when the task is being run. Setting a range will limit the action to entries submitted during that date range.', 'forgravity_entryautomation' ),
					esc_html__( 'If no start date is set, all entries since the beginning of time will be included. If no end date is set, all entries until the time the action is run will be included.', 'forgravity_entryautomation' )
				),
				'dependency' => [
					'live'   => true,
					'fields' => [
						[
							'field'  => 'target',
							'values' => [ 'custom' ],
						],
					],
				],
			],
			[
				'name'       => 'dateField',
				'label'      => esc_html__( 'Select Entry By', 'forgravity_entryautomation' ),
				'type'       => 'select',
				'choices'    => $this->get_entry_date_fields(),
				'dependency' => [
					'live'   => true,
					'fields' => [
						[
							'field'  => 'target',
							'values' => [ 'custom' ],
						],
					],
				],
			],
			[
				'name'        => 'entryStatus[]',
				'label'       => esc_html__( 'Entry Status', 'forgravity_entryautomation' ),
				'type'        => 'select',
				'multiple'    => true,
				'enhanced_ui' => true,
				'choices'     => $this->get_entry_statuses(),
				'value'       => [
					'active',
				],
				'dependency'  => [
					'live'   => true,
					'fields' => [
						[
							'field'  => 'entryType',
							'values' => [ 'entry' ],
						],
					],
				],
			],
		];
	}

	/**
	 * Get entry date sources.
	 *
	 * @since 3.0
	 *
	 * @return array[]
	 */
	public function get_entry_date_fields() {

		$form        = $this->get_current_form();
		$date_fields = [];
		$choices     = [
			[
				'value' => 'date_created',
				'label' => esc_html__( 'Date Created', 'forgravity_entryautomation' ),
			],
			[
				'value' => 'date_updated',
				'label' => esc_html__( 'Date Updated', 'forgravity_entryautomation' ),
			],
		];

		// Get form fields and looping to get date fields.
		foreach ( $form['fields'] as $field ) {
			if ( $field->type === 'date' ) {
				$date_fields[] = [
					'value' => $field->id,
					'label' => esc_html( GFCommon::get_label( $field ) ),
				];
			}
		}

		if ( empty( $date_fields ) ) {
			return $choices;
		}

		// Put date fields in its own group.
		$date_fields = [
			'label'   => esc_html__( 'Date Field', 'forgravity-entryautomation' ),
			'choices' => $date_fields,
		];

		array_push( $choices, $date_fields );

		return $choices;

	}





	// # MEMBERS INTEGRATION -------------------------------------------------------------------------------------------

	/**
	 * Register the ForGravity capabilities group with the Members plugin.
	 *
	 * @since  1.2.6
	 * @access public
	 */
	public function members_register_cap_group() {

		members_register_cap_group(
			'forgravity',
			[
				'label' => esc_html( 'ForGravity' ),
				'icon'  => 'dashicons-forgravity',
				'caps'  => [],
			]
		);

	}

	/**
	 * Register the capabilities and their human readable labels wit the Members plugin.
	 *
	 * @since  1.2.6
	 * @access public
	 */
	public function members_register_caps() {

		// Define capabilities for Easy Passthrough.
		$caps = [
			'forgravity_entryautomation'           => esc_html__( 'Manage Settings', 'forgravity_entryautomation' ),
			'forgravity_entryautomation_uninstall' => esc_html__( 'Uninstall', 'forgravity_entryautomation' ),
		];

		// Register capabilities.
		foreach ( $caps as $cap => $label ) {
			members_register_cap(
				$cap,
				[
					'label' => sprintf( '%s: %s', $this->get_short_title(), $label ),
					'group' => 'forgravity',
				]
			);
		}

	}

	/**
	 * Get the includes folder path.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_includes_path() {

		return dirname( __FILE__ );

	}

}
