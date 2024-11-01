<?php

/**
 TK Reference State
 */
if (!defined('ABSPATH')) {
    exit;
}
class TKReferenceState
{
    public $table;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix.'trackedfiles_reference_state';
    }

    public function addNewState($id_file, $filename, $filelocation, $lastmodified, $filesize)
    {
        global $wpdb;
        $create = $wpdb->insert($this->table, array('id_file' => $id_file, 'filename' => $filename, 'filelocation' => $filelocation, 'lastmodified' => $lastmodified, 'filesize' => $filesize), array('%s', '%s', '%s', '%s', '%s'));
        if ($create == 1) {
            return $wpdb->insert_id;
        }

        return false;
    }

    public function getFileByID($id_file)
    {
        global $wpdb;
        $sql = 'SELECT * FROM '.$this->table.' WHERE id_file ='.$id_file;

        return $wpdb->get_row($sql, OBJECT);

        return false;
    }

    public function checkFileIDExist($id_file)
    {
        $idstate = null;
        global $wpdb;
        $sql = 'SELECT id_state FROM '.$this->table.' WHERE id_file ="'.$id_file.'"';

        $idstate = $wpdb->get_row($sql, OBJECT);

        if(is_object($idstate)){
            if ((int) $idstate->id_state > 0) {
            return true;
        }
        }
        

        return false;
    }

    public function countFiles()
    {
        global $wpdb;

        $sql = 'SELECT COUNT(id_state) FROM '.$this->table;

        $count = $wpdb->get_var($sql);
        if ($count) {
            return $count;
        }

        return false;
    }

    public function getAllChoosen($lmt = '')
    {
        global $wpdb;
        $sql = 'SELECT * FROM '.$this->table.' ORDER BY id_state ASC '.$lmt;
        $rows = $wpdb->get_results($sql, OBJECT);

        return $rows;

        return false;
    }

    public function getAllChoosenAS($lmt = '')
    {
        global $wpdb;
        $sql = 'SELECT * FROM '.$this->table.' ORDER BY id_state ASC '.$lmt;
        $rows = $wpdb->get_results($sql, ARRAY_A);

        return $rows;

        return false;
    }

    public function deleteFile($id)
    {
        if ($id == '') {
            return false;
        }
        global $wpdb;
        $del_maker = $wpdb->delete($this->table, array('id_file' => $id), array('%s'));
        if ($del_maker == 1) {
            return true;
        }

        return false;
    }

    public function killReferenceState()
    {
        global $wpdb;
        $wpdb->query('TRUNCATE TABLE '.$this->table);

        return true;
    }

    public function checkReferenceExist()
    {
        if ($this->countFiles()) {
            return true;
        } else {
            return false;
        }
    }
} //class ends

// $nkchoosenstat = new TKReferenceState();

