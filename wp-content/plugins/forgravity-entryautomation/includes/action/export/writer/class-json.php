<?php

namespace ForGravity\Entry_Automation\Action\Export\Writer;

use ForGravity\Entry_Automation\Action\Export;
use ForGravity\Entry_Automation\Action\Export\Writer;
use ForGravity\Entry_Automation\Task;
use GFAPI;

/**
 * Export entries to JSON file.
 *
 * @since 3.0
 */
class JSON extends Writer {

	/**
	 * JSON constructor.
	 *
	 * @since 3.0
	 *
	 * @param Export $action The Export action object.
	 */
	public function __construct( $action ) {

		parent::__construct( $action );

		$this->file_content = [];

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

		// Loop through export fields.
		foreach ( $this->fields as $field_meta ) {

			// Get field label and value.
			$label = $this->get_field_label( $this->form, $field_meta );
			$value = $this->get_field_value( $this->form, $entry, $field_meta['id'], false );

			/**
			 * Override the field value before it is included in the JSON export.
			 *
			 * @since 1.1.6
			 *
			 * @param string $field_value Value of the field being exported.
			 * @param array  $form        The Form object.
			 * @param string $field_id    The ID of the current field.
			 * @param array  $entry       The Entry object.
			 * @param Task   $task        Entry Automation Task meta.
			 */
			$value = apply_filters( 'fg_entryautomation_export_field_value', $value, $this->form, $field_meta['id'], $entry, $this->action->task );

			// Convert value.
			if ( function_exists( 'mb_convert_encoding' ) ) {

				// Convert array.
				if ( is_array( $value ) ) {
					array_walk_recursive( $value, function( $val ) {
						return mb_convert_encoding( $val, get_option( 'blog_charset' ) );
					} );
				} else {
					$value = mb_convert_encoding( $value, get_option( 'blog_charset' ) );
				}

			}

			// Add entry to JSON array.
			$built_entry[ $label ] = $value;

		}

	}

	/**
	 * Write entries to JSON file.
	 *
	 * @since 3.0
	 *
	 * @param string $file_path  Path to export file.
	 * @param string $write_type Export write type.
	 *
	 * @return bool|int
	 */
	protected function write_to_file( $file_path, $write_type = 'new' ) {

		switch ( $write_type ) {

			case 'append':

				// Get existing file contents.
				$existing_json = file_exists( $file_path ) ? json_decode( file_get_contents( $file_path ) ) : array();

				// Add new entries.
				$new_json = array_merge( $existing_json, $this->file_content );

				// Write to file.
				return file_put_contents( $file_path, json_encode( $new_json ) );

			case 'prepend':

				// Get existing file contents.
				$existing_json = file_exists( $file_path ) ? json_decode( file_get_contents( $file_path ) ) : array();

				// Add new entries.
				$new_json = array_merge( $this->file_content, $existing_json );

				// Write to file.
				return file_put_contents( $file_path, json_encode( $new_json ) );

			default:
				return file_put_contents( $file_path, json_encode( $this->file_content ) );

		}

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

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
	public function get_field_value( $form, $entry, $field_id, $is_csv = true ) {

		// Get field.
		$field = GFAPI::get_field( $form, $field_id );

		// If field is a list field, return entry value.
		if ( is_a( $field, '\GF_Field_List' ) ) {
			$field_value = rgar( $entry, $field_id );

			return maybe_unserialize( $field_value );
		}

		return parent::get_field_value( $form, $entry, $field_id, $is_csv );

	}


}
