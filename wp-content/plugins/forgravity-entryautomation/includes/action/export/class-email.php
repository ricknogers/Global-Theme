<?php

namespace ForGravity\Entry_Automation\Action\Export;

use ForGravity\Entry_Automation\Action\Export;
use ForGravity\Entry_Automation\Task;
use GFAPI;
use GFCommon;

/**
 * Email the action result.
 *
 * @since 3.0
 */
class Email {

	/**
	 * The export action.
	 *
	 * @since 3.0
	 *
	 * @var Export $action
	 */
	private $action;

	/**
	 * The task.
	 *
	 * @since 3.0
	 *
	 * @var Task $task;
	 */
	private $task;

	/**
	 * The form with default exported fields.
	 *
	 * @since 3.0
	 *
	 * @var array $form
	 */
	private $form;

	/**
	 * The exported file path.
	 *
	 * @since 3.0
	 *
	 * @var string $file_path
	 */
	private $file_path;

	/**
	 * The entry object.
	 *
	 * @since 3.0
	 *
	 * @var array $entry
	 */
	private $entry;

	/**
	 * The from email.
	 *
	 * @since 3.0
	 *
	 * @var string $from_email;
	 */
	private $from_email;

	/**
	 * The from name.
	 *
	 * @since 3.0
	 *
	 * @var string $from_name
	 */
	private $from_name;

	/**
	 * The recipient's email.
	 *
	 * @since 3.0
	 *
	 * @var string $to
	 */
	private $to;

	/**
	 * The maximum file size of an email attachment.
	 *
	 * @since 3.0
	 *
	 * @var int $maximum_attachment_size
	 */
	private $maximum_attachment_size;

	/**
	 * The email subject.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	private $subject;

	/**
	 * The email message.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	private $message;

	/**
	 * The email headers.
	 *
	 * @since 3.0
	 *
	 * @var $headers
	 */
	private $headers;

	/**
	 * Writer constructor.
	 *
	 * @since 3.0
	 *
	 * @param Export $action The Export action object.
	 */
	public function __construct( $action ) {

		// Prepare the action.
		$this->action = $action;

		// Prepare the properties get from an action.
		$this->task      = $this->action->task;
		$this->form      = $this->action->form;
		$this->file_path = $this->action->file_path;

		// Get entry object for merge tags.
		$this->entry = $this->task->entry_id ? GFAPI::get_entry( $this->task->entry_id ) : [];
		if ( is_wp_error( $this->entry ) ) {
			$this->entry = [];
		}

		// Prepare default wp_mail() params.
		$this->set_from();
		$this->set_to();
		$this->set_maximum_attachment_size();
		$this->set_subject();
		$this->set_message();
		$this->set_headers();

	}

	/**
	 * Get the Email Settings section.
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public static function get_settings_fields() {

		// Prepare merge tag class.
		$mt_class = ' merge-tag-support mt-position-right mt-hide_all_fields';

		// Get the current form.
		$form = fg_entryautomation()->get_current_form();

		// When we're running AJAX action, $form is false here so let's get it again.
		if ( ! $form ) {
			$form = GFAPI::get_form( sanitize_text_field( $_POST['form_id'] ) );
		}

		return [
			'id'         => 'email',
			'title'      => esc_html__( 'Email Settings', 'forgravity_entryautomation' ),
			'dependency' => [
				'live'   => true,
				'fields' => [
					[
						'field'  => 'action',
						'values' => [ 'export' ],
					],
				],
			],
			'sections'   => [
				[
					'id'     => 'export-email',
					'title'  => esc_html__( 'Email Settings', 'forgravity_entryautomation' ),
					'fields' => [
						[
							'name'    => 'exportEmailEnable',
							'type'    => 'toggle',
							'label'   => esc_html__( 'Email Generated Export File', 'forgravity_entryautomation' ),
							'tooltip' => sprintf(
								'<h6>%s</h6>%s',
								esc_html__( 'Send Email', 'forgravity_entryautomation' ),
								esc_html__( 'When enabled, an email will be sent after the export is completed with the generated export file attached. By default, only export files under 10 MB will be sent.', 'forgravity_entryautomation' )
							),
						],
						[
							'name'          => 'exportEmailAddress',
							'type'          => 'text',
							'label'         => esc_html__( 'Send Email To', 'forgravity_entryautomation' ),
							'required'      => true,
							'class'         => $mt_class,
							'default_value' => get_bloginfo( 'admin_email' ),
							'dependency'    => [
								'live'   => true,
								'fields' => [
									[
										'field' => 'exportEmailEnable',
									],
								],
							],
							'tooltip'       => sprintf(
								'<h6>%s</h6>%s',
								esc_html__( 'Send Email To', 'forgravity_entryautomation' ),
								esc_html__( 'Set the email address the export file will be emailed to. You can send to multiple email addresses by separating them with commas.', 'forgravity_entryautomation' )
							),
						],
						[
							'name'          => 'exportEmailFromName',
							'type'          => 'text',
							'label'         => esc_html__( 'From Name', 'forgravity_entryautomation' ),
							'required'      => true,
							'class'         => 'medium',
							'default_value' => get_bloginfo( 'name' ),
							'dependency'    => [
								'live'   => true,
								'fields' => [
									[
										'field' => 'exportEmailEnable',
									],
								],
							],
						],
						[
							'name'          => 'exportEmailFrom',
							'type'          => 'text',
							'label'         => esc_html__( 'From Address', 'forgravity_entryautomation' ),
							'required'      => true,
							'class'         => 'medium',
							'default_value' => get_bloginfo( 'admin_email' ),
							'dependency'    => [
								'live'   => true,
								'fields' => [
									[
										'field' => 'exportEmailEnable',
									],
								],
							],
						],
						[
							'name'          => 'exportEmailSubject',
							'type'          => 'text',
							'label'         => esc_html__( 'Subject', 'forgravity_entryautomation' ),
							'required'      => true,
							'class'         => $mt_class,
							/* translators: The form title. */
							'default_value' => sprintf( __( 'Entry Automation export for "%s"', 'forgravity_entryautomation' ), sanitize_text_field( $form['title'] ) ),
							'dependency'    => [
								'live'   => true,
								'fields' => [
									[
										'field' => 'exportEmailEnable',
									],
								],
							],
						],
						[
							'name'          => 'exportEmailMessage',
							'type'          => 'textarea',
							'label'         => esc_html__( 'Message', 'forgravity_entryautomation' ),
							'required'      => true,
							'class'         => 'merge-tag-support mt-position-right mt-hide_all_fields mt-prepopulate',
							/* translators: The form title. */
							'default_value' => sprintf( __( 'The latest entry export for your form, %s, is attached to this message.', 'forgravity_entryautomation' ), sanitize_text_field( $form['title'] ) ),
							'dependency'    => [
								'live'   => true,
								'fields' => [
									[
										'field' => 'exportEmailEnable',
									],
								],
							],
							'use_editor'    => true,
							'callback'      => [ __CLASS__, 'field_export_email_message' ],
						],
					],
				],
			],
		];

	}

	/**
	 * Send email.
	 *
	 * @since 3.0
	 *
	 * @return false|void
	 */
	public function send() {

		global $phpmailer;

		if ( ! rgar( $this->action->task->meta, 'exportEmailEnable' ) ) {
			return;
		}

		// Log that email export is enabled.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Email of export file is enabled. Beginning email process.' );

		$file_path = $this->file_path;
		$task      = $this->task;

		// If file name is empty, return.
		if ( rgblank( $file_path ) ) {
			fg_entryautomation()->log_error( __METHOD__ . '(): Unable to email export file because export file name was not provided.' );
			return;
		}

		// Prepare wp_mail() params.
		$to                      = $this->get_to();
		$maximum_attachment_size = $this->get_maximum_attachment_size();
		$subject                 = $this->get_subject();
		$message                 = $this->get_message();
		$headers                 = $this->get_headers();

		// If email address is invalid, return.
		if ( ! $to ) {
			fg_entryautomation()->log_error( __METHOD__ . '(): Unable to email export file because an invalid email address was provided.' );

			return false;
		}

		// If file size is larger than maximum allowed, exit.
		if ( $maximum_attachment_size && filesize( $this->file_path ) > $maximum_attachment_size ) {
			fg_entryautomation()->log_error( sprintf(
				__METHOD__ . '(): Unable to email export file because it is larger (%s) than the allowed maximum attachment size (%s).',
				size_format( filesize( $this->file_path ), 2 ),
				size_format( $maximum_attachment_size, 2 )
			) );
			return false;
		}

		// Log email to be sent prior to sending it.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Email to be sent: ' . print_r( compact( 'to', 'subject', 'message', 'headers', 'file_path' ), true ) );

		// Send email.
		$is_success = wp_mail( $to, $subject, $message, $headers, [ $file_path ] );
		$result     = is_wp_error( $is_success ) ? $is_success->get_error_message() : $is_success;

		// Get $phpmailer->ErrorInfo value if available.
		$error_info = is_object( $phpmailer ) ? $phpmailer->ErrorInfo : '';

		// Log email send result.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Result from wp_mail(): ' . print_r( $result, true ) );

		if ( ! is_wp_error( $is_success ) && $is_success ) {
			fg_entryautomation()->log_debug( sprintf( '%s(): WordPress successfully passed the export email (Task #%d on Form #%d) to the sending server.', __METHOD__, $task->id, $task->form_id ) );
		} else {
			fg_entryautomation()->log_error( sprintf( '%s(): WordPress was unable to send the export email (Task #%d on Form #%d) to the sending server.', __METHOD__, $task->id, $task->form_id ) );
		}

		if ( has_filter( 'phpmailer_init' ) ) {
			fg_entryautomation()->log_debug( __METHOD__ . '(): The WordPress phpmailer_init hook has been detected, usually used by SMTP plugins. It can alter the email setup/content or sending server, and impact the deliverability of the export email.' );
		}

		if ( ! empty( $error_info ) ) {
			fg_entryautomation()->log_debug( __METHOD__ . '(): PHPMailer class returned an error message: ' . $error_info );
		}

	}





	// # SETTERS -------------------------------------------------------------------------------------------------------

	/**
	 * Set the from email and name.
	 *
	 * @since 3.0
	 *
	 * @param string $email The from email.
	 * @param string $name  The from name.
	 */
	public function set_from( $email = '', $name = '' ) {

		// Prepare from address.
		if ( empty( $email ) ) {
			if ( rgar( $this->task->meta, 'exportEmailFrom' ) ) {
				$email = $this->task->merge_tags->replace_tags( sanitize_text_field( $this->task->meta['exportEmailFrom'] ), [], false, false, false, 'text' );
			} else {
				$email = get_option( 'admin_email' );
			}
		}

		// Prepare from name.
		if ( empty( $name ) && rgar( $this->task->meta, 'exportEmailFromName' ) ) {
			$name = $this->task->merge_tags->replace_tags( sanitize_text_field( $this->task->meta['exportEmailFromName'] ), [], false, false, false, 'text' );
		}

		$this->from_email = $email;
		$this->from_name  = $name;

	}

	/**
	 * Set the recipient's email.
	 *
	 * @since 3.0
	 *
	 * @param string $to The email address.
	 *
	 * @return void|false
	 */
	public function set_to( $to = '' ) {

		if ( empty( $to ) ) {

			// Prepare the recipient's email address.
			$export_email = rgars( $this->task, 'meta/exportEmailAddress' );
			$to           = $this->task->merge_tags->replace_tags( $export_email, $this->entry, false, false, false, 'text' );

		}

		// If email address is invalid, return.
		if ( ! GFCommon::is_valid_email_list( $to ) ) {
			fg_entryautomation()->log_error( __METHOD__ . "(): $to is an invalid email address; cannot send email." );

			return false;
		}

		$this->to = $to;

	}

	/**
	 * Get the maximum file size.
	 *
	 * @since 3.0
	 *
	 * @param int $maximum_attachment_size The maximum file size.
	 *
	 * @return void
	 */
	public function set_maximum_attachment_size( $maximum_attachment_size = null ) {

		if ( empty( $maximum_attachment_size ) ) {

			// Prepare the maximum attachment size.
			$maximum_attachment_size = 10485760;

		}

		$this->maximum_attachment_size = (int) $maximum_attachment_size;

	}

	/**
	 * Set the email subject.
	 *
	 * @since 3.0
	 *
	 * @param string $subject The email subject.
	 */
	public function set_subject( $subject = '' ) {

		if ( empty( $subject ) ) {

			// Prepare email subject.
			$subject = rgars( $this->task, 'meta/exportEmailSubject' );
			if ( $subject ) {
				$subject = $this->task->merge_tags->replace_tags( $subject, $this->entry, false, true, false, 'text' );
			} else {
				/* translators: The form title. */
				$subject = sprintf( __( 'Entry Automation export for "%s"', 'forgravity_entryautomation' ), sanitize_text_field( $this->form['title'] ) );
			}

		}

		$this->subject = $subject;

	}

	/**
	 * Set the email message.
	 *
	 * @since 3.0
	 *
	 * @param string $message The email message.
	 */
	public function set_message( $message = '' ) {

		if ( empty( $message ) ) {

			// Prepare email message.
			$message = rgars( $this->task, 'meta/exportEmailMessage' );
			if ( $message ) {
				$message = $this->task->merge_tags->replace_tags( $message, $this->entry, false, false );
				$message = wp_kses( $message, wp_kses_allowed_html( 'post' ) );
			} else {
				/* translators: The form title. */
				$message = sprintf( __( 'The latest entry export for your form, %s, is attached to this message.', 'forgravity_entryautomation' ), sanitize_text_field( $this->form['title'] ) );
			}

		}

		$this->message = $message;

	}

	/**
	 * Set the email headers.
	 *
	 * @since 3.0
	 *
	 * @param array $headers The email headers.
	 */
	public function set_headers( $headers = [] ) {

		if ( empty( $headers ) ) {

			// Prepare email headers.
			$message = $this->get_message();

			$headers = [
				'From: ' . $this->get_from(),
				'Content-type: ' . ( $message === strip_tags( $message ) ? 'text/plain' : 'text/html' ),
			];

		}

		$this->headers = $headers;

	}





	// # GETTERS -------------------------------------------------------------------------------------------------------

	/**
	 * Get the email from name and email.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_from() {

		$from_name  = $this->from_name;
		$from_email = $this->from_email;

		$from = empty( $from_name ) ? $from_email : $from_name . ' <' . $from_email . '>';

		/**
		 * Modify the export email from.
		 *
		 * @since 3.0
		 *
		 * @param string $from      Email from.
		 * @param Task   $task      Entry Automation Task meta.
		 * @param array  $form      The form object.
		 * @param string $file_path Export file path.
		 */
		return gf_apply_filters(
			[
				'fg_entryautomation_export_email_from',
				$this->form['id'],
			],
			$from,
			$this->task,
			$this->form,
			$this->file_path
		);

	}

	/**
	 * Get the recipient's email.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_to() {

		return $this->to;

	}

	/**
	 * Get the maximum file size.
	 *
	 * @since 3.0
	 *
	 * @return int
	 */
	public function get_maximum_attachment_size() {

		/**
		 * Get maximum email attachment size allowed in bytes.
		 * Defaults to 2 MB.
		 *
		 * @since unknown
		 *
		 * @param int    $maximum_attachment_size Maximum file size allowed for attachment.
		 * @param Task   $task                    Entry Automation Task meta.
		 * @param array  $form                    The form object.
		 * @param string $file_path               Export file path.
		 */
		return gf_apply_filters(
			[
				'fg_entryautomation_maximum_attachment_size',
				$this->form['id'],
			],
			$this->maximum_attachment_size,
			$this->task,
			$this->form,
			$this->file_path
		);

	}

	/**
	 * Get the email subject.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_subject() {

		/**
		 * Modify the export email subject.
		 *
		 * @since unknown
		 *
		 * @param string $subject   Email subject.
		 * @param Task   $task      Entry Automation Task meta.
		 * @param array  $form      The form object.
		 * @param string $file_path Export file path.
		 */
		$subject = gf_apply_filters(
			[
				'fg_entryautomation_export_email_subject',
				$this->form['id'],
			],
			$this->subject,
			$this->task,
			$this->form,
			$this->file_path
		);

		// If the subject is empty, return the default value.
		if ( empty( $subject ) ) {
			fg_entryautomation()->log_debug( __METHOD__ . '(): the fg_entryautomation_export_email_subject filter set the subject as an empty string, fallback to use the default subject' );

			return $this->subject;
		}

		return $subject;

	}

	/**
	 * Get the email message.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_message() {

		/**
		 * Modify the export email message.
		 *
		 * @since unknown
		 *
		 * @param string $message   Email message.
		 * @param Task   $task      Entry Automation Task meta.
		 * @param array  $form      The form object.
		 * @param string $file_path Export file path.
		 */
		$message = gf_apply_filters(
			[
				'fg_entryautomation_export_email_message',
				$this->form['id'],
			],
			$this->message,
			$this->task,
			$this->form,
			$this->file_path
		);

		// If the subject is empty, return.
		if ( empty( $message ) ) {
			fg_entryautomation()->log_debug( __METHOD__ . '(): the fg_entryautomation_export_email_message filter set the message as an empty string, fallback to use the default message' );

			return $this->message;
		}

		return $message;

	}

	/**
	 * Get the email headers.
	 *
	 * @since 3.0
	 *
	 * @return mixed
	 */
	public function get_headers() {

		/**
		 * Modify the export email headers.
		 *
		 * @since unknown
		 *
		 * @param array  $headers   Email headers.
		 * @param Task   $task      Entry Automation Task meta.
		 * @param string $file_path Export file path.
		 */
		$headers = gf_apply_filters(
			[
				'fg_entryautomation_export_email_headers',
				$this->form['id'],
			],
			$this->headers,
			$this->task,
			$this->file_path
		);

		// If the headers is empty, return.
		if ( empty( $headers ) ) {
			fg_entryautomation()->log_debug( __METHOD__ . '(): the fg_entryautomation_export_email_headers filter set the headers as an empty array, fallback to use the default headers' );

			return $this->headers;
		}

		return $headers;

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Render a textarea field with a wrapper for styling.
	 *
	 * @since  3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public static function field_export_email_message( $field, $echo = true ) {

		// Get textarea markup.
		$html = fg_entryautomation()->settings_textarea( $field, false );

		// Wrap markup with wrapper.
		$html = sprintf(
			'<div class="%s-container">%s</div>',
			$field['name'],
			$html
		);

		if ( $echo ) {
			echo $html;
		}

		return $html;

	}

}
