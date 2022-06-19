<?php
/**
 * Base Writer Class for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation\Action\Export;

use ForGravity\Entry_Automation\Action\Export;
use ForGravity\Entry_Automation\Task;
use ForGravity\Entry_Automation\Entries;
use GFAPI;
use GFCommon;
use GFFormsModel;

/**
 * Write exported entries to a file.
 *
 * @since 3.0
 */
class Writer {

	/**
	 * The export action.
	 *
	 * @since 3.0
	 *
	 * @var Export $action
	 */
	protected $action;

	/**
	 * The search criteria.
	 *
	 * @since 3.0
	 *
	 * @var array $search_criteria
	 */
	protected $search_criteria;

	/**
	 * The paging.
	 *
	 * @since 3.0
	 *
	 * @var int[] $paging
	 */
	protected $paging;

	/**
	 * The sorting.
	 *
	 * @since 3.0
	 *
	 * @var array $sorting
	 */
	protected $sorting;

	/**
	 * The total found entries count.
	 *
	 * @since 3.0
	 *
	 * @var int $found_entries
	 */
	protected $found_entries;

	/**
	 * The fields.
	 *
	 * @since 3.0
	 *
	 * @var array $fields
	 */
	protected $fields;

	/**
	 * The form with default exported fields.
	 *
	 * @since 3.0
	 *
	 * @var array $form
	 */
	protected $form;

	/**
	 * The file content.
	 *
	 * @since 3.0
	 *
	 * @var array|string $file_content
	 */
	protected $file_content = '';

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

		// Prepare the task and form.
		$task = $this->action->task;
		$form = $this->action->form;

		// Prepare search criteria.
		$this->search_criteria = $task->get_search_criteria();

		// Prepare paging criteria.
		$this->paging = Entries::$paging;

		// Get sorting.
		$this->sorting = Export::get_sorting( $task, $form );

		// Get total entry count.
		$this->found_entries = $task->found_entries;

		// Get export fields.
		$this->fields = array_filter( $task->meta['exportFields'], function( $field ) { return $field['enabled']; } );

		// Add default export fields to form.
		$this->form = Export::add_default_export_fields( $this->action->form );

	}

	/**
	 * Build the header for the export file.
	 *
	 * @since  3.0
	 */
	protected function build_header() {
	}

	/**
	 * Build the formatted entry.
	 *
	 * @since 3.0
	 *
	 * @param array|string $built_entry       The built entry.
	 * @param array        $entry             The original entry data.
	 * @param int          $entries_processed The entries has been processed.
	 */
	protected function build_entry( &$built_entry, $entry, $entries_processed ) {
	}

	/**
	 * Build the footer for the export file.
	 *
	 * @since 3.0
	 */
	protected function build_footer() {
	}

	/**
	 * Export form entries to a file.
	 *
	 * @since  3.0
	 *
	 * @return string
	 */
	public function prepare_file() {

		// Prepare the task.
		$task = $this->action->task;

		// Get export file name.
		$file_path = $this->action->file_path;

		// Log export file name.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Exporting entries to file "' . $file_path . '".' );

		// Build the file header.
		$this->build_header();

		// Prevent Nested Forms from replacing field value.
		add_filter( 'gpnf_should_use_static_value', '__return_true' );

		// Loop until all entries have been processed.
		while ( $task->entries_processed < $this->found_entries ) {

			// Log the page number.
			fg_entryautomation()->log_debug( __METHOD__ . '(): Starting export of page ' . ( round( $task->entries_processed / $this->paging['page_size'] ) + 1 ) . ' of ' . ( round( $this->found_entries / $this->paging['page_size'] ) + 1 ) );

			// Get entries.
			$args    = [
				'form_id'         => $this->form['id'],
				'search_criteria' => $this->search_criteria,
				'sorting'         => $this->sorting,
				'paging'          => $this->paging,
			];
			$entry_type = rgar( $task->meta, 'entryType' );
			$entries   = $this->action->entries->get( $args, $entry_type );

			// If no more entries were found, break.
			if ( empty( $entries ) ) {
				fg_entryautomation()->log_debug( __METHOD__ . '(): No entries were found for this page.' );
				break;
			}

			// Loop through entries.
			foreach ( $entries as $entry ) {

				// Initialize built entry.
				$built_entry = is_array( $this->file_content ) ? [] : '';

				// Turn draft submissions into a standard entry object.
				if ( $entry_type === 'draft_submission' ) {
					$entry = Entries::decode_draft_submission( $entry );
				}

				$this->build_entry( $built_entry, $entry, $task->entries_processed );

				// Add entry to built array.
				if ( is_array( $this->file_content ) ) {
					$this->file_content[] = $built_entry;
				} else {
					$this->file_content .= $built_entry;
				}

				// Increase entries processed count.
				$task->entries_processed++;

			}

			// Increase offset.
			$this->paging['offset'] += $this->paging['page_size'];

		}

		// Reset Nested Forms filter.
		remove_filter( 'gpnf_should_use_static_value', '__return_true' );

		// Build the file footer.
		$this->build_footer();

		// Write entries to file.
		$this->write_to_file( $file_path, $task->meta['exportWriteType'] );

		// Log that export has been completed.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Export completed.' );

		return $file_path;

	}

	/**
	 * Write entries to a file.
	 *
	 * @since 3.0
	 *
	 * @param string $file_path  Path to export file.
	 * @param string $write_type Export write type.
	 *
	 * @return bool|int
	 */
	protected function write_to_file( $file_path, $write_type = 'new' ) {

		return true;

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Get the field label.
	 *
	 * @since  3.0
	 *
	 * @param array|\GF_Field_Repeater $form       The form object.
	 * @param array                    $field_meta Fields being exported.
	 *
	 * @return string
	 */
	protected function get_field_label( $form, $field_meta ) {

		// Get field.
		$field = GFFormsModel::get_field( $form, $field_meta['id'] );

		// If field does not exist, return.
		if ( ! $field ) {
			return '';
		}

		// Get field label.
		$label = rgar( $field_meta, 'label' ) ? $field_meta['label'] : rgar( $field_meta, 'default_label' );

		if ( ! empty( $label ) ) {
			return $label;
		}

		// For repeater and nested forms, field_meta didn't come with default_label, we will manually build up the label.
		$field_label = ( rgobj( $field, 'adminLabel' ) ? $field->adminLabel : $field->label );

		// Get field input.
		$input = GFFormsModel::get_input( $field, $field_meta['id'] );

		// Prepare label.
		if ( $input != null && ! rgar( $field_meta, 'label' ) ) {
			$input_label = rgar( $input, 'customLabel', rgar( $input, 'label' ) );
			$label       = $field_label . ' (' . $input_label . ')';
		} else {
			$label = $field_label;
		}

		return $label;

	}

	/**
	 * Get field value from entry.
	 *
	 * @since  3.0
	 *
	 * @param array  $form     The Form object.
	 * @param array  $entry    The Entry object.
	 * @param string $field_id The field ID to return.
	 * @param bool   $is_csv   Return value for CSV file.
	 *
	 * @return string
	 */
	protected function get_field_value( $form, $entry, $field_id, $is_csv = true ) {

		// Get field value based on field ID.
		switch ( $field_id ) {

			case 'date_created':

				// Get entry GMT time.
				$entry_gmt_time = mysql2date( 'G', $entry['date_created'] );

				// Get entry local time.
				$entry_local_time = GFCommon::get_local_timestamp( $entry_gmt_time );

				// Get formatted time.
				$field_value = date_i18n( 'Y-m-d H:i:s', $entry_local_time, true );

				break;

			case 'entry_notes':

				// Get entry notes.
				$field_value = GFFormsModel::get_lead_notes( $entry['id'] );
				$field_value = json_decode( json_encode( $field_value ), true );

				break;

			case 'forgravity-fillablepdfs':
				$field_value = $this->get_fillablepdfs_pdfs( $entry );
				break;

			case 'form_title':

				// Get form title.
				$field_value = rgar( $form, 'title' );

				break;

			case 'ip':
			case 'source_url':
			case 'id':

				// Get value, set to lowercase.
				$field_value = rgar( $entry, strtolower( $field_id ) );

				break;

			default:

				/**
				 * Get field.
				 *
				 * @var \GF_Field|\GF_Field_Date|\GF_Field_Repeater|\GP_Field_Nested_Form $field
				 */
				$field = GFAPI::get_field( $form, $field_id );

				// If this is a Nested Form, get Nested Form value.
				if ( $field && 'form' === $field->type ) {

					$field_value = $this->get_nested_form_value( $form, $entry, $field );

					// If this is a Repeater field, get Repeater value.
				} elseif ( $field && 'repeater' === $field->type ) {

					$field_value = $this->get_repeater_field_value( $form, $entry, $field, $is_csv );

					// If this is a date field, format date value.
				} else if ( $field && 'date' === $field->get_input_type() ) {

					// Get field value.
					$field_value = rgar( $entry, $field_id );

					// Format date.
					$field_value = GFCommon::date_display( $field_value, $field->dateFormat, $field->get_output_date_format() );

					// If the field exists, use GF_Field::get_value_export().
				} else if ( is_a( $field, '\GF_Field' ) ) {

					$field_value = $field->get_value_export( $entry, $field_id, false, $is_csv );
					$field_value = $is_csv || empty( $field_value ) ? $field_value : fg_entryautomation()->maybe_decode_json( $field_value );

					// Return value from entry object.
				} else {

					$field_value = rgar( $entry, $field_id );

				}

				break;

		}

		// Unserialize field value.
		$field_value = maybe_unserialize( $field_value );

		return $field_value;

	}

	/**
	 * Get available Fillable PDFs for entry.
	 *
	 * @since 4.0
	 *
	 * @param array $entry The Entry object.
	 *
	 * @return array
	 */
	protected function get_fillablepdfs_pdfs( $entry ) {

		$pdf_urls = [];

		if ( ! function_exists( 'fg_fillablepdfs' ) ) {
			return $pdf_urls;
		}

		$entry_pdfs = fg_fillablepdfs()->get_entry_pdfs( $entry );

		if ( empty( $entry_pdfs ) ) {
			return $pdf_urls;
		}

		foreach ( $entry_pdfs as $entry_pdf ) {
			$pdf_urls[] = fg_fillablepdfs()->build_pdf_url( $entry_pdf, true );
		}

		fg_entryautomation()->log_debug( __METHOD__ . '(): PDF URLs: ' . print_r( $pdf_urls, true ) );

		return $pdf_urls;

	}

	/**
	 * Get field value for Nested Form.
	 *
	 * @since  3.0
	 *
	 * @param array                 $form              The Form object.
	 * @param array                 $entry             The Entry object.
	 * @param \GP_Field_Nested_Form $nested_form_field The Nested Form field.
	 *
	 * @return array
	 */
	protected function get_nested_form_value( $form, $entry, $nested_form_field ) {

		// Initialize value array.
		$value = [];

		// Get entry IDs.
		$entry_ids = rgar( $entry, $nested_form_field->id );

		// If Nested form has no entries, return.
		if ( rgblank( $entry_ids ) ) {
			return $value;
		}

		// Get Nested Form.
		$nested_form = $nested_form_field->gpnfForm ? GFAPI::get_form( $nested_form_field->gpnfForm ) : false;

		// If Nested form was not found, return.
		if ( ! $nested_form ) {
			return $value;
		}

		// Explode entry IDs.
		$entry_ids = explode( ',', $entry_ids );

		// Loop through entry IDs.
		foreach ( $entry_ids as $entry_id ) {

			// Initialize entry array.
			$e = [];

			// Get nested entry.
			$nested_entry = GFAPI::get_entry( $entry_id );

			// If entry could not be found, skip.
			if ( ! $nested_entry ) {
				continue;
			}

			// Loop through Nested Form fields.
			foreach ( $nested_form['fields'] as $field ) {

				// If field is not nested, skip.
				if ( ! is_array( $nested_form_field->gpnfFields ) || ! in_array( $field->id, $nested_form_field->gpnfFields ) ) {
					continue;
				}

				// Get field label and value.
				$field_label = $this->get_field_label( $nested_form, [ 'id' => $field->id ] );
				$field_value = $this->get_field_value( $nested_form, $nested_entry, $field->id );

				// Add to entry.
				$e[ $field_label ] = $field_value;

			}

			// Add entry to return value.
			$value[] = $e;

		}

		return $value;

	}

	/**
	 * Get values for Repeater field.
	 *
	 * @since  3.0
	 *
	 * @param array              $form           The Form object.
	 * @param array              $entry          The Entry object.
	 * @param \GF_Field_Repeater $repeater_field Repeater Field object.
	 * @param bool               $is_csv         Return value for CSV file.
	 *
	 * @return array
	 */
	protected function get_repeater_field_value( $form, $entry, $repeater_field, $is_csv = true ) {

		// Initialize value array.
		$value = [];

		// Get items.
		$items = rgar( $entry, $repeater_field->id );

		// If no items were submitted, return.
		if ( empty( $items ) ) {
			return $value;
		}

		// Loop through items, get value.
		foreach ( $items as $item ) {

			// Initialize value for item.
			$val = [];

			/**
			 * Loop through fields.
			 *
			 * @var GF_Field $field
			 */
			foreach ( $repeater_field->fields as $field ) {

				// Get label.
				$label = self::get_field_label( $repeater_field, [ 'id' => $field->id ] );

				if ( is_array( $field->fields ) ) {

					// Get inputs.
					$inputs = $field->get_entry_inputs();

					// Get field value for inputs.
					if ( is_array( $inputs ) ) {

						$field_value = [];
						$field_keys  = array_keys( $item );

						foreach ( $field_keys as $input_id ) {
							if ( is_numeric( $input_id ) && absint( $input_id ) == absint( $field->id ) ) {
								$field_value[ $input_id ] = $item[ $input_id ];
							}
						}

					} else {

						// Get field value.
						$field_value = isset( $item[ $field->id ] ) ? $item[ $field->id ] : '';
						$field_value = [ (string) $field->id => $field_value ];

					}

					$field_value = $this->get_repeater_field_value( $field->fields, $field_value, $field, $is_csv );

				} else {

					$field_value = $this->get_field_value( $form, $item, $field->id, $is_csv );

				}

				// Add to value.
				$val[ $label ] = $field_value;

			}

			// Add to main value array.
			$value[] = $val;

		}

		return $value;

	}

	/**
	 * Determines if a entry has at least one submitted Nested Form field.
	 *
	 * @since 3.0
	 *
	 * @param array $entry The Entry object.
	 *
	 * @return bool
	 */
	protected function has_nested_form_inputs( $entry ) {

		foreach ( $this->fields as $field_meta ) {

			if ( ! $field = GFAPI::get_field( $this->form, $field_meta['id'] ) ) {
				continue;
			}

			if ( $field->type !== 'form' ) {
				continue;
			}

			if ( $field_meta['id'] != $field->id && rgar( $entry, $field->id ) ) {
				return true;
			}

		}

		return false;

	}

	/**
	 * Determines if a entry has at least one submitted entry note.
	 *
	 * @since 3.0
	 *
	 * @param array $entry The Entry object.
	 *
	 * @return array|false
	 */
	protected function has_entry_notes( $entry ) {

		foreach ( $this->fields as $field_meta ) {

			if ( $field_meta['id'] !== 'entry_notes' ) {
				continue;
			}

			if ( $entry_notes = GFFormsModel::get_lead_notes( $entry['id'] ) ) {
				return $entry_notes;
			}

		}

		return false;

	}

	/**
	 * Returns all Child Entries for a field.
	 *
	 * @since 3.0
	 *
	 * @param string|array $entry_ids Child entry IDs.
	 *
	 * @return array
	 */
	protected function get_child_entries( $entry_ids = '' ) {

		if ( ! is_array( $entry_ids ) ) {
			$entry_ids = explode( ',', trim( $entry_ids ) );
		}

		$entries = [];

		foreach ( $entry_ids as $entry_id ) {

			$entry = GFAPI::get_entry( $entry_id );

			if ( ! is_wp_error( $entry ) ) {
				$entries[] = $entry;
			}

		}

		return $entries;

	}

}
