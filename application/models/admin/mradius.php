<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mradius extends CI_Model
{

	 function getCountRadius($search = "", $order = "")
	{
	
		$data = array();
		$this->db->select('count(*) as count');
		$this->db->from("radius");
		$this->db->where('status','1');	
		if($search != "")
		{
			$this->db->like("radius.user_type", $search);
		}	
		$this->db->order_by('radius.created_date', $order );
		$query = $this->db->get();
	   //echo $this->db->last_query();die();
		return $query->row_array(); 
	
	}
	function getAllRadius($limit = 10, $start = 0, $order = "",  $search = "")
	{
		$data = array();
		$this->db->select("*");
		$this->db->from("radius");
		$this->db->where('status','1');	
		$this->db->limit($limit, $start);
	   
	   	if($search != "")
		{
			$this->db->like("radius.user_type", $search);
		}	
		$this->db->order_by('radius.created_date', $order );	
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
		
	}

	function getRadius($id=NULL){
	
		$data = array();
		$this->db->select("*");
		$this->db->from("radius");	
		$this->db->where('id',$id);	
		$query = $this->db->get();
		return $query->row_array(); 
	
	}
	

}
