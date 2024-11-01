<?php

/**
 * 
 */

require_once ABSPATH.'wp-includes/class-phpmailer.php';
require_once ABSPATH.'wp-includes/pluggable.php';

class TKMailBoss
{
	
    public $plugin_name = 'wp-track-keeper';
	public function post($content, $summary)
	{
         if(get_option('tk_cron_set_smtp')=='yes'){

         	return $this->sendSMTP($content, $summary);

         }
         else{
             return $this->sendBasic($content, $summary);
         }
         return true;
	}


    public function getTo()
    {
    	   $to = get_option('tk_cron_set_mail_email');
		    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
		       $to = get_bloginfo('admin_email');
		     }

		 return $to;

    }

    public function getSubject()
    { 
    	return 'Hack Alert: '.get_bloginfo('name');
    }

	public function prepareBody($content, $summary)
	{
		 $vars = $this->getVar();
		 $vars['[:content:]'] = $content;
		 $vars['[:summary:]'] = $summary;
		 return $this->generateTemplate($vars);
	}
	public function sendBasic($content, $summary)
	{
    require_once ABSPATH.'wp-includes/pluggable.php';

		$to = $this->getTo();
		$subject = $this->getSubject();
		$body = $this->prepareBody($content, $summary);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		return \wp_mail( $to, $subject, $body, $headers );
	}

   
    public function getVar()
    {
        return ['[:sitename:]'=> get_bloginfo('name'), '[:siteurl:]'=>get_bloginfo('url'), '[:imagebanner:]'=>plugins_url().'/'.$this->plugin_name.'/mail/img/bg.jpg', '[:blankgif:]'=>plugins_url().'/'.$this->plugin_name.'/mail/img/blank.gif', '<script>'=>' ', '</script>'=>' '];
    }

	public function sendSMTP($content, $summary)
	{
		$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                       
    $mail->isSMTP();                                            
    $mail->Host       = get_option('tk_cron_set_smtp_host');  
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = get_option('tk_cron_set_smtp_user');                  
    $mail->Password   = get_option('tk_cron_set_smtp_password');  

    $ency = get_option('tk_cron_set_smtp_encryption');
    if($ency =='ssl'){
     $mail->SMTPSecure ='ssl';
    } 
    elseif($ency =='tls'){
    	 $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
    } 
    else{
      $mail->SMTPSecure = false;
      $mail->SMTPAutoTLS = false;
    }                           

    $mail->Port       = (int) get_option('tk_cron_set_smtp_port'); 


    $to = $this->getTo();
    
    $mail->setFrom(get_bloginfo('admin_email'), get_bloginfo('name'));

    $mail->addAddress($to, 'Keeper');     
   

    // Content
    $mail->isHTML(true);                                  
    $mail->Subject = $this->getSubject();
    $mail->Body    = $this->prepareBody($content, $summary);
    $mail->AltBody = 'Check '.get_bloginfo('name').' files: Some files were modified';

    $mail->send();
    return true;
} catch (Exception $e) {
    return $this->sendBasic($content, $summary);

}

	}



	public function generateTemplate($t_vars)
	{
        
        try{
         $c =file_get_contents(plugins_url().'/'.$this->plugin_name.'/mail/en.html');
        }
        catch(Exception $e){
       return ' Could not load mail template ';
        }
		 $t_keys = array_keys($t_vars);
		 $t_values = array_values($t_vars);
         $content =  str_replace($t_keys, $t_values, $c);
         return $content;

	}




	
}