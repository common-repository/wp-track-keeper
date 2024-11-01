<?php
/**
 * TK MOMENT
 * https://mrparagon.me.
 */
if (!defined('ABSPATH')) {
    exit;
}
class TKConfigurationAdmin
{
    
    public function __construct()
    { 
       
        add_action('admin_init', array($this, 'registerTKMilitaryActionSetting'));
        add_action('admin_menu', array($this, 'addmenuPage'));
     
    }

    public function addmenuPage()
    {
        add_menu_page('Track K Settings', 'Track Keeper Settings', 'manage_options', 'trackkeeper_nokidnapping_nophishing', array($this, 'tkSettingCurrentPage'), 'dashicons-exerpt-view', '8.4');
    }

    public function registerTKMilitaryActionSetting()
    {

        register_setting('tk_cronwp', 'tk_cron_set_enable', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);
        register_setting('tk_cronwp', 'tk_cron_set_delete', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);

        register_setting('tk_cronwp', 'tk_cron_set_frequency',['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);

        register_setting('tk_cronwp', 'tk_cron_set_mix_name_file', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);
        register_setting('tk_cronwp', 'tk_cron_set_sendmail', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);

        register_setting('tk_cronwp', 'tk_cron_set_sendsms', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);

        register_setting('tk_cronwp', 'tk_cron_set_mail_email', array($this, 'saveTKEmailSettings'));


        register_setting('tk_cronwp', 'tk_cron_set_sms_number', array($this, 'saveTKSettings'));
        register_setting('tk_cronwp', 'tk_cron_set_sms_username', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);
        register_setting('tk_cronwp', 'tk_cron_set_sms_password', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);


        register_setting('tk_cronwp', 'tk_cron_set_sms_sender', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);

        register_setting('tk_cronwp', 'tk_cron_set_smtp', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);
        register_setting('tk_cronwp', 'tk_cron_set_smtp_host', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);
        register_setting('tk_cronwp', 'tk_cron_set_smtp_port', ['type'=>'number','sanitize_callback'=>array($this, 'saveTKNumberSettings')]);
        register_setting('tk_cronwp', 'tk_cron_set_smtp_user', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);
        register_setting('tk_cronwp', 'tk_cron_set_smtp_password', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);

        register_setting('tk_cronwp', 'tk_cron_set_smtp_encryption', ['type'=>'string','sanitize_callback'=>array($this, 'saveTKSettings')]);





    }

    /**
     * Call settings_fields function inside the form
     * param
     * settings group.
     */
    public function tkSettingCurrentPage()
    {
        $tk_cron_set_enable = esc_attr(get_option('tk_cron_set_enable'));
        $tk_cron_set_delete = esc_attr(get_option('tk_cron_set_delete'));

        $tk_cron_set_frequency = esc_attr(get_option('tk_cron_set_frequency'));

        $tk_cron_set_mix_name_file = esc_attr(get_option('tk_cron_set_mix_name_file'));

        $tk_cron_set_sendsms = esc_attr(get_option('tk_cron_set_sendsms'));
        $tk_cron_set_sms_sender = esc_attr(get_option('tk_cron_set_sms_sender'));
        $tk_cron_set_sms_number = esc_attr(get_option('tk_cron_set_sms_number'));
        $tk_cron_set_sms_username = esc_attr(get_option('tk_cron_set_sms_username'));
        $tk_cron_set_sms_password = esc_attr(get_option('tk_cron_set_sms_password'));

        $tk_cron_set_sendmail = esc_attr(get_option('tk_cron_set_sendmail'));
        $tk_cron_set_mail_email = esc_attr(get_option('tk_cron_set_mail_email'));

        $tk_cron_set_smtp = esc_attr(get_option('tk_cron_set_smtp'));
        $tk_cron_set_smtp_host = esc_attr(get_option('tk_cron_set_smtp_host'));
                
        $tk_cron_set_smtp_port = esc_attr(get_option('tk_cron_set_smtp_port'));
        $tk_cron_set_smtp_user = esc_attr(get_option('tk_cron_set_smtp_user'));

        $tk_cron_set_smtp_password = esc_attr(get_option('tk_cron_set_smtp_password'));

        $tk_cron_set_smtp_encryption = esc_attr(get_option('tk_cron_set_smtp_encryption'));

        ?>

<br>
<?php settings_errors(); ?>

<br>

    <div class="pure-g">
           <h2 class="top_desc center-h2"> Statistics From Last Scan </h2>
    </div>

    <div class="flex-row">
      <div class="deleted_col">
                            
                    <h2>Deleted </h2>

                    <span class="pricing-table-price">
                        <?php echo number_format(get_option('tk_deletedf_count'), 2); 
        ?> <span> file(s)</span>
                    </span>
                
      </div>
      <div class="modified_col">
                           
                      <h2>Modified</h2>

                    <span class="pricing-table-price">
                        <?php echo number_format(get_option('tk_doctoredf_count'), 2);
        ?> <span>file(s)</span>
                    </span>
               
      </div>
      <div class="new_col">

                        
                    <h2>New </h2>

                    <span class="pricing-table-price">
                       <?php echo number_format(get_option('tk_newf_count'),2);
        ?>  <span>file(s)</span>
                    </span>
              
        
      </div>
      <div class="total_col">

                <h2>Total Files</h2>

                    <span class="pricing-table-price">
                        <?php echo number_format(get_option('tk_totalref_count'), 2);
        ?> <span> file(s)</span>
                    </span>
        
      </div>
      
    </div>


<br> <br>
 <div class="settings_form_tk">       
<form class="form pure-form pure-form-stacked" method="POST" action="options.php">
<?php settings_fields('tk_cronwp');
        ?>
<div class="panel-box">
<div class="panel-box-header">
<h4> Configure Automated file Tracker  </h4>
</div>
<div class="panel-box-body">

  <div class="flex-set-row">
     <div class="main-set">

          <div class="switch-field">
      <div class="switch-title">Enable File Track </div>
      <input type="radio" id="switch_enabletrack" name="tk_cron_set_enable" value="yes" <?php echo ($tk_cron_set_enable == 'yes') ? ' Checked' : '';
        ?> />
      <label for="switch_enabletrack">Yes</label>
      <input type="radio" id="switch_disabletrack" name="tk_cron_set_enable" value="no" <?php echo ($tk_cron_set_enable == 'no') ? ' Checked' : '';
        ?> />
      <label for="switch_disabletrack">No</label>
    </div>


    <div class="switch-field">
      <div class="switch-title"> Delete New Files </div>
      <input type="radio" id="switch_enabledelete" name="tk_cron_set_delete" value="yes" <?php echo ($tk_cron_set_delete == 'yes') ? ' Checked' : '';
        ?> />
      <label for="switch_enabledelete">Yes</label>
      <input type="radio" id="switch_disabledelete" name="tk_cron_set_delete" value="no" <?php echo ($tk_cron_set_delete == 'no') ? ' Checked' : '';
        ?> />
      <label for="switch_disabledelete">No</label>
    </div>
     <span> <span class="danger"> Not Recommended</span> <em> Empower Track Keeper to delete new files on discovery </em> </span> 

     
     <br><br>
    <div class="switch-field">
      <div class="switch-title"> Quarantine new files  <span> <em>Enable Track Keeper to quarantine new files on discovery </em> </span></div>
      <input type="radio" id="switch_enablequarantine" name="tk_cron_set_mix_name_file" value="yes" <?php echo ($tk_cron_set_mix_name_file == 'yes') ? ' Checked' : '';
        ?> />
      <label for="switch_enablequarantine">Yes</label>
      <input type="radio" id="switch_disablequarantine" name="tk_cron_set_mix_name_file" value="no" <?php echo ($tk_cron_set_mix_name_file == 'no') ? ' Checked' : '';
        ?> />
      <label for="switch_disablequarantine">No</label>
    </div>

     <div class="form-group">
   <label for="tk_cron_set_frequency">File Check Frequency </label>
        <select name="tk_cron_set_frequency" class="tk-input">  
         <option value ="hourly" <?php if($tk_cron_set_frequency == "hourly") echo " selected";  ?>> <?php _e('   Every Hour', 'wp-track-keeper'); ?> </option>
<option value ="twicedaily"  <?php if($tk_cron_set_frequency == "twicedaily") echo " selected";  ?>>  <?php _e('Twice Daily', 'wp-track-keeper'); ?> </option>
<option value ="daily" <?php if($tk_cron_set_frequency == "daily")  echo " selected"; ?>> <?php _e('Once 
a day', 'wp-track-keeper'); ?> </option>

        </select>
      </div>


<!-- sms start -->
  <h3 class="mid-setting-title"> SMS Settings </h3>
            <div class="switch-field">
      <div class="switch-title"> Enable SMS  <span>  </span></div>
      <input type="radio" id="switch_doemail" name="tk_cron_set_sendsms" value="yes" <?php echo ($tk_cron_set_sendsms == 'yes') ? ' Checked' : '';
        ?>/>
      <label for="switch_doemail">Yes</label>
      <input type="radio" id="switch_dontemail" name="tk_cron_set_sendsms" value="no" <?php echo ($tk_cron_set_sendsms == 'no') ? ' Checked' : '';
        ?> />
      <label for="switch_dontemail">No</label>
    </div>


<div class="form-group">
        <label for="tk_cron_set_sms_sender">Sender Name</label>
        <input name="tk_cron_set_sms_sender" maxlength="11" class="tk-input" value="<?php echo $tk_cron_set_sms_sender;
        ?>" type="text" placeholder="Sender">
      </div>

<div class="form-group">
        <label for="tk_cron_set_sms_number"> +country-code phone number e.g +2348068598393</label>
        <input name="tk_cron_set_sms_number" class="tk-input" value="<?php echo $tk_cron_set_sms_number;
        ?>" type="tel" placeholder="phone">
      </div>
    



        <div class="form-group">
        <label for="tk_cron_set_sms_username">ZillionText Username</label>
        <input name="tk_cron_set_sms_username" class="tk-input" value="<?php echo $tk_cron_set_sms_username;
        ?>" type="text" placeholder="Username">
      </div>

      <div class="form-group">
        <label for="tk_cron_set_sms_password">ZillionText Password</label>
        <input name="tk_cron_set_sms_password" class="tk-input" value="<?php echo $tk_cron_set_sms_password;
        ?>" type="password" placeholder="ZillionText Password">
      </div>



      </div>
     <div class="email-set">

        <h3 class="mid-setting-title"> Email Settings </h3>
                 <div class="switch-field">
      <div class="switch-title">Enable Email Alert  <span> <em>Enable Track Keeper to send you email</em> </span></div>
      <input type="radio" id="switch_doemail_tsend" name="tk_cron_set_sendmail" value="yes" <?php echo ($tk_cron_set_sendmail == 'yes') ? ' Checked' : '';
        ?>/>
      <label for="switch_doemail_tsend">Yes</label>
      <input type="radio" id="switch_dontemail_tsend" name="tk_cron_set_sendmail" value="no" <?php echo ($tk_cron_set_sendmail == 'no') ? ' Checked' : '';
        ?> />
      <label for="switch_dontemail_tsend">No</label>
    </div>
     
     <div class="form-group">
     <label for="tk_cron_set_mail_email"> Email (Email address for receiving alert)</label>
        <input type ="email" class="tk-input" name="tk_cron_set_mail_email" value ="<?php echo $tk_cron_set_mail_email;
        ?>">
      </div>


      <div class="switch-field">
      <div class="switch-title"> Use My SMTP Server  </div>
      <input type="radio" id="switch_doemailsmtp" name="tk_cron_set_smtp" value="yes" <?php echo ($tk_cron_set_smtp == 'yes') ? ' Checked' : '';
        ?>/>
      <label for="switch_doemailsmtp">Yes</label>
      <input type="radio" id="switch_dontemailsmtp" name="tk_cron_set_smtp" value="no" <?php echo ($tk_cron_set_smtp == 'no') ? ' Checked' : '';
        ?> />
      <label for="switch_dontemailsmtp">No</label>
    </div>
    <span> <em>SMTP is for advanced users, if you are not so sure, you can leave this and use the default Wordpress email</em> </span>

        <div class="form-group">
        <label for="tk_cron_set_smtp_host">Host </label>
        <input name="tk_cron_set_smtp_host" class="tk-input" value="<?php echo $tk_cron_set_smtp_host;
        ?>" type="text" placeholder="Host">  
      </div>

    <div class="form-group">
   <label for="tk_cron_set_smtp_port">Port</label>
        <input name="tk_cron_set_smtp_port" class="tk-input" value="<?php echo $tk_cron_set_smtp_port;
        ?>" type="number" placeholder="Port">  
      </div>



 <div class="form-group">
   <label for="tk_cron_set_smtp_encryption">Encyrption</label>
        <select name="tk_cron_set_smtp_encryption" class="tk-input">  
          <option value="ssl"<?php if($tk_cron_set_smtp_encryption =='ssl'): ?> selected <?php endif; ?>> SSL </option>
          <option value="tls"<?php if($tk_cron_set_smtp_encryption =='tls'): ?> selected <?php endif; ?>> TLS </option>
          <option value="none"<?php if($tk_cron_set_smtp_encryption =='none'): ?> selected <?php endif; ?>> None </option>

        </select>
      </div>

      <div class="form-group">
   <label for="tk_cron_set_smtp_user">SMTP User</label>
        <input name="tk_cron_set_smtp_user" class="tk-input" value="<?php echo $tk_cron_set_smtp_user;
        ?>" type="text" placeholder="SMTP User">  
      </div>

        <div class="form-group">
   <label for="tk_cron_set_smtp_password">SMTP Password</label>
        <input  name="tk_cron_set_smtp_password" class="tk-input" value="<?php echo $tk_cron_set_smtp_password;
        ?>" type="password" placeholder="SMTP Password">  
      </div>






      </div>
    

  </div>

       

   





</div>
<div class="panel-box-footer">
<button type="submit" class="pure-button pure-button-default"><i class="fa fa-save"> </i> <br/>Save</button>

</div>
</div>
</form>
</div>
<hr>

<?php

    }

    public function saveTKSettings($var)
    {
        return sanitize_text_field($var);
    }

    public function saveTKEmailSettings($email)
    {
        if(!$this->isEmail($email)){
          add_settings_error( $email, "invalid_{$email}", 'Invalid email: Email address for receiving alert is required' );
    }
       return sanitize_email($email);
    }

        

    
    public function isEmail($email)
     {
       if(filter_var($email, FILTER_VALIDATE_EMAIL)){
          return true;
       }
       return false;
     }

    public function saveTKNumberSettings($port)
    {
        return (int) $port;
    }



    
}// close class


new TKConfigurationAdmin();

?>