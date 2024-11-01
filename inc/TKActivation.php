<?php

class TKActivation
{
	public static function run()
	{
		  // all action process here

		 self::createSchedule();
		 self::createTable();
		 self::preConfig();
	}



public  static function createSchedule()
 {
    if(get_option('wpTrackKeeperScheduleTime') ==false ){  
    add_option('wpTrackKeeperScheduleTime','hourly');
}


$nextaction = wp_next_scheduled('makeNewFileCheckNow');

if($nextaction ==false){
wp_schedule_event( time(), get_option('wpTrackKeeperScheduleTime'), 'makeNewFileCheckNow' );
}

}


public static function createTable()
{
	    global $wpdb;
        $tablename = $wpdb->prefix.'trackedfiles_reference_state';
        $charset_collate = $wpdb->get_charset_collate();
        require_once ABSPATH.'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE IF NOT EXISTS $tablename (
	    id_state bigint(20) NOT NULL AUTO_INCREMENT,
	    id_file VARCHAR (128) NOT NULL,
	    filename VARCHAR(256) NOT NULL,
	    filelocation VARCHAR(256) NOT NULL,
	    lastmodified bigint(64) NOT NULL,
	    filesize bigint(20) NOT NULL,

	    UNIQUE KEY id_state (id_state)
	) $charset_collate";
        dbDelta($sql);
}





 public static function preConfig()
 {
 	    update_option('tk_cron_set_enable', 'yes');
        update_option('tk_cron_set_delete', 'no');
        update_option('tk_cron_set_mix_name_file','no');

        update_option('tk_cron_set_sendsms', 'no');
        update_option('tk_cron_set_sms_sender', 'HACK ALERT');
        update_option('tk_cron_set_sms_number', '+234');
        update_option('tk_cron_set_sms_username', 'ZI');
        update_option('tk_cron_set_sms_password', 'ZI');

        update_option('tk_cron_set_sendmail', 'yes');
        update_option('tk_cron_set_mail_email', get_bloginfo('admin_email'));

        update_option('tk_cron_set_smtp', 'no');
        update_option('tk_cron_set_smtp_host', 'mail.mywebsite.com');
                
        update_option('tk_cron_set_smtp_port', '465');
        update_option('tk_cron_set_smtp_user', 'myname@mysite.com');
        update_option('tk_cron_set_smtp_password', '');
        update_option('tk_cron_set_smtp_encryption', 'ssl');
 }

}