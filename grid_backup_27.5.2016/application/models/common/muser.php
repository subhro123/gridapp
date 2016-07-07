<?php
Class Muser extends CI_Model
{
 
function checkEmail($table,$val)
	 {
		   $this -> db -> select('*');
		   $this -> db -> from($table);
		   $this -> db -> where('email_address', $val);
		   $query = $this->db->get();
		   //echo $this->db->last_query();
		   return $query->num_rows ;
		   
	 }


	 
}
?>