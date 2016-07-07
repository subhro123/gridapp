<?php
Class MUser extends CI_Model
{
 function userlogin($username, $password)
	 {
		   $this -> db -> select('*');
		   $this -> db -> from('admin');
		   $this -> db -> where('username', $username);
		   $this -> db -> where('password', $password);
		
		   $query = $this->db-> get();
		   //echo $this->db->last_query();
		   //die();
		   if($query-> num_rows() == 1)
		   {
			 return $query->result();
		   }
		   else
		   {
			 return false;
		   }
	 }


 ///////////////////////////////// GET DYNAMIC MENU START /////////////////////////////////
 
		 public function getMenuSubMenuTree($id=NULL){
			//echo $id;
			$data = array();
			$data1 = array();
			//$data2 = array();
			$this->db->select("*");
			$this->db->from("menu");
			$this->db->where('type','back');		
			$this->db->where('status','1');
			$query = $this->db->get();
			//echo $this->db->last_query();
			$data = $query->result_array();
			
			foreach($data as $key=>$val){
			
						$this->db->select("*");
						$this->db->from("submenu");
						$this->db->where('menu_id',$val['id']);
						$this->db->where('type','back');		
						$this->db->where('status','1');
						$query = $this->db->get();
						//echo $this->db->last_query();
						$data[$key]['submenu'] = $query->result_array();
						
			
			}
		  
			//$data = $data1; 
			return $data;	 
			 
		 }
 
 
 ///////////////////////////////// GET DYNAMIC MENU END /////////////////////////////////
 
    public function userdetails($id=NULL){
				
			   $this->db->select('*');
			   $this->db->from('admin');
			   $this->db->where('id',$id);
			   $query = $this->db->get();
			   //echo $this->db->last_query();
			   return $query->row_array(); 
	}
	
	public function chkOldPassword($old_password){
				
			   $this->db->select('*');
			   $this->db->from('admin');
			   $this->db->where('id','1');
			    $this->db->where('role','admin');
			   $this->db->where('password',$old_password);
			   $query = $this->db->get();
			   //echo $this->db->last_query();
			   return $query->num_rows; 
				
	
	}
 }

?>
