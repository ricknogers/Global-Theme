<?php



if ( ! class_exists( 'PDA_Activate_All_Sites' ) ) {
    class PDA_Activate_All_Sites extends WP_Background_Process
    {
        /**
         * @var string
         */
        protected $action = PDA_v3_Constants::PDA_V3_ACTIVATE_ALL_SITES_OPTION_NAME;

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
        protected function task($status)
        {
            $pda_gold_func = new Pda_Gold_Functions();
            $pda_gold_func->activate_all_sites();
            return $status;
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