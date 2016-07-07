<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . 'libraries/REST_Controller.php';

class Subinterest extends REST_Controller {
			
			
		public function __construct() {
        
		parent::__construct();
        $this->load->model('response', 'Response');
        $this->load->model('error_codes', 'ErrorCodes');
        $this->load->library('form_validation');
		//$this->load->library('generatereferalcode');
		//$this->load->model('Emailoneseven', 'emailoneseven');
		$this->load->model('Msubinterest', '', TRUE);

    }
	
	   /********************************/
		
		// GET ALL SUB INTEREST
		
		/********************************/
	
	   public function getsubinterest_post()
		{
					
					$data=array();
					$data['interest_id']=$_POST['interest_id'];
					 //$userData=array();
					 //$json = file_get_contents('php://input');
					 //$userData = json_decode($json, true);
					$getsubinterest = $this->Msubinterest->getSubInterest($data['interest_id']);
					  
					 if(empty($getsubinterest)){
					 
					 		$errors = 'SubInterest not Found !!';
            				return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);
					 }
					else{
					
					
					 
					 		$this->Response->outputResponse(true, false, array("response" =>  $getsubinterest), '', '', '');
					 }
		
		}

	
	
}
