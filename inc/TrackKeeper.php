<?php

require_once dirname(__FILE__).'/TKWPBaseField.php';
class TrackKeeper extends TKWPBaseField
{

   
   public function __construct()
   {
   	   $this->name = 'wp-track-keeper';
   	   $this->version = '1.0';
   	   $this->acro = 'BS';
   	   parent::__construct();
   }


    public function widget()
    {

    }

    public function shortcode()
    {
    	
    }


}


