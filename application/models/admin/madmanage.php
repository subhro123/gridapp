<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Madmanage extends CI_Model
{

	 function getCountAdmanage($search = "", $order = "")
	{
	
		$data = array();
		$this->db->select('count(*) as count');
		$this->db->from("ad_manage");
		//$this->db->where('status','active');	
		if($search != "")
		{
			$this->db->like("ad_manage.title", $search);
		}	
		$this->db->order_by('ad_manage.created_date', $order );
		$query = $this->db->get();
	   //echo $this->db->last_query();die();
		return $query->row_array(); 
	
	}
	function getAllAdmanage($limit = 10, $start = 0, $order = "",  $search = "")
	{
		$data = array();
		$this->db->select("*");
		$this->db->from("ad_manage");
		//$this->db->where('status','1');	
		$this->db->limit($limit, $start);
	   
	   	if($search != "")
		{
			$this->db->like("ad_manage.title", $search);
		}	
		$this->db->order_by('ad_manage.created_date', $order );	
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
		
	}
	
	function getAllState(){
	
	    $data = array();
		$this->db->select("*");
		$this->db->from("state");	
		$query = $this->db->get();
		return $query->result_array(); 
	
	}

	/*function getRadius($id=NULL){
	
		$data = array();
		$this->db->select("*");
		$this->db->from("radius");	
		$this->db->where('id',$id);	
		$query = $this->db->get();
		return $query->row_array(); 
	
	}*/
	

}
