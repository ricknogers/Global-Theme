<?php
/**
 * Class Export
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation\Action;

use ForGravity\Entry_Automation\Action;
use ForGravity\Entry_Automation\Date;
use ForGravity\Entry_Automation\Task;
use ForGravity\Entry_Automation\Action\Export\Email;

use GFAPI;
use GFCommon;
use GFExport;
use GF_Field;
use GFFormsModel;
use Gravity_Forms\Gravity_Forms\Settings\Fields;

/**
 * Class Export
 */
class Export extends Action {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.2
	 * @access protected
	 * @var    object $_instance If available, contains an instance of this class.
	 */
	protected static $_instance = null;

	/**
	 * Defines the action name.
	 *
	 * @since  1.2
	 * @access protected
	 * @var    string $action Action name.
	 */
	protected $name = 'export';

	/**
	 * The file path of the export file.
	 *
	 * @since 3.0
	 *
	 * @var string $file_path
	 */
	public $file_path;




	// # ACTION SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Settings fields for configuring this Entry Automation action.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return array
	 */
	public function settings_fields() {

		$export_fields_field = [
			'name'          => 'exportFields',
			'label'         => esc_html__( 'Fields To Export', 'forgravity_entryautomation' ),
			'type'          => 'export_fields',
			'required'      => true,
			'error_message' => esc_html__( 'You must select at least one field.', 'forgravity_entryautomation' ),
		];

		if ( fg_entryautomation()->is_gravityforms_supported( '2.5-beta-3' ) ) {

			$export_fields_field['type'] = 'fg_entryautomation_export_fields';

			Fields::register( $export_fields_field['type'], '\ForGravity\Entry_Automation\Settings\Fields\Export_Fields' );
			unset( $export_fields_field['callback'], $export_fields_field['validation_callback'] );

		}

		$write_type_description = $this->get_export_file_type_description();
		$xlsx                   = [
			'value' => 'xlsx',
			'label' => esc_html__( 'XLSX', 'forgravity_entryautomation' ),
			'icon'  => file_get_contents( fg_entryautomation()->get_base_path() . '/images/export/file-types/xlsx.svg' ),
		];

		if ( ! empty( $write_type_description ) ) {
			$xlsx['disabled'] = 'disabled';
		}

		return [
			[
				'id'         => 'export',
				'title'      => esc_html__( 'Export Settings', 'forgravity_entryautomation' ),
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
						'id'     => 'export-file',
						'title'  => esc_html__( 'File Settings', 'forgravity_entryautomation' ),
						'fields' => [
							[
								'name'          => 'exportFileType',
								'type'          => 'radio',
								'label'         => esc_html__( 'File Type', 'forgravity_entryautomation' ),
								'description'   => $write_type_description,
								'required'      => true,
								'default_value' => 'csv',
								'choices'       => [
									[
										'value' => 'csv',
										'label' => esc_html__( 'CSV', 'forgravity_entryautomation' ),
										'icon'  => file_get_contents( fg_entryautomation()->get_base_path() . '/images/export/file-types/csv.svg' ),
									],
									$xlsx,
									[
										'value' => 'json',
										'label' => esc_html__( 'JSON', 'forgravity_entryautomation' ),
										'icon'  => file_get_contents( fg_entryautomation()->get_base_path() . '/images/export/file-types/json.svg' ),
									],
									[
										'value' => 'pdf',
										'label' => esc_html__( 'PDF', 'forgravity_entryautomation' ),
										'icon'  => file_get_contents( fg_entryautomation()->get_base_path() . '/images/export/file-types/pdf.svg' ),
									],
								],
							],
							[
								'name'          => 'exportFileName',
								'type'          => 'text',
								'label'         => esc_html__( 'File Name', 'forgravity_entryautomation' ),
								'required'      => true,
								'class'         => 'merge-tag-support mt-position-right mt-hide_all_fields',
								'default_value' => '{form_title}-{timestamp}',
								'tooltip'       => sprintf(
									esc_html__( 'Available merge tags: %s', 'forgravity_entryautomation' ),
									implode(
										', ',
										[
											'{form_id}',
											'{form_title}',
											'{timestamp}',
											'{date}',
											'{date:format}',
										]
									)
								),
							],
							[
								'name'          => 'exportWriteType',
								'type'          => 'select',
								'label'         => esc_html__( 'If File Already Exists', 'forgravity_entryautomation' ),
								'default_value' => 'new',
								'choices'       => [
									[
										'label' => esc_html__( 'Increment file name', 'forgravity_entryautomation' ),
										'value' => 'new',
									],
									[
										'label' => esc_html__( 'Overwrite file', 'forgravity_entryautomation' ),
										'value' => 'overwrite',
									],
									[
										'label' => esc_html__( 'Add entries to file', 'forgravity_entryautomation' ),
										'value' => 'add',
									],
								],
							],
						],
					],
					[
						'id'          => 'export-order',
						'title'       => esc_html__( 'Export Order Settings', 'forgravity_entryautomation' ),
						'description' => esc_html__( 'Select which column to use to order entries and what direction to order them in.', 'forgravity_entryautomation' ),
						'fields'      => [
							[
								'name'          => 'exportSorting[key]',
								'type'          => 'select',
								'label'         => esc_html__( 'Order Entries By', 'forgravity_entryautomation' ),
								'default_value' => 'id',
								'choices'       => self::get_sorting_fields(),
							],
							[
								'name'          => 'exportSorting[direction]',
								'type'          => 'select',
								'label'         => esc_html__( 'Sort Entries', 'forgravity_entryautomation' ),
								'default_value' => 'ASC',
								'choices'       => [
									[
										'label' => esc_html__( 'Ascending', 'forgravity_entryautomation' ),
										'value' => 'ASC',
									],
									[
										'label' => esc_html__( 'Descending', 'forgravity_entryautomation' ),
										'value' => 'DESC',
									],
								],
							],
						],
					],
					[
						'id'     => 'export-general',
						'title'  => esc_html__( 'Field Selection Settings', 'forgravity_entryautomation' ),
						'fields' => [ $export_fields_field ],
					],
					Action\Export\Upload_Writer::get_settings_fields(),
					[
						'id'          => 'post-export-settings',
						'title'       => esc_html__( 'Post Export Settings', 'forgravity_entryautomation' ),
						'description' => esc_html__( 'You can select actions to perform after the task is complete.', 'forgravity_entryautomation' ),
						'fields'      => [
							[
								'name'    => 'exportDeleteFile',
								'type'    => 'toggle',
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
			Email::get_settings_fields(),
		];

	}

	/**
	 * Action label, used in Entry Automation settings.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return string
	 */
	public function get_label() {

		return esc_html__( 'Export Entries', 'forgravity_entryautomation' );

	}

	/**
	 * Icon class for Entry Automation settings button.
	 *
	 * @since  1.2
	 *
	 * @return string
	 */
	public function get_icon() {

		if ( version_compare( \GFForms::$version, '2.5-beta-1', '<' ) ) {
			return 'fa-file-excel-o';
		}

		return parent::get_icon();

	}

	/**
	 * Action short label, used in Entry Automation Tasks table.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @return string
	 */
	public function get_short_label() {

		return esc_html__( 'Export', 'forgravity_entryautomation' );

	}





	// # ACTION SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Add links to feed list actions.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @param array  $links  Action links to be filtered.
	 * @param array  $task   Entry Automation Task meta.
	 * @param string $column The column ID.
	 *
	 * @return array
	 */
	public function feed_list_actions( $links, $task, $column ) {

		// Get existing export file path.
		$export_file = get_option( fg_entryautomation()->get_slug() . '_file_' . $task['task_id'] );

		$export_folder = self::get_export_folder( Task::get( $task['task_id'] ), GFAPI::get_form( $task['form_id'] ) );

		// If export file was found, add action.
		if ( $export_file && file_exists( $export_folder . $export_file ) ) {

			// Prepare URL.
			$url = admin_url( 'admin.php?action=fg_entryautomation_export_file&tid=' . $task['task_id'] );
			$url = wp_nonce_url( $url, 'fg_entryautomation_export_file' );

			$links[] = sprintf(
				'<a href="%s">%s</a>',
				$url,
				esc_html__( 'Last Exported File', 'forgravity_entryautomation' )
			);

		}

		return $links;

	}





	// # RUNNING ACTION ------------------------------------------------------------------------------------------------

	/**
	 * Process task.
	 *
	 * @since  1.2
	 * @since  3.0 Deprecated the $task and $form parameters.
	 *
	 * @return bool
	 */
	public function run() {

		// Return false if the task property isn't set correctly.
		if ( ! $this->task instanceof Task ) {
			return false;
		}

		// Prepare the task and form.
		$task = $this->task;
		$form = $this->form;

		// Set prepend/append.
		if ( 'add' === rgar( $task->meta, 'exportWriteType' ) ) {

			// Get sorting.
			$sorting = self::get_sorting( $task, $form );

			// Set export write type.
			$task->meta['exportWriteType'] = 'DESC' === $sorting['direction'] ? 'prepend' : 'append';

		}

		// Prepare export file.
		$file_path = $this->prepare_export_file();

		// Send email.
		$email = new Email( $this );
		$email->send();

		// Delete export.
		if ( rgar( $task->meta, 'exportDeleteFile' ) ) {
			wp_delete_file( $file_path );
		}

		// Save reference to file.
		if ( file_exists( $file_path ) ) {

			// Save to option.
			$export_folder = self::get_export_folder( $task, $form );
			update_option( fg_entryautomation()->get_slug() . '_file_' . $task->id, str_replace( $export_folder, '', $file_path ) );

			return $file_path;

		} else {

			// Delete option.
			delete_option( fg_entryautomation()->get_slug() . '_file_' . $task->id );

			return false;

		}

	}

	/**
	 * Export form entries.
	 *
	 * @since  1.2
	 * @since  3.0 Deprecated the $task and $form parameters.
	 *
	 * @return string|boolean
	 */
	public function prepare_export_file() {

		// Return false if the task property isn't set correctly.
		if ( ! $this->task instanceof Task ) {
			return false;
		}

		// Prepare the task and form.
		$task = $this->task;
		$form = $this->form;

		// Set the file_path.
		$this->file_path = self::get_file_path( $task, $form );

		// Initiate the writer based on the file type.
		$file_type = strtoupper( $task->meta['exportFileType'] );
		$writer    = 'ForGravity\Entry_Automation\Action\Export\Writer\\' . $file_type;

		if ( ! class_exists( $writer ) || ( $file_type === 'XLSX' && version_compare( phpversion(), '7.2', '<' ) ) ) {
			return false;
		}

		// Write export file.
		$writer          = new $writer( $this );
		$this->file_path = $writer->prepare_file();

		if ( rgars( $task->meta, 'exportFiles/enabled' ) && rgar( $task->meta, 'exportWriteType' ) !== 'add' ) {

			$upload_writer     = new Action\Export\Upload_Writer( $this );
			$archive_file_path = $upload_writer->write();

			if ( $archive_file_path ) {
				$this->file_path = $archive_file_path;
			}

		}

		/**
		 * Executed after entries have been exported.
		 *
		 * @param Task   $task      Entry Automation Task meta.
		 * @param array  $form      The form object.
		 * @param string $file_path File name of export file.
		 */
		gf_do_action( [ 'fg_entryautomation_after_export', $this->form['id'] ], $task, $form, $this->file_path );

		return $this->file_path;

	}





	// # ACTION DELETION -----------------------------------------------------------------------------------------------

	/**
	 * Delete task.
	 *
	 * @since  1.2.5
	 * @access public
	 *
	 * @param int $task_id Task ID.
	 */
	public function delete_task( $task_id ) {

		// Delete export file reference.
		delete_option( fg_entryautomation()->get_slug() . '_file_' . $task_id );

	}





	// # EXPORT FIELDS -------------------------------------------------------------------------------------------------

	/**
	 * Add default form and entry meta fields to form object.
	 *
	 * @since 1.3
	 * @since 3.0 Deprecated the second param.
	 *
	 * @param array  $form       The Form object.
	 * @param string $deprecated Deprecated. It was the export file type.
	 *
	 * @return array
	 */
	public static function add_default_export_fields( $form, $deprecated = 'csv' ) {

		// Add default export fields.
		$form = GFExport::add_default_export_fields( $form );

		// Add Entry Notes field to end of form object.
		$form['fields'][] = [
			'id'    => 'entry_notes',
			'label' => __( 'Entry Notes', 'forgravity_entryautomation' ),
		];

		// Add Fillable PDFs to end of form object.
		if ( function_exists( 'fg_fillablepdfs' ) && fg_fillablepdfs()->get_active_feeds( $form['id'] ) ) {
			$form['fields'][] = [
				'id'    => fg_fillablepdfs()->get_slug(),
				'label' => esc_html__( 'Generated PDFs', 'forgravity_entryautomation' ),
			];
		}

		// Convert field objects.
		$form = GFFormsModel::convert_field_objects( $form );

		return $form;

	}

	/**
	 * Render a Entry Ordering field.
	 *
	 * @since      Unknown
	 * @deprecated 3.0
	 *
	 * @param array $field Field settings.
	 * @param bool  $echo  Display field. Defaults to true.
	 *
	 * @return string
	 */
	public static function field_sorting( $field, $echo = true ) {

		// Prepare key field.
		$key = [
			'name'          => $field['name'] . '[key]',
			'default_value' => 'id',
			'choices'       => self::get_sorting_fields(),
		];

		// Prepare direction field.
		$direction = [
			'name'    => $field['name'] . '[direction]',
			'choices' => [
				[
					'label' => esc_html__( 'Ascending', 'forgravity_entryautomation' ),
					'value' => 'ASC',
				],
				[
					'label' => esc_html__( 'Descending', 'forgravity_entryautomation' ),
					'value' => 'DESC',
				],
			],
		];

		// Display fields.
		$html = sprintf(
			'%s %s',
			fg_entryautomation()->settings_select( $key, false ),
			fg_entryautomation()->settings_select( $direction, false )
		);

		if ( $echo ) {
			echo $html; // WPCS: XSS OK.
		}

		return $html;

	}





	// # EXPORT SETTINGS HELPER METHODS --------------------------------------------------------------------------------

	/**
	 * Prepare exportable fields for settings field.
	 *
	 * @since  3.0
	 *
	 * @return array
	 */
	public static function get_default_export_fields() {

		// Get form.
		$form = fg_entryautomation()->get_current_form();

		// If no form is available, return array.
		if ( ! $form ) {
			return [];
		}

		// Add default export fields.
		$form = self::add_default_export_fields( $form );

		// Initialize fields array.
		$fields = [];

		/**
		 * Loop through form fields.
		 *
		 * @var GF_Field $field
		 */
		foreach ( $form['fields'] as $field ) {

			// Get field inputs.
			$inputs = $field->get_entry_inputs();

			// Add fields.
			if ( ! $field->displayOnly ) {

				// Add field as choice.
				$fields[] = [
					'id'            => esc_attr( $field->id ),
					'enabled'       => false,
					'label'         => '',
					'default_label' => self::get_field_label( $field, $field->id ),
				];

			}

			// If field has inputs, add them as choices.
			if ( is_array( $inputs ) ) {

				// Loop through field inputs.
				foreach ( $inputs as $input ) {

					// Add input as choice.
					$fields[] = [
						'id'            => esc_attr( $input['id'] ),
						'enabled'       => false,
						'label'         => '',
						'default_label' => self::get_input_label( $field, $input['id'] ),
					];

				}

			}

		}

		return $fields;

	}

	/**
	 * Get fields for Entry Ordering settings field.
	 *
	 * @since  1.4
	 * @access public
	 *
	 * @uses   Export::get_default_export_fields()
	 *
	 * @return array
	 */
	public static function get_sorting_fields() {

		// Get export fields.
		$fields = self::get_default_export_fields();

		// Loop through fields, modify formatting.
		foreach ( $fields as $i => $field ) {

			// Modify formatting.
			$fields[ $i ] = [
				'label' => $field['default_label'],
				'value' => $field['id'],
			];

		}

		return $fields;

	}

	/**
	 * Add new exportable fields for settings field.
	 *
	 * @since  3.0
	 *
	 * @param array $fields Existing choices.
	 *
	 * @return array
	 */
	public static function update_export_fields( $fields ) {

		// Get form.
		$form = fg_entryautomation()->get_current_form();

		// If no form is available, return.
		if ( ! $form ) {
			return $fields;
		}

		// Get existing field IDs.
		$existing_fields = wp_list_pluck( $fields, 'id' );

		// Add default export fields.
		$form = self::add_default_export_fields( $form );

		/**
		 * Loop through form fields.
		 *
		 * @var GF_Field $form_field
		 */
		foreach ( $form['fields'] as $i => $form_field ) {

			// If the field does not exist, skip.
			if ( ! is_a( $form_field, '\GF_Field' ) ) {
				continue;
			}

			// Get field inputs.
			$inputs = $form_field->get_entry_inputs();

			// If field has inputs, add them as choices.
			if ( is_array( $inputs ) ) {

				// Loop through field inputs.
				foreach ( $inputs as $input_index => $input ) {

					// If input is already in list, skip.
					if ( in_array( strval( $input['id'] ), $existing_fields ) ) {
						continue;
					}

					// Get new choice index.
					$field_position  = self::get_previous_field_position( $form, $i, $fields ) + 1;
					$field_position += $input_index > 0 ? $input_index : 0;

					// Prepare input as choice.
					$new_choice = [
						'id'            => esc_attr( $input['id'] ),
						'enabled'       => false,
						'label'         => '',
						'default_label' => self::get_input_label( $form_field, $input['id'] ),
					];

					// Insert choice.
					array_splice( $fields, $field_position, 0, [ $new_choice ] );

				}

			}

			if ( ! $form_field->displayOnly ) {

				// If field is already in list, skip.
				if ( in_array( strval( $form_field->id ), $existing_fields ) ) {
					continue;
				}

				// Get new choice index.
				$field_position = self::get_previous_field_position( $form, $i, $fields ) + 1;

				// Prepare field as choice.
				$new_choice = [
					'id'            => esc_attr( $form_field->id ),
					'enabled'       => false,
					'label'         => '',
					'default_label' => self::get_field_label( $form_field, $form_field->id ),
				];

				// Insert choice.
				array_splice( $fields, $field_position, 0, [ $new_choice ] );

			}

		}

		// Loop through export fields.
		foreach ( $fields as $i => &$field_meta ) {

			// Get field.
			$field = GFFormsModel::get_field( $form, $field_meta['id'] );

			// If field could not be found, skip.
			if ( ! $field ) {
				unset( $fields[ $i ] );
				continue;
			}

			// Get field inputs.
			$inputs = $field->get_entry_inputs();

			// If field has inputs, find input for this export field.
			if ( is_array( $inputs ) ) {

				if ( $field_meta['id'] == intval( $field_meta['id'] ) ) {

					$field_meta['default_label'] = self::get_field_label( $field, $field_meta['id'] );

					continue;

				}

				// Loop through field inputs.
				foreach ( $inputs as $input ) {

					// If this is not the field, skip it.
					if ( strval( $input['id'] ) !== $field_meta['id'] ) {
						continue;
					}

					// Update default label.
					$field_meta['default_label'] = self::get_input_label( $field, $input['id'] );

				}

			} else if ( ! $field->displayOnly ) {

				// Update default label.
				$field_meta['default_label'] = self::get_field_label( $field, $field_meta['id'] );

			}

		}

		return array_values( $fields );

	}

	/**
	 * Get position of previous form field in export fields list.
	 *
	 * @since  3.0
	 *
	 * @param array $form             The Form object.
	 * @param int   $form_field_index Index of current field in Form object.
	 * @param array $export_fields    Export fields.
	 *
	 * @return int|null|string
	 */
	public static function get_previous_field_position( $form, $form_field_index, $export_fields ) {

		/**
		 * Get previous field.
		 *
		 * @var GF_Field $previous_field
		 */
		$previous_field = $form_field_index == 0 ? false : $form['fields'][ $form_field_index - 1 ];

		// Get previous field ID.
		if ( $previous_field ) {

			// Use final input.
			if ( $previous_field->get_entry_inputs() ) {

				// Get inputs.
				$inputs = $previous_field->inputs;

				// Get last input.
				$last_input = end( $inputs );

				// Use last input ID.
				$previous_field_id = $last_input['id'];

			} else {

				// Use field ID.
				$previous_field_id = $previous_field->id;

			}

		}

		// If previous field ID is defined, find previous field in export fields list.
		if ( isset( $previous_field_id ) ) {

			// Loop through fields.
			foreach ( $export_fields as $i => $field ) {

				// If the field IDs match, return index.
				if ( esc_attr( $previous_field_id ) === esc_attr( $field['id'] ) ) {
					return $i;
				}

			}

		}

		end( $export_fields );

		return key( $export_fields );

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Returns the label for a field.
	 *
	 * @since 3.0
	 *
	 * @param GF_Field|array $field    Field object.
	 * @param string         $field_id Target field ID.
	 *
	 * @return string
	 */
	private static function get_field_label( $field, $field_id ) {

		$label = GFCommon::get_label( $field );

		if ( ! $field ) {
			return $label;
		}

		// Append "(Full)" for name and address full field values.
		if ( $field->type === 'name' || $field->type === 'address' ) {
			$label .= esc_html__( ' (Full)', 'forgravity_entryautomation' );
		}

		// Append "(Checked)" for checkbox full field values.
		if ( $field->type === 'checkbox' ) {
			$label .= esc_html__( ' (Checked)', 'forgravity_entryautomation' );
		}

		if ( $field->type !== 'form' || $field->id == $field_id ) {
			return $label;
		}

		$exploded_field_id = explode( '.', $field_id );
		$child_field_id    = array_pop( $exploded_field_id );
		$child_field       = GFAPI::get_field( $field->gpnfForm, $child_field_id );

		return sprintf( '%s / %s', $label, self::get_field_label( $child_field, $child_field->id ) );

	}

	/**
	 * Returns the custom label for Checkbox inputs.
	 *
	 * @since 3.0
	 *
	 * @param GF_Field|array $field    Field object.
	 * @param int            $input_id The input ID.
	 *
	 * @return string
	 */
	private static function get_input_label( $field, $input_id ) {

		$input_label = GFCommon::get_label( $field, $input_id );

		return $field->type === 'checkbox' ? sprintf( '%s (%s)', GFCommon::get_label( $field ), $input_label ) : $input_label;

	}

	/**
	 * Get file name for Entry Automation export file.
	 *
	 * @since 1.0
	 * @since 3.0 Separate get_export_folder() logic to its own method.
	 *
	 * @param Task  $task Entry Automation Task meta.
	 * @param array $form The Form object.
	 *
	 * @return string
	 */
	public static function get_file_path( $task, $form ) {

		$folder = self::get_export_folder( $task, $form );

		// Get file name from Entry Automation settings.
		$file_name = rgar( $task->meta, 'exportFileName' );

		// Get default extension.
		$extension = rgar( $task->meta, 'exportFileType' );

		// If file name is empty, use default file name.
		if ( rgblank( $file_name ) ) {
			$file_name = '{form_title}-{timestamp}.' . $extension;
		}

		// Replace merge tags in file name.
		$entry     = $task->entry_id ? GFAPI::get_entry( $task->entry_id ) : [];
		$file_name = $task->merge_tags->replace_tags( $file_name, $entry, false, false, false, 'text' );

		// Get file name extension.
		$ext = pathinfo( $file_name, PATHINFO_EXTENSION );

		// If file name does not have extension, add it.
		if ( rgblank( $ext ) ) {
			$ext .= $extension;
		}

		// Sanitize the file name.
		$file_name = sanitize_file_name( $file_name );

		// Get filename without extension.
		$file_name = pathinfo( $file_name, PATHINFO_FILENAME );

		// Define target file path.
		$target_file_path = $folder . $file_name . '.' . $ext;

		if ( 'overwrite' === rgar( $task->meta, 'exportWriteType' ) && file_exists( $target_file_path ) ) {

			// Log that we will be deleting the existing file.
			fg_entryautomation()->log_debug( __METHOD__ . '(): Overwrite existing export file enabled. Deleting previous export file.' );

			// Delete file.
			wp_delete_file( $target_file_path );

		} else if ( ( 'new' === rgar( $task->meta, 'exportWriteType' ) || ! rgar( $task->meta, 'exportWriteType' ) ) && file_exists( $target_file_path ) ) {

			// Define starting duplicate file name counter.
			$counter = 1;

			// If file name exists, iterate until it does not.
			while ( file_exists( $target_file_path ) ) {
				$target_file_path = $folder . $file_name . '-' . $counter . '.' . $ext;
				$counter++;
			}

		}

		/**
		 * Modify the export file path.
		 *
		 * @since unknown
		 * @deprecated 3.0 Use the {@see 'fg_entryautomation_export_file_path'} filter instead.
		 *
		 * @param string $file_path File path of export file.
		 * @param Task   $task      Entry Automation Task meta.
		 * @param array  $form      The Form object.
		 */
		$target_file_path = gf_apply_filters( [
			'fg_entryautomation_export_file_name',
			$form['id'],
		], $target_file_path, $task, $form );

		// Trigger deprecation notices.
		if ( gf_has_filters( [ 'fg_entryautomation_export_file_name', $form['id'] ] ) ) {
			_deprecated_hook( 'fg_entryautomation_export_file_name', '3.0', 'fg_entryautomation_export_file_path' );
		}

		/**
		 * Modify the export file path.
		 *
		 * @since 3.0
		 *
		 * @param string $file_path File path of export file.
		 * @param Task   $task      Entry Automation Task meta.
		 * @param array  $form      The Form object.
		 */
		$target_file_path = gf_apply_filters( [
			'fg_entryautomation_export_file_path',
			$form['id'],
		], $target_file_path, $task, $form );

		return $target_file_path;

	}

	/**
	 * Get the upload root default path.
	 *
	 * @since 3.0
	 *
	 * @param string $sub_folder The sub folder. Default is 'export'.
	 *
	 * @return string
	 */
	public static function get_upload_root( $sub_folder = 'export' ) {

		return trailingslashit( GFFormsModel::get_upload_root() . fg_entryautomation()->get_slug() . "/{$sub_folder}" );

	}

	/**
	 * Get the export folder by form ID, create it if not exist.
	 *
	 * @since 3.0
	 * @since 3.1 Added sub folders named with hashed form ID.
	 *
	 * @param Task  $task Entry Automation Task meta.
	 * @param array $form The Form object.
	 *
	 * @return string
	 */
	public static function get_export_folder( $task, $form ) {

		// Require export class.
		if ( ! class_exists( 'GFExport' ) ) {
			require_once GFCommon::get_base_path() . '/export.php';
		}

		// Get form folder name.
		$form_id          = absint( $form['id'] );
		$form_folder_name = sprintf( '%d-%s', $form_id, wp_hash( $form_id ) );

		// Get upload root.
		$upload_root = self::get_upload_root();

		/**
		 * Modify the root export folder path.
		 *
		 * @since 1.2.3
		 *
		 * @param string $folder Path to the export folder.
		 * @param Task   $task   Entry Automation Task meta.
		 * @param array  $form   The Form object.
		 */
		$folder = gf_apply_filters( [
			'fg_entryautomation_export_folder',
			$form_id,
			$task->id,
		], trailingslashit( $upload_root . $form_folder_name ), $task, $form );

		// If export folder does not exist, create it.
		if ( ! is_dir( $folder ) ) {
			wp_mkdir_p( $folder );
		}

		// Add trailing slash to folder path.
		$folder = trailingslashit( $folder );

		// Add htaccess file to the base export folder.
		GFExport::maybe_create_htaccess_file( $upload_root );

		// Add index file to the form export folder.
		GFExport::maybe_create_index_file( $folder );

		return $folder;

	}

	/**
	 * Get sorting for task.
	 *
	 * @since 1.4
	 * @since 5.0 Change the default sorting for draft submissions.
	 *
	 * @param Task  $task Entry Automation task meta.
	 * @param array $form The current Form object.
	 *
	 * @return array
	 */
	public static function get_sorting( $task, $form ) {

		// Initialize default sorting.
		$sorting = [ 'key' => 'id', 'direction' => 'DESC' ];
		if ( rgar( $task->meta, 'entryType' ) === 'draft_submission' ) {
			$sorting = [ 'key' => 'date_created', 'direction' => 'DESC' ];
		}

		// If task has sorting defined, use it.
		if ( rgar( $task->meta, 'exportSorting' ) ) {
			$sorting = [
				'key'       => $task->meta['exportSorting']['key'],
				'direction' => $task->meta['exportSorting']['direction'],
			];
		}

		/**
		 * Define entry sorting criteria.
		 *
		 * @since 1.2
		 *
		 * @param array $sorting Sorting criteria.
		 * @param Task  $task    Entry Automation Task meta.
		 * @param array $form    The Form object.
		 */
		$sorting = gf_apply_filters( [
			'fg_entryautomation_export_sorting',
			$task->id,
		], $sorting, $task, $form );

		return $sorting;

	}

	/**
	 * Get the export file type description.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public static function get_export_file_type_description() {

		$write_type_description = '';

		if ( version_compare( phpversion(), '7.2', '<' ) ) {
			/* translators: PHP version */
			$write_type_description = sprintf( esc_html__( 'Exporting to the Excel (XLSX) file format requires PHP 7.2+. Please upgrade your version of PHP (%s) to access this feature.', 'forgravity_entryautomation' ), PHP_VERSION );
		}

		return $write_type_description;

	}

}
