<?php

namespace ForGravity\Entry_Automation\Settings\Fields;

use ForGravity\Entry_Automation\Action\Export;
use Gravity_Forms\Gravity_Forms\Settings\Fields\Hidden;

defined( 'ABSPATH' ) || die();

class Export_Fields extends Hidden {

	/**
	 * Field type.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	public $type = 'fg_entryautomation_export_fields';





	// # RENDER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Render field.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function markup() {

		return sprintf(
			'%s%s%s',
			$this->get_error_icon(),
			parent::markup(),
			'<div id="exportFieldsContainer"></div>' // Add container for DateTimePicker.
		);

	}





	// # VALIDATION METHODS --------------------------------------------------------------------------------------------

	/**
	 * Validate selected Export Fields.
	 *
	 * @since 3.0
	 *
	 * @param array|bool|string $value Posted field value.
	 */
	public function do_validation( $value ) {

		// Initialize selected checkboxes count.
		$selected = 0;

		// Loop through export fields.
		foreach ( $value as $export_field ) {

			// If choice does not have a valid value, exit.
			if ( ! is_bool( $export_field['enabled'] ) ) {
				$this->set_error( esc_html__( 'Invalid value.', 'gravityforms' ) );
				return;
			}

			// If choice is selected, increase selected count.
			if ( $export_field['enabled'] === true ) {
				$selected++;
			}

		}

		// If this field is required and no choices were selected, set error.
		if ( $this->required && $selected < 1 ) {
			$this->set_error( rgobj( $this, 'error_message' ) );
		}

	}





	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Update existing choices with newly added/deleted form fields.
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public function get_value() {

		$value = parent::get_value();

		// Get field value.
		if ( $value ) {
			return Export::update_export_fields( $value );
		} else {
			return Export::get_default_export_fields();
		}

	}

}
