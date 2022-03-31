<?php

if ( ! class_exists( 'Pda_Update_Service' ) ) {

	class Pda_Update_Service {

		private $pda_service_version;

		public function __construct() {
			$this->pda_service_version = false === get_option( PDA_v3_Constants::PDA_UPDATE_SERVICES ) ? '1.0' : get_option( PDA_v3_Constants::PDA_UPDATE_SERVICES );
		}

		/**
		 * Update services
		 */
		public function pda_migrate_old_data() {
			if ( $this->pda_service_version === '1.0' ) {
				$this->migrate_data_for_no_access_page();
				$this->pda_service_version = '1.1';
				update_option( PDA_v3_Constants::PDA_UPDATE_SERVICES, $this->pda_service_version );
			}
		}

		public function migrate_data_for_no_access_page() {
			$settings = get_option( PDA_v3_Constants::OPTION_NAME );
			if ( $settings ) {
				$options = unserialize( $settings );
				if ( array_key_exists( PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE, $options )
				     && '' !== $options[ PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE ]
				     && ';' !== $options[ PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE ] ) {
					$no_access_page                                          = $options[ PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE ];
					$options[ PDA_v3_Constants::PDA_NAP_EXISTING_PAGE_POST ] = $no_access_page;
					$options[ PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE ]    = 'search-page-post';
					update_option( PDA_v3_Constants::OPTION_NAME, serialize( $options ), 'no' );
				}
			}
		}

	}

}
