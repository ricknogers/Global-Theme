<?php



if ( ! class_exists( 'PDA_Move_Files_After_Activate' ) ) {
    class PDA_Move_Files_After_Activate extends WP_Background_Process
    {
        /**
         * @var string
         */
        protected $action = 'pda_move_files_after_activate';

        /**
         * Task
         *
         * Override this method to perform any actions required on each
         * queue item. Return the modified item for further processing
         * in the next pass through. Or, return false to remove the
         * item from the queue.
         *
         * @param mixed $item Queue item to iterate over
         *
         * @return mixed
         */
        protected function task($task)
        {
	        if ( get_option( PDA_v3_Constants::PDA_IS_BACKUP_AFTER_ACTIVATE_OPTION ) === "1" ) {
		        $repo = new PDA_v3_Gold_Repository();
		        $repo->backup_protection();
		        update_option( PDA_v3_Constants::PDA_IS_BACKUP_AFTER_ACTIVATE_OPTION, 0 );
		        update_option( PDA_v3_Constants::PDA_NOTICE_CRONJOB_AFTER_ACTIVATE_OPTION, 0 );
	        }
	        return false;
        }

        /**
         * Complete
         *
         * Override if applicable, but ensure that the below actions are
         * performed, or, call parent::complete().
         */
        protected function complete()
        {
            parent::complete();
            // Show notice to user or perform some other arbitrary task...
        }
    }
}