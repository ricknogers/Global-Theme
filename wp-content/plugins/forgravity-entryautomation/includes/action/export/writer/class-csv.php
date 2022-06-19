<?php
/**
 * CSV Writer Class for Entry Automation
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

/**
 * Export entries to CSV file.
 *
 * @since 3.0
 */
class CSV extends Writer {

	/**
	 * The field rows.
	 *
	 * @since 3.0
	 *
	 * @var array $field_rows
	 */
	protected $field_rows;

	/**
	 * The CSV separator.
	 *
	 * @since 3.0
	 *
	 * @var string $separator
	 */
	protected $separator;

	/**
	 * CSV constructor.
	 *
	 * @since 3.0
	 *
	 * @param Export $action The Export action object.
	 */
	public function __construct( $action ) {

		parent::__construct( $action );

		// Get field row counts.
		$this->field_rows = Entries::get_field_row_count( $this->form, wp_list_pluck( $this->fields, 'id' ), $this->found_entries, rgar( $action->task->meta, 'entryType' ) );

		// Define the separator.
		$this->separator = gf_apply_filters( [ 'gform_export_separator', $this->form['id'] ], ',', $this->form['id'] );

	}

	/**
	 * Get header for CSV export file.
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
		$separator  = $this->separator;
		$field_rows = $this->field_rows;

		// If exporting to a new file, add header.
		// Initialize header string.
		$header = '';

		// Loop through export fields.
		foreach ( $fields as $field ) {

			// Get field label.
			$label = $this->get_field_label( $form, $field );

			// Add columns for entry notes.
			if ( $field['id'] === 'entry_notes' ) {

				$header .= $this->build_entry_notes_header( $label );

				continue;

			}

			// Get subrow count.
			$subrow_count = isset( $field_rows[ $field['id'] ] ) ? intval( $field_rows[ $field['id'] ] ) : 0;

			// Add label to header.
			if ( $subrow_count === 0 ) {
				$header .= '"' . str_replace( '"', '""', $label ) . '"' . $separator;
			} else {
				for ( $i = 1; $i <= $subrow_count; $i++ ) {
					$header .= '"' . str_replace( '"', '""', $label ) . ' ' . $i . '"' . $separator;
				}
			}

		}

		// Remove last separator from header.
		$header = substr( $header, 0, strlen( $header ) - strlen( $separator ) );

		// Add line break.
		$header .= PHP_EOL;

		/**
		 * Allows the BOM character to be excluded from the beginning of entry export files.
		 *
		 * @since 2.0
		 *
		 * @param bool  $include_bom Whether or not to include the BOM characters. Defaults to true.
		 * @param array $form        The Form Object.
		 * @param Task  $task        Entry Automation Task object.
		 */
		$include_bom = apply_filters( 'gform_include_bom_export_entries', true, $form );
		$include_bom = apply_filters( 'fg_entryautomation_export_include_bom', $include_bom, $form, $this->action->task );

		// Adding BOM marker for UTF-8.
		if ( $include_bom ) {
			$header = chr( 239 ) . chr( 187 ) . chr( 191 ) . $header;
		}

		// Write header to file.
		file_put_contents( $file_path, $header, FILE_APPEND );

	}

	/**
	 * Build the formatted entry.
	 *
	 * @since 3.0
	 *
	 * @param string $built_entry       The CSV entry.
	 * @param array  $entry             The original entry data.
	 * @param int    $entries_processed The entries has been processed.
	 */
	protected function build_entry( &$built_entry, $entry, $entries_processed ) {

		$task = $this->action->task;

		// Loop through export fields.
		foreach ( $this->fields as $field_meta ) {

			// Added 3 empty columns for entry notes.
			if ( $field_meta['id'] === 'entry_notes' ) {

				for ( $i = 0; $i < 3; $i ++ ) {
					$built_entry .= '""' . $this->separator;
				}

				continue;

			}

			// Get field value.
			$field_value = $this->get_field_value( $this->form, $entry, $field_meta['id'] );

			/**
			 * Override the field value before it is included in the CSV export.
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
					// Loop through List field rows.
					foreach ( $field_value as $row ) {

						// Convert row to string.
						$row_values = array_values( $row );
						$row_string = implode( '|', $row_values );

						// Prevent Excel formulas.
						if ( 0 === strpos( $row_string, '=' ) ) {
							$row_string = "'" . $row_string;
						}

						$built_entry .= '"' . str_replace( '"', '""', $row_string ) . '"' . $this->separator;

					}
				}

				// Fill in missing subrow columns.
				$missing_subrows = intval( $this->field_rows[ $field_meta['id'] ] ) - $subrows;

				for ( $i = 0; $i < $missing_subrows; $i++ ) {
					$built_entry .= '""' . $this->separator;
				}

			} else {

				// Add field value to export string.
				$built_entry .= $this->format_field_value( $field_value );

			}

		}

		// Remove last separator from line.
		$built_entry = substr( $built_entry, 0, strlen( $built_entry ) - strlen( $this->separator ) );

		// Add line break.
		$built_entry .= PHP_EOL;

		// Build child entries for Nested Forms.
		if ( $this->has_nested_form_inputs( $entry ) ) {
			$this->build_child_entries( $built_entry, $entry );
		}

		// Build subrows to list entry notes.
		if ( $entry_notes = $this->has_entry_notes( $entry ) ) {
			$this->build_entry_notes( $built_entry, $entry, $entry_notes );
		}

	}

	/**
	 * Build the formatted Nested Forms child entries.
	 *
	 * @since 3.0
	 *
	 * @param string $built_entry The CSV entry.
	 * @param array  $entry       The original entry data.
	 */
	private function build_child_entries( &$built_entry, $entry ) {

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

				$line            = '';
				$has_child_field = false;

				foreach ( $this->fields as $field_meta ) {

					if ( intval( $field_meta['id'] ) == $nested_form_field->id && $field_meta['id'] != $nested_form_field->id ) {

						$has_child_field     = true;
						$exploded_field_id   = explode( '.', $field_meta['id'] );
						$child_form_field_id = array_pop( $exploded_field_id );

						$field_value = $this->get_field_value( $nested_form, $child_entry, $child_form_field_id );
						$line       .= $this->format_field_value( $field_value );

					} else {

						$line = $this->add_empty_columns( $line, $field_meta['id'] );

					}

				}

				if ( ! $has_child_field ) {
					continue;
				}

				// Remove last separator from line.
				$built_entry .= substr( $line, 0, strlen( $line ) - strlen( $this->separator ) );

				// Add line break.
				$built_entry .= PHP_EOL;

			}

		}

	}

	/**
	 * Build custom header columns for entry notes.
	 *
	 * @since 3.0
	 *
	 * @param string $label The field label.
	 *
	 * @return string
	 */
	private function build_entry_notes_header( $label ) {

		$header = '';

		$columns = array(
			sprintf( '%s / %s', $label, esc_html__( 'Author', 'forgravity_entryautomation' ) ),
			sprintf( '%s / %s', $label, esc_html__( 'Date', 'forgravity_entryautomation' ) ),
			sprintf( '%s / %s', $label, esc_html__( 'Content', 'forgravity_entryautomation' ) ),
		);

		foreach ( $columns as $column ) {
			$header .= '"' . str_replace( '"', '""', $column ) . '"' . $this->separator;
		}

		return $header;

	}

	/**
	 * Build the entry notes.
	 *
	 * @since 3.0
	 *
	 * @param string $built_entry The CSV entry.
	 * @param array  $entry       The original entry data.
	 * @param array  $entry_notes The entry notes.
	 */
	private function build_entry_notes( &$built_entry, $entry, $entry_notes ) {

		$columns = [ 'user_name', 'date_created', 'value' ];

		foreach ( $entry_notes as $entry_note ) {

			if ( empty( $entry_note->value ) ) {
				continue;
			}

			$line = '';

			foreach ( $this->fields as $field_meta ) {

				if ( $field_meta['id'] === 'entry_notes' ) {

					foreach ( $columns as $column ) {

						$line .= $this->format_field_value( $entry_note->{$column} );

					}

				} else {

					$line = $this->add_empty_columns( $line, $field_meta['id'] );

				}

			}

			// Remove last separator from line.
			$built_entry .= substr( $line, 0, strlen( $line ) - strlen( $this->separator ) );

			// Add line break.
			$built_entry .= PHP_EOL;

		}

	}

	/**
	 * Write entries to CSV file.
	 *
	 * @since 3.0
	 *
	 * @param string $file_path  Path to export file.
	 * @param string $write_type Export write type.
	 *
	 * @return bool|int|void
	 */
	protected function write_to_file( $file_path, $write_type = 'new' ) {

		// Convert lines.
		if ( function_exists( 'mb_convert_encoding' ) ) {
			$this->file_content = mb_convert_encoding( $this->file_content, get_option( 'blog_charset' ) );
		}

		/**
		 * Filter the CSV entry export lines before they are written to the file.
		 *
		 * @since 1.3.5
		 *
		 * @param string $file_content     Lines to be included in .csv export
		 * @param Task   $task      Entry Automation Task meta.
		 * @param string $file_path File name of export file.
		 */
		$this->file_content = apply_filters( 'fg_entryautomation_export_lines', $this->file_content, $this->action->task, $file_path );

		switch ( $write_type ) {

			case 'prepend':

				// Get existing file contents.
				$existing_csv = explode( PHP_EOL, file_get_contents( $file_path ) );

				// Explode lines.
				$this->file_content = explode( PHP_EOL, $this->file_content );

				// Get header.
				$csv_header = array_shift( $existing_csv );

				// Merge lines.
				$new_csv = array_filter( array_merge( [ $csv_header ], $this->file_content, $existing_csv ) );
				$new_csv = implode( PHP_EOL, $new_csv );
				$new_csv .= PHP_EOL;

				// Write to file.
				return file_put_contents( $file_path, $new_csv );

			default:
				return file_put_contents( $file_path, $this->file_content, FILE_APPEND );

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

		// Add field value to export string.
		return '"' . str_replace( '"', '""', $value ) . '"' . $this->separator;

	}

	/**
	 * Get header for CSV export file.
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
	 * @param bool   $is_csv   Return value for CSV file.
	 *
	 * @return string
	 */
	public function get_field_value( $form, $entry, $field_id, $is_csv = true ) {

		// Get field.
		$field = GFAPI::get_field( $form, $field_id );

		// If field is Fillable PDFs, return as comma separated list.
		if ( function_exists( 'fg_fillablepdfs' ) && $field_id === fg_fillablepdfs()->get_slug() ) {
			$value = parent::get_field_value( $form, $entry, $field_id, $is_csv );
			return implode( ', ', $value );
		}

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
	 * @param bool               $is_csv         Return value for CSV file.
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
	 * @param string $line     The line content.
	 * @param string $field_id The field ID.
	 *
	 * @return string
	 */
	private function add_empty_columns( $line, $field_id ) {

		// Add empty column for each empty field.
		$counter = rgar( $this->field_rows, $field_id, 1 );
		while ( $counter > 0 ) {
			$line .= $this->format_field_value();
			$counter--;
		}

		return $line;

	}

}
