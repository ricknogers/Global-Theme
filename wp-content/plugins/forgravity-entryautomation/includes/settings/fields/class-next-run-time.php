<?php

namespace ForGravity\Entry_Automation\Settings\Fields;

use Gravity_Forms\Gravity_Forms\Settings\Fields\Hidden;

defined( 'ABSPATH' ) || die();

class Next_Run_Time extends Hidden {

	/**
	 * Field type.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	public $type = 'fg_entryautomation_next_run_time';





	// # RENDER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Render field.
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function markup() {

		$format = 'Y-m-d\TH:i:s';

		// Set default start time.
		$this->default_value = fg_entryautomation()->strtotime( '+1 hour', $format, true );

		// If feed has not run, set default start time.
		if ( fg_entryautomation()->get_current_feed_id() ) {

			// Get next scheduled run time.
			$next_run_time = fg_entryautomation()->get_next_run_time( fg_entryautomation()->get_current_feed_id(), $format );

			// If next run time found, set as default value.
			if ( $next_run_time ) {
				$this->default_value = $next_run_time;
			}
		}

		return sprintf(
			'%s%s%s',
			parent::markup(),
			sprintf(
				'<div id="entryautomation-next-run-time"></div><script type="text/javascript">var taskHasRun = %s;</script>',
				wp_json_encode( $this->hasRun )
			),
			$this->get_error_icon()
		);

	}





	// # VALIDATION METHODS --------------------------------------------------------------------------------------------

	/**
	 * Validate selected Next Run Time.
	 *
	 * @since 3.0
	 *
	 * @param string $value Posted field value.
	 */
	public function do_validation( $value ) {

		if ( rgblank( $value ) ) {
			$this->set_error( esc_html__( 'You must set a time for the task to run.', 'forgravity_entryautomation' ) );
			return;
		}

		if ( strtotime( $value ) < current_time( 'timestamp' ) ) {
			$this->set_error( esc_html__( 'Task cannot be run in the past.', 'forgravity_entryautomation' ) );
			return;
		}

	}

}
