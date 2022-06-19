<?php
/**
 * Notification Action for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation\Action;

use ForGravity\Entry_Automation\Action;
use ForGravity\Entry_Automation\Entries;
use ForGravity\Entry_Automation\Task;
use GFCommon;
use GFFormDisplay;
use Gravity_Forms\Gravity_Forms\Settings\Fields;
use Gravity_Forms\Gravity_Forms\Settings\Settings;

/**
 * Notification Action
 */
class Notification extends Action {

	/**
	 * Regular expression for detecting a merge tag.
	 *
	 * @since 5.0
	 * @var   string
	 */
	const MERGE_TAX_REGEX = '/{[^{]*?:(\d+(\.\d+)?)(:(.*?))?}/mi';

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since 5.0
	 * @var   Notification $_instance If available, contains an instance of this class.
	 */
	protected static $_instance = null;

	/**
	 * Defines the action name.
	 *
	 * @since 5.0
	 * @var   string $action Action name.
	 */
	protected $name = 'notification';

	/**
	 * Stores the current entry or draft submission being processed.
	 *
	 * @since 5.0
	 * @var   array $current_submission The entry or draft submission.
	 */
	private $current_submission = [];





	// # ACTION SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Settings fields for configuring this Entry Automation action.
	 *
	 * @since 5.0
	 *
	 * @return array
	 */
	public function settings_fields() {

		$notification_choices = $this->get_notifications_as_choices();

		// If no active Notifications exist, display warning message instead of fields.
		if ( empty( $notification_choices ) ) {
			return $this->get_no_notifications_settings_fields();
		}

		// Register custom settings field type for Notification Override.
		Fields::register( 'fg_entryautomation_notification', '\ForGravity\Entry_Automation\Settings\Fields\Notification_Override' );

		// Prepare re-used dependencies.
		$notification_selected_dependency = [
			'live'   => true,
			'fields' => [ [ 'field' => 'notificationId' ] ],
		];
		$override_enabled_dependency      = [
			'live'   => true,
			'fields' => [ [ 'field' => 'notificationOverride_enabled' ] ],
		];

		// Define To warning style.
		$show_notification_to_warning  = self::show_notification_override_to_warning( fg_entryautomation()->get_settings_renderer() );
		$notification_to_warning_style = $show_notification_to_warning ? '' : ' style="display:none;"';

		// Define merge tags class.
		$merge_tags_class = fg_entryautomation()->get_setting( 'notificationType' ) === 'digest' ? '' : 'merge-tag-support mt-position-right mt-hide_all_fields';

		return [
			'id'         => 'notification',
			'title'      => esc_html__( 'Notification Settings', 'forgravity_entryautomation' ),
			'dependency' => [
				'live'   => true,
				'fields' => [
					[
						'field'  => 'action',
						'values' => [ $this->name ],
					],
				],
			],
			'sections'   => [
				[
					'id'     => 'notification-settings',
					'title'  => esc_html__( 'Notification Settings', 'forgravity_entryautomation' ),
					'fields' => [
						[
							'name'          => 'notificationType',
							'type'          => 'radio',
							'required'      => true,
							'label'         => esc_html__( 'Entry Delivery', 'forgravity_entryautomation' ),
							'default_value' => 'single',
							'choices'       => [
								[
									'label' => esc_html__( 'Send Each Entry Separately', 'forgravity_entryautomation' ),
									'value' => 'single',
									'icon'  => file_get_contents( fg_entryautomation()->get_base_path() . '/images/notification/delivery-types/single.svg' ),
								],
								[
									'label' => esc_html__( 'Send All Entries At Once', 'forgravity_entryautomation' ),
									'value' => 'digest',
									'icon'  => file_get_contents( fg_entryautomation()->get_base_path() . '/images/notification/delivery-types/digest.svg' ),
								],
							],
						],
						[
							'name'     => 'notificationId',
							'type'     => 'select',
							'required' => true,
							'label'    => esc_html__( 'Notification', 'forgravity_entryautomation' ),
							'choices'  => $notification_choices,
						],
						[
							'name'       => 'notificationOverride',
							'type'       => 'fg_entryautomation_notification',
							'dependency' => $notification_selected_dependency,
							'fields'     => [
								[
									'name'          => 'notificationOverride_enabled',
									'type'          => 'toggle',
									'label'         => esc_html__( 'Override Notification Settings', 'forgravity_entryautomation' ),
									'readonly'      => $show_notification_to_warning,
									'default_value' => $show_notification_to_warning,
								],
								[
									'name'                => 'notificationOverride_to',
									'label'               => esc_html__( 'To', 'forgravity_entryautomation' ),
									'type'                => 'text',
									'class'               => $merge_tags_class,
									'dependency'          => $override_enabled_dependency,
									'validation_callback' => [ $this, 'validation_notification_override_to' ],
								],
								[
									'name' => 'notificationOverride_to_warning',
									'type' => 'html',
									'html' => sprintf(
										'<div class="alert gforms_note_warning"%2$s>%1$s</div>',
										esc_html__( 'You must enter a specific email address as the To address when sending all entries at once. The Notification that will be sent is a wrapper for multiple entries but is not associated with an entry itself, so entry referenced emails are not supported.', 'forgravity_entryautomation' ),
										$notification_to_warning_style
									),
								],
								[
									'name'       => 'notificationOverride_fromName',
									'label'      => esc_html__( 'From Name', 'forgravity_entryautomation' ),
									'type'       => 'text',
									'class'      => $merge_tags_class,
									'dependency' => $override_enabled_dependency,
								],
								[
									'name'       => 'notificationOverride_from',
									'label'      => esc_html__( 'From Email', 'forgravity_entryautomation' ),
									'type'       => 'text',
									'class'      => $merge_tags_class,
									'dependency' => $override_enabled_dependency,
								],
								[
									'name'       => 'notificationOverride_subject',
									'label'      => esc_html__( 'Subject', 'forgravity_entryautomation' ),
									'type'       => 'text',
									'class'      => $merge_tags_class,
									'dependency' => $override_enabled_dependency,
								],
								[
									'name'          => 'notificationOverride_header',
									'label'         => esc_html__( 'Header', 'forgravity_entryautomation' ),
									'tooltip'       => esc_html__( 'Add text to be displayed before messages for all found entries.', 'forgravity_entryautomation' ),
									'type'          => 'textarea',
									'callback'      => [ $this, 'render_textarea_without_merge_tags' ],
									'editor_height' => 100,
									'dependency'    => [
										'live'   => true,
										'fields' => [
											[ 'field' => 'notificationOverride_enabled' ],
											[
												'field'  => 'notificationType',
												'values' => 'digest',
											],
										],
									],
								],
								[
									'name'          => 'notificationOverride_message',
									'label'         => esc_html__( 'Message', 'forgravity_entryautomation' ),
									'type'          => 'textarea',
									'use_editor'    => true,
									'editor_height' => 328,
									'dependency'    => $override_enabled_dependency,
								],
								[
									'name'          => 'notificationOverride_footer',
									'label'         => esc_html__( 'Footer', 'forgravity_entryautomation' ),
									'tooltip'       => esc_html__( 'Add text to be displayed after messages for all found entries.', 'forgravity_entryautomation' ),
									'type'          => 'textarea',
									'callback'      => [ $this, 'render_textarea_without_merge_tags' ],
									'editor_height' => 100,
									'dependency'    => [
										'live'   => true,
										'fields' => [
											[ 'field' => 'notificationOverride_enabled' ],
											[
												'field'  => 'notificationType',
												'values' => 'digest',
											],
										],
									],
								],
							],
						],
						[
							'name'  => 'notificationSuppress',
							'type'  => 'toggle',
							'label' => esc_html__( 'Do not send notification on form submission', 'forgravity_entryautomation' ),
						],
					],
				],
			],
		];

	}

	/**
	 * Returns Action settings to display if Form does not have any Notifications.
	 *
	 * @since 5.0
	 *
	 * @return array
	 */
	private function get_no_notifications_settings_fields() {

		$message = sprintf(
			esc_html__( 'You must have at least one active form notification to use the Send Notifications action. %1$sClick here to create a notification.%2$s', 'forgravity_entryautomation' ),
			'<a href="' . esc_url( add_query_arg( [ 'subview' => 'notification', 'nid' => 0, 'fid' => null ] ) ) . '">',
			'</a>'
		);

		return [
			'id'         => 'notification',
			'title'      => esc_html__( 'Notification Settings', 'forgravity_entryautomation' ),
			'dependency' => [
				'live'   => true,
				'fields' => [
					[
						'field'  => 'action',
						'values' => [ $this->name ],
					],
				],
			],
			'sections'   => [
				[
					'id'     => '',
					'title'  => esc_html__( 'Notification Settings', 'forgravity_entryautomation' ),
					'fields' => [
						[
							'name'          => 'notificationId',
							'type'          => 'hidden',
							'required'      => true,
							'default_value' => '',
						],
						[
							'type' => 'html',
							'html' => sprintf( '<div class="alert warning" role="alert">%s</div>', $message ),
						],
					],
				],
			],
		];

	}

	/**
	 * Returns the current Form's Notifications as a collection of choices.
	 *
	 * @since 5.0
	 *
	 * @return array
	 */
	private function get_notifications_as_choices() {

		$choices       = [];
		$notifications = $this->get_notifications();

		if ( empty( $notifications ) ) {
			return $choices;
		}

		$choices[] = [
			'label' => esc_html__( 'Select a Notification', 'forgravity_entryautomation' ),
			'value' => '',
		];

		foreach ( $notifications as $notification ) {
			$choices[] = [
				'label' => esc_html( rgar( $notification, 'name' ) ),
				'value' => esc_html( rgar( $notification, 'id' ) ),
			];
		}

		return $choices;

	}

	/**
	 * Action label, used in Entry Automation settings.
	 *
	 * @since  5.0
	 *
	 * @return string
	 */
	public function get_label() {

		return esc_html__( 'Send Notification', 'forgravity_entryautomation' );

	}

	/**
	 * Action short label, used in Entry Automation Tasks table.
	 *
	 * @since  5.0
	 *
	 * @return string
	 */
	public function get_short_label() {

		return $this->get_label();

	}

	/**
	 * Ensure a defined email address is used when sending digest Notifications.
	 *
	 * @since 5.0
	 *
	 * @param Fields\Text $field Override To field.
	 * @param string      $value Submitted value.
	 */
	public function validation_notification_override_to( $field, $value ) {

		$notification_id = $field->settings->get_value( 'notificationId' );
		$notification    = $this->get_notification( $notification_id );

		if ( ! self::is_notification_to_valid( $notification, new Task( [ 'meta' => $field->settings->get_current_values() ] ) ) ) {
			$field->set_error( esc_html__( 'To address must be set to a specific email address.', 'forgravity_entryautomation' ) );
		}

	}

	/**
	 * Display warning message when sending digest Notifications.
	 *
	 * @since 5.0
	 *
	 * @param Settings $settings Settings API instance.
	 *
	 * @return bool
	 */
	public function show_notification_override_to_warning( $settings ) {

		$notification_id = $settings->get_value( 'notificationId' );
		$notification    = $this->get_notification( $notification_id );

		return ! self::is_notification_to_valid( $notification, new Task( [ 'meta' => $settings->get_current_values() ] ) );

	}

	/**
	 * Render a Textarea field with a rich text editor excluding merge tags.
	 *
	 * @since 5.0
	 *
	 * @param Fields\Textarea $field Field object.
	 * @param bool            $echo  Echo markup.
	 *
	 * @return string
	 */
	public function render_textarea_without_merge_tags( $field, $echo ) {

		// Get value.
		$value = $field->get_value();

		// Create editor container.
		$html = sprintf(
			'<span class="mt-gaddon-editor mt-%s_%s"></span>',
			esc_attr( $field->settings->get_input_name_prefix() ),
			esc_attr( $field->name )
		);

		// Display description.
		$html .= $field->get_description();

		$html .= '<span class="' . esc_attr( $field->get_container_classes() ) . '">';

		// Insert editor.
		ob_start();
		wp_editor(
			$value,
			esc_attr( $field->settings->get_input_name_prefix() ) . '_' . esc_attr( $field->name ),
			[
				'autop'         => false,
				'editor_class'  => '',
				'editor_height' => $this->editor_height,
			]
		);
		$html .= ob_get_contents();
		ob_end_clean();

		// If field failed validation, add error icon.
		$html .= $field->get_error_icon();

		$html .= '</span>';

		if ( $echo ) {
			echo $html; // phpcs:ignore
		}

		return $html;

	}





	// # RUNNING ACTION ------------------------------------------------------------------------------------------------

	/**
	 * Process task.
	 *
	 * @since  5.0
	 *
	 * @return bool
	 */
	public function run() {

		// Return false if the task property isn't set correctly.
		if ( ! $this->task instanceof Task ) {
			return false;
		}

		$task         = $this->task;
		$form         = $this->form;
		$notification = $this->get_notification( rgar( $task->meta, 'notificationId' ), $form );

		if ( rgar( $notification, 'isActive' ) === false ) {
			fg_entryautomation()->log_error( __METHOD__ . "(): Skipping task #{$task->id} because notification ({$notification['id']} - {$notification['name']}) is inactive." );
			return false;
		}

		// Prepare search criteria.
		$search_criteria = $task->get_search_criteria();

		// Prepare paging criteria.
		$paging = Entries::$paging;

		$entries = [];

		// Loop until all entries have been processed.
		while ( $task->entries_processed < $task->found_entries ) {

			// Log the page number.
			fg_entryautomation()->log_debug( __METHOD__ . '(): Gathering entries, group ' . ( round( $task->entries_processed / $paging['page_size'] ) + 1 ) . ' of ' . ( round( $task->found_entries / $paging['page_size'] ) ) );

			// Get entries.
			$args             = [
				'form_id'         => $form['id'],
				'search_criteria' => $search_criteria,
			];
			$returned_entries = $this->entries->get( $args, rgar( $task->meta, 'entryType' ) );

			// If no more entries were found, break.
			if ( empty( $returned_entries ) || is_wp_error( $returned_entries ) ) {
				fg_entryautomation()->log_debug( __METHOD__ . '(): No entries were found for this page.' );
				break;
			}

			$entries                  = array_merge( $entries, $returned_entries );
			$task->entries_processed += count( $returned_entries );

		}

		call_user_func( [ $this, sprintf( 'process_%s_notifications', $task->meta['notificationType'] ) ], $entries );

		// Log that notifications have been sent.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Notifications sent.' );

		/**
		 * Executed after notifications have been sent.
		 *
		 * @since 5.0
		 *
		 * @param Task  $task Entry Automation Task.
		 * @param array $form The Form object.
		 */
		gf_do_action( [ 'fg_entryautomation_after_send_notifications', $form['id'] ], $task, $form );

		return true;

	}

	/**
	 * Send individual notifications for a collection of entries.
	 *
	 * @since 5.0
	 *
	 * @param array $entries Collection of entries.
	 *
	 * @return bool
	 */
	private function process_single_notifications( $entries ) {

		$form         = $this->form;
		$notification = $this->get_notification( $this->task->meta['notificationId'], $form );

		if ( ! $notification ) {
			return false;
		}

		// Loop through entries.
		foreach ( $entries as $entry ) {

			$submission = $entry;
			$entry      = rgar( $this->task->meta, 'entryType' ) === 'draft_submission' ? Entries::decode_draft_submission( $entry ) : $entry;

			$entry_notification            = $this->override_notification_properties( $notification );
			$entry_notification['message'] = $this->replace_message_variables( $entry_notification, $submission );

			GFCommon::send_notification( $entry_notification, $form, $entry );

		}

		return true;

	}

	/**
	 * Send a digest notification for a collection of entries.
	 *
	 * @since 5.0
	 *
	 * @param array $entries Collection of entries.
	 *
	 * @return bool
	 */
	private function process_digest_notifications( $entries ) {

		$form         = $this->form;
		$notification = $this->get_notification( $this->task->meta['notificationId'], $form );

		if ( ! $notification || ! $entries ) {
			return false;
		}

		if ( ! self::is_notification_to_valid( $notification, $this->task ) ) {
			fg_entryautomation()->log_error( __METHOD__ . '(): Unable to send Notification due to invalid To address.' );
			return false;
		}

		$notification = $this->override_notification_properties( $notification );

		$notification_message = '';

		if ( rgar( $this->task->meta, 'notificationOverride_enabled' ) && rgar( $this->task->meta, 'notificationOverride_header' ) ) {
			$notification_message = $this->task->meta['notificationOverride_header'];
		}

		// Loop through entries.
		foreach ( $entries as $entry ) {
			$notification_message .= $this->replace_message_variables( $notification, $entry ) . PHP_EOL;
		}

		if ( rgar( $this->task->meta, 'notificationOverride_enabled' ) && rgar( $this->task->meta, 'notificationOverride_footer' ) ) {
			$notification_message .= $this->task->meta['notificationOverride_footer'];
		}

		$notification['message'] = $notification_message;

		GFCommon::send_notification( $notification, $form, [] );

		return true;

	}

	/**
	 * Replace Notification properties with values defined in the Task meta.
	 *
	 * @since 5.0
	 *
	 * @param array $notification Notification object.
	 *
	 * @return array
	 */
	private function override_notification_properties( $notification ) {

		if ( ! rgars( $this->task->meta, 'notificationOverride_enabled' ) ) {
			return $notification;
		}

		foreach ( [ 'to', 'fromName', 'from', 'subject', 'message' ] as $prop ) {

			$override_value = rgars( $this->task->meta, 'notificationOverride_' . $prop );

			if ( ! $override_value ) {
				continue;
			}

			$notification[ $prop ] = $override_value;

			if ( $prop === 'to' ) {
				$notification['toType'] = 'email';
			}

		}

		return $notification;

	}

	/**
	 * Replace variables in Notification message.
	 *
	 * @since 5.0
	 *
	 * @param array $notification Notification object.
	 * @param array $entry        Entry or draft submission.
	 *
	 * @return string
	 */
	private function replace_message_variables( $notification, $entry ) {

		$is_draft_submission = rgar( $this->task->meta, 'entryType' ) === 'draft_submission';

		$submission               = $entry;
		$this->current_submission = $submission;
		$entry                    = $is_draft_submission ? Entries::decode_draft_submission( $submission ) : $entry;

		$message_format   = rgempty( 'message_format', $notification ) ? 'html' : rgar( $notification, 'message_format' );
		$merge_tag_format = $message_format === 'multipart' ? 'html' : $message_format;
		$nl2br            = ! rgar( $notification, 'disableAutoformat' );

		$message = GFCommon::replace_variables( rgar( $notification, 'message' ), $this->form, $entry, false, false, $nl2br, $merge_tag_format );

		if ( ! $is_draft_submission ) {
			return $message;
		}

		if ( ! class_exists( 'GFFormDisplay' ) ) {
			include_once GFCommon::get_base_path() . '/form_display.php';
		}

		add_filter( 'gform_save_and_continue_resume_url', [ $this, 'filter_gform_save_and_continue_resume_url' ], 9999, 4 );
		$message = GFFormDisplay::replace_save_variables( $message, $this->form, rgar( $submission, 'uuid' ), rgar( $submission, 'email' ) );
		remove_filter( 'gform_save_and_continue_resume_url', [ $this, 'filter_gform_save_and_continue_resume_url' ], 9999 );

		$this->current_submission = [];

		return $message;

	}

	/**
	 * Replaces the resume URL with the URL from the draft submission.
	 *
	 * @since 5.0
	 *
	 * @param string $resume_url   The URL to be used to resume the partial entry.
	 * @param array  $form         The Form Object.
	 * @param string $resume_token The token that is used within the URL.
	 * @param string $email        The email address associated with the partial entry.
	 *
	 * @return string
	 */
	public function filter_gform_save_and_continue_resume_url( $resume_url, $form, $resume_token, $email ) {

		if ( ! $this->current_submission || rgar( $this->current_submission, 'uuid' ) !== $resume_token ) {
			return $resume_url;
		}

		$resume_url = rgar( $this->current_submission, 'source_url' );
		$resume_url = add_query_arg( [ 'gf_token' => $resume_token ], $resume_url );

		return $resume_url;

	}





	// # SUPPRESS NOTIFICATIONS ----------------------------------------------------------------------------------------

	/**
	 * Suppress notification if enabled in Task.
	 *
	 * @since 5.0
	 *
	 * @param bool  $disable_notification Determines if the notification will be disabled.
	 * @param array $notification         The Notification object to be sent.
	 * @param array $form                 The Form Object that triggered the notification event.
	 * @param array $entry                The Entry Object that triggered the notification event.
	 * @param array $data                 Array of data which can be used in the notifications via the generic {object:property} merge tag. Defaults to empty array.
	 *
	 * @return bool
	 */
	public static function filter_gform_disable_notification( $disable_notification, $notification, $form, $entry, $data = [] ) {

		$tasks = self::get_notification_tasks( $form, $notification['id'] );

		if ( empty( $tasks ) ) {
			return $disable_notification;
		}

		foreach ( $tasks as $task ) {

			if ( ! rgar( $task->meta, 'notificationSuppress' ) || ! $task->is_active ) {
				continue;
			}

			$logic = rgars( $task->meta, 'feed_condition_conditional_logic_object/conditionalLogic' );

			if ( ! rgar( $task->meta, 'feed_condition_conditional_logic' ) || fg_entryautomation()->evaluate_conditional_logic( $logic, $form, $entry ) ) {

				// Add entry note that notification is being disabled.
				fg_entryautomation()->add_note(
					$entry['id'],
					sprintf(
						esc_html__( 'Notification "%1$s" (%2$s) disabled by Entry Automation (Task #%3$d - %4$s).', 'forgravity_entryautomation' ),
						esc_html( $notification['name'] ),
						esc_html( $notification['id'] ),
						intval( $task->id ),
						esc_html( $task->meta['feedName'] )
					),
					'success'
				);

				// Add a debug log entry that notification is being disabled.
				fg_entryautomation()->log_debug( __METHOD__ . '(): Feed condition met for task ' . $task->id . '. Disabling notification.' );

				return true;

			}

		}

		return $disable_notification;

	}




	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Returns a specific form notification.
	 *
	 * @since 5.0
	 *
	 * @param string $notification_id Notification ID.
	 * @param array  $form            Form object.
	 *
	 * @return array
	 */
	private function get_notification( $notification_id, $form = false ) {

		$notifications = $this->get_notifications( $form );

		return rgars( $notifications, $notification_id, [] );

	}

	/**
	 * Returns notifications for a form.
	 *
	 * @since 5.0
	 *
	 * @param array $form      Form object.
	 * @param bool  $is_active Include only active notifications.
	 *
	 * @return array
	 */
	private function get_notifications( $form = false, $is_active = true ) {

		if ( ! $form ) {
			$form = fg_entryautomation()->get_current_form();
		}

		$notifications = rgar( $form, 'notifications', [] );

		if ( $is_active ) {
			$notifications = array_filter( $notifications, function( $notification ) {
				return rgar( $notification, 'isActive' ) !== false;
			} );
		}

		return $notifications;

	}

	/**
	 * Returns Tasks associated to a specific Notification.
	 *
	 * @since 5.0
	 *
	 * @param array  $form            The Form object.
	 * @param string $notification_id Notification ID.
	 *
	 * @return Task[]
	 */
	private static function get_notification_tasks( $form, $notification_id ) {

		$tasks = fg_entryautomation()->get_feeds( $form['id'] );

		$tasks = array_filter( $tasks, function( $task ) use ( $notification_id ) {
			return rgars( $task, 'meta/action' ) === 'notification' && rgars( $task, 'meta/notificationId' ) === $notification_id;
		} );

		return array_map(
			function( $task ) {
				return new Task( $task );
			},
			$tasks
		);

	}

	/**
	 * Determine if Notification To property is valid.
	 *
	 * @since 5.0
	 *
	 * @param array $notification Notification object.
	 * @param Task  $task         Task.
	 *
	 * @return bool
	 */
	private static function is_notification_to_valid( $notification, $task ) {

		if ( rgar( $task->meta, 'notificationType' ) !== 'digest' ) {
			return true;
		}

		if ( in_array( rgar( $notification, 'toType' ), [ 'field', 'routing' ] ) ) {

			if ( ! rgar( $task->meta, 'notificationOverride_to' ) ) {
				return false;
			}

			return ! preg_match( self::MERGE_TAX_REGEX, rgar( $task->meta, 'notificationOverride_to' ) );

		}

		if ( rgar( $task->meta, 'notificationOverride_to' ) && preg_match( self::MERGE_TAX_REGEX, rgar( $task->meta, 'notificationOverride_to' ) ) ) {
			return false;
		}

		if ( ! rgar( $task->meta, 'notificationOverride_to' ) && preg_match( self::MERGE_TAX_REGEX, rgar( $notification, 'toEmail' ) ) ) {
			return false;
		}

		return true;

	}

}
