<?php


/**
 * 
 */
require_once 'TKTracker.php';
require_once 'TKReferenceState.php';
require_once 'TKMailBoss.php';
require_once 'WPZillionText.php';


class TKScheduleRunner
{
    
    public function makeTrack()
    {

         if (get_option('tk_cron_set_enable') == 'no') {
            return;
        }

        $tktrackObj = new TKTracker();
        $tkrefObj = new TKReferenceState();
        $refisavailable = $tkrefObj->checkReferenceExist();

         $action_note = '';
        if ($refisavailable) {
            $scanreport = $tktrackObj->doCompareofFiles(ABSPATH);
            if (is_array($scanreport['newimplants'])) {
                if (get_option('tk_cron_set_mix_name_file') == 'yes') {
                    $action_note= $scanreport['countnewf'].' file(s) quarantined';
                    foreach ($scanreport['newimplants'] as $qfile) {
                       
                       if(!strpos($qfile['filelocation'], 'plugins'.DIRECTORY_SEPARATOR.'wp-track-keeper')){
                        rename($qfile['filelocation'], $qfile['filelocation'].'_tk_suspected.txt');
                       }

                        
                    }
                }
                if (get_option('tk_cron_set_delete') == 'yes' && get_option('tk_cron_set_mix_name_file') == 'no') {
                    $action_note= $scanreport['countnewf'].' file(s) deleted';
                    foreach ($scanreport['newimplants'] as $qfile) {
                        if(!strpos($qfile['filelocation'], 'plugins'.DIRECTORY_SEPARATOR.'wp-track-keeper')){
                        unlink($qfile['filelocation']);
                    }
                    }
                }
            }
             
            $scanreport['action'] = $action_note;
            return $scanreport;
        } else {
          return   ['action' =>'NoRef'];
        }
    }



      public function runReport()
      {
            $mailer = new TKMailBoss();
            $sms = new WPZillionText();

            $report = $this->makeTrack();

          if(!is_array($report)){
            return;
          }
          if($report['action'] =='NoRef'){
               $content = '<table style="width: 100%"> <tr> <td colspan="3">There is no "file reference state" saved in your website, '."\n".' Please save reference state </td></tr></table>';
               $summary = 'No reference state found';

             
               $mailer->post($content, $summary);
               if(get_option('tk_cron_set_sendsms') =='yes'){
                $sms->send('No Reference state found, please set reference state');

               }
               return true;
          }

          $html = '';
          $summary = '';

            $lastscann = get_option('tk_previous_scan_date');
            update_option('tk_previous_scan_date', date('Y-m-d H:i:s'));

           
           if(is_array($report['newimplants']) && count($report['newimplants']) >0){
               $summary .= $report['countnewf'].' new file(s) added ';

                 $html .='<tr> <th colspan="3"> New Files </th></tr>
                           <br> <tr style="border:1px solid #00aff0; color:#00aff0;">
                            <th> File</th>   <th> Size </th> <th> Added on </th>
                                                </tr>';
                foreach($report['newimplants'] as $wpfile){
                    $html .= '<tr style="padding:10px; border:1px solid #00aff0;color:#000">
                    <td style="color:#908e8f;padding:10px;line-height:1.6">'. $wpfile['filelocation'].'</td><td style="color:#908e8f">'. ($wpfile['filesize'] / 1000).' kb</td>
              <td style="color:#908e8f">'.TKTracker::showTime($wpfile['lastmodified']).'</td> </tr>';

                }                                
           }




            if(is_array($report['doctored']) && count($report['doctored']) >0){
                if($summary ==''){
                   $summary .= $report['countdoctored'].' modified file(s) ';
                }
                else{
                   $summary .= ', '.$report['countdoctored'].' modified file(s) ';
                }

                 $html .='<tr> <th colspan="3"> Modified Files </th></tr>
                           <br> <tr style="border:1px solid #00aff0;color:#00aff0;">
                            <th> File</th>   <th> Size </th> <th> Modified on </th>
                                                </tr>';
                
                foreach($report['doctored'] as $wpfile){
                    $html .= '<tr style="padding:10px; border:1px solid #00aff0; color:#000;">
                    <td style="color:#908e8f;padding:10px;line-height:1.6">'. $wpfile['filelocation'].'</td>
                    <td style="color:#908e8f;padding:10px;line-height:1.6">'. ($wpfile['filesize'] / 1000).' kb</td>
              <td style="color:#908e8f;padding:10px;line-height:1.6">'.TKTracker::showTime($wpfile['lastmodified']).'</td> </tr>';

                }                                
           }


           if(is_array($report['deletedfiles']) && count($report['deletedfiles']) >0){
            if($summary ==''){
                   $summary .= $report['countdeleted'].' deleted file(s) ';
                }
                else{
                   $summary .= ' and '.$report['countdeleted'].' deleted file(s)  since '.$lastscann;
                }

                 $html .='<tr> <th colspan="3"> Deleted Files </th></tr>
                           <br> <tr style="border:1px solid #00aff0; color:#00aff0;">
                            <th> File</th>   <th> Size </th> <th> Last modified </th>
                                                </tr>';
                foreach($report['deletedfiles'] as $wpfile){
                    $html .= '<tr style="padding:10px; border:1px solid #00aff0;color:#000">
                    <td style="color:#908e8f;padding:10px;line-height:1.6">'. $wpfile['filelocation'].'</td>
                    <td style="color:#908e8f;padding:10px;line-height:1.6">'. ($wpfile['filesize'] / 1000).' kb</td>
              <td style="color:#908e8f;padding:10px;line-height:1.6">'.TKTracker::showTime($wpfile['lastmodified']).'</td> </tr>';

                }                                
           }

           
           if($html !=''){

             if(get_option('tk_cron_set_sendsms') =='yes'){
                $sms->send($summary);

               }
              $content = '<table style="width: 100%">'.$html.'</table>';
              $mailer->post($content, $summary);
           }



      }



}
