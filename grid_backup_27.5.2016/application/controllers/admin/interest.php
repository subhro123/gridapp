<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Interest extends CI_Controller {
			
			
		 function __construct()
		 {
		   	parent::__construct();
			$this->load->library('form_validation',"session");
			$this->load->library('jsonoutput');
			//$this->load->library('resizeimage');
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/muser','',TRUE);
			$this->load->model('admin/minterest','',TRUE);
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
				   $this->load->view('admin/interest',$data);
				  }
					else
			      {
						 redirect('admin', 'refresh');
				  }	
	   }
	   
	   function paginginterest()
	   {
			$search = $this->input->post("sSearch");
			$limit = $this->input->post("iDisplayLength");
			$start = $this->input->post("iDisplayStart");
			$getorder= $this->input->post("sSortDir_0");
			$order = $this->input->post("sSortDir_0");
		
			if($search == "")
			{
				$count = $this->minterest->getCountInterest($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$interest = $this->minterest->getAllInterest($limit,$start,$order);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			}
			else
			{
				
				//$count = $this->muser->getCountFunctions();
				$count = $this->minterest->getCountInterest($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$interest = $this->minterest->getAllInterest($limit,$start,$order,$search);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			
			}
		
			$search = $this->input->post("sSearch");
			$_SESSION['adminintereststart'] = $start;
			//echo '<pre>';
			//print_r($assessments);
			$columns = array(
			array( 'db' => 'id', 
				   'dt' => 0 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   $rtrn = ($_SESSION['adminintereststart']) + (++$i);
					//unset($_SESSION['start']);
					return $rtrn.".";
			}),
			array( 'db' => 'interest_name', 
				   'dt' => 1 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					$str = strtoupper($row['interest_name']);
					return $str;
			}),
		  	array( 'db' => 'subinterest_name', 
				   'dt' => 2 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					//$str = strtoupper($row['interest_name']);
					$str = "<div style='text-align:center'><strong ><a href='javascript:void(0)' title=\"Sub Interest\"   title=\"view user details\" class=\"ajax\"  onclick=\"return showsubinterest('{$row['id']}')\">View <!--<i class=\"fa fa-fw fa-eye\"></i>--></a></strong> </div>";
					return $str;
			}),
			array(
				'db'        => 'created_date',
				'dt'        => 3,
				'formatter' => function( $d, $row, $i ) {
				 $str =  "<div style='text-align:center'>".date('d/m/Y',strtotime($d))."</div>";
					return $str;
				}
			),
			array(
			'db'        => 'status',
			'dt'        => 4,
			'formatter' => function( $d, $row, $i ) {
				$str = "<div style='text-align:center'><strong ><a href='".base_url('admin/interest/editinterest/'.$row['id'])."' title=\"edit\" ><i class=\"fa fa-fw fa-edit\"></i></a></strong> </div>";
				 return $str;
			}
			)
		);
			$data['aaData'] = $this->jsonoutput->data_output($columns,$interest);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($data);
			exit;
	   }
	   
	   function addinterest(){
	   
	   			if($this->session->userdata('admin_logged_in'))
				 	  		{
					  		 if($this->input->post()){
								 
								 $data = array();
								 $flag = $this->input->post('flag');
								 //die();
								 $data['interest_name'] = $this->input->post('interest_name');
								 $countinterestname = $this->minterest->getCountinterestname($data['interest_name']);
								 if($flag=='1' && $data['interest_name']==''){
								 
								  $this->session->set_flashdata('errormsg', 'Please add a Interest name !!');								  
								  redirect('admin/interest/addinterest');
								   
								 }else{
								 if($flag==2 && $this->input->post('subinterest_name')==''){
										  
								   $this->session->set_flashdata('errormsg', 'Please add a Subinterest name !!');							 
								   redirect('admin/interest/addinterest');
								   
								 }
								 else{
						         $countsubinterestname = $this->minterest->getCountsubinterestname($this->input->post('subinterest_name'));
								 if($countinterestname ==1){
								 		
										$this->session->set_flashdata('errormsg', 'This Interest name already exists !!');							 
								   		redirect('admin/interest/addinterest');
								   
								 }else  if($countsubinterestname ==1){
								 		
										$this->session->set_flashdata('errormsg', 'This SubInterest name already exists !!');							 
								   		redirect('admin/interest/addinterest');
								   
								 }else{
								 $opt_interest_id = $this->input->post('opt_interest_id');
								 if(isset($opt_interest_id) && $opt_interest_id!=0){
								 	
									$getinterestid=$this->minterest->getInterestId($opt_interest_id);
									$data['subinterest_name'] = $this->input->post('subinterest_name');
									$data['interest_id'] = $getinterestid['id'];
									
								 	}
								 
								 $data['created_date'] = date('Y-m-d');
								 $data['result'] = $this->functions->insert('interest',$data);
								 if(isset($opt_interest_id) && $opt_interest_id!=0){
								 $this->session->set_flashdata('msg', 'Subinterest added successfully');
								 }else{
								 $this->session->set_flashdata('msg', 'Interest added successfully');
								 }
							     redirect('admin/interest');
								 			}
								 		}
								 	}
								 }
								 else
								 {
									 $data = array();
									 $data['getallinterests'] = $this->minterest->getAllInterests();
									 $data['session_data'] = $this->session->userdata('admin_logged_in');
									 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
									 $this->load->view('admin/addinterest',$data);
								 }
							 
					  	}
							else
							{
								 redirect('admin', 'refresh');
							}
	   
	   }
	   public function deleteSubinterest(){
	   			
			$subinterestStatus = array();
			$subinterestStatus = explode(',',$this->input->post('txt_subinterest'));
			$interestID = $this->input->post('interestID');
			
			for($i=0;$i<count($subinterestStatus);$i++){
				
					$data['status'] = '0';
					$this->functions->update('interest',$data,$subinterestStatus[$i],'id');
				}
			$this->session->set_flashdata('msg', 'Sub Interest deleted successfully');
			redirect('admin/interest');
	   }
	   
	   public function editinterest($id=NULL){
	   
	   		if($this->session->userdata('admin_logged_in') && $id > 0)
				 	  {
				 	  		if($this->input->post()){
				 	  			 $data = array();
								 $data['id'] = $id;
								 $data['interest_name'] = $this->input->post('interest_name');
								 $data['created_date'] = date('Y-m-d');
								 $data['result'] = $this->functions->update('interest',$data,$id,'id');
							     $this->session->set_flashdata('msg', 'Interest updated successfully');
							     redirect('admin/interest');
							 
				 	  		}
								 $data = array();
							 	 $data['session_data'] = $this->session->userdata('admin_logged_in');
								 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
								 $data['editinterest'] = $this->minterest->getInterest($id);
								 $data['id'] = $id;
							 	 $this->load->view('admin/editinterest',$data);
				   		
				  	 }
				   else
					   {
						 redirect('admin', 'refresh');
					   }
	   
	   }
	   
	   public function showsubinterest(){
         //echo $id;
		 //$data['status'] ="0";
		 $data['interestID'] = $this->input->post('interestID');
		 $data['subinterest'] = $this->minterest->getSubInterest($data['interestID']);	
		 //echo '<pre>';
		 //print_r($data['subinterest']);
		 echo $this->load->view('admin/subinterest',$data,true);

	}
	   

}

?>
