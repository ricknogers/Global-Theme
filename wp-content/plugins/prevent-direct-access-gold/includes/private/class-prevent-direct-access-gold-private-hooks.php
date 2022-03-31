<?php

if ( ! class_exists( 'PDA_Private_Hooks' ) ) {
	/**
	 * Helper class to define the hooks existing in the Prevent Direct Access Gold plugin.
	 *
	 * Class PDA_Private_Hooks
	 */
	class PDA_Private_Hooks {
		/**
		 * Action check have bucket
		 */
		const PDA_HOOK_CHECK_S3_HAS_BUCKET = 'pda_check_s3_has_bucket';

		/**
		 * Action show file status under pda column
		 */
		const PDA_HOOK_SHOW_STATUS_FILE_IN_PDA_COLUMN = 'pda_show_status_file_in_pda_column';

		/**
		 * Action handle protect file in folder
		 */
		const PDA_HOOK_CUSTOM_HANDLE_PROTECTED_FILE = 'pda_custom_handle_protected_file';

		/**
		 * Action handle before sending protected link content
		 */
		const PDA_HOOK_BEFORE_SENDING_PROTECTED_LINK = 'pda_before_sending_protected_link_content';

		/**
		 * Filter handle after checking file existed.
		 */
		const PDA_HOOK_AFTER_CHECK_FILE_EXIST = 'pda_after_check_file_exist';

	}
}
