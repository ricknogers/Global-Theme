<?php

namespace ForGravity\Entry_Automation;

use Exception;

class_exists( '\GFForms' ) || die();

/**
 * Entry Automation System Report class.
 *
 * Adds Entry Automation Scheduled Tasks section to Gravity Forms System Report.
 *
 * @since     3.3
 * @package   EntryAutomation
 * @author    ForGravity
 * @copyright Copyright (c) 2021, ForGravity
 */
class System_Report {

	/**
	 * Adds Entry Automation Scheduled Tasks section to Gravity Forms System Report.
	 *
	 * @since 3.3
	 *
	 * @param array $system_report An array of sections displayed on the System Status page.
	 *
	 * @return array
	 */
	public static function filter_gform_system_report( $system_report ) {

		$events = self::get_events();

		if ( empty( $events ) ) {
			return $system_report;
		}

		// Get section key for Gravity Forms Environment section.
		$title_export   = wp_list_pluck( $system_report, 'title_export' );
		$gf_section_key = array_search( 'Gravity Forms Environment', $title_export );

		// Add Entry Automation section to System Report.
		$system_report[ $gf_section_key ]['tables'][] = self::build_system_report( $events );

		return $system_report;

	}

	/**
	 * Returns the System Report table for the scheduled Tasks.
	 *
	 * @since 3.3
	 *
	 * @param array $events Entry Automation scheduled events.
	 *
	 * @return array
	 */
	private static function build_system_report( $events ) {

		$table = [
			'title'        => esc_html__( 'Entry Automation Scheduled Tasks', 'forgravity_entryautomation' ),
			'title_export' => 'Entry Automation Scheduled Tasks',
			'items'        => [],
		];

		foreach ( $events as $event ) {

			foreach ( $event['args'][0] as $task_id ) {

				try {
					$task = Task::get( $task_id );
				} catch ( Exception $e ) {
					continue;
				}

				if ( ! $task->is_active ) {
					continue;
				}

				$table['items'][] = self::build_task_row( $task, $event['timestamp'] );

			}

		}

		return $table;

	}

	/**
	 * Returns the System Report row for a Task.
	 *
	 * @since 3.3
	 *
	 * @param Task $task      Task object.
	 * @param int  $timestamp Scheduled event timestamp.
	 *
	 * @return array
	 */
	private static function build_task_row( $task, $timestamp ) {

		// Build label.
		$label = sprintf(
			'%s (#%d) - Form #%d',
			$task->meta['feedName'],
			$task->id,
			$task->form_id
		);

		// Format timestamp.
		$value = ( new Date( Date::FORMAT_DATETIME, $timestamp ) )->format();

		return [
			'label'        => $label,
			'label_export' => $label,
			'value'        => $value,
			'value_export' => $value,
			'is_valid'     => ! self::is_task_late( $timestamp ),
		];

	}

	/**
	 * Returns all scheduled Entry Automation events.
	 *
	 * @since 3.3
	 *
	 * @return array
	 */
	private static function get_events() {

		$events = [];
		$cron   = _get_cron_array();

		if ( ! $cron ) {
			return $events;
		}

		foreach ( $cron as $timestamp => $actions ) {

			// Remove events that are not Entry Automation Tasks.
			$actions = array_filter(
				$actions,
				function( $item, $action ) {
					return $action === FG_ENTRYAUTOMATION_EVENT;
				},
				ARRAY_FILTER_USE_BOTH
			);

			if ( empty( $actions ) ) {
				continue;
			}

			foreach ( $actions as $action => $action_events ) {

				foreach ( $action_events as $event ) {

					$events[] = [
						'action'    => $action,
						'args'      => $event['args'],
						'timestamp' => $timestamp,
						'schedule'  => $event['schedule'],
						'interval'  => isset( $event['interval'] ) ? $event['interval'] : false,
					];

				}

			}

		}

		return $events;

	}

	/**
	 * Determine whether a Task is late.
	 *
	 * A task which has missed its schedule by more than 10 minutes is considered late.
	 *
	 * @since 3.3
	 *
	 * @param int $timestamp Scheduled event timestamp.
	 *
	 * @return bool
	 */
	private static function is_task_late( $timestamp ) {

		$until = $timestamp - time();

		return ( $until < ( 0 - ( 10 * MINUTE_IN_SECONDS ) ) );

	}

}
