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
        $query = "SELECT id, name FROM {$this->table} WHERE active = 1";
        $query .= " ORDER BY name";
        $statuses = $db->queryData($query);
        foreach($statuses as $s)
        {
            $label = $c['name'];
            $value = $c['id'];
            if($selected)
            {
                $check = ($value == $selected)? "selected='selected'" : "";
            }
            $ret_string .= "<option $check value='$value'>$label</option>";
        }
        return $ret_string;
    }
}
?>