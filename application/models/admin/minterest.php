<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Minterest extends CI_Model
{

	 function getCountInterest($search = "", $order = "")
	{
	
		$data = array();
		$this->db->select('count(*) as count');
		$this->db->from("interest");
		$this->db->where('interest_id',0);	
		$this->db->where('status','1');	
		if($search != "")
		{
			$this->db->like("interest.interest_name", $search);
		}	
		$this->db->order_by('interest.created_date', $order );
		$query = $this->db->get();
	   //echo $this->db->last_query();die();
		return $query->row_array(); 
	
	}
	function getAllInterest($limit = 10, $start = 0, $order = "",  $search = "")
	{
		$data = array();
		$this->db->select("*");
		$this->db->from("interest");
		$this->db->where('interest_id',0);	
		$this->db->where('status','1');	
		$this->db->limit($limit, $start);
	   
	   	if($search != "")
		{
			$this->db->like("interest.interest_name", $search);
		}	
		$this->db->order_by('interest.created_date', $order );	
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
		
	}

	function getInterest($id=NULL){
	
		$data = array();
		$this->db->select("*");
		$this->db->from("interest");	
		$this->db->where('id',$id);	
		$query = $this->db->get();
		return $query->row_array(); 
	
	}
	
	function getAllInterests(){
	
		$data = array();
		$this->db->select("*");
		$this->db->from("interest");	
		$this->db->where('interest_id',0);	
		$query = $this->db->get();
		return $query->result_array(); 
	}
	
	function getInterestId($opt_interest_id){
		
		$data = array();
		$this->db->select("id");
		$this->db->from("interest");	
		$this->db->where('id',$opt_interest_id);	
		$query = $this->db->get();
		return $query->row_array(); 
			
	}
	
	function getCountinterestname($interest_name){
				
				$data = array();
				$this->db->select("*");
				$this->db->from("interest");	
				$this->db->where('interest_name',$interest_name);	
				$query = $this->db->get();
				return $query->num_rows; 
	}
	
	function getCountsubinterestname($subinterest_name){
				
				$data = array();
				$this->db->select("*");
				$this->db->from("interest");	
				$this->db->where('subinterest_name',$subinterest_name);	
				$query = $this->db->get();
				return $query->num_rows; 
	}
	
	function getSubInterest($interestid){
			
		$data = array();
		$this->db->select("*");
		$this->db->from("interest");	
		$this->db->where('interest_id',$interestid);
		$this->db->where('status','1');		
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array(); 
	}
}
