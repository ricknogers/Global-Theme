<?php

namespace ForGravity\Entry_Automation\Settings\Fields;

use Gravity_Forms\Gravity_Forms\Settings\Settings;
use Gravity_Forms\Gravity_Forms\Settings\Fields;
use Gravity_Forms\Gravity_Forms\Settings\Fields\Base;

defined( 'ABSPATH' ) || die();

class Date_Range extends Base {

	/**
	 * Field type.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	public $type = 'fg_entryautomation_date_range';

	/**
	 * Child inputs.
	 *
	 * @since 3.0
	 *
	 * @var Base[]
	 */
	public $inputs = [];

	/**
	 * Initialize Field Select field.
	 *
	 * @since 3.0
	 *
	 * @param array    $props    Field properties.
	 * @param Settings $settings Settings instance.
	 */
	public function __construct( $props, $settings ) {

		parent::__construct( $props, $settings );

		// Prepare inputs.
		$this->inputs = [
			'start' => [
				'type' => 'hidden',
				'name' => $this->name . '[start]',
			],
			'end'   => [
				'type' => 'hidden',
				'name' => $this->name . '[end]',
			],
		];

		// Prepare input fields.
		foreach ( $this->inputs as &$input ) {
			$input = Fields::create( $input, $this->settings );
		}

	}





	// # RENDER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Render field.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function markup() {

		$html = '';
		foreach ( $this->inputs as $input ) {
			$html .= $input->markup();
		}
		$html .= '<div id="entryautomation-date-range"></div>';
		$html .= $this->get_error_icon();

		return $html;

	}

	/**
	 * Validate posted field value.
	 *
	 * @since 3.0
	 *
	 * @param array|bool|string $value Posted field value.
	 */
	public function do_validation( $value ) {

		parent::do_validation( $value );

		// Validate start date.
		if ( rgobj( $this, 'start_date' ) ) {

			// Get field value.
			$start_date = rgar( $value, 'start' );

			// If start date is defined, validate.
			if ( $start_date ) {

				// Convert start date to time.
				$start_date = fg_entryautomation()->strtotime( $start_date );

				// If time did not convert correctly, set field error.
				if ( ! $start_date ) {
					fg_entryautomation()->set_field_error( $this->inputs['start'], esc_html__( 'You must use a valid date string.', 'forgravity_entryautomation' ) );
				}

			}

		}

		// Validate end date.
		if ( rgobj( $this, 'end_date' ) ) {

			// Get field value.
			$end_date = rgar( $value, 'end' );

			// If end date is defined, validate.
			if ( $end_date ) {

				// Convert end date to time.
				$end_date = fg_entryautomation()->strtotime( $end_date );

				// If time did not convert correctly, set field error.
				if ( ! $end_date ) {
					fg_entryautomation()->set_field_error( $this->inputs['end'], esc_html__( 'You must use a valid date string.', 'forgravity_entryautomation' ) );
				}

			}

		}

	}

}
