<?php
/**
 * TK : MOMENT
 */
if (!defined('ABSPATH')) {
    exit;
}
class TKTrackKeeperLoad
{
    
    public function __construct()
    {

        add_action('admin_menu', array($this, 'addmenuPage'));
        add_action('admin_footer', array($this, 'addMilitaryAjaxCall'));
        add_action('wp_ajax_saveNewReferenceStatePHP', array($this, 'saveNewReferenceStatePHP'));

        add_action('wp_ajax_killSuspectedInfectedFilePHP', array($this, 'killSuspectedInfectedFilePHP'));
        add_action('wp_ajax_quarantSuspectedInfectedFilePHP', array($this, 'quarantSuspectedInfectedFilePHP'));


    }

    public function addmenuPage()
    {
      
        add_submenu_page('trackkeeper_nokidnapping_nophishing', 'Review and Manage Track Keeper and Files ', 'Manage Tracked Files', 'manage_options', 'tk_military_action', array($this, 'tkMilitaryActionCurrentPage'));
    }

    public function tkMilitaryActionCurrentPage()
    {
        require_once plugin_dir_path(dirname(__FILE__)).'classes/TKTracker.php';
        require_once plugin_dir_path(dirname(__FILE__)).'classes/TKReferenceState.php';
        ?>

<div class="horizontal-zine"></div>
 <div class="flex-set-row">
  <div class="one_first"> 
   <h4 class=""> Load files within your wordpress installation folder. Inspect the files and set "Reference state". </h4>
   <span><em>Reference state is the best state of your files, the way you want your files to remain until next update.</em></span> 
           <div class="big-button-box">
            <a href="<?php echo admin_url();?>/admin.php?page=tk_military_action&tkloadstate=yes&reqtoken=<?php echo wp_create_nonce('tk_secure_link'); ?>" class="pure-button-success button-xlarge pure-button loadbtn">Load all Files</a>
        </div>

  </div>
   <div class="one_first">

      <div class="files-compare">
  <h4> Compare files </h4>
  <span> Ensure that you have a saved reference file before comparing files. </span>
<div class="big-button-box">
            <a href="<?php echo admin_url(); ?>/admin.php?page=tk_military_action&tkcomparestate=yes&reqtoken=<?php echo wp_create_nonce('tk_secure_link'); ?>" class="pure-button-success button-xlarge pure-button loadbtn">Compare   Files</a>
        </div>

  </div>


    </div>

 </div>





<div class="row-plan-view">
<?php
if (isset($_GET)) {
    if (isset($_GET['tkcomparestate'])) {
        
        if(!wp_verify_nonce( sanitize_text_field($_GET['reqtoken']), 'tk_secure_link')){
            wp_die('Could not load page: This page has expired');
        }


        if (sanitize_text_field($_GET['tkcomparestate']) == 'yes') {
            $nktracker = new TKTracker();
            $nkchoosen = new TKReferenceState();

            $scanreport = $nktracker->doCompareofFiles(ABSPATH);
            if (is_array($scanreport)) {
                if (isset($scanreport['newimplants'])) {
                    ?>
               
               <div class="col-5050">
    <h2 class="bar-h2-new"> New  ( <?php echo count($scanreport['newimplants']);
                    ?> File(s))</h2>
<table class="pure-table t-perfect-width">
    <thead>
        <tr>
           <th>SN</th>
            <th>File Name</th>
            <th>File Size </th>
            <th>Last Modified </th>
            <th>Delete </th>
            <th> Quarantine </th>

        </tr>
    </thead>
    <tbody>

                    <?php
                    if (!empty($scanreport['newimplants'])) {
                        $i = 1;
                        foreach ($scanreport['newimplants'] as $wpfile) {
                            ?>
                    <?php if (($i % 2) == 0) {
    echo '<tr class="pure-table-odd">';
} else {
    echo '<tr>';
}
                            ?>

            <td> <?php echo $i;
                            ?></td>
            
            <td><?php echo $wpfile['filelocation'];
                            ?></td>
             <td><?php echo $wpfile['filesize'] / 1000;
                            ?> kb</td>
              <td><?php echo  TKTracker::showTime($wpfile['lastmodified']);
                            ?></td>
            <td> <button id="killdelsuspinffile" class="pure-button-error btn-er pure-button" data-idhrefkillfile="<?php echo $wpfile['filelocation'];
                            ?>"> <i class="fa fa-trash"> </i> </button> </td>

          <td> <button id="quarantinedfilsusp" class="pure-button-warning btn-warning pure-button" data-idfilequarantine="<?php echo $wpfile['filelocation'];
                            ?>"> <i class="fa fa-ban"> </i> </button> </td>

        </tr>
<?php

++$i;
                        }
                    } else {
                        echo '<tr> <td colspan="5"> No record found, No new files have been added. Good Job! </td> </tr>';
                    }

                    echo '        
    </tbody>
</table>

    </div>
    <hr/>';
                }
// START DELETED FILES SHOW CASE

                if (isset($scanreport['deletedfiles'])) {
                    ?>
               
               <div class="col-5050">
    <h2 class="bar-h2-danger"> Deleted  ( <?php echo count($scanreport['deletedfiles']);
                    ?> File(s))</h2>
<table class="pure-table t-perfect-width">
    <thead>
        <tr>
            <th>SN</th>
            
            <th>File Name </th>
            <th>File Size </th>
            <th>Last Modified </th>
            <th>Action </th>

        </tr>
    </thead>
    <tbody>

                    <?php
                    if (!empty($scanreport['deletedfiles'])) {
                        $i = 1;
                        foreach ($scanreport['deletedfiles'] as $wpfile) {
                            ?>
                    <?php if (($i % 2) == 0) {
    echo '<tr class="pure-table-odd">';
} else {
    echo '<tr>';
}
                            ?>

            <td> <?php echo $i;
                            ?></td>
          
           
            <td><?php echo $wpfile['filelocation'];
                            ?></td>
             <td><?php echo $wpfile['filesize'] / 1000;
                            ?> kb</td>
              <td><?php echo  TKTracker::showTime($wpfile['lastmodified']);
                            ?></td>
          
        </tr>
<?php

++$i;
                        }
                    } else {
                        echo '<tr> <td colspan="5"> No record found, No files was deleted from the watch list. Good Job! </td> </tr>';
                    }

                    echo '        
    </tbody>
</table>

    </div>
    <hr/>';
                }

// STOP DELETED FILE SHOWCASE

            if (isset($scanreport['doctored'])) {
                ?>
               
               <div class="col-5050">
    <h2 class="bar-h2-mod"> Modified Files  ( <?php echo count($scanreport['doctored']);
                ?> File(s))</h2>
<table class="pure-table t-perfect-width">
    <thead>
        <tr>
            <th>SN</th>
            <th>File Name</th>
            <th>File Size </th>
            <th>Last Modified </th>
            <th>Delete </th>
             <th>Quarantine</th>

        </tr>
    </thead>
    <tbody>

                    <?php
                    if (!empty($scanreport['doctored'])) {
                        $i = 1;
                        foreach ($scanreport['doctored'] as $wpfile) {
                            ?>
                    <?php if (($i % 2) == 0) {
    echo '<tr class="pure-table-odd">';
} else {
    echo '<tr>';
}
                            ?>

            <td> <?php echo $i;
                            ?></td>
        
            <td><?php echo $wpfile['filelocation'];
                            ?></td>
             <td><?php echo $wpfile['filesize'] / 1000;
                            ?> kb</td>
              <td><?php echo  TKTracker::showTime($wpfile['lastmodified']);
                            ?></td>
            <td> <button id="killdelsuspinffile" class="pure-button-error btn-er pure-button" data-idhrefkillfile="<?php echo $wpfile['filelocation'];
                            ?>"> <i class="fa fa-trash"> </i> </button> </td>
             <td> <button id="quarantinedfilsusp" class="pure-button-warning btn-warning pure-button" data-idfilequarantine="<?php echo $wpfile['filelocation'];
                            ?>"> <i class="fa fa-ban"> </i> </button> </td>
        </tr>
<?php

++$i;
                        }
                    } else {
                        echo '<tr> <td colspan="5"> No record found, No was modified. Good Job! </td> </tr>';
                    }

                echo '        
    </tbody>
</table>

    </div>
    <hr/>';
            }
            }
        }
    }
}
        ?>

</div>



<?php if (isset($_GET)) {
    if (isset($_GET['tkloadstate'])) {
        if(!wp_verify_nonce( sanitize_text_field($_GET['reqtoken']), 'tk_secure_link')){
            wp_die('Could not load page: This page has expired');
        }

        if (sanitize_text_field($_GET['tkloadstate']) == 'yes') {
            $nktracker = new TKTracker();
            $nkchoosen = new TKReferenceState();
            ?>
       <?php $allwpfiles = $nktracker->getAllfiles(ABSPATH);

            $wpstoredreference = $nkchoosen->getAllChoosen();

            ?>
  <div class="row-plan-view">
  <div class="col-5050">
    <h2 class="bar-h2"> Reference State ( <?php echo count($wpstoredreference);
            ?> Files)</h2>
<table class="pure-table t-perfect-width">
    <thead>
        <tr>
            <th>File Name</th>
            <th>File Size </th>
            <th>Last Modified </th>

        </tr>
    </thead>
    <tbody>
        <?php 
    if (!empty($wpstoredreference)) {
        $i = 1;
        foreach ($wpstoredreference as $wpfile) {
            ?>
<?php if (($i % 2) == 0) {
    echo '<tr class="pure-table-odd">';
} else {
    echo '<tr>';
}
            ?>
           
            <td><?php echo $wpfile->filelocation;
            ?></td>
             <td> <?php echo($wpfile->filesize / 1000);
            ?> kb</td>
              <td> <?php echo TKTracker::showTime($wpfile->lastmodified);
            ?> </td>
        </tr>
<?php

++$i;
        }
    } else {
        echo '<tr> <td colspan="5"> No record found, please create reference state.  </td> </tr>';
    }

            ?>   

        
    </tbody>
</table>

    </div>
    <hr/>
    <div class="col-5050"> <h2 class="bar-h2">List of current Wordpress files  (<?php echo count($allwpfiles); ?> files)   </h2> 
<table class="pure-table">
    <thead>
        <tr>
            <th>SN</th>
            <th>File Name</th>
            <th>File Size </th>
            <th>Last Modified </th>
            <th>Action </th>


        </tr>
    </thead>
    <tbody>
    <?php 
    if (!empty($allwpfiles)) {
        $i = 1;
        foreach ($allwpfiles as $wpfile) {
            ?>
<?php if (($i % 2) == 0) {
    echo '<tr class="pure-table-odd">';
} else {
    echo '<tr>';
}
            ?>

            <td> <?php echo $i;
            ?></td>
           
           
            <td><?php echo $wpfile['filelocation'];
            ?></td>
             <td><?php echo $wpfile['filesize'] / 1000;
            ?> kb</td>
              <td><?php echo  TKTracker::showTime($wpfile['lastmodified']);
            ?></td>
            <td> <button id="killdelsuspinffile" class="pure-button-error btn-er pure-button" data-idhrefkillfile="<?php echo $wpfile['filelocation'];
            ?>"> <i class="fa fa-trash"> </i> </button> </td>
        </tr>
<?php

++$i;
        }
    } else {
        echo '<tr> <td colspan="5"> No record found, unbelievable contact your server administrator.  </td> </tr>';
    }
            ?> 
        
    </tbody>
</table>

<div id="nkresponderfileref"> </div>
<div class="btnsaveref">
<button id="nksaverefstatebtn" class="pure-button pure-button-primary">Save as "Reference State"</button>
</div>
    </div>
</div>




<?php

        }
    }
}
        ?>
  
 
 <hr/>


<?php

    }

  public function addMilitaryAjaxCall()
  {  ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){

$('#nksaverefstatebtn').on('click', function(ev){
   ev.preventDefault();
 
   var conf = confirm('Are you sure you want to save this as reference');
   if(conf){
   $('#nksaverefstatebtn').html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Saving...</span>');
       var saverefdata = {
          _ajax_nonce: "<?php echo wp_create_nonce('tk_ajax_secret_token_var');?>",
         token:'FC3FD0AF-BEC0-43B6-B0C7-F7F591135B24',
         action:'saveNewReferenceStatePHP',
       }
       var saveref = $.post(ajaxurl, saverefdata);
       saveref.success(function(report){
    $('#nksaverefstatebtn').html('Completed');
       if(isNaN(report)){
         $('#nkresponderfileref').html('<div class="alert-error"> '+report+'</div>');
       }
       else{
         $('#nkresponderfileref').html('<div class="alert-success"> Total of '+report+' files saved as reference. Good Job!</div>');

       }
        //alert(report);
       });
   }
});

$('body').on('click', '#killdelsuspinffile', function(ev){
          ev.preventDefault();
          var fileline= $(this).closest('tr');
          var deldata = {
              _ajax_nonce: "<?php echo wp_create_nonce('tk_ajax_secret_token_var');?>",
              token:'E13C48E4-DF60-4772-B047-FD8920A15AEA',
              filelocation: $(this).data('idhrefkillfile'),
              action:'killSuspectedInfectedFilePHP'
          }

          var doconf = confirm('Are you sure you want to delete this file?: Note File deletion my cause damage to your website. If you are not sure of resultant effect of your action, click "Cancel" to discontinue. Click OK to proceed');
          if(doconf){
            var senddelfile = $.post(ajaxurl, deldata);
             senddelfile.success(function(report){
                  // alert(report);
                   if(report =='success'){
                    fileline.fadeOut();
                   }
             });
          }
});

$('body').on('click', '#quarantinedfilsusp', function(ev){
          ev.preventDefault();
          var fileline= $(this).closest('tr');
          var deldata = {
              _ajax_nonce: "<?php echo wp_create_nonce('tk_ajax_secret_token_var');?>",

              token:'051CE73E-7DEF-46BF-9340-C71CCB7E7010',
              filelocation: $(this).data('idfilequarantine'),
              action:'quarantSuspectedInfectedFilePHP'
          }

          var doconf = confirm('Are you sure you want to quarantine  this file?  This Action will rename the file to a text file.');
          if(doconf){
            var senddelfile = $.post(ajaxurl, deldata);
             senddelfile.success(function(report){
                   //alert(report);
                   if(report =='success'){
                    fileline.fadeOut();
                   }
             });
          }
});



});
        
    </script>


 <?php
  }

    public function saveNewReferenceStatePHP()
    {
        check_ajax_referer( 'tk_ajax_secret_token_var', '_ajax_nonce' );
        // $curr_admin  = wp_get_current_user();
         if(!current_user_can( 'manage_options' )){
           wp_die('Not accessible');

         }

        require_once plugin_dir_path(dirname(__FILE__)).'classes/TKTracker.php';
        $nktracker = new TKTracker();
        if (isset($_POST)) {
            if (isset($_POST['token'])) {


                if (sanitize_text_field($_POST['token']) == 'FC3FD0AF-BEC0-43B6-B0C7-F7F591135B24') {
                    $report = $nktracker->getAllfiles(ABSPATH, 1);
                    echo $report;
                    wp_die();
                }
            }
        }
    }

    public function killSuspectedInfectedFilePHP()
    {
        if(!current_user_can( 'manage_options' )){
           wp_die('Not accessible');

         }
      check_ajax_referer( 'tk_ajax_secret_token_var', '_ajax_nonce' );
        if (isset($_POST)) {
            if (isset($_POST['token'])) {
                if (sanitize_text_field($_POST['token'] )== 'E13C48E4-DF60-4772-B047-FD8920A15AEA') {
                    $file = sanitize_text_field($_POST['filelocation']);
                    
                    if ($file != '') {
                        if (file_exists($file)) {
                            unlink($file);
                            echo 'success';
                            wp_die();
                        }
                    } else {
                        echo 'failed';
                        wp_die();
                    }
                }
            }
        }
    }
    public function quarantSuspectedInfectedFilePHP()
    {
        if(!current_user_can( 'manage_options' )){
           wp_die('Not accessible');

         }
      check_ajax_referer( 'tk_ajax_secret_token_var', '_ajax_nonce' );
        if (isset($_POST)) {
            if (isset($_POST['token'])) {
                if (sanitize_text_field($_POST['token']) == '051CE73E-7DEF-46BF-9340-C71CCB7E7010') {

                    $file = sanitize_text_field($_POST['filelocation']);
               

                    if ($file != '') {
                        if (file_exists($file)) {
                            //unlink($file);
                            rename($file, $file.'_tk_sus.txt');
                            echo 'success';
                            wp_die();
                        }
                    } else {
                        echo 'failed';
                        wp_die();
                    }
                }
            }
        }
    }
}// close class

 new TKTrackKeeperLoad();

?>