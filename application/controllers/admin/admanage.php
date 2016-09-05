<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Admanage extends CI_Controller {
			
			
		 function __construct()
		 {
		   	parent::__construct();
			$this->load->library('form_validation',"session");
			$this->load->library('jsonoutput');
			//$this->load->library('resizeimage');
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/muser','',TRUE);
			$this->load->model('admin/madmanage','',TRUE);
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
				   $data['admanage'] = array();
				   $this->load->view('admin/admanage',$data);
				  }
					else
			      {
						 redirect('admin', 'refresh');
				  }	
	   }
	   
	   function pagingadmanage()
	   {
			$search = $this->input->post("sSearch");
			$limit = $this->input->post("iDisplayLength");
			$start = $this->input->post("iDisplayStart");
			$getorder= $this->input->post("sSortDir_0");
			$order = $this->input->post("sSortDir_0");
		
			if($search == "")
			{
				$count = $this->madmanage->getCountAdmanage($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$admanage = $this->madmanage->getAllAdmanage($limit,$start,$order);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			}
			else
			{
				
				//$count = $this->muser->getCountFunctions();
				$count = $this->madmanage->getCountAdmanage($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$admanage = $this->madmanage->getAllAdmanage($limit,$start,$order,$search);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			
			}
		
			$search = $this->input->post("sSearch");
			$_SESSION['adminadmanagestart'] = $start;
			//echo '<pre>';
			//print_r($assessments);
			$columns = array(
			array( 'db' => 'id', 
				   'dt' => 0 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   $rtrn = ($_SESSION['adminadmanagestart']) + (++$i);
					//unset($_SESSION['start']);
					return $rtrn.".";
			}),
			array( 'db' => 'text', 
				   'dt' => 1 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					$str = $row['text'];
					return $str;
			}),
			
			array( 'db' => 'start_date', 
				   'dt' => 2 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					$str = "<div style='text-align:center'>".$row['start_date']."</div>";
					return $str;
			}),
			
			array( 'db' => 'end_date', 
				   'dt' => 3 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   
					//unset($_SESSION['start']);
					$str = "<div style='text-align:center'>".$row['end_date']."</div>";
					return $str;
			}),
			
			array(
				'db'        => 'status',
				'dt'        => 4,
				'formatter' => function( $d, $row, $i ) {
					 
					 $str = "<div style='text-align:center'><strong >".$row['status']."</strong> </div>";
					 return $str;
				}
			)
		  	
			/*array(
				'db'        => 'status',
				'dt'        => 5,
				'formatter' => function( $d, $row, $i ) {
					$str = "<div style='text-align:center'><strong ><a href='".base_url('admin/radius/editradius/'.$row['id'])."' title=\"edit\" ><i class=\"fa fa-fw fa-edit\"></i></a></strong> </div>";
					 return $str;
				}
			)*/
		);
			$data['aaData'] = $this->jsonoutput->data_output($columns,$admanage);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($data);
			exit;
	   }
	   
	   function addadmanage(){
	   
	   	if($this->session->userdata('admin_logged_in'))
				 	  		{
									 if($this->input->post()){
									 
												$data = array();
												$data['state_id'] = $this->input->post('state_id');
												$data['text'] = $this->input->post('text');
												$data['charging_model'] = $this->input->post('charging_model');
												$data['amount'] = $this->input->post('amount');
												/*$data['image'] = $this->input->post('image');*/
												$data['start_date'] = $this->input->post('start_date');
												$data['end_date'] = $this->input->post('end_date');
												$link=$this->input->post('link');
												if(isset($link)){
													$data['link'] = $link;
												}
											    $client=$this->input->post('client');
												if(isset($client)){
													$data['client'] = $client;
												}
												$place = $this->input->post('place');
												if(isset($place)){
													$data['place'] = $place;
												}
												$data['created_date'] = date('Y-m-d');
												
												if(!empty($_FILES['image'])){
												
												 $image  = $_FILES['image'];
											  //echo $image['name'];
												  $file_element_name = 'image';
												  if($image['name']!=''){
															
															/*$edituser = $this->muser->userdetails(1);
															$smallimage = explode('.',$edituser['image']);
															$thumbFile = './uploads/ad/thumb/'.$smallimage[0].'_thumb.png';
															$bigFile = './uploads/ad/'.$edituser['image'];
															if(is_file($bigFile)) {
																	unlink($thumbFile);
																	unlink($bigFile); // delete file
															}*/
															
															$config['upload_path'] = './uploads/ad/';
															$config['allowed_types'] = 'gif|jpg|png|doc|txt|jpeg';
															$config['max_size'] = 1024 * 8;
															$config['encrypt_name'] = TRUE;
													 
															$this->load->library('upload', $config);
															 
															 
																if (!$this->upload->do_upload($file_element_name))
																{
																	$status = 'error';
																	$msg = $this->upload->display_errors('', '');
																}
																else
																{
																		
																		$data2 = $this->upload->data();
																		$data1 = array(	
																							'image' => $data2['file_name'],
																						);
																		 $result = $this->functions->update('admin',$data1,$profile_id,'id');
																	   
																		 $config['image_library'] = "gd2";
																		 $config['source_image'] = './uploads/ad/'.$data2['file_name'];
																		 $config['new_image'] = './uploads/ad/thumb/'.$data2['file_name'] ;
																		 $config['create_thumb']     = TRUE;      
																		 $config['maintain_ratio'] = FALSE;
																		 $config['width'] = "446";
																		 $config['height'] = "98";
																
																		$this->load->library('image_lib', $config);
																		if (!$this->image_lib->resize()) {
																
																			echo $this->image_lib->display_errors();
																		}
																	
																	
																}
													 }
													 
													 $data['image']=$data2['file_name'];
													 
											    }
												$data['result'] = $this->functions->insert('ad_manage',$data);
												$this->session->set_flashdata('msg', 'Ads added successfully !!');
												redirect('admin/admanage');
									 }	
									else
									{
									             /*echo 'aaaa';
												 die();*/
												 $data = array();
												 $data['session_data'] = $this->session->userdata('admin_logged_in');
												 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
												 $data['state'] = $this->madmanage->getAllState();
												 $this->load->view('admin/addadmanage',$data);
									}
	   
						} 
					
	       }
	  
	   
	   /*public function editradius($id=NULL){
	   
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
											 $data['editradius'] = $this->madmanage->getRadius($id);
											 $data['id'] = $id;
											 $this->load->view('admin/editradius',$data);
								}
	   
					} 
	   
	   }*/
	   
	 
	   

}

?>
