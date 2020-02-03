<?php

 /**
  * Jobstatus Class
  *

  * @author     Mark Solly <mark@baledout.com.au>
  */

class Jobstatus extends Model{
    /**
      * Table name for this & extending classes.
      *
      * @var string
      */
    public $table = "job_status";

    public function getSelectStatus( $selected = false )
    {
        $db = Database::openConnection();
        $check = "";
        $ret_string = "";
        $query = "SELECT id, name FROM {$this->table} ORDER BY name";
        $statuses = $db->queryData($query);
        foreach($statuses as $s)
        {
            $label = $s['name'];
            $value = $s['id'];
            if($selected)
            {
                $check = ($value == $selected)? "selected='selected'" : "";
            }
            $ret_string .= "<option $check value='$value'>$label</option>";
        }
        return $ret_string;
    }

    public function addStatus($data)
    {
        $db = Database::openConnection();
        return false;
    }

    public function checkStatusNames($name, $current_name)
    {
        $db = Database::openConnection();
        $name = strtoupper($name);
        $current_name = strtoupper($current_name);
        $q = "SELECT name FROM {$this->table}";
        $rows = $db->queryData($q);
        $valid = 'true';
        foreach($rows as $row)
        {
        	if($name == strtoupper($row['name']) && $name != $current_name)
        	{
        		$valid = 'false';
        	}
        }
        return $valid;
    }
}
?>