<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msubscription extends CI_Model
{

	 function getCountSubscription($search = "", $order = "")
	{
	
		$data = array();
		$this->db->select('count(*) as count');
		$this->db->from("subscription");
		$this->db->where('status','1');	
		if($search != "")
		{
			$this->db->like("subscription.name", $search);
		}	
		$this->db->order_by('subscription.created_date', $order );
		$query = $this->db->get();
	   //echo $this->db->last_query();die();
		return $query->row_array(); 
	
	}
	function getAllSubscription($limit = 10, $start = 0, $order = "",  $search = "")
	{
		$data = array();
		$this->db->select("*");
		$this->db->from("subscription");
		$this->db->where('status','1');	
		$this->db->limit($limit, $start);
	   
	   	if($search != "")
		{
			$this->db->like("subscription.name", $search);
		}	
		$this->db->order_by('subscription.created_date', $order );	
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
		
	}

	function getSubscription($id=NULL){
	
		$data = array();
		$this->db->select("*");
		$this->db->from("subscription");	
		$this->db->where('id',$id);	
		$query = $this->db->get();
		return $query->row_array(); 
	
	}
}
