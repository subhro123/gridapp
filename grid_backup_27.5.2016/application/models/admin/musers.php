<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Musers extends CI_Model
{

	 function getCountUsers($search = "", $order = "")
	{
	
		$data = array();
		$this->db->select('count(*) as count');
		$this->db->from("user");
		$this->db->where('role !=','admin');	
		$this->db->where('is_delete','1');	
		if($search != "")
		{
			$this->db->like("user.fullname", $search);
		}	
		$this->db->order_by('user.created_date', $order );
		$query = $this->db->get();
	   //echo $this->db->last_query();die();
		return $query->row_array(); 
	
	}
	function getAllUsers($limit = 10, $start = 0, $order = "",  $search = "")
	{
		$data = array();
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where('role !=','admin');	
		$this->db->where('is_delete','1');	
		$this->db->limit($limit, $start);
	   
	   	if($search != "")
		{
			$this->db->like("user.fullname", $search);
		}	
		$this->db->order_by('user.created_date', $order );	
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
		
	}

	function getUsers($id=NULL){
	
		$data = array();
		$this->db->select("*");
		$this->db->from("user");	
		$this->db->where('id',$id);	
		$query = $this->db->get();
		return $query->row_array(); 
	
	}
		
}
