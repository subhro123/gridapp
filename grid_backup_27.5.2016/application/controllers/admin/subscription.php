<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Subscription extends CI_Controller {
			
			
		 function __construct()
		 {
		   	parent::__construct();
			$this->load->library('form_validation',"session");
			$this->load->library('jsonoutput');
			//$this->load->library('resizeimage');
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/muser','',TRUE);
			$this->load->model('admin/msubscription','',TRUE);
			$this->load->model('common/functions','',TRUE);
		 }
		 
		 function index()
 	   {
	   //echo 'aaaa';
	   //die();
	   		if($this->session->userdata('admin_logged_in'))
				  {
				   $data['session_data'] = $this->session->userdata('admin_logged_in');
				   $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
				   $data['interest'] = array();
				   $this->load->view('admin/subscription',$data);
				  }
					else
			      {
						 redirect('admin', 'refresh');
				  }	
	   }
	   
	   function pagingsubscription()
	   {
			$search = $this->input->post("sSearch");
			$limit = $this->input->post("iDisplayLength");
			$start = $this->input->post("iDisplayStart");
			$getorder= $this->input->post("sSortDir_0");
			$order = $this->input->post("sSortDir_0");
		
			if($search == "")
			{
				$count = $this->msubscription->getCountSubscription($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$subscription = $this->msubscription->getAllSubscription($limit,$start,$order);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			}
			else
			{
				
				//$count = $this->muser->getCountFunctions();
				$count = $this->msubscription->getCountSubscription($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$subscription = $this->msubscription->getAllSubscription($limit,$start,$order,$search);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			
			}
		
			$search = $this->input->post("sSearch");
			$_SESSION['adminsubscriptionstart'] = $start;
			//echo '<pre>';
			//print_r($assessments);
			$columns = array(
			array( 'db' => 'id', 
				   'dt' => 0 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   $rtrn = ($_SESSION['adminsubscriptionstart']) + (++$i);
					//unset($_SESSION['start']);
					return $rtrn.".";
			}),
			array( 'db' => 'name', 
				   'dt' => 1 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					$str = strtoupper($row['name']);
					return $str;
			}),
		  	array( 'db' => 'cost', 
				   'dt' => 2 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
				   $str = "<div style='text-align:center'>".$row['price']."</div>";
				   return $str;
					
			}),
			array(
				'db'        => 'start_date',
				'dt'        => 3,
				'formatter' => function( $d, $row, $i ) {
				 $str =  "<div style='text-align:center'>".date('d/m/Y',strtotime($row['start_date']))."</div>";
				return $str;
				}
			),
			array(
				'db'        => 'end_date',
				'dt'        => 4,
				'formatter' => function( $d, $row, $i ) {
				 $str =  "<div style='text-align:center'>".date('d/m/Y',strtotime($row['end_date']))."</div>";
				return $str;
				}
			),
			array( 'db' => 'is_active', 
				   'dt' => 5 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   if($d == "1")
					{
					   $str = "<div style='text-align:center'><input type=\"checkbox\"  id=\"active{$row['id']}\" class=\"minimal-red\" value=\"1\" checked =\"checked\" onclick=\"updateStatus('{$row['id']}')\" title=\"active\"></div>";
					}else{
					
						$str = "<div style='text-align:center'><input type=\"checkbox\"  id=\"active{$row['id']}\" class=\"minimal-red\" value=\"1\" onclick=\"updateStatus('{$row['id']}')\" title=\"banned\"></div>";
					}
				   
				   return $str;
			}),
			array(
			'db'        => 'status',
			'dt'        => 6,
			'formatter' => function( $d, $row, $i ) {
				$str = "<div style='text-align:center'><strong ><a href='".base_url('admin/subscription/editsubscription/'.$row['id'])."' title=\"edit\" ><i class=\"fa fa-fw fa-edit\"></i></a></strong> </div>";
				 return $str;
			}
			)
		);
			$data['aaData'] = $this->jsonoutput->data_output($columns,$subscription);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($data);
			exit;
	   }
	   
	   function isactivecheck()
 	   {
			$id = $this->input->post('id');
			$val  = $this->input->post('val');
			if($id > 0)
			{
				if($val==1){
				
				$newval='1';
				}else{
				$newval='0';
				}
				$data['is_active'] =$newval;	
				$data['result'] = $this->functions->update('subscription',$data,$id,'id');
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($data);
				exit;
					
			}

 	}
	
	function addsubscription(){
	   
	   			if($this->session->userdata('admin_logged_in'))
				 	  		{
					  		 	 if($this->input->post()){
								 
											$data['name'] = $this->input->post('name');
											$data['type'] = $this->input->post('type');
											$data['description'] = $this->input->post('description');
											$data['price'] = $this->input->post('price');
											$data['start_date'] = $this->input->post('start_date');
											$data['end_date'] = $this->input->post('end_date');
											$data['created_date'] = date('Y-m-d');
											$result = $this->functions->insert('subscription',$data);
											$this->session->set_flashdata('msg', 'Subscription added successfully');
											redirect('admin/subscription');
								 }
								 else
								 {
											 $data = array();
											 //$data['getallinterests'] = $this->msubscription->getAllSubscription();
											 $data['session_data'] = $this->session->userdata('admin_logged_in');
											 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
											 $this->load->view('admin/addsubscription',$data);
								 }
							 
					  	}
							else
							{
								 redirect('admin', 'refresh');
							}
	   
	   }
	
	function editsubscription($id=NULL){
	   
	   		if($this->session->userdata('admin_logged_in') && $id > 0)
				 	  {
				 	  		if($this->input->post()){
				 	  			 $data = array();
								 $data['id'] = $id;
								 $data['name'] = $this->input->post('name');
								 $data['type'] = $this->input->post('type');
								 $data['description'] = $this->input->post('description');
								 $data['price'] = $this->input->post('price');
								 $data['start_date'] = $this->input->post('start_date');
								 $data['end_date'] = $this->input->post('end_date');
								 $data['created_date'] = date('Y-m-d');
								 $data['result'] = $this->functions->update('subscription',$data,$id,'id');
							     $this->session->set_flashdata('msg', 'Subscription updated successfully');
							     redirect('admin/subscription');
							 
				 	  		}
								 $data = array();
							 	 $data['session_data'] = $this->session->userdata('admin_logged_in');
								 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
								 $data['editsubscription'] = $this->msubscription->getSubscription($id);
								 $data['id'] = $id;
							 	 $this->load->view('admin/editsubscription',$data);
				   		
				  	 }
				   else
					   {
						 redirect('admin', 'refresh');
					   }
	   
	   }
	   
	   
	   

}

?>
