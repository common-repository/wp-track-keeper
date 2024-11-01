<?php
/*
 SMSpress API.....
*/

 class WPZillionText
 {
     const SINGLEDAPI_URL = 'https://zilliontext.com.ng/index.php';
     const BULKAPI_URL = 'https://zilliontext.com.ng/index.php';
     const BALAPI_URL = 'https://zilliontext.com.ng/index.php';
     public $susername;
     public $spassword;
     public $sender;
     public $mobile;
     public $messagedata;

     public function __construct()
     {
         $this->susername = get_option('tk_cron_set_sms_username');
         $this->spassword = get_option('tk_cron_set_sms_password');
     }


     
     public function doHTTPRequest($base_url, $options)
     {
        return wp_remote_retrieve_body(wp_remote_get($base_url, $options));
     }



     public function send( $message )
     {
         $mobile = get_option('tk_cron_set_sms_number');
         $sender = get_option('tk_cron_set_sms_sender');
         if(!$mobile || !$sender){
            return;
         }
         $this->sendMessage($mobile, $message, $sender);
     }


     public function sendMessage($mobile, $messagedata, $sender)
     {

         try {
             
             $rays = ['body'=>['spapiusername'=>$this->susername, 'password'=>$this->spassword, 'countrycode'=>'All', 'smstype'=>'0', 'sender'=>$sender, 'messagetext'=>$messagedata, 'messagenumber'=>$mobile, 'action'=>'sms']];

             $this->doHTTPRequest(self::SINGLEDAPI_URL, $rays);


         } catch (Exception $e) {
             echo 'error occured with connection';
             exit;
         }

         return true;
     }


    


    public function checkbalance()
    {

        $rays = ['body'=>['spapiusername'=>$this->susername, 'password'=>$this->spassword, 'action'=>'bal']];
        $portresponse = $this->doHTTPRequest(self::BALAPI_URL, $rays);
        if (stripos($portresponse, ':')) {
            $args = explode(':', $portresponse);

            return array_pop($args);
        }

        return $portresponse;
    }


 }