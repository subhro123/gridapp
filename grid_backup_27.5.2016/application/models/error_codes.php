<?php

class Error_codes extends CI_Model {
    protected $CI;
    public $codes;

    function __construct() {
        parent::__construct();
        $this->CI =& get_instance();
        
       $sql = "SELECT e.*, v.* FROM errorcodes e LEFT JOIN verbiages v ON v.id=e.verbiage_id";
       $query = $this->CI->db->query($sql);
       foreach($query->result() as $row) {
                $this->codes[$row->short_name] = $row;
        }
            //echo "<pre>";
            //print_r($this->codes); 
        
    }

    public static function getErrorByCode($code) {
        $query = get_instance()->db->where(array("code" => $code));
        return $query->row();

    }
    public function getErrorByShort($short_name, $category = false, $context = false) {
        return $this->codes[$short_name];
        $sql = "SELECT ec.code, ec.name, ec.context, ec.level, ec.category, ec.short_name, ec.verbiage_id, ec.restriction, v.content FROM error_codes ec LEFT JOIN verbiages v ON v.id=ec.verbiage_id WHERE ec.short_name = ?";
        $params[] = $short_name;
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        if ($context) {
            $sql .= " AND context = ?";
            $params[] = $context;
        }
        //echo $sql;
        $query = $this->CI->db->query($sql, $params);
        return $query->row();
    }
}
?>