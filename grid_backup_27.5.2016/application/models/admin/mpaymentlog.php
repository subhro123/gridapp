<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpaymentlog extends CI_Model
{

	 function getCountPaymentlog($search = "", $order = "")
	{
	
		$data = array();
		$this->db->select('count(*) as count');
		$this->db->from("paymentlog");
		$this->db->join('user', 'user.id = paymentlog.user_id');
		$this->db->where('paymentlog.status','1');	
		if($search != "")
		{
			$this->db->like("paymentlog.transaction_id", $search);
		}	
		$this->db->order_by('paymentlog.payment_created_date', $order );
		$query = $this->db->get();
	   //echo $this->db->last_query();die();
		return $query->row_array(); 
	
	}
	function getAllPaymentlog($limit = 10, $start = 0, $order = "",  $search = "")
	{
		$data = array();
		$this->db->select("*");
		$this->db->from("paymentlog");
		$this->db->join('user', 'user.id = paymentlog.user_id');
		$this->db->where('paymentlog.status','1');	
		$this->db->limit($limit, $start);
	   if($search != "")
		{
			$this->db->like("paymentlog.transaction_id", $search);
		}	
		$this->db->order_by('paymentlog.payment_created_date', $order );	
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
		
	}

}
