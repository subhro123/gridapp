<?php

class Response extends CI_Model
{
	public function __construct(){ parent::__construct(); }
	public function outputResponse($status, $error = false, $response = array(), $error_code = false, $addl = array(),$message='')
	{
		$return = array(
			"status" => $status,
			//"error" => $error,
			//"error_code" => $error_code,
			"message"=> $message
		);
		//print_r($return);
		if  ($addl) $return = array_merge($return, $addl);
		
		if (!is_array($response)) $response = array("response" => $response);
		$return += $response;
		header("Content-Type: application/json");
		
		echo trim(json_encode($return));
		exit();
	}
	
	// Wrapper for above function
	public function out($status, $error = false, $response = array(), $error_code = false, $addl = array()) {
		$this->outputResponse($status, $error, $response, $error_code, $addl);
	}
	
	
}

		