<?php 

if (!defined('BASEPATH')) {

    exit('No direct script access allowed');

}







require APPPATH . 'libraries/REST_Controller.php';

require_once APPPATH .'libraries/Braintree_lib.php';



class User extends REST_Controller {



    public function __construct() {

        

		

		parent::__construct();

		//$this->output->set_header('Access-Control-Allow-Origin: *');

		$this->load->model('response', 'Response');

        $this->load->model('error_codes', 'ErrorCodes');

        $this->load->library('form_validation');

		$this->load->library('generatereferalcode');

		$this->load->library('generatenumber');

		$this->load->library('aessecurity');

		$this->load->library('location');

		//$this->load->library('plivo');
		
		$this->load->library('apnengine');
		
		$this->load->library('gcmengine');

		$this->load->library('payeezy');
		
		$this->load->library('firebaselib');

		$this->load->model('Emailoneseven', 'emailoneseven');

		$this->load->model('common/functions','',TRUE);

		$this->load->model('Muser', '', TRUE);



    }

	 

	

	    /********************************/

		

		// USER REGISTRATION

		

		/********************************/

	

	   public function registration_post()

		{

				

			/*$getauthresponse = $this->auth();

					

					 if($getauthresponse == 200){*/

					 $check_auth_client = $this->check_auth_client();

					 if($check_auth_client == true){

					 $userData = array();
					 
					 $json = file_get_contents('php://input');

					 $userData = json_decode($json, true);
					 
					 $data=array();

					 $datapayment=array();

					 $dataactivation=array();
					
					 $auth_key  = $this->get_header('Uniquetime-Key', TRUE);
					 
					 //die();
					 
					 if(isset($auth_key)){
					 
					 	$data['token'] = $auth_key;
					 
					 }

					 if(isset($userData['fullname'])){

					 	$data['fullname']=$userData['fullname'];

					 }

					  if(isset($userData['email'])){

					 	$data['email']=$userData['email'];

					 }

					  if(isset($userData['password'])){

					 	$data['password']=md5($userData['password']);

					 }

					  if(isset($userData['phone'])){

					 	$data['phone']=$userData['phone'];

					 }

					  if(isset($userData['occupation'])){

					 	$data['occupation']=$userData['occupation'];

					 }

					 
					  if(isset($userData['dob'])){

					 	$data['dob']=date('Y-m-d',strtotime($userData['dob']));

					  }

					  

					  if(isset($userData['gender'])){

					 	$data['gender']=$userData['gender'];

					  }

					 

					 if(isset($userData['subcription'])  && $userData['subcription']=='FREE'){

						 

							$data['role']='freeuser';

					 }

					 if(isset($userData['subcription'])  && $userData['subcription'] =='PAID'){

						 

							$data['role']='paiduser';

					 }

					 $data['created_date']=date('Y-m-d H:i:s');

					 

					 $check_user_id = $this->Muser->check_user_id($data['email']);

					 

					 if($check_user_id == 0){

					 	if(isset($userData['subcription']) && $userData['subcription']=='FREE'){	 

							  $unique_user_id = $this->Muser->create('user',$data);

						}

						if(isset($userData['subcription'])  && $userData['subcription']=='PAID'){

								

								$payment_type =$userData['payment_type'];

					 			$getsubscription = $this->Muser->getSubscription($payment_type);

								$unique_user_id = $this->Muser->create('user',$data);

								$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($unique_user_id);

								$datapayment['user_id']=$unique_user_id;

								$datapayment['amount']=$getsubscription['price'];

								$datapayment['post_id']=0;

								$datapayment['transaction_id']=$userData['transactionID'];

								$datapayment['payment_type']=$userData['payment_type'];

								$datapayment['payment_created_date']=date('Y-m-d');

								//echo '<pre>';

								//print_r($datapayment);

								$unique_payment_user_id = $this->Muser->create('paymentlog',$datapayment);

								

								//echo $this->db->last_query();

								

						}

							  if (!$unique_user_id OR $unique_user_id == 0)

							{

									$errors = 'Problem in creating User';

									return $this->Response->outputResponse(false, $error->msg_content, null, '', '', $errors);

							}else{

							

							 

							 //$now = new DateTime();

							 $dataactivation['user_id']=$unique_user_id;

							 $dataactivation['activationcode']=$this->generatereferalcode->generateReferalcode(9, 1, 2, 3);

							 $dataactivation['codesentdate']=date('Y-m-d H:i:s');

							 $dataactivation['codeexpirydate']=date("Y-m-d H:i:s", time() + 24 * 60 * 60);

							 $dataactivation['activationstatus']=0;

							 $unique_activation_id = $this->Muser->create('activationcode',$dataactivation);

							  

							  $this->send_activation_email($data['email'],$unique_user_id,$dataactivation['activationcode'],$data['fullname']);

									$new_user_details = array(

                						'user_subscription' => $getSubscriptionStatus['role']

										);

								$this->Response->outputResponse(true, false, array("response" =>  $new_user_details ), '', '', 'Successfully Registered!!');

							}

					

					}else{

					

							$errors = 'This User already exists !!';

							return $this->Response->outputResponse(false, $error->msg_content, null, '', '', $errors);

							}

							

					 }else{

					

							$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

					}

					

					/* }*/

					 //print_r($data); 

					 //die;	

			}

			

	protected function send_activation_email($email,$unique_user_id,$activationcode,$fullname) {

					//$email = $this->Account->get_user_by_id($user_id)->email;

					$date=date('d-m-Y',strtotime(date('Y-m-d')));

					$email_from= 'gridapp@gmail.com';

					$subject = "Grid App: Activate Registration";

					$title = "Code for activating registration";

					$message = "<p>Your registration is successful.Please use the below 6 disit activation code to ACTIVATE to Grid App .</p><p><b>Activation Code  : </b> {$activationcode} </p><p>Thanks,</p><p><b>Team Grid App </b></p><p>This is an auto generated email.We request you not to reply.</p><b>Date : </b>{$date}";

					//$message = "To log into your account for Bomb Obama, please use the following access information<br /><p><b>Username:</b> {$user_name}</p><p><b>Password:</b> {$new_password}";

					$body = $this->emailoneseven->email_template($unique_user_id, $title, $message);

					$response = $this->emailoneseven->send_email($email, $subject, $body,$email_from);

					return true;

				}

			/*************************************/

	   

	  	// RESEND ACTIVATION CODE

	  

	  	/*************************************/

	  

		  public function resend_activate_post()

			{

					    /*$getauthresponse = $this->auth();

						if($getauthresponse == 200){*/

						$check_auth_client = $this->check_auth_client();

					 

					 	if($check_auth_client == true){

						$userData  = array();
						
						$json = file_get_contents('php://input');

					 	$userData = json_decode($json, true);
						
						$data=array();

						

						if(isset($userData['email'])){

								//$data['email']=$userData['email'];
								$getuserid = $this->Muser->getuseridByToken('',$userData['email']);
								$data['email'] =$getuserid['email'];

							 }

						 	 

						 $dataactivation['activationcode']=$this->generatereferalcode->generateReferalcode(9, 1, 2, 3);

						 

						 $getuserdetails=$this->Muser->getuserdetails($data['email']);

						 $updateactivationcode =$this->Muser->updateactivationcode($getuserdetails['id'],$dataactivation['activationcode']);

						 	 

						 $this->update_activation_email($data['email'],$getuserdetails['id'],$dataactivation['activationcode'],$getuserdetails['fullname']);	

						

						 return $this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'Activation Code regenerated successfully !!');

						 }

						 else{

					

							$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

					}

					/*}*/

			}	 

			

			protected function update_activation_email($email,$unique_user_id,$activationcode,$fullname) {

									//$email = $this->Account->get_user_by_id($user_id)->email;

									$date=date('d-m-Y',strtotime(date('Y-m-d')));

									$email_from= 'gridapp@gmail.com';

									$subject = "Grid App: Resend activation code";

									$title = "Code for activating registration";

									$message = "<p>You have generated new activation code .Please use the below 6 disit activation code to ACTIVATE to Grid App .</p><p><b>Activation Code  : </b> {$activationcode} </p><p>Thanks,</p><p><b>Team Grid App </b></p><p>This is an auto generated email.We request you not to reply.</p><b>Date : </b>{$date}";

									//$message = "To log into your account for Bomb Obama, please use the following access information<br /><p><b>Username:</b> {$user_name}</p><p><b>Password:</b> {$new_password}";

									$body = $this->emailoneseven->email_template($unique_user_id, $title, $message);

									$response = $this->emailoneseven->send_email($email, $subject, $body,$email_from);

									return true;

				}

				

	

	

			

		/********************************/

		

		// USER ACTIVATION

		

		/********************************/

		

			 public function activate_post()

			 	{

			 		 $check_auth_client = $this->check_auth_client();

					 

					 if($check_auth_client == true){

					 

					 $data=array();

					 $userData=array();
					 
					 $json = file_get_contents('php://input');

					 $userData = json_decode($json, true);

					 if(isset($userData['email'])){

					 	$getuserid = $this->Muser->getuseridByToken('',$userData['email']);
						
						
						$data['email']=$getuserid['email'];
						
						

					 }

					 if(isset($userData['activationcode'])){

					 	$data['activationcode']=$userData['activationcode'];

					 }

					 if(isset($userData['platform'])){

					 	$data['platform']=$userData['platform'];

					 }

					 if(isset($userData['model'])){

					 	$data['model']=$userData['model'];

					 }

					 if(isset($userData['UUID'])){

					 	$data['UUID']=$userData['UUID'];

					 }

					 

					 

					 $activateuser = $this->Muser->activateUser($data['email'],$data['activationcode'],$data['platform'],$data['model'],$data['UUID']);

					 

					 if($activateuser =='-1'){

					 

					 		$errors = 'Invalid Email !!';

            				return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

					 }

					 else if($activateuser =='-2'){

					 

					 		$errors = 'Invalid Activation Code !!';

            				return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

							 

					 }

					 else if($activateuser =='-3'){

					 

					 		$errors = 'Your activation code has been expired !!';

            				return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

							 

					 }

					 else{

					 
							if(isset($userData['email'])){

									$user_id = $this->Muser->get_final_user_id($data['email']);

						 if(isset($user_id['token'])){

									 $oldtoken = $user_id['token'];
									 
									 $data1['user_id'] =$user_id['id']; 

									}

									$data1['login_timestamp'] =date('Y-m-d H:i:s'); 

									$data1['counter'] =1; 

									//$data['is_active'] ='1'; 

									$unique_user_id = $this->Muser->create('login',$data1);

									$last_login = date('Y-m-d H:i:s');

									$updatedtoken = crypt(substr(md5(rand()), 0, 7), $password);

									$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));

									$this->db->trans_start();

									$this->db->where('token',$oldtoken)->update('user',array('last_login' => $last_login));
									$this->db->where('user_id',$oldtoken)->update('activationcode',array('user_id' => $updatedtoken));
									//$data2['user_id'] = $user_id['id'];

									$data2['token'] = $oldtoken ;

									$data2['expired_at'] = $expired_at ;

									if ($this->db->trans_status() === FALSE){

									  $this->db->trans_rollback();

									  //return array('status' => 500,'message' => 'Internal server error.');

									  $this->Response->outputResponse( 500, false, array("response" =>  $new_user_details), '', '', 'Logged in Successfully!!');

								   }else{

								    $this->db->trans_commit();
									//echo $data2['token'];
									$this->db->where('token',$data2['token'])->update('user',array('token' => $updatedtoken,'expired_at' => $data2['expired_at']));
									//$this->db->last_query();
									//$unique_user_id = $this->Muser->create('user_authentication',$data2);

									$getInterestbyId = $this->Muser->getInterestbyId($user_id['id']);

									$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($user_id['id']);

									$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
									
									//$getRegisteredUser = $this->Muser->getRegisteredUser();
									
									$getActivationDetailsByUser = $this->Muser->getActivationDetailsByUser($user_id['id']);

									if($getInterestbyId==0){

														$flag=false;

												}

												if($getInterestbyId>0){

														$flag=true;

												}

										}

						 

						            $new_user_details = array(

										'fullname' => $getSubscriptionStatus['fullname'],
										
										'image' => $getSubscriptionStatus['image'],
										
										'loginType' => 'email',

										'is_interest' => $flag,

										'token'=>$updatedtoken,
										
										'user_id'=>$user_id['id'],

										'user_subscription'=>$getSubscriptionStatus['role'],

										'min_radius'=>$getRadiusBySubscription['min'],

										'max_radius'=>$getRadiusBySubscription['max'],
										
										'email' => $getActivationDetailsByUser['email'],
										
										'password' =>$getActivationDetailsByUser['password'] 
										
										/*'gridusers' => $getRegisteredUser */

										);

					 		       $this->Response->outputResponse(200, false, array("response" =>  $new_user_details), '', '', 'Logged in Successfully!!');

						  }
					 		//$this->Response->outputResponse(true, false, array("response" =>  '1'), '', '', 'Activated Successfully!!');

					    }

						

					}

						 else{

					

							$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

					}

					

					/*}*/

			 			

		} 

		

		

		  function test_push_post(){
		  
		  		
					$json = file_get_contents('php://input');

					 $userData = json_decode($json, true);
					 
					 //echo '<pre>';
					 //print_r($userData);
					 //die();
					 
					 if(isset($userData['android_push_reg_id'])){
					 		
							
							$android_push_reg_id=$userData['android_push_reg_id'];
					 
					 }
					 
				$message = 'Hi, you have logged in successfully';
		  		//$this->gcmengine->getGcmPushNotification($message,$android_push_reg_id);
				$this->apnengine->send_ios_notification($android_push_reg_id,$message);
		  
		  }

	    

		/********************************/

		

		// USER LOGIN 

		

		/********************************/

		

		public function login_post() {

		

					 

			         $check_auth_client = $this->check_auth_client();

					 

					 if($check_auth_client == true){

					 $data=array();
					 //echo $userData['email'];
					 $userData=array();
                         
					 $json = file_get_contents('php://input');

					 $userData = json_decode($json, true);
					 
					 //echo '<pre>';
					 //print_r($userData);
					 //die();
					 
					 if(isset($userData['platform'])){
					 		
							
							$platform=$userData['platform'];
					 
					 }
					 
					 if(isset($userData['uuid'])){
					 		
							
							$uuid=$userData['uuid'];
					 
					 }
					 
					 if(isset($userData['android_push_reg_id'])){
					 		
							
							$android_push_reg_id=$userData['android_push_reg_id'];
					 
					 }
					 
					 if(isset($userData['android_push_reg_id'])){
					 		
							
							$android_push_reg_id=$userData['android_push_reg_id'];
					 
					 }

					 if(isset($userData['email'])){

					 //$data['email']=$userData['email'];
					 
					 	$getuserid = $this->Muser->getuseridByToken('',$userData['email']);
					 
					 	$data['email']=$getuserid['email'];

					 }

					  if(isset($userData['password'])){

					 	$data['password']=md5($userData['password']);

					 }
                       /*echo '<pre>';
					   print_r($data);*/
					   
					 $check_user_activation = $this->Muser->check_user_activation($data);
					 	
					 $check_user_login = $this->Muser->check_user_login($data);
					 
					/*if(isset($data['email'])){
					 		
							 $user_id = $this->Muser->get_final_user_id($data['email']);
					 
					 }*/
					 
					 
					 //echo $android_push_reg_id;
					 //echo '<br />';
					 //echo $checkdeviceuser['device_id'];
					
					 		
							if($check_user_login ==0){
		
							 
		
									$errors = 'Invalid Login Details !!';
		
									return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);
		
									}
							else if($check_user_activation == 0){
							 
							 
							
								
									 $this->Response->outputResponse( 500, false, array("response" =>  ''), '', '', 'User not activated !!');
									  
									 }
							else {
		
									$flag = true;
		
									
		
									$data2 = array();
		
									
		
									if(isset($userData['email'])){
											
											
										$user_id = $this->Muser->get_final_user_id($data['email']);
										
										 $device_id = $this->Muser->checkDeviceId($user_id['id']);
											 
										 $device_track = array();
											 
											 if($device_id==0){
														
														
														
														$device_track['user_id'] = $user_id['id'];
														$device_track['device_id'] = $android_push_reg_id;
														$device_track['uuid'] = $uuid;
														$device_track['platform'] = $platform;
														$unique_user_id = $this->Muser->create('device_track',$device_track);
														//$message = 'Hi, you have logged in successfully';
														//$this->gcmengine->getGcmPushNotification($message,$android_push_reg_id);
											 
											 }
										
										
										/* $trackdeviceid  = $this->Muser->trackDeviceid($user_id['id']); 
										  if($trackdeviceid>0){
														
														 $this->db->where('user_id',$user_id['id'])->update('device_track',array('device_id' => $android_push_reg_id));
											 }*/
										 $checkdeviceuser  = $this->Muser->checkDeviceUser($user_id['id'],$android_push_reg_id); 
										 if($checkdeviceuser==0){
					 
					 							$errors = 'You have logged in from another device,please logout first !!';
												return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);
							
					 						}else{
										 
										 	
		
											if(isset($user_id['token'])){
		
											 $oldtoken = $user_id['token'];
											 
											 $data1['user_id'] =$user_id['id']; 
											 
											 
											 
											
											
											 
											 $checkuserlogin = $this->Muser->checkUserLogin($user_id['id']);
											 //echo $checkuserlogin['login_status'];
											 if($checkuserlogin['login_status']=='1'){
													
																$getreceivermessages = $this->Muser->getReceiverMessages($user_id['id']);
																
																//echo '<pre>';
																//print_r($getreceivermessages);
																
																foreach($getreceivermessages as $key=>$val){
																
																				$getnotificationmessage = $this->Muser->getNotificationMessage($val['message_id']);
																				$checkfromlogin = $this->Muser->checkUserLogin($val['sender_id']);
																				$getdeviceid = $this->Muser->getDeviceId($user_id['id']);
																				$message = str_replace("#name", $checkfromlogin['fullname'], $getnotificationmessage['message']);
																				if($getdeviceid['platform']=='android'){
																				$this->gcmengine->getGcmPushNotification($message,$getdeviceid['device_id']);
																				}
																				if($getdeviceid['platform']=='ios'){
																				$this->apnengine->send_ios_notification($getdeviceid['device_id'],$message);
																				}
																
																}
											 
											 }
											 /*else{
											 
														 $message = 'Hi, you have logged in successfully';
														 
														 $device_id = $this->Muser->getDeviceId($user_id['id'],$android_push_reg_id);
																	
														 $this->gcmengine->getGcmPushNotification($message,$android_push_reg_id);
											 }*/
		
										}
		
											$data1['login_timestamp'] =date('Y-m-d H:i:s'); 
		
											$data1['counter'] =1; 
		
											//$data['is_active'] ='1'; 
		
											$unique_user_id = $this->Muser->create('login',$data1);
		
											$last_login = date('Y-m-d H:i:s');
		
											$updatedtoken = crypt(substr(md5(rand()), 0, 7), $password);
		
											$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
		
											$this->db->trans_start();
		
											$this->db->where('token',$oldtoken)->update('user',array('last_login' => $last_login,'login_status' => '1'));
											$this->db->where('user_id',$oldtoken)->update('activationcode',array('user_id' => $updatedtoken));
											
											//$data2['user_id'] = $user_id['id'];
		
											$data2['token'] = $oldtoken ;
		
											$data2['expired_at'] = $expired_at ;
		
											if ($this->db->trans_status() === FALSE){
		
											  $this->db->trans_rollback();
		
											  //return array('status' => 500,'message' => 'Internal server error.');
		
											  $this->Response->outputResponse( 500, false, array("response" =>  $new_user_details), '', '', 'Logged in Successfully!!');
		
										   }else{
		
											$this->db->trans_commit();
											//echo $data2['token'];
											$this->db->where('token',$data2['token'])->update('user',array('token' => $updatedtoken,'expired_at' => $data2['expired_at']));
											//$this->db->last_query();
											//$unique_user_id = $this->Muser->create('user_authentication',$data2);
		
											$getInterestbyId = $this->Muser->getInterestbyId($user_id['id']);
		
											$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($user_id['id']);
		
											$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
											
											
											$getActivationDetailsByUser = $this->Muser->getActivationDetailsByUser($user_id['id']);
											
											//$getRegisteredUser = $this->Muser->getRegisteredUser();
		
											if($getInterestbyId==0){
		
																$flag=false;
		
														}
		
														if($getInterestbyId>0){
		
																$flag=true;
		
														}
		
												}
		
								 
		
											$new_user_details = array(
		
												'fullname' => $getSubscriptionStatus['fullname'],
												
												'image' => $getSubscriptionStatus['image'],
												
												'loginType' => 'email',
		
												'is_interest' => $flag,
		
												'token'=>$updatedtoken,
												
												'user_id'=>$user_id['id'],
		
												'user_subscription'=>$getSubscriptionStatus['role'],
		
												'min_radius'=>$getRadiusBySubscription['min'],
		
												'max_radius'=>$getRadiusBySubscription['max'],
												
												'email'=>$getActivationDetailsByUser['email'],
												
												'password' =>$getActivationDetailsByUser['password']
												
												/*'gridusers' => $getRegisteredUser */
		
												);
		
										   $this->Response->outputResponse(200, false, array("response" =>  $new_user_details), '', '', 'Logged in Successfully!!');
										}
								   
								   }
		
							 }
					 	
					 
					 	
					 
					}else{

			

					$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

			}

					 

				

					 

		}
		
		
		
		/********************************/

		

		// USER LOGOUT 

		

		/********************************/
		
		
		public function logout_post(){
		
						
					$getauthresponse = $this->auth();

				    if($getauthresponse == 200){

					 $data=array();
					 //echo $userData['email'];
					 $userData=array();
                         
					 $json = file_get_contents('php://input');

					 $userData = json_decode($json, true);
					 
					 if(isset($userData['user_ID'])){
					 
					 	 $getuserid = $this->Muser->getuseridByToken($userData['user_ID']);
		   	 		 	 $user_id =$getuserid['id'];
						 $result = $this->Muser->delete('device_track',$user_id,'user_id');
						 $pushresult = $this->Muser->delete('push_notification_message_user_relation',$user_id,'sender_id');
						 //$user_id = $userData['user_id'];
					 }
					 
					 $this->db->where('id',$user_id)->update('user',array('login_status' => '0'));
					 
					 return $this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'You have logged out successfully !!');
					 
					 }
		
		
		}

		

		

		/********************************/

		

		// FORGOT PASSWORD

		

		/********************************/

		

		public function forgot_password_post() {

			//http://192.168.0.1/hivea/api/user/reset_password

			//$json = file_get_contents('php://input');

			//$userData = json_decode($json, true);

			//echo $user_id = $userData['first_name'];

	

			//$data['email'] = $userData['email'];

			//$data['email']          = $this->input->get('email');

			$check_auth_client = $this->check_auth_client();

			

			if($check_auth_client == true){
			
			$userData = array();
			
		    $json = file_get_contents('php://input');

			$userData = json_decode($json, true);		

			$data['email']=$userData['email'];

	        

			$this->form_validation->set_rules('email', '"Email"', 'trim|required|valid_email');



			//validating the user email id

			if ($data['email'] == '') {

				$error = $this->ErrorCodes->codes['invalid_form']; //print_r($error);die;

				$errors = 'Email Address is Required';

				$this->Response->outputResponse(false, $errors, null, '', '', $errors);

			}

			if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {

				$error = $this->ErrorCodes->codes['invalid_form']; //print_r($error);die;

				$errors = 'Invalid Email Format';

				$this->Response->outputResponse(false, $errors, null, '', '', $errors);

			}

			//getting the id of the user

			$user_id = $this->Muser->get_final_user_id($data['email']);

			//$d = $this->Muser->get_active_user_by_email($data['email']);

			//echo $d['id'];

			//print_r($d);

			//$user_name = $this->Account->get_active_user_by_email($data['email'])->user_name;

		if ($user_id['id'] > 0) {

		//echo $user_id['id'];

			//resetting the password with auto generated password

			$new_password = $this->Muser->reset_password($user_id['id']);



			//sending email to user

			//echo $d->user_name;

			$this->send_forgot_password_email($data['email'],$user_id['id'], $new_password);



			//Log password reset email sent

			log_message('info', 'Sent account forgot password email' . $data['email']);



			// Send success response

			$msg = "A email has been send to your account with the email and new password.Please log into your account with the new password";

			$this->Response->outputResponse(true, false, array("response" => array("user_id" => $user_id)), '', '', $msg);

			} else {

				//wrong email id

				$error = $this->ErrorCodes->codes['invalid_email'];

				$this->Response->outputResponse(false, $error->msg_content, null, '', '', $error->msg_content);

			}

		

		}else{

			

					$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

			}

		

	   /*}*/

	}

        

		/********************************/

				

		//sending forgot password mail

				

	   /********************************/

				

		protected function send_forgot_password_email($email,$user_id, $new_password) {

					//$email = $this->Account->get_user_by_id($user_id)->email;

					$date=date('d-m-Y',strtotime(date('Y-m-d')));

					$email_from= 'gridapp@gmail.com';

					$subject = "Grid App: Forgot Password";

					$title = "Reset Password for Grid App User Account";

					$message = "Your request to reset the password is successful.Please use the below access code to LOGIN to Grid App .<p></p>You can also set your preferred password anytime you want.<p></p><b>Access Code : </b> {$new_password}<p>&nbsp;</p><p>&nbsp;</p><p></p>Thanks,<p></p><b>Team Grid App</b><p></p>This is an auto generated email.We request you not to reply.<p></p><b>Date : </b>{$date}";

					//$message = "To log into your account for Bomb Obama, please use the following access information<br /><p><b>Username:</b> {$user_name}</p><p><b>Password:</b> {$new_password}";

					$body = $this->emailoneseven->email_template($user_id, $title, $message);

					$response = $this->emailoneseven->send_email($email, $subject, $body,$email_from);

					return true;

				}

				

	/********************************************/

	

	//RESET PASSWORD 

	

	/*******************************************/

	

	protected function reset_password_post() {

	

					 $check_auth_client = $this->check_auth_client();

					 

					 if($check_auth_client == true){

						

					 $data=array();

					 $userData=array();

					 $json = file_get_contents('php://input');

					 $userData = json_decode($json, true);

					 

					 //$data['activationcode']=$userData['activationcode'];

					 //$data['password']=md5($userData['password']);

					 $data['access_code']=md5($userData['access_code']);

					 $data['new_password']=md5($userData['new_password']);

					 

					 $unique_user_id = $this->Muser->get_user_password_id($data['access_code'],$data['new_password']);

					 

					 if ($unique_user_id == 0)

					{

					

							$errors = 'Please enter correct access code !!';

            				return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

						

					

					}else{

					

							

							$this->Response->outputResponse(true, false, array("response" =>  '1' ), '', '', 'Password reset successfully !!');

					

					}

					

			}

	}

	

	/********************************************/

	

	//SAVE INTEREST BY USER

	

	/*******************************************/

	

	public function save_interest_post() {

				

				

				 $getauthresponse = $this->auth();

				 

				 if($getauthresponse == 200){

				 
                 $userData = array();
				 
				 $json = file_get_contents('php://input');

				 $userData = json_decode($json, true);

				 

				 $data['interest_id']=$userData['interest_id'];

				 $data['email']=$userData['email'];

				 $data['loginType']=$userData['loginType'];

				 
                 if($data['loginType']=='twitter' || $data['loginType']=='facebook' || $data['loginType']=='linkedin'){
				 
				 $user_id = $this->Muser->getUserIdFromEmail($data['email'],$data['loginType']);

				 $getuserid = $this->Muser->getuseridByToken($user_id['token'],'');
				 
				 }
				 if($data['loginType']=='email' ){
				 
				 $user_id = $this->Muser->getUserIdFromEmail($data['email'],$data['loginType']);

				 $getuserid = $this->Muser->getuseridByToken($user_id['token'],'');
				 
				 }

				 if(!empty($userData['subinterest_ids'])){

					 $datarelation=array();

					  //$arr = explode(',',);

					   

					 for($i=0;$i<count($userData['subinterest_ids']);$i++){

					 

					       //echo $userData['subinterest_ids'][$i];

						     $getinterest_id[$i] = $this->Muser->getInterestIdFromUserId($user_id['id'],$userData['subinterest_ids'][$i]);

							 if($getinterest_id[$i]==0){
								//echo $getuserid['id'];
					 		 //$datarelation[$i]['user_id'] = $user_id['id'];
							 
							 $datarelation[$i]['user_id'] = $getuserid['id'];

							 $datarelation[$i]['interest_id'] = $data['interest_id'];

							 $datarelation[$i]['subinterest_id'] = $userData['subinterest_ids'][$i];

							 $datarelation[$i]['created_date'] = date('Y-m-d');

							 $this->Muser->create('interest_user_relation',$datarelation[$i]);

					  		//$unique_user_id = $this->Muser->create('interest_user_relation',$data);

							}

					 

					 

					 		}

							$this->Response->outputResponse(true, false, array("response" =>  '1' ), '', '', 'Details inserted successfully !!');

							

					 }else{

					 

					 		$errors = 'Data not inserted !!';

            				return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

					 }

					 

			}

				 

				

	}

	

	/********************************************/

	

	//DISPLAY INTEREST

	

	/*******************************************/

	

	public function display_interest_post(){

	

		$phone=$this->functions->appsbee_encode('9732158171');

		echo $this->functions->appsbee_decode($phone,"salt.crypt");

		die();	

	

	}

	

	/***********************************************/

	

	// IMPORT CONTACTS FROM PHONE

	

	/**********************************************/

	public function import_contact_post(){

	

		$getauthresponse = $this->auth();
		
		if($getauthresponse == 200){

		    $userData = array();

		    $json = file_get_contents('php://input');

			$userData = json_decode($json, true);

			$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
		    $user_id =$getuserid['id'];
			
			//$user_id = $userData['user_id'];

			$contacts = $userData['contactDetails'];

			

			$sContacts = $this->Muser->contactsByUser($user_id);

		    

			foreach ($contacts as $key => $contact) {

					//$dataimport[$key]['phone_unique']=md5( substr($contact['phone'],-10));
			        $getcountuserimportbyuser[$key] = $this->Muser->checkCountPhoneImportByUser($user_id,$contact['phone']);
					  
					if( !isset($sContacts[md5($contact['phone'])])  && $getcountuserimportbyuser[$key] ==0){ //Check if already exist.

		
						
						if($contact['fullname'] != '' && $contact['phone'] !=''){

							
							
								
								$contacts[$key]['user_id'] = $user_id;
	
								$contacts[$key]['fullname'] = $contact['fullname'];
	
								$contacts[$key]['email'] = $this->functions->appsbee_encode($contact['email']);
	
								$contacts[$key]['phone'] = $this->functions->appsbee_encode($contact['phone']);
	
								//$contacts[$key]['email'] = $this->aessecurity->generateEncryptedSecuredData($this->functions->appsbee_encode($contact['email']));
	
								//$contacts[$key]['phone'] = $this->aessecurity->generateEncryptedSecuredData($this->functions->appsbee_encode($contact['phone']));
	
								$contacts[$key]['phone_unique'] = md5($contact['phone']);
	
								$contacts[$key]['created_date'] = date('Y-m-d H:i:s');
								
								
								

						   }else{

							unset($contacts[$key]);
	
							  }
	
						  }else{
	
							unset($contacts[$key]);
	
						}
					
					

				}

				

				$result = '0';

					if(!empty($contacts)){

						//echo '<pre>';

						//print_r($contacts);
						
						  asort($contacts);
								$result = $this->Muser->import('userimport',$contacts);
						 

						

					}

					    $datanew = array();

						$datas = array();

						$getalluserimportbyuser = $this->Muser->getAllUserImportByUser($user_id);

						

						//echo '<pre>';

						//print_r($getalluserimportbyuser);

						foreach($getalluserimportbyuser as $x=>$importval){

								

									if($importval['email']!=''){

									//$encodedEmail[$x]=$this->aessecurity->generateDecryptedSecuredData($this->functions->appsbee_decode($importval['email'],"salt.crypt"));

									$encodedEmail[$x]=$this->functions->appsbee_decode($importval['email'],"salt.crypt");

									}

									 if($importval['phone']!=''){

									 

									//$encodedPhone[$x]=$this->aessecurity->generateDecryptedSecuredData($this->functions->appsbee_decode($importval['phone'],"salt.crypt"));

									$encodedPhone[$x]=$this->functions->appsbee_decode($importval['phone'],"salt.crypt");

								    }

									$datanew[$x]['import_user_id']=$importval['id'];

									$datanew[$x]['fullname']=$importval['fullname'];

									$datanew[$x]['email']=$encodedEmail[$x];

									$datanew[$x]['phone']=$encodedPhone[$x];

								    //echo '<br/>';

									

									

									if(!empty($datanew[$x])){

										

										//echo $datanew[$x]['phone']=$encodedPhone[$x];

								        //echo '<br/>';

										$count[$x] = $this->Muser->getPhoneNumber($datanew[$x]['phone']);

										$result = $this->Muser->getUserDetailsFromPhoneNumber($datanew[$x]['phone']);

										$datas[$x] = $datanew[$x];
										
										
										$datas[$x]['index'] = $x;

										$datas[$x]['user_id']=($result['token']!='')?$result['token']:NULL;

										$datas[$x]['image']=($result['image']!='')?$result['image']:NULL;

											

									}

								

										if($count[$x]==1){

													$datas[$x]['grid'] = true;

												}

										if($count[$x]==0){

													$datas[$x]['grid'] = false;

												}

										}

										

						    $finalarray=$datas;

							$new_user_details = array(

												'contactDetails' => $finalarray

										);

					

					        return $this->Response->outputResponse(true, false, array("response" =>  $new_user_details), '', '', 'This user has been registered in GridApp!!');

				}

		  }



	/*
	
	{
	"user_id":38,
	"contactDetails":[
		{
			"fullname":"Deb Sinha",
			"email":"deb.sinha@yahoo.com",
			"phone":3445568
		},
		{
			"fullname":"Subham Dey",
			"email":"subham.dey@appsbee.com",
			"phone":3445566
		},
		{
			"fullname":"Rahul Dey",
			"email":"rahul.dey@appsbee.com",
			"phone":3445569
		},
		{
			"fullname":"Ramen Saha",
			"email":"ramen.saha@appsbee.com",
			"phone":3445565
		}
	]
	
	}
	
	public function import_contact_post(){

				

				

			   $getauthresponse = $this->auth();

			   if($getauthresponse == 200){

			   $json = file_get_contents('php://input');

			   $userData = json_decode($json, true);

			   $data['user_id'] = $userData['user_id'];

			   $finalarray = [];

			   $i=0;

			   if(!empty($userData['contactDetails'])){

				asort($userData['contactDetails']);

				 foreach($userData['contactDetails'] as $key=>$val){

				           

						if($val['fullname']!='' && $val['phone']!=''){

								$k=0;

								

								$dataimport[$i]['user_id']=$data['user_id'];

								$dataimport[$i]['fullname']=$val['fullname'];

								

								$dataimport[$i]['email']=$this->functions->appsbee_encode($val['email']);

								$dataimport[$i]['phone']=$this->functions->appsbee_encode($val['phone']);

								

								

								$dataimport[$i]['phone_unique']=md5( substr($val['phone'],-10));

								$dataimport[$i]['created_date']=date("Y-m-d H:i:s");

								

						

			                    $getcountuserimportbyuser[$i] = $this->Muser->getCountUserImportByUser($data['user_id'],$dataimport[$i]['phone_unique']);

								if($getcountuserimportbyuser[$i] == 0){

									

		                         $unique_user_id[$i] = $this->Muser->create('userimport', $dataimport[$i]);										

									

								}

								$getalluserimportbyuser = $this->Muser->getAllUserImportByUser($data['user_id']);

								if(!empty($getalluserimportbyuser)){

								

								foreach($getalluserimportbyuser as $x=>$importval){

								

									if($importval['email']!=''){

									$encodedEmail[$x]=$this->functions->appsbee_decode($importval['email'],"salt.crypt");

									

									}

									 if($importval['phone']!=''){

									 

									$encodedPhone[$x]=$this->functions->appsbee_decode($importval['phone'],"salt.crypt");

									

								    }

									$datanew[$x]['import_user_id']=$importval['id'];

									$datanew[$x]['fullname']=$importval['fullname'];

									$datanew[$x]['email']=$encodedEmail[$x];

									$datanew[$x]['phone']=$encodedPhone[$x];

								

									

									if(!empty($datanew[$x])){

										

									$count[$x] = $this->Muser->getPhoneNumber($datanew[$x]['phone']);

									$result[$x] = $this->Muser->getUserDetailsFromPhoneNumber($datanew[$x]['phone']);

									$datas[$x] = $datanew[$x];

									$datas[$x]['user_id']=$result[$x]['id'];

									$datas[$x]['image']=$result[$x]['image'];

											

									}

								

										if($count[$x]==1){

													$datas[$x]['grid'] = true;

												}

										if($count[$x]==0){

													$datas[$x]['grid'] = false;

												}

										}

								

									

								}

								

								

					}

					

			

					$i++;

							

			}

		       $finalarray=$datas;



	}

				

	$new_user_details = array(

                						'contactDetails' => $finalarray

										);

					

							return $this->Response->outputResponse(true, false, array("response" =>  $new_user_details), '', '', 'This user has been registred in GridApp!!');

					

		}

	

	}*/

	

	

	/************************************************/

	

	//  FACEBOOK DATA IMPORT AND SAVE

		

	/************************************************/

	

	function facebook_import_post(){

	

				 $check_auth_client = $this->check_auth_client();

				if($check_auth_client == true){
				//echo 'aaaa';
				$userData = array();

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

				  //echo $social = $userData['social'];

				   //echo $role = $userData['role'];

				  //echo '<pre>';

				 //print_r($userData);

				  //die();

				  $fblogindata = $userData['fbProfileData'];

				  

				if($userData['social']=='facebook'){

				 
                    $auth_key  = $this->get_header('Uniquetime-Key', TRUE);
					 
					 //die();
					 
				   if(isset($auth_key)){
						 
							$data['token'] = $auth_key;
						 
					}
					
				    if(isset($fblogindata['id'])){

					$data['social_id']= $fblogindata['id'];

					}

					 if(isset($fblogindata['id'])){

					$data['fullname']= $fblogindata['name'];

					}

					

					if(isset($fblogindata['gender']) && $fblogindata['gender']=='male'){

						

							$data['gender']= 'M';

							

						}else if(isset($fblogindata['gender']) && $fblogindata['gender']=='female'){

							

							$data['gender']= 'F';

						}

					if(isset($fblogindata['location']['name'])){

					$data['location']= $fblogindata['location']['name'];

					}

					if(isset($fblogindata['email'])){

					$data['email']=  $fblogindata['email'];

					}

					if(isset($userData['social'])){

					$data['social']= $userData['social'];

					}

					if(isset($userData['role'])){

					$data['role']= $userData['role'];

					}

					$data['created_date']=date('Y-m-d H:i:s');
					
					$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
								
					$data['expired_at'] = $expired_at;

					

					//echo 'INSERT INTO user SET social_id='.$data['social_id'].', fullname='.$data['fullname'].', gender='.$data['gender'].',location='.$data['location'].',email='.$data['email'].',social='.$data['social'].',created_date='.$data['created_date'];

					//echo '<pre>';

					//print_r($data);

					

					$user_id = $this->Muser->get_final_count_user_id_social($fblogindata['id']);

					if($user_id==0){

						$unique_user_id = $this->Muser->create('user',$data);

					}

					
					//echo $fblogindata['id'];
					//die();
					$email = $this->Muser->get_final_user_email_social($fblogindata['id']);
					//echo $email['email'];
					//die();
					
				      if($email['email']!=''){

										//$getsubscription = $this->Muser->getSubscription($payment_type);

								    $getsocialid= $this->Muser->getSocialId($fblogindata['id']);

									$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($getsocialid['id']);
	
									
	
									  $getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
									  
									  if($user_id==0){
											
											$new_user_details = array(
	
																'email' => true,
	
																'social_id' => $twtlogindata['id'],
	
																'loginType'=>$userData['loginType'],
																
																'token'=>$auth_key,
											
																'user_id'=>$unique_user_id,
																
																'user_subscription'=>$getSubscriptionStatus['role'],
	
																'min_radius'=>$getRadiusBySubscription['min'],
						
																'max_radius'=>$getRadiusBySubscription['max']
	
																);
											
											}else{
											
											
											$getInterestbyId = $this->Muser->getInterestbyId($getsocialid['id']);
											
											
											if($getInterestbyId==0){
	
															$flag=false;
	
													}
	
													if($getInterestbyId>0){
	
															$flag=true;
	
													}
	
											
											
											//$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($data['email']);
	
									
	
											$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
											
											//$user_id = $this->Muser->get_final_user_id($getsocialid['id']);
											
											$updatedtoken = crypt(substr(md5(rand()), 0, 7), $getsocialid['social_id']);
											
											$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
		
											//$this->db->trans_start();
											
											$last_login = date('Y-m-d H:i:s');
		
											$this->db->where('id',$getsocialid['id'])->update('user',array('token' => $updatedtoken,'expired_at' => $expired_at));
											
											$new_user_details = array(
	
																'email' => true,
	
																'social_id' => $twtlogindata['id'],
	
																'loginType'=>$getsocialid['social'],
																
																'token'=>$updatedtoken,
											
																'user_id'=>$getsocialid['id'],
																
																'is_interest' =>$flag,
																
																'user_subscription'=>$getSubscriptionStatus['role'],
	
																'min_radius'=>$getRadiusBySubscription['min'],
						
																'max_radius'=>$getRadiusBySubscription['max']
	
																);
																
													}

									}

					  if($email['email']==''){

									

									/*	$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],

															'loginType'=>$userData['loginType']

															);*/
															
										$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($unique_user_id);

								

										$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
										if($user_id==0){
										
										$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],

															'loginType'=>'facebook',
															
															'token'=>$auth_key,
										
															'user_id'=>$unique_user_id,
															
															'user_subscription'=>$getSubscriptionStatus['role'],

															'min_radius'=>$getRadiusBySubscription['min'],
					
															'max_radius'=>$getRadiusBySubscription['max']

															);
										
										}else{
										
										
										
										
										$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($data['email']);

								        $getInterestbyId = $this->Muser->getInterestbyId($data['email']);
										
										
										if($getInterestbyId==0){

														$flag=false;

												}

												if($getInterestbyId>0){

														$flag=true;

												}

										

										$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
										
										$user_id = $this->Muser->get_final_user_id($data['email']);
										
										$updatedtoken = crypt(substr(md5(rand()), 0, 7), $data['email']);
										
										$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
	
										$this->db->trans_start();
											
										$last_login = date('Y-m-d H:i:s');
		
										$this->db->where('email',$data['email'])->update('user',array('token' => $updatedtoken,'expired_at' => $expired_at));
										
										$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],
															
															'is_interest' =>$flag,

															'loginType'=>$userData['loginType'],
															
															'token'=>$updatedtoken,
										
															'user_id'=>$user_id['id'],
															
															'user_subscription'=>$getSubscriptionStatus['role'],

															'min_radius'=>$getRadiusBySubscription['min'],
					
															'max_radius'=>$getRadiusBySubscription['max']

															);
															
												}
												
								}
									
				/*	if($email['email']!=''){

					

						$new_user_details = array(

											'email' => true,

											'social_id' => $fblogindata['id'],

											'loginType'=>$userData['loginType']

											);

					}

					if($email['email']==''){

					

						$new_user_details = array(

											'email' => false,

											'social_id' => $fblogindata['id'],

											'loginType'=>$userData['loginType']

											);

					}*/

					

					if(isset($fblogindata['id'])){

									$user_id = $this->Muser->get_final_user_id_social($fblogindata['id']);

									if(isset($user_id['id'])){

									 $data1['user_id'] = $user_id['id'];

									}

									$data1['login_timestamp'] =date('Y-m-d H:i:s'); 

									$data1['counter'] =1; 

									//$data['is_active'] ='1'; 

									$unique_user_id = $this->Muser->create('login',$data1);

							}

							

							return $this->Response->outputResponse(true, false, array("response" =>  $new_user_details), '', '', 'Facebook details saved successfully!!');

				}

				

				 

			}

				 

				 

	}

	/************************************************/

	

	//  LINKEDIN DATA IMPORT AND SAVE

		

	/************************************************/
	
	function linkedin_import_post(){
	
				 /*$check_auth_client = $this->check_auth_client();
				 
				 if($check_auth_client == true){*/
				 
				 $userData = array();

				 $json = file_get_contents('php://input');

				 $userData = json_decode($json, true);

				 

				 $data = array();

				  $linkedinlogindata = $userData['linkedinlogindata'];
				  
							if($userData['social']=='linkedin'){
		
								
		
								 $auth_key  = $this->get_header('Uniquetime-Key', TRUE);
							 
							 //die();
							 
								 if(isset($auth_key)){
								 
									$data['token'] = $auth_key;
								 
								 }
								 
								 if(isset($linkedinlogindata['id'])){

										$data['social_id']= $linkedinlogindata['id'];

									}
								
								if(isset($twtlogindata['formattedName'])){

										$data['fullname']= $linkedinlogindata['formattedName'];

								}
								/*if(isset($linkedinlogindata['location'])){

										$data['location']= $linkedinlogindata['location'];

								}*/

							  if(isset($linkedinlogindata['emailAddress'])){

								$data['email']=  $linkedinlogindata['emailAddress'];

								}

								if(isset($userData['social'])){

								$data['social']= $userData['social'];

								}

								if(isset($userData['role'])){

								$data['role']= $userData['role'];

								}

								$data['created_date']=date('Y-m-d H:i:s');
                                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
								
								
								$data['expired_at'] = $expired_at;
								

								$user_id = $this->Muser->get_final_count_user_id_social($linkedinlogindata['id']);

									if($user_id==0){

										$unique_user_id = $this->Muser->create('user',$data);

									}

									

								$email = $this->Muser->get_final_user_email_social($linkedinlogindata['id']);
                               
										
                                  //$getInterestbyId = $this->Muser->getInterestbyId($user_id['id']);
							    if($email['email']!=''){

										//$getsubscription = $this->Muser->getSubscription($payment_type);

								    $getsocialid= $this->Muser->getSocialId($linkedinlogindata['id']);

									$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($getsocialid['id']);
	
									
	
									  $getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
									  
									  if($user_id==0){
											
											$new_user_details = array(
	
																'email' => true,
	
																'social_id' => $data['social_id'],
	
																'loginType'=>$data['social'],
																
																'token'=>$auth_key,
											
																'user_id'=>$unique_user_id,
																
																'user_subscription'=>$getSubscriptionStatus['role'],
	
																'min_radius'=>$getRadiusBySubscription['min'],
						
																'max_radius'=>$getRadiusBySubscription['max']
	
																);
											
											}else{
											
											
											$getInterestbyId = $this->Muser->getInterestbyId($getsocialid['id']);
											
											
											if($getInterestbyId==0){
	
															$flag=false;
	
													}
	
													if($getInterestbyId>0){
	
															$flag=true;
	
													}
	
											
											
											//$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($data['email']);
	
									
	
											$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
											
											//$user_id = $this->Muser->get_final_user_id($getsocialid['id']);
											
											$updatedtoken = crypt(substr(md5(rand()), 0, 7), $getsocialid['social_id']);
											
											$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
		
											//$this->db->trans_start();
											
											$last_login = date('Y-m-d H:i:s');
		
											$this->db->where('id',$getsocialid['id'])->update('user',array('token' => $updatedtoken,'expired_at' => $expired_at));
											
											$new_user_details = array(
	
																'email' => true,
	
																'social_id' => $linkedinlogindata['id'],
	
																'loginType'=>$getsocialid['social'],
																
																'token'=>$updatedtoken,
											
																'user_id'=>$getsocialid['id'],
																
																'is_interest' =>$flag,
																
																'user_subscription'=>$getSubscriptionStatus['role'],
	
																'min_radius'=>$getRadiusBySubscription['min'],
						
																'max_radius'=>$getRadiusBySubscription['max']
	
																);
																
													}

									}

								if($email['email']==''){

									

									/*	$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],

															'loginType'=>$userData['loginType']

															);*/
															
										$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($unique_user_id);

								

										$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
										if($user_id==0){
										
										$new_user_details = array(

															'email' => false,

															'social_id' => $data['social_id'],
	
															'loginType'=>'linkedin',
															
															'token'=>$auth_key,
										
															'user_id'=>$unique_user_id,
															
															'user_subscription'=>$getSubscriptionStatus['role'],

															'min_radius'=>$getRadiusBySubscription['min'],
					
															'max_radius'=>$getRadiusBySubscription['max']

															);
										
										}else{
										
										
										
										
										$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($data['email']);

								        $getInterestbyId = $this->Muser->getInterestbyId($data['email']);
										
										
										if($getInterestbyId==0){

														$flag=false;

												}

												if($getInterestbyId>0){

														$flag=true;

												}

										

										$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
										
										$user_id = $this->Muser->get_final_user_id($data['email']);
										
										$updatedtoken = crypt(substr(md5(rand()), 0, 7), $data['email']);
										
										$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
	
										$this->db->trans_start();
											
										$last_login = date('Y-m-d H:i:s');
		
										$this->db->where('email',$data['email'])->update('user',array('token' => $updatedtoken,'expired_at' => $expired_at));
										
										$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],
															
															'is_interest' =>$flag,

															'loginType'=>$userData['loginType'],
															
															'token'=>$updatedtoken,
										
															'user_id'=>$user_id['id'],
															
															'user_subscription'=>$getSubscriptionStatus['role'],

															'min_radius'=>$getRadiusBySubscription['min'],
					
															'max_radius'=>$getRadiusBySubscription['max']

															);
															
												}
									}		
								
								
								 if(isset($linkedinlogindata['id'])){

									$user_id = $this->Muser->get_final_user_id_social($linkedinlogindata['id']);

									if(isset($user_id['id'])){

									 $data1['user_id'] = $user_id['id'];

									}

									$data1['login_timestamp'] =date('Y-m-d H:i:s'); 

									$data1['counter'] =1; 

									//$data['is_active'] ='1'; 

									$unique_user_id = $this->Muser->create('login',$data1);

							}

							

							     return $this->Response->outputResponse(true, false, array("response" =>  $new_user_details), '', '', 'Linkedin details saved successfully!!');

								 
						 }
				 
				/* }*/
	   
	   }

	

	/************************************************/

	

	//  TWITTER DATA IMPORT AND SAVE

		

	/************************************************/

	

	function twitter_import_post(){

	

				 $check_auth_client = $this->check_auth_client();
				 if($check_auth_client == true){

				 $userData = array();

				 $json = file_get_contents('php://input');

				 $userData = json_decode($json, true);

				 

				 $data = array();

				  $twtlogindata = $userData['twtlogindata'];

				  if($userData['social']=='twitter'){

				  		

						 $auth_key  = $this->get_header('Uniquetime-Key', TRUE);
					 
					 //die();
					 
						 if(isset($auth_key)){
						 
							$data['token'] = $auth_key;
						 
						 }

						 if(isset($twtlogindata['id'])){

							$data['social_id']= $twtlogindata['id'];

							}

							 if(isset($twtlogindata['name'])){

							$data['fullname']= $twtlogindata['name'];

							}

							

							if(isset($twtlogindata['location'])){

							$data['location']= $twtlogindata['location'];

							}

							

							if(isset($twtlogindata['email'])){

								$data['email']=  $twtlogindata['email'];

								}

								if(isset($userData['social'])){

								$data['social']= $userData['social'];

								}

								if(isset($userData['role'])){

								$data['role']= $userData['role'];

								}

								$data['created_date']=date('Y-m-d H:i:s');
                                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
								
								$data['expired_at'] = $expired_at;
								

								$user_id = $this->Muser->get_final_count_user_id_social($twtlogindata['id']);

									if($user_id==0){

										$unique_user_id = $this->Muser->create('user',$data);

									}

									

								$email = $this->Muser->get_final_user_email_social($twtlogindata['id']);
                               //echo $email['email'];
							   //die();
										
                                  //$getInterestbyId = $this->Muser->getInterestbyId($user_id['id']);
							    if($email['email']!=''){

										//$getsubscription = $this->Muser->getSubscription($payment_type);

								    $getsocialid= $this->Muser->getSocialId($twtlogindata['id']);

									$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($getsocialid['id']);
	
									
	
									  $getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
									  
									  if($user_id==0){
											
											$new_user_details = array(
	
																'email' => true,
	
																'social_id' => $twtlogindata['id'],
	
																'loginType'=>$userData['loginType'],
																
																'token'=>$auth_key,
											
																'user_id'=>$unique_user_id,
																
																'user_subscription'=>$getSubscriptionStatus['role'],
	
																'min_radius'=>$getRadiusBySubscription['min'],
						
																'max_radius'=>$getRadiusBySubscription['max']
	
																);
											
											}else{
											
											
											$getInterestbyId = $this->Muser->getInterestbyId($getsocialid['id']);
											
											
											if($getInterestbyId==0){
	
															$flag=false;
	
													}
	
													if($getInterestbyId>0){
	
															$flag=true;
	
													}
	
											
											
											//$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($data['email']);
	
									
	
											$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
											
											//$user_id = $this->Muser->get_final_user_id($getsocialid['id']);
											
											$updatedtoken = crypt(substr(md5(rand()), 0, 7), $getsocialid['social_id']);
											
											$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
		
											//$this->db->trans_start();
											
											$last_login = date('Y-m-d H:i:s');
		
											$this->db->where('id',$getsocialid['id'])->update('user',array('token' => $updatedtoken,'expired_at' => $expired_at));
											
											$new_user_details = array(
	
																'email' => true,
	
																'social_id' => $twtlogindata['id'],
	
																'loginType'=>$getsocialid['social'],
																
																'token'=>$updatedtoken,
											
																'user_id'=>$getsocialid['id'],
																
																'is_interest' =>$flag,
																
																'user_subscription'=>$getSubscriptionStatus['role'],
	
																'min_radius'=>$getRadiusBySubscription['min'],
						
																'max_radius'=>$getRadiusBySubscription['max']
	
																);
																
													}

									}

								if($email['email']==''){

									

									/*	$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],

															'loginType'=>$userData['loginType']

															);*/
															
										$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($unique_user_id);

								

										$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
										//echo $user_id;
										if($user_id==0){
										//echo 'aaaa';
										$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],

															'loginType'=>'twitter',
															
															'token'=>$data['token'],
										
															'user_id'=>$unique_user_id,
															
															'user_subscription'=>$getSubscriptionStatus['role'],

															'min_radius'=>$getRadiusBySubscription['min'],
					
															'max_radius'=>$getRadiusBySubscription['max']

															);
										
										}else{
										
										
										//echo 'bbbb';
										
										$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($data['email']);

								        $getInterestbyId = $this->Muser->getInterestbyId($data['email']);
										
										
										if($getInterestbyId==0){

														$flag=false;

												}

												if($getInterestbyId>0){

														$flag=true;

												}

										

										$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);
										
										$user_id = $this->Muser->get_final_user_id($data['email']);
										
										$updatedtoken = crypt(substr(md5(rand()), 0, 7), $data['email']);
										
										$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
	
										$this->db->trans_start();
											
										$last_login = date('Y-m-d H:i:s');
		
										$this->db->where('email',$data['email'])->update('user',array('token' => $updatedtoken,'expired_at' => $expired_at));
										
										$new_user_details = array(

															'email' => false,

															'social_id' => $twtlogindata['id'],
															
															'is_interest' =>$flag,

															'loginType'=>$userData['loginType'],
															
															'token'=>$data['token'],
										
															'user_id'=>$user_id['id'],
															
															'user_subscription'=>$getSubscriptionStatus['role'],

															'min_radius'=>$getRadiusBySubscription['min'],
					
															'max_radius'=>$getRadiusBySubscription['max']

															);
															
												}

									}

					

					        if(isset($twtlogindata['id'])){

									$user_id = $this->Muser->get_final_user_id_social($twtlogindata['id']);

									if(isset($user_id['id'])){

									 $data1['user_id'] = $user_id['id'];

									}

									$data1['login_timestamp'] =date('Y-m-d H:i:s'); 

									$data1['counter'] =1; 

									//$data['is_active'] ='1'; 

									$unique_user_id = $this->Muser->create('login',$data1);

							}

							

							return $this->Response->outputResponse(true, false, array("response" =>  $new_user_details), '', '', 'Twitter details saved successfully!!');

				  

				}

				  

			}

	}

	

	

	/************************************************/

	

	//  EMAIL UPDATE FROM SOCIAL SECTION

		

	/************************************************/

	

	

	function email_update_social_post(){

			

			$check_auth_client = $this->check_auth_client();
		    
			if($check_auth_client == true){


			 
 			 $userData = array();
			 
			 $json = file_get_contents('php://input');

			 $userData = json_decode($json, true);

			 

			  if(isset($userData['social_id'])){

					 		$social_id =$userData['social_id'];

					 }

					 

			 if(isset($userData['email'])){

					 		$data['email']=$userData['email'];

					 }

			$getTwitterEmail = $this->Muser->getTwitterEmail($data['email']);	 
			
			if($getTwitterEmail==0){

			$result = $this->functions->update('user',$data,$social_id,'social_id');

			return $this->Response->outputResponse(true, false, array("response" =>  '1'), '', '', 'Email updated successfully!!');
			
			
			}
			
			else{
			
			
			$result = $this->Muser->delete('user',$social_id,'social_id');
			
			return $this->Response->outputResponse(false, false, array("response" =>  '0'), '', '', 'This user already exsits!!');
			
			}

			

			}

	

	}

	

	/*protected function change_password_post() {

	

					

					$data=array();

					 //$userData=array();

					 //$json = file_get_contents('php://input');

					 //$userData = json_decode($json, true);

					 

					 $data['old_password']=md5($userData['old_password']);

					 $data['new_password']=md5($userData['new_password']);

					 $data['email']=$userData['email'];

					 $data['username']=$userData['username'];

					 

					 $unique_user_id = $this->Muser->get_user_password_id($data['old_password'],$data['new_password'],$data['email'],$data['username']);

					 

					 if ($unique_user_id == 0)

					{

					

							$errors = 'Please enter correct password !!';

            				return $this->Response->outputResponse(false, $error->msg_content, null, '', '', $errors);

						

					

					}else{

					

							

							$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'Password updated successfully !!');

					

					}

					 

					 

					 

	

	}*/

	

	

	/************************************************/

	

	//  CREATE EVENT POST

		

	/************************************************/

	

	public function create_event_post()

		{

			$getauthresponse = $this->auth();

			if($getauthresponse == 200){

			$userData = array();

			$json = file_get_contents('php://input');

			$userData = json_decode($json, true);

			/*echo '<pre>';

			print_r($userData);

			die();*/

			//$email=$userData['email'];

			//$getuserdetails=$this->Muser->getuserdetails($email);

			$data = array();

			$getuserid = $this->Muser->getuseridByToken($userData['user_id'],'');
			
			//$data['user_id']=$userData['user_id'];
			
			$data['user_id']=$getuserid['id'];

			$data['postnumber']=$this->generatenumber->generateNumber(9, 1, 5, 3);

			$data['event_type'] =$userData['event_type'];

			$date =$userData['date'];

			$time =$userData['time'];

			$x = strtotime($date.' '.$time);

			//$data['event_type']  =$userData['date'];

			$data['event_date'] = date('Y-m-d H:i:s', $x);

			$data['formated_date']  = $userData['date'];

			$data['formated_time']  = $userData['time'];

			$data['event_date_timestamp']  = strtotime($data['event_date']);

			$data['maxpersonallowed'] =$userData['maxpersonallowed'];

			$data['post_title'] =$userData['post_title'];

			$data['post_location'] =$userData['post_location'];

			$data['post_lat'] =$userData['post_lat'];

			$data['post_long'] =$userData['post_long'];

 			$data['joinee'] =$userData['joinee'];

			$data['payee'] =$userData['payee'];

			$data['description'] =$userData['description'];

			$data['is_featured'] =$userData['is_featured'];

			//$data['event_status'] =$userData['event_status'];

			$data['created_date'] =date('Y-m-d H:i:s');

			

			$transaction_id = $userData['transaction_id'];

			//print_r($data);

			//die();

			$unique_user_id = $this->Muser->create('events',$data);

			$lastpostid =  $this->db->insert_id();

			

			if(isset($userData['is_featured']) && $userData['is_featured']=='1'){

				

				$payment_type = 'featured';

				$getsubscription = $this->Muser->getSubscription($payment_type);	

				$datapayment = array();

				$datapayment['user_id']=$userData['user_id'];

				$datapayment['post_id']=$lastpostid;

				$datapayment['amount']=$getsubscription['price'];

				$datapayment['transaction_id']=$transaction_id;

				$datapayment['payment_type']=$payment_type;

				$datapayment['payment_created_date']=date('Y-m-d');

				$unique_user_id = $this->Muser->create('paymentlog',$datapayment);

				

			}

			

							  if (!$unique_user_id OR $unique_user_id == 0)

							{

									$errors = 'Problem in creating Event';

									return $this->Response->outputResponse(false, $error->msg_content, null, '', '', $errors);

							}else{	 

							
								$post_id = array('post_id'=>$lastpostid);
								$this->Response->outputResponse(true, false, array("response" =>  $post_id), '', '', 'Event created successfully!!');	

					}

					

			}

	}

		

		

		/************************************************/

	

		//  EDIT  EVENT

		

		/************************************************/

	

		public function edit_event_post()

			{

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					$userData=array();

					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

					/*echo '<pre>';

					print_r($userData);

					die();*/

					$post_id =$userData['post_id'];

					$displayevent = $this->Muser->displayEvent($post_id);

					

					if(!empty($displayevent)){

						$this->Response->outputResponse(true, false, array("response" =>  $displayevent ), '', '', 'Following are the details of the created event !!');

					}else{
                         
						
						$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}

				}

			}

			

	  /************************************************/

	

		//  SAVE  EVENT

		

		/************************************************/

	

	

		public function save_event_post()

			{

					

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					$userData = array();
					
					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

					/*echo '<pre>';

					print_r($userData);

					die();*/

					$post_id =$userData['post_id'];

					

					if(isset($userData['datetime'])){

					$data['datetime'] =$userData['datetime'];

					}

					if(isset($userData['event_type'])){

					$data['event_type'] =$userData['event_type'];

					}

					if(isset($userData['joinee'])){

					$data['joinee'] =$userData['joinee'];

					}

					if(isset($userData['post_title'])){

					$data['post_title'] =$userData['post_title'];

					}

					if(isset($userData['post_location'])){

					$data['post_location'] =$userData['post_location'];

					}

					if(isset($userData['payee'])){

					$data['payee'] =$userData['payee'];

					}

					if(isset($userData['description'])){

					$data['description'] =$userData['description'];

					}

					$displayevent = $this->Muser->updateEvent($post_id,$data);

					

					$this->Response->outputResponse(true, false, array("response" => ''), '', '', 'Event updated successfully !!');

					/*if(!empty($displayevent)){

						$this->Response->outputResponse(true, false, array("response" =>  $displayevent ), '', '', '');

					}else{

						$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}	*/

					

				}

			}

			

	/******************************************************************************/

	

	// DISPLAY EVENT BY LATITUDE AND LONGITUDE AND RADIUS

	

	/******************************************************************************/

	

		public function display_event_post()

			{

				    

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					

					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

					/*echo '<pre>';

					print_r($userData);

					die();*/

					if(isset($userData['event_date'])){

						//echo $userData['event_date'];

						if(isset($userData['date_select_type']) && $userData['date_select_type']=='calendar'){

						//echo $userData['event_date'];

							//$data['event_date'] =date

							$date =$userData['event_date'];

							//$time =$userData['time'];

							$x = strtotime($date);

							$data['event_date'] =date('Y-m-d H:i:s', $x);

							}else{

							$date =$userData['event_date'];

							//$time =$userData['time'];

							$x = strtotime($date);

							$data['event_date'] =date('Y-m-d H:i:s', $x);

							//$data['event_date'] =$userData['event_date'];

							}

					}

					if(isset($userData['date_select_type'])){

					$data['date_select_type'] =$userData['date_select_type'];

					}

					if(isset($userData['post_lat'])){

					$data['post_lat'] =$userData['post_lat'];

					}

					if(isset($userData['post_long'])){

					$data['post_long'] =$userData['post_long'];

					}

					if(isset($userData['radius'])){

					$data['radius'] =$userData['radius'];

					}

					if(isset($userData['user_id'])){
					
					$getuserid = $this->Muser->getuseridByToken($userData['user_id'],'');
					
					$data['user_id'] =$getuserid['id'];

					}

					if(isset($userData['post_filter'])){

					$post_filter =$userData['post_filter'];

					}

					

					$getdisplayevent = $this->Muser->getDisplayEvent($data['post_lat'],$data['post_long'],$data['radius'],$data['event_date'],$data['date_select_type'],$data['user_id'],$post_filter);

					//echo '<pre>';

					//print_r($getdisplayevent);

					if(!empty($getdisplayevent)){

					

					if(is_null($getdisplayevent[0]['id'])){

						 $this->Response->outputResponse(true, false, array("response" =>  '','event_date'=>$getdisplayevent['event_dates'] ), '', '', $getdisplayevent['message']);

					}

					else {

						 $this->Response->outputResponse(true, false, array("response" => $getdisplayevent), '', '', 'List of Events');

						}

						

					}else{

					 if($userData['date_select_type']==''){

						 $this->Response->outputResponse(true, false, array("response" =>  '','event_date'=>date('l, F jS Y',strtotime($userData['event_date'])) ), '', '', 'No record found!!');

						}

					 if(is_null($getdisplayevent[0]['event_date'])&& $userData['date_select_type']=='prev'){

						  $this->Response->outputResponse(true, false, array("response" =>  '','event_date'=>date('l, F jS Y',strtotime($userData['event_date'])) ), '', '', 'No date found!!');

					 }

					 if(is_null($getdisplayevent[0]['event_date'])&& $userData['date_select_type']=='next'){

						  $this->Response->outputResponse(true, false, array("response" =>  '','event_date'=>date('l, F jS Y',strtotime($userData['event_date'])) ), '', '', 'No date found!!');

					 }

					 if(is_null($getdisplayevent[0]['event_date'])&& $userData['date_select_type']=='calendar'){

						  $this->Response->outputResponse(true, false, array("response" =>  '','event_date'=>date('l, F jS Y',strtotime($userData['event_date']))), '', '', 'No date found!!');

					  }

				}

				

		  }

	}	

			

	  

	/******************************************************************************/

	

	// DISPLAY EVENT USERS  IN DASHBOARD

		

	/******************************************************************************/

	

	public function display_event_user_dashboard_post()

			{

					

					

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					

					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

					

					if(isset($userData['user_id'])){

					

								//$user_id = $userData['user_id'];
								
								$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					 			$user_id =$getuserid['id'];

					

					}

					if(isset($userData['type'])){

					

								$type = $userData['type'];

					

					}

					

					

					$geteventuser = $this->Muser->getEventUser($user_id,$type);

					

					if(!empty($geteventuser)){

					

						 $getrequestgoingcount = $this->Muser->getRequestGoingCount($user_id);

						 

						 $this->Response->outputResponse(true, false, array("request_count"=>$getrequestgoingcount,"response" => $geteventuser), '', '', 'List of Users in Event Dashboard');

						

					}else{

						

						 $getrequestgoingcount = $this->Muser->getRequestGoingCount($user_id);

						 $this->Response->outputResponse(true, false, array("request_count"=>$getrequestgoingcount,"response" =>  '' ), '', '', 'No users found!!');

						}

					

					}

			

			}

	/************************************************/

	

	//  GET  POST VIEW SECTION FOR OWNER

			

    /************************************************/

	

	

	public function view_event_owner_post()

		{

					

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					

					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

				

					if(isset($userData['user_id'])){

						
						$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					    $user_id =$getuserid['id'];
						//$user_id = $userData['user_id'];

					}

					

					if(isset($userData['event_id'])){

						$event_id = $userData['event_id'];

					}

					

						$eventviewownerdetails = $this->Muser->eventOwnerViewDetails($user_id,$event_id);

						

						if(!empty($eventviewownerdetails)){

					

						 $this->Response->outputResponse(true, false, array("response" => $eventviewownerdetails), '', '', ' Owner Event View');

						

					}else{

					

						 $this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}

					

					

				}

		}

	

	

	

	/************************************************/

	

	//  GET  POST VIEW SECTION FOR OTHER

			

    /************************************************/

	

	

		public function view_event_other_post()

		{

					

					

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					

					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

				

					if(isset($userData['user_id'])){

						
						$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					 	$user_id =$getuserid['id'];
						//$user_id = $userData['user_id'];

					}

					

					if(isset($userData['event_id'])){

						$event_id = $userData['event_id'];

					}

					

					$eventviewotherdetails = $this->Muser->eventOtherViewDetails($user_id,$event_id);

					

					if(!empty($eventviewotherdetails)){

					

						 $this->Response->outputResponse(true, false, array("response" => $eventviewotherdetails), '', '', ' Other Event View');

						

					}else{

					

						 $this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}

					

					

				}

		

		}

	  

	  /*****************************************************************/

	

	 //  GET  POST VIEW SECTION REQUEST UPDATE FOR OTHER

			

     /*****************************************************************/

	 

	 

	 public function view_event_update_post()

		{

					

					

					$getauthresponse = $this->auth();

					if($getauthresponse == 200){

					

					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

				

					if(isset($userData['row_id'])){

						$row_id = $userData['row_id'];

					}

					if(isset($userData['status'])){

						$status = $userData['status'];

					}

					

					$eventviewupdatedetails = $this->Muser->eventUpdateViewDetails($row_id,$status);

					//echo '<pre>';

					//print_r($eventviewupdatedetails);

					if(!empty($eventviewupdatedetails['is_approve'])){

						 $arr = array('is_approve'=>$eventviewupdatedetails['is_approve'],'availableperson'=>$eventviewupdatedetails['availableperson']);

						 $this->Response->outputResponse(true, false, array("response" =>$arr  ), '', '', 'Updated successfully');

						

					}else{

					

						 $this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}

					

					

				}

			}

	

	

	  /************************************************/

	

	  //  PROFILE DISPLAY

		

	  /************************************************/

		

		public function profile_display_post()

			{

					
					
					$getauthresponse = $this->auth();
					
					//echo $getauthresponse;
					
					if($getauthresponse == 200){

					$userData = array();

					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

					//echo '<pre>';

					//print_r($userData['sender_id']);

					//die();

					/*if(isset($userData['email'])){*/

					

						//$email=$userData['email'];

						//$getuserdetails=$this->Muser->getuserdetails($email);

						//$user_id =$getuserdetails['id'];

						

					/*}*/

					if(isset($userData['sender_id'])){

					$getuserid = $this->Muser->getuseridByToken($userData['sender_id']);
					$sender_id = $getuserid['id'];

					}

					

					if(isset($userData['receiver_id'])){

					
					$getuserid = $this->Muser->getuseridByToken($userData['receiver_id']);
					$receiver_id = $getuserid['id'];

					}

					if(isset($userData['user_id'])){

					
					$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					$user_id = $getuserid['id'];

					}

					

					$getuserprofiledata = $this->Muser->getUserProfileData($sender_id,$receiver_id,$user_id);
					
					//echo '<pre>';
					
					//print_r($getuserprofiledata);

					//die();

					if(!empty($getuserprofiledata)){

					

						 $this->Response->outputResponse(true, false, array("response" => $getuserprofiledata), '', '', 'Profile Details');

						

					}else{

					

						 $this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

						}

					

					

					 

				}

					

					

			} 

			

	/************************************************/

	

	//  BACKGROUND MULTIPLE IMAGE SAVE

			

    /************************************************/

	

	public function background_image_post()

			{

					
				     /*$userData = array();
					 
					 $json = file_get_contents('php://input');

					 $userData = json_decode($json, true);*/
				

				 /*$check_auth_image = $this->check_auth_image();

				 if($check_auth_image == true){*/

				

				//echo '<pre>'

				//return $this->Response->outputResponse(true, false, array("response" => count($_FILES)), '', '', 'Background Image updated successfully !!');

				

				//die();

				$data   = array();
				
				$userData = array();

				//$image  = count($_FILES['file']);

				//$number_of_files = sizeof($_FILES['file']['tmp_name']);
				
				$getuserid = $this->Muser->getuseridByToken($_POST['user_id']);
				$user_id =$getuserid['id'];

				//$user_id=$_POST['user_id'];

				$cover_pic_1=$_FILES['cover_pic_1'];

				$cover_pic_2=$_FILES['cover_pic_2'];

				$cover_pic_3=$_FILES['cover_pic_3'];

				//echo '<pre>';

				//print_r($cover_pic_1);

				if($cover_pic_1['name']!=''){

				

				$cover_image_1=$cover_pic_1['name'];

				

				} 

				if($cover_pic_2['name']!=''){

				

				$cover_image_2=$cover_pic_2['name'];

				

				}

				if($cover_pic_3['name']!=''){

				

				$cover_image_3=$cover_pic_3['name'];

				

				}

				

				$databackimage  = $this->_uploadbackImage( $cover_image_1,$cover_image_2,$cover_image_3,$user_id );

				if(!empty($databackimage)){

				return $this->Response->outputResponse(true, false, array("response" => $databackimage ), '', '', 'Background Image updated successfully !!'); 

				}else{

				return $this->Response->outputResponse(true, false, array("response" => ''), '', '', 'Background Image not found !!'); 

				}

				

				

				//die();

				/*if($number_of_files >0){

				

					$files = $_FILES['file'];

					$this->load->library('image_lib');

					$bigFolder      = './uploads/background/big/';

					$thumbFolder    = './uploads/background/thumb/';

					$configUpload['upload_path'] = $bigFolder;

					$configUpload['allowed_types'] = '*';

					$configUpload['encrypt_name'] = TRUE;

					$this->load->library('upload',$configUpload);

					$uploadedFileNames = array();

					$dataimg=array();

					$ImageArray = array();

					$getimagecountbyuserid = $this->Muser->getImageCountByUserid($user_id); 

					//die();

					if($getimagecountbyuserid==0){

					

					//start each file upload

							for ($i = 0; $i < $number_of_files; $i++)

							{

							  $_FILES['file']['name'] = $files['name'][$i];

							  $_FILES['file']['type'] = $files['type'][$i];

							  $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];

							  $_FILES['file']['error'] = $files['error'][$i];

							  $_FILES['file']['size'] = $files['size'][$i];

				

							  $this->upload->initialize($configUpload);

							  $flag = FALSE;

							  list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);

						

								$aspect_ratio = $width/$height;

								$adjusted_height = 100;

								$adjusted_width = 100;

								

								

								$configThumb = array();  

								$configThumb['image_library']   = 'gd2';  

								$configThumb['create_thumb']    = TRUE;

								$configThumb['new_image']       = $thumbFolder;  

								$configThumb['maintain_ratio']  = FALSE;

								$configThumb['width']           = $adjusted_width;  

								$configThumb['height']          = $adjusted_height;

								$configThumb['thumb_marker']    = "";

								if ($this->upload->do_upload('file'))

								{

								$uploadedData = $this->upload->data();

								

								if($uploadedData['is_image'] == 1 )

									{

										$configThumb['source_image']        = $uploadedData['full_path'];

										$configThumbMedium['source_image']  = $uploadedData['full_path'];

										$raw_name                           = $uploadedData['raw_name'];

										$file_ext                                 = $uploadedData['file_ext'];

										$imgname                             = $raw_name.$file_ext;

										//echo '<br/>';

										$this->image_lib->initialize($configThumb);

										$this->image_lib->resize();

										$dataimg[$i]['user_id'] = $user_id;

										$dataimg[$i]['image'] = $imgname;

										$dataimg[$i]['created_date'] = date('Y-m-d');

										$this->Muser->create('userbackground',$dataimg[$i]);

										$ImageArray[$i]['image_path'] = 'http://grid.digiopia.in/uploads/background/thumb/'.$imgname;

										

									}

									

								 

							  }

							  $this->image_lib->initialize($configThumb);

							  $this->image_lib->resize();

							  }

							  return $this->Response->outputResponse(true, false, array("response" => $ImageArray ), '', '', 'Background Image updated successfully !!');

				

				       }

					   else{

					   

					   		$unlinkbackimage = $this->Muser->unlinkbackgroundimage($user_id);

							

							foreach($unlinkbackimage as $key=>$val){

									$thumbFile[$key] = './uploads/background/thumb/'.$val['image'];

									$bigFile[$key] = './uploads/background/big/'.$val['image'];

										if(is_file($bigFile[$key])) {

												unlink($thumbFile[$key]); // delete thumb file

												unlink($bigFile[$key]); // delete big file

										}	

								}

								

							$deletebackimage = 	 $this->Muser->deletebackimage($user_id);

							

							$getimagecountbyuserid = $this->Muser->getImageCountByUserid($user_id); 

								//die();

								if($getimagecountbyuserid==0){

								

								//start each file upload

										for ($i = 0; $i < $number_of_files; $i++)

										{

										  $_FILES['file']['name'] = $files['name'][$i];

										  $_FILES['file']['type'] = $files['type'][$i];

										  $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];

										  $_FILES['file']['error'] = $files['error'][$i];

										  $_FILES['file']['size'] = $files['size'][$i];

							

										  $this->upload->initialize($configUpload);

										  $flag = FALSE;

										  list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);

									

											$aspect_ratio = $width/$height;

											$adjusted_height = 100;

											$adjusted_width = 100;

											

											

											$configThumb = array();  

											$configThumb['image_library']   = 'gd2';  

											$configThumb['create_thumb']    = TRUE;

											$configThumb['new_image']       = $thumbFolder;  

											$configThumb['maintain_ratio']  = FALSE;

											$configThumb['width']           = $adjusted_width;  

											$configThumb['height']          = $adjusted_height;

											$configThumb['thumb_marker']    = "";

											if ($this->upload->do_upload('file'))

											{

											$uploadedData = $this->upload->data();

											if($uploadedData['is_image'] == 1 )

												{

													$configThumb['source_image']        = $uploadedData['full_path'];

													$configThumbMedium['source_image']  = $uploadedData['full_path'];

													$raw_name                           = $uploadedData['raw_name'];

													$file_ext                                 = $uploadedData['file_ext'];

													$imgname                             = $raw_name.$file_ext;

													//echo '<br/>';

													$this->image_lib->initialize($configThumb);

													$this->image_lib->resize();

													$dataimg[$i]['user_id'] = $user_id;

													$dataimg[$i]['image'] = $imgname;

													$dataimg[$i]['created_date'] = date('Y-m-d');

													$this->Muser->create('userbackground',$dataimg[$i]);

													$ImageArray[$i]['image_path'] = 'http://grid.digiopia.in/uploads/background/thumb/'.$imgname;

												}

												

											 

										  }

										  $this->image_lib->initialize($configThumb);

										  $this->image_lib->resize();

										  }

										  return $this->Response->outputResponse(true, false, array("response" => $ImageArray ), '', '', 'Background Image updated successfully !!');

							

					   }

				 

			    }

				

		}

		        else{

				

						$uploadData =  array(

							'image_path'   => 'No image found'

						);

						return $this->Response->outputResponse(true, false, array("response" => $uploadData ), '', '', 'Background Image not found !!'); 	

				

				}*/

				

			/*}

					else{

			

					$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

				 }*/

		

		}

		         

		public function _uploadbackImage( $cover_image_1,$cover_image_2,$cover_image_3,$user_id )

			{

		//echo 'inside _uploadImage--->'.$image['name'] ;

        

		

		$data   = array();

		$unlinkimage = $this->Muser->unlinkimage($user_id);

		

		if($cover_image_1!=''){

		$thumbFile = './uploads/background/thumb/'.$unlinkimage['cover_pic_1'];

		$bigFile = './uploads/background/big/'.$unlinkimage['cover_pic_1'];

		if(is_file($bigFile)) {

					unlink($thumbFile);

					unlink($bigFile); // delete file

			}

		}

		if($cover_image_2!=''){

		$thumbFile = './uploads/background/thumb/'.$unlinkimage['cover_pic_2'];

		$bigFile = './uploads/background/big/'.$unlinkimage['cover_pic_2'];

		if(is_file($bigFile)) {

					unlink($thumbFile);

					unlink($bigFile); // delete file

			}

		}

		if($cover_image_3!=''){

		$thumbFile = './uploads/background/thumb/'.$unlinkimage['cover_pic_3'];

		$bigFile = './uploads/background/big/'.$unlinkimage['cover_pic_3'];

		if(is_file($bigFile)) {

					unlink($thumbFile);

					unlink($bigFile); // delete file

			}

		}

			

        $bigFolder      = './uploads/background/big/';

        $thumbFolder    = './uploads/background/thumb/';

        

 		 if( isset( $cover_image_1 ) && $cover_image_1 != "" )

		{

            //print_r($cover_image_1); die;



           list($width, $height, $type, $attr) = getimagesize($cover_image_1['tmp_name']);

            

            $aspect_ratio = $width/$height;

			$adjusted_width = 1242;

			$adjusted_height = 495;

           // $adjusted_height = 100;

           // $adjusted_width = round(100*$aspect_ratio,0);

            

            $this->load->library('image_lib');

            

            $configUpload['upload_path']    = $bigFolder;

            $configUpload['allowed_types']  = 'jpg|png|jpeg|gif';

            $configUpload['max_size']       = '0';

            $configUpload['max_width']      = '0';

            $configUpload['max_height']     = '0';

            $configUpload['encrypt_name']   = true;

            $this->load->library('upload', $configUpload);

            

            $configThumb = array();  

            $configThumb['image_library']   = 'gd2';  

            $configThumb['create_thumb']    = TRUE;

            $configThumb['new_image']       = $thumbFolder;  

            $configThumb['maintain_ratio']  = FALSE;

            $configThumb['width']           = $adjusted_width;  

            $configThumb['height']          = $adjusted_height;

            $configThumb['thumb_marker']    = "";

            //echo $this->upload->do_upload('file');

            if( !$this->upload->do_upload('cover_pic_1') )

			{

			//echo 'aaaa';

                $data   = $this->wrap_error('Error uploading the image, Try again!');

            }

			else

			{

			

				$uploadedDetails    = $this->upload->data();

				//echo $uploadedDetails['is_image'];

				if( $uploadedDetails['is_image'] == 1 )

				{

					$configThumb['source_image']        = $uploadedDetails['full_path'];

					$configThumbMedium['source_image']  = $uploadedDetails['full_path'];

					$raw_name1                           = $uploadedDetails['raw_name'];

					$file_ext1                           = $uploadedDetails['file_ext'];

					$imgname1                            = $raw_name1.$file_ext1;

					

					$this->image_lib->initialize($configThumb);

					$this->image_lib->resize();

					$data['cover_pic_1']= $imgname1;

					$updateprofileimage = $this->Muser->updatebackimage($data,$user_id);

					$uploadData1 =  array(

						'image_path'   => 'http://grid.digiopia.in/uploads/background/thumb/'.$imgname1

					);

				}

				

				

				/*echo '<pre>';

				print_r($uploadData1);*/

                

                }

			}

			

		 if( isset( $cover_image_2 ) && $cover_image_2 != "" )

		{

           // print_r($cover_image_2); die;



           list($width, $height, $type, $attr) = getimagesize($cover_image_2['tmp_name']);

            

            $aspect_ratio = $width/$height;

			$adjusted_width = 1242;

			$adjusted_height = 495;

            /*$adjusted_height = 100;

            $adjusted_width = round(100*$aspect_ratio,0);*/

            

            $this->load->library('image_lib');

            

            $configUpload['upload_path']    = $bigFolder;

            $configUpload['allowed_types']  = 'jpg|png|jpeg|gif';

            $configUpload['max_size']       = '0';

            $configUpload['max_width']      = '0';

            $configUpload['max_height']     = '0';

            $configUpload['encrypt_name']   = true;

            $this->load->library('upload', $configUpload);

            

            $configThumb = array();  

            $configThumb['image_library']   = 'gd2';  

            $configThumb['create_thumb']    = TRUE;

            $configThumb['new_image']       = $thumbFolder;  

            $configThumb['maintain_ratio']  = FALSE;

            $configThumb['width']           = $adjusted_width;  

            $configThumb['height']          = $adjusted_height;

            $configThumb['thumb_marker']    = "";

            //echo $this->upload->do_upload('file');

            if( !$this->upload->do_upload('cover_pic_2') )

			{

			//echo 'aaaa';

                $data   = $this->wrap_error('Error uploading the image, Try again!');

            }

			else

			{

			

				$uploadedDetails    = $this->upload->data();

				//echo $uploadedDetails['is_image'];

				if( $uploadedDetails['is_image'] == 1 )

				{

					$configThumb['source_image']        = $uploadedDetails['full_path'];

					$configThumbMedium['source_image']  = $uploadedDetails['full_path'];

					$raw_name2                           = $uploadedDetails['raw_name'];

					$file_ext2                           = $uploadedDetails['file_ext'];

					$imgname2                            = $raw_name2.$file_ext2;

					

					$this->image_lib->initialize($configThumb);

					$this->image_lib->resize();

					$data['cover_pic_2']= $imgname2;

					$updatebackimage = $this->Muser->updatebackimage($data,$user_id);

					$uploadData2 =  array(

						'image_path'   => 'http://grid.digiopia.in/uploads/background/thumb/'.$imgname2

					);

				}

				

				

				/*echo '<pre>';

				print_r($uploadData2);*/

				

                

                }

			}

			

		 if( isset( $cover_image_3 ) && $cover_image_3 != "" )

		{

           // print_r($cover_image_3); die;



           list($width, $height, $type, $attr) = getimagesize($cover_image_3['tmp_name']);

            

            $aspect_ratio = $width/$height;

			$adjusted_width = 1242;

			$adjusted_height = 495;

            /*$adjusted_height = 100;

            $adjusted_width = round(100*$aspect_ratio,0);*/

            

            $this->load->library('image_lib');

            

            $configUpload['upload_path']    = $bigFolder;

            $configUpload['allowed_types']  = 'jpg|png|jpeg|gif';

            $configUpload['max_size']       = '0';

            $configUpload['max_width']      = '0';

            $configUpload['max_height']     = '0';

            $configUpload['encrypt_name']   = true;

            $this->load->library('upload', $configUpload);

            

            $configThumb = array();  

            $configThumb['image_library']   = 'gd2';  

            $configThumb['create_thumb']    = TRUE;

            $configThumb['new_image']       = $thumbFolder;  

            $configThumb['maintain_ratio']  = FALSE;

            $configThumb['width']           = $adjusted_width;  

            $configThumb['height']          = $adjusted_height;

            $configThumb['thumb_marker']    = "";

            //echo $this->upload->do_upload('file');

            if( !$this->upload->do_upload('cover_pic_3') )

			{

			//echo 'aaaa';

                $data   = $this->wrap_error('Error uploading the image, Try again!');

            }

			else

			{

			

				$uploadedDetails    = $this->upload->data();

				//echo $uploadedDetails['is_image'];

				if( $uploadedDetails['is_image'] == 1 )

				{

					$configThumb['source_image']        = $uploadedDetails['full_path'];

					$configThumbMedium['source_image']  = $uploadedDetails['full_path'];

					$raw_name3                           = $uploadedDetails['raw_name'];

					$file_ext3                           = $uploadedDetails['file_ext'];

					$imgname3                            = $raw_name3.$file_ext3;

					

					$this->image_lib->initialize($configThumb);

					$this->image_lib->resize();

					

					$data['cover_pic_3']= $imgname3;

					$updatebackimage = $this->Muser->updatebackimage($data,$user_id);

					$uploadData3 =  array(

						'image_path'   => 'http://grid.digiopia.in/uploads/background/thumb/'.$imgname3

					);

					

					/*echo '<pre>';

					print_r($uploadData3);*/

				}

				

				

				

                

                }

			}

			

			$uploaddata = [];

			if(!empty($uploadData1)){

			//echo 'aaaa';

			$uploaddata[]=$uploadData1;

			

			}

			 if(!empty($uploadData2)){

			//echo 'bbbb';

			$uploaddata[]=$uploadData2;

			

			}

			if(!empty($uploadData3)){

			//echo 'cccc';

			$uploaddata[]=$uploadData3;

		

			

			}

		

        	return $uploaddata;

			

		

    }	

	

			

	/************************************************/

	

	//  PROFILE IMAGE SAVE

			

    /************************************************/

	

	public function profile_image_post()

		{

				  

				  

				  /*$check_auth_image = $this->check_auth_image();

				  if($check_auth_image == true){*/

				 	 /*$userData = array();
					 
					 $json = file_get_contents('php://input');

					 $userData = json_decode($json, true);*/

					 $data   = array();

					 $image  = $_FILES['file'];

					 

					 //print_r($_FILES['file']); die;

					if(!empty($image)){

				

					$paramValue = $userData['options'];

					//echo $userData['user_id'];
					
					$getuserid = $this->Muser->getuseridByToken($_POST['user_id']);
					$user_id =$getuserid['id'];

					if(isset($user_id)){	

							

						$email=$userData['email'];

						$getuserdetails=$this->Muser->getuserdetails($email);



						//$user_id =$_POST['user_id'];
						
						//$user_id =$_POST['user_id'];

						$dataimage  = $this->_uploadImage( $image,$user_id );

						return $this->Response->outputResponse(true, false, array("response" => $dataimage ), '', '', 'Profile Image updated successfully !!'); 	

					}

				 }else{

						

						$uploadData =  array(

							'image_path'   => 'No image found'

						);

						return $this->Response->outputResponse(false, false, array("response" => $uploadData ), '', '', 'Profile Image not found !!'); 	

									 

				 	}

				 /*}else{

			

					$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

				 }*/

				

			}

			

	 public function _uploadImage( $image,$user_id )

		{

		//echo 'inside _uploadImage--->'.$image['name'] ;

		

		

        $data   = array();

		$unlinkimage = $this->Muser->unlinkimage($user_id);

		$thumbFile = './uploads/profile/thumb/'.$unlinkimage['image'];

		$bigFile = './uploads/profile/big/'.$unlinkimage['image'];

			if(is_file($bigFile)) {

					unlink($thumbFile);

					unlink($bigFile); // delete file

			}

        //$id=1;

        /*if( !is_dir('./uploads/profile/user-'.$id) )

        {

		echo './uploads/profile/user-'.$id;

            mkdir( './uploads/profile/user-'.$id );

        }

        if( !is_dir('./uploads/profile/user-'.$id.'/thumb') )

        {

            mkdir( './uploads/profile/user-'.$id.'/thumb' );

        }

        if( !is_dir('./uploads/profile/user-'.$id.'/big') )

        {

            mkdir( './uploads/profile/user-'.$id.'/big' );

        }*/

        $bigFolder      = './uploads/profile/big/';

        $thumbFolder    = './uploads/profile/thumb/';

		

		/*if( isset( $image['name'] ) && $image['name'] != "" )

		{

					

					$configUpload['upload_path'] = $bigFolder;

					$configUpload['allowed_types'] = 'jpg|png|jpeg|gif';

					$configUpload['file_name'] = $image['name'];

					//$config['overwrite'] = TRUE;

					$configUpload['max_size'] = '0';

					$configUpload['max_width']  = '0';

					$configUpload['max_height']  = '0';

					$configUpload['encrypt_name']   = true;

					 

					$this->load->library('upload', $configUpload);

					 

					if(!is_dir($configUpload['upload_path'])){

						mkdir($configUpload['upload_path'], 0755, TRUE);

					}

					 

					if (!$this->upload->do_upload('file')){ //Upload file

								//redirect("errorhandler"); //If error, redirect to an error page

								echo 'error1';

							}else{

								

								

						$upload_data = $this->upload->data();

						$image_config["image_library"] = "gd2";

						$image_config["source_image"] = $upload_data["full_path"];

						$image_config['create_thumb'] = FALSE;

						$image_config['maintain_ratio'] = TRUE;

						$image_config['new_image'] = $upload_data["file_path"] . $config['file_name'];

						$image_config['quality'] = "100%";

						$image_config['width'] = 800;

						$image_config['height'] = 600;

						$dim = (intval($upload_data["image_width"]) / intval($upload_data["image_height"])) - ($image_config['width'] / $image_config['height']);

						$image_config['master_dim'] = ($dim > 0)? "height" : "width";

						 

						$this->load->library('image_lib');

						$this->image_lib->initialize($image_config);

						 

						if(!$this->image_lib->resize()){ //Resize image

							echo 'error2'; //If error, redirect to an error page

						}else{

							//$this->load->library('image_lib');

							//echo '<pre>';

							//print_r($upload_data);

							$image_configs['image_library'] = 'gd2';

							$image_configs['source_image'] = $upload_data["full_path"];

							$image_configs['new_image'] = $thumbFolder;

							$image_configs['quality'] = "100%";

							$image_configs['maintain_ratio'] = FALSE;

							$image_configs['width'] = 800;

							$image_configs['height'] = 600;

							$image_configs['x_axis'] = '0';

							$image_configs['y_axis'] = '0';

							 

							$this->image_lib->clear();

							$this->image_lib->initialize($image_configs); 

							 

							if (!$this->image_lib->resize()){

									echo 'error3';//If error, redirect to an error page

							}else{

								

								$data['image']= $upload_data["raw_name"].$upload_data["file_ext"];

								$imgname= $upload_data["raw_name"].$upload_data["file_ext"];

								$updateprofileimage = $this->Muser->updateprofileimage($data,$user_id);

								$uploadData =  array(

									'image_path'   => 'http://grid.digiopia.in/uploads/profile/thumb/'.$imgname

								);

								//redirect("successpage");

							}

							

							

						}

						

				}

  

			

		}*/

        

 		 /*if( isset( $image['name'] ) && $image['name'] != "" )

		{

           //echo $image['name'];



           //list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);

		   $original_size = getimagesize($image['tmp_name']);

            

            //$aspect_ratio = $width/$height;

            //$adjusted_height = $height;

			//$adjusted_width =  200;

            //$adjusted_width = round(100*$aspect_ratio,0);

            

            $this->load->library('image_lib');

            //$this->image_lib->fit();

            $configUpload['upload_path']    = $bigFolder;

            $configUpload['allowed_types']  = 'jpg|png|jpeg|gif';

            $configUpload['max_size']       = '0';

            $configUpload['max_width']      = '0';

            $configUpload['max_height']     = '0';

            $configUpload['encrypt_name']   = true;

            $this->load->library('upload', $configUpload);

            

            $configThumb = array();  

            $configThumb['image_library']   = 'gd2';  

            $configThumb['create_thumb']    = TRUE;

            $configThumb['new_image']       = $thumbFolder;

            $configThumb['maintain_ratio']  = FALSE; 

			$configThumb['quality'] = "80%";

            $configThumb['width']           = 200; 

            $configThumb['height']          = 200;

			//$dim = (intval($original_size[0]) / intval($original_size[1])) - ($configThumb['width'] / $configThumb['height']);

			//$configThumb['master_dim'] = ($dim > 0)? "height" : "width";

            $configThumb['thumb_marker']    = "";

            //echo $this->upload->do_upload('file');

            if( !$this->upload->do_upload('file') )

			{

			//echo 'aaaa';

                $data   = $this->wrap_error('Error uploading the image, Try again!');

            }

			else

			{

			

				$uploadedDetails    = $this->upload->data();

				//echo $uploadedDetails['is_image'];

				if( $uploadedDetails['is_image'] == 1 )

				{

					$configThumb['source_image']        = $uploadedDetails['full_path'];

					$configThumbMedium['source_image']  = $uploadedDetails['full_path'];

					$raw_name                           = $uploadedDetails['raw_name'];

					$file_ext                           = $uploadedDetails['file_ext'];

					$imgname                            = $raw_name.$file_ext;

					

					$this->image_lib->initialize($configThumb);

					//$this->image_lib->crop();

					$this->image_lib->resize();

				}

				$data['image']= $imgname;

				$updateprofileimage = $this->Muser->updateprofileimage($data,$user_id);

				$uploadData =  array(

					'image_path'   => 'http://grid.digiopia.in/uploads/profile/thumb/'.$imgname

				);

				

                

                }

		  }*/

		  

		  if( isset( $image['name'] ) && $image['name'] != "" )

		{

				

				    $configUpload['upload_path'] = $bigFolder;

					$configUpload['allowed_types'] = 'jpg|png|jpeg|gif';

					$configUpload['file_name'] = $image['name'];

					//$config['overwrite'] = TRUE;

					$configUpload['max_size'] = '0';

					$configUpload['max_width']  = '0';

					$configUpload['max_height']  = '0';

					$configUpload['encrypt_name']   = true;

					 

					$this->load->library('upload', $configUpload);

					 

					/*if(!is_dir($configUpload['upload_path'])){

						mkdir($configUpload['upload_path'], 0755, TRUE);

					}*/

					

						$this->load->library('image_lib');

					 

						if (!$this->upload->do_upload('file')){ //Upload file

								//redirect("errorhandler"); //If error, redirect to an error page

								echo 'error1';

								

							}else{

								$upload_data = $this->upload->data();

								

								

								if( $upload_data['is_image'] == 1 )

								{

										

										

										 //Resize Image

										$config = array();

										$config['image_library'] = 'gd2';

										$config['source_image'] = $upload_data["full_path"]; ;

										$config['new_image'] = $thumbFolder;

										$config['create_thumb'] = FALSE;

										$config['maintain_ratio'] = TRUE;

										$config['master_dim']= 'height';

										$config['quality']  = '100';

										$config['width'] = '200';

										$config['height']= '200';				

										

										$this->image_lib->initialize($config);

										$this->image_lib->resize();

										

										 if(!$this->image_lib->resize()){ //Resize image

											echo 'error2'; //If error, redirect to an error page

										}else{

										// Crop Image

										$config = array();

										$config['image_library'] = 'gd2';

										/*$config['image_library'] = 'ImageMagick';

										$config['library_path'] = '/usr/local/bin';*/

										$config['source_image'] = $thumbFolder;

										$config['new_image'] = $thumbFolder;

										$config['create_thumb'] = FALSE;

										$config['maintain_ratio'] = FALSE;

										$config['quality']  = '100';

										$config['x_axis'] = '0';

										$config['y_axis'] = '0';

										$config['width'] = '200';

										$config['height']= '200';

										$this->image_lib->initialize($config);

										$this->image_lib->crop();

										

										 if(!$this->image_lib->crop()){

										 	  echo $this->image_lib->display_errors();

										 }

										

										}

								}

				        

						

					}

					$imgname = $upload_data['raw_name'].$upload_data['file_ext'];

					$data['image']= $imgname;

					$updateprofileimage = $this->Muser->updateprofileimage($data,$user_id);

					$uploadData =  array(

						'image_path'   => 'http://grid.digiopia.in/uploads/profile/thumb/'.$imgname

					);

		}

        

		//echo "<pre>";

		//print_r($uploadData);

        return $uploadData;

		

    }

		

	/************************************************/

	

	//  PDF UPLOAD

			

    /************************************************/

	

	public function pdf_upload_post()

	{

				 $getauthresponse = $this->auth();

				 if($getauthresponse == 200){

				 

				 $data   = array();

        		 $pdf  = $_FILES['file'];

				 

				 

				 if(!empty($pdf)){

				

					//$paramValue = $userData['options'];

				   $getuserid = $this->Muser->getuseridByToken($userData['user_id']);
				   $user_id =$getuserid['id'];

					if(isset($user_id)){	

							

						//$email=$userData['email'];

						//$getuserdetails=$this->Muser->getuserdetails($email);



						$user_id =$userData['user_id'];

						$datapdf = $this->_uploadpdf( $pdf,$user_id );

						return $this->Response->outputResponse(true, false, array("response" => $datapdf ), '', '', 'PDF updated successfully !!'); 	

					}

				 }else{

						

						$uploadData =  array(

							'pdf_path'   => 'No image found'

						);

						return $this->Response->outputResponse(false, false, array("response" => $uploadData ), '', '', 'PDF not found !!'); 	

									 

				 	}

				 }

			}

		public function _uploadpdf( $pdf,$user_id )

		{

		//echo 'inside _uploadImage--->'.$image['name'] ;

        $data   = array();

		

			

        $pdfFolder      = './uploads/pdf/';

        

 		 if( isset( $pdf['name'] ) && $pdf['name'] != "" )

		{

            //print_r($pdf['name']); die;



          	$configUpload['upload_path']    = $pdfFolder;

            $configUpload['allowed_types']  = 'pdf';

            $configUpload['max_size']       = '0';

            $configUpload['max_width']      = '0';

            $configUpload['max_height']     = '0';

            $configUpload['encrypt_name']   = true;

            $this->load->library('upload', $configUpload);

            

            //echo $this->upload->do_upload('file');

            if( !$this->upload->do_upload('file') )

			{

				//echo 'aaaa';

				return $this->Response->outputResponse(false, false, array("response" => '' ), '', '', 'Only PDF can be uploaded !!'); 	

                //$data   = $this->wrap_error('Error uploading the image, Try again!');

            }

			else

			{

			//echo 'bbbb';

			$unlinkimage = $this->Muser->unlinkimage($user_id);

			//$thumbFile = './uploads/profile/thumb/'.$unlinkimage['image'];

			$pdfFile = './uploads/pdf/'.$unlinkimage['file'];

				if(is_file($pdfFile)) {

						unlink($pdfFile);  // delete file

						

				}

				$uploadedDetails    = $this->upload->data();

				//echo '<pre>';

				//print_r($uploadedDetails);

				//echo $uploadedDetails['is_image'];

				if( $uploadedDetails['file_name'] !='' )

				{

					//$configThumb['source_image']        = $uploadedDetails['full_path'];

					$configFullPath['full_path']   = $uploadedDetails['full_path'];

					$raw_name                           = $uploadedDetails['raw_name'];

					$file_ext                                 = $uploadedDetails['file_ext'];

					$pdfname                              = $raw_name.$file_ext;

					

					//$this->image_lib->initialize($configFullPath);

					//$this->image_lib->resize();

				}

				$data['file']= $pdfname;

				$updatepdf = $this->Muser->updatepdf($data,$user_id);

				$uploadData =  array(

					'pdf_path'   => 'http://grid.digiopia.in/uploads/pdf/'.$pdfname

				);

				

                

                }

			}

        

		//echo "<pre>";

		//print_r($uploadData);

        return $uploadData;

    }

	

	/************************************************/

	

	//  SAVE USER PROFILE DATA

			

    /************************************************/

	

	public function profile_save_post()

			{

				$getauthresponse = $this->auth();

				if($getauthresponse == 200){

				

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

					 

				if(isset($userData['user_id'])){

					 
							$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					 		$user_id =$getuserid['id'];

					 }

					 

				

				if(isset($userData['description'])){

					 

					 		$data['description']=$userData['description'];

					 

					 }

				

				if(isset($userData['gender'])){

					 //$newgender= strtolower($userData['gender']);

					 		/*if($newgender=='M'){

								

								$gender = 'M';

							}*/

							/*if($newgender=='F'){

								

								$gender = 'F';

							}*/

					 		$data['gender']=$userData['gender'];

					 

					 }

				

				if(isset($userData['dob'])){

						

						$data['dob']=date('Y-m-d',strtotime($userData['dob']));

				}

			   if(isset($userData['occupation'])){

						

						$data['occupation']=$userData['occupation'];

				}

			    if(isset($userData['phone'])){

						

						$data['phone']=$userData['phone'];

				}

				 if(isset($userData['fb_link'])){

						

						$data['fb_link']=$userData['fb_link'];

				}

				 if(isset($userData['tw_link'])){

						

						$data['tw_link']=$userData['tw_link'];

				}

				 if(isset($userData['linkin_link'])){

						

						$data['linkin_link']=$userData['linkin_link'];

				}

				if(isset($userData['insta_link'])){

						

						$data['insta_link']=$userData['insta_link'];

				}

				

				if(!empty($userData['interest'])){

									//echo '<pre>';

							//print_r($userData['interest']);

							foreach($userData['interest'] as $key=>$val){

							

									$delinterestuserrelation[$key] =$this->Muser->delinterestuserrelation($val['id'],$user_id);

									 
							}    

				

				}

			$result = $this->functions->update('user',$data,$user_id,'id');
			
			$message_id=1;
		    $getnotificationmessage = $this->Muser->getNotificationMessage($message_id);
			
			$getfrienddeviceid = $this->Muser->getFriendDeviceId($user_id);
			
			$android_push_reg_id = $getdeviceid;
			
			$pushmsg=[];
			
			foreach($getfrienddeviceid as $key=>$val){
			
			    $device_id = $this->Muser->getDeviceId($val['id']);
				
				$checkupdate = $this->Muser->checkUpdate($user_id,$val['id'],$message_id);
				
				
				if($checkupdate==0){
				
				$pushmsg['message_id']=$message_id;
				$pushmsg['sender_id']=$user_id;
				$pushmsg['receiver_id']=$val['id'];
				$pushmsg['created']=date('Y-m-d H:i:s');
				
			    $this->Muser->create('push_notification_message_user_relation',$pushmsg);
				
				}
				if($checkupdate!=0){
				
				$pushmsg['created']=date('Y-m-d H:i:s');
				$this->functions->update('push_notification_message_user_relation',$pushmsg,$user_id,'sender_id');
			   
			   }
				
				$checkuserlogin = $this->Muser->checkUserLogin($user_id);
				//echo $checkuserlogin;
				 if($checkuserlogin['login_status']=='1'){
					 //echo $getnotificationmessage;
				 	 $android_push_reg_id = $device_id['device_id'];
					 $message= str_replace("#name", $checkuserlogin['fullname'], $getnotificationmessage['message']);
					 $getdeviceid = $this->Muser->getDeviceId($val['id']);
					 if($getdeviceid['platform']=='android'){
								$this->gcmengine->getGcmPushNotification($message,$getdeviceid['device_id']);
						}
					if($getdeviceid['platform']=='ios'){
								$this->apnengine->send_ios_notification($getdeviceid['device_id'],$message);
						}
					 //$this->gcmengine->getGcmPushNotification($message,$getdeviceid['device_id']);
					}
			
			}
			
			//if($result)

			return $this->Response->outputResponse(true, false, array("response" =>  ''), '', '', 'Profile updated successfully!!');

			

				}

		}

		

	

	/************************************************/

	

	//  FRIEND REQUEST/ACCEPT 

			

    /************************************************/

	

	

	public function friend_request_accept_post()

			{

					

				 $getauthresponse = $this->auth();

				 if($getauthresponse == 200){

				 

				 $json = file_get_contents('php://input');

				 $userData = json_decode($json, true);

					 

				if(isset($userData['sender_id'])){

					 		$getuserid = $this->Muser->getuseridByToken($userData['sender_id']);
					 		$sender_id =$getuserid['id'];

					 		//$sender_id =$userData['sender_id'];

					 }

					 

				

				if(isset($userData['receiver_id'])){

					 
							$getuserid = $this->Muser->getuseridByToken($userData['receiver_id']);
					 		$receiver_id =$getuserid['id'];
							
					 		//$receiver_id=$userData['receiver_id'];

					 

					 }

					 

				if(isset($userData['status'])){

					 

					 		$data['status']=$userData['status'];

							

							

					 

					 }

					 

			if(isset($userData['unique_id'])){

					 

					 		$unique_id=$userData['unique_id'];

					}

		   if(isset($userData['relation_status'])){

					 

					 		$relation_status=$userData['relation_status'];

							

							

					 

					 }

				if(isset($unique_id)){	 

				$countid=$this->Muser->checkrequestaccept($unique_id);

				}else{

				$countid=0;	

				}

			

			   if($countid==0){

			   			

						     $data['sender_id'] = $sender_id;

							 $data['receiver_id'] = $receiver_id;

							 $data['relationshipstatus']= 'request';

							 $data['frndreqsentdatetime'] = date('Y-m-d H:i:s');

							 $this->Muser->create('friendsrelation',$data);

							 $lastid = $this->db->insert_id();

							 $userdetails=array('unique_id'=>$lastid ,'status'=>$data['status']);

					  		 $this->Response->outputResponse(true, false, array("response" =>  $userdetails), '', '', 'Friend request sent successfully !!');

						

			   }else{

			  

							 $data['unique_id'] = $unique_id;

							 $data['relationshipstatus']= $relation_status;

							 if($relation_status=='accept'){

							 $data['acceptdatetime'] = date('Y-m-d H:i:s');

							 }

							 if($relation_status=='decline'){

							  $data['declinedatetime'] = date('Y-m-d H:i:s');

							  }

							 $this->Muser->updatefrienrequest($data['unique_id'],$data['relationshipstatus']);



					  		 $this->Response->outputResponse(true, false, array("response" =>  'success'), '', '', 'Friend request accepted successfully !!');

			   		}

			   

			 }

		}

	

	/************************************************/

	

	//  FRIEND REQUEST LIST

			

    /************************************************/

	

	public function friend_request_list_post()

			{

				

				$getauthresponse = $this->auth();

				if($getauthresponse == 200){

				

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

				//$userData['user_id'];

				if(isset($userData['user_id'])){

					 

					 		//$receiver_id =$userData['user_id'];
							
							$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					 		$receiver_id =$getuserid['id'];


					 }

					 

				$friendrequestlist=$this->Muser->friendRequestList($receiver_id);

				if(!empty($friendrequestlist)){

					$this->Response->outputResponse(true, false, array("response" =>  $friendrequestlist), '', '', 'Friend request listing !!');

				}else{

					$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}

				

				}

			}

			

	/************************************************/

	

	//  FRIEND PENDING LIST

			

    /************************************************/

	

	

	public function friend_pending_list_post()

			{

				$getauthresponse = $this->auth();

				if($getauthresponse == 200){

				

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

				//$userData['user_id'];

				if(isset($userData['user_id'])){

					 

					 		//$sender_id =$userData['user_id'];
							$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					 		$sender_id =$getuserid['id'];

					 }

					 

				$friendpendinglist=$this->Muser->friendPendingList($sender_id);

				if(!empty($friendpendinglist)){

					$this->Response->outputResponse(true, false, array("response" =>  $friendpendinglist), '', '', 'Friend pending listing !!');

				}else{

					$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}

				

				}

			}

	

	

	/************************************************/

	

	//  FRIEND PENDING LIST

			

    /************************************************/

	

	public function friend_accept_list_post()

		{

				$getauthresponse = $this->auth();

				//echo '<pre>';

				//print_r($getauthresponse);

				if($getauthresponse == 200){

				

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

				//$userData['user_id'];

				if(isset($userData['user_id'])){

					 
							$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					 		$user_id =$getuserid['id'];

					 }

					 

				$friendacceptlist=$this->Muser->friendAcceptList($user_id);

				if(!empty($friendacceptlist)){

					$this->Response->outputResponse(true, false, array("response" =>  $friendacceptlist), '', '', 'Friend accept listing !!');

				}else{

					$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

				}

				

			}

		}

	

	/************************************************/

	

	//  USER POSITION LIST

			

    /************************************************/

	

	public function user_position_post()

		{

				 

				 $json = file_get_contents('php://input');

				 $userData = json_decode($json, true);

				 

				 $getauthresponse = $this->auth();

				 if($getauthresponse == 200){

				 

				 

				 

				 if(isset($userData['user_id'])){

					 

					 		$user_id =$userData['user_id'];
							$getuserid = $this->Muser->getuseridByToken($user_id,'');

					 }

					 

			   if(isset($userData['post_lat'])){

					 

					 		$post_lat=$userData['post_lat'];

					 

					 }

					 

				if(isset($userData['post_long'])){

					 

					 		$post_long=$userData['post_long'];

							

							
					 }

					 

				$chkuserpositionbyuserid=$this->Muser->chkUserPositionByUserid($getuserid['id']);

				

					if($chkuserpositionbyuserid==0){

					
					
					$data['user_id']=$getuserid['id'];
					
					//$data['user_id']=$user_id;

					$data['post_lat']=$post_lat;

					$data['post_long']=$post_long;

					$data['created_date']=date('Y-m-d');

					$unique_user_id = $this->Muser->create('user_position',$data);

					$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'Successfully Position has been set!!');

					

					}else{

					

					$data['post_lat']=$post_lat;

					$data['post_long']=$post_long;

					$data['created_date']=date('Y-m-d');

					$result = $this->functions->update('user_position',$data,$getuserid['id'],'user_id');

					$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'Successfully Position has been updated!!');

					

					}

				

				}

				

			

		}

		

		

   /************************************************/

	

	//  FRIEND POSITION LIST

			

    /************************************************/

	

		public function friend_position_post()

		{

				 

				 $getauthresponse = $this->auth();

				 if($getauthresponse == 200){

				 

				 $json = file_get_contents('php://input');

				 $userData = json_decode($json, true);

				 

				  if(isset($userData['user_id'])){

					 

					 		//$user_id =$userData['user_id'];
							$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					 		$user_id =$getuserid['id'];

					 }

					 

				   if(isset($userData['post_lat'])){

					 

					 		$post_lat =$userData['post_lat'];

					 }

					 

			      if(isset($userData['post_long'])){

					 

					 		$post_long =$userData['post_long'];

					 }

					 

				 if(isset($userData['radius'])){

					 

					 		$radius =$userData['radius'];

					 }

					 

					 

				$chkfriendpositionbyuserid=$this->Muser->chkFriendPositionByUserid($user_id,$radius,$post_lat,$post_long);

				if(!empty($chkfriendpositionbyuserid)){

					$this->Response->outputResponse(true, false, array("response" =>  $chkfriendpositionbyuserid), '', '', 'Friend nearby listing !!');

				}else{

					$this->Response->outputResponse(true, false, array("response" =>  '' ), '', '', 'No record found!!');

					}

				

				}

				 

		}

		

	

	

		

	/************************************************/

	

	//  INVITE USER LIST

			

    /************************************************/

	

	public function friend_invite_post()

	{

			

			$getauthresponse = $this->auth();

			if($getauthresponse == 200){

			

			$this->load->library('plivo');

			

			 $json = file_get_contents('php://input');

			 $userData = json_decode($json, true);

				 

				  if(isset($userData['user_id'])){

					 

					 		 $user_id =$userData['user_id'];

					 }

				  if(isset($userData['import_user_id'])){

					 

					 		 $import_user_id =$userData['import_user_id'];

					 }

					 

			 $getsenderphonenumberbyuserId = $this->Muser->getSenderPhoneNumberByUserId($user_id);

			 $getreceiverphonenumberbyuserId = $this->Muser->getReceiverPhoneNumberByUserId($import_user_id);

			 

			 

			 $getsenderemailuserId = $this->Muser->getSenderEmailByUserId($user_id);

			 $getreceiveremailbyuserId = $this->Muser->getReceiverEmailByUserId($import_user_id);

			 

			 $this->send_invite_email($getreceiveremailbyuserId['email'],$import_user_id,$getsenderemailuserId['fullname']);

			 

			 

			 //$senderphone=$getsenderphonenumberbyuserId['phone'];

			

			 //$receiverphone='+91'.$getreceiverphonenumberbyuserId['phone'];

			 //die();

			 //echo strlen($getsenderphonenumberbyuserId['phone']);

			 if(strlen($getsenderphonenumberbyuserId['phone'])==10){

			 		

					$senderphone='+91'.$getsenderphonenumberbyuserId['phone'];

					

			 }else{

			 

			 		$senderphone=$getsenderphonenumberbyuserId['phone'];

			 }

			 

			  if(strlen($getreceiverphonenumberbyuserId['phone'])==10){

			 		

					$receiverphone='+91'.$getreceiverphonenumberbyuserId['phone'];

					

			 }else{

			 

			 		$receiverphone=$getreceiverphonenumberbyuserId['phone'];

			 }

			 

			 //die();



       		 $sms_data = array(

            'src' => $senderphone, //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567

            'dst' => $receiverphone, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.

            'text' => 'A notification has been sent from '.$getsenderphonenumberbyuserId['fullname'].' regarding invitation to join GridApp', // The text to send

            'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'

            'url' => base_url() . 'index.php/plivo_test/receive_sms', // The URL which will be called with the status of the message.

            'method' => 'POST', // The method used to call the URL. Defaults to. POST

        );



        /*

         * look up available number groups

         */

         $response_array = $this->plivo->send_sms($sms_data);

		 

		 //echo '<pre>';

		 //print_r($response_array);die();



       if ($response_array[0] == '202')

        {

            $data["response"] = json_decode($response_array[1], TRUE);

			$this->Response->outputResponse(true, false, array("response" =>  ''), '', '', 'Email and SMS has been sent successfully !!');



            //print_r($data["response"]);

        }

        else

        {

            /*

             * the response wasn't good, show the error

            */

            $this->api_error($response_array);

        }

		

	   }

	}

	

	public function send_invite_email($email,$import_user_id,$fullname)

	{

	

			//$email = $this->Account->get_user_by_id($user_id)->email;

					$date=date('d-m-Y',strtotime(date('Y-m-d')));

					$email_from= 'gridapp@gmail.com';

					$subject = "Grid App: Invitation";

					$title = "Invitation to GridApp";

					$message = "<p>A notification has been sent from ".$fullname." regarding invitation to join GridApp</p>

											<p>Thanks,</p><p><b>Team Grid App </b></p><p>This is an auto generated email.We request you not to reply.</p><b>Date : </b>{$date}";

					//$message = "To log into your account for Bomb Obama, please use the following access information<br /><p><b>Username:</b> {$user_name}</p><p><b>Password:</b> {$new_password}";

					$body = $this->emailoneseven->email_invite_template($import_user_id, $title, $message);

					$response = $this->emailoneseven->send_email($email, $subject, $body,$email_from);

					return true;

	}

	

	

	

	/************************************************/

	

	//  GET PHONE CODE FROM LAT LONG  

			

    /************************************************/

	

	public function get_phone_code_post()

	{

				 

				 $check_auth_client = $this->check_auth_client();

				  if($check_auth_client == true){

				 

				 $json = file_get_contents('php://input');

				 $userData = json_decode($json, true);

				 

				  if(isset($userData['post_lat'])){

					 

					 		$latitude =$userData['post_lat'];

					 }

					 

				   if(isset($userData['post_long'])){

					 

					 		$longitude =$userData['post_long'];

					 }

					 

			    $country_name=$this->location->get_location($latitude,$longitude);

				

				$getphonecodebycountryname= $this->Muser->getPhoneCodeByCountryName($country_name);

				

				/*echo '<pre>';

				print_r($country_name);

				die();*/

				if(!empty($getphonecodebycountryname)){

				

				$response = array('phonecode'=>'+'.$getphonecodebycountryname['phonecode']);

				

				$this->Response->outputResponse(true, false, array("response" =>  $response), '', '', 'Phone code using  latitude and longitude !!');

				

				}else{

				

				   $errors = 'No phone code found !!';

				   return $this->Response->outputResponse(false, $error->msg_content, null, '', '', $errors);

				}

		    }else{

					

							$this->Response->outputResponse(402, false, array("response" =>  ''), '', '', 'Unauthorized.');

					}

				

	}

	

	/************************************************/

	

	//  SUBSCRIPTION DETAILS BRAINTREE

			

    /************************************************/

	

	public function get_subscription_post()

	{

				/*Braintree_Configuration::environment('sandbox');

				Braintree_Configuration::merchantId('c7c7y2kqc75gg8rk');

				Braintree_Configuration::publicKey('fy3d2hmdgkmqsr29');

				Braintree_Configuration::privateKey('6470d312b38adaf98fe3b78c5e6d7afb');*/

				

				$check_auth_client = $this->check_auth_client();

				if($check_auth_client == true){

				

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

				 

				  if(isset($userData['payment_type'])){

					 

					 		$payment_type =$userData['payment_type'];

					 }

				

				$getsubscription= $this->Muser->getSubscription($payment_type);

				//$clientToken = Braintree_ClientToken::generate();

				

				if(!empty($getsubscription)){

				

				$response = array('paymentVal'=>$getsubscription['price']);

				

				$this->Response->outputResponse(true, false, array("response" =>  $response), '', '', 'Subscription details !!');

				

				}else{

				

				   $errors = 'No data found !!';

				   return $this->Response->outputResponse(false, $error->msg_content, null, '', '', $errors);

				}

				

			}

				

				

	}

	/************************************************/

	

	//  FIREBASE PUSH NOTIFICATION

			

    /************************************************/
	
	public function get_firebase_post()

	{
				 $firebase = new firebaselib('https://gridapp-efa47.firebaseio.com/', 'ajYGV7SxilxMjuN0pHbsoWSLYLujJgqi3qeXJ9ug');
				
				$data =array('ip' => "123456789",'session' => "1234",'sequence' => "12",'time' => "159159159",'event' => "Pause",'data' => "1");
						
						/*echo '<pre>';
						print_r($firebase);*/
						
						//$dateTime = new DateTime();
						//echo $firebase->set( '/' . $dateTime->format('c'), $test);
						
					//$res = $firebase->push('/', $data);
					$firebase->set('/', $data);
					$value = $firebase->get('/');
					$firebase->delete('/'); 
					/*$data =array('ip' => "123456789",'session' => "1234",'sequence' => "12",'time' => "159159159",'event' => "Pause",'data' => "1",'gender'=>"male");
					$firebase->update('/', $data); 
					$firebase->push('/', $data); */ 
						
	}
	

	/************************************************/

	

	//  PAYMENT THROUGH PAYEEZE

			

    /************************************************/

	

	

	public function get_payeeze_post()

	{

	

	    $check_auth_client = $this->check_auth_client();

		

		$getauthresponse = $this->auth();

	    if($getauthresponse == 200 || $check_auth_client == true){

		

		$json = file_get_contents('php://input');

		$userData = json_decode($json, true);

		

		// for sandbox testing

		$serviceURL = 'https://api-cert.payeezy.com/v1/transactions'; 

		

		// for live testing

		//$serviceURL = 'https://api.payeezy.com/v1/transactions';

		

		

		$apiKey = "H2zmUltiQSxsjMOr6g1YfSthBF6LUwDs";

		$apiSecret = "6c80d2124b6bda2b9f6e5562481896cdbb55ab3046223af85ec94dedf2acd293";

		$token = "fdoa-d6fd58fde0dc70b31052572513cb1f8ad6fd58fde0dc70b3";

		

		

		

		$nonce = strval(hexdec(bin2hex(openssl_random_pseudo_bytes(4, $cstrong))));

		$timestamp = strval(time()*1000); //time stamp in milli seconds

		

		$card_holder_name = $card_number = $card_type = $card_cvv = $card_expiry = $currency_code = $merchant_ref="";

		

		

					 

		//$payment_type ='featured';

		

		if(isset($userData['payment_type'])){

					 

					 		$payment_type =$userData['payment_type'];

					 }

					

		

		$getsubscription = $this->Muser->getSubscription($payment_type);



        $card_holder_name = $this->processInput($userData['cardHolderName']);

        $card_number = $this->processInput($userData['cardNumber']);

        $card_type = $this->processInput($userData['cardType']);

        $card_cvv = $this->processInput($userData['cvvCode']);

        $card_expiry = $this->processInput($userData['expDate']);

        $amount = $getsubscription['price'];

        $currency_code = $this->processInput("USD");

        $merchant_ref = $this->processInput("Acme Sock");



        $primaryTxPayload = array(

            "amount"=> $amount,

            "card_number" => $card_number,

            "card_type" => $card_type,

            "card_holder_name" => $card_holder_name,

            "card_cvv" => $card_cvv,

            "card_expiry" => $card_expiry,

            "merchant_ref" => $merchant_ref,

            "currency_code" => $currency_code,

        );

		

	

    

    $data = array(

              'merchant_ref'=> $primaryTxPayload['merchant_ref'],

              'transaction_type'=> "purchase",

              'method'=> 'credit_card',

              'amount'=> $primaryTxPayload['amount'],

              'currency_code'=> strtoupper($primaryTxPayload['currency_code']),

              'credit_card'=> array(

                      'type'=> $primaryTxPayload['card_type'],

                      'cardholder_name'=> $primaryTxPayload['card_holder_name'],

                      'card_number'=> $primaryTxPayload['card_number'],

                      'exp_date'=> $primaryTxPayload['card_expiry'],

                      'cvv'=> $primaryTxPayload['card_cvv'],

					  'eci_indicator'=>'2'

                    )

    );

	

	

			$payload = json_encode($data, JSON_FORCE_OBJECT);

			

			//echo "<br><br> Request JSON Payload :" ;



			//echo $payload ;

			

			//echo "<br><br> Authorization :" ;

			

			$data = $apiKey . $nonce . $timestamp . $token . $payload;

			

			$hashAlgorithm = "sha256";

			

			### Make sure the HMAC hash is in hex -->

			$hmac = hash_hmac ( $hashAlgorithm , $data , $apiSecret, false );

			

			### Authorization : base64 of hmac hash -->

			$hmac_enc = base64_encode($hmac);

			

			//echo "<br><br> " ;

			

			//echo $hmac_enc;

			

			//echo "<br><br>" ;

			

			$curl = curl_init('https://api-cert.payeezy.com/v1/transactions');

			

			$headers = array(

				  'Content-Type: application/json',

				  'apikey:'.strval($apiKey),

				  'token:'.strval($token),

				  'Authorization:'.$hmac_enc,

				  'nonce:'.$nonce,

				  'timestamp:'.$timestamp,

				);

			

			

			

			curl_setopt($curl, CURLOPT_HEADER, false);

			curl_setopt($curl, CURLOPT_POST, true);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

			

			curl_setopt($curl, CURLOPT_VERBOSE, true);

			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			

			$json_response = curl_exec($curl);

			

			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			

			$response = json_decode($json_response, true);

			

			/*echo '<pre>';

			print_r($response);

			echo "<br><br> " ;*/

			

			/*if ( $status != 201 ) {

				die("Error: call to URL $serviceURL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));

			}*/

			//echo $status; die();

			if($status == 201){

				

			$responses = array('transaction_status'=>$response["validation_status"],'transaction_id'=>$response["transaction_id"]);

				

			$this->Response->outputResponse(true, false, array("response" =>  $responses), '', '', 'Transaction details!!');

				

			 }else{

				

				   //$errors = "Error: call to URL $serviceURL failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl);

				   //return $this->Response->outputResponse(false, $error->msg_content, null, '', '', $errors);

				   if($response["transaction_status"]!=''){

				   $responses = array('transaction_status'=>$response["transaction_status"]);

				   }else{

				   $responses = array('transaction_status'=>$response["fault"]["faultstring"]);	   

				   }

				   return $this->Response->outputResponse(false, false, array("response" =>  $responses), '', '', 'Failed Transaction details!!');

			}

			

			curl_close($curl);

			

			//echo "Response is: ".$response."\n";

			

			//echo "JSON response is: ".$json_response."\n";

   

    		//return json_encode($data, JSON_FORCE_OBJECT);



        	//return $primaryTxPayload;

		         

	   		/* $nonce = $userData["payment_method_nonce"];			 

				Braintree_Configuration::environment('sandbox');

				Braintree_Configuration::merchantId('c7c7y2kqc75gg8rk');

				Braintree_Configuration::publicKey('fy3d2hmdgkmqsr29');

				Braintree_Configuration::privateKey('6470d312b38adaf98fe3b78c5e6d7afb');*/

		

		    /*$result = Braintree_Transaction::sale(array(

					 'amount' => '100.00',

					 'creditCard' => array(

					 'number' => '6011111111111117',

					 'expirationDate' => '08/19'

					)

				  ));*/

				/*$result = Braintree_Transaction::sale([

					  'amount' => '10.00',

					  'paymentMethodNonce' => $nonce,

					  'options' => [

						'submitForSettlement' => True

					  ]

				]);*/

		

			/*echo '<pre>';

			print_r($nonce);*/

	

			/*if ($result->success) {

				print_r("success!: " . $result->transaction->id);

			  } else if ($result->transaction) {

				print_r("Error processing transaction:");

				print_r("\n  code: " . $result->transaction->processorResponseCode);

				print_r("\n  text: " . $result->transaction->processorResponseText);

			  } else {

				  print_r("Validation errors: \n");

				  print_r($result->errors->deepAll());

				}

			

			

			$clientToken = Braintree_ClientToken::generate();*/

			//echo $clientToken;

		}

	 }

	

	public function processInput($data) {

        

		$data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return strval($data);

    }

	

	/************************************************/

	

	//  UPDATE PAYMENT THROUGH PAYEEZE

			

    /************************************************/



	public function update_subscription_post()

	{

		

		$getauthresponse = $this->auth();

	    if($getauthresponse == 200){

		

		$json = file_get_contents('php://input');

		$userData = json_decode($json, true);

		

		 if(isset($userData['user_id'])){

					 
						$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
						$user_id =$getuserid['id'];
					 		//$user_id =$userData['user_id'];

					 }

					 

		if(isset($userData['transactionID'])){

					 

					 		$transaction_id =$userData['transactionID'];

					 }

		

		if(isset($userData['payment_type'])){

					 

					 		$payment_type =$userData['payment_type'];

					 }

					 

		

					 

		

								$data['role']= 'paiduser';

								$updateuserrole = $this->Muser->updateuserrole($data,$user_id);

								

								$getpaymentstatus = $this->Muser->getPaymentStatus($user_id);

								

								$getsubscription = $this->Muser->getSubscription($payment_type);

								

								$getSubscriptionStatus = $this->Muser->getSubscriptionStatus($user_id);

								

								$getRadiusBySubscription = $this->Muser->getRadiusBySubscription($getSubscriptionStatus['role']);

								if($getpaymentstatus==0){

									

								$datapayment['user_id']=$user_id;

								$datapayment['amount']=$getsubscription['price'];

								$datapayment['transaction_id']=$transaction_id;

								$datapayment['payment_type']=$payment_type;

								$datapayment['payment_created_date']=date('Y-m-d');

								

								$unique_payment_user_id = $this->Muser->create('paymentlog',$datapayment);

								

								$new_user_details = array(

                						'user_subscription' => $getSubscriptionStatus['role'],

										'min_radius'=>$getRadiusBySubscription['min'],

										'max_radius'=>$getRadiusBySubscription['max']

										);

				

								$this->Response->outputResponse(true, false, array("response" => $new_user_details), '', '', '');

				

								

								}else{

								

								$errors = 'You have already paid !!';

            				    return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

							

								}

								

		  }

		

	}

	

	

	/************************************************/

	

	//  GET TRANSACTION HISTORY

			

    /************************************************/

	

	

	public function get_transaction_history_post()

	{

				

				$getauthresponse = $this->auth();

				if($getauthresponse == 200){

				

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

				

				 if(isset($userData['user_id'])){

							 
									$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
									$user_id =$getuserid['id'];
									//$user_id =$userData['user_id'];

							 }

				if(isset($userData['start_date']) && !empty($userData['start_date'])){

							 

									$start_date = date("Y-m-d",strtotime($userData['start_date']));

							 }

				 if(isset($userData['end_date']) && !empty($userData['end_date'])){

							 

									$end_date = date("Y-m-d",strtotime($userData['end_date']));

							 }

							 

				$getsubscriptionuser = $this->Muser->getSubscriptionUser($user_id,$start_date,$end_date);

				 if(!empty($getsubscriptionuser)){

				

							//$responses = array('transaction_status'=>$response["validation_status"],'transaction_id'=>$response["transaction_id"]);

								

							   $this->Response->outputResponse(true, false, array("response" => $getsubscriptionuser), '', '', 'Transaction history!!');              

							   

							   }else{

								

								$errors = 'No record Found !!';

            				    return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

							

								}

								

					

				}

	}

	

	/****************************************************************/

	

	//  LIST EVENTS ACCORDING TO LATITUDE , LONGITUDE , DATE

			

    /***************************************************************/

	

	

	public function get_event_date_lat_long_post()

	{

	

			    $getauthresponse = $this->auth();

			    if($getauthresponse == 200){

				

				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);

				

				

				if(isset($userData['post_lat'])){

					 

					 		$post_lat =$userData['post_lat'];

					 }

					 

				if(isset($userData['post_long'])){

					 

					 		$post_long =$userData['post_long'];

					 }

					 

			   if(isset($userData['radius'])){

					 

					 		$radius =$userData['radius'];

					 }

			   if(isset($userData['event_date'])){

					 

					 		$event_date =date('Y-m-d h:i:s',strtotime($userData['event_date']));

					 }

					 

			  $geteventlatlongbydate = $this->Muser->getEventLatLongByDate($post_lat,$post_long,$radius,$event_date);

			  

			   if(!empty($geteventlatlongbydate)){

				

							   //$responses = array('transaction_status'=>$response["validation_status"],'transaction_id'=>$response["transaction_id"]);

								

							   $this->Response->outputResponse(true, false, array("response" => $geteventlatlongbydate), '', '', 'List of Events !!');              

							   

							   }else{

								

								$errors = 'No record Found !!';

            				    return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  '0'), '', '', $errors);

							

								}

								

					

				}

			

	}

	/********************************************************************************************/

	// GET ALL PERSONS  FROM CONTACT IMPORT AND FRIEND LIST USING GRIDAPP

	/*********************************************************************************************/


 public function  get_person_grid_post()
		 {
		 		
				
				
				 $getauthresponse = $this->auth();

			    if($getauthresponse == 200){
				
				
				$json = file_get_contents('php://input');

				$userData = json_decode($json, true);
				//echo '<pre>';
				//print_r($userData);
				//echo $userData['user_id'];
				 if(isset($userData['user_id'])){

							 
									$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
									$user_id =$getuserid['id'];
									//$user_id =$userData['user_id'];

					}
					
					$getpersonusinggrid = $this->Muser->getPersonUsingGrid($user_id);
					
					//echo ''

				 	if(!empty($getpersonusinggrid)){

				

							//$responses = array('transaction_status'=>$response["validation_status"],'transaction_id'=>$response["transaction_id"]);
							$this->Response->outputResponse(true, false, array("response" => $getpersonusinggrid), '', '', 'List of persons using gridapp !!');              

							   

					 }else{

								

								$errors = 'No record Found !!';

            				    return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  ''), '', '', $errors);

						}
						
				}
					
					
					
		 }
		 
	/*******************************************************************/
	
	// STUBHUB API FETCHING EVENTS
	
	/******************************************************************/
	
	
	public function get_events_stubhub_post()
	{
	
				  $getauthresponse = $this->auth();

			      if($getauthresponse == 200){
				  
				  $json = file_get_contents('php://input');
				  $userData = json_decode($json, true);
				  
				  if(isset($userData['post_lat'])){

					 		$post_lat =$userData['post_lat'];

					 }
					 
			     if(isset($userData['post_long'])){

					 		$post_long =$userData['post_long'];

					 }
					 
				 if(isset($userData['radius'])){

					 		$radius =$userData['radius'];

					 }
					 
				if(isset($userData['from_date'])){

					 		$from_date = date('Y-m-d',strtotime($userData['from_date']));

					 }
					 
			    if(isset($userData['to_date'])){

					 		$to_date = date('Y-m-d',strtotime($userData['to_date']));

					 }
				  
				  //echo "point=".$post_lat.",".$post_long."&radius=".$radius."&date=".$from_date." TO ".$to_date."&sort=distance asc";
				  //die();
				  $authorization = "Authorization:Bearer 3ba813bccaeeb9c77c81b40343f7f7c";
				  $url = 'https://api.stubhub.com/search/catalog/events/v2?';
				  $param = urlencode("point=".$post_lat.",".$post_long."&radius=".$radius."&date=".$from_date." TO ".$to_date."&sort=distance asc");
				  //$param = urlencode("point=22.5,88.3&radius=120&minAvailableTickets=1&date=2014-06-01T00:00 TO 2014-06-01T23:59&sort=distance asc");
				  
				   $ch = curl_init();
				   curl_setopt($ch, CURLOPT_URL, $url.$param);
				   curl_setopt($ch, CURLOPT_HTTPHEADER, array( $authorization ));
				   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				   $result = curl_exec($ch);
				   $httpcode = curl_getinfo($ch);
					// print_r($httpcode);
				   curl_close($ch);
				   $data = json_decode($result, TRUE);
				   $arr=[];
				   $i=0;
				   foreach($data['events'] as $key=>$val){
				   
								if($val['status']=='Active'){
									
									$arr[$i] = $val;
									$i++;
								}
						
				   }
				   
				   if(!empty($arr)){

				   			$this->Response->outputResponse(true, false, array("response" => $arr), '', '', 'List of events in Gridapp !!');          
		           
				    }else{
					
					$errors = 'No event Found !!';

            		return $this->Response->outputResponse(false, $error->msg_content, array("response" =>  ''), '', '', $errors);

					}
					
			}
	
	}
		 
	/********************************************************************************************/

	// INVITE ALL PERSONS  FROM CONTACT IMPORT AND FRIEND LIST USING GRIDAPP

	/*********************************************************************************************/
	
	
	public function  invite_person_grid_post()
		 {
		 		 $getauthresponse = $this->auth();

			    if($getauthresponse == 200){
				
				
					$json = file_get_contents('php://input');
					$userData = json_decode($json, true);
					
					//$getuserid = $this->Muser->getuseridByToken($userData['user_id']);
					//$user_id =$getuserid['id'];
					
					
					//$invite_list=$userData['invite_list'];
						if(!empty($userData['invite_list'])){
					
						$invite_list=$userData['invite_list'];
						
						}
						
						if(isset($userData['post_id'])){
							
							
							$post_id = $userData['post_id'];
						
					  }
				
					if(!empty($invite_list)){
							foreach($invite_list as $key=>$val){
							
										//echo $val;
										$getuserid = $this->Muser->getuseridByToken($val);
										$user_id =$getuserid['id'];
										$checkinvitebyuser = $this->Muser->checkInviteByUser($user_id,$post_id);
										if($checkinvitebyuser ==0){
										$data[$key]['user_id'] =$user_id;
										$data[$key]['event_id'] =$post_id;
										$data[$key]['created_date'] = date('Y-m-d H:i:s');
										$unique_user_id = $this->Muser->create('event_invite',$data[$key]);
										}else{
										
										$this->Response->outputResponse(false, false, array("response" => ''), '', '', 'Invitation already sent  !!'); 
										break;      
										}
								}
					
							$this->Response->outputResponse(true, false, array("response" => ''), '', '', 'Invitation sent successfully !!'); 
						
						}  else{
						
								$this->Response->outputResponse(false, false, array("response" => ''), '', '', 'No person found for invitation  !!'); 
						}    
						
					}      
		}
	

	/*********************************************************/

	// USER AUTHENTICATION CHECK AFTER LOGIN

	 /********************************************************/

	 public function auth()

		 {

			 

			   /*$users_id  = $this->get_header('User-ID', TRUE);

			   $token     = $this->get_header('Authorization', TRUE);*/
			   
			     //$users_id = $this->get_header('User-Id', TRUE);
			     $token  = $this->get_header('Authorizations', TRUE);
			   

				//$returnauthencticationcode = $this->Muser->returnAuthencticationCode($users_id,$token);

				$returnauthencticationcode = $this->Muser->returnAuthencticationCode($token);

				if($returnauthencticationcode==401){

				//$errors = 'Unauthorized.';

				//$this->Response->outputResponse(true, false, array('status' => 401), '', '', 'Unauthorized.');    

				//return $this->Response->outputResponse(401,false, $error->msg_content, null, '', '', $errors);

				return $returnauthencticationcode;

				}

				if($returnauthencticationcode==402){

				//$errors = 'Your session has been expired.';

				//$this->Response->outputResponse(true, false, array('status' => 402), '', '', 'Your session has been expired.');  

				//return $this->Response->outputResponse(402,false, $error->msg_content, null, '', '', $errors);

				return $returnauthencticationcode;

				

				}

				if($returnauthencticationcode==200){

				//$errors = 'Your session has been expired.';

				//$this->Response->outputResponse(true, false, array('status' => 200), '', '', 'Authorized.');  

				return $returnauthencticationcode;

				//$this->Response->outputResponse(200, false, array("response" =>  ''), '', '', 'Authorized.');

				

				}

				

			}

	/************************************************************/

		

		// USER AUTHENTICATION CHECK DURING LOGIN

		

    /************************************************************/

	var $client_service = "GRIDAPP-CLIENT";

    var $auth_key       = 'adminGRID API';

	

	public function  check_auth_image()

		 {

		 		    //$paramValue = $userData['options'];
					
					$json = file_get_contents('php://input');

					$userData = json_decode($json, true);

					$client_service = $userData['Client_Service'];

					$auth_key  = $userData['Auth_Key'];

			

					if($client_service == $this->client_service && $auth_key == md5($this->auth_key)){

			

						return true;

			

					} else {

			

					   return false;

			

					}





		 }

	 public function  check_auth_client()

		 {

		 		    $client_service = $this->get_header('Client-Service', TRUE);
					$auth_key  = $this->get_header('Auth-Key', TRUE);
					
					/*echo '<pre>';
					print_r($client_service);
					print_r($auth_key);*/
					
					
					//echo  $this->client_service;
					//echo md5($this->auth_key);

					if($client_service == $this->client_service && $auth_key == md5($this->auth_key)){

			

						return true;

			

					} else {

			

					   return false;

			

					}





		 }

		 

	function get_header( $pHeaderKey )

		{
			
			//echo $pHeaderKey;

			 $test = getallheaders();
			 
			 //echo '<pre>';
			 //print_r($test);
			 
			 
			 //die();
			if ( array_key_exists($pHeaderKey, $test) ) {

				$headerValue = $test[ $pHeaderKey ];

			}

			return $headerValue;
			
			//die('ssss');

		}

	

}





