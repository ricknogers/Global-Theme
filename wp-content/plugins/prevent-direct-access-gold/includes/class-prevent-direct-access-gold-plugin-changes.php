<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 9/17/19
 * Time: 16:14
 */

if ( ! class_exists( 'PDA_Plugin_Changes' ) ) {
	/**
	 * PDA Plugin changes that using admin_init to update our logic or data changes
	 * Class PDA_Plugin_Changes
	 */
	class PDA_Plugin_Changes {
		/**
		 * Plugin changes version will have format x.y with x and y are positive numeric
		 *
		 * @var string Plugin version.
		 */
		private $version = '1.0';

		/**
		 * Gold helper functions
		 *
		 * @since 3.1.5
		 * @var Pda_Gold_Functions;
		 */
		private $helpers;

		/**
		 * PDA_Plugin_Changes constructor.
		 * Init plugin option.
		 *
		 * @param Pda_Gold_Functions $helpers PDA Gold helpers function.
		 */
		public function __construct( $helpers = null ) {
			$opt = get_option( PDA_v3_Constants::PDA_OPTION_PLUGIN_CHANGE_VERSION, false );
			if ( false !== $opt ) {
				$this->version = $opt;
			}

			if ( is_null( $helpers ) ) {
				$this->helpers = new Pda_Gold_Functions();
			} else {
				$this->helpers = $helpers;
			}
		}

		/**
		 * Process plugin changes
		 */
		public function process_plugin_changes() {
			$this->version_1_0_update_cron_job_timestamp();
			$this->version_1_1_add_web_crawlers_option();
		}

		/**
		 * Update version 1.0 that we will un-schedule and schedule the cron job again.
		 */
		private function version_1_0_update_cron_job_timestamp() {
			if ( '1.0' === $this->version ) {
				$cronjob_handler = new PDA_Cronjob_Handler();
				$cronjob_handler->unschedule_ls_cron_job();
				$this->version = '1.1';
				update_option( PDA_v3_Constants::PDA_OPTION_PLUGIN_CHANGE_VERSION, $this->version, 'no' );
			}
		}

		/**
		 * Update version 1.1 that we add default data for Web Crawlers option.
		 */
		private function version_1_1_add_web_crawlers_option() {
			if ( '1.1' === $this->version ) {
				// Only update the default values for WC options in main site.
				if ( ! is_main_site() ) {
					return;
				}
				if ( false === $this->helpers->get_site_settings( PDA_v3_Constants::PDA_GOLD_ENABLE_WEB_CRAWLERS ) ) {
					Pda_Gold_Functions::update_site_settings( PDA_v3_Constants::PDA_GOLD_ENABLE_WEB_CRAWLERS, true );
					if ( empty( $this->helpers->get_site_setting_type_is_array( PDA_v3_Constants::PDA_GOLD_WEB_CRAWLERS ) ) ) {
						$supported_crawlers = array_column( Pda_Gold_Functions::get_supported_crawlers(), 'value' );
						Pda_Gold_Functions::update_site_settings( PDA_v3_Constants::PDA_GOLD_WEB_CRAWLERS, $supported_crawlers );
					}
				}
				$this->version = '1.2';
				update_option( PDA_v3_Constants::PDA_OPTION_PLUGIN_CHANGE_VERSION, $this->version, 'no' );

				// Need to refresh the htaccess rules while adding the default values.
				Prevent_Direct_Access_Gold_Htaccess::register_rewrite_rules();
			}
		}
	}
}
