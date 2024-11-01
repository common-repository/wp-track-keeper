<?php

/**
 * 
 */
@ini_set('max_execution_time', '360');
require_once 'TKReferenceState.php';
class TKTracker
{
    public $keeper;
    public function __construct()
    {
        $this->keeper = new TKReferenceState();
    }

    public function getAllfiles($dirlocation = '', $store = '')
    {
        if ($dirlocation == '') {
            return false;
        }

        if ($store) {
            if ($store == 1) {
                $this->keeper->killReferenceState();
            }
        }
        $total = 0;
        $filedata = array();
        $supportedtype = array('html','php');
        $fileinfos = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dirlocation)
);
        foreach ($fileinfos as $pathname => $fileinfo) {
            if (!$fileinfo->isFile()) {
                continue;
            }
            $arg = explode('.', $pathname);
            $ext = array_pop($arg);
            if (in_array($ext, $supportedtype)) {
                if (file_exists($pathname) === true) {
                    $withpath = explode('\\', $pathname);
                    $fname = array_pop($withpath);

                    $id_file = md5($fname.$pathname);
                    if ($store) {
                        if ($store == 1) {
                            $this->keeper->addNewState($id_file, $fname, $pathname, filemtime($pathname), filesize($pathname));
                            ++$total;
                        }
                    }

                    $tfile = array(
                      'id_file' => $id_file,
                       'filename' => $fname,
                        'filelocation' => $pathname,
                        'lastmodified' => filemtime($pathname),
                         'filesize' => filesize($pathname),
                        );

                    $filedata[] = $tfile;
                }
            }
        }
        if ($store) {
            if ($store == 1) {
                update_option('tk_totalref_count', $total);

                return $total;
            }
        } else {
            return $filedata;
        }
    }

    public static function deleteBadFile($path)
    {
    }

    public static function showTime($tdate)
    {
        return date('F j, Y, g:i a', $tdate);
    }

    public function checkFileArray($files, $field, $value)
    {
        foreach ($files as $key => $file) {
            if ($file[$field] === $value) {
                return $files[$key];
            }
        }

        return false;
    }

    public function doCompareofFiles($path)
    {
        $newfilesare = array();
        $doctoredfiles = array();
        $returndata = array();
        if ($this->keeper->checkReferenceExist()) {
            $refe = $this->keeper->getAllChoosenAS();
            $curr = $this->getAllfiles($path);
            foreach ($curr as $curfile) {
                if ($this->keeper->checkFileIDExist($curfile['id_file'])) {
                    // echo $curfile['id_file'];
                    $matchedfiles = $this->checkFileArray($refe, 'id_file', $curfile['id_file']);
                    if (($matchedfiles['lastmodified'] != $curfile['lastmodified']) || ($matchedfiles['filesize'] != $curfile['filesize'])) {
                        $doctoredfiles[] = $matchedfiles;
                    }
                    continue;
                }

                $newfilesare[] = $curfile;
            }
            $returndata['newimplants'] = $newfilesare;
            $returndata['doctored'] = $doctoredfiles;

            update_option('tk_doctoredf_count', count($doctoredfiles));
            update_option('tk_newf_count', count($newfilesare));

            $returndata['countnewf'] = count($newfilesare);
            $returndata['countdoctored'] = count($doctoredfiles);
            $deletedfiles = [];
            foreach ($refe as $myref) {
                if (!$this->checkFileArray($curr, 'id_file', $myref['id_file'])) {
                    $deletedfiles[] = $myref;
                }
            }

            update_option('tk_deletedf_count', count($deletedfiles));
            $returndata['deletedfiles'] = $deletedfiles;
            $returndata['countdeleted'] = count($deletedfiles);

            return $returndata;
        } else {
            return $this->showNotExistReference();
        }
    }

    public function showNotExistReference()
    {
        ?>
         <div class="row-plan-view">
         <div class="col-5050">
          <div class="alert-error"> Please say reference state before comparing files!  </div>
         </div>
         </div>
    
    	<?php

    }
}
