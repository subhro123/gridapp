<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class User extends CI_Controller {
			
			 function __construct()
		 {
		   	parent::__construct();
   			$this->load->library('form_validation',"session");
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('mlogin');
			$this->load->model('common/muser','',TRUE);
		 }

		function index()
 	   {
	   		
			//$this->load->view('login');
	   }
	   
			function checkEmailExistence(){
			
						
						
						$validateValue = $this->input->post('email');
						//$validateId= $this->input->post('fieldId');
						
						 //$validateError= "This username is already taken";
						 //$validateSuccess= "This username is not available";
						 
						 /* RETURN VALUE */
						
						$arrayToJs = array();
						//$arrayToJs[0] = $validateId;
						
						$result = $this->muser->checkEmail('user',$validateValue);
						
						if($result ==1){
										
								     echo  "true";
									//$arrayToJs[1] = true; // RETURN TRUE
								    //echo json_encode($arrayToJs); // RETURN ARRAY WITH success
									
						}else {
									
									echo "false";
									//$arrayToJs[1] = false;
  								    //echo json_encode($arrayToJs);
								
						  }
						//echo json_encode($data);
			
			}
			

}
?>