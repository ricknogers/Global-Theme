<?php

namespace ForGravity\Entry_Automation\Legacy;

use ForGravity\Entry_Automation\Action;
use ForGravity\Entry_Automation\Action\Export;
use ForGravity\Entry_Automation\Action\Delete;
use ForGravity\Entry_Automation\Extension;
use ForGravity\Entry_Automation\Task;

use DateTime;
use DateTimeZone;
use GFAddOn;
use GFFeedAddOn;
use GFForms;
use GFCommon;
use GFAPI;

GFForms::include_feed_addon_framework();

/**
 * Legacy Entry Automation for Gravity Forms.
 *
 * @since     2.1
 * @author    ForGravity
 * @copyright Copyright (c) 2020, Travis Lopes
 */
class Entry_Automation extends \ForGravity\Entry_Automation\Entry_Automation {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0
	 * @access private
	 * @var    ForGravity\Entry_Automation\Legacy\Entry_Automation $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Get instance of this class.
	 *
	 * @since  1.0
	 * @access public
	 * @static
	 *
	 * @return ForGravity\Entry_Automation\Legacy\Entry_Automation
	 */
	public static function get_instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;

	}

	/**
	 * Register needed AJAX actions.
	 *
	 * @since  3.0
	 * @access public
	 */
	public function init_ajax() {

		parent::init_ajax();

		add_action( 'wp_ajax_' . $this->_slug . '_time_preview', [ $this, 'ajax_time_preview' ] );

	}

	/**
	 * Enqueue needed scripts.
	 *
	 * @since  3.0
	 * @access public
	 *
	 * @return array
	 */
	public function scripts() {

		global $wp_version;

		// Get minification string.
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

		$scripts = [
			[
				'handle'  => $this->get_slug() . '_vendor_react',
				'src'     => 'https://cdnjs.cloudflare.com/ajax/libs/react/16.2.0/umd/react.production.min.js',
				'version' => '16.2.0',
			],
			[
				'handle'  => $this->get_slug() . '_vendor_react-dom',
				'src'     => 'https://cdnjs.cloudflare.com/ajax/libs/react-dom/16.2.0/umd/react-dom.production.min.js',
				'version' => '16.2.0',
				'deps'    => [ $this->get_slug() . '_vendor_react' ],
			],
			[
				'handle'  => $this->get_slug() . '_vendor-moment',
				'src'     => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js',
				'version' => '2.18.1',
			],
			[
				'handle'  => 'forgravity_entryautomation_feed_settings',
				'src'     => $this->get_base_url() . "/legacy/js/feed_settings{$min}.js",
				'version' => $min ? $this->_version : filemtime( $this->get_base_path() . '/legacy/js/feed_settings.js' ),
				'deps'    => [
					'jquery',
					version_compare( $wp_version, '5.0', '>=' ) ? 'react-dom' : $this->get_slug() . '_vendor_react-dom',
					version_compare( $wp_version, '5.0', '>=' ) ? 'moment' : $this->get_slug() . '_vendor-moment',
				],
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
				'src'     => $this->get_base_url() . '/legacy/js/plugin_settings.js',
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
						'activate'   => '<i class="fa fa-spinner fa-pulse fa-fw"></i>&nbsp;' . wp_strip_all_tags( __( 'Activating Extension...', 'forgravity_entryautomation' ) ),
						'deactivate' => '<i class="fa fa-spinner fa-pulse fa-fw"></i>&nbsp;' . wp_strip_all_tags( __( 'Deactivating Extension...', 'forgravity_entryautomation' ) ),
						'install'    => '<i class="fa fa-spinner fa-pulse fa-fw"></i>&nbsp;' . wp_strip_all_tags( __( 'Installing Extension...', 'forgravity_entryautomation' ) ),
					],
				],
			],
		];

		$scripts = array_merge( GFFeedAddOn::scripts(), $scripts );

		return apply_filters( 'fg_entryautomation_scripts', $scripts );

	}

	/**
	 * Enqueue needed stylesheets.
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public function styles() {

		// Get minification string.
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min';

		$styles = [
			[
				'handle'  => $this->get_slug() . '_feed_settings',
				'src'     => $this->get_base_url() . "/legacy/css/feed_settings{$min}.css",
				'version' => $min ? $this->get_version() : filemtime( $this->get_base_path() . '/css/feed_settings.css' ),
				'enqueue' => [
					[
						'admin_page' => [ 'form_settings' ],
						'tab'        => $this->get_slug(),
					],
				],
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

		return array_merge( GFFeedAddOn::styles(), $styles );

	}

	// # TABBED SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Renders the UI of all settings page based on the specified configuration array $sections.
	 * (Forked to display section tabs.)
	 *
	 * @since  1.0.7
	 * @access public
	 *
	 * @param  array $sections Configuration array containing all fields to be rendered grouped into sections.
	 */
	public function render_settings( $sections ) {

		// Add default save button if not defined.
		if ( ! fg_entryautomation()->has_setting_field_type( 'save', $sections ) ) {
			$sections = fg_entryautomation()->add_default_save_button( $sections );
		}

		// Initialize tabs.
		$tabs = [];

		// Get tabs.
		foreach ( $sections as $section ) {

			// If no tab is defined, skip it.
			if ( ! rgar( $section, 'tab' ) ) {
				continue;
			}

			// If section doesn't meet dependency, skip it.
			if ( ! fg_entryautomation()->setting_dependency_met( rgar( $section, 'dependency' ) ) ) {
				continue;
			}

			// Add tab.
			$tabs[ rgar( $section, 'id' ) ] = [
				'label' => rgars( $section, 'tab/label' ),
				'icon'  => rgars( $section, 'tab/icon' ),
			];

		}

		?>

        <form id="gform-settings" action="" enctype="multipart/form-data" method="post">
			<?php if ( ! empty( $tabs ) ) { ?>
                <input type="hidden" name="entryautomation_tab"
                       value="<?php echo sanitize_text_field( rgpost( 'entryautomation_tab' ) ); ?>"/>
                <div class="wp-filter entryautomation-tabs">
                    <ul class="filter-links">
						<?php
						$is_first = true;
						foreach ( $tabs as $id => $tab ) {

							$is_current = fg_entryautomation()->is_current_section( $id, $is_first );

							echo '<li id="' . esc_attr( $id ) . '-nav">';
							echo '<a href="#' . esc_attr( $id ) . '"' . ( $is_current ? ' class="current"' : '' ) . '>';
							echo $tab['icon'] ? '<i class="fa ' . esc_attr( $tab['icon'] ) . '"></i> ' : null;
							echo esc_html( $tab['label'] );
							echo '</a>';
							echo '</li>';
							$is_first = false;

						}

						?>
                    </ul>
                </div>
				<?php
			}
			wp_nonce_field( fg_entryautomation()->get_slug() . '_save_settings', '_' . fg_entryautomation()->get_slug() . '_save_settings_nonce' );
			fg_entryautomation()->settings( $sections );
			?>
        </form>

		<?php
	}

	/***
	 * Displays the UI for a field section
	 *
	 * @since 1.3
	 *
	 * @param array $section  The section to be displayed
	 * @param bool  $is_first true for the first section in the list, false for all others
	 */
	public function single_section( $section, $is_first = false ) {

		/**
		 * @var string|bool $title
		 * @var string|bool $description
		 * @var string      $id
		 * @var string|bool $class
		 * @var string      $style
		 * @var string|bool $tooltip
		 * @var string      $tooltip_class
		 */
		extract(
			wp_parse_args(
				$section, [
					'title'         => false,
					'description'   => false,
					'id'            => '',
					'class'         => false,
					'style'         => '',
					'tooltip'       => false,
					'tooltip_class' => '',
				]
			)
		);

		$classes = [ 'gaddon-section' ];

		if ( $is_first ) {
			$classes[] = 'gaddon-first-section';
		}

		if ( $class ) {
			$classes[] = $class;
		}

		$slug = fg_entryautomation()->get_slug();

		if ( 'gf_edit_forms' === rgget( 'page' ) && $slug === rgget( 'subview' ) && strpos( $class, 'entryautomation-feed-section' ) !== false ) {
			$classes[] = fg_entryautomation()->is_current_section( $section['id'], $is_first, true );
		}

		?>

		<div
			id="<?php echo $id; ?>"
			class="<?php echo implode( ' ', $classes ); ?>"
			style="<?php echo $style; ?>"
		>

			<?php if ( $title ): ?>
				<h4 class="gaddon-section-title gf_settings_subgroup_title">
					<?php echo $title; ?>
					<?php if ( $tooltip ): ?>
						<?php gform_tooltip( $tooltip, $tooltip_class ); ?>
					<?php endif; ?>
				</h4>
			<?php endif; ?>

			<?php if ( $description ): ?>
				<div class="gaddon-section-description"><?php echo $description; ?></div>
			<?php endif; ?>

			<table class="form-table gforms_form_settings">

				<?php
				foreach ( $section['fields'] as $field ) {

					if ( ! fg_entryautomation()->setting_dependency_met( rgar( $field, 'dependency' ) ) ) {
						continue;
					}

					if ( is_callable( [ fg_entryautomation(), "single_setting_row_{$field['type']}" ] ) ) {
						call_user_func( [ fg_entryautomation(), "single_setting_row_{$field['type']}" ], $field );
					} else {
						fg_entryautomation()->single_setting_row( $field );
					}
				}
				?>

			</table>

		</div>

		<?php
	}





	// # FEED SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Setup fields for feed settings.
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public function feed_settings_fields() {

		// Has the task already run?
		$has_task_run = fg_entryautomation()->get_current_feed_id() && get_option( fg_entryautomation()->get_slug() . '_last_run_time_' . fg_entryautomation()->get_current_feed_id() );

		// Prepare Next Run Time field.
		$next_run_field = [
			'name'       => 'nextRun',
			'type'       => 'next_run_time',
			'callback'   => [ __CLASS__, 'field_next_run_time' ],
			'dependency' => [ __CLASS__, 'dependency_run_time' ],
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

		$settings = [
			[
				'id'     => 'section-general',
				'tab'    => [
					'label' => esc_html__( 'General', 'forgravity_entryautomation' ),
					'icon'  => 'fa-cog',
				],
				'class'  => 'entryautomation-feed-section',
				'fields' => [
					[
						'name'     => 'feedName',
						'label'    => esc_html__( 'Task Name', 'forgravity_entryautomation' ),
						'type'     => 'text',
						'class'    => 'medium',
						'required' => true,
					],
					[
						'name'          => 'type',
						'label'         => esc_html__( 'Task Type', 'forgravity_entryautomation' ),
						'type'          => 'radio',
						'required'      => true,
						'default_value' => 'scheduled',
						'onclick'       => "jQuery( this ).parents( 'form' ).submit()",
						'choices'       => [
							[
								'value' => 'manual',
								'icon'  => '',
								'label' => esc_html__( 'Manually', 'forgravity_entryautomation' ),
							],
							[
								'value' => 'scheduled',
								'icon'  => '',
								'label' => esc_html__( 'Scheduled', 'forgravity_entryautomation' ),
							],
							[
								'value' => 'submission',
								'icon'  => '',
								'label' => esc_html__( 'On Form Submission', 'forgravity_entryautomation' ),
							],
						],
					],
					[
						'name'     => 'action',
						'label'    => esc_html__( 'Automation Action', 'forgravity_entryautomation' ),
						'type'     => 'radio',
						'required' => true,
						'onclick'  => "jQuery( this ).parents( 'form' ).submit()",
						'choices'  => $this->get_actions_as_choices(),
					],
					$next_run_field,
					[
						'name'                => 'runTime',
						'label'               => esc_html__( 'Run Task Every', 'forgravity_entryautomation' ),
						'type'                => 'text_select',
						'callback'            => [ __CLASS__, 'field_text_select' ],
						'dependency'          => [ __CLASS__, 'dependency_run_time' ],
						'validation_callback' => [ __CLASS__, 'validate_text_select' ],
						'required'            => true,
						'text'                => [
							'name'        => 'runTime[number]',
							'class'       => 'small',
							'input_type'  => 'number',
							'after_input' => ' ',
							'min'         => 1,
						],
						'select'              => [
							'name'          => 'runTime[unit]',
							'default_value' => 'hours',
							'choices'       => [
								[
									'value' => 'minutes',
									'label' => esc_html__( 'minutes', 'forgravity_entryautomation' ),
								],
								[
									'value' => 'hours',
									'label' => esc_html__( 'hours', 'forgravity_entryautomation' ),
								],
								[
									'value' => 'days',
									'label' => esc_html__( 'days', 'forgravity_entryautomation' ),
								],
								[
									'value' => 'weeks',
									'label' => esc_html__( 'weeks', 'forgravity_entryautomation' ),
								],
								[
									'value' => 'months',
									'label' => esc_html__( 'months', 'forgravity_entryautomation' ),
								],
							],
						],
						'tooltip'             => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Run Task Time', 'forgravity_entryautomation' ),
							esc_html__( 'Select how often to run the Entry Automation task. By default, Entry Automation runs tasks every 15 minutes.', 'forgravity_entryautomation' )
						),
					],
					[
						'name'                => 'dateRange',
						'label'               => esc_html__( 'Select Date Range', 'forgravity_entryautomation' ),
						'type'                => 'date_range',
						'callback'            => [ __CLASS__, 'field_date_range' ],
						'dependency'          => [ __CLASS__, 'dependency_date_range' ],
						'validation_callback' => [ __CLASS__, 'validate_date_range' ],
						'start_date'          => true,
						'end_date'            => true,
						'tooltip'             => sprintf(
							'<h6>%s</h6>%s<br /><br />%s',
							esc_html__( 'Deletion Date Range', 'forgravity_entryautomation' ),
							esc_html__( 'Select a date range. Date range is relative to when the task is being run. Setting a range will limit the action to entries submitted during that date range.', 'forgravity_entryautomation' ),
							esc_html__( 'If no start date is set, all entries since the beginning of time will be included. If no end date is set, all entries until the time the action is run will be included.', 'forgravity_entryautomation' )
						),
					],
					[
						'name'           => 'condition',
						'type'           => 'feed_condition',
						'dependency'     => 'action',
						'label'          => esc_html__( 'Conditional Logic', 'forgravity_entryautomation' ),
						'checkbox_label' => esc_html__( 'Enable', 'forgravity_entryautomation' ),
						'instructions'   => esc_html__( 'Include entries if', 'forgravity_entryautomation' ),
						'tooltip'        => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Conditional Logic', 'forgravity_entryautomation' ),
							esc_html__( 'Filter the entries by adding conditions.', 'forgravity_entryautomation' )
						),
					],
				],
			],
		];

		// Loop through registered actions.
		foreach ( Action::get_registered_actions() as $action ) {

			// Get settings fields for action.
			$action_settings = call_user_func( [ __CLASS__, 'feed_settings_fields_' . $action->get_name() ], $action );
			$action_settings = apply_filters( 'fg_entryautomation_' . $action->get_name() . '_settings_fields', $action_settings );

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

		// Add advanced settings tab.
		$settings[] = [
			'id'         => 'section-advanced',
			'tab'        => [
				'label' => esc_html__( 'Advanced Settings', 'forgravity_entryautomation' ),
				'icon'  => 'fa-cog',
			],
			'class'      => 'entryautomation-feed-section',
			'dependency' => [ __CLASS__, 'dependency_date_range' ],
			'fields'     => [
				[
					'name'     => 'run_task',
					'label'    => esc_html__( 'Run Task Now', 'forgravity_entryautomation' ),
					'type'     => 'run_task',
					'callback' => [ __CLASS__, 'field_run_task' ],
				],
			],
		];

		// Add save button.
		$settings[] = [
			'dependency' => 'action',
			'fields'     => [
				[
					'type'     => 'save',
					'messages' => [
						'error'   => esc_html__( 'There was an error while saving the Entry Automation task. Please review the errors below and try again.', 'forgravity_entryautomation' ),
						'success' => esc_html__( 'Entry Automation task updated.', 'forgravity_entryautomation' ),
					],
				],
			],
		];

		return $settings;

	}

	/**
	 * Setup Delete Entries fields for feed settings.
	 *
	 * @since 3.0
	 *
	 * @param Action\Delete $action The delete action class.
	 *
	 * @return array
	 */
	public static function feed_settings_fields_delete( $action = null ) {

		// Get form.
		$form = fg_entryautomation()->get_current_form();

		// Prepare Delete Fields choice.
		$delete_fields_choice = [
			'label' => esc_html__( 'Delete Specific Fields', 'forgravity_entryautomation' ),
			'value' => 'fields',
		];
		if ( empty( $form['fields'] ) ) {
			$delete_fields_choice['disabled'] = true;
		}

		return [
			'id'         => 'section-delete',
			'class'      => 'entryautomation-feed-section',
			'tab'        => [
				'label' => esc_html__( 'Delete Settings', 'forgravity_entryautomation' ),
				'icon'  => 'fa-trash',
			],
			'dependency' => [ 'field' => 'action', 'values' => [ 'delete' ] ],
			'fields'     => [
				[
					'name'          => 'deleteType',
					'label'         => esc_html__( 'Deletion Type', 'forgravity_entryautomation' ),
					'type'          => 'radio',
					'default_value' => 'entry',
					'tooltip'       => sprintf(
						'<h6>%s</h6>%s',
						esc_html__( 'Deletion Type', 'forgravity_entryautomation' ),
						esc_html__( 'Choose to delete the entire entry from the database or specific fields.', 'forgravity_entryautomation' )
					),
					'choices'       => [
						[
							'label' => esc_html__( 'Delete Entry', 'forgravity_entryautomation' ),
							'value' => 'entry',
						],
						$delete_fields_choice
					],
				],
				[
					'name'    => 'moveToTrash',
					'label'   => esc_html__( 'Move To Trash', 'forgravity_entryautomation' ),
					'type'    => 'checkbox',
					'hidden'  => 'fields' === fg_entryautomation()->get_setting( 'deleteType' ),
					'tooltip' => sprintf(
						'<h6>%s</h6>%s',
						esc_html__( 'Move To Trash', 'forgravity_entryautomation' ),
						esc_html__( 'When enabled, entries will be moved to the trash section instead of being immediately deleted from the database.', 'forgravity_entryautomation' )
					),
					'choices' => [
						[
							'name'  => 'moveToTrash',
							'label' => esc_html__( 'Move entries to trash instead of deleting them immediately', 'forgravity_entryautomation' ),
						],
					],
				],
				[
					'name'       => 'deleteFields',
					'label'      => esc_html__( 'Fields to Delete', 'forgravity_entryautomation' ),
					'type'       => 'checkbox',
					'choices'    => Delete::get_delete_fields_choices(),
					'no_choices' => esc_html__( 'You must add at least one form field.', 'forgravity_entryautomation' ),
					'hidden'     => 'fields' !== fg_entryautomation()->get_setting( 'deleteType' ),
				],
			],
		];


	}

	/**
	 * Setup Export Entries fields for feed settings.
	 *
	 * @since 3.0
	 *
	 * @param Action\Delete $action The delete action class.
	 *
	 * @return array
	 */
	public static function feed_settings_fields_export( $action = null ) {

		$file_name_class = 'submission' === fg_entryautomation()->get_setting( 'type' ) ? ' merge-tag-support mt-position-right' : '';

		// Prepare merge tag class.
		$mt_class = 'submission' === fg_entryautomation()->get_setting( 'type' ) ? ' merge-tag-support mt-position-right' : '';

		// Get the current form.
		$form = fg_entryautomation()->get_current_form();

		// When we're running AJAX action, $form is false here so let's get it again.
		if ( ! $form ) {
			$form = GFAPI::get_form( sanitize_text_field( $_POST['form_id'] ) );
		}

		$write_type_description = Export::get_export_file_type_description();
		$xlsx                   = [
			'value' => 'xlsx',
			'label' => esc_html__( 'XLSX', 'forgravity_entryautomation' ),
		];

		if ( ! empty( $write_type_description ) ) {
			$xlsx['disabled'] = 'disabled';
		}

		return [
			[
				'id'         => 'section-export',
				'class'      => 'entryautomation-feed-section',
				'tab'        => [
					'label' => esc_html__( 'Export Settings', 'forgravity_entryautomation' ),
					'icon'  => 'fa-file-excel-o',
				],
				'dependency' => [ 'field' => 'action', 'values' => [ 'export' ] ],
				'fields'     => [
					[
						'name'          => 'exportFileType',
						'label'         => esc_html__( 'File Type', 'forgravity_entryautomation' ),
						'description'   => $write_type_description,
						'type'          => 'radio',
						'required'      => true,
						'horizontal'    => true,
						'default_value' => 'csv',
						'onclick'       => "jQuery( this ).parents( 'form' ).submit()",
						'choices'       => [
							[
								'value' => 'csv',
								'label' => esc_html__( 'CSV', 'forgravity_entryautomation' ),
							],
							$xlsx,
							[
								'value' => 'json',
								'label' => esc_html__( 'JSON', 'forgravity_entryautomation' ),
							],
							[
								'value' => 'pdf',
								'label' => esc_html__( 'PDF', 'forgravity_entryautomation' ),
							],
						],
					],
					[
						'name'          => 'exportFileName',
						'label'         => esc_html__( 'File Name', 'forgravity_entryautomation' ),
						'type'          => 'text',
						'required'      => true,
						'class'         => 'medium' . $file_name_class,
						'default_value' => '{form_title}-{timestamp}',
						'tooltip'       => sprintf(
							'<h6>%s</h6>%s<br /><br />%s',
							esc_html__( 'Export File Name', 'forgravity_entryautomation' ),
							esc_html__( 'Set the file name for entry export file.', 'forgravity_entryautomation' ),
							sprintf(
								esc_html__( 'Available merge tags: %s', 'forgravity_entryautomation' ),
								implode( ', ', [
									'{form_id}',
									'{form_title}',
									'{timestamp}',
									'{date}',
									'{date:format}',
								] )
							)
						),
					],
					[
						'name'          => 'exportWriteType',
						'label'         => null,
						'type'          => 'select',
						'default_value' => 'new',
						'callback'      => [ 'ForGravity\Entry_Automation\Action\Export', 'field_write_type' ],
						'choices'       => [
							[
								'label' => esc_html__( 'increment file name.', 'forgravity_entryautomation' ),
								'value' => 'new',
							],
							[
								'label' => esc_html__( 'overwrite file.', 'forgravity_entryautomation' ),
								'value' => 'overwrite',
							],
							[
								'label' => esc_html__( 'add entries to file.', 'forgravity_entryautomation' ),
								'value' => 'add',
							],
						],
					],
					[
						'name'     => 'exportSorting',
						'label'    => esc_html__( 'Entry Ordering', 'forgravity_entryautomation' ),
						'type'     => 'sorting',
						'callback' => [ 'ForGravity\Entry_Automation\Action\Export', 'field_sorting' ],
						'required' => false,
						'tooltip'  => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Entry Ordering', 'forgravity_entryautomation' ),
							esc_html__( 'Select which column to use to order entries and what direction to order them in.', 'forgravity_entryautomation' )
						),
					],
					[
						'name'                => 'exportFields',
						'label'               => esc_html__( 'Select Fields', 'forgravity_entryautomation' ),
						'type'                => 'export_fields',
						'required'            => true,
						'callback'            => [ __CLASS__, 'field_export_fields' ],
						'validation_callback' => [ __CLASS__, 'validate_export_fields' ],
						'error_message'       => esc_html__( 'You must select at least one field.', 'forgravity_entryautomation' ),
						'tooltip'             => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Export Selected Fields', 'forgravity_entryautomation' ),
							esc_html__( 'Select the fields you would like to include in the export.', 'forgravity_entryautomation' )
						),
					],
					[
						'name'        => 'exportDeleteFile',
						'label'       => esc_html__( 'Post Export Settings', 'forgravity_entryautomation' ),
						'description' => esc_html__( 'You can select actions to perform after the task is complete.', 'forgravity_entryautomation' ),
						'type'        => 'checkbox',
						'choices'     => [
							[
								'name'    => 'exportDeleteFile',
								'label'   => esc_html__( 'Delete export file', 'forgravity_entryautomation' ),
								'tooltip' => sprintf(
									'<h6>%s</h6>%s',
									esc_html__( 'Delete Export File', 'forgravity_entryautomation' ),
									esc_html__( 'When enabled, the export file will be deleted after the task is completed.', 'forgravity_entryautomation' )
								),
							],
						],
					],
				],
			],
			[
				'id'         => 'section-export-email',
				'class'      => 'entryautomation-feed-section',
				'tab'        => [
					'label' => esc_html__( 'Email Settings', 'forgravity_entryautomation' ),
					'icon'  => ' fa-envelope-o',
				],
				'dependency' => [ 'field' => 'action', 'values' => [ 'export' ] ],
				'fields'     => [
					[
						'name'    => 'exportEmailEnable',
						'label'   => esc_html__( 'Send Email', 'forgravity_entryautomation' ),
						'type'    => 'checkbox',
						'onclick' => "jQuery( this ).parents( 'form' ).submit()",
						'tooltip' => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Send Email', 'forgravity_entryautomation' ),
							esc_html__( 'When enabled, an email will be sent after the export is completed with the generated export file attached. By default, only export files under 2 MB will be sent.', 'forgravity_entryautomation' )
						),
						'choices' => [
							[
								'name'  => 'exportEmailEnable',
								'label' => esc_html__( 'Send an email with export file attached', 'forgravity_entryautomation' ),
							],
						],
					],
					[
						'name'          => 'exportEmailAddress',
						'label'         => esc_html__( 'Send To', 'forgravity_entryautomation' ),
						'type'          => 'text',
						'required'      => true,
						'class'         => 'medium' . $mt_class,
						'default_value' => get_bloginfo( 'admin_email' ),
						'dependency'    => [ 'field' => 'exportEmailEnable', 'values' => [ '1' ] ],
						'tooltip'       => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'Send To', 'forgravity_entryautomation' ),
							esc_html__( 'Set the email address the export file will be emailed to. You can send to multiple email addresses by separating them with commas.', 'forgravity_entryautomation' )
						),
					],
					[
						'name'          => 'exportEmailFromName',
						'label'         => esc_html__( 'From Name', 'forgravity_entryautomation' ),
						'type'          => 'text',
						'required'      => true,
						'class'         => 'medium',
						'default_value' => get_bloginfo( 'name' ),
						'dependency'    => [ 'field' => 'exportEmailEnable', 'values' => [ '1' ] ],
						'tooltip'       => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'From Name', 'forgravity_entryautomation' ),
							esc_html__( 'Enter the name you would like the export email message to be sent from.', 'forgravity_entryautomation' )
						),
					],
					[
						'name'          => 'exportEmailFrom',
						'label'         => esc_html__( 'From Address', 'forgravity_entryautomation' ),
						'type'          => 'text',
						'required'      => true,
						'class'         => 'medium',
						'default_value' => get_bloginfo( 'admin_email' ),
						'dependency'    => [ 'field' => 'exportEmailEnable', 'values' => [ '1' ] ],
						'tooltip'       => sprintf(
							'<h6>%s</h6>%s',
							esc_html__( 'From Address', 'forgravity_entryautomation' ),
							esc_html__( 'Enter the email address you would like the export email message to be sent from.', 'forgravity_entryautomation' )
						),
					],
					[
						'name'          => 'exportEmailSubject',
						'label'         => esc_html__( 'Subject', 'forgravity_entryautomation' ),
						'type'          => 'text',
						'required'      => true,
						'class'         => 'large' . $mt_class,
						'default_value' => sprintf( __( 'Entry Automation export for "%s"', 'forgravity_entryautomation' ), sanitize_text_field( $form['title'] ) ),
						'dependency'    => [ 'field' => 'exportEmailEnable', 'values' => [ '1' ] ],
					],
					[
						'name'          => 'exportEmailMessage',
						'label'         => esc_html__( 'Message', 'forgravity_entryautomation' ),
						'type'          => 'textarea',
						'required'      => true,
						'class'         => 'large merge-tag-support mt-position-right mt-hide_all_fields mt-prepopulate',
						'default_value' => sprintf( __( 'The latest entry export for your form, %s, is attached to this message.', 'forgravity_entryautomation' ), sanitize_text_field( $form['title'] ) ),
						'dependency'    => [ 'field' => 'exportEmailEnable', 'values' => [ '1' ] ],
						'use_editor'    => true,
						'callback'      => [ 'ForGravity\Entry_Automation\Action\Export', 'field_export_email_message' ],
					],
				],
			],
		];


	}

	/**
	 * Determine when Date Range feed setting can be displayed.
	 *
	 * @since 3.0
	 *
	 * @return bool
	 */
	public static function dependency_date_range() {

		// If action has not been selected, return false.
		if ( ! fg_entryautomation()->get_setting( 'action' ) ) {
			return false;
		}

		// If this is the On Form Submission task type, return false.
		if ( 'submission' === fg_entryautomation()->get_setting( 'type' ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Determines when Run Time related feed settings can be displayed.
	 *
	 * @since 3.0
	 *
	 * @return bool
	 */
	public static function dependency_run_time() {

		// If action has not been selected, return false.
		if ( ! fg_entryautomation()->get_setting( 'action' ) ) {
			return false;
		}

		// Get task type.
		$type = fg_entryautomation()->get_setting( 'type' );

		// If this is the On Form Submission or Manual task type, return false.
		if ( in_array( $type, [ 'manual', 'submission' ] ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Render a Next Run Time field.
	 *
	 * @since  3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public static function field_next_run_time( $field, $echo = true ) {

		// Initialize return HTML.
		$html = '';

		// Set default start time.
		$field['default_value'] = fg_entryautomation()->strtotime( '+1 hour', 'Y-m-d\TH:i', true );
		$field['default_value'] = fg_entryautomation()->strtotime( $field['default_value'], 'Y-m-d\TH:i:s', true );

		// If feed has not run, set default start time.
		if ( fg_entryautomation()->get_current_feed_id() ) {

			// Get next scheduled run time.
			$next_run_time = fg_entryautomation()->get_next_run_time( fg_entryautomation()->get_current_feed_id(), 'Y-m-d\TH:i:s' );

			// If next run time found, set as default value.
			if ( $next_run_time ) {
				$field['default_value'] = $next_run_time;
			}

		}

		// Add field.
		$html = fg_entryautomation()->settings_hidden( $field, false );

		// Add container for DateTimePicker.
		$html .= '<div id="nextRunContainer"></div>';

		if ( $echo ) {
			echo $html;
		}

		return $html;

	}

	/**
	 * Render a text and select settings field.
	 *
	 * @since  3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public static function field_text_select( $field, $echo = true ) {

		// Initialize return HTML.
		$html = '';

		// Duplicate fields.
		$select_field = $text_field = $field;

		// Merge properties.
		$text_field   = array_merge( $text_field, $text_field['text'] );
		$select_field = array_merge( $select_field, $select_field['select'] );

		unset( $text_field['text'], $select_field['text'], $text_field['select'], $select_field['select'] );

		$html .= fg_entryautomation()->settings_text( $text_field, false );
		$html .= fg_entryautomation()->settings_select( $select_field, false );

		if ( fg_entryautomation()->field_failed_validation( $field ) ) {
			$html .= fg_entryautomation()->get_error_icon( $field );
		}

		if ( $echo ) {
			echo $html;
		}

		return $html;

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
		$text_field_name = explode( '/', str_replace( [ '[', ']' ], [ '/', '' ], $field['text']['name'] ) );

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
	 * Render a date range field.
	 *
	 * @since  3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public static function field_date_range( $field, $echo = true ) {

		// Initialize return HTML.
		$html = '';

		// Display start date.
		if ( rgar( $field, 'start_date' ) ) {

			// Prepare number field.
			$start_number = [ 'name' => $field['name'] . '[start]', 'autocomplete' => 'off' ];

			$html .= '<span class="range">';
			$html .= fg_entryautomation()->settings_text( $start_number, false );
			$html .= '<strong>' . esc_html__( 'From', 'forgravity_entryautomation' ) . '</strong>';
			$html .= '<span class="time-preview"></span>';
			$html .= '</span>';

		}

		// Display end date.
		if ( rgar( $field, 'end_date' ) ) {

			// Prepare number field.
			$end_number = [ 'name' => $field['name'] . '[end]', 'autocomplete' => 'off' ];

			$html .= '<span class="range">';
			$html .= fg_entryautomation()->settings_text( $end_number, false );
			$html .= '<strong>' . esc_html__( 'To', 'forgravity_entryautomation' ) . '</strong>';
			$html .= '<span class="time-preview"></span>';
			$html .= '</span>';

		}

		if ( $echo ) {
			echo $html;
		}

		return $html;

	}

	/**
	 * Render a run test now settings field.
	 *
	 * @since  3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public static function field_run_task( $field, $echo = true ) {

		// Initialize return HTML.
		$html = '';

		// Add button.
		$html .= '<button id="fg-entryautomation-run-task" class="button">' . esc_html__( 'Run Task Now', 'forgravity_entryautomation' ) . '</button>';

		// Add response container.
		$html .= '<span id="fg-entryautomation-run-task-response"></span>';

		if ( $echo ) {
			echo $html;
		}

		return $html;

	}

	/**
	 * Validates a date range settings field.
	 *
	 * @since  3.0
	 *
	 * @param array $field    Field settings.
	 * @param array $settings Submitted settings values.
	 */
	public static function validate_date_range( $field, $settings ) {

		// Validate start date.
		if ( rgar( $field, 'start_date' ) ) {

			// Get start date field.
			$start_field          = $field;
			$start_field['name'] .= '[start]';

			// Get field value.
			$start_date = rgars( $settings, $field['name'] . '/start' );

			// If start date is defined, validate.
			if ( $start_date ) {

				// Convert start date to time.
				$start_date = fg_entryautomation()->strtotime( $start_date );

				// If time did not convert correctly, set field error.
				if ( ! $start_date ) {
					fg_entryautomation()->set_field_error( $start_field, esc_html__( 'You must use a valid date string.', 'forgravity_entryautomation' ) );
				}

			}

		}

		// Validate end date.
		if ( rgar( $field, 'end_date' ) ) {

			// Get end date field.
			$end_field         = $field;
			$end_field['name'] .= '[end]';

			// Get field value.
			$end_date = rgars( $settings, $field['name'] . '/end' );

			// If end date is defined, validate.
			if ( $end_date ) {

				// Convert end date to time.
				$end_date = fg_entryautomation()->strtotime( $end_date );

				// If time did not convert correctly, set field error.
				if ( ! $end_date ) {
					fg_entryautomation()->set_field_error( $end_field, esc_html__( 'You must use a valid date string.', 'forgravity_entryautomation' ) );
				}

			}

		}

	}

	/**
	 * Render an export fields field.
	 *
	 * @since  3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public static function field_export_fields( $field, $echo = true ) {

		// Get field value.
		if ( fg_entryautomation()->get_setting( $field['name'] ) ) {
			$value = Export::update_export_fields( fg_entryautomation()->get_setting( $field['name'] ) );
		} else {
			$value = Export::get_default_export_fields();
		}

		// Add hidden field container.
		$html = '<input
                    type="hidden"
                    name="_gaddon_setting_' . esc_attr( $field['name'] ) . '"
                    value=\'' . esc_attr( json_encode( $value ) ) . '\' ' .
		        implode( ' ', fg_entryautomation()->get_field_attributes( $field ) ) .
		        ' />';

		if ( fg_entryautomation()->field_failed_validation( $field ) ) {
			$html .= fg_entryautomation()->get_error_icon( $field );
		}

		// Add export fields container.
		$html .= '<div id="exportFieldsContainer"></div>';

		if ( $echo ) {
			echo $html;
		}

		return $html;

	}

	/**
	 * Validate Export Select Field setting.
	 *
	 * @since  3.0
	 *
	 * @param array $field       The settings field being validated.
	 * @param array $field_value The submitted settings field value.
	 */
	public static function validate_export_fields( $field, $field_value ) {

		// Initialize selected checkboxes count.
		$selected = 0;

		// Loop through export fields.
		foreach ( $field_value as $export_field ) {

			// If choice does not have a valid value, exit.
			if ( ! is_bool( $export_field['enabled'] ) ) {
				fg_entryautomation()->set_field_error( $field, esc_html__( 'Invalid value.', 'gravityforms' ) );
				return;
			}

			// If choice is selected, increase selected count.
			if ( $export_field['enabled'] === true ) {
				$selected++;
			}

		}

		// If this field is required and no choices were selected, set error.
		if ( rgar( $field, 'required' ) && $selected < 1 ) {
			fg_entryautomation()->set_field_error( $field, rgar( $field, 'error_message' ) );
		}

	}





	// # FEED LIST -----------------------------------------------------------------------------------------------------

	/**
	 * Define the title for the feed list page.
	 *
	 * @since  3.0
	 * @access public
	 *
	 * @uses   GFAddOn::get_short_title()
	 * @uses   GFFeedAddOn::can_create_feed()
	 *
	 * @return string
	 */
	public function feed_list_title() {

		// If feed creation is disabled, display title without Add New button.
		if ( ! $this->can_create_feed() ) {
			return sprintf(
				esc_html__( '%s Tasks', 'forgravity_entryautomation' ),
				$this->get_short_title()
			);
		}

		// Prepare add new feed URL.
		$url = add_query_arg( [ 'fid' => '0' ] );
		$url = esc_url( $url );

		// Display feed list title with Add New button.
		return sprintf(
			'%s <a class="add-new-h2" href="%s">%s</a>',
			sprintf(
				esc_html__( '%s Tasks', 'forgravity_entryautomation' ),
				$this->get_short_title()
			),
			$url,
			esc_html__( 'Add New', 'gravityforms' )
		);

	}





	// # ENTRY AUTOMATION ----------------------------------------------------------------------------------------------

	/**
	 * Run a single task from the task settings page.
	 *
	 * @since  3.0
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

		// Get settings.
		$settings = $this->get_posted_settings();

		// Get sections.
		$sections = $this->get_feed_settings_fields();
		$sections = $this->remove_field( 'runTime', $sections );

		// Check if settings are valid.
		$is_valid = $this->validate_settings( $sections, $settings );

		// If settings are invalid, return.
		if ( ! $is_valid ) {
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
		$message .= 'export' === $task->meta['action'] && file_exists( $response ) ? sprintf( esc_html__( '%sDownload export file.%s', 'forgravity_entryautomation' ), ' <a href="' . $url . '" target="_blank">', '</a>' ) : '';

		// Send result.
		wp_send_json_success( [ 'message' => $message ] );

	}

	/**
	 * Generate a preview of the Entry Automation date range.
	 *
	 * @since  3.0
	 */
	public function ajax_time_preview() {

		// Verify nonce.
		if ( ! wp_verify_nonce( rgpost( 'nonce' ), $this->get_slug() ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'forgravity_entryautomation' ) ] );
		}

		// Verify capabilities.
		if ( ! GFCommon::current_user_can_any( $this->_capabilities_settings_page ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid request.', 'forgravity_entryautomation' ) ] );
		}

		// Get time to preview.
		$preview_time = sanitize_text_field( rgars( $_POST, 'task/time' ) );

		// If preview time is already a time, return it.
		if ( preg_match( '/^([0-9]{4}-[0-9]{2}-[0-9]{2}( [0-9]{1,2}:[0-9]{2}((:[0-9]{2})|( AM| PM)))?)$/', $preview_time ) ) {
			wp_send_json_success( [ 'time' => date( 'Y-m-d g:i A', strtotime( $preview_time ) ) ] );
		}

		// If this is an invalid time, return error.
		if ( ! strtotime( $preview_time ) ) {
			wp_send_json_error( [ 'time' => esc_html__( 'Invalid Time', 'forgravity-entryautomation' ) ] );
		}

		// Get offset.
		$offset = get_option( 'gmt_offset', 0 );
		$offset = $offset >= 0 ? '+' . $offset : strval( $offset );

		// Prepare next run time from request.
		$next_run_time = rgars( $_POST, 'task/nextRun' );
		$next_run_time = $this->strtotime( $next_run_time, 'timestamp', true );

		try {

			// Prepare time zone object.
			$dtz = $offset ? new DateTimeZone( $offset ) : null;

			try {

				// Initialize new DateTime object.
				$dt = new DateTime( null, $dtz );
				$dt->setTimestamp( $next_run_time );
				$dt->modify( is_numeric( $preview_time[0] ) ? '-' . $preview_time : $preview_time );

				wp_send_json_success( [ 'time' => $dt->format( 'Y-m-d g:i A' ) ] );

			} catch ( Exception $e ) {

				// Log that DateTime could not be initialized.
				$this->log_error( __METHOD__ . '(): Unable to initialize DateTime object to prepare preview time, defaulting to strtotime; ' . $e->getMessage() );

				wp_send_json_success( [ 'time' => date( 'Y-m-d g:i A', strtotime( $preview_time, $next_run_time ) ) ] );

			}

		} catch ( Exception $e ) {

			// Log that DateTimeZone could not be initialized.
			$this->log_error( __METHOD__ . '(): Unable to initialize DateTimeZone object to prepare preview time, defaulting to strtotime; ' . $e->getMessage() );

			wp_send_json_success( [ 'time' => date( 'Y-m-d g:i A', strtotime( $preview_time, $next_run_time ) ) ] );

		}

	}





	// # EXTENSION MANAGEMENT ------------------------------------------------------------------------------------------

	/**
	 * Displays available Entry Automation extensions.
	 *
	 * @since  1.3
	 * @access public
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
				echo $html;
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
				echo $html;
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

		// Initialize table.
		$html = '<table>';

		// Loop through
		foreach ( $license_data->extensions as $extension ) {

			// Initialize button text and link variables.
			$button_text   = '';
			$button_link   = '#';
			$button_action = '';
			$button_plugin = $extension->plugin_file;

			// If extension is active, offer deactivate link.
			if ( Extension::is_activated( $extension->plugin_file ) ) {
				$button_text   = esc_html__( 'Deactivate Extension', 'forgravity_entryautomation' );
				$button_action = 'deactivate';
			} else if ( Extension::is_installed( $extension->plugin_file ) ) {
				$button_text   = esc_html__( 'Activate Extension', 'forgravity_entryautomation' );
				$button_action = 'activate';
			} else if ( ! Extension::is_installed( $extension->plugin_file ) ) {
				if ( $extension->has_access ) {
					$button_text   = esc_html__( 'Install Extension', 'forgravity_entryautomation' );
					$button_action = 'install';
				} else {
					$button_text   = esc_html__( 'Upgrade License', 'forgravity_entryautomation' );
					$button_action = 'upgrade';
					$button_link   = $extension->upgrade_url;
				}
			}

			// Add extension row.
			$html .= sprintf(
				'<tr>
                    <td>
                        <strong>%s</strong><br />
                        %s
                    </td>
                    <td style="padding-left: 20px;">
                        <a data-action="%s" data-plugin="%s" href="%s" class="button">
                            %s
                        </a>
                    </td>
                </tr>',
				$extension->name,
				$extension->description,
				$button_action,
				$button_plugin,
				$button_link,
				$button_text
			);

		}

		// Close table.
		$html .= '</table>';

		if ( $echo ) {
			echo $html;
		}

		return $html;

	}

}
