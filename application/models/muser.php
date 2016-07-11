<?php if (!defined('BASEPATH'))exit('No direct script access allowed');



/**

 * This class is used to manage User's login, logout and registration.

 */

 

 

 

 class Muser extends CI_Model {



    protected $CI;

    private $config;

    private $Encryption;



    function __construct() {

	

        parent::__construct();

        $this->load->model("key", "Key");

        $this->load->library('encrypt');

		$this->load->library('generatepass');



    }

	

	public function returnAuthencticationCode($token){

		

			$q  = $this->db->select('expired_at')->from('user')->where('token',$token)->get()->row();

				//echo $this->db->last_query();
				//die();

				if($q == ""){

					

					return 401;

					//return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));

				} else {

					if($q->expired_at < date('Y-m-d H:i:s')){

						//return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));

						return 402;

					} else {

						$updated_at = date('Y-m-d H:i:s');

						$expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));

						$this->db->where('token',$token)->update('user',array('expired_at' => $expired_at,'updated_at' => $updated_at));

						return 200;

						//return array('status' => 200,'message' => 'Authorized.');

					}

				}

	}

	 

	  public function create($table,$data) {

	  			/*echo '<pre>';
				print_r($data);
				die();*/
				
				//die("huhh");
		

	  			$this->db->insert($table,$data); 
				//echo "message: ".$msg = $this->db->_error_message();die;
				//echo $this->db->last_query(); 

				//echo '<br/>';

				//die();

				$afftectedRows = $this->db->affected_rows();
				
				

				if($afftectedRows)

						{

							return $this->db->insert_id();

						}

						else

						{

							return FALSE;

						}
						
				

	     }

		 

	 public function import($table,$data){

		

		//$db = $this->CI->db;

		$this->db->insert_batch("userimport", $data);

		return $this->db->affected_rows();

	}

		 

	public function contactsByUser($user_id){

	

					$this->db->select('*');

   					$this->db->from('userimport');

					$this->db->where('user_id', $user_id);

					$this->db->order_by("fullname");

					$query = $this->db->get();

 					$data=$query->result_array(); 

					$out = array();

					foreach ($data as $key => $value) {

						$out[$value['phone_unique']] = $value;

					}

		return $out;

	}

		 

		 public function check_user_id($email) {

		 

		 			if($email!=''){

							$rs=$this->db->select('*')->where('email',$email)->get('user');

					//echo $this->db->last_query();

					}

					

					return $rs->num_rows ;

		 

		 }
		 
		 
		 public function getTwitterEmail($email){
		 
		 				if($email!=''){

										$rs=$this->db->select('*')->where('email',$email)->get('user');

										//echo $this->db->last_query();

							}

					

					return $rs->num_rows ;
		 
		 }
	
	public function delete($table,$id,$col){
			 
			 
			 	$this->db->where($col, $id);
			 	$this->db->delete($table);
			 
			 }
		 
		 public function getRegisteredUser(){
		 		
					
					$rs=$this->db->select('token as id,fullname,image')->where('status','1')->get('user');
					
					
					return $rs->result_array() ;
				
		 
		 }

		 
          public function check_user_activation($data){
			  
			  if($data['password']!='' && $data['email']!=''){
				
			   $this->db->select('*');

			   $this->db->from('user');

			   $this->db->join('activationcode', 'activationcode.user_id = user.id');

			   $this->db->where('activationcode.activationstatus','1');
			   
			   $this->db->where('user.email',$data['email']);
			   
			   $this->db->where('user.password',$data['password']);
			 

			   $rs = $this->db->get();
			   
			   //echo $this->db->last_query();
			   
			   return $rs->num_rows;
				  
			  }
		  }
		  public function check_user_login($data) {


		 			    if($data['password']!='' && $data['email']!=''){

							

							$rs=$this->db->select('*')->where('social','email')->where('email',$data['email'])->where('password',$data['password'])->where('is_delete','1')->get('user');	

							//$row=$rs->row_array();

							//echo $this->db->last_query();

							return $rs->num_rows;

								

					   }

						

					

					//return $rs->num_rows ;

		 

		 }

		 public function get_final_user_email_social($fb_id){

		 

		 			$this->db->select('email');

   					$this->db->from('user');

					if($fb_id!=''){

						$this->db->where('social_id',$fb_id);

					}

					/*if($username!=''){

						$this->db->where('username',$username);

					}*/

					$query = $this->db->get();

   					//echo $this->db->last_query();

 					return $query->row_array(); 

		 }

		 

		 public function get_final_user_id_social($fb_id){

		 

		 			$this->db->select('id');

   					$this->db->from('user');

					if($fb_id!=''){

						$this->db->where('social_id',$fb_id);

					}

					/*if($username!=''){

						$this->db->where('username',$username);

					}*/

					$query = $this->db->get();

   					//echo $this->db->last_query();

 					return $query->row_array(); 

		 }

		 public function get_final_count_user_id_social($fb_id){

		 

		 			$this->db->select('*');

   					$this->db->from('user');

					if($fb_id!=''){

						$this->db->where('social_id',$fb_id);

					}

					/*if($username!=''){

						$this->db->where('username',$username);

					}*/

					$query = $this->db->get();

   					//echo $this->db->last_query();

 					return $query->num_rows; 

		 }

		 public function get_final_user_id($email){

					

					$this->db->select('id,token');

   					$this->db->from('user');

					if($email!=''){

						$this->db->where('email',$email);

					}

					/*if($username!=''){

						$this->db->where('username',$username);

					}*/

					$query = $this->db->get();

   					//echo $this->db->last_query();

 					return $query->row_array(); 

		}

		public function checkDeviceUser($user_id,$uuid){
	
				     $this->db->select('*');
					 $this->db->from('device_track');
					 if($uuid!=''){

						$this->db->where('uuid',$uuid);

					 }
					 if($user_id!=''){

						$this->db->where('user_id',$user_id);

					}

					$query = $this->db->get();
					//echo $this->db->last_query();
					return $query->num_rows; 
		}	
		
     public function trackDeviceid($user_id,$uuid){
	 
	 				$this->db->select('*');
					$this->db->from('device_track');
					if($user_id!=''){

						$this->db->where('user_id',$user_id);

					}
					if($uuid!=''){

						$this->db->where('uuid',$uuid);

					}
					$query = $this->db->get();
					//echo $this->db->last_query();
					return $query->num_rows; 
	 }

	 public function getuserdetails($email){

					

					$this->db->select('id,fullname');

   					$this->db->from('user');

					if($email!=''){

						$this->db->where('email',$email);

					}

					/*if($username!=''){

						$this->db->where('username',$username);

					}*/

					$query = $this->db->get();

   					//echo $this->db->last_query();

 					return $query->row_array(); 

		}

		

		

		

	public function reset_password($user_id) {

        //$new_password = "pass1700" . $user_id . rand(0, 5);

		

		$password = $this->generatepass->generatePassword(12, 1, 2, 3);

        //$hashed_password = $this->encrypt->passwordHash($password);

		$hashed_password = md5($password);

        $query = $this->db->query("UPDATE user SET password = ? WHERE id = ?", array($hashed_password, $user_id));

        //echo $query;

		return $password;

        //return $new_password;

    }

	public function get_user_password_id($access_code,$new_password){

		

		               if($access_code!=''){		

						$rs=$this->db->select('*')->where('password',$access_code)->get('user');

						//echo $this->db->last_query();

						$row=$rs->row_array();

						

						}

						

						

						if($rs->num_rows==0){

									

									return "0";

						

						}else{

						

								$data2=array(

									'password'=>$new_password

								);

								if($new_password!=''){					

										$update=$this->db->where('id',$row['id'])->update('user',$data2);

									}

								

								

								    return	$rs->num_rows;

						

						}

		}

		

	  public function updateactivationcode($user_id,$activationcode){

	  		

			$dataactivationstatus=array('activationcode'=>$activationcode);

									

			$update=$this->db->where('user_id',$user_id)->update('activationcode',$dataactivationstatus);

	  }

		

	public function getInterestIdFromUserId($user_id,$subinterest_id){

			

			   $this->db->select('*');

			   $this->db->from('interest_user_relation');

			   $this->db->where('user_id',$user_id);

			   $this->db->where('subinterest_id',$subinterest_id);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   return $query->num_rows; 

	

	}

	public function getUserIdFromEmail($email,$loginType){

	
			   if($loginType=='twitter' || $loginType=='facebook' || $loginType=='linkedin'){
			   
			   $this->db->select('id,token');

			   $this->db->from('user');

			   $this->db->where('token',$email);

			   $this->db->where('social',$loginType);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   return $query->row_array(); 
			   
			   }
			   if($loginType=='email'){
			   
			   $this->db->select('id,token');

			   $this->db->from('user');

			   $this->db->where('token',$email);

			   $this->db->where('social',$loginType);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   return $query->row_array(); 
			   
			   }

	

	}

	

	public function getPhoneNumber($phone){

	

			   $this->db->select('*');

			   $this->db->from('user');

			   $this->db->where('phone',$phone);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   //$row=$query->row_array();

			   

			   return $query->num_rows;

			   //return $row(); 

	

	}

	

	public function getSenderPhoneNumberByUserId($user_id){

	

			   $this->db->select('phone,fullname');

			   $this->db->from('user');

			   $this->db->where('id',$user_id);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $row=$query->row_array();

			   

			   return $row;

			   //return $row(); 

	

	}

	

	public function getReceiverPhoneNumberByUserId($import_user_id){

				

			   $this->db->select('phone');

			   $this->db->from('userimport');

			   $this->db->where('id',$import_user_id);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $row=$query->row_array();

			   

			   return $row;

			   //return $row(); 

	

	}

	

	public function getSenderEmailByUserId($user_id){

	

			   $this->db->select('email,fullname');

			   $this->db->from('user');

			   $this->db->where('id',$user_id);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $row=$query->row_array();

			   

			   return $row;

			   //return $row(); 

	

	}

	

	public function getReceiverEmailByUserId($import_user_id){

				

			   $this->db->select('email,fullname');

			   $this->db->from('userimport');

			   $this->db->where('id',$import_user_id);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $row=$query->row_array();

			   

			   return $row;

			   //return $row(); 

	

	}

	

	public function getUserDetailsFromPhoneNumber($phone){

	

			   $this->db->select('*');

			   $this->db->from('user');

			   $this->db->where('phone',$phone);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   //echo '<br/>';

			   return $query->row_array();

	

	

	}

	

	public function checkPhoneNumberFromImport($phone,$user_id){

			   //$phone = substr($phone, -10);

			   $this->db->select('*');

			   $this->db->from('user');

			   $this->db->where('phone',$phone);

			   //$this->db->where('RIGHT(phone,10)',$phone);

			   $this->db->where('id',$user_id);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   return $query->num_rows();

	}
	
	public function getActivationDetailsByUser($user_id){
	
				
					$rs=$this->db->select('*')->where('id',$user_id)->get('user');

					$datauser=$rs->row_array();
					
					return $datauser;
	}

	public function activateUser($email,$activationcode,$platform,$model,$UUID){

	

				    if($email!=''){

					$rs=$this->db->select('*')->where('email',$email)->get('user');

					$datauser=$rs->row_array();

					// echo $this->db->last_query();

					if($datauser['email']!=''){

							

							$rs=$this->db->select('*')->where('user_id',$datauser['id'])->where('activationcode',$activationcode)->get('activationcode');		

							$dataactivation=$rs->row_array();

							// echo $this->db->last_query();

									if($dataactivation['activationcode']!=''){

										//echo $dataactivation['activationcode'];

									    //$dataactivationstatus = array();

										//echo $dataactivation['id'];

											$rs=$this->db->select('*')->where('now() BETWEEN "'.$dataactivation['codesentdate'].'" AND "'.$dataactivation['codeexpirydate'].'" ')->get('activationcode');	

											//echo $this->db->last_query();

											if($rs->num_rows>0){

												return '-3';

											}else{	

												$dataactivationstatus=array(

														'activationstatus'=>1,

														'platform'=>$platform,

														'model'=>$model,

														'UUID'=>$UUID

													);

									

											$update=$this->db->where('user_id',$dataactivation['user_id'])->update('activationcode',$dataactivationstatus);

										}

									    //echo $this->db->last_query();

										}else{

									

									return '-2';

									

									}

									

							}else{

							

									return '-1';

							}

					//echo $this->db->last_query();

					}

	

	}

	

	

	public function displayEvent($post_id){

			

			   $this->db->select('*');

			   $this->db->from('events');

			   $this->db->where('id',$post_id);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   return $query->result_array(); 

	}

	

	public function updateEvent($post_id,$data){

	

			$update=$this->db->where('id',$post_id)->update('events',$data);

	}

	

	public function getInterestbyId($user_id){

	

			   $this->db->select('*');

			   $this->db->from('interest_user_relation');

			   $this->db->where('user_id',$user_id);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   return $query->num_rows(); 

	}
	
	public function getSocialId($social_id){
				
				$this->db->select('*');

			   $this->db->from('user');

			   $this->db->where('social_id',$social_id);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   return $query->row_array(); 
	
	}

	

	public function getSubscriptionStatus($user_id){

	

			   $this->db->select('fullname,role,image');

			   $this->db->from('user');

			   $this->db->where('id',$user_id);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   return $query->row_array(); 

	}

	

	public function getUserProfileData($sender_id,$receiver_id,$user_id){

				

			  

			   $this->db->select('*');

			   $this->db->from('user');

			   if($sender_id!='' && $receiver_id==''){

			   $this->db->where('id',$sender_id);

			   }

			   else if($sender_id!='' && $receiver_id!=''){

			   $this->db->where('id',$receiver_id);

			   }

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   $row=$query->row_array();
			   
			   if($row['dob']=='0000-00-00'){
			   
			   
			   $row['dob'] = 'MM/DD/YYYY';
			   
			   }else{

			   $row['dob'] = date('m/d/Y',strtotime($row['dob']));
			   
			   
			   }

			   

			   if($sender_id!='' && $receiver_id==''){

			   

			   $this->db->select('receiver_id');

			   $this->db->from('friendsrelation');

			   $this->db->where('sender_id',$sender_id);

			   $this->db->where('relationshipstatus','accept');

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $friendcount1=$query->num_rows();

			   

			   $this->db->select('sender_id');

			   $this->db->from('friendsrelation');

			   $this->db->where('receiver_id',$sender_id);

			   $this->db->where('relationshipstatus','accept');

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $friendcount2=$query->num_rows();

			   

				if($friendcount1!=0  && $friendcount2==0){

					  //echo '1';

					  $row['friend_count']=$friendcount1;

				   

				   }

				else if($friendcount1==0 && $friendcount2!=0){

					  //echo '2---';

					  $row['friend_count']=$friendcount2;

				   

				   }

				   

				else if($friendcount1!=0 && $friendcount2!=0){

				   	   //echo '3';

					   $row['friend_count']=($friendcount1+$friendcount2);

					   

				   }

			    else {

				   $row['friend_count']= 0;

			    }

			   }

			   

			   

			   

			   if($sender_id!='' && $receiver_id!=''){

			   

			   $this->db->select('receiver_id');

			   $this->db->from('friendsrelation');

			   $this->db->where('sender_id',$receiver_id);

			   $this->db->where('relationshipstatus','accept');

			   $query = $this->db->get();

			   $friendcount1=$query->num_rows();

			   

			   $this->db->select('sender_id');

			   $this->db->from('friendsrelation');

			   $this->db->where('receiver_id',$receiver_id);

			   $this->db->where('relationshipstatus','accept');

			   $query = $this->db->get();

			   $friendcount2=$query->num_rows();

			   

			   //echo 'bbbb';

				   //echo '4';

				if($friendcount2!=0 && $friendcount1==0){

					   

					   $row['friend_count']=$friendcount2;

					   

				   }

				   

				   

				 else if($friendcount1!=0  && $friendcount2==0){

					  //echo '5'; 

					  $row['friend_count']=$friendcount1;

				   

				   }

				   

				 else if($friendcount1!=0 && $friendcount2!=0){

				   	   //echo '6';

					   $row['friend_count']=($friendcount1+$friendcount2);

					   

				   }

				 else {

					 

				      $row['friend_count']= 0;

			     }

			   

			   }

			   

			  

			   

			 

			   

			   if($sender_id!='' && $receiver_id!=''){

				   

			   $this->db->select('friendsrelation.relationshipstatus');

			   $this->db->from('friendsrelation');

			   $this->db->where('sender_id',$sender_id);

			   $this->db->where('receiver_id',$receiver_id);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $row2=$query->row_array();

			   if(isset($row2['relationshipstatus'])){

			   $row['relation_status']=$row2['relationshipstatus'];

			   	}else{

			   $this->db->select('friendsrelation.relationshipstatus');

			   $this->db->from('friendsrelation');

			   $this->db->where('receiver_id',$sender_id);

			   $this->db->where('sender_id',$receiver_id);

			   $query = $this->db->get();

			   //echo $this->db->last_query();

			   $row2=$query->row_array();

				   if(isset($row2['relationshipstatus'])){

				   $row['relation_status']=$row2['relationshipstatus'];

				   }else{

				   $row['relation_status']='';  

				   }

			   	}

			   }

			   

			   $this->db->select('*');

			   $this->db->from('events');

			   if($sender_id!='' && $receiver_id==''){

			   $this->db->where('user_id',$sender_id);

			   }

			   else if($sender_id!='' && $receiver_id!=''){

			   $this->db->where('user_id',$receiver_id);

			   }

			   //$this->db->where('user_id',$user_id);

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			   //echo $this->db->last_query();

			   $row['event_count']=$query->num_rows();

			   

			   $this->db->select('interest.id,interest.subinterest_name');

			   $this->db->from('interest_user_relation');

			   $this->db->join('interest', 'interest.id = interest_user_relation.subinterest_id');

			   if($sender_id!='' && $receiver_id==''){

			   $this->db->where('interest_user_relation.user_id',$row['id']);

			   }

			   else if($sender_id!='' && $receiver_id!=''){

			   $this->db->where('interest_user_relation.user_id',$row['id']);

			   }

			   //$this->db->where('interest_user_relation.user_id',$row['id']);

			   //$this->db->group_by('interest_user_relation.interest_id');

			   $query = $this->db->get();

			   //return	$query->num_rows; 

			  // echo $this->db->last_query();

			   $row['interest']=$query->result_array(); 

			   

			   //echo json_decode($row['interest']);

			   

			   if(empty($row['interest'])){

			   

			   	$row['interest']='No interest found';

			   }

			   

			   return $row; 

			   

			   

	}

	

	function getDisplayEvent($post_lat,$post_long,$radius,$event_date,$date_select_type,$user_id,$post_filter){

	

	//echo $post_filter;

	//echo $user_id;

	//echo $date_select_type;

	

		  if($date_select_type=='next'){

			

			if($post_filter=='myfriend'){

			

						 

				$SQL1 = ' SELECT sender_id as userid FROM `friendsrelation` WHERE receiver_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL1);

				$datasender=$query->result_array();

				

				if(!empty($datasender)){

				foreach($datasender as $key=>$val){

							$arrsender[$key]= $val;

					}

				}

				

				$SQL2 = ' SELECT receiver_id as userid  FROM `friendsrelation` WHERE sender_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL2);

				$datareceiver=$query->result_array();

				

				if(!empty($datareceiver)){

				foreach($datareceiver as $key=>$val){

							$arrreceiver[$key]= $val;

					}

				}

				$data1=[];

				if(!empty($arrsender) && !empty($arrreceiver)){

					$data1 = array_merge($arrsender,$arrreceiver);

				}

				if(!empty($arrsender) && empty($arrreceiver)){

					$data1 = $arrsender;

				}

				if(!empty($arrreceiver) && empty($arrsender)){

					$data1 = $arrreceiver;

				}

				if(empty($arrreceiver) && empty($arrsender)){

					$data1 = [];

				}

				//echo '<pre>';

				//print_r($data);

				$arr=array();

				if(!empty($data1)){

				foreach($data1 as $key=>$val){

					

						$arr[$key]= $val['userid'];

				}

				$str = implode(',',$arr);

				}else{

				$str = '';	

				}

				

				if(!empty($data1)){

					$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."'  AND user_id IN (".$str.")  ORDER BY event_date asc";

				}else{

					$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."'  AND user_id IN ('')  ORDER BY event_date asc";

				}

			

			

			}

			else if($post_filter=='around'){

					

					$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."' AND user_id!='".$user_id."' ORDER BY event_date asc";		

			

			}

			

			else if($post_filter=='myevent'){

			

					$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."'   AND user_id='".$user_id."' ORDER BY event_date asc";		

			

			}

			 

			 

			 //$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."' ORDER BY event_date asc";

				

			

			$query = $this->db->query($SQL);

			

			$datamain=$query->result_array();

			if(!empty($datamain)){

				foreach($datamain as $k=>$val){

					

					if($post_filter=='myfriend'){

					$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id IN (".$str.") ";	

					}

					if($post_filter=='around'){

						$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id!='".$user_id."'";	

					}

					if($post_filter=='myevent' ){

					$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id='".$user_id."'";		

					}	

					$query = $this->db->query($SQL);

					$result=$query->result_array();

					foreach($result as $m=>$val){

							if($val['formated_date']!=''){

							

								 $SQL = "SELECT id,formated_date FROM `events` WHERE id = '".$val['id']."'";

								

								$query = $this->db->query($SQL);

								

								$row=$query->row_array();

								//$row['id'];

								$id[$m]=$row['id'];

								$formated_date = $row['formated_date'];

							

								}

						

						}

					break;

			      }

			}

		}				 

		  else if($date_select_type=='prev'){

			

			 if($post_filter=='myfriend'){

			

						 

				$SQL1 = ' SELECT sender_id as userid FROM `friendsrelation` WHERE receiver_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL1);

				$datasender=$query->result_array();

				

				if(!empty($datasender)){

				foreach($datasender as $key=>$val){

							$arrsender[$key]= $val;

					}

				}

				

				$SQL2 = ' SELECT receiver_id as userid  FROM `friendsrelation` WHERE sender_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL2);

				$datareceiver=$query->result_array();

				

				if(!empty($datareceiver)){

				foreach($datareceiver as $key=>$val){

							$arrreceiver[$key]= $val;

					}

				}

				$data1=[];

				if(!empty($arrsender) && !empty($arrreceiver)){

					$data1 = array_merge($arrsender,$arrreceiver);

				}

				if(!empty($arrsender) && empty($arrreceiver)){

					$data1 = $arrsender;

				}

				if(!empty($arrreceiver) && empty($arrsender)){

					$data1 = $arrreceiver;

				}

				if(empty($arrreceiver) && empty($arrsender)){

					$data1 = [];

				}

				//echo '<pre>';

				//print_r($data);

				$arr=array();

				if(!empty($data1)){

				foreach($data1 as $key=>$val){

					

					

							$arr[$key]= $val['userid'];

					}

				$str = implode(',',$arr);

				}else{

				

				$str='';

				}

				if(!empty($data1)){

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) < '".$event_date."'  AND user_id IN (".$str.")  AND DATE(event_date) >= DATE(NOW()) ORDER BY event_date desc";

				}else{

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) < '".$event_date."'  AND user_id IN ('')  AND DATE(event_date) >= DATE(NOW()) ORDER BY event_date desc";	

				}

			

			}

			else if($post_filter=='around'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) < '".$event_date."'   AND user_id!=".$user_id." AND DATE(event_date) >= DATE(NOW()) ORDER BY event_date desc";		

			

			}

			

			else if($post_filter=='myevent'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) < '".$event_date."'   AND user_id=".$user_id." AND DATE(event_date) >= DATE(NOW()) ORDER BY event_date desc";		

			

			}

			 

			 

			 

			 //$SQL = "SELECT * FROM `events` WHERE DATE(event_date) < '".$event_date."' AND DATE(event_date) >= DATE(NOW()) ORDER BY event_date desc";

				

			

			$query = $this->db->query($SQL);

			

			$datamain=$query->result_array();

			if(!empty($datamain)){

			foreach($datamain as $k=>$val){

					

					if($post_filter=='myfriend'){

					$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id IN (".$str.") ";	

					}

					if($post_filter=='around'){

					$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id!=".$user_id;	

					}

					if($post_filter=='myevent' ){

					$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id=".$user_id;		

					}	

					$query = $this->db->query($SQL);

					$result=$query->result_array();

					foreach($result as $m=>$val){

							if($val['formated_date']!=''){

							

								 $SQL = "SELECT id,formated_date FROM `events` WHERE id = '".$val['id']."'";

								

								$query = $this->db->query($SQL);

								

								$row=$query->row_array();

								//$row['id'];

								$id[$m]=$row['id'];

								$formated_date = $row['formated_date'];

							

								}

						

						}

					break;

			      		}

					}

					

			  }

		  else if($date_select_type=='calendar'){

			

			

			//$datamain=$query->row_array();

			//$id=$datamain['id'];

			if($post_filter=='myfriend'){

			

						 

				$SQL1 = ' SELECT sender_id as userid FROM `friendsrelation` WHERE receiver_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL1);

				$datasender=$query->result_array();

				

				if(!empty($datasender)){

				foreach($datasender as $key=>$val){

							$arrsender[$key]= $val;

					}

				}

				

				$SQL2 = ' SELECT receiver_id as userid  FROM `friendsrelation` WHERE sender_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL2);

				$datareceiver=$query->result_array();

				

				if(!empty($datareceiver)){

				foreach($datareceiver as $key=>$val){

							$arrreceiver[$key]= $val;

					}

				}

				$data1=[];

				if(!empty($arrsender) && !empty($arrreceiver)){

					$data1 = array_merge($arrsender,$arrreceiver);

				}

				if(!empty($arrsender) && empty($arrreceiver)){

					$data1 = $arrsender;

				}

				if(!empty($arrreceiver) && empty($arrsender)){

					$data1 = $arrreceiver;

				}

				if(empty($arrreceiver) && empty($arrsender)){

					$data1 = [];

				}

				//echo '<pre>';

				//print_r($data);

				$arr=array();

				if(!empty($data1)){

				foreach($data1 as $key=>$val){

					

					

							$arr[$key]= $val['userid'];

					}

				$str = implode(',',$arr);

				}else{

				$str = '';	

				}

				if(!empty($data1)){

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."'  AND user_id IN (".$str.") AND user_id!=".$user_id;

				}else{

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."'  AND user_id IN ('') AND user_id!=".$user_id;	

				}

			

			}

			else if($post_filter=='around'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."'   AND user_id!=".$user_id;		

			

			}

			

			else if($post_filter=='myevent'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."'   AND user_id=".$user_id;		

				

				//$SQL = "SELECT * FROM `events` WHERE DATE(event_date) < '".$event_date."'   AND user_id=".$user_id." AND DATE(event_date) >= DATE(NOW()) ORDER BY event_date desc";		

			

			}

			

			//$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."'";

			$query = $this->db->query($SQL);

			$datamain=$query->result_array();

			if(!empty($datamain)){

			foreach($datamain as $key=>$val){

			//echo $val['event_date'];

			

								$id[$key] = $val['id'];

			

						 }

					}

					

			  }

		  else if($date_select_type==''){

			

			//echo 'bbbbb';

			if($post_filter=='myfriend'){

			

						//echo 'bbbbb'; 

				$SQL1 = ' SELECT sender_id as userid FROM `friendsrelation` WHERE receiver_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL1);

				$datasender=$query->result_array();

				

				if(!empty($datasender)){

				foreach($datasender as $key=>$val){

							$arrsender[$key]= $val;

					}

				}

				

				$SQL2 = 'SELECT receiver_id as userid  FROM `friendsrelation` WHERE sender_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL2);

				$datareceiver=$query->result_array();

				

				if(!empty($datareceiver)){

				foreach($datareceiver as $key=>$val){

							$arrreceiver[$key]= $val;

					}

				}

				$data1=[];

				

				if(!empty($arrsender) && !empty($arrreceiver)){

					$data1 = array_merge($arrsender,$arrreceiver);

				}

				if(!empty($arrsender) && empty($arrreceiver)){

					$data1 = $arrsender;

				}

				if(!empty($arrreceiver) && empty($arrsender)){

					$data1 = $arrreceiver;

				}

				if(empty($arrreceiver) && empty($arrsender)){

					$data1 = [];

				}

				//echo '<pre>';

				//print_r($data);

				$arr=array();

				if(!empty($data1)){

				foreach($data1 as $key=>$val){

					

					

							$arr[$key]= $val['userid'];

					}

				

				 $str = implode(',',$arr);

				 }

				 else{

				 $str='';	 

				 }

				if(!empty($data1)){

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."' AND user_id IN (".$str.") ";

				}else{

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."' AND user_id IN ('') ";

				}

			}

			

			else if($post_filter=='around'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."'   AND user_id!=".$user_id;		

			

			}

			

			else if($post_filter=='myevent'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) = '".$event_date."'   AND user_id=".$user_id;		

			

			}

			

			$query = $this->db->query($SQL);

			

			$datacount=$query->num_rows();

			

			if($datacount!=0){

			

			$datamain=$query->result_array();

			foreach($datamain as $k=>$val){

					$id1[$k]=$val['id'];

				}

			

				  

			$id=$id1	;

			

			}else{

			

			/*if($post_filter=='myfriend'){

			//echo 'aaaaa';

						 

				$SQL1 = ' SELECT sender_id as userid FROM `friendsrelation` WHERE receiver_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL1);

				$datasender=$query->result_array();

				

				if(!empty($datasender)){

				foreach($datasender as $key=>$val){

							$arrsender[$key]= $val;

					}

				}

				

				$SQL2 = 'SELECT receiver_id as userid  FROM `friendsrelation` WHERE sender_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL2);

				$datareceiver=$query->result_array();

				

				if(!empty($datareceiver)){

				foreach($datareceiver as $key=>$val){

							$arrreceiver[$key]= $val;

					}

				}

				

				$data1=[];

				if(!empty($arrsender) && !empty($arrreceiver)){

					$data1 = array_merge($arrsender,$arrreceiver);

				}

				if(!empty($arrsender) && empty($arrreceiver)){

					$data1 = $arrsender;

				}

				if(!empty($arrreceiver) && empty($arrsender)){

					$data1 = $arrreceiver;

				}

				if(empty($arrreceiver) && empty($arrsender)){

					$data1 = [];

				}

				//echo '<pre>';

				//print_r($data);

				$arr=array();

				if(!empty($data1)){

				foreach($data1 as $key=>$val){

					

					

							$arr[$key]= $val['userid'];

					}

				

				 $str = implode(',',$arr);

				 }

				 else{

				 $str='';	 

				 }

				if(!empty($data1)){

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."' AND user_id IN (".$str.") ORDER BY event_date asc";

				}else{

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."' AND user_id IN ('') ORDER BY event_date asc";

				}

			

			}

			

			else if($post_filter=='around'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."'   AND user_id!=".$user_id." ORDER BY event_date asc";		

			

			}

			

			else if($post_filter=='myevent'){

			

				$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."'   AND user_id=".$user_id." ORDER BY event_date asc";		

			

			}*/

			

			 //$SQL = "SELECT * FROM `events` WHERE DATE(event_date) > '".$event_date."' ORDER BY event_date asc";

				

			

			/*$query = $this->db->query($SQL);

			

			$datamain=$query->result_array();*/

			//echo '<pre>';

			//print_r($datamain);

			/*if(!empty($datamain)){

			foreach($datamain as $k=>$val){

					//echo $val['id'];

					if($post_filter=='myfriend'){

						$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id IN (".$str.") ";	

					}

					if($post_filter=='around'){

						$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id!=".$user_id;	

					}

					if($post_filter=='myevent'){

						$SQL = "SELECT formated_date,id FROM `events` WHERE formated_date='".$val['formated_date']."' AND user_id=".$user_id;		

					}

					$query = $this->db->query($SQL);

					$result=$query->result_array();

					foreach($result as $m=>$val){

							if($val['formated_date']!=''){

							

								$SQL = "SELECT id FROM `events` WHERE id = '".$val['id']."'";

								

								$query = $this->db->query($SQL);

								

								$row=$query->row_array();

								//$row['id'];

								$id[$m]=$row['id'];

							

									}

						

								}

								break;

			      			}

						}*/

			

					

			  }

	   }

			  

			  $strs=implode(',',$id);	 

			  //die();

			  

			  

			  if($strs!=''){

				

				

				$SQL = "SELECT *, ( 3959 * acos( cos( radians(".$post_lat.") ) * cos( radians( post_lat ) ) * cos( radians( post_long ) - radians(".$post_long.") ) + sin( radians(".$post_lat.") ) * sin( radians( post_lat ) ) ) ) AS distance FROM events WHERE id IN(".$strs.") HAVING distance < ".$radius." ORDER BY is_featured desc,event_date";

			

						$query = $this->db->query($SQL);

						$data=$query->result_array();

						

						if($post_filter=='myfriend' || $post_filter=='around')

						{

						foreach($data as $key=>$val){

							

						

						$SQL3 = "SELECT * FROM event_view WHERE event_id=".$val['id']." and status='accept'";

						$query = $this->db->query($SQL3);

						$countevent=$query->num_rows();

						

						if($countevent==$val['maxpersonallowed']){

							

							

							$data[$key]['event_status']   = 'unavailable';

							

							

						}

						if($countevent==0){

							

							$SQL2 = "SELECT status FROM event_view WHERE event_join_id='".$user_id."' AND event_id=".$val['id'];        

								

								$query = $this->db->query($SQL2);

								$eventtype=$query->row_array();

								

								if(is_null($eventtype['status'])){

									

								$data[$key]['event_status']   = '';

								

								}else{

								if($eventtype['status']=='cancel'){

								$data[$key]['event_status']   = '';	

								}else{

								$data[$key]['event_status']   = $eventtype['status'];

									}

								}

						}

						

						if($countevent!=0 && $countevent<$val['maxpersonallowed']){

								//echo 'cccc';

						$SQL2 = "SELECT status FROM event_view WHERE event_join_id='".$user_id."' AND event_id=".$val['id'];        

								

								$query = $this->db->query($SQL2);

								$eventtype=$query->row_array();

								

								

								if(is_null($eventtype['status'])){

									

								$data[$key]['event_status']   = '';

								

								}else{

									

								if($eventtype['status']=='cancel'){

								$data[$key]['event_status']   = ''	;

								}else{

								$data[$key]['event_status']   = $eventtype['status'];

									}

								}

								

								

							}

						
									
								  $this->db->select('is_invite');

								   $this->db->from('event_invite');
								   
								   $this->db->where('event_id',$val['id']);

								   $this->db->where('user_id',$user_id);

								   $query = $this->db->get();
								   
								   //echo $this->db->last_query();

								   $row_event=$query->row_array(); 
								   
								    if(isset($row_event['is_invite'])){

								   	$data[$key]['is_invite']   = $row_event['is_invite'];

								   }else{
								   
								   $data[$key]['is_invite']   = 0;
								   
								   }
								   
								   
								   $this->db->select('fullname,gender,token');

								   $this->db->from('user');

								   $this->db->where('id',$val['user_id']);

								   $query = $this->db->get();

								   //return	$query->num_rows; 

								   //echo $this->db->last_query();

								   $row=$query->row_array(); 

								   $data[$key]['fullname'] = $row['fullname'];

								   

								   if($row['gender']=='M'){

								   	$data[$key]['gender']   = 'male';

								   }

								    if($row['gender']=='F'){

								   	$data[$key]['gender']   = 'female';

								   }	
								   
								    if(isset($row['token'])){
									
										$data[$key]['user_token']   = $row['token'];

								
									}
								

								

							}

						}

						else if($post_filter=='myevent')

						{

							foreach($data as $key=>$val){

							

						

						$SQL3 = "SELECT * FROM event_view WHERE event_id=".$val['id']." and status='accept'";

						$query = $this->db->query($SQL3);

						$countevent=$query->num_rows();

						//echo $val['maxpersonallowed'];

						if($countevent==$val['maxpersonallowed']){

							$SQL2 = "SELECT status FROM event_view WHERE event_join_id='".$user_id."' AND event_id=".$val['id'];        

							

						    $query = $this->db->query($SQL2);

							$eventtype=$query->row_array();

							

							$data[$key]['event_status']   = 'accept';	

							

						}else{

							

							$data[$key]['event_status']   = '';			

						}

						
								   $this->db->select('is_invite');

								   $this->db->from('event_invite');
								   
								   $this->db->where('event_id',$val['id']);

								   $this->db->where('user_id',$user_id);

								   $query = $this->db->get();

								   $row_event=$query->row_array(); 
								   
								    if(isset($row_event['is_invite'])){

								   	$data[$key]['is_invite']   = $row_event['is_invite'];

								   }else{
								   
								   $data[$key]['is_invite']   = 0;
								   
								   }
						

						           $this->db->select('fullname,gender,token');

								   $this->db->from('user');

								   $this->db->where('id',$val['user_id']);

								   $query = $this->db->get();

								   //return	$query->num_rows; 

								   //echo $this->db->last_query();

								   $row=$query->row_array(); 

								   $data[$key]['fullname'] = $row['fullname'];

								   

								   if($row['gender']=='M'){

								   	$data[$key]['gender']   = 'male';

								   }

								    if($row['gender']=='F'){

								   	$data[$key]['gender']   = 'female';

								   }
								   
								    if(isset($row['token'])){
									
										$data[$key]['user_token']   = $row['token'];

								
									}

								

								

							}	

					}

					

			

							$finalarr = [];

							$i=0;

							foreach($data as $key=>$val){

									

									$finalarr[$i] = $val;

									$i++;

							 }

						//echo $formated_date;

						if($formated_date!='' && empty($finalarr)){

								  if($date_select_type=='prev'){

									  $data['event_dates']=$formated_date;

									  $data['message']='No record found within the range';

									  return $data;

									}

								  if($date_select_type=='next'){

									  $data['event_dates']=$formated_date;

									  $data['message']='No record found within the range';

									  return $data;

									}

							  }

							  else{

								return $finalarr;  

							  }

					/*if($formated_date!='' && empty($finalarr)){

								  if($date_select_type=='prev'){

									  $data['event_dates']=$formated_date;

									  $data['message']='No record found within the range';

									  return $data;

									}

							  }

							  

					else if($formated_date!='' && !empty($finalarr)){*/

									 

					/*}*/

								  

				

						//return $finalarr;

				

			  }

	}

	

public function getEventLatLongByDate($post_lat,$post_long,$radius,$event_date){

				

				

				$SQL = "SELECT *, ( 3959 * acos( cos( radians(".$post_lat.") ) * cos( radians( post_lat ) ) * cos( radians( post_long ) - radians(".$post_long.") ) + sin( radians(".$post_lat.") ) * sin( radians( post_lat ) ) ) ) AS distance FROM events WHERE event_date=".$event_date." HAVING distance < ".$radius." ORDER BY is_featured desc";

			

			    $query = $this->db->query($SQL);

			    $data=$query->result_array();

				

				return $data;



}

public function updatepdf($data,$user_id){

		

		$update=$this->db->where('id',$user_id)->update('user',$data);

		//echo $this->db->last_query();

}



public function updateprofileimage($data,$user_id){

		

		$update=$this->db->where('id',$user_id)->update('user',$data);

		//echo $this->db->last_query();

}



public function updatebackimage($data,$user_id){

		

		$update=$this->db->where('id',$user_id)->update('user',$data);

		//echo $this->db->last_query();

}



public function updateuserrole($data,$user_id){

		

		$update=$this->db->where('id',$user_id)->update('user',$data);

		//echo $this->db->last_query();

}	



public function unlinkimage($user_id){

		

		

		   $SQL = "SELECT * FROM `user` WHERE id ='".$user_id."' ";

		   $query = $this->db->query($SQL);

		   $dataimage=$query->row_array();

		   return $dataimage;



}



public function getPaymentStatus($user_id){

	

		   $SQL = "SELECT * FROM `paymentlog` WHERE  user_id ='".$user_id."' ";

		   $query = $this->db->query($SQL);

		   $status=$query->num_rows();

		   return $status;

}



public function unlinkbackgroundimage($user_id){



		   $SQL = "SELECT * FROM `userbackground` WHERE user_id ='".$user_id."' ";

		   $query = $this->db->query($SQL);

		   $dataimage=$query->result_array();

		   return $dataimage;



}



public function getImageCountByUserid($user_id){



		   $SQL = "SELECT * FROM `userbackground` WHERE user_id ='".$user_id."' ";

		   $query = $this->db->query($SQL);

		   $countimage=$query->num_rows();

		   return $countimage;

}



public function deletebackimage($user_id){



		   $SQL = "DELETE  FROM `userbackground` WHERE user_id ='".$user_id."' ";

		   $query = $this->db->query($SQL);

		   //$countimage=$query->num_rows();

		   //return $countimage;

}



public function delinterestuserrelation($subinterest_id,$user_id){



		    $SQL = "DELETE FROM  `interest_user_relation` WHERE subinterest_id ='".$subinterest_id."' AND user_id =".$user_id;

		   //echo '<br/>';

		    $query = $this->db->query($SQL);

		   //$subinterestid=$query->row_array();

		   //return $subinterestid;

}



public function getEventUser($user_id,$type){

			 

			 if($type=='update')

			 {

			 $SQL = "SELECT events.created_date,events.formated_date,events.user_id as event_creator_id,events.id as post_id,events.formated_time,events.description,events.post_location,user.id as user_id,user.fullname,user.image FROM events,user WHERE events.user_id=user.id AND events.user_id!='".$user_id."' AND DATE(events.event_date) >= DATE(NOW()) ORDER BY created_date DESC";

		     $query = $this->db->query($SQL);

			 $eventuser=$query->result_array();

			 

			 //echo date_default_timezone_get();

			// die();

			 

			 foreach($eventuser as $key=>$val){
				 
				 	 $SQL1 = "SELECT token FROM user WHERE id='".$val['event_creator_id']."'";
					 $query = $this->db->query($SQL1);
					 $creatortoken=$query->row_array();
					 
					 $eventuser[$key]['event_creator_id'] = $creatortoken['token'];
					 
					 
					 $SQL2 = "SELECT token FROM user WHERE id='".$val['user_id']."'";
					 $query = $this->db->query($SQL2);
					 $usertoken=$query->row_array();
					 
					 $eventuser[$key]['user_id'] = $usertoken['token'];
					 //echo strtotime($val['formated_time']);

					 //$ret_date = 0;

					 $current_time = time();

					// strtotime($val['event_date']);

					 $remain_time[$key] = $current_time-strtotime($val['created_date']);

					//echo $remain_time[$key];

					

					 $eventuser[$key]['time_diff'] = $this->secondsToTime($remain_time[$key]);

					//echo '<br/>';

					 /*if($remain_time[$key] < 24*60*60){

				

						$eventuser[$key]['time_diff'] = date('h', $remain_time[$key] );

				

					}else{

				

						$eventuser[$key]['time_diff'] = date('M d', $remain_time[$key] );

				

					}*/

				}

				

			 }

			 if($type=='going')

			 {

			   $SQL ="SELECT event_creator_id,event_id FROM event_view WHERE event_join_id='".$user_id."' AND status='accept'";

			   $query = $this->db->query($SQL);

			   $data=$query->result_array();

			 

			   foreach($data as $key=>$val){

				   		
						

						$SQL1 = "SELECT events.created_date,events.id as post_id,events.formated_date,events.formated_time,events.description,events.user_id as event_creator_id,events.post_location,user.id as user_id,user.fullname,user.image FROM events,user WHERE events.user_id=user.id AND events.user_id = ".$val['event_creator_id']." AND events.id =".$val['event_id']." AND DATE(events.event_date) >= DATE(NOW())";

						

						$query = $this->db->query($SQL1);

			     	    $arr[$key]=$query->row_array();

						

						$current_time = time();

						if(!empty($arr[$key])){

					    $remain_time[$key] = $current_time-strtotime($arr[$key]['created_date']);

					    $arr[$key]['time_diff'] = $this->secondsToTime($remain_time[$key]);
						
						 $SQL1 = "SELECT token FROM user WHERE id='".$arr[$key]['event_creator_id']."'";
						 $query = $this->db->query($SQL1);
						 $creatortoken=$query->row_array();
						 
						 $arr[$key]['event_creator_id'] = $creatortoken['token'];
						 
						 
						 $SQL2 = "SELECT token FROM user WHERE id='".$arr[$key]['user_id']."'";
						 $query = $this->db->query($SQL2);
						 $usertoken=$query->row_array();
					 
					     $arr[$key]['user_id'] = $usertoken['token'];


						}else{

						unset($arr[$key]);	

						}

				   }

			   

			   

			   $eventuser = $arr;

			 }

			 

			if($type=='pending')

			 {

				 $SQL ="SELECT event_creator_id,event_id FROM event_view WHERE event_join_id='".$user_id."' AND status='request'";

			   $query = $this->db->query($SQL);

			   $data=$query->result_array();

			 

			   foreach($data as $key=>$val){

				   		
					 
						

						$SQL1 = "SELECT events.created_date,events.id as post_id,events.formated_date,events.formated_time,events.description,events.user_id as event_creator_id,events.post_location,user.id as user_id,user.fullname,user.image FROM events,user WHERE events.user_id=user.id AND events.user_id = ".$val['event_creator_id']." AND events.id =".$val['event_id']." AND DATE(events.event_date) >= DATE(NOW())";

						

						$query = $this->db->query($SQL1);

			     	    $arr[$key]=$query->row_array();

						
			
						$current_time = time();

						if(!empty($arr[$key])){

					    $remain_time[$key] = $current_time-strtotime($arr[$key]['created_date']);

					    $arr[$key]['time_diff'] = $this->secondsToTime($remain_time[$key]);
						
						 $SQL1 = "SELECT token FROM user WHERE id='".$arr[$key]['event_creator_id']."'";
						 $query = $this->db->query($SQL1);
						 $creatortoken=$query->row_array();
						 
						 $arr[$key]['event_creator_id'] = $creatortoken['token'];
						 
						 
						 $SQL2 = "SELECT token FROM user WHERE id='".$arr[$key]['user_id']."'";
						 $query = $this->db->query($SQL2);
						 $usertoken=$query->row_array();
					 
					     $arr[$key]['user_id'] = $usertoken['token'];


						}else{

						unset($arr[$key]);	

						}

				   }

			   

			   

			   $eventuser = $arr;

				

				 

				 

			 	 

				 

			 }

			if($type=='request')

			 {

				$SQL ="SELECT event_join_id,event_id FROM event_view WHERE event_creator_id='".$user_id."' AND status='request'";

				

			   $query = $this->db->query($SQL);

			   $data=$query->result_array();

			 

			   foreach($data as $key=>$val){

				   		

						

						$SQL1 = "SELECT events.created_date,events.user_id as event_creator_id,events.id as post_id,events.formated_date,events.formated_time,events.description,events.post_location FROM events WHERE events.id =".$val['event_id']." AND DATE(events.event_date) >= DATE(NOW())";

						

						$query = $this->db->query($SQL1);

			     	    $arr[$key]=$query->row_array();

						
						 $SQL1 = "SELECT token FROM user WHERE id='".$arr[$key]['event_creator_id']."'";
						 $query = $this->db->query($SQL1);
						 $creatortoken=$query->row_array();
						 
						 $arr[$key]['event_creator_id'] = $creatortoken['token'];
						 


						$SQL2 = "SELECT id as user_id,image,fullname,token FROM user WHERE user.id =".$val['event_join_id']." ";

						

						$query = $this->db->query($SQL2);

						$result=$query->row_array();

						

						$arr[$key]['user_id'] = $result['token'];

						$arr[$key]['image'] = $result['image'];

						$arr[$key]['fullname'] = $result['fullname'];

						

						

						

						

						$current_time = time();

						if(!empty($arr[$key])){

					    $remain_time[$key] = $current_time-strtotime($arr[$key]['created_date']);

					    $arr[$key]['time_diff'] = $this->secondsToTime($remain_time[$key]);

						}else{

						unset($arr[$key]);	

						}

				   }

			   

			   

			   $eventuser = $arr;

			 }

			 /**/

			 return $eventuser;



}	



function getRequestGoingCount($user_id){

	

			 $SQL ="SELECT event_join_id,event_id FROM event_view WHERE event_creator_id='".$user_id."' AND status='request'";

			 $query = $this->db->query($SQL);

			 $eventusercount=$query->num_rows();

			 $count = $eventusercount;

			 

			 return $count;

}





function secondsToTime($seconds) {

    $dtF = new DateTime("@0");

    $dtT = new DateTime("@$seconds");

	if($seconds< 24*60*60){

				

						//$eventuser[$key]['time_diff'] = date('h', $remain_time[$key] );

						return $dtF->diff($dtT)->format('%h hour');

				

					}else{

				

						return $dtF->diff($dtT)->format('%a days');

						//$eventuser[$key]['time_diff'] = date('M d', $remain_time[$key] );

				

					}

   // return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');

}



function checkrequestaccept($unique_id){

					

			 $SQL = "SELECT * FROM friendsrelation WHERE id=".$unique_id;

		     $query = $this->db->query($SQL);

			 $countid=$query->num_rows();

			 

			 return $countid;



}



public function updatefrienrequest($unique_id,$status){



			//echo $status;

			//echo $unique_id;

			$data['relationshipstatus']= $status;

			$data['acceptdatetime'] = date('Y-m-d H:i:s');

			$update=$this->db->where('id',$unique_id)->update('friendsrelation',$data);



}



public function friendRequestList($receiver_id){



			 $SQL = "SELECT user.id,user.fullname,friendsrelation.relationshipstatus,friendsrelation.frndreqsentdatetime,friendsrelation.id as uniuqe_id FROM friendsrelation,user WHERE user.id=friendsrelation.sender_id AND  friendsrelation.receiver_id=".$receiver_id." AND friendsrelation.relationshipstatus='request'  ";

		     $query = $this->db->query($SQL);

			 $result=$query->result_array();

			 

			 foreach($result as $key=>$val){

			 	

				$SQL = "SELECT image,token FROM user WHERE id=".$val['id'];

				$query = $this->db->query($SQL);

			 	$row=$query->row_array();

				$data[$key]['unique_id']=$val['uniuqe_id'];

				$data[$key]['user_id']=$row['token'];

				$data[$key]['fullname']=$val['fullname'];

			 	$data[$key]['created_date']= date('jS F, Y',strtotime($val['frndreqsentdatetime']));

				$data[$key]['image']=$row['image'];

			 }

			 //$result= $result2;

			 

			 return $data;

			 

}



public function friendPendingList($sender_id){



			 $SQL = "SELECT user.id,user.fullname,friendsrelation.relationshipstatus,friendsrelation.frndreqsentdatetime,friendsrelation.id as uniuqe_id FROM friendsrelation,user WHERE user.id=friendsrelation.receiver_id AND  friendsrelation.sender_id=".$sender_id." AND friendsrelation.relationshipstatus='request'  ";

		     $query = $this->db->query($SQL);

			 $result=$query->result_array();

			 

			 foreach($result as $key=>$val){

			 	

				$SQL = "SELECT image,token FROM user WHERE id=".$val['id'];

				$query = $this->db->query($SQL);

			 	$row=$query->row_array();

				$data[$key]['unique_id']=$val['uniuqe_id'];

				$data[$key]['user_id']=$row['token'];

				$data[$key]['fullname']=$val['fullname'];

			 	$data[$key]['created_date']= date('jS F, Y',strtotime($val['frndreqsentdatetime']));

				$data[$key]['image']=$row['image'];

			 }

			 //$result= $result2;

			 

			 return $data;

			 

}



public function friendAcceptList($user_id){



		 $SQL = "SELECT friendsrelation.relationshipstatus,friendsrelation.frndreqsentdatetime,friendsrelation.id as uniuqe_id,friendsrelation.sender_id,friendsrelation.receiver_id,friendsrelation.acceptdatetime FROM friendsrelation WHERE   (friendsrelation.sender_id=".$user_id." OR friendsrelation.receiver_id='".$user_id."') AND friendsrelation.relationshipstatus='accept'  ";

		  $query = $this->db->query($SQL);

		  $result=$query->result_array();

		  

		   foreach($result as $key=>$val){

			 	

				

				if($user_id!=$val['receiver_id']){

				$SQL = "SELECT fullname,image,id,token FROM user WHERE id=".$val['receiver_id'];

				

				$query = $this->db->query($SQL);

			 	$row=$query->row_array();

				$data[$key]['unique_id']=$val['uniuqe_id'];

				$data[$key]['user_id']=$row['token'];

				$data[$key]['fullname']=$row['fullname'];

			 	$data[$key]['created_date']= date('jS F, Y',strtotime($val['acceptdatetime']));

				$data[$key]['image']=$row['image'];

				}

				if($user_id!=$val['sender_id']){

				$SQL = "SELECT fullname,image,id,token FROM user WHERE id=".$val['sender_id'];

				

				$query = $this->db->query($SQL);

			 	$row=$query->row_array();

				$data[$key]['unique_id']=$val['uniuqe_id'];

				$data[$key]['user_id']=$row['token'];

				$data[$key]['fullname']=$row['fullname'];

			 	$data[$key]['created_date']= date('jS F, Y',strtotime($val['acceptdatetime']));

				$data[$key]['image']=$row['image'];

				}

			 }

      return $data;



}

public function getuseridByToken($token,$email){
	
				 if($email!='' && $token==''){
					 
					$SQL1 = "SELECT * FROM user WHERE email='".$email."'";
					
					$query1 = $this->db->query($SQL1);

			 	 	$row_token=$query1->row_array();
					
					$SQL2 = "SELECT * FROM user WHERE token='".$row_token['token']."'";
					
					$query2 = $this->db->query($SQL2);

			 	 	$row=$query2->row_array();
					 
				 }
				 if($email=='' && $token!=''){
				 	
					$SQL = "SELECT * FROM user WHERE token='".$token."'";
					
					$query = $this->db->query($SQL);

			 	 	$row=$query->row_array();
				 }

				 

				 

		         return $row;
	
}

public function chkUserPositionByUserid($user_id){



				 $SQL = "SELECT * FROM user_position WHERE user_id='".$user_id."'";

				 $query = $this->db->query($SQL);

			 	 $row=$query->num_rows();

				 

		         return $row;

				 

}



public function chkFriendPositionByUserid($user_id,$radius,$post_lat,$post_long){

				

				if($user_id!=''){

				$SQL = "SELECT * FROM friendsrelation WHERE (sender_id='".$user_id."' OR receiver_id='".$user_id."') AND relationshipstatus='accept'";

				$query = $this->db->query($SQL);

				$result=$query->result_array();

				//echo '<pre>';

				//print_r($result);

				if(!empty($result)){

				/*echo '<pre>';

				print_r($result);*/

				$id = [];

				foreach($result as $key=>$val){

				//echo $val['receiver_id'];

				$friend_id = $val['sender_id'];

				if($user_id==$val['sender_id'])

					$friend_id = $val['receiver_id'];

				

							$SQL1 = "SELECT user_id FROM user_position WHERE user_id=".$friend_id;

							$query = $this->db->query($SQL1);

							$row=$query->row_array();

							if(!empty($row['user_id'])){

								$id[] = $row['user_id'];

							}

							

							

							//$query = $this->db->query($SQL2);

							//$data=$query->result_array();

							

					}

				$str=implode(',',$id);	 	

				//die();

				 if($str!=''){

			 				

							$SQL = "SELECT *, ( 3959 * acos( cos( radians(".$post_lat.") ) * cos( radians( post_lat ) ) * cos( radians( post_long ) - radians(".$post_long.") ) + sin( radians(".$post_lat.") ) * sin( radians( post_lat ) ) ) ) AS distance FROM user_position WHERE user_id IN(".$str.") HAVING distance < ".$radius." ORDER BY created_date";

			 

			 $query = $this->db->query($SQL);

			 $data=$query->result_array();

			 

			 foreach($data as $keyname=>$val){

			 

			 				//echo $val['user_id'];

							

							$SQL2 = "SELECT fullname,image,token FROM user WHERE id='".$val['user_id']."'";

							$query = $this->db->query($SQL2);

							$row = $query->row_array();

							//echo $row['fullname'];
							
							$data[$keyname]['user_id']=$row['token'];

							$data[$keyname]['fullname']=$row['fullname'];

							$data[$keyname]['image']=$row['image'];

							

			 			    

			 }

			 //$data['fullname']=$data2;

			 return $data;

					

						}

						

					}

							

				}

				

			}

			

			

public function getAllUserImportByUser($user_id){



			

			

			$SQL = "SELECT * FROM userimport WHERE user_id='".$user_id."'";

			$query = $this->db->query($SQL);

			$result=$query->result_array();

			

			return $result;

			



}


public function checkCountPhoneImportByUser($user_id,$phone){

			$phone = substr($phone, -10);
			
			$SQL = "SELECT * FROM user WHERE id='".$user_id."' AND RIGHT(phone,10)='".$phone."'";

			$query = $this->db->query($SQL);

			$count=$query->num_rows();

			//echo $this->db->last_query();

			//echo '<br/>';

			return $count;


}


public function getCountUserImportByUser($user_id,$phone){




			//$phone = substr($phone, -10);

			//$SQL = "SELECT * FROM userimport WHERE user_id=".$user_id." AND RIGHT(phone,10)='".$phone."'";

			$SQL = "SELECT * FROM userimport WHERE user_id='".$user_id."' AND phone_unique = '".$phone."' ";

			$query = $this->db->query($SQL);

			$count=$query->num_rows();

			//echo $this->db->last_query();

			//echo '<br/>';

			return $count;



}



public function getPhoneCodeByCountryName($country_name){



			$SQL = "SELECT phonecode FROM country WHERE nicename='".$country_name."'";

			$query = $this->db->query($SQL);

			$code=$query->row_array();

			

			return $code;

}



public function eventUpdateViewDetails($row_id,$status){

			

			$SQL1 = "SELECT event_id FROM event_view WHERE  id=".$row_id."";

			$query = $this->db->query($SQL1);

			$row=$query->row_array();

			

			$SQL2 = "SELECT * FROM event_view WHERE  event_id=".$row['event_id']." and status = 'accept' ";

			$query = $this->db->query($SQL2);

			$rowevent=$query->num_rows();

			

			$SQL3 = "SELECT maxpersonallowed FROM events WHERE id=".$row['event_id']."";

			$query = $this->db->query($SQL3);

			$rowmaxperspn=$query->row_array();

			

			

			

			

			$data = [];

			

			

			

			

			if($rowmaxperspn['maxpersonallowed']==$rowevent){

					

					if($status=='cancel'){

					$SQL = "UPDATE event_view SET status='".$status."' WHERE  id=".$row_id."";

					$query = $this->db->query($SQL);

					

					$SQL4 = "SELECT status FROM event_view WHERE  id=".$row_id."";

					$query = $this->db->query($SQL4);

					$row=$query->row_array();

					$data['is_approve'] = $row['status'];	

					}else{

					$data['is_approve'] = 'notaccept';

					}

			}

			else{

					$SQL = "UPDATE event_view SET status='".$status."' WHERE  id=".$row_id."";

					$query = $this->db->query($SQL);

					

					$SQL4 = "SELECT status,event_id FROM event_view WHERE  id=".$row_id."";

					$query = $this->db->query($SQL4);

					$row=$query->row_array();

					//echo $rowmaxperspn['maxpersonallowed'];

			//echo $rowevent;

			

			        $SQL6 = "SELECT * FROM event_view WHERE  event_id=".$row['event_id']." and status = 'accept' ";

					$query = $this->db->query($SQL6);

					$rowevent=$query->num_rows();

					//echo $rowmaxperspn['maxpersonallowed'];

					//echo $rowevent;

					$result= ($rowmaxperspn['maxpersonallowed']-$rowevent);

					$data['availableperson'] = $result;

					$data['is_approve'] = $row['status'];

			}

			

			return $data;



}

public function eventOwnerViewDetails($user_id,$event_id){

			

			

		$SQL1 = "SELECT * FROM event_view WHERE  event_id=".$event_id." and event_join_id='".$user_id."'";

		$query = $this->db->query($SQL1);

		$countevent=$query->num_rows();

		

		

		$SQL2 = "SELECT * FROM events WHERE id=".$event_id." ";

		$query = $this->db->query($SQL2);

		$event=$query->row_array();

		

		/*if($countevent==0){

		$SQL = "INSERT INTO event_view SET event_creator_id=".$user_id.",event_join_id='',event_id=".$event_id.",status='', created_date='".date('Y-m-d H:i:s')."' ";

		$query = $this->db->query($SQL);

		}*/

		

		$SQL3 = "SELECT user.id,user.fullname,user.image,user.image,event_view.id as row_id FROM event_view,user WHERE  event_view.event_join_id=user.id AND  event_view.event_id=".$event_id."  and event_view.status='request' ";

		$query = $this->db->query($SQL3);

		$eventrequest=$query->result_array();

		

		$SQL4 = "SELECT user.id,user.fullname,user.image,user.image,event_view.id as row_id FROM event_view,user WHERE  event_view.event_join_id=user.id AND  event_view.event_id=".$event_id."  and event_view.status='accept' ";

		$query = $this->db->query($SQL4);

		$eventaccept=$query->result_array();

		

		

		

		

		$SQL6 = "SELECT status FROM event_view WHERE event_id=".$event_id." AND event_creator_id='".$user_id."' AND status='accept' ";

		$query = $this->db->query($SQL6);

		$availableperson=$query->num_rows();

		

		$result= ($event['maxpersonallowed']-$availableperson);

		

		$arrevent['type'] = 'owner';

		$arrevent['maxpersonallowed'] = $event['maxpersonallowed'];

		$arrevent['availableperson'] = ($result!=0)?$result:0;

		$arrevent['formated_date'] = $event['formated_date'];

		$arrevent['formated_time'] = $event['formated_time'];

		$arrevent['location'] = $event['post_location'];

		$arrevent['event_type'] = $event['event_type'];

		if(!empty($eventaccept)){

		$arrevent['is_going'] =$eventaccept;

		foreach($eventaccept as $key=>$val){

			

				$SQL5 = "SELECT relationshipstatus,frndreqsentdatetime FROM friendsrelation WHERE sender_id=".$val['id']." OR receiver_id=".$val['id'];

				$query = $this->db->query($SQL5);

				$result=$query->row_array();

				$arrevent['is_going'][$key]['relationshipstatus'] = $result['relationshipstatus'];

				$arrevent['is_going'][$key]['frndreqsentdatetime'] = date('jS F, Y',strtotime($result['frndreqsentdatetime']));

			}

		}else{

		$arrevent['is_going'] = '';

		}

		

		if(!empty($eventrequest)){

			

		$arrevent['is_join'] =$eventrequest;

		//$SQL5 = "SELECT friendsrelation FROM ";

		

		}else{

		$arrevent['is_join'] = '';

		}

		$arrevent['event_title'] = $event['post_title'];

		$arrevent['summary'] = $event['description'];

		return $arrevent;

		

}



public function getSubscription($payment_type){



	    if($payment_type=='featured'){

		

		$this->db->select('price');

   		$this->db->from('subscription');

		$this->db->where('payment_type','featured');

		$query = $this->db->get();

		$subscription=$query->row_array();

			

		} 

		if($payment_type=='subscription'){

		

		$this->db->select('price');

   		$this->db->from('subscription');

		$this->db->where('payment_type','subscription');

		$query = $this->db->get();

		$subscription=$query->row_array();

		

		}

		

		return $subscription;

}



public function getSubscriptionUser($user_id,$start_date,$end_date){

		

		

		

		if($start_date!='' && $end_date!='' && $user_id!=''){

		

		//echo 'test1';

		

		$SQL = "SELECT payment_created_date,payment_type,amount FROM paymentlog WHERE  user_id='".$user_id."' and payment_created_date BETWEEN '".$start_date."'  AND '".$end_date."'  ";

		$query = $this->db->query($SQL);

		$data=$query->result_array();

		

		

		}else if($start_date=='' && $end_date=='' && $user_id!=''){

		

		//echo 'test2';

		

		$SQL = "SELECT payment_created_date,payment_type,amount FROM paymentlog WHERE  user_id='".$user_id."'";

		$query = $this->db->query($SQL);

		$data=$query->result_array();

		

		

		}

		

		return $data;

		

}



public function getRadiusBySubscription($role){



		if($role=='freeuser'){

				

				$this->db->select('min,max');

				$this->db->from('radius');

				$this->db->where('user_type','free');

				$query = $this->db->get();

				$radius=$query->row_array();

				

		}

		if($role=='paiduser'){

				

				$this->db->select('min,max');

				$this->db->from('radius');

				$this->db->where('user_type','paid');

				$query = $this->db->get();

				$radius=$query->row_array();

				

		}

		return $radius;

}

public function eventOtherViewDetails($user_id,$event_id){



		$SQL4 = "SELECT * FROM event_view WHERE  event_id=".$event_id." and event_join_id='".$user_id."'";

		$query = $this->db->query($SQL4);

		$countevent=$query->num_rows();

		

		$SQL1 = "SELECT * FROM events WHERE id=".$event_id." ";

		$query = $this->db->query($SQL1);

		$event=$query->row_array();

		

		if($countevent==0){

		 $SQL = "INSERT INTO event_view SET event_creator_id=".$event['user_id'].",event_join_id=".$user_id.",status='',event_id=".$event_id.",created_date='".date('Y-m-d H:i:s')."' ";

		 $query = $this->db->query($SQL);

		 }

	

		$SQL2 = "SELECT * FROM user WHERE id='".$event['user_id']."' ";

		$query = $this->db->query($SQL2);

		$user=$query->row_array();

		

		

		$SQL3 = "SELECT relationshipstatus FROM friendsrelation WHERE  (sender_id=".$user_id." AND receiver_id=".$event['user_id'].") OR   (sender_id=".$event['user_id']." AND receiver_id='".$user_id."')";

		$query = $this->db->query($SQL3);

		$friend=$query->row_array();

		 

		$SQL5 = "SELECT status,id FROM event_view WHERE  event_id=".$event_id." and event_join_id='".$user_id."'";

		$query = $this->db->query($SQL5);

		$eventstatus=$query->row_array();

		

		$SQL5 = "SELECT user.id,user.fullname,user.image FROM `user`,`event_view` WHERE user.id=event_view.event_join_id AND event_view.event_creator_id=".$event['user_id']." AND event_id='".$event_id."' AND event_view.status='accept' ";

		$query = $this->db->query($SQL5);

		$eventgoing=$query->result_array();

		
		
		//$getuserid = $this->getuseridByToken($event['user_id']);
		//$user_id =$getuserid['id'];
		
		$SQL6 = "SELECT * FROM user WHERE id='".$event['user_id']."'";
					
		$query = $this->db->query($SQL6);

		$event_user=$query->row_array();
		
		$arrevent['type'] = 'other';

		$arrevent['user_id'] = $event_user['token'];

		$arrevent['formated_date'] = $event['formated_date'];

		$arrevent['formated_time'] = $event['formated_time'];

		$arrevent['location'] = $event['post_location'];

		$arrevent['event_type'] = $event['event_type'];

		$arrevent['row_id'] = $eventstatus['id'];

		if($eventstatus['status']==''){

		$arrevent['is_approve'] = '';

		}else{

		$arrevent['is_approve'] = $eventstatus['status'];

		}

		if(!empty($eventgoing)){

		$arrevent['is_going'] = $eventgoing;

		foreach($eventgoing as $key=>$val){

			

				$SQL5 = "SELECT relationshipstatus,frndreqsentdatetime FROM friendsrelation WHERE sender_id=".$val['id']." OR receiver_id=".$val['id'];

				$query = $this->db->query($SQL5);

				$result=$query->row_array();

				$arrevent['is_going'][$key]['relationshipstatus'] = $result['relationshipstatus'];

				$arrevent['is_going'][$key]['frndreqsentdatetime'] = date('jS F, Y',strtotime($result['frndreqsentdatetime']));

			}

		}else{

		$arrevent['is_going'] = '';

		}

		$arrevent['event_title'] = $event['post_title'];

		$arrevent['summary'] = $event['description'];

		$arrevent['created_by'] = $user['fullname'];

		$arrevent['user_image'] = $user['image'];

		

		if($friend['relationshipstatus']==''){

		

		$arrevent['is_friend'] = 'add';

		}else{

		

		$arrevent['is_friend'] =$friend['relationshipstatus'];

		}

		

		return $arrevent;

	}
	
	
public function getPersonUsingGrid($user_id){

				$CI =& get_instance();
				$CI->load->model('common/functions','Functions',true);
				
				
				$SQL1 = ' SELECT sender_id as userid FROM `friendsrelation` WHERE receiver_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL1);

				$datasender=$query->result_array();

				

				if(!empty($datasender)){

				foreach($datasender as $key=>$val){

							$arrsender[$key]= $val;

					}

				}

				

				$SQL2 = ' SELECT receiver_id as userid  FROM `friendsrelation` WHERE sender_id="'.$user_id.'" and relationshipstatus="accept" ';

				$query = $this->db->query($SQL2);

				$datareceiver=$query->result_array();

				

				if(!empty($datareceiver)){

				foreach($datareceiver as $key=>$val){

							$arrreceiver[$key]= $val;

					}

				}

				$data1=[];

				if(!empty($arrsender) && !empty($arrreceiver)){

					$data1 = array_merge($arrsender,$arrreceiver);

				}

				if(!empty($arrsender) && empty($arrreceiver)){

					$data1 = $arrsender;

				}

				if(!empty($arrreceiver) && empty($arrsender)){

					$data1 = $arrreceiver;

				}

				if(empty($arrreceiver) && empty($arrsender)){

					$data1 = [];

				}
				
				$arr=array();

				if(!empty($data1)){

					foreach($data1 as $key=>$val){
	
						
							$SQL3 = ' SELECT phone FROM user where id=" '.$val['userid'].' " ';
							$query = $this->db->query($SQL3);
							$dataphoneimport=$query->row_array();
							
							//$arr[$key]= $val['userid'];
							$arr[$key]= $this->Functions->appsbee_encode($dataphoneimport['phone']);
	
					}
	
					$str = implode(',',$arr);
					
					//echo '<pre>';
					//print_r($arr);
				
				}
				
				
				$SQL4 = ' SELECT id,fullname,phone FROM userimport  where user_id=" '.$user_id.' " AND phone NOT IN ("'.$str.'") ';

				$query = $this->db->query($SQL4);

				$dataimport=$query->result_array();
				//print_r($data1);
				
				//$this->load->model('common/functions','',TRUE);
				
				$arrphone = [];
				foreach($dataimport as $keyphone=>$val){
					
					//echo $val['id'].'---->'. $val['fullname'].'---->'.$this->Functions->appsbee_decode($val['phone'],"salt.crypt");
					//echo '<br/>';
					
					$phone[$keyphone] = $this->Functions->appsbee_decode($val['phone'],"salt.crypt");
					
					$SQL5 = ' SELECT id  FROM user where user.phone = "'.$phone[$keyphone].'" '  ;

					$query = $this->db->query($SQL5);
	
					$phoneimport=$query->row_array();
					
					if(!empty($phoneimport)){
					$arrphone[$keyphone] = $phoneimport['id'];
					
					}
				
				}
				
				
				$newarr=[];
				
				if(!empty($data1) && !empty($arrphone)){
				
					$newarr = array_merge($data1,$arrphone);
				
				}
				
				if(!empty($data1) && empty($arrphone)){

					$newarr = $data1;

				}

				if(!empty($arrphone) && empty($data1)){

					$newarr = $arrphone;

				}

				if(empty($data1) && empty($arrphone)){

					$newarr = [];

				}
				
				$arrimport=array();

				if(!empty($newarr)){

						foreach($newarr as $keynew=>$val){
		
							
		
								$arrimport[$keynew]= $val['userid'];
		
						}
	
					//$str = implode(',',$arr);
				
				}
		$finalarr=[];
			if(!empty($arrimport)){
						
							foreach($arrimport as $keyval=>$val){
							
										$SQL6 = ' SELECT token,fullname,image  FROM user where user.id = "'.$val.'" '  ;

										$query = $this->db->query($SQL6);
						
										$row=$query->row_array();
										
										if(!empty($row)){
											
											$finalarr[$keyval]['token'] = $row['token'];
											$finalarr[$keyval]['fullname'] = $row['fullname'];
											$finalarr[$keyval]['image'] = $row['image'];
											$finalarr[$keyval]['is_checked'] = 'false';
										
										}
								}
					
					}
				//echo '<pre>';
				//print_r($finalarr);
				if(!empty($finalarr)){
				return $finalarr;
				}


}

function getReceiverMessages($user_id){

									    $SQL = ' SELECT message_id,sender_id  FROM push_notification_message_user_relation WHERE receiver_id='.$user_id ;

										$query = $this->db->query($SQL);
						
										$data=$query->result_array();
										
										return $data;
										
										
}
function checkInviteByUser($user_id,$post_id){

										$SQL = ' SELECT id  FROM event_invite WHERE event_invite.user_id = "'.$user_id.'"  AND event_invite.event_id='.$post_id  ;

										$query = $this->db->query($SQL);
						
										$count=$query->num_rows();
										
										return $count;

			}

function checkUpdate($sender_id,$receiver_id,$message_id){

										
										$SQL = ' SELECT id  FROM push_notification_message_user_relation WHERE sender_id='.$sender_id.' AND receiver_id='.$receiver_id.' AND message_id='.$message_id ;

										$query = $this->db->query($SQL);
						
										$count=$query->num_rows();
										
										return $count;

	
    }
function checkAllReceiverPush($user_id){

										$SQL = ' SELECT device_track.login_status,device_track.uuid FROM device_track WHERE user_id='.$user_id ;

										$query = $this->db->query($SQL);
						
										$data=$query->result_array();
										
										return $data;
}
function getSenderNamePush($user_id){
				
									    $SQL = ' SELECT fullname FROM user WHERE id='.$user_id ;

										$query = $this->db->query($SQL);
						
										$row=$query->row_array();
										
										return $row;

}
function checkReceiverPush($uuid){
										
										$SQL = ' SELECT device_track.login_status,user.fullname  FROM user, device_track WHERE user.id=device_track.user_id AND device_track.uuid="'.$uuid.'"' ;

										$query = $this->db->query($SQL);
						
										$row=$query->row_array();
										
										return $row;

		}
function checkDeviceId($user_id,$uuid){

										$SQL = 'SELECT id  FROM device_track WHERE user_id= "'.$user_id.'" AND uuid ="'.$uuid.'"'  ;

										$query = $this->db->query($SQL);
						
										$count=$query->num_rows();
										
										return $count;

		}
		
function getDeviceId($user_id,$uuid){

										$SQL = 'SELECT device_id,platform  FROM device_track WHERE user_id= "'.$user_id.'" AND uuid="'.$uuid.'"'  ;

										$query = $this->db->query($SQL);
						
										$row=$query->row_array();
										
										return $row;
}
		  
function getFriendDeviceId($user_id){
								
								
								$SQLSENDER = ' SELECT sender_id as id FROM `friendsrelation` WHERE receiver_id='.$user_id.'  and relationshipstatus= "accept"  '  ;

							    $querysender = $this->db->query($SQLSENDER);
								
								$resultsender =$querysender->result_array();
								
								
								$arrsender = [];
								
								foreach($resultsender as $key=>$val){
								
										$arrsender[$key] = $val;	
								
								}
								
								
								$SQLRECEIVER = ' SELECT receiver_id as id FROM `friendsrelation` WHERE sender_id='.$user_id.'  and relationshipstatus= "accept"   '  ;

							    $queryreceiver = $this->db->query($SQLRECEIVER);
								
								$resultreceiver =$queryreceiver->result_array();
								
								
								$arrreceiver = [];
								
								foreach($resultreceiver as $key=>$val){
								
										$arrreceiver[$key] = $val;	
								
								}
								
								$arrfriends = [];
								
								$arrfriends = array_merge($arrsender,$arrreceiver);
								
								//$strfriends ='';
								
								//$strfriends = implode(',',$arrfriends);
								
								//$device_id=$query->row_array();
										
							    return $arrfriends;

	}

function getNotificationMessage($message_id){
	
							$SQL = ' SELECT message  FROM push_notification_message WHERE id= "'.$message_id.'" '  ;

						    $query = $this->db->query($SQL);
						
						    $device_id=$query->row_array();
										
							return $device_id;
							
			}
}