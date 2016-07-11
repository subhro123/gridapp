<?php
if ( ! defined('BASEPATH') )
  exit( 'No direct script access allowed' );
	// DEFINE our cipher

class Gcmengine
{
	
	public function __construct()
    {
        //session_start();
	}
		
		
	public function getGcmPushNotification($message,$android_push_reg_id)
    {
			// Message to be sent
			//$message = $_POST['message'];
			// Set POST variables
			$url = 'https://android.googleapis.com/gcm/send';
			$fields = array(
							'registration_ids'  => array($android_push_reg_id),
							'data'              => array( "message" => $message ),
							 'content_available'    => true,                 
                    		 'priority'              => 'high' 
							);
			
			$headers = array( 
								'Authorization: key=AIzaSyApOYWK7LSM5sW570-ConAkh3CoEhIxJpQ',
								'Content-Type: application/json'
							);
							
	        //echo '<pre>';
			//echo ($android_push_reg_id);
			//die();
			// Open connection
			
			$ch = curl_init();
			
			// Set the url, number of POST vars, POST data
			
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
			
			// Execute post
			$result = curl_exec($ch);
			
			// Close connection
			curl_close($ch);
			
			return $result;
			
		}

}