<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Functions extends CI_Model
{
	function insert($table,$data)
	{
				  $this->db->insert($table,$data);  
				  //echo $this->db->last_query();
				  //die();
	}
	function update($table,$data,$id,$col)
	{
				 $this->db->where($col, $id);
				 $this->db->update($table, $data);
				 return '1';
				 //echo $this->db->last_query();
	}
	
	function delete($table,$data,$id)
	 {
			 $this->db->where('id', $id);
			 $this->db->update($table, $data);
			 
			 //echo $this->db->last_query(); die();
	 }

	 function appsbee_encode($str){
	 	$strlen = strlen( $str );
	 	$encodedStr = "";
		for( $i = 0; $i <= $strlen; $i++ ) {
		     $code = ord(substr( $str, $i, 1 ));
		     if($code > 0){
		     	$encodedStr .= (int)($code*71/.005)."#";
		     }		     
		}
		$encodedStr = rtrim($encodedStr,"#");
		return $encodedStr;
	 }

	 function appsbee_decode($str,$fileName=NULL){
	 	if($fileName==NULL || $fileName==""){
	 		exit("Blank file-name is not allowed!");
	 	}else{
	 		$filePath = "./".$fileName;
	 		$keyFile = fopen($filePath, "r") or die("Unable to open key-file!");
			$key = fread($keyFile,filesize($filePath));
			fclose($keyFile);
			if($key!="" && $key=="498#2D83B631%3800EBD!801600D*7E3CC13"){
				$arr = explode("#",$str);
				$decodedStr = "";
			 	foreach($arr as $val){
			 		$decodedStr .= chr((int)($val/71*.005));
			 	}
				return $decodedStr;		
			}else{
				exit("Invalid key!");
			}	 		
	 	}
	 }
}

?>