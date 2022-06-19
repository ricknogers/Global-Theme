<?php
/**
 * XLSX Writer Class for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation\Action\Export\Writer;

use ForGravity\Entry_Automation\Action\Export;
use ForGravity\Entry_Automation\Action\Export\Writer;
use ForGravity\Entry_Automation\Entries;
use ForGravity\Entry_Automation\Task;
use GFAPI;
use GFExport;
use GFFormsModel;

// Include PHPSpreadsheet.
if ( ! class_exists( '\PhpOffice\PhpSpreadsheet\Spreadsheet' ) ) {
	require_once fg_entryautomation()->get_includes_path() . '/vendor/autoload.php';
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;

/**
 * Export entries to Excel file.
 *
 * @since 3.0
 */
class XLSX extends Writer {

	/**
	 * The spreadsheet.
	 *
	 * @since 3.0
	 *
	 * @var Spreadsheet $spreadsheet
	 */
	protected $spreadsheet;

	/**
	 * The active worksheet.
	 *
	 * @since 3.0
	 *
	 * @var Worksheet
	 */
	protected $active_sheet;

	/**
	 * The highest row of the active worksheet.
	 *
	 * @var int
	 */
	protected $highest_row;

	/**
	 * The field rows.
	 *
	 * @since 3.0
	 *
	 * @var array $field_rows
	 */
	protected $field_rows;

	/**
	 * Excel constructor.
	 *
	 * @since 3.0
	 *
	 * @param Export $action The Export action object.
	 */
	public function __construct( $action ) {

		parent::__construct( $action );

		// Set the highest row.
		$this->highest_row = 1;

		$file_path = $this->action->file_path;

		if ( file_exists( $file_path ) ) {

			$this->spreadsheet  = IOFactory::load( $file_path );
			$this->active_sheet = $this->spreadsheet->getActiveSheet();

			switch ( $this->action->task->meta['exportWriteType'] ) {

				case 'prepend':

					// Insert new rows in the beginning of the worksheet.
					try {
						$this->active_sheet->insertNewRowBefore( 2, $this->found_entries );
					} catch ( Exception $e ) {
						fg_entryautomation()->log_error( 'Unable to add rows to current worksheet: ' . $e->getMessage() );
					}

					break;

				case 'append':

					// Change the highest row to the worksheet highest row.
					$this->highest_row = $this->active_sheet->getHighestRow();

					break;

			}

		} else {

			// Initiate the Spreadsheet object.
			$this->spreadsheet = new Spreadsheet();

			// Set active worksheet.
			$this->active_sheet = $this->spreadsheet->getActiveSheet();

		}

		// Get field row counts.
		$this->field_rows = Entries::get_field_row_count( $this->form, wp_list_pluck( $this->fields, 'id' ), $this->found_entries, rgar( $action->task->meta, 'entryType' ) );

	}

	/**
	 * Get header for Excel export file.
	 *
	 * @since  3.0
	 *
	 * @return void
	 */
	protected function build_header() {

		$file_path = $this->action->file_path;

		if ( file_exists( $file_path ) ) {
			return;
		}

		$form       = $this->form;
		$fields     = $this->fields;
		$field_rows = $this->field_rows;

		// Loop through export fields.
		$column = 0;
		foreach ( $fields as $field ) {
			$column++;

			// Get field label.
			$label = $this->get_field_label( $form, $field );

			// Add columns for entry notes.
			if ( $field['id'] === 'entry_notes' ) {

				$column--;
				$this->build_entry_notes_header( $label, $column );

				continue;

			}

			// Get subrow count.
			$subrow_count = isset( $field_rows[ $field['id'] ] ) ? intval( $field_rows[ $field['id'] ] ) : 0;

			// Add label to header.
			if ( $subrow_count === 0 ) {
				$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, $label );
			} else {
				$column--;
				for ( $i = 1; $i <= $subrow_count; $i++ ) {
					$column++;
					$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, $label . ' ' . $i );
				}
			}

		}

	}

	/**
	 * Build the formatted entry.
	 *
	 * @since 3.0
	 *
	 * @param string $built_entry       The Excel entry.
	 * @param array  $entry             The original entry data.
	 * @param int    $entries_processed The entries has been processed.
	 */
	protected function build_entry( &$built_entry, $entry, $entries_processed ) {

		$task = $this->action->task;

		// Loop through export fields.
		$column = 0;
		$this->highest_row++;

		foreach ( $this->fields as $field_meta ) {
			$column++;

			// Added 3 empty columns for entry notes.
			if ( $field_meta['id'] === 'entry_notes' ) {

				$column--;
				for ( $i = 0; $i < 3; $i ++ ) {
					$column++;
					$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, '' );
				}

				continue;

			}

			// Get field value.
			$field_value = $this->get_field_value( $this->form, $entry, $field_meta['id'] );

			/**
			 * Override the field value before it is included in the Excel export.
			 *
			 * @since 1.1.6
			 *
			 * @param string $field_value Value of the field being exported.
			 * @param array  $form        The Form object.
			 * @param string $field_id    The ID of the current field.
			 * @param array  $entry       The Entry object.
			 * @param Task   $task        Entry Automation Task meta.
			 */
			$field_value = apply_filters( 'fg_entryautomation_export_field_value', $field_value, $this->form, $field_meta['id'], $entry, $task );

			if ( isset( $this->field_rows[ $field_meta['id'] ] ) ) {

				$subrows = 0;

				if ( is_array( $field_value ) ) {
					$subrows = count( $field_value );
					// Loop through List filed rows.
					$column--;
					foreach ( $field_value as $row ) {
						$column++;

						// Convert row to string.
						$row_values = array_values( $row );
						$row_string = implode( '|', $row_values );

						// Prevent Excel formulas.
						if ( 0 === strpos( $row_string, '=' ) ) {
							$row_string = "'" . $row_string;
						}

						$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, $row_string );

					}
				}

				// Fill in missing subrow columns.
				$missing_subrows = intval( $this->field_rows[ $field_meta['id'] ] ) - $subrows;

				if ( $missing_subrows > 0 ) {
					$column--;
					for ( $i = 0; $i < $missing_subrows; $i++ ) {
						$column++;
						$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, '' );
					}
				}

			} else {

				// Add field value to export string.
				$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, $this->format_field_value( $field_value ) );

			}

		}

		// Build child entries for Nested Forms.
		if ( $this->has_nested_form_inputs( $entry ) ) {
			$this->build_child_entries( $entry );
		}

		// Build subrows to list entry notes.
		if ( $entry_notes = $this->has_entry_notes( $entry ) ) {
			$this->build_entry_notes( $entry, $entry_notes );
		}

	}

	/**
	 * Build the formatted Nested Forms child entries.
	 *
	 * @since 3.0
	 *
	 * @param array $entry  The original entry data.
	 */
	private function build_child_entries( $entry ) {

		$nested_form_fields = GFAPI::get_fields_by_type( $this->form, [ 'form' ] );

		foreach ( $nested_form_fields as $nested_form_field ) {

			$child_entries = $this->get_child_entries( rgar( $entry, $nested_form_field->id ) );

			if ( empty( $child_entries ) ) {
				continue;
			}

			$nested_form = GFAPI::get_form( $nested_form_field->gpnfForm );

			if ( ! $nested_form ) {
				continue;
			}

			foreach ( $child_entries as $child_entry ) {

				$column = 0;
				$this->highest_row ++;

				foreach ( $this->fields as $field_meta ) {
					$column++;

					if ( intval( $field_meta['id'] ) == $nested_form_field->id && $field_meta['id'] != $nested_form_field->id ) {

						$exploded_field_id   = explode( '.', $field_meta['id'] );
						$child_form_field_id = array_pop( $exploded_field_id );

						$field_value = $this->get_field_value( $nested_form, $child_entry, $child_form_field_id );
						$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, $this->format_field_value( $field_value ) );

					} else {

						$column--;
						$this->add_empty_columns( $field_meta['id'], $column );

					}

				}

			}

		}

	}

	/**
	 * Build custom header columns for entry notes.
	 *
	 * @since 3.0
	 *
	 * @param string $label  The field label.
	 * @param int    $column The column.
	 */
	private function build_entry_notes_header( $label, &$column ) {

		$columns = array(
			sprintf( '%s / %s', $label, esc_html__( 'Author', 'forgravity_entryautomation' ) ),
			sprintf( '%s / %s', $label, esc_html__( 'Date', 'forgravity_entryautomation' ) ),
			sprintf( '%s / %s', $label, esc_html__( 'Content', 'forgravity_entryautomation' ) ),
		);

		foreach ( $columns as $value ) {
			$column++;
			$this->active_sheet->setCellValueByColumnAndRow( $column, 1, $value );
		}

	}

	/**
	 * Build the entry notes.
	 *
	 * @since 3.0
	 *
	 * @param array $entry       The original entry data.
	 * @param array $entry_notes The entry notes.
	 */
	private function build_entry_notes( $entry, $entry_notes ) {

		$columns = [ 'user_name', 'date_created', 'value' ];

		foreach ( $entry_notes as $entry_note ) {

			if ( empty( $entry_note->value ) ) {
				continue;
			}

			$column = 0;
			$this->highest_row++;

			foreach ( $this->fields as $field_meta ) {
				$column++;

				if ( $field_meta['id'] === 'entry_notes' ) {

					$column--;
					foreach ( $columns as $value ) {
						$column++;
						$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, $this->format_field_value( $entry_note->{$value} ) );

					}

				} else {

					$column--;
					$this->add_empty_columns( $field_meta['id'], $column );

				}

			}

		}

	}

	/**
	 * Write entries to Excel file.
	 *
	 * @since 3.0
	 *
	 * @param string $file_path  Path to export file.
	 * @param string $write_type Export write type.
	 *
	 * @return void
	 */
	protected function write_to_file( $file_path, $write_type = 'new' ) {

		try {
			$writer = IOFactory::createWriter( $this->spreadsheet, 'Xlsx' );
			$writer->save( $file_path );
		} catch ( Exception $e ) {
			fg_entryautomation()->log_error( 'Unable to write to spreadsheet: ' . $e->getMessage() );
		}

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Returns a field value into a compatible format.
	 *
	 * @since 3.0
	 *
	 * @param string|array $value Field value.
	 *
	 * @return string
	 */
	private function format_field_value( $value = '' ) {

		// If field value is an array, convert it to a string.
		if ( is_array( $value ) ) {
			$value = implode( '|', $value );
		}

		// Prevent Excel formulas.
		if ( 0 === strpos( $value, '=' ) ) {
			$value = "'" . $value;
		}

		// Return the field value.
		return $value;

	}

	/**
	 * Get header for Excel export file.
	 *
	 * @since  3.0
	 *
	 * @param array $form       The form object.
	 * @param array $field_meta Fields being exported.
	 *
	 * @return string
	 */
	protected function get_field_label( $form, $field_meta ) {

		// Get label.
		$label = parent::get_field_label( $form, $field_meta );

		// Get field.
		$field = GFFormsModel::get_field( $form, $field_meta['id'] );

		/**
		 * Override the field header in the entries export.
		 *
		 * @since 1.0
		 *
		 * @param string $label The header being used for the current field. Defaults to the field label (input label for multi-input fields).
		 * @param array  $form  The current form.
		 * @param array  $field The current field.
		 */
		$label = gf_apply_filters( [
			'gform_entries_field_header_pre_export',
			$form['id'],
			$field_meta['id'],
		], $label, $form, $field );

		// Strip quotes from label.
		$label = str_replace( '"', '""', $label );

		// Prevent Excel formulas.
		if ( 0 === strpos( $label, '=' ) ) {
			$label = "'" . $label;
		}

		return $label;

	}

	/**
	 * Return the child entry IDs for Nested Forms fields.
	 *
	 * @since  3.0
	 *
	 * @param array  $form     The Form object.
	 * @param array  $entry    The Entry object.
	 * @param string $field_id The field ID to return.
	 * @param bool   $is_csv   Return value for Excel file.
	 *
	 * @return string
	 */
	public function get_field_value( $form, $entry, $field_id, $is_csv = true ) {

		// Get field.
		$field = GFAPI::get_field( $form, $field_id );

		// If field is a Nested Forms field, return entry value.
		if ( ! $field || $field->type !== 'form' ) {
			return parent::get_field_value( $form, $entry, $field_id, $is_csv );
		}

		return rgar( $entry, $field_id );

	}

	/**
	 * Get values for Repeater field.
	 *
	 * @since  3.0
	 *
	 * @param array              $form           The Form object.
	 * @param array              $entry          The Entry object.
	 * @param \GF_Field_Repeater $repeater_field Repeater Field object.
	 * @param bool               $is_csv         Return value for Excel file.
	 *
	 * @return array
	 */
	protected function get_repeater_field_value( $form, $entry, $repeater_field, $is_csv = true ) {

		return $repeater_field->get_value_export( $entry, '', false, $is_csv );

	}

	/**
	 * Add empty columns.
	 *
	 * @since 3.0
	 *
	 * @param string $field_id The field ID.
	 * @param int    $column   The column.
	 */
	private function add_empty_columns( $field_id, &$column ) {

		// Add empty column for each empty field.
		$counter = rgar( $this->field_rows, $field_id, 1 );
		while ( $counter > 0 ) {
			$column++;
			$this->active_sheet->setCellValueByColumnAndRow( $column, $this->highest_row, '' );
			$counter--;
		}

	}

}
