<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Radius extends CI_Controller {
			
			
		 function __construct()
		 {
		   	parent::__construct();
			$this->load->library('form_validation',"session");
			$this->load->library('jsonoutput');
			//$this->load->library('resizeimage');
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/muser','',TRUE);
			$this->load->model('admin/mradius','',TRUE);
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
				   $this->load->view('admin/radius',$data);
				  }
					else
			      {
						 redirect('admin', 'refresh');
				  }	
	   }
	   
	   function pagingradius()
	   {
			$search = $this->input->post("sSearch");
			$limit = $this->input->post("iDisplayLength");
			$start = $this->input->post("iDisplayStart");
			$getorder= $this->input->post("sSortDir_0");
			$order = $this->input->post("sSortDir_0");
		
			if($search == "")
			{
				$count = $this->mradius->getCountRadius($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$interest = $this->mradius->getAllRadius($limit,$start,$order);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			}
			else
			{
				
				//$count = $this->muser->getCountFunctions();
				$count = $this->mradius->getCountRadius($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$interest = $this->mradius->getAllRadius($limit,$start,$order,$search);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			
			}
		
			$search = $this->input->post("sSearch");
			$_SESSION['adminradiusstart'] = $start;
			//echo '<pre>';
			//print_r($assessments);
			$columns = array(
			array( 'db' => 'id', 
				   'dt' => 0 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   $rtrn = ($_SESSION['adminradiusstart']) + (++$i);
					//unset($_SESSION['start']);
					return $rtrn.".";
			}),
			array( 'db' => 'user_type', 
				   'dt' => 1 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					$str = strtoupper($row['user_type']);
					return $str;
			}),
		  	array( 'db' => 'min', 
				   'dt' => 2 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					//$str = strtoupper($row['interest_name']);
					$str =  "<div style='text-align:center'>".$row['min']."</div>";
					return $str;
			    }),
			array( 'db' => 'max', 
				   'dt' => 3 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					//$str = strtoupper($row['interest_name']);
					$str =  "<div style='text-align:center'>".$row['max']."</div>";
					return $str;
			    }),
			array(
					'db'        => 'created_date',
					'dt'        => 4,
					'formatter' => function( $d, $row, $i ) {
					 $str =  "<div style='text-align:center'>".date('d/m/Y',strtotime($d))."</div>";
						return $str;
					}
				),
			array(
				'db'        => 'status',
				'dt'        => 5,
				'formatter' => function( $d, $row, $i ) {
					$str = "<div style='text-align:center'><strong ><a href='".base_url('admin/radius/editradius/'.$row['id'])."' title=\"edit\" ><i class=\"fa fa-fw fa-edit\"></i></a></strong> </div>";
					 return $str;
				}
			)
		);
			$data['aaData'] = $this->jsonoutput->data_output($columns,$interest);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($data);
			exit;
	   }
	   
	   function addradius(){
	   
	   	if($this->session->userdata('admin_logged_in'))
				 	  		{
									 if($this->input->post()){
									 
												$data = array();
												$data['user_type'] = $this->input->post('user_type');
												$data['min'] = $this->input->post('min');
												$data['max'] = $this->input->post('max');
												$data['created_date'] = date('Y-m-d');
												$data['result'] = $this->functions->insert('radius',$data);
												$this->session->set_flashdata('msg', 'Radius added successfully !!');
												redirect('admin/radius');
									 }	
									else
									{
												 $data = array();
												 $data['session_data'] = $this->session->userdata('admin_logged_in');
												 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
												 $this->load->view('admin/addradius',$data);
									}
	   
						} 
					
	       }
	  
	   
	   public function editradius($id=NULL){
	   
	   			if($this->session->userdata('admin_logged_in'))
				 	  		{
								 if($this->input->post()){
								 
											$data = array();
											$data['user_type'] = $this->input->post('user_type');
											$data['min'] = $this->input->post('min');
											$data['max'] = $this->input->post('max');
											$data['created_date'] = date('Y-m-d');
											 $data['result'] = $this->functions->update('radius',$data,$id,'id');
											$this->session->set_flashdata('msg', 'Radius updated successfully !!');
											redirect('admin/radius');
								 }	
								else
								{
											 $data = array();
											 $data['session_data'] = $this->session->userdata('admin_logged_in');
											 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
											 $data['editradius'] = $this->mradius->getRadius($id);
											 $data['id'] = $id;
											 $this->load->view('admin/editradius',$data);
								}
	   
					} 
	   
	   }
	   
	 
	   

}

?>
