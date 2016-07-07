<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class User extends CI_Controller {

		 function __construct()
		 {
		   	parent::__construct();
			//$this->cache->clean();
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/muser','',TRUE);
			$this->load->model('common/functions','',TRUE);
			//$this->output->nocache();
			//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		 }

		function index()
 	   {
	   		
				if(!$this->session->userdata('admin_logged_in'))
				   {
				       $this->load->view('admin/login');
					}
					else
				   {
				   	   $session_data['session_data'] = $this->session->userdata('admin_logged_in');
				   }
	   }
	   
	   function signin()
	   {
	   				$username = $this->input->post('username');
		   			$password = $this->input->post('password');
					
		    		$result = $this->muser->userlogin($username, md5($password));
					 if($result)
					   {
					  
						 $sess_array = array();
						 foreach($result as $row)
						 {
						   $sess_array = array(
							 'id' => $row->id,
							 'fullname' => $row->fullname,
							 'email' => $row->email,
							 'created_date' => $row->created_date,
							 'image' => $row->image,   
							 'role' => $row->role  
						   );
						   $this->session->set_userdata('admin_logged_in', $sess_array);
						
						 }
						 
						 redirect('admin/dashboard', 'refresh');
				   }
					else
					   {
									 $this->session->set_flashdata('errormsg', 'Invalid email or password !!');
									 redirect('admin');
					   }
	   		
	   }
	   
	     function logout()
	   {
	   			 $this->session->unset_userdata('admin_logged_in');
				 $this->session->sess_destroy();
				 $this->session->set_flashdata('successmsg', 'Logged out successfully !!');
				 //$this->cache->clean();
				 redirect('admin', 'refresh');
	   }
	   
	 
	   
		
	   
	   function profile(){

		if($this->session->userdata('admin_logged_in'))
				   {
				   	     $data = array();
						 $data['session_data'] = $this->session->userdata('admin_logged_in');
						 $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
						 /*echo '<pre>';
						 print_r($data['session_data']);*/
						 $this->load->view('admin/profile',$data);
				   
				   }else{
				   
				       redirect('admin', 'refresh');
				   
				   }
		

	   }
	   
	   function update_admin_name(){
		//echo $name;
		  $name = $this->input->post('full_name');
		  $id = $this->input->post('profile_id');
		  $data = array('fullname' => $name);
		  $result = $this->functions->update('admin',$data,$id,'id');
		  echo 'success';
	 }
	 function update_admin_password(){
		 
		  $password = $this->input->post('password');
		  $id = $this->input->post('profile_id');
		  $data = array('password' => md5($password));
		  $result = $this->functions->update('admin',$data,$id,'id');
		  echo 'success';
	}
	
	function upload_file()
	{
		$status = "";
		$msg = "";
		$file_element_name = 'image';
		$username = $this->input->post('username');
		$profile_id = $_REQUEST['profile_id'];
		$edituser = $this->muser->userdetails(1);
			$smallimage = explode('.',$edituser['image']);
			$thumbFile = './uploads/admin/thumb/'.$smallimage[0].'_thumb.png';
			$bigFile = './uploads/admin/'.$edituser['image'];
			if(is_file($bigFile)) {
					unlink($thumbFile);
					unlink($bigFile); // delete file
			}
		//echo $edituser['image'];
		
		//echo $image;
	   /* if (empty($username))
		{
			$status = "error";
			$msg = "Please enter a username";
		}
		 
		if ($status != "error")
		{*/
			$config['upload_path'] = './uploads/admin/';
			$config['allowed_types'] = 'gif|jpg|png|doc|txt|jpeg';
			$config['max_size'] = 1024 * 8;
			$config['encrypt_name'] = TRUE;
	 
			$this->load->library('upload', $config);
			//echo $file_element_name;
			
			 
				
			if (!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('', '');
			}
			else
			{
				    
					$data = $this->upload->data();
					$data1 = array(	
										'image' => $data['file_name'],
									);
					 $result = $this->functions->update('admin',$data1,$profile_id,'id');
				   
					 $config['image_library'] = "gd2";
					 $config['source_image'] = './uploads/admin/'.$data['file_name'];
					 $config['new_image'] = './uploads/admin/thumb/'.$data['file_name'] ;
					 $config['create_thumb']     = TRUE;      
					 $config['maintain_ratio'] = FALSE;
					 $config['width'] = "200";
					 $config['height'] = "150";
			
					$this->load->library('image_lib', $config);
					if (!$this->image_lib->resize()) {
			
						echo $this->image_lib->display_errors();
					}
				
				 $file_id = '1';
				/* $data = array(	
										'image' => $data['file_name'],
									);
				$result = $this->madmin->updateFinalData('admin',$data,'1');*/
				//$file_id = $this->madmin->updateFinalData($data['file_name'], $_POST['title']);
				//die();
				if($file_id)
				{
					//unlink($data['file_name']);
					$status = "success";
					$msg = "File successfully uploaded.Wait,Page is getting refreshed*".$data['file_name'];
				}
				else
				{
					unlink($data['full_path']);
					$status = "error";
					$msg = "Something went wrong when saving the file, please try again.";
				}
			}
			
			//@unlink($data['file_name']);
	   /* }*/
		echo json_encode(array('status' => $status, 'msg' => $msg));
	}
	
	function updateadminprofile(){

		if($this->session->userdata('admin_logged_in'))
				   {
				   	     $data = array();
						 //$data['session_data'] = $this->session->userdata('admin_logged_in');
						 //$username = $this->input->post('username');
		   				 $data['fullname'] = $this->input->post('fullname');
						 $password = $this->input->post('passwords');
						
						 if(isset($password) && $password!=''){
						 //echo 'aaaa';
						 	$data['password']  = md5($password);
						 }
						 /*echo 'bbbbb';
						 die();*/
						 //$data['password'] = $this->input->post('imagename');
						 $profile_id = $this->input->post('profile_id');
						 //$data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
						 
						  $image  = $_FILES['image'];
								  //echo $image['name'];
								  $file_element_name = 'image';
								 	if($image['name']!=''){
											
											$edituser = $this->muser->userdetails(1);
											$smallimage = explode('.',$edituser['image']);
											$thumbFile = './uploads/admin/thumb/'.$smallimage[0].'_thumb.png';
											$bigFile = './uploads/admin/'.$edituser['image'];
											if(is_file($bigFile)) {
													unlink($thumbFile);
													unlink($bigFile); // delete file
											}
								            
											$config['upload_path'] = './uploads/admin/';
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
														 $config['source_image'] = './uploads/admin/'.$data2['file_name'];
														 $config['new_image'] = './uploads/admin/thumb/'.$data2['file_name'] ;
														 $config['create_thumb']     = TRUE;      
														 $config['maintain_ratio'] = FALSE;
														 $config['width'] = "200";
														 $config['height'] = "150";
												
														$this->load->library('image_lib', $config);
														if (!$this->image_lib->resize()) {
												
															echo $this->image_lib->display_errors();
														}
													
													
												}
									 }
								
						 $data['result'] = $this->functions->update('admin',$data,$profile_id,'id');
						 $this->session->set_flashdata('msg', 'Profile updated successfully');
						 redirect('admin/user/profile');

				   
				   }else{
				   
				       redirect('admin', 'refresh');
				   
				   }

	}
	
	function chkoldpassword(){
				
				if($this->session->userdata('admin_logged_in'))
				{	
						$old_password = md5($this->input->post('old_password'));
						$result = $this->muser->chkOldPassword($old_password);
						
						echo $result;
				
				}else{
				   
				       redirect('admin', 'refresh');
				   
				}

				
	}


	   
}

?>
