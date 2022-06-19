<?php
/**
 * Base task Class for Entry Automation
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation;

use ForGravity\Entry_Automation\Task\Merge_Tags;
use GFAPI;

use ArrayAccess;
use Exception;

class_exists( '\GFForms' ) || die();

/**
 * Base Task class.
 */
class Task implements ArrayAccess {

	/**
	 * Task ID.
	 *
	 * @var int
	 */
	public $id = 0;

	/**
	 * Task form ID.
	 *
	 * @var int
	 */
	public $form_id = 0;

	/**
	 * Entry ID to use for task.
	 *
	 * @var bool
	 */
	public $entry_id = false;

	/**
	 * Number of entries found for the Task.
	 *
	 * @var int
	 */
	public $found_entries = 0;

	/**
	 * Number of entries processed by the Task.
	 *
	 * @var int
	 */
	public $entries_processed = 0;

	/**
	 * If Task is enabled.
	 *
	 * @var bool
	 */
	public $is_active = true;

	/**
	 * Task type: manual, scheduled or submission.
	 *
	 * @var string
	 */
	public $type = 'scheduled';

	/**
	 * Task Action class.
	 *
	 * @var bool|Action
	 */
	public $action = false;

	/**
	 * Task metadata.
	 *
	 * @var array
	 */
	public $meta = [];

	/**
	 * Task search criteria,
	 *
	 * @var array
	 */
	private $search_criteria = [];

	/**
	 * The merge tags.
	 *
	 * @since unknown.
	 *
	 * @var Merge_Tags
	 */
	public $merge_tags;

	/**
	 * Initialize the object.
	 *
	 * @since unknown.
	 *
	 * @param array $params The accepted params.
	 */
	public function __construct( $params = [] ) {

		// Get object properties.
		$props = array_keys( get_object_vars( $this ) );

		// Loop through object properties.
		foreach ( $props as $prop ) {

			// If task property exists in args, add to task object.
			if ( isset( $params[ $prop ] ) ) {
				$this->{$prop} = $params[ $prop ];
			}

			// Initialize Action object.
			if ( 'meta' === $prop && rgar( $this->meta, 'action' ) ) {
				$this->action = Action::get_action_by_name( $this->meta['action'], $this );
			}

			// Set task type.
			if ( 'meta' === $prop && rgar( $this->meta, 'type' ) ) {
				$this->type = $this->meta['type'];
			}

		}

		$this->merge_tags = new Merge_Tags( $this );

	}





	// # GET TASK ------------------------------------------------------------------------------------------------------

	/**
	 * Get Task object.
	 *
	 * @param int $task_id Task ID.
	 *
	 * @return Task
	 * @throws Exception The exception.
	 */
	public static function get( $task_id ) {

		// Get feed.
		$feed = fg_entryautomation()->get_feed( $task_id );

		// If task was not found, throw exception.
		if ( ! $feed ) {
			throw new Exception( 'Unable to find task #' . $task_id );
		}

		// Set default type.
		if ( ! rgars( $feed, 'meta/type' ) ) {
			$feed['meta']['type'] = 'scheduled';
		}

		// Prepare params.
		$params = [
			'id'        => $feed['id'],
			'form_id'   => $feed['form_id'],
			'is_active' => $feed['is_active'],
			'meta'      => $feed['meta'],
		];

		return new self( $params );

	}





	// # RUN TASK ------------------------------------------------------------------------------------------------------

	/**
	 * Run task.
	 *
	 * @since 2.0
	 * @since 3.0 Removed the $task and $form object that we passed to the Action::run() method.
	 *
	 * @param bool $set_last_run_time Store the task run time.
	 * @param bool $run_if_inactive   Run task if set as inactive.
	 *
	 * @return bool
	 */
	public function run( $set_last_run_time = true, $run_if_inactive = false ) {

		$run_time = $this->get_current_run_time();

		// Prepare next run time.
		$next_run_time = 'scheduled' === $this->type && $set_last_run_time ? $this->prepare_next_run_time( $run_time ) : false;

		// If task is deactivated, exit.
		if ( ! $this->is_active && ! $run_if_inactive ) {

			// Log that we are skipping processing.
			fg_entryautomation()->log_debug( __METHOD__ . '(): Skipping ' . $this->action->get_name() . ' process for task #' . $this->id . ' because task is deactivated.' );

			// Schedule next run.
			if ( $next_run_time ) {
				Scheduler::schedule_task( $this->id, $this->form_id, $next_run_time );
			}

			return false;

		}

		// Log that we are beginning to run the task.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Starting ' . $this->action->get_name() . ' process for task #' . $this->id . ' on form #' . $this->form_id );

		// Get form.
		$form = GFAPI::get_form( $this->form_id );

		// If form was not found, exit.
		if ( ! $form ) {

			// Log that form could not be found.
			fg_entryautomation()->log_error( __METHOD__ . '(): Not running task #' . $this->id . ' because form #' . $this->form_id . ' could not be found.' );

			return false;

		}

		// If form is trashed, exit.
		if ( $form['is_trash'] ) {

			// Log that we are skipping processing.
			fg_entryautomation()->log_error( __METHOD__ . '(): Not running task #' . $this->id . ' because form #' . $this->form_id . ' has been trashed.' );

			// Schedule next run.
			if ( $next_run_time ) {
				Scheduler::schedule_task( $this->id, $this->form_id, $next_run_time );
			}

			return false;

		}

		// Get search criteria for task.
		$search_criteria = $this->get_search_criteria();

		// Log the search criteria.
		fg_entryautomation()->log_debug( __METHOD__ . '(): Search criteria for task #' . $this->id . ': ' . print_r( $search_criteria, true ) );

		// Get entry found for search criteria.
		$args                = [
			'form_id'         => $this->form_id,
			'search_criteria' => $search_criteria,
		];
		$this->found_entries = $this->action->entries->get_total_count( $args, rgar( $this->meta, 'entryType' ) );

		/**
		 * Disable the task from not running if no entries matched the search criteria.
		 *
		 * @since 1.3.6
		 *
		 * @param bool $disable_task_skipping Disable task skipping
		 * @param Task $task                  The current Task object.
		 */
		$disable_task_skipping = gf_apply_filters( [
			'fg_entryautomation_disable_task_skipping',
			$this->form_id,
			$this->id,
		], false, $this );

		// If no entries were found, exit.
		if ( ! $this->found_entries && ! $disable_task_skipping ) {

			// Log that no entries were found.
			fg_entryautomation()->log_debug( __METHOD__ . '(): Not running task #' . $this->id . ' because no entries were found matching the search criteria.' );

			// Set last run time.
			if ( $set_last_run_time ) {
				update_option( fg_entryautomation()->get_slug() . '_last_run_time_' . $this->id, $run_time );
			}

			// Schedule next run.
			if ( $next_run_time ) {
				Scheduler::schedule_task( $this->id, $this->form_id, $next_run_time );
			}

			return false;

		}

		// Run action.
		$response = $this->action->run();

		// Set last run time.
		if ( $set_last_run_time ) {
			update_option( fg_entryautomation()->get_slug() . '_last_run_time_' . $this->id, $run_time );
		}

		// Schedule next run.
		if ( $next_run_time ) {
			fg_entryautomation()->log_debug( __METHOD__ . '(): Scheduling next run time for task #' . $this->id . ': ' . fg_entryautomation()->strtotime( $next_run_time, Date::FORMAT_DATETIME_NO_SECONDS ) );
			Scheduler::schedule_task( $this->id, $this->form_id, $next_run_time );
		}

		return $response;

	}

	/**
	 * Get search criteria for task.
	 *
	 * @since  2.0
	 *
	 * @return array
	 */
	public function get_search_criteria() {

		// If search criteria has already been set, return it.
		if ( ! empty( $this->search_criteria ) ) {
			return $this->search_criteria;
		}

		// Get form.
		$form = GFAPI::get_form( $this->form_id );

		// If an entry ID is set, use that as the search criteria.
		if ( $this->entry_id ) {

			// Initialize search criteria.
			$search_criteria = [
				'field_filters' => [
					[
						'key'   => 'id',
						'value' => $this->entry_id,
					],
				],
			];

		} else {

			// Initialize search criteria.
			$search_criteria = [ 'target' => rgar( $this->meta, 'target', 'custom' ) ];

			// Set the date source.
			$search_criteria['date_field'] = rgar( $this->meta, 'dateField', 'date_created' );

			// Set start time.
			if ( $search_criteria['target'] === 'custom' && rgars( $this->meta, 'dateRange/start' ) ) {

				// Add start time to search criteria.
				$search_criteria['start_date'] = fg_entryautomation()->strtotime( $this->meta['dateRange']['start'], Date::FORMAT_DATETIME );

			} elseif ( $search_criteria['target'] !== 'all' ) {

				if ( $search_criteria['target'] === 'since_last_run' ) {

					// Get the last run time to set it as the start date.
					$start_date = $this->get_last_run_time( Date::FORMAT_DATETIME );

				} else {

					$start_date = date( Date::FORMAT_DATETIME, 0 ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date

				}

				// Add start time to search criteria.
				$search_criteria['start_date'] = $start_date;

			}

			// Set end time.
			if ( $search_criteria['target'] === 'custom' && rgars( $this->meta, 'dateRange/end' ) ) {

				// Add end time to search criteria.
				$search_criteria['end_date'] = fg_entryautomation()->strtotime( $this->meta['dateRange']['end'], Date::FORMAT_DATETIME );

			} elseif ( $search_criteria['target'] !== 'all' ) {

				$run_time = $this->get_current_run_time( true );

				// Add end time to search criteria.
				$search_criteria['end_date'] = $run_time;

			}

			// Set entry statuses.
			$search_criteria['status'] = rgar( $this->meta, 'entryStatus', 'active' );

			// Add conditional logic.
			if ( rgar( $this->meta, 'feed_condition_conditional_logic' ) ) {

				// Get conditional logic.
				$conditional_logic = $this->meta['feed_condition_conditional_logic_object']['conditionalLogic'];

				// Initialize field filters array.
				$field_filters = [ 'mode' => $conditional_logic['logicType'] ];

				// Loop through rules.
				foreach ( $conditional_logic['rules'] as $rule ) {

					// Get field.
					$field = GFAPI::get_field( $form, $rule['fieldId'] );

					// Handle product field.
					if ( $field && $field->type === 'product' && in_array( $field->get_input_type(), [ 'radio', 'select' ] ) ) {
						$rule['operator'] = 'contains';
					}

					// Add rule.
					$field_filters[] = [
						'key'      => $rule['fieldId'],
						'operator' => $rule['operator'],
						'value'    => $rule['value'],
					];

				}

				// Add to search criteria.
				$search_criteria['field_filters'] = $field_filters;

			}

		}

		/**
		 * Modify the Entry Automation search criteria.
		 *
		 * @param array $search_criteria Search criteria.
		 * @param Task  $task            Entry Automation Task object.
		 * @param array $form            The Form object.
		 */
		$this->search_criteria = gf_apply_filters( [
			'fg_entryautomation_search_criteria',
			$this->id,
		], $search_criteria, $this, $form );

		return $this->search_criteria;

	}





	// # RUN TIME ------------------------------------------------------------------------------------------------------

	/**
	 * Get time task was last run.
	 *
	 * @since 2.0
	 *
	 * @param string $format Format to return last run time in.
	 *
	 * @return bool|int|string
	 */
	public function get_last_run_time( $format = 'Y-m-d g:i A' ) {

		// Get last run time.
		$last_run_time = get_option( fg_entryautomation()->get_slug() . '_last_run_time_' . $this->id );

		return $last_run_time ? fg_entryautomation()->strtotime( $last_run_time, $format, true, true ) : false;

	}

	/**
	 * Get time task is scheduled to run next.
	 *
	 * @since 2.0
	 *
	 * @param string $format Format to return next run time in.
	 *
	 * @return bool|int|string
	 */
	public function get_next_run_time( $format = 'timestamp' ) {

		// Get next scheduled event.
		$next_event = Scheduler::get_task_event( $this->id );

		// If task has not run yet, return.
		if ( ! $next_event ) {
			return false;
		}

		return fg_entryautomation()->strtotime( $next_event['timestamp'], $format, true, true );

	}

	/**
	 * Prepare the next time an Entry Automation task should run.
	 *
	 * @since  1.0.4
	 * @access public
	 *
	 * @param int $task_run_time Run time of current task.
	 *
	 * @return int
	 */
	public function prepare_next_run_time( $task_run_time ) {

		// Get the frequency setting.
		$frequency = rgar( $this->meta, 'frequency' );

		switch ( $frequency ) {
			case 'days_of_week':
			case 'days_of_month':
				$days    = [];
				$options = wp_list_pluck( fg_entryautomation()->get_days_of_options( $frequency ), 'name' );

				foreach ( $options as $option ) {
					if ( $this->meta[ $option ] == 1 ) {
						$days[] = str_replace( "{$frequency}_", '', $option );
					}
				}

				if ( empty( $days ) ) {
					$next_run_time = false;
				} else {
					$next_run_time = $this->get_next_run_time_by_days( $task_run_time, $frequency, $days );
				}

				break;
			default:
				$next_run_time = $this->get_next_run_time_by_interval( $task_run_time );
		}

		/**
		 * Modify when the task will run next.
		 *
		 * @since 1.4.1
		 *
		 * @param int  $next_run_time Unix timestamp for when the task runs next.
		 * @param Task $task          Entry Automation task meta.
		 * @param int  $task_run_time Run time of current task.
		 */
		return apply_filters( 'fg_entryautomation_next_run_time', $next_run_time, $this, $task_run_time );

	}

	/**
	 * Get the next run time by interval.
	 *
	 * @since 3.0
	 *
	 * @param int $task_run_time The current task run time.
	 *
	 * @return int
	 */
	private function get_next_run_time_by_interval( $task_run_time ) {

		// Set a default run time interval.
		if ( empty( $this->meta['runTime']['number'] ) ) {
			$this->meta['runTime']['number'] = 1;
		}

		// Get interval.
		switch ( $this->meta['runTime']['unit'] ) {

			case 'minutes':
				$next_run_time = strtotime( sprintf( '+%d minutes', $this->meta['runTime']['number'] ), $task_run_time );
				$interval      = MINUTE_IN_SECONDS * $this->meta['runTime']['number'];
				break;

			case 'hours':
				$next_run_time = strtotime( sprintf( '+%d hours', $this->meta['runTime']['number'] ), $task_run_time );
				$interval      = HOUR_IN_SECONDS * $this->meta['runTime']['number'];
				break;

			case 'days':
				$next_run_time = strtotime( sprintf( '+%d days', $this->meta['runTime']['number'] ), $task_run_time );
				$interval      = DAY_IN_SECONDS * $this->meta['runTime']['number'];
				break;

			case 'weeks':
				$next_run_time = strtotime( sprintf( '+%d weeks', $this->meta['runTime']['number'] ), $task_run_time );
				$interval      = WEEK_IN_SECONDS * $this->meta['runTime']['number'];
				break;

			case 'months':
				$next_run_time = strtotime( sprintf( '+%d months', $this->meta['runTime']['number'] ), $task_run_time );
				$interval      = MONTH_IN_SECONDS * $this->meta['runTime']['number'];
				break;

			case 'years':
				$next_run_time = strtotime( sprintf( '+%d years', $this->meta['runTime']['number'] ), $task_run_time );
				$interval      = YEAR_IN_SECONDS * $this->meta['runTime']['number'];
				break;

			default:
				$next_run_time = $task_run_time;
				$interval      = 1;
				break;

		}

		// Make sure the next run time is in the future.
		$time = time();

		if ( $next_run_time <= $time ) {
			fg_entryautomation()->log_debug( __METHOD__ . sprintf( '(): Running task #%d scheduled for every %d %s at: %s', $this->id, $this->meta['runTime']['number'], $this->meta['runTime']['unit'], date( Date::FORMAT_DATETIME, $task_run_time ) ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date

			$updated_next_run_time = $time + $interval - ( ( $time - $next_run_time ) % $interval );

			fg_entryautomation()->log_debug( __METHOD__ . sprintf( '(): The supposed next run time (%s) has passed, update it to: %s', date( Date::FORMAT_DATETIME, $next_run_time ), date( Date::FORMAT_DATETIME, $updated_next_run_time ) ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date

			$next_run_time = $updated_next_run_time;
		}

		return $next_run_time;

	}

	/**
	 * Get the next turn time by selected days.
	 *
	 * @since 3.0
	 *
	 * @param int    $task_run_time The current run time.
	 * @param string $frequency     The frequency.
	 * @param array  $days          The selected days.
	 *
	 * @return int
	 */
	private function get_next_run_time_by_days( $task_run_time, $frequency, $days ) {

		// Use the current time as the base to find the closest next run time.
		$current_time = time();
		// Get the time from task run time.
		$time = gmdate( Date::FORMAT_TIME, $task_run_time );
		// Store all the scheduled days in timestamps in an array.
		$scheduled_days = [];

		if ( $frequency === 'days_of_week' ) {

			foreach ( $days as $d ) {
				if ( ucfirst( $d ) === gmdate( 'l' ) ) {
					// Schedule the day in the next week.
					$scheduled_days[] = strtotime( $d, $current_time + 86400 );
				} else {
					// $d will be the day of week, e.g. sunday, monday...
					$scheduled_days[] = strtotime( $d );
				}
			}
		} else {

			$current_ym        = gmdate( 'Y-m-', $current_time );
			$next_ym           = gmdate( 'Y-m-', strtotime( '+1 month' ) );
			$last_day_of_month = (int) gmdate( 't' );

			foreach ( $days as $d ) {

				// $d is the day of month in number, or the last day of month if it doesn't exist.
				if ( $d > $last_day_of_month ) {
					$d = $last_day_of_month;
				}

				$time_this_month = strtotime( $current_ym . $d );
				$time_next_month = strtotime( $next_ym . $d );

				if ( $time_this_month > $current_time ) {
					$scheduled_days[] = $time_this_month;
				} else {
					// This will include the case when $d === date( 'd' ).
					$scheduled_days[] = $time_next_month;
				}
			}
		}

		// min( $scheduled_days ) will be the closest scheduled day.
		$next_run_date = gmdate( Date::FORMAT_DATE, min( $scheduled_days ) );

		return strtotime( $next_run_date . ' ' . $time );

	}

	/**
	 * Get the current task run time.
	 *
	 * @since 3.0
	 *
	 * @param bool $set_end_date If it's for setting the end date, the format needs to be different.
	 *
	 * @return bool|string|null
	 */
	private function get_current_run_time( $set_end_date = false ) {

		if ( ! wp_doing_cron() ) {

			// If it's not a cron job running, just use the current time as the run time.
			$run_time = time();

		} else {

			$current_event = Scheduler::get_task_event( $this->id, true );

			// If task has not run yet, return.
			if ( ! $current_event ) {

				return false;

			}

			// Get the current task run time.
			$run_time = $current_event['timestamp'];

		}

		if ( $set_end_date ) {

			fg_entryautomation()->log_debug( __METHOD__ . '(): Setting the end date to the current run time: ' . $run_time );

			return fg_entryautomation()->strtotime( $run_time, Date::FORMAT_DATETIME, true, true );

		}

		return $run_time;

	}





	// # ARRAY ACCESS HELPERS ------------------------------------------------------------------------------------------

	/**
	 * Whether or not an offset exists
	 *
	 * @since 2.0
	 *
	 * @param string $offset An offset to check for.
	 *
	 * @return bool
	 */
	public function offsetExists( $offset ) {

		// If property exists, return it.
		if ( property_exists( $this, $offset ) ) {
			return true;
		}

		// Check for item in meta array.
		if ( isset( $this->meta[ $offset ] ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Returns the value at specified offset.
	 *
	 * @since 2.0
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return bool|mixed
	 */
	public function offsetGet( $offset ) {

		// If property exists, return it.
		if ( property_exists( $this, $offset ) ) {
			return $this->{$offset};
		}

		// Check for item in meta array.
		if ( isset( $this->meta[ $offset ] ) ) {
			return $this->meta[ $offset ];
		}

		return false;

	}

	/**
	 * Assign a value to the specified offset.
	 *
	 * @since 2.0
	 *
	 * @param string $offset The offset to assign the value to.
	 * @param mixed  $value  The value to set.
	 */
	public function offsetSet( $offset, $value ) {

		// If property exists, return it.
		if ( property_exists( $this, $offset ) ) {
			$this->{$offset} = $value;
		}

		// Check for item in meta array.
		if ( isset( $this->meta[ $offset ] ) ) {
			$this->meta[ $offset ] = $value;
		}

	}

	/**
	 * Unset an offset.
	 *
	 * @since 2.0
	 *
	 * @param string $offset The offset to unset.
	 */
	public function offsetUnset( $offset ) {

		// If property exists, return it.
		if ( property_exists( $this, $offset ) ) {
			unset( $this->{$offset} );
		}

		// Check for item in meta array.
		if ( isset( $this->meta[ $offset ] ) ) {
			unset( $this->meta[ $offset ] );
		}

	}

}
