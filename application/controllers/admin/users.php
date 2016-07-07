<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Users extends CI_Controller {

		 function __construct()
		 {
		   	parent::__construct();
   			$this->load->library('form_validation',"session");
			$this->load->library('jsonoutput');
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/muser','',TRUE);
			$this->load->model('admin/musers','',TRUE);
			$this->load->model('common/functions','',TRUE);
		 }

		function index()
 	   {
	   		
				if($this->session->userdata('admin_logged_in'))
				  {
				   $data['session_data'] = $this->session->userdata('admin_logged_in');
				   $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
				   $data['users'] = array();
				   $this->load->view('admin/users',$data);
				  }
					else
			      {
						 redirect('admin', 'refresh');
				  }	
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
				$data['status'] =$newval;	
				$data['result'] = $this->functions->update('user',$data,$id,'id');
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($data);
				exit;
					
			}

 	}
	   function pagingusers()
	   {
			$search = $this->input->post("sSearch");
			$limit = $this->input->post("iDisplayLength");
			$start = $this->input->post("iDisplayStart");
			$order = $this->input->post("sSortDir_0");
		
			if($search == "")
			{
				$count = $this->musers->getCountUsers($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$users = $this->musers->getAllUsers($limit,$start,$order);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			}
			else
			{
				
				//$count = $this->muser->getCountFunctions();
				$count = $this->musers->getCountUsers($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$users = $this->musers->getAllUsers($limit,$start,$order,$search);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			
			}
		
			$search = $this->input->post("sSearch");
			$_SESSION['adminusersstart'] = $start;
			//echo '<pre>';
			//print_r($assessments);
			$columns = array(
			array( 'db' => 'id', 
				   'dt' => 0 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   $rtrn = ($_SESSION['adminusersstart']) + (++$i);
					//unset($_SESSION['start']);
					return $rtrn.".";
			}),
			array( 'db' => 'fullname', 
				   'dt' => 1 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					$str = strtoupper($row['fullname']);
					return $str;
			}),
		  	array( 'db' => 'email', 
				   'dt' => 2 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					//$str = strtoupper($row['interest_name']);
					$str =  $row['email'];
					return $str;
			}),
			array( 'db' => 'social', 
				   'dt' => 3 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					//$str = strtoupper($row['interest_name']);
					$str = "<div style='text-align:center'>".$row['social']."</div>";
					return $str;
			}),
			array( 'db' => 'status', 
				   'dt' => 4 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   if($d == "1")
					{
					   $str = "<div style='text-align:center'><input type=\"checkbox\"  id=\"active{$row['id']}\" class=\"minimal-red\" value=\"1\" checked =\"checked\" onclick=\"updateStatus('{$row['id']}')\" title=\"active\"></div>";
					}else{
					
						$str = "<div style='text-align:center'><input type=\"checkbox\"  id=\"active{$row['id']}\" class=\"minimal-red\" value=\"1\" onclick=\"updateStatus('{$row['id']}')\" title=\"banned\"></div>";
					}
				   
				   return $str;
					//unset($_SESSION['start']);
					//$str = strtoupper($row['interest_name']);
					//$str = $row['email'];
					//return $str;
			}),
			array(
				'db'        => 'created_date',
				'dt'        => 5 ,
				'formatter' => function( $d, $row, $i ) {
				 $str =  "<div style='text-align:center'>".date('d/m/Y',strtotime($d))."</div>";
				  return $str;
				}
			),
			array(
			'db'        => 'is_delete',
			'dt'        => 6 ,
			'formatter' => function( $d, $row, $i ) {
				$str = "<div style='text-align:center'><strong ><a href='".base_url('admin/users/detail/'.$row['id'])."'    title=\"show information\" class=\"ajax\"><i class=\"fa fa-fw fa-eye\"></i></a></strong> </div>";
				 return $str;
				}
			)
		);
			$data['aaData'] = $this->jsonoutput->data_output($columns,$users);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($data);
			exit;
	   }
	 
	 public function detail($user_id){

		if($this->session->userdata('admin_logged_in'))
				 	  {
					  	$data['userinformation'] = $this->musers->getUsers($user_id);
						$this->load->view('admin/userinformation',$data);
					  }
					  else
					 {
						 redirect('admin', 'refresh');
					 }

}
	   
	   
  }

?>
