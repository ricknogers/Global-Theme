<?php



if ( ! class_exists( 'PDA_Move_Files_After_Deactivate' ) ) {
    class PDA_Move_Files_After_Deactivate extends WP_Background_Process
    {
        /**
         * @var string
         */
        protected $action = 'pda_move_files_after_deactivate';

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
            $repository = new PDA_v3_Gold_Repository();
            $repository->un_protect_files();
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