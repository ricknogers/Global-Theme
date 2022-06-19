<?php
/**
 * Entry Automation Task Scheduler.
 *
 * @package ForGravity\Entry_Automation
 */

namespace ForGravity\Entry_Automation;

if ( ! class_exists( '\GFForms' ) ) {
	die();
}

/**
 * Task Scheduler class.
 *
 * @since     3.0
 * @package   ForGravity\Entry_Automation
 * @author    ForGravity
 * @copyright Copyright (c) 2021, ForGravity
 */
class Scheduler {

	/**
	 * The cron jobs.
	 *
	 * @since 3.0
	 *
	 * @var array $cron The cache of _get_cron_array().
	 */
	public static $cron;

	/**
	 * Schedule event for task.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param int        $task_id   Task ID.
	 * @param int        $form_id   Form ID.
	 * @param int|string $timestamp Time for event.
	 *
	 * @uses   Entry_Automation::strtotime()
	 * @uses   Scheduler::get_form_events()
	 * @uses   Scheduler::unschedule_task()
	 */
	public static function schedule_task( $task_id = 0, $form_id = 0, $timestamp = 0 ) {

		// If task ID, form ID or timestamp are not define, exit.
		if ( ! $task_id || ! $form_id || ! $timestamp ) {
			return;
		}

		// Convert timestamp.
		if ( ! is_numeric( $timestamp ) ) {
			$timestamp = fg_entryautomation()->strtotime( $timestamp );
		}

		// Unschedule existing event.
		self::unschedule_task( $task_id );

		// Get events for form.
		$form_events = self::get_form_events( $form_id );

		// If events were found for form, reschedule existing event.
		if ( $form_events ) {

			// Loop through form events.
			foreach ( $form_events as $form_event ) {

				// If event does not match timestamp, continue.
				if ( $form_event['timestamp'] !== $timestamp ) {
					continue;
				}

				// Unschedule existing event.
				wp_clear_scheduled_hook( FG_ENTRYAUTOMATION_EVENT, $form_event['args'] );

				// Get feed IDs for form.
				$feeds    = fg_entryautomation()->get_feeds( $form_id );
				$feed_ids = wp_list_pluck( $feeds, 'id' );
				$feed_ids = array_map( 'intval', $feed_ids );

				// Filter form feed IDs.
				$feed_ids = array_filter( $feed_ids, function( $feed_id ) use ( $task_id, $form_event ) {

					// If feed ID is the task ID, return true.
					if ( intval( $feed_id ) === intval( $task_id ) ) {
						return true;
					}

					// If feed ID is in the existing event, return true.
					if ( in_array( intval( $feed_id ), $form_event['args'][0] ) ) {
						return true;
					}

					return false;

				} );

				// Prepare event arguments.
				$args = array( array_values( $feed_ids ), intval( $form_id ) );

				// Schedule event.
				wp_schedule_single_event( $timestamp, FG_ENTRYAUTOMATION_EVENT, $args );

				return;

			}

		}

		// Prepare event arguments.
		$args = [ [ intval( $task_id ) ], intval( $form_id ) ];

		// Schedule event.
		wp_schedule_single_event( $timestamp, FG_ENTRYAUTOMATION_EVENT, $args );

		return;

	}

	/**
	 * Unschedule a task event.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param int $task_id Task ID.
	 *
	 * @uses   Scheduler::get_task_event()
	 */
	public static function unschedule_task( $task_id = 0 ) {

		// If task ID is not provided, return.
		if ( ! $task_id ) {
			return;
		}

		// Get event for task.
		$event = self::get_task_event( $task_id );

		// If event task is not found, return.
		if ( ! $event ) {
			return;
		}

		// If more than one task is assigned to event, exclude task and save.
		if ( count( $event['args'][0] ) > 1 ) {

			// Clear scheduled event.
			wp_clear_scheduled_hook( FG_ENTRYAUTOMATION_EVENT, $event['args'] );

			// Remove task ID from event arguments.
			$event['args'][0] = array_filter( $event['args'][0], function( $task ) use ( $task_id ) {
				return intval( $task_id ) != intval( $task );
			} );
			$event['args'][0] = array_values( $event['args'][0] );

			// Schedule event.
			wp_schedule_single_event( $event['timestamp'], FG_ENTRYAUTOMATION_EVENT, $event['args'] );

		} else {

			// Clear scheduled event.
			wp_clear_scheduled_hook( FG_ENTRYAUTOMATION_EVENT, $event['args'] );

		}

	}

	/**
	 * Get cron events for form.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param int $form_id Form ID.
	 *
	 * @return array|bool
	 */
	public static function get_form_events( $form_id = 0 ) {

		// If form ID is not provided, return.
		if ( ! $form_id ) {
			return false;
		}

		// Initialize return array.
		$return = array();

		// Get cron array.
		$cron = _get_cron_array();

		// Loop through cron items.
		foreach ( $cron as $timestamp => $actions ) {

			// Loop through actions at time.
			foreach ( $actions as $action => $events ) {

				// If this is not an Entry Automation event, skip.
				if ( FG_ENTRYAUTOMATION_EVENT !== $action ) {
					continue;
				}

				// Loop through events.
				foreach ( $events as $event ) {

					// If form is not assigned to this event, skip.
					if ( intval( $form_id ) !== $event['args'][1] ) {
						continue;
					}

					// Add to return array.
					$return[] = array(
						'action'    => $action,
						'args'      => $event['args'],
						'timestamp' => $timestamp,
						'schedule'  => $event['schedule'],
						'interval'  => isset( $event['interval'] ) ? $event['interval'] : false,
					);

				}

			}

		}

		return $return ? $return : false;

	}

	/**
	 * Get cron event for task.
	 *
	 * @since  1.3
	 * @access public
	 *
	 * @param int  $task_id   Task ID.
	 * @param bool $use_cache If use cron jobs from the cache.
	 *
	 * @return array|bool
	 */
	public static function get_task_event( $task_id = 0, $use_cache = false ) {

		// If task ID is not provided, return.
		if ( ! $task_id ) {
			return false;
		}

		// Get cron array.
		if ( $use_cache && ! empty( self::$cron ) ) {
			$cron = self::$cron;
		} else {
			$cron = _get_cron_array();
		}

		// Loop through cron items.
		foreach ( $cron as $timestamp => $actions ) {

			// Loop through actions at time.
			foreach ( $actions as $action => $events ) {

				// If this is not an Entry Automation event, skip.
				if ( FG_ENTRYAUTOMATION_EVENT !== $action ) {
					continue;
				}

				// Loop through events.
				foreach ( $events as $event ) {

					// Get tasks for event.
					$tasks = $event['args'][0];

					// If task is not assigned to this event, skip.
					if ( ! in_array( intval( $task_id ), $tasks ) ) {
						continue;
					}

					return array(
						'action'    => $action,
						'args'      => $event['args'],
						'timestamp' => $timestamp,
						'schedule'  => $event['schedule'],
						'interval'  => isset( $event['interval'] ) ? $event['interval'] : false,
					);

				}

			}

		}

		return false;

	}

	/**
	 * Cache the cron array.
	 *
	 * @since 3.0
	 *
	 * @return array|false
	 */
	public static function get_cron_array() {

		if ( wp_doing_cron() ) {
			self::$cron = _get_cron_array();
		}

		return self::$cron;

	}

}
