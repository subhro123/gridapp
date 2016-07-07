<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Dashboard extends CI_Controller {

		 function __construct()
		 {
		   	parent::__construct();
			
			$this->load->model('admin/muser','',TRUE);
   			$this->load->library('form_validation',"session");
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/mdashboard','',TRUE);
			//$this->load->model('mdashboard','',TRUE);
		 }

		function index()
 	   {
	   //echo '<pre>';
	  // print_r($this->session->userdata('admin_logged_in'));die();
	   			if(!$this->session->userdata('admin_logged_in'))
				   {
				       $this->load->view('admin/login');
					}
					else
				   {
				   
				        
						$data['session_data'] = $this->session->userdata('admin_logged_in');
						$session_data =  $this->session->userdata('admin_logged_in');
						$data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
						//$data['users'] = $this->mdashboard->getDashboardUser();
						//$data['dashboardgraph'] = $this->mdashboard->getDashboardGraph();
						$data['fullname'] = $session_data['fullname'];
						//$data['type'] = $session_data['type'];
						$data['created_date'] = $session_data['created_date'];
						 //$data['user_id'] = $session_data['id'];
						 //echo '<pre>';
						 //print_r($data['users']);
						 //die();
					   	$this->load->view('admin/dashboard',$data);
				   }
	   
	   }
}
	   
?>
