<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 9/18/18
 * Time: 09:49
 */

/**
 * Cron job handler class
 * Class PDA_Cronjob_Handler
 */
class PDA_Cronjob_Handler {

	/**
	 * Logger
	 *
	 * @var PDA_Logger
	 */
	private $logger;

	/**
	 * License service
	 *
	 * @var License service
	 */
	private $service;

	/**
	 * PDA_Cronjob_Handler constructor.
	 *
	 * @param PDA_Services $pda_service PDA Services.
	 */
	public function __construct( $pda_service = null ) {
		$this->logger  = new PDA_Logger();
		$this->service = is_null( $pda_service ) ? new PDA_Services() : $pda_service;
	}

	/**
	 * Exec function for PDA license cron job.
	 * Only call API if the expired date less then current time. Otherwise it will re-schedule the cron job.
	 * It will update the expired license flag if the license expired.
	 * @deprecated
	 */
	public function pda_ls_cron_exec() {
	}

	/**
	 * Exec function for PDA delete expired private link cron job
	 */
	public function pda_delete_expired_private_links_cron_exec() {
		$repository = new PDA_v3_Gold_Repository();
		$repository->delete_all_private_link_expired_with_type( PDA_v3_Constants::PDA_PRIVATE_LINK_EXPIRED, PDA_v3_Constants::RETENTION_TIMES_P_EXPIRED );
		$repository->delete_all_private_link_expired_with_type( PDA_v3_Constants::PDA_PRIVATE_LINK_LONG_LIFE, PDA_v3_Constants::RETENTION_TIMES_LONG_LIFE );
		$this->logger->info( 'Delete expired private links\'s response: Deleted' );
	}

	/**
	 * Schedule license cron job by license expired date plus a gap X more day(s) (LICENSE_GAP_TIME_CHECKING constant) . Otherwise get the current time.
	 * @deprecated
	 * @param bool $force Force flag to schedule cron job immediately.
	 *
	 * @return bool
	 */
	public function schedule_ls_cron_job( $force = false ) {
		return false;
	}

	/**
	 * Schedule delete expired private link cron job
	 */
	public function schedule_delete_expired_private_links_cron_job() {
		if ( ! wp_next_scheduled( PDA_v3_Constants::PDA_DELETE_EXPIRED_PRIVATE_LINK_CRON_JOB_NAME ) ) {
			wp_schedule_event( time(), 'daily', PDA_v3_Constants::PDA_DELETE_EXPIRED_PRIVATE_LINK_CRON_JOB_NAME );
		}
	}

	/**
	 * Un-schedule license cron job
	 */
	public function unschedule_ls_cron_job() {
		$timestamp = wp_next_scheduled( PDA_v3_Constants::PDA_LS_CRON_JOB_NAME );
		wp_unschedule_event( $timestamp, PDA_v3_Constants::PDA_LS_CRON_JOB_NAME );
	}

	/**
	 * Un-schedule delete expired private link cron job
	 */
	public function unschedule_delete_expired_private_links_cron_job() {
		$timestamp = wp_next_scheduled( PDA_v3_Constants::PDA_DELETE_EXPIRED_PRIVATE_LINK_CRON_JOB_NAME );
		wp_unschedule_event( $timestamp, PDA_v3_Constants::PDA_DELETE_EXPIRED_PRIVATE_LINK_CRON_JOB_NAME );
	}

	/**
	 * Add our custom intervals quarterly, yearly and monthly
	 *
	 * @param array $schedules Schedules.
	 *
	 * @return mixed
	 */
	public function add_custom_intervals( $schedules ) {
		$schedules[ PDA_v3_Constants::CRON_JOB_QUARTER ] = array(
			'interval' => 4 * MONTH_IN_SECONDS,
			'display'  => __( 'Quarterly', 'prevent-direct-access-gold' ),
		);

		$schedules[ PDA_v3_Constants::CRON_JOB_YEARLY ] = array(
			'interval' => YEAR_IN_SECONDS,
			'display'  => __( 'Yearly', 'prevent-direct-access-gold' ),
		);

		$schedules[ PDA_v3_Constants::CRON_JOB_MONTHLY ] = array(
			'interval' => MONTH_IN_SECONDS,
			'display'  => __( 'Monthly', 'prevent-direct-access-gold' ),
		);

		return $schedules;
	}

	/**
	 * Schedule the event immediately.
	 *
	 * @param bool   $force      Force mode to run the cron job whatever it run before or not.
	 * @param string $recurrence The recurrence set for cron job to run. For example, daily, monthly.
	 */
	private function schedule_event_now( $force, $recurrence ) {
		if ( $force ) {
			wp_schedule_event( time(), $recurrence, PDA_v3_Constants::PDA_LS_CRON_JOB_NAME );
		} else {
			if ( ! wp_next_scheduled( PDA_v3_Constants::PDA_LS_CRON_JOB_NAME ) ) {
				wp_schedule_event( time(), $recurrence, PDA_v3_Constants::PDA_LS_CRON_JOB_NAME );
			}
		}
	}

	/**
	 * Schedule event in the specific time
	 *
	 * @param int    $first_time_to_run The first time you want cron job to execute.
	 * @param bool   $force             Force mode to run the cron job whatever it run before or not.
	 * @param string $recurrence        The recurrence set for cron job to run. For example, daily, monthly.
	 */
	private function schedule_event_in_specific_time( $first_time_to_run, $force, $recurrence ) {
		if ( $force ) {
			wp_schedule_event( $first_time_to_run, $recurrence, PDA_v3_Constants::PDA_LS_CRON_JOB_NAME );
		} elseif ( ! wp_next_scheduled( PDA_v3_Constants::PDA_LS_CRON_JOB_NAME ) ) {
			wp_schedule_event( $first_time_to_run, $recurrence, PDA_v3_Constants::PDA_LS_CRON_JOB_NAME );
		}
	}
}
