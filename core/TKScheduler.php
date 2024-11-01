<?php


class TKScheduler
 {


        public function __construct()
        {
        	  add_action('makeNewFileCheckNow', array($this, 'startAndCheckFiles'));
        }


        public function startAndCheckFiles()
        {
              require_once plugin_dir_path(dirname(__FILE__)).'classes/TKScheduleRunner.php';
              $tsr = new TKScheduleRunner();
              $tsr->runReport();
              return true;

        }


		     
        public function clearSchedule()
        {
            wp_clear_scheduled_hook( 'makeNewFileCheckNow' );

        }

        public function genToken( $length = 64 )
           {
                   $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $count = mb_strlen($chars);

                    for ($i = 0, $token = ''; $i < $length; $i++) {
                        $index = rand(0, $count - 1);
                        $token .= mb_substr($chars, $index, 1);
                    }

                    return $token;
        }


}

new TKScheduler();