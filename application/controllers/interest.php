<?php

if (!defined('BASEPATH')) {

    exit('No direct script access allowed');

}



require APPPATH . 'libraries/REST_Controller.php';



class Interest extends REST_Controller {

			

			

		public function __construct() {

        

		parent::__construct();

        $this->load->model('response', 'Response');

        $this->load->model('error_codes', 'ErrorCodes');

        $this->load->library('form_validation');

		$this->load->model('Muser', '', TRUE);

		//$this->load->library('generatereferalcode');

		//$this->load->model('Emailoneseven', 'emailoneseven');

		$this->load->model('Minterest', '', TRUE);

		



    }

	

	 /********************************/

		

		// GET ALL INTEREST

		

	/********************************/

	

	   public function getinterest_post()

		{

					

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					

					$data=array();

					 //$userData=array();

					 //$json = file_get_contents('php://input');

					 //$userData = json_decode($json, true);

					$getallinterest = $this->Minterest->getallinterest($data);

					  

					 if(empty($getallinterest)){

					 

					 		$errors = 'Interest not Found !!';

            				return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

					 }

					else{

					

					

					 

					 		$this->Response->outputResponse(true, false, array("response" =>  $getallinterest), '', '', '');

					 }

					 

				}

		

		}

		

	 /********************************/

		

		// USER AUTHENTICATION CHECK

		

     /********************************/

	 public function auth()

		 {

			 

			    //$users_id  = $this->get_header('User-Id');

				$token     = $this->get_header('Authorizations');

				//$returnauthencticationcode = $this->Muser->returnAuthencticationCode($users_id,$token);
				
				$returnauthencticationcode = $this->Muser->returnAuthencticationCode($token);
				

				if($returnauthencticationcode==401){

				$errors = 'Unauthorized.';

				//return $this->Response->outputResponse(401,false, $error->msg_content, null, '', '', $errors);

				return $returnauthencticationcode;

				}

				if($returnauthencticationcode==402){

				$errors = 'Your session has been expired.';

				//return $this->Response->outputResponse(402,false, $error->msg_content, null, '', '', $errors);

				return $returnauthencticationcode;

				

				}

				if($returnauthencticationcode==200){

				$errors = 'Your session has been expired.';

				return $returnauthencticationcode;

				//$this->Response->outputResponse(200, false, array("response" =>  ''), '', '', 'Authorized.');

				

				}

				

			}

		function get_header( $pHeaderKey )

		{

			 $test = getallheaders();

			if ( array_key_exists($pHeaderKey, $test) ) {

				$headerValue = $test[ $pHeaderKey ];

			}

			return $headerValue;

		}



	

	

}

